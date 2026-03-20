<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;
use App\Models\AdsPerformance;
use Illuminate\Support\Facades\Log;

class AdsOrchestrationService
{
    protected array $services = [];

    public function __construct(
        protected GoogleAdsService $google,
        protected MetaAdsService $meta,
        protected TikTokAdsService $tiktok,
        protected PinterestAdsService $pinterest,
        protected YouTubeAdsService $youtube,
    ) {
        $this->services = [
            AdsPlatformEnum::GOOGLE_ADS->value => $this->google,
            AdsPlatformEnum::META_ADS->value => $this->meta,
            AdsPlatformEnum::TIKTOK_ADS->value => $this->tiktok,
            AdsPlatformEnum::PINTEREST_ADS->value => $this->pinterest,
            AdsPlatformEnum::YOUTUBE_ADS->value => $this->youtube,
        ];
    }

    // =====================================================
    // OVERVIEW — All platforms combined
    // =====================================================

    /**
     * Get unified overview for all 5 platforms.
     */
    public function getOverview(string $dateFrom, string $dateTo): array
    {
        $platforms = AdsPerformance::overviewByPlatform($dateFrom, $dateTo);

        $totals = [
            'impressions' => 0,
            'clicks' => 0,
            'cost' => 0,
            'conversions' => 0,
            'leads' => 0,
        ];

        foreach ($platforms as &$p) {
            $enum = AdsPlatformEnum::tryFrom($p['platform']);
            $p['label'] = $enum?->label() ?? $p['platform'];
            $p['roas'] = $p['total_cost'] > 0
                ? round(($p['total_conversions'] * 100) / $p['total_cost'], 2)
                : 0;

            $totals['impressions'] += $p['total_impressions'];
            $totals['clicks'] += $p['total_clicks'];
            $totals['cost'] += $p['total_cost'];
            $totals['conversions'] += $p['total_conversions'];
            $totals['leads'] += $p['total_leads'];
        }

        $totals['ctr'] = $totals['impressions'] > 0
            ? round(($totals['clicks'] / $totals['impressions']) * 100, 2) : 0;
        $totals['cpl'] = $totals['leads'] > 0
            ? round($totals['cost'] / $totals['leads'], 2) : 0;
        $totals['cpc'] = $totals['clicks'] > 0
            ? round($totals['cost'] / $totals['clicks'], 2) : 0;

        return [
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
            'platforms' => $platforms,
            'totals' => $totals,
        ];
    }

    // =====================================================
    // SINGLE PLATFORM DATA
    // =====================================================

    public function getPlatformData(AdsPlatformEnum $platform, string $dateFrom, string $dateTo): array
    {
        $service = $this->getService($platform);

        return [
            'platform' => $platform->value,
            'label' => $platform->label(),
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
            'campaigns' => $service->getStats($dateFrom, $dateTo),
            'daily' => $service->getDailyBreakdown($dateFrom, $dateTo),
        ];
    }

    // =====================================================
    // CAMPAIGN LIST
    // =====================================================

    public function getCampaigns(AdsPlatformEnum $platform, string $dateFrom, string $dateTo): array
    {
        $service = $this->getService($platform);
        return $service->getStats($dateFrom, $dateTo);
    }

    // =====================================================
    // BUDGET ANALYSIS
    // =====================================================

    /**
     * Budget plan vs actual spend.
     */
    public function getBudgetAnalysis(string $dateFrom, string $dateTo): array
    {
        $actualByPlatform = AdsPerformance::whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('platform, SUM(cost) as actual_spend')
            ->groupBy('platform')
            ->pluck('actual_spend', 'platform')
            ->toArray();

        // Monthly budget plan (from config or database — configurable)
        $budgetPlan = config('ads.budget_plan', [
            'google_ads' => 3000,
            'meta_ads' => 2000,
            'tiktok_ads' => 1500,
            'pinterest_ads' => 500,
            'youtube_ads' => 1000,
        ]);

        $analysis = [];
        $totalBudget = 0;
        $totalSpent = 0;

        foreach (AdsPlatformEnum::cases() as $platform) {
            $planned = $budgetPlan[$platform->value] ?? 0;
            $actual = $actualByPlatform[$platform->value] ?? 0;
            $variance = $planned - $actual;
            $pctUsed = $planned > 0 ? round(($actual / $planned) * 100, 1) : 0;

            $analysis[] = [
                'platform' => $platform->value,
                'label' => $platform->label(),
                'budget_planned' => $planned,
                'budget_actual' => round($actual, 2),
                'variance' => round($variance, 2),
                'pct_used' => $pctUsed,
                'status' => $pctUsed > 110 ? 'over_budget' : ($pctUsed > 90 ? 'near_limit' : 'on_track'),
            ];

            $totalBudget += $planned;
            $totalSpent += $actual;
        }

        return [
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
            'platforms' => $analysis,
            'total_budget' => $totalBudget,
            'total_spent' => round($totalSpent, 2),
            'total_remaining' => round($totalBudget - $totalSpent, 2),
            'pct_used' => $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0,
        ];
    }

    // =====================================================
    // SYNC ALL PLATFORMS
    // =====================================================

    /**
     * Sync data from all platforms (or specific ones).
     * Called by n8n W04-W07 or manual trigger.
     */
    public function syncAll(?string $dateFrom = null, ?string $dateTo = null, ?array $platforms = null): array
    {
        $platforms = $platforms ?? array_keys($this->services);
        $results = [];

        foreach ($platforms as $platformKey) {
            $service = $this->services[$platformKey] ?? null;

            if (!$service) {
                $results[$platformKey] = ['success' => false, 'error' => 'Unknown platform'];
                continue;
            }

            $results[$platformKey] = $service->sync($dateFrom, $dateTo);
        }

        Log::info('Ads sync all completed', [
            'platforms' => array_keys($results),
            'success' => array_filter($results, fn ($r) => $r['success'] ?? false),
        ]);

        return $results;
    }

    /**
     * Sync single platform.
     */
    public function syncPlatform(AdsPlatformEnum $platform, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->getService($platform)->sync($dateFrom, $dateTo);
    }

    // =====================================================
    // DASHBOARD WIDGET
    // =====================================================

    /**
     * Quick stats for Dashboard section.
     */
    public function getDashboardStats(): array
    {
        $last7 = now()->subDays(7)->toDateString();
        $today = now()->toDateString();

        $data = AdsPerformance::where('date', '>=', $last7)
            ->selectRaw('
                SUM(cost) as total_spend,
                SUM(clicks) as total_clicks,
                SUM(leads_count) as total_leads,
                SUM(impressions) as total_impressions,
                CASE WHEN SUM(leads_count) > 0 THEN SUM(cost)/SUM(leads_count) ELSE 0 END as avg_cpl
            ')
            ->first();

        $cplByPlatform = AdsPerformance::where('date', '>=', $last7)
            ->selectRaw('
                platform,
                CASE WHEN SUM(leads_count) > 0 THEN SUM(cost)/SUM(leads_count) ELSE 0 END as cpl
            ')
            ->groupBy('platform')
            ->pluck('cpl', 'platform')
            ->toArray();

        return [
            'period' => '7d',
            'total_spend' => round($data->total_spend ?? 0, 2),
            'total_clicks' => (int) ($data->total_clicks ?? 0),
            'total_leads' => (int) ($data->total_leads ?? 0),
            'total_impressions' => (int) ($data->total_impressions ?? 0),
            'avg_cpl' => round($data->avg_cpl ?? 0, 2),
            'cpl_by_platform' => $cplByPlatform,
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function getService(AdsPlatformEnum $platform): AbstractPlatformService
    {
        return $this->services[$platform->value]
            ?? throw new \InvalidArgumentException("No service for platform: {$platform->value}");
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AdsOrchestrationService — единый сервис для всех 5 рекламных платформ.
// getOverview() — сводная таблица всех платформ: impressions, clicks,
// cost, conversions, leads, CTR, CPL, CPC, ROAS.
// getPlatformData() — данные одной платформы: кампании + daily breakdown.
// getBudgetAnalysis() — план vs факт (budget_plan из config или БД).
// syncAll() — синхронизация всех/выбранных платформ (для n8n W04-W07).
// getDashboardStats() — виджет dashboard: spend, clicks, leads, CPL за 7 дней.
// Файл: app/Services/Ads/AdsOrchestrationService.php
// ---------------------------------------------------------------
