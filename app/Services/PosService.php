<?php

namespace App\Services;

use App\Enums\PaymentMethodEnum;
use App\Enums\PosTransactionStatusEnum;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PosTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosService
{
    // =====================================================
    // STEP 1: Receive payment at office
    // =====================================================

    /**
     * Register a new POS transaction when client pays at office.
     * Receipt number is auto-generated.
     * Transaction stays in staging — does NOT touch CRM yet.
     */
    public function receivePayment(array $data, ?int $operatorId = null): PosTransaction
    {
        return PosTransaction::create([
            'client_name' => $data['client_name'],
            'client_phone' => $data['client_phone'],
            'client_email' => $data['client_email'] ?? null,
            'client_passport' => $data['client_passport'] ?? null,
            'service_type' => $data['service_type'],
            'service_description' => $data['service_description'] ?? null,
            'documents_submitted' => $data['documents_submitted'] ?? null,
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'PLN',
            'tax_rate' => $data['tax_rate'] ?? 23.00,
            'terminal_transaction_id' => $data['terminal_transaction_id'] ?? null,
            'status' => PosTransactionStatusEnum::RECEIVED,
            'received_by' => $operatorId,
        ]);
    }

    // =====================================================
    // STEP 2: Owner reviews and decides
    // =====================================================

    /**
     * Owner APPROVES transaction → calculate tax → ready for CRM.
     */
    public function approveTransaction(
        PosTransaction $transaction,
        int $ownerId,
        ?string $notes = null
    ): PosTransaction {
        $transaction->approve($ownerId, $notes);

        Log::info('POS transaction approved', [
            'id' => $transaction->id,
            'receipt' => $transaction->receipt_number,
            'amount' => $transaction->amount,
            'tax' => $transaction->tax_amount,
            'net' => $transaction->net_amount,
        ]);

        return $transaction->fresh();
    }

    /**
     * Owner REJECTS transaction → mark for refund.
     */
    public function rejectTransaction(
        PosTransaction $transaction,
        int $ownerId,
        string $reason
    ): PosTransaction {
        $transaction->reject($ownerId, $reason);

        Log::info('POS transaction rejected', [
            'id' => $transaction->id,
            'receipt' => $transaction->receipt_number,
            'reason' => $reason,
        ]);

        return $transaction->fresh();
    }

    // =====================================================
    // STEP 3A: Create CRM records (after approval)
    // =====================================================

    /**
     * After approval: create/find client → create invoice → create payment → link to CRM.
     * All in one DB transaction for atomicity.
     */
    public function processApprovedTransaction(
        PosTransaction $transaction,
        ?int $existingClientId = null,
        ?int $caseId = null
    ): PosTransaction {
        if ($transaction->status !== PosTransactionStatusEnum::APPROVED) {
            throw new \InvalidArgumentException(
                "Transaction #{$transaction->id} must be APPROVED before processing. " .
                "Current status: {$transaction->status->value}"
            );
        }

        return DB::transaction(function () use ($transaction, $existingClientId, $caseId) {

            // --- 1. Find or create client ---
            $clientId = $existingClientId;

            if (!$clientId) {
                $client = Client::firstOrCreate(
                    ['phone' => $transaction->client_phone],
                    [
                        'name' => $transaction->client_name,
                        'email' => $transaction->client_email,
                        'passport_number' => $transaction->client_passport,
                        'source' => 'pos_office',
                    ]
                );
                $clientId = $client->id;
            }

            // --- 2. Create Invoice ---
            $invoice = Invoice::create([
                'client_id' => $clientId,
                'case_id' => $caseId,
                'invoice_number' => $this->generateInvoiceNumber(),
                'amount' => $transaction->amount,
                'net_amount' => $transaction->net_amount,
                'tax_amount' => $transaction->tax_amount,
                'tax_rate' => $transaction->tax_rate,
                'currency' => $transaction->currency,
                'status' => 'paid',
                'description' => $transaction->service_description
                    ?? "POS: {$transaction->service_type->label()}",
                'issued_at' => now(),
                'paid_at' => $transaction->created_at,
                'pos_receipt_number' => $transaction->receipt_number,
            ]);

            // --- 3. Create Payment record ---
            $payment = Payment::create([
                'client_id' => $clientId,
                'invoice_id' => $invoice->id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'method' => $transaction->payment_method->value,
                'status' => 'completed',
                'paid_at' => $transaction->created_at,
                'reference' => $transaction->receipt_number,
                'terminal_transaction_id' => $transaction->terminal_transaction_id,
            ]);

            // --- 4. Link POS transaction to CRM ---
            $transaction->linkToCrm(
                clientId: $clientId,
                invoiceId: $invoice->id,
                paymentId: $payment->id,
                caseId: $caseId
            );

            Log::info('POS transaction processed → CRM', [
                'pos_id' => $transaction->id,
                'receipt' => $transaction->receipt_number,
                'client_id' => $clientId,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
            ]);

            return $transaction->fresh();
        });
    }

    // =====================================================
    // STEP 3B: Process refund (after rejection)
    // =====================================================

    /**
     * Mark refund as completed after money returned to client.
     */
    public function processRefund(
        PosTransaction $transaction,
        ?string $refundMethod = null
    ): PosTransaction {
        $transaction->markRefunded($refundMethod);

        Log::info('POS transaction refunded', [
            'id' => $transaction->id,
            'receipt' => $transaction->receipt_number,
            'refund_amount' => $transaction->refund_amount,
            'refund_method' => $refundMethod ?? $transaction->payment_method->value,
        ]);

        return $transaction->fresh();
    }

    // =====================================================
    // REPORTS
    // =====================================================

    /**
     * Get all transactions awaiting owner's decision.
     */
    public function getPendingTransactions(): \Illuminate\Database\Eloquent\Collection
    {
        return PosTransaction::awaitingDecision()
            ->orderBy('created_at', 'asc')
            ->with('receivedByUser')
            ->get();
    }

    /**
     * Dashboard widget data: pending count + amount.
     */
    public function getPendingStats(): array
    {
        return [
            'count' => PosTransaction::pending()->count(),
            'total_amount' => PosTransaction::pending()->sum('amount'),
            'oldest_waiting_hours' => PosTransaction::pending()
                ->oldest()
                ->first()
                ?->waitingHours,
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function generateInvoiceNumber(): string
    {
        $prefix = 'FV';
        $year = now()->format('Y');
        $month = now()->format('m');
        $sequence = str_pad(
            Invoice::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return "{$prefix}/{$year}/{$month}/{$sequence}";
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Сервис PosService — оркестрация полного цикла POS-транзакций.
//
// ПОТОК РАБОТЫ:
//   1. receivePayment() — оператор вводит данные, клиент платит.
//      Receipt auto-generated (WC-260217-0001). CRM НЕ ТРОГАЕТСЯ.
//
//   2. approveTransaction() / rejectTransaction() — владелец решает.
//      При approve: считается VAT 23% (net = amount / 1.23).
//
//   3A. processApprovedTransaction() — ОДНА DB-транзакция:
//       → findOrCreate Client (по телефону)
//       → Create Invoice (FV/2026/02/0001) с налогом
//       → Create Payment (completed)
//       → linkToCrm() — привязка POS → CRM
//       CRM теперь содержит подтверждённый платёж с налогом.
//
//   3B. processRefund() — возврат средств, закрытие транзакции.
//
// ОТЧЁТЫ:
//   getPendingTransactions() — список на рассмотрение владельца
//   getPendingStats() — виджет dashboard (count, total, oldest hours)
//
// INVOICE FORMAT: FV/2026/02/0001 (польский формат faktury VAT)
//
// Файл: app/Services/PosService.php
// ---------------------------------------------------------------
