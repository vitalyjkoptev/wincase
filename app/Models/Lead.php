<?php

namespace App\Models;

use App\Enums\LeadSourceEnum;
use App\Enums\LeadStatusEnum;
use App\Enums\PriorityEnum;
use App\Enums\ServiceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'service_type',
        'message',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'gclid',
        'fbclid',
        'ttclid',
        'landing_page',
        'language',
        'device',
        'ip_address',
        'country',
        'city',
        'status',
        'assigned_to',
        'priority',
        'notes',
        'first_contact_at',
        'consultation_at',
        'converted_at',
        'client_id',
        'case_id',
        'gdpr_consent',
        'gdpr_consent_at',
    ];

    protected function casts(): array
    {
        return [
            'source' => LeadSourceEnum::class,
            'status' => LeadStatusEnum::class,
            'priority' => PriorityEnum::class,
            'service_type' => ServiceTypeEnum::class,
            'gdpr_consent' => 'boolean',
            'gdpr_consent_at' => 'datetime',
            'first_contact_at' => 'datetime',
            'consultation_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function assignedManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            LeadStatusEnum::NEW,
            LeadStatusEnum::CONTACTED,
            LeadStatusEnum::CONSULTATION,
            LeadStatusEnum::CONTRACT,
        ]);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', [
            LeadStatusEnum::PAID,
            LeadStatusEnum::REJECTED,
            LeadStatusEnum::SPAM,
        ]);
    }

    public function scopeBySource($query, LeadSourceEnum $source)
    {
        return $query->where('source', $source);
    }

    public function scopeByLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    public function scopePaid($query)
    {
        return $query->whereIn('source', [
            LeadSourceEnum::GOOGLE_ADS,
            LeadSourceEnum::FACEBOOK_ADS,
            LeadSourceEnum::TIKTOK_ADS,
            LeadSourceEnum::PINTEREST_ADS,
            LeadSourceEnum::YOUTUBE_ADS,
        ]);
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

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [
            PriorityEnum::HIGH,
            PriorityEnum::URGENT,
        ]);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getIsConvertedAttribute(): bool
    {
        return $this->client_id !== null;
    }

    public function getHasClickIdAttribute(): bool
    {
        return $this->gclid !== null
            || $this->fbclid !== null
            || $this->ttclid !== null;
    }

    public function getResponseTimeAttribute(): ?float
    {
        if (!$this->first_contact_at) {
            return null;
        }

        return $this->created_at->diffInMinutes($this->first_contact_at);
    }

    public function getFullUtmAttribute(): ?string
    {
        $parts = array_filter([
            $this->utm_source,
            $this->utm_medium,
            $this->utm_campaign,
        ]);

        return count($parts) > 0 ? implode(' / ', $parts) : null;
    }

    // =====================================================
    // METHODS
    // =====================================================

    public function markAsContacted(): void
    {
        $this->update([
            'status' => LeadStatusEnum::CONTACTED,
            'first_contact_at' => now(),
        ]);
    }

    public function markAsConsultation(): void
    {
        $this->update([
            'status' => LeadStatusEnum::CONSULTATION,
            'consultation_at' => now(),
        ]);
    }

    public function convertToClient(int $clientId, ?int $caseId = null): void
    {
        $this->update([
            'status' => LeadStatusEnum::PAID,
            'converted_at' => now(),
            'client_id' => $clientId,
            'case_id' => $caseId,
        ]);
    }

    public function reject(?string $reason = null): void
    {
        $this->update([
            'status' => LeadStatusEnum::REJECTED,
            'notes' => $reason ? $this->notes . "\nRejected: " . $reason : $this->notes,
        ]);
    }

    public function markAsSpam(): void
    {
        $this->update(['status' => LeadStatusEnum::SPAM]);
    }

    public function assignTo(int $userId, ?PriorityEnum $priority = null): void
    {
        $data = ['assigned_to' => $userId];

        if ($priority) {
            $data['priority'] = $priority;
        }

        $this->update($data);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель Lead — основная модель лидов WINCASE CRM.
// PHP 8.4 backed enums через casts() (Laravel 12 формат).
// Связи: assignedManager (users), client (clients), case (cases).
// 9 скоупов: active, closed, bySource, byLanguage, paid, today,
// thisMonth, unassigned, highPriority.
// 4 аксессора: isConverted, hasClickId, responseTime, fullUtm.
// 5 бизнес-методов: markAsContacted, markAsConsultation,
// convertToClient, reject, markAsSpam, assignTo.
// Файл: app/Models/Lead.php
// ---------------------------------------------------------------
