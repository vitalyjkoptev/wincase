<?php

namespace App\Models;

use App\Enums\AdsPlatformEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsPerformance extends Model
{
    use HasFactory;

    protected $table = 'ads_performance';

    protected $fillable = [
        'platform',
        'campaign_id',
        'campaign_name',
        'date',
        'impressions',
        'clicks',
        'cost',
        'conversions',
        'conversion_value',
        'cpc',
        'cpl',
        'ctr',
        'leads_count',
        'status',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'platform' => AdsPlatformEnum::class,
            'date' => 'date',
            'cost' => 'decimal:2',
            'conversion_value' => 'decimal:2',
            'cpc' => 'decimal:4',
            'cpl' => 'decimal:4',
            'ctr' => 'decimal:4',
            'raw_data' => 'array',
        ];
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeByPlatform($query, AdsPlatformEnum $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLast7Days($query)
    {
        return $query->where('date', '>=', now()->subDays(7)->toDateString());
    }

    public function scopeLast30Days($query)
    {
        return $query->where('date', '>=', now()->subDays(30)->toDateString());
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getRoasAttribute(): float
    {
        return $this->cost > 0
            ? round($this->conversion_value / $this->cost, 2)
            : 0;
    }

    // =====================================================
    // STATIC AGGREGATIONS
    // =====================================================

    public static function overviewByPlatform(string $from, string $to): array
    {
        return static::selectRaw('
                platform,
                SUM(impressions) as total_impressions,
                SUM(clicks) as total_clicks,
                SUM(cost) as total_cost,
                SUM(conversions) as total_conversions,
                SUM(leads_count) as total_leads,
                AVG(ctr) as avg_ctr,
                CASE WHEN SUM(leads_count) > 0
                     THEN SUM(cost) / SUM(leads_count)
                     ELSE 0 END as avg_cpl
            ')
            ->whereBetween('date', [$from, $to])
            ->groupBy('platform')
            ->get()
            ->toArray();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель AdsPerformance — ежедневные метрики рекламных кампаний.
// PHP 8.4 backed enum AdsPlatformEnum. raw_data → JSON cast.
// Скоупы: byPlatform, dateRange, active, last7Days, last30Days.
// Аксессор roas — Return On Ad Spend.
// Статический метод overviewByPlatform() — агрегация по платформам за период.
// Файл: app/Models/AdsPerformance.php
// ---------------------------------------------------------------
