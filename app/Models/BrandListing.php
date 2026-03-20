<?php

namespace App\Models;

use App\Enums\BrandListingStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'url',
        'domain',
        'nap_name',
        'nap_address',
        'nap_phone',
        'nap_consistent',
        'status',
        'last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BrandListingStatusEnum::class,
            'nap_consistent' => 'boolean',
            'last_checked_at' => 'datetime',
        ];
    }

    // =====================================================
    // CONSTANTS (Reference NAP data for WinCase)
    // =====================================================

    public const REFERENCE_NAP = [
        'wincase.pro' => [
            'name' => 'WinCase - Immigration Bureau',
            'address' => 'Warsaw, Poland',
            'phone' => '+48 579 266 493',
        ],
        'wincase-legalization.com' => [
            'name' => 'WinCase Legalization',
            'address' => 'Warsaw, Poland',
            'phone' => '+48 579 266 493',
        ],
        'wincase-job.com' => [
            'name' => 'WinCase Job Centre',
            'address' => 'Warsaw, Poland',
            'phone' => '+48 579 266 493',
        ],
        'wincase.org' => [
            'name' => 'WinCase Corporate',
            'address' => 'Warsaw, Poland',
            'phone' => '+48 579 266 493',
        ],
    ];

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeListed($query)
    {
        return $query->where('status', BrandListingStatusEnum::LISTED);
    }

    public function scopeInconsistent($query)
    {
        return $query->where('nap_consistent', false)
                     ->where('status', BrandListingStatusEnum::LISTED);
    }

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeNeedsCheck($query, int $daysThreshold = 30)
    {
        return $query->where(function ($q) use ($daysThreshold) {
            $q->whereNull('last_checked_at')
              ->orWhere('last_checked_at', '<', now()->subDays($daysThreshold));
        });
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getDaysSinceCheckAttribute(): ?int
    {
        return $this->last_checked_at
            ? (int) $this->last_checked_at->diffInDays(now())
            : null;
    }

    // =====================================================
    // METHODS
    // =====================================================

    public function checkNapConsistency(?string $domain = null): bool
    {
        $refDomain = $domain ?? $this->domain ?? 'wincase.pro';
        $reference = self::REFERENCE_NAP[$refDomain] ?? self::REFERENCE_NAP['wincase.pro'];

        $isConsistent = true;

        if ($this->nap_name && !str_contains(strtolower($this->nap_name), strtolower($reference['name']))) {
            $isConsistent = false;
        }

        if ($this->nap_phone && $this->nap_phone !== $reference['phone']) {
            $isConsistent = false;
        }

        $this->update([
            'nap_consistent' => $isConsistent,
            'last_checked_at' => now(),
        ]);

        return $isConsistent;
    }

    // =====================================================
    // STATIC AGGREGATIONS
    // =====================================================

    public static function consistencyReport(): array
    {
        return [
            'total' => static::count(),
            'listed' => static::listed()->count(),
            'consistent' => static::where('nap_consistent', true)->count(),
            'inconsistent' => static::inconsistent()->count(),
            'pending' => static::where('status', BrandListingStatusEnum::PENDING)->count(),
            'not_listed' => static::where('status', BrandListingStatusEnum::NOT_LISTED)->count(),
            'errors' => static::where('status', BrandListingStatusEnum::ERROR)->count(),
            'needs_check' => static::needsCheck()->count(),
            'consistency_rate' => static::listed()->count() > 0
                ? round(
                    (static::where('nap_consistent', true)->count() / static::listed()->count()) * 100,
                    1
                )
                : 0,
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель BrandListing — 50+ каталогов бизнес-листингов для NAP проверки.
// REFERENCE_NAP — эталонные данные WinCase для 4 доменов.
// Скоупы: listed, inconsistent, byDomain, needsCheck.
// Метод checkNapConsistency() — сверка NAP с эталоном, обновление статуса.
// consistencyReport() — полный отчёт NAP-консистентности.
// Файл: app/Models/BrandListing.php
// ---------------------------------------------------------------
