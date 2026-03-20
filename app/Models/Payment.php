<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'client_id', 'case_id', 'pos_transaction_id',
        'amount', 'currency', 'payment_method',
        'payment_date', 'paid_date', 'reference_number', 'reference',
        'status', 'received_by', 'notes',
        // Online gateway fields
        'gateway', 'gateway_payment_id', 'gateway_status',
        'checkout_session_id', 'gateway_metadata',
        'failure_reason', 'refund_amount', 'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'paid_date' => 'date',
            'amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'refunded_at' => 'datetime',
            'gateway_metadata' => 'array',
        ];
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function posTransaction(): BelongsTo { return $this->belongsTo(PosTransaction::class); }
    public function receivedBy(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeCompleted($q) { return $q->where('status', 'completed'); }
    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeByMethod($q, string $m) { return $q->where('payment_method', $m); }
    public function scopeByGateway($q, string $g) { return $q->where('gateway', $g); }
    public function scopeOnline($q) { return $q->whereNotNull('gateway'); }

    // =====================================================
    // HELPERS
    // =====================================================

    public function isOnline(): bool
    {
        return !empty($this->gateway);
    }

    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }

    public function markAsPaid(string $gatewayPaymentId = null, array $metadata = []): self
    {
        $this->update([
            'status' => 'completed',
            'paid_date' => now(),
            'gateway_payment_id' => $gatewayPaymentId ?? $this->gateway_payment_id,
            'gateway_status' => 'paid',
            'gateway_metadata' => array_merge($this->gateway_metadata ?? [], $metadata),
        ]);
        return $this;
    }

    public function markAsFailed(string $reason = null): self
    {
        $this->update([
            'status' => 'failed',
            'gateway_status' => 'failed',
            'failure_reason' => $reason,
        ]);
        return $this;
    }
}
