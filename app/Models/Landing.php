<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Landing extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'path',
        'language',
        'title',
        'audience',
        'traffic_sources',
        'ab_variant',
        'is_active',
        'views_total',
        'submissions_total',
        'conversion_rate',
        'pagespeed_score',
    ];

    protected function casts(): array
    {
        return [
            'traffic_sources' => 'array',
            'is_active' => 'boolean',
            'conversion_rate' => 'decimal:2',
        ];
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'landing_page', 'path');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeByLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getFullUrlAttribute(): string
    {
        return 'https://' . $this->domain . $this->path;
    }

    public function getConversionRateCalculatedAttribute(): float
    {
        return $this->views_total > 0
            ? round(($this->submissions_total / $this->views_total) * 100, 2)
            : 0;
    }

    // =====================================================
    // METHODS
    // =====================================================

    public function incrementView(): void
    {
        $this->increment('views_total');
        $this->recalculateConversionRate();
    }

    public function incrementSubmission(): void
    {
        $this->increment('submissions_total');
        $this->recalculateConversionRate();
    }

    public function recalculateConversionRate(): void
    {
        $this->update([
            'conversion_rate' => $this->conversionRateCalculated,
        ]);
    }

    // =====================================================
    // STATIC AGGREGATIONS
    // =====================================================

    public static function overviewByDomain(): array
    {
        return static::selectRaw('
                domain,
                COUNT(*) as total_pages,
                SUM(views_total) as total_views,
                SUM(submissions_total) as total_submissions,
                AVG(conversion_rate) as avg_conversion,
                AVG(pagespeed_score) as avg_pagespeed
            ')
            ->where('is_active', true)
            ->groupBy('domain')
            ->get()
            ->toArray();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель Landing — 14+ лендинговых страниц на 4 доменах WinCase.
// Связь: leads() — лиды пришедшие с этой страницы (по landing_page).
// Скоупы: active, byDomain, byLanguage.
// Аксессоры: fullUrl, conversionRateCalculated.
// Методы: incrementView, incrementSubmission, recalculateConversionRate.
// overviewByDomain() — агрегация по доменам.
// Файл: app/Models/Landing.php
// ---------------------------------------------------------------
