<?php

namespace App\Services\Dashboard;

use App\Events\DashboardUpdated;
use Illuminate\Support\Facades\Cache;

class DashboardCacheService
{
    protected const TTL_KPI = 60;          // 1 minute
    protected const TTL_SECTION = 300;     // 5 minutes
    protected const TTL_FULL = 120;        // 2 minutes
    protected const CACHE_PREFIX = 'dashboard:';

    public function __construct(
        protected DashboardService $dashboard
    ) {}

    // =====================================================
    // CACHED GETTERS
    // =====================================================

    public function getKpiBar(): array
    {
        return Cache::remember(self::CACHE_PREFIX . 'kpi', self::TTL_KPI, function () {
            return $this->dashboard->getKpiBar();
        });
    }

    public function getSection(string $section): array
    {
        $method = 'get' . ucfirst($section) . 'Section';

        if (!method_exists($this->dashboard, $method)) {
            return [];
        }

        return Cache::remember(self::CACHE_PREFIX . $section, self::TTL_SECTION, function () use ($method) {
            return $this->dashboard->$method();
        });
    }

    public function getFullDashboard(): array
    {
        return Cache::remember(self::CACHE_PREFIX . 'full', self::TTL_FULL, function () {
            return $this->dashboard->getFullDashboard();
        });
    }

    // =====================================================
    // INVALIDATION + BROADCAST
    // =====================================================

    /**
     * Invalidate cache for a section and broadcast update via Reverb.
     * Called by observers/listeners when data changes.
     */
    public function invalidateAndBroadcast(string $section): void
    {
        // Clear specific section cache
        Cache::forget(self::CACHE_PREFIX . $section);
        Cache::forget(self::CACHE_PREFIX . 'kpi');
        Cache::forget(self::CACHE_PREFIX . 'full');

        // Re-fetch fresh data
        $freshData = match ($section) {
            'kpi' => $this->dashboard->getKpiBar(),
            'cases' => $this->dashboard->getCasesSection(),
            'leads' => $this->dashboard->getLeadsSection(),
            'finance' => $this->dashboard->getFinanceSection(),
            'ads' => $this->dashboard->getAdsSection(),
            'social' => $this->dashboard->getSocialSection(),
            'seo' => $this->dashboard->getSeoSection(),
            'accounting' => $this->dashboard->getAccountingSection(),
            'automation' => $this->dashboard->getAutomationSection(),
            default => [],
        };

        // Broadcast to connected Vue.js clients via Reverb
        event(new DashboardUpdated($section, $freshData));
    }

    /**
     * Clear all dashboard caches (manual reset).
     */
    public function clearAll(): void
    {
        $sections = ['kpi', 'cases', 'leads', 'finance', 'ads', 'social', 'seo', 'accounting', 'automation', 'full'];

        foreach ($sections as $section) {
            Cache::forget(self::CACHE_PREFIX . $section);
        }
    }

    // =====================================================
    // TRIGGER MAPPINGS
    // =====================================================

    /**
     * Map model events to dashboard sections for auto-invalidation.
     *
     * Usage in EventServiceProvider or Model Observers:
     *   DashboardCacheService::triggerMap()['Lead'] → ['kpi', 'leads']
     *   When a Lead is created → invalidateAndBroadcast('kpi') + invalidateAndBroadcast('leads')
     */
    public static function triggerMap(): array
    {
        return [
            'Lead'              => ['kpi', 'leads'],
            'CaseModel'         => ['kpi', 'cases'],
            'Invoice'           => ['kpi', 'finance'],
            'PosTransaction'    => ['kpi', 'finance'],
            'AdsPerformance'    => ['kpi', 'ads'],
            'SocialPost'        => ['social'],
            'SocialAccount'     => ['social'],
            'SeoData'           => ['seo'],
            'TaxReport'         => ['kpi', 'accounting'],
            'Expense'           => ['accounting'],
            'Task'              => ['kpi'],
            'Client'            => ['kpi'],
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// DashboardCacheService — Redis кеширование + Reverb broadcast.
// TTL: KPI = 60s, Секции = 300s, Full = 120s.
// invalidateAndBroadcast() — очистка кеша → свежие данные → WebSocket push.
// triggerMap() — маппинг Model → Dashboard sections для auto-invalidation:
//   Lead → kpi + leads, Invoice → kpi + finance, и т.д.
// Используется в Model Observers или EventServiceProvider.
// Файл: app/Services/Dashboard/DashboardCacheService.php
// ---------------------------------------------------------------
