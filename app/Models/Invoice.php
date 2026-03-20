<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'client_id', 'case_id',
        'issue_date', 'due_date', 'paid_date',
        'net_amount', 'vat_rate', 'vat_amount', 'gross_amount', 'total_amount', 'currency',
        'status', 'payment_method', 'bank_account', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date', 'due_date' => 'date', 'paid_date' => 'date',
            'net_amount' => 'decimal:2', 'vat_rate' => 'decimal:2',
            'vat_amount' => 'decimal:2', 'gross_amount' => 'decimal:2', 'total_amount' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function scopePaid($q) { return $q->where('status', 'paid'); }
    public function scopeOverdue($q) { return $q->where('status', '!=', 'paid')->where('due_date', '<', now()); }
    public function scopePending($q) { return $q->whereIn('status', ['sent', 'overdue']); }

    protected static function booted(): void
    {
        static::creating(function (self $inv) {
            if (empty($inv->invoice_number)) {
                $y = now()->format('Y'); $m = now()->format('m');
                $last = static::whereYear('created_at', $y)->whereMonth('created_at', $m)->max('id') ?? 0;
                $inv->invoice_number = sprintf('FV/%s/%s/%03d', $y, $m, $last + 1);
            }
        });
    }
}
