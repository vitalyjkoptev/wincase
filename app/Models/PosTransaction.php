<?php

namespace App\Models;

use App\Enums\PaymentMethodEnum;
use App\Enums\PosTransactionStatusEnum;
use App\Enums\ServiceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PosTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_number',
        'terminal_transaction_id',
        'client_name',
        'client_phone',
        'client_email',
        'client_passport',
        'service_type',
        'service_description',
        'documents_submitted',
        'payment_method',
        'amount',
        'currency',
        'tax_rate',
        'tax_amount',
        'net_amount',
        'status',
        'decision_notes',
        'decided_by',
        'decided_at',
        'refund_amount',
        'refund_method',
        'refund_reason',
        'refunded_at',
        'client_id',
        'case_id',
        'invoice_id',
        'payment_id',
        'received_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => PosTransactionStatusEnum::class,
            'payment_method' => PaymentMethodEnum::class,
            'service_type' => ServiceTypeEnum::class,
            'documents_submitted' => 'array',
            'amount' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'decided_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    // =====================================================
    // BOOT — Auto-generate receipt number
    // =====================================================

    protected static function booted(): void
    {
        static::creating(function (PosTransaction $transaction) {
            if (empty($transaction->receipt_number)) {
                $transaction->receipt_number = static::generateReceiptNumber();
            }
        });
    }

    public static function generateReceiptNumber(): string
    {
        $prefix = 'WC';
        $date = now()->format('ymd');
        $sequence = str_pad(
            static::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return "{$prefix}-{$date}-{$sequence}";
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function decidedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopePending($query)
    {
        return $query->whereIn('status', [
            PosTransactionStatusEnum::RECEIVED,
            PosTransactionStatusEnum::UNDER_REVIEW,
        ]);
    }

    public function scopeAwaitingDecision($query)
    {
        return $query->whereNull('decided_at')
                     ->whereIn('status', [
                         PosTransactionStatusEnum::RECEIVED,
                         PosTransactionStatusEnum::UNDER_REVIEW,
                     ]);
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', [
            PosTransactionStatusEnum::APPROVED,
            PosTransactionStatusEnum::INVOICED,
        ]);
    }

    public function scopeRejected($query)
    {
        return $query->whereIn('status', [
            PosTransactionStatusEnum::REJECTED,
            PosTransactionStatusEnum::REFUNDED,
        ]);
    }

    public function scopeFinalized($query)
    {
        return $query->whereIn('status', [
            PosTransactionStatusEnum::INVOICED,
            PosTransactionStatusEnum::REFUNDED,
        ]);
    }

    public function scopeByMethod($query, PaymentMethodEnum $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeCash($query)
    {
        return $query->where('payment_method', PaymentMethodEnum::CASH);
    }

    public function scopeCardTerminal($query)
    {
        return $query->where('payment_method', PaymentMethodEnum::CARD_TERMINAL);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getIsAwaitingDecisionAttribute(): bool
    {
        return $this->status->isActive();
    }

    public function getIsFinalizedAttribute(): bool
    {
        return $this->status->isFinalized();
    }

    public function getIsLinkedToCrmAttribute(): bool
    {
        return $this->client_id !== null && $this->invoice_id !== null;
    }

    public function getWaitingHoursAttribute(): ?float
    {
        if ($this->decided_at) {
            return null;
        }

        return round($this->created_at->diffInHours(now()), 1);
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2, ',', ' ') . ' ' . $this->currency;
    }

    // =====================================================
    // BUSINESS METHODS — Decision Flow
    // =====================================================

    /**
     * Owner marks transaction for review.
     */
    public function markForReview(): void
    {
        $this->guardTransition(PosTransactionStatusEnum::UNDER_REVIEW);

        $this->update([
            'status' => PosTransactionStatusEnum::UNDER_REVIEW,
        ]);
    }

    /**
     * Owner APPROVES the transaction — will create invoice next.
     * Tax is calculated at this point.
     */
    public function approve(int $decidedBy, ?string $notes = null): void
    {
        $this->guardTransition(PosTransactionStatusEnum::APPROVED);

        $netAmount = round($this->amount / (1 + $this->tax_rate / 100), 2);
        $taxAmount = round($this->amount - $netAmount, 2);

        $this->update([
            'status' => PosTransactionStatusEnum::APPROVED,
            'decided_by' => $decidedBy,
            'decided_at' => now(),
            'decision_notes' => $notes,
            'net_amount' => $netAmount,
            'tax_amount' => $taxAmount,
        ]);
    }

    /**
     * After approval — link to CRM (client, case, invoice, payment).
     * This is the moment payment officially enters CRM.
     */
    public function linkToCrm(
        int $clientId,
        int $invoiceId,
        int $paymentId,
        ?int $caseId = null
    ): void {
        $this->guardTransition(PosTransactionStatusEnum::INVOICED);

        $this->update([
            'status' => PosTransactionStatusEnum::INVOICED,
            'client_id' => $clientId,
            'case_id' => $caseId,
            'invoice_id' => $invoiceId,
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * Owner REJECTS the transaction — pending refund.
     */
    public function reject(int $decidedBy, string $reason): void
    {
        $this->guardTransition(PosTransactionStatusEnum::REJECTED);

        $this->update([
            'status' => PosTransactionStatusEnum::REJECTED,
            'decided_by' => $decidedBy,
            'decided_at' => now(),
            'decision_notes' => $reason,
            'refund_amount' => $this->amount,
        ]);
    }

    /**
     * Refund has been processed — transaction is closed.
     */
    public function markRefunded(?string $refundMethod = null): void
    {
        $this->guardTransition(PosTransactionStatusEnum::REFUNDED);

        $this->update([
            'status' => PosTransactionStatusEnum::REFUNDED,
            'refund_method' => $refundMethod ?? $this->payment_method->value,
            'refunded_at' => now(),
        ]);
    }

    /**
     * Guard: ensure status transition is allowed.
     */
    protected function guardTransition(PosTransactionStatusEnum $newStatus): void
    {
        $allowed = $this->status->allowedTransitions();

        if (!in_array($newStatus, $allowed)) {
            throw new \InvalidArgumentException(
                "Cannot transition POS transaction #{$this->id} " .
                "from [{$this->status->value}] to [{$newStatus->value}]. " .
                "Allowed: [" . implode(', ', array_map(fn ($s) => $s->value, $allowed)) . "]"
            );
        }
    }

    // =====================================================
    // STATIC AGGREGATIONS — Reports
    // =====================================================

    /**
     * Daily summary: total received, approved, rejected, refunded.
     */
    public static function dailySummary(?string $date = null): array
    {
        $date = $date ?? today()->toDateString();

        $query = static::whereDate('created_at', $date);

        return [
            'date' => $date,
            'total_transactions' => (clone $query)->count(),
            'total_amount' => (clone $query)->sum('amount'),

            'pending_count' => (clone $query)->pending()->count(),
            'pending_amount' => (clone $query)->pending()->sum('amount'),

            'approved_count' => (clone $query)->approved()->count(),
            'approved_amount' => (clone $query)->approved()->sum('amount'),
            'approved_tax' => (clone $query)->approved()->sum('tax_amount'),
            'approved_net' => (clone $query)->approved()->sum('net_amount'),

            'rejected_count' => (clone $query)->rejected()->count(),
            'rejected_amount' => (clone $query)->rejected()->sum('amount'),
            'refunded_amount' => (clone $query)->rejected()->sum('refund_amount'),

            'cash_amount' => (clone $query)->cash()->sum('amount'),
            'card_amount' => (clone $query)->cardTerminal()->sum('amount'),
        ];
    }

    /**
     * Monthly tax report: all invoiced transactions with tax breakdown.
     */
    public static function monthlyTaxReport(int $year, int $month): array
    {
        $query = static::where('status', PosTransactionStatusEnum::INVOICED)
            ->whereYear('decided_at', $year)
            ->whereMonth('decided_at', $month);

        return [
            'year' => $year,
            'month' => $month,
            'total_gross' => $query->sum('amount'),
            'total_net' => $query->sum('net_amount'),
            'total_vat' => $query->sum('tax_amount'),
            'transaction_count' => $query->count(),
            'by_service' => (clone $query)
                ->selectRaw('service_type, COUNT(*) as count, SUM(amount) as gross, SUM(net_amount) as net, SUM(tax_amount) as vat')
                ->groupBy('service_type')
                ->get()
                ->toArray(),
            'by_method' => (clone $query)
                ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('payment_method')
                ->get()
                ->toArray(),
        ];
    }

    /**
     * Cash flow report: cash vs card vs refunds.
     */
    public static function cashFlowReport(string $from, string $to): array
    {
        return [
            'period' => ['from' => $from, 'to' => $to],
            'income' => [
                'total' => static::approved()->whereBetween('decided_at', [$from, $to])->sum('amount'),
                'cash' => static::approved()->cash()->whereBetween('decided_at', [$from, $to])->sum('amount'),
                'card' => static::approved()->cardTerminal()->whereBetween('decided_at', [$from, $to])->sum('amount'),
            ],
            'tax' => [
                'total_vat' => static::where('status', PosTransactionStatusEnum::INVOICED)
                    ->whereBetween('decided_at', [$from, $to])->sum('tax_amount'),
            ],
            'refunds' => [
                'total' => static::refunded()->whereBetween('refunded_at', [$from, $to])->sum('refund_amount'),
                'count' => static::refunded()->whereBetween('refunded_at', [$from, $to])->count(),
            ],
            'pending' => [
                'total' => static::pending()->sum('amount'),
                'count' => static::pending()->count(),
            ],
        ];
    }

    // =====================================================
    // SCOPE ALIAS for refunded
    // =====================================================

    public function scopeRefunded($query)
    {
        return $query->where('status', PosTransactionStatusEnum::REFUNDED);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель PosTransaction — STAGING-зона платежей (POS-терминал + наличные).
//
// БИЗНЕС-ПОТОК:
//   1. Оператор создаёт запись → auto receipt_number (WC-260217-0001)
//   2. Статус RECEIVED → ожидает решения владельца
//   3. Владелец markForReview() → UNDER_REVIEW (опционально)
//   4А. Владелец approve() → APPROVED → рассчитывается VAT 23%
//       → linkToCrm() → INVOICED (создаётся invoice+payment в CRM)
//   4Б. Владелец reject() → REJECTED → markRefunded() → REFUNDED
//
// ЗАЩИТА: guardTransition() — запрещает невалидные переходы статусов.
// Например, нельзя INVOICED → REJECTED.
//
// ОТЧЁТЫ:
//   dailySummary() — сводка дня (pending/approved/rejected/cash/card)
//   monthlyTaxReport() — налоговый отчёт (gross/net/VAT по услугам и методам)
//   cashFlowReport() — денежный поток за период (доход/налог/возвраты)
//
// CRM-СВЯЗИ: client_id, case_id, invoice_id, payment_id — заполняются
// ТОЛЬКО при linkToCrm() после approve(). До этого — NULL.
// CRM остаётся чистой от неподтверждённых транзакций.
//
// Файл: app/Models/PosTransaction.php
// ---------------------------------------------------------------
