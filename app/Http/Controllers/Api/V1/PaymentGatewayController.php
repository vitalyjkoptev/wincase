<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Finance\PaymentGatewayService;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Payment Gateway API — checkout, status, webhooks.
 *
 * Routes:
 *   POST   /api/v1/payments/checkout         — Create checkout (Stripe/P24/PayPal)
 *   GET    /api/v1/payments/gateways          — List available gateways
 *   GET    /api/v1/payments/{id}/status        — Check payment status
 *   POST   /api/v1/payments/{id}/refund       — Refund payment
 *   GET    /api/v1/payments/by-invoice/{id}    — Payments for invoice
 *
 * Webhooks (NO auth):
 *   POST   /api/v1/webhooks/stripe            — Stripe webhook
 *   POST   /api/v1/webhooks/przelewy24        — P24 notification
 *   POST   /api/v1/webhooks/paypal            — PayPal webhook
 *   GET    /api/v1/payments/paypal/capture     — PayPal return (capture order)
 */
class PaymentGatewayController extends Controller
{
    protected PaymentGatewayService $service;

    public function __construct(PaymentGatewayService $service)
    {
        $this->service = $service;
    }

    // =====================================================
    // LIST ALL PAYMENTS (for admin panel)
    // =====================================================

    /**
     * GET /api/v1/payments
     * Returns all payments + summary stats for stat cards.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['client:id,first_name,last_name', 'invoice:id,invoice_number', 'case:id,case_number']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }
        if ($request->filled('gateway')) {
            $query->where('gateway', $request->input('gateway'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('id', 'like', "%{$s}%")
                  ->orWhere('reference', 'like', "%{$s}%")
                  // ->orWhere('reference_number', 'like', "%{$s}%")
                  ->orWhere('gateway_payment_id', 'like', "%{$s}%")
                  ->orWhereHas('client', function ($cq) use ($s) {
                      $cq->where('first_name', 'like', "%{$s}%")
                         ->orWhere('last_name', 'like', "%{$s}%");
                  });
            });
        }

        $payments = $query->orderByDesc('created_at')->orderByDesc('id')->get();

        // Stats
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $allPayments = Payment::all();

        $totalReceived = (float) $allPayments->where('status', 'completed')->sum('amount');
        $thisMonth = (float) $allPayments->where('status', 'completed')
            ->where('paid_date', '>=', $monthStart)->sum('amount');
        $pending = $allPayments->where('status', 'pending');
        $pendingAmount = (float) $pending->sum('amount');
        $pendingCount = $pending->count();
        $refunded = $allPayments->whereIn('status', ['refunded', 'partially_refunded']);
        $refundedAmount = (float) $refunded->sum('refund_amount') ?: (float) $refunded->sum('amount');
        $refundedCount = $refunded->count();

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $payments,
                'stats' => [
                    'total_received' => $totalReceived,
                    'this_month' => $thisMonth,
                    'pending_amount' => $pendingAmount,
                    'pending_count' => $pendingCount,
                    'refunded_amount' => $refundedAmount,
                    'refunded_count' => $refundedCount,
                ],
            ],
        ]);
    }

    // =====================================================
    // RECORD PAYMENT MANUALLY (admin panel)
    // =====================================================

    /**
     * POST /api/v1/payments
     * Record a manual (office) payment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'invoice_id' => 'nullable|integer|exists:invoices,id',
            'case_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $method = $request->input('payment_method');
        $isImmediate = in_array($method, ['cash', 'card']);

        $payment = Payment::create([
            'client_id' => $request->input('client_id'),
            'invoice_id' => $request->input('invoice_id'),
            'case_id' => $request->input('case_id'),
            'amount' => $request->input('amount'),
            'currency' => 'PLN',
            'payment_method' => $method,
            'paid_date' => $isImmediate ? now()->toDateString() : null,
            'status' => $isImmediate ? 'completed' : 'pending',
            'reference' => $request->input('reference'),
            'notes' => $request->input('notes'),
            'received_by' => $request->user()->id,
        ]);

        // Auto-update invoice if paid
        if ($payment->status === 'completed' && $payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice) {
                $totalPaid = Payment::where('invoice_id', $invoice->id)
                    ->where('status', 'completed')->sum('amount');
                if ($totalPaid >= $invoice->total_amount) {
                    $invoice->update(['status' => 'paid', 'paid_date' => now()]);
                }
            }
        }

        $payment->load(['client:id,first_name,last_name', 'invoice:id,invoice_number']);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded',
            'data' => $payment,
        ], 201);
    }

    // =====================================================
    // CHECKOUT
    // =====================================================

    /**
     * POST /api/v1/payments/checkout
     *
     * Body (FormData):
     *   invoice_id  (required)
     *   gateway     (required: stripe|przelewy24|paypal)
     *   return_url  (optional)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
            'gateway' => 'required|string|in:stripe,przelewy24,paypal',
            'return_url' => 'nullable|url',
        ]);

        $result = $this->service->createCheckout(
            $request->input('invoice_id'),
            $request->input('gateway'),
            $request->input('return_url')
        );

        $statusCode = $result['success'] ? 200 : 422;
        return response()->json($result, $statusCode);
    }

    // =====================================================
    // AVAILABLE GATEWAYS
    // =====================================================

    /**
     * GET /api/v1/payments/gateways
     */
    public function gateways()
    {
        return response()->json([
            'success' => true,
            'gateways' => $this->service->getAvailableGateways(),
        ]);
    }

    // =====================================================
    // PAYMENT STATUS
    // =====================================================

    /**
     * GET /api/v1/payments/{id}/status
     */
    public function status(int $id)
    {
        $result = $this->service->checkStatus($id);

        return response()->json([
            'success' => true,
            'payment_id' => $id,
            ...$result,
        ]);
    }

    // =====================================================
    // REFUND
    // =====================================================

    /**
     * POST /api/v1/payments/{id}/refund
     */
    public function refund(Request $request, int $id)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
        ]);

        $result = $this->service->refund(
            $id,
            $request->input('amount'),
            $request->input('reason', '')
        );

        $statusCode = $result['success'] ? 200 : 422;
        return response()->json($result, $statusCode);
    }

    // =====================================================
    // PAYMENTS BY INVOICE
    // =====================================================

    /**
     * GET /api/v1/payments/by-invoice/{invoiceId}
     */
    public function byInvoice(int $invoiceId)
    {
        $payments = Payment::where('invoice_id', $invoiceId)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'payments' => $payments,
        ]);
    }

    // =====================================================
    // PAYPAL CAPTURE (return URL callback)
    // =====================================================

    /**
     * GET /api/v1/payments/paypal/capture?token=ORDER_ID
     * Called when PayPal redirects back after approval.
     */
    public function paypalCapture(Request $request)
    {
        $orderId = $request->input('token');

        if (!$orderId) {
            return response()->json(['success' => false, 'error' => 'No order token'], 400);
        }

        $result = $this->service->capturePayPalOrder($orderId);

        return response()->json($result);
    }

    // =====================================================
    // WEBHOOKS (no auth middleware)
    // =====================================================

    /**
     * POST /api/v1/webhooks/stripe
     */
    public function webhookStripe(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature', '');

        $result = $this->service->handleStripeWebhook($payload, $signature);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * POST /api/v1/webhooks/przelewy24
     */
    public function webhookP24(Request $request)
    {
        $result = $this->service->handleP24Webhook($request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * POST /api/v1/webhooks/paypal
     */
    public function webhookPayPal(Request $request)
    {
        $result = $this->service->handlePayPalWebhook($request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
