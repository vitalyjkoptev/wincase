<?php

namespace App\Services\Finance;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Unified payment gateway service for Stripe, Przelewy24, PayPal.
 *
 * Flow:
 *   1. createCheckout($invoiceId, $gateway) → returns redirect URL
 *   2. User pays on gateway page
 *   3. Webhook confirms payment → handleWebhook()
 *   4. Payment status updated in DB
 *
 * All gateways use REST API (no SDKs — works on shared hosting/cPanel).
 */
class PaymentGatewayService
{
    // =====================================================
    // CREATE CHECKOUT (unified entry point)
    // =====================================================

    /**
     * Create a checkout session for given invoice.
     *
     * @return array{success: bool, redirect_url?: string, payment_id?: int, error?: string}
     */
    public function createCheckout(int $invoiceId, string $gateway, ?string $returnUrl = null): array
    {
        $invoice = Invoice::with('client')->findOrFail($invoiceId);

        if ($invoice->status === 'paid') {
            return ['success' => false, 'error' => 'Invoice already paid'];
        }

        $returnUrl = $returnUrl ?: url('/payment/status');

        return match ($gateway) {
            'stripe' => $this->createStripeCheckout($invoice, $returnUrl),
            'przelewy24' => $this->createP24Checkout($invoice, $returnUrl),
            'paypal' => $this->createPayPalCheckout($invoice, $returnUrl),
            default => ['success' => false, 'error' => "Unknown gateway: {$gateway}"],
        };
    }

    // =====================================================
    // STRIPE
    // =====================================================

    protected function createStripeCheckout(Invoice $invoice, string $returnUrl): array
    {
        $secret = config('services.stripe.secret');
        if (!$secret) {
            return ['success' => false, 'error' => 'Stripe not configured'];
        }

        try {
            $response = Http::withBasicAuth($secret, '')
                ->asForm()
                ->post('https://api.stripe.com/v1/checkout/sessions', [
                    'mode' => 'payment',
                    'currency' => config('services.stripe.currency', 'pln'),
                    'line_items[0][price_data][currency]' => config('services.stripe.currency', 'pln'),
                    'line_items[0][price_data][unit_amount]' => (int) ($invoice->total_amount * 100), // cents
                    'line_items[0][price_data][product_data][name]' => "Invoice {$invoice->invoice_number}",
                    'line_items[0][price_data][product_data][description]' => "WinCase - {$invoice->notes}",
                    'line_items[0][quantity]' => 1,
                    'customer_email' => $invoice->client?->email,
                    'metadata[invoice_id]' => $invoice->id,
                    'metadata[invoice_number]' => $invoice->invoice_number,
                    'success_url' => $returnUrl . '?status=success&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $returnUrl . '?status=cancelled&invoice_id=' . $invoice->id,
                ]);

            if (!$response->successful()) {
                Log::error('Stripe checkout failed', ['body' => $response->json()]);
                return ['success' => false, 'error' => $response->json('error.message') ?? 'Stripe error'];
            }

            $session = $response->json();

            // Create pending payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'case_id' => $invoice->case_id,
                'amount' => $invoice->total_amount,
                'currency' => config('services.stripe.currency', 'PLN'),
                'payment_method' => 'card',
                'status' => 'pending',
                'gateway' => 'stripe',
                'checkout_session_id' => $session['id'],
                'gateway_status' => 'created',
            ]);

            return [
                'success' => true,
                'redirect_url' => $session['url'],
                'payment_id' => $payment->id,
                'session_id' => $session['id'],
            ];

        } catch (\Throwable $e) {
            Log::error('Stripe checkout exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Handle Stripe webhook event.
     */
    public function handleStripeWebhook(string $payload, string $signature): array
    {
        $secret = config('services.stripe.webhook_secret');

        // Verify signature
        if ($secret) {
            $timestamp = null;
            $sigs = [];
            foreach (explode(',', $signature) as $part) {
                [$key, $val] = explode('=', trim($part), 2);
                if ($key === 't') $timestamp = $val;
                if ($key === 'v1') $sigs[] = $val;
            }

            $expectedSig = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);
            if (!in_array($expectedSig, $sigs)) {
                Log::warning('Stripe webhook: invalid signature');
                return ['success' => false, 'error' => 'Invalid signature'];
            }
        }

        $event = json_decode($payload, true);
        $type = $event['type'] ?? '';

        Log::info('Stripe webhook received', ['type' => $type]);

        if ($type === 'checkout.session.completed') {
            $session = $event['data']['object'] ?? [];
            $sessionId = $session['id'] ?? '';
            $paymentIntent = $session['payment_intent'] ?? '';

            $payment = Payment::where('checkout_session_id', $sessionId)->first();

            if ($payment && $payment->status !== 'completed') {
                $payment->markAsPaid($paymentIntent, [
                    'stripe_session_id' => $sessionId,
                    'stripe_payment_intent' => $paymentIntent,
                    'stripe_customer' => $session['customer'] ?? null,
                ]);

                // Update invoice
                $this->markInvoicePaid($payment);

                Log::info('Stripe payment completed', ['payment_id' => $payment->id]);
            }

            return ['success' => true, 'action' => 'payment_completed'];
        }

        if ($type === 'checkout.session.expired') {
            $sessionId = $event['data']['object']['id'] ?? '';
            $payment = Payment::where('checkout_session_id', $sessionId)->first();
            if ($payment) {
                $payment->markAsFailed('Session expired');
            }
            return ['success' => true, 'action' => 'session_expired'];
        }

        return ['success' => true, 'action' => 'ignored'];
    }

    // =====================================================
    // PRZELEWY24
    // =====================================================

    protected function createP24Checkout(Invoice $invoice, string $returnUrl): array
    {
        $merchantId = config('services.przelewy24.merchant_id');
        $posId = config('services.przelewy24.pos_id') ?: $merchantId;
        $apiKey = config('services.przelewy24.api_key');
        $crc = config('services.przelewy24.crc');
        $sandbox = config('services.przelewy24.sandbox', false);

        if (!$merchantId || !$apiKey || !$crc) {
            return ['success' => false, 'error' => 'Przelewy24 not configured'];
        }

        $baseUrl = $sandbox
            ? 'https://sandbox.przelewy24.pl'
            : 'https://secure.przelewy24.pl';

        $sessionId = 'WC-' . $invoice->id . '-' . time();
        $amountInGrosze = (int) ($invoice->total_amount * 100);

        // P24 signature: sha384( json{"sessionId","merchantId","amount","currency","crc"} )
        $signData = json_encode([
            'sessionId' => $sessionId,
            'merchantId' => (int) $merchantId,
            'amount' => $amountInGrosze,
            'currency' => 'PLN',
            'crc' => $crc,
        ]);
        $sign = hash('sha384', $signData);

        try {
            $response = Http::withBasicAuth($posId, $apiKey)
                ->post("{$baseUrl}/api/v1/transaction/register", [
                    'merchantId' => (int) $merchantId,
                    'posId' => (int) $posId,
                    'sessionId' => $sessionId,
                    'amount' => $amountInGrosze,
                    'currency' => 'PLN',
                    'description' => "Faktura {$invoice->invoice_number} - WinCase",
                    'email' => $invoice->client?->email ?? '',
                    'country' => 'PL',
                    'language' => 'pl',
                    'urlReturn' => $returnUrl . '?status=p24_return&invoice_id=' . $invoice->id,
                    'urlStatus' => url('/api/v1/webhooks/przelewy24'),
                    'sign' => $sign,
                ]);

            if (!$response->successful()) {
                Log::error('P24 register failed', ['body' => $response->json()]);
                return ['success' => false, 'error' => $response->json('error') ?? 'P24 error'];
            }

            $data = $response->json('data') ?? $response->json();
            $token = $data['token'] ?? null;

            if (!$token) {
                return ['success' => false, 'error' => 'No token from P24'];
            }

            // Create pending payment
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'case_id' => $invoice->case_id,
                'amount' => $invoice->total_amount,
                'currency' => 'PLN',
                'payment_method' => 'przelewy24',
                'status' => 'pending',
                'gateway' => 'przelewy24',
                'gateway_payment_id' => $sessionId,
                'checkout_session_id' => $token,
                'gateway_status' => 'registered',
            ]);

            $redirectUrl = "{$baseUrl}/trnRequest/{$token}";

            return [
                'success' => true,
                'redirect_url' => $redirectUrl,
                'payment_id' => $payment->id,
                'token' => $token,
            ];

        } catch (\Throwable $e) {
            Log::error('P24 checkout exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Handle Przelewy24 status notification.
     */
    public function handleP24Webhook(array $data): array
    {
        $merchantId = config('services.przelewy24.merchant_id');
        $posId = config('services.przelewy24.pos_id') ?: $merchantId;
        $apiKey = config('services.przelewy24.api_key');
        $crc = config('services.przelewy24.crc');
        $sandbox = config('services.przelewy24.sandbox', false);

        $baseUrl = $sandbox
            ? 'https://sandbox.przelewy24.pl'
            : 'https://secure.przelewy24.pl';

        $sessionId = $data['sessionId'] ?? '';
        $orderId = $data['orderId'] ?? '';
        $amount = $data['amount'] ?? 0;

        Log::info('P24 webhook received', $data);

        $payment = Payment::where('gateway', 'przelewy24')
            ->where('gateway_payment_id', $sessionId)
            ->first();

        if (!$payment) {
            Log::warning('P24 webhook: payment not found', ['sessionId' => $sessionId]);
            return ['success' => false, 'error' => 'Payment not found'];
        }

        // Verify transaction with P24
        $signData = json_encode([
            'sessionId' => $sessionId,
            'orderId' => (int) $orderId,
            'amount' => (int) $amount,
            'currency' => 'PLN',
            'crc' => $crc,
        ]);
        $sign = hash('sha384', $signData);

        $verifyResponse = Http::withBasicAuth($posId, $apiKey)
            ->put("{$baseUrl}/api/v1/transaction/verify", [
                'merchantId' => (int) $merchantId,
                'posId' => (int) $posId,
                'sessionId' => $sessionId,
                'amount' => (int) $amount,
                'currency' => 'PLN',
                'orderId' => (int) $orderId,
                'sign' => $sign,
            ]);

        if ($verifyResponse->successful() && ($verifyResponse->json('data.status') === 'success' || $verifyResponse->status() === 200)) {
            $payment->markAsPaid($orderId, [
                'p24_order_id' => $orderId,
                'p24_session_id' => $sessionId,
                'p24_statement' => $data['statement'] ?? null,
            ]);

            $this->markInvoicePaid($payment);

            return ['success' => true, 'action' => 'payment_verified'];
        }

        Log::error('P24 verification failed', ['response' => $verifyResponse->json()]);
        $payment->markAsFailed('P24 verification failed');

        return ['success' => false, 'error' => 'Verification failed'];
    }

    // =====================================================
    // PAYPAL
    // =====================================================

    protected function createPayPalCheckout(Invoice $invoice, string $returnUrl): array
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $apiUrl = config('services.paypal.api_url');

        if (!$clientId || !$secret) {
            return ['success' => false, 'error' => 'PayPal not configured'];
        }

        try {
            // 1. Get access token
            $tokenResponse = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("{$apiUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$tokenResponse->successful()) {
                return ['success' => false, 'error' => 'PayPal auth failed'];
            }

            $accessToken = $tokenResponse->json('access_token');

            // 2. Create order
            $orderResponse = Http::withToken($accessToken)
                ->post("{$apiUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => (string) $invoice->id,
                        'description' => "Invoice {$invoice->invoice_number} - WinCase",
                        'amount' => [
                            'currency_code' => config('services.paypal.currency', 'PLN'),
                            'value' => number_format($invoice->total_amount, 2, '.', ''),
                        ],
                        'custom_id' => "INV-{$invoice->id}",
                    ]],
                    'payment_source' => [
                        'paypal' => [
                            'experience_context' => [
                                'brand_name' => 'WinCase Legalization',
                                'return_url' => $returnUrl . '?status=paypal_approved&invoice_id=' . $invoice->id,
                                'cancel_url' => $returnUrl . '?status=cancelled&invoice_id=' . $invoice->id,
                                'user_action' => 'PAY_NOW',
                                'landing_page' => 'LOGIN',
                            ],
                        ],
                    ],
                ]);

            if (!$orderResponse->successful()) {
                Log::error('PayPal order create failed', ['body' => $orderResponse->json()]);
                return ['success' => false, 'error' => $orderResponse->json('message') ?? 'PayPal error'];
            }

            $order = $orderResponse->json();
            $orderId = $order['id'] ?? null;

            // Find redirect URL from links
            $redirectUrl = null;
            foreach ($order['links'] ?? [] as $link) {
                if (($link['rel'] ?? '') === 'payer-action') {
                    $redirectUrl = $link['href'];
                    break;
                }
            }

            if (!$redirectUrl) {
                return ['success' => false, 'error' => 'No PayPal redirect URL'];
            }

            // Create pending payment
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'case_id' => $invoice->case_id,
                'amount' => $invoice->total_amount,
                'currency' => config('services.paypal.currency', 'PLN'),
                'payment_method' => 'paypal',
                'status' => 'pending',
                'gateway' => 'paypal',
                'gateway_payment_id' => $orderId,
                'gateway_status' => 'created',
            ]);

            return [
                'success' => true,
                'redirect_url' => $redirectUrl,
                'payment_id' => $payment->id,
                'order_id' => $orderId,
            ];

        } catch (\Throwable $e) {
            Log::error('PayPal checkout exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Capture PayPal order after user approves.
     */
    public function capturePayPalOrder(string $orderId): array
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $apiUrl = config('services.paypal.api_url');

        try {
            // Get token
            $tokenResponse = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("{$apiUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

            $accessToken = $tokenResponse->json('access_token');

            // Capture
            $captureResponse = Http::withToken($accessToken)
                ->withHeaders(['Prefer' => 'return=representation'])
                ->post("{$apiUrl}/v2/checkout/orders/{$orderId}/capture", []);

            if (!$captureResponse->successful()) {
                return ['success' => false, 'error' => 'PayPal capture failed'];
            }

            $order = $captureResponse->json();
            $status = $order['status'] ?? '';

            $payment = Payment::where('gateway', 'paypal')
                ->where('gateway_payment_id', $orderId)
                ->first();

            if ($payment && $status === 'COMPLETED') {
                $capture = $order['purchase_units'][0]['payments']['captures'][0] ?? [];

                $payment->markAsPaid($orderId, [
                    'paypal_order_id' => $orderId,
                    'paypal_capture_id' => $capture['id'] ?? null,
                    'paypal_payer' => $order['payer'] ?? [],
                ]);

                $this->markInvoicePaid($payment);

                return ['success' => true, 'action' => 'payment_captured'];
            }

            return ['success' => false, 'error' => "PayPal status: {$status}"];

        } catch (\Throwable $e) {
            Log::error('PayPal capture exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Handle PayPal webhook notification.
     */
    public function handlePayPalWebhook(array $event): array
    {
        $eventType = $event['event_type'] ?? '';

        Log::info('PayPal webhook received', ['type' => $eventType]);

        if ($eventType === 'CHECKOUT.ORDER.APPROVED') {
            $orderId = $event['resource']['id'] ?? '';
            return $this->capturePayPalOrder($orderId);
        }

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $orderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? '';
            $captureId = $event['resource']['id'] ?? '';

            $payment = Payment::where('gateway', 'paypal')
                ->where('gateway_payment_id', $orderId)
                ->first();

            if ($payment && $payment->status !== 'completed') {
                $payment->markAsPaid($orderId, [
                    'paypal_capture_id' => $captureId,
                    'paypal_amount' => $event['resource']['amount'] ?? [],
                ]);
                $this->markInvoicePaid($payment);
            }

            return ['success' => true, 'action' => 'capture_completed'];
        }

        return ['success' => true, 'action' => 'ignored'];
    }

    // =====================================================
    // REFUND (unified)
    // =====================================================

    /**
     * Refund a completed payment (full or partial).
     */
    public function refund(int $paymentId, ?float $amount = null, string $reason = ''): array
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== 'completed') {
            return ['success' => false, 'error' => 'Payment not completed'];
        }

        $refundAmount = $amount ?? $payment->amount;

        return match ($payment->gateway) {
            'stripe' => $this->refundStripe($payment, $refundAmount, $reason),
            'przelewy24' => $this->refundP24($payment, $refundAmount, $reason),
            'paypal' => $this->refundPayPal($payment, $refundAmount, $reason),
            default => ['success' => false, 'error' => "Refund not supported for gateway: {$payment->gateway}"],
        };
    }

    protected function refundStripe(Payment $payment, float $amount, string $reason): array
    {
        $secret = config('services.stripe.secret');
        $amountInCents = (int) ($amount * 100);

        $response = Http::withBasicAuth($secret, '')
            ->asForm()
            ->post('https://api.stripe.com/v1/refunds', [
                'payment_intent' => $payment->gateway_payment_id,
                'amount' => $amountInCents,
                'reason' => 'requested_by_customer',
            ]);

        if ($response->successful()) {
            $payment->update([
                'refund_amount' => $amount,
                'refunded_at' => now(),
                'gateway_status' => 'refunded',
                'status' => $amount >= $payment->amount ? 'refunded' : 'partially_refunded',
            ]);
            return ['success' => true, 'refund_id' => $response->json('id')];
        }

        return ['success' => false, 'error' => $response->json('error.message') ?? 'Stripe refund failed'];
    }

    protected function refundP24(Payment $payment, float $amount, string $reason): array
    {
        // P24 refunds done via merchant panel — just mark in DB
        $payment->update([
            'refund_amount' => $amount,
            'refunded_at' => now(),
            'gateway_status' => 'refund_pending',
            'status' => 'refund_pending',
            'notes' => ($payment->notes ? $payment->notes . "\n" : '') . "Refund requested: {$amount} PLN. {$reason}",
        ]);

        return ['success' => true, 'note' => 'P24 refund must be processed in merchant panel'];
    }

    protected function refundPayPal(Payment $payment, float $amount, string $reason): array
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $apiUrl = config('services.paypal.api_url');

        $tokenResponse = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post("{$apiUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        $accessToken = $tokenResponse->json('access_token');

        $captureId = $payment->gateway_metadata['paypal_capture_id'] ?? null;
        if (!$captureId) {
            return ['success' => false, 'error' => 'No PayPal capture ID for refund'];
        }

        $response = Http::withToken($accessToken)
            ->post("{$apiUrl}/v2/payments/captures/{$captureId}/refund", [
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency_code' => $payment->currency,
                ],
                'note_to_payer' => $reason ?: 'Refund from WinCase',
            ]);

        if ($response->successful()) {
            $payment->update([
                'refund_amount' => $amount,
                'refunded_at' => now(),
                'gateway_status' => 'refunded',
                'status' => $amount >= $payment->amount ? 'refunded' : 'partially_refunded',
            ]);
            return ['success' => true, 'refund_id' => $response->json('id')];
        }

        return ['success' => false, 'error' => $response->json('message') ?? 'PayPal refund failed'];
    }

    // =====================================================
    // CHECK PAYMENT STATUS (poll)
    // =====================================================

    public function checkStatus(int $paymentId): array
    {
        $payment = Payment::findOrFail($paymentId);

        if (!$payment->gateway || $payment->status === 'completed') {
            return ['status' => $payment->status, 'gateway' => $payment->gateway];
        }

        return match ($payment->gateway) {
            'stripe' => $this->checkStripeStatus($payment),
            'paypal' => $this->checkPayPalStatus($payment),
            default => ['status' => $payment->status],
        };
    }

    protected function checkStripeStatus(Payment $payment): array
    {
        $secret = config('services.stripe.secret');

        $response = Http::withBasicAuth($secret, '')
            ->get("https://api.stripe.com/v1/checkout/sessions/{$payment->checkout_session_id}");

        if ($response->successful()) {
            $session = $response->json();
            $status = $session['payment_status'] ?? 'unknown';

            if ($status === 'paid' && $payment->status !== 'completed') {
                $payment->markAsPaid($session['payment_intent'] ?? null);
                $this->markInvoicePaid($payment);
            }

            return ['status' => $status, 'gateway' => 'stripe'];
        }

        return ['status' => $payment->status, 'gateway' => 'stripe'];
    }

    protected function checkPayPalStatus(Payment $payment): array
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $apiUrl = config('services.paypal.api_url');

        $tokenResponse = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post("{$apiUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        $accessToken = $tokenResponse->json('access_token');

        $response = Http::withToken($accessToken)
            ->get("{$apiUrl}/v2/checkout/orders/{$payment->gateway_payment_id}");

        if ($response->successful()) {
            $status = $response->json('status') ?? 'unknown';
            return ['status' => $status, 'gateway' => 'paypal'];
        }

        return ['status' => $payment->status, 'gateway' => 'paypal'];
    }

    // =====================================================
    // AVAILABLE GATEWAYS
    // =====================================================

    /**
     * Return list of configured/available gateways.
     */
    public function getAvailableGateways(): array
    {
        $gateways = [];

        if (config('services.stripe.secret')) {
            $gateways[] = [
                'id' => 'stripe',
                'name' => 'Stripe',
                'description' => 'Card payment (Visa, Mastercard, BLIK)',
                'icon' => 'ri-bank-card-line',
                'methods' => ['card', 'blik'],
                'currency' => config('services.stripe.currency', 'PLN'),
            ];
        }

        if (config('services.przelewy24.merchant_id') && config('services.przelewy24.crc')) {
            $gateways[] = [
                'id' => 'przelewy24',
                'name' => 'Przelewy24',
                'description' => 'Bank transfer, BLIK, card (Polish banks)',
                'icon' => 'ri-bank-line',
                'methods' => ['bank_transfer', 'blik', 'card'],
                'currency' => 'PLN',
            ];
        }

        if (config('services.paypal.client_id') && config('services.paypal.secret')) {
            $gateways[] = [
                'id' => 'paypal',
                'name' => 'PayPal',
                'description' => 'PayPal account or card',
                'icon' => 'ri-paypal-line',
                'methods' => ['paypal', 'card'],
                'currency' => config('services.paypal.currency', 'PLN'),
            ];
        }

        return $gateways;
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function markInvoicePaid(Payment $payment): void
    {
        if (!$payment->invoice_id) return;

        try {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice && $invoice->status !== 'paid') {
                $invoice->update([
                    'status' => 'paid',
                    'paid_date' => now(),
                    'payment_method' => $payment->gateway,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to mark invoice as paid', ['error' => $e->getMessage()]);
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// PaymentGatewayService — единый сервис для Stripe, Przelewy24, PayPal.
// createCheckout() → redirect URL. Webhooks → markAsPaid().
// Refund: Stripe (API), PayPal (API), P24 (manual через панель).
// NO SDK — чистые HTTP запросы (работает на shared hosting/cPanel).
// Файл: app/Services/Finance/PaymentGatewayService.php
// ---------------------------------------------------------------
