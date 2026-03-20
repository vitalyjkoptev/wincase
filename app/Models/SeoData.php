<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoData extends Model
{
    use HasFactory;

    protected $table = 'seo_data';

    protected $fillable = [
        'domain',
        'date',
        'source',
        'clicks',
        'impressions',
        'avg_position',
        'users',
        'sessions',
        'conversions',
        'domain_authority',
        'backlinks',
        'referring_domains',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'avg_position' => 'decimal:2',
            'raw_data' => 'array',
        ];
    }

    // =====================================================
    // CONSTANTS
    // =====================================================

    public const DOMAINS = [
        'wincase.pro',
        'wincase-legalization.com',
        'wincase-job.com',
        'wincase.org',
    ];

    public const SOURCES = [
        'gsc',
        'ga4',
        'ahrefs',
        'moz',
    ];

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    public function scopeGsc($query)
    {
        return $query->where('source', 'gsc');
    }

    public function scopeGa4($query)
    {
        return $query->where('source', 'ga4');
    }

    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    public function scopeLast30Days($query)
    {
        return $query->where('date', '>=', now()->subDays(30)->toDateString());
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getCtrAttribute(): float
    {
        return $this->impressions > 0
            ? round(($this->clicks / $this->impressions) * 100, 2)
            : 0;
    }

    // =====================================================
    // STATIC AGGREGATIONS
    // =====================================================

    public static function overviewAllDomains(string $from, string $to): array
    {
        return static::selectRaw('
                domain,
                source,
                SUM(clicks) as total_clicks,
                SUM(impressions) as total_impressions,
                AVG(avg_position) as avg_position,
                SUM(users) as total_users,
                SUM(sessions) as total_sessions,
                SUM(conversions) as total_conversions,
                MAX(domain_authority) as domain_authority,
                MAX(backlinks) as backlinks,
                MAX(referring_domains) as referring_domains
            ')
            ->whereBetween('date', [$from, $to])
            ->groupBy('domain', 'source')
            ->get()
            ->groupBy('domain')
            ->toArray();
    }

    public static function trendByDomain(string $domain, string $source, string $from, string $to): array
    {
        return static::where('domain', $domain)
            ->where('source', $source)
            ->whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->get(['date', 'clicks', 'impressions', 'avg_position', 'users', 'sessions'])
            ->toArray();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель SeoData — SEO-метрики для 4 доменов WinCase.
// Константы DOMAINS (4 домена) и SOURCES (gsc/ga4/ahrefs/moz).
// Скоупы: byDomain, bySource, gsc, ga4, dateRange, last30Days.
// Аксессор ctr — Click-Through Rate в процентах.
// Статические методы: overviewAllDomains() — сводка всех доменов,
// trendByDomain() — тренд по конкретному домену и источнику.
// Файл: app/Models/SeoData.php
// ---------------------------------------------------------------
