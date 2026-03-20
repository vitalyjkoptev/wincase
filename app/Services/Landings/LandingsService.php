<?php

namespace App\Services\Landings;

use App\Models\LandingPage;
use App\Models\LandingVariant;
use App\Models\LandingVisit;
use Illuminate\Support\Facades\DB;

class LandingsService
{
    // =====================================================
    // 15+ LANDING PAGES — multi-language, multi-service
    // =====================================================

    protected array $landingMatrix = [
        'legalization' => [
            'domain' => 'wincase-legalization.com',
            'languages' => ['pl', 'en', 'ru', 'ua', 'hi'],
            'pages' => ['main', 'karta-pobytu', 'zezwolenie-na-prace', 'obywatelstwo', 'blue-card'],
        ],
        'jobs' => [
            'domain' => 'wincase-job.com',
            'languages' => ['pl', 'en', 'ua', 'hi', 'tl'],
            'pages' => ['main', 'praca-w-polsce', 'work-permit', 'staffing'],
        ],
        'main' => [
            'domain' => 'wincase.pro',
            'languages' => ['pl', 'en', 'ru'],
            'pages' => ['home', 'about', 'services', 'contact', 'consultation'],
        ],
        'network' => [
            'domain' => 'wincase.org',
            'languages' => ['pl', 'en'],
            'pages' => ['main', 'partners'],
        ],
    ];

    // =====================================================
    // LANDING PAGES CRUD
    // =====================================================

    public function getAllLandings(?string $domain = null, ?string $status = null): array
    {
        $query = LandingPage::with('variants');

        if ($domain) $query->where('domain', $domain);
        if ($status) $query->where('status', $status);

        return $query->orderBy('domain')->orderBy('slug')->get()->toArray();
    }

    public function getLanding(int $id): ?LandingPage
    {
        return LandingPage::with(['variants', 'variants.visits'])->find($id);
    }

    public function createLanding(array $data): LandingPage
    {
        return LandingPage::create($data);
    }

    public function updateLanding(int $id, array $data): LandingPage
    {
        $landing = LandingPage::findOrFail($id);
        $landing->update($data);
        return $landing->fresh();
    }

    // =====================================================
    // A/B TESTING — Variants
    // =====================================================

    public function createVariant(int $landingId, array $data): LandingVariant
    {
        $data['landing_page_id'] = $landingId;

        // Ensure traffic split totals 100%
        $existingTraffic = LandingVariant::where('landing_page_id', $landingId)
            ->where('is_active', true)
            ->sum('traffic_pct');

        $data['traffic_pct'] = min($data['traffic_pct'] ?? 50, 100 - $existingTraffic);

        return LandingVariant::create($data);
    }

    /**
     * Record visit and assign variant based on traffic split.
     */
    public function recordVisit(int $landingId, array $visitorData): array
    {
        $variants = LandingVariant::where('landing_page_id', $landingId)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($variants->isEmpty()) {
            return ['variant' => null];
        }

        // Weighted random selection
        $rand = mt_rand(1, 100);
        $cumulative = 0;
        $selectedVariant = $variants->first();

        foreach ($variants as $variant) {
            $cumulative += $variant->traffic_pct;
            if ($rand <= $cumulative) {
                $selectedVariant = $variant;
                break;
            }
        }

        // Record visit
        LandingVisit::create([
            'landing_variant_id' => $selectedVariant->id,
            'visitor_ip' => $visitorData['ip'] ?? null,
            'utm_source' => $visitorData['utm_source'] ?? null,
            'utm_medium' => $visitorData['utm_medium'] ?? null,
            'utm_campaign' => $visitorData['utm_campaign'] ?? null,
            'utm_content' => $visitorData['utm_content'] ?? null,
            'referer' => $visitorData['referer'] ?? null,
            'user_agent' => $visitorData['user_agent'] ?? null,
            'language' => $visitorData['language'] ?? 'pl',
            'device_type' => $visitorData['device_type'] ?? 'desktop',
        ]);

        // Increment counters
        $selectedVariant->increment('visits_count');

        return [
            'variant' => $selectedVariant->variant_name,
            'variant_id' => $selectedVariant->id,
        ];
    }

    /**
     * Record conversion on variant.
     */
    public function recordConversion(int $variantId): void
    {
        $variant = LandingVariant::findOrFail($variantId);
        $variant->increment('conversions_count');
    }

    // =====================================================
    // ANALYTICS
    // =====================================================

    public function getAnalytics(?string $domain = null, int $days = 30): array
    {
        $since = now()->subDays($days)->toDateString();

        // Per-landing stats
        $query = LandingPage::with(['variants' => function ($q) {
            $q->select('id', 'landing_page_id', 'variant_name', 'visits_count', 'conversions_count', 'traffic_pct', 'is_active');
        }]);

        if ($domain) $query->where('domain', $domain);

        $landings = $query->get();

        $summary = [];
        $totalVisits = 0;
        $totalConversions = 0;

        foreach ($landings as $landing) {
            $visits = $landing->variants->sum('visits_count');
            $conversions = $landing->variants->sum('conversions_count');
            $cr = $visits > 0 ? round(($conversions / $visits) * 100, 2) : 0;

            $totalVisits += $visits;
            $totalConversions += $conversions;

            $variantStats = $landing->variants->map(function ($v) {
                $cr = $v->visits_count > 0 ? round(($v->conversions_count / $v->visits_count) * 100, 2) : 0;
                return [
                    'name' => $v->variant_name,
                    'visits' => $v->visits_count,
                    'conversions' => $v->conversions_count,
                    'conversion_rate' => $cr,
                    'traffic_pct' => $v->traffic_pct,
                    'is_active' => $v->is_active,
                ];
            })->toArray();

            $summary[] = [
                'id' => $landing->id,
                'domain' => $landing->domain,
                'slug' => $landing->slug,
                'language' => $landing->language,
                'status' => $landing->status,
                'total_visits' => $visits,
                'total_conversions' => $conversions,
                'conversion_rate' => $cr,
                'variants' => $variantStats,
            ];
        }

        // Daily trend
        $dailyTrend = LandingVisit::where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // UTM breakdown
        $utmSources = LandingVisit::where('created_at', '>=', $since)
            ->whereNotNull('utm_source')
            ->selectRaw('utm_source, COUNT(*) as visits')
            ->groupBy('utm_source')
            ->orderByDesc('visits')
            ->limit(10)
            ->get()
            ->toArray();

        // Device breakdown
        $devices = LandingVisit::where('created_at', '>=', $since)
            ->selectRaw('device_type, COUNT(*) as visits')
            ->groupBy('device_type')
            ->get()
            ->pluck('visits', 'device_type')
            ->toArray();

        return [
            'total_visits' => $totalVisits,
            'total_conversions' => $totalConversions,
            'overall_cr' => $totalVisits > 0 ? round(($totalConversions / $totalVisits) * 100, 2) : 0,
            'landings' => $summary,
            'daily_trend' => $dailyTrend,
            'utm_sources' => $utmSources,
            'devices' => $devices,
            'matrix' => $this->landingMatrix,
        ];
    }

    // =====================================================
    // BEST PERFORMING
    // =====================================================

    public function getBestVariants(int $limit = 5): array
    {
        return LandingVariant::where('visits_count', '>=', 50)
            ->where('is_active', true)
            ->selectRaw('*, CASE WHEN visits_count > 0 THEN (conversions_count / visits_count) * 100 ELSE 0 END as cr')
            ->orderByDesc('cr')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LandingsService — управление 15+ лендинговых страниц.
// 4 домена: wincase-legalization.com (5 страниц, 5 языков),
// wincase-job.com (4 страницы, 5 языков), wincase.pro (5 страниц, 3 языка),
// wincase.org (2 страницы, 2 языка).
// A/B тестирование: variants с traffic_pct%, weighted random selection.
// recordVisit() — запись посещения с UTM, device, language.
// recordConversion() — инкремент конверсий.
// getAnalytics() — per-landing stats, daily trend, UTM sources, devices.
// Файл: app/Services/Landings/LandingsService.php
// ---------------------------------------------------------------
