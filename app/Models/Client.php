<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'name', 'email', 'phone',
        'nationality', 'passport_number', 'pesel', 'date_of_birth',
        'address', 'city', 'postal_code', 'voivodeship',
        'preferred_language', 'status',
        'lead_id', 'assigned_to',
        'company_name', 'nip', 'gdpr_consent', 'notes',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gdpr_consent' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function assignedManager(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function cases(): HasMany { return $this->hasMany(ClientCase::class); }
    public function documents(): HasMany { return $this->hasMany(Document::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function tasks(): HasMany { return $this->hasMany(Task::class); }
    public function messages(): HasMany { return $this->hasMany(Message::class); }
    public function verifications(): HasMany { return $this->hasMany(ClientVerification::class); }

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeArchived($q) { return $q->where('status', 'archived'); }
    public function scopeByNationality($q, string $nat) { return $q->where('nationality', $nat); }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: ($this->name ?? '');
    }
}
