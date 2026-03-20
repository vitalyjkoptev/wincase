<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'case_number', 'client_id', 'lead_id', 'service_type', 'status',
        'assigned_to', 'priority', 'voivodeship', 'office_name',
        'submission_date', 'decision_date', 'deadline', 'appeal_deadline',
        'documents_required', 'documents_collected', 'progress_percentage',
        'amount', 'currency', 'is_paid', 'notes',
        'completed_at', 'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'decision_date' => 'date',
            'deadline' => 'date',
            'appeal_deadline' => 'date',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
            'documents_required' => 'array',
            'documents_collected' => 'array',
            'amount' => 'decimal:2',
            'is_paid' => 'boolean',
            'progress_percentage' => 'integer',
        ];
    }

    // ---- Relations ----

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'case_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'case_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'case_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'case_id');
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class, 'case_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Message::class, 'case_id');
    }

    // ---- Scopes ----

    public function scopeActive($q)
    {
        return $q->whereNotIn('status', ['completed', 'closed', 'cancelled']);
    }

    public function scopeByStatus($q, string $status)
    {
        return $q->where('status', $status);
    }

    public function scopeByServiceType($q, string $type)
    {
        return $q->where('service_type', $type);
    }

    public function scopeOverdue($q)
    {
        return $q->where('deadline', '<', now())->whereNotIn('status', ['completed', 'closed', 'cancelled']);
    }

    public function scopeAssignedTo($q, int $userId)
    {
        return $q->where('assigned_to', $userId);
    }

    // ---- Boot ----

    protected static function booted(): void
    {
        static::creating(function (self $case) {
            if (empty($case->case_number)) {
                $year = now()->format('Y');
                $last = static::whereYear('created_at', $year)->max('id') ?? 0;
                $case->case_number = sprintf('WC-%s-%04d', $year, $last + 1);
            }
        });
    }
}
