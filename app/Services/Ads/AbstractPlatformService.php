<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;
use App\Models\AdsPerformance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractPlatformService
{
    /**
     * Platform enum for this service.
     */
    abstract protected function platform(): AdsPlatformEnum;

    /**
     * Fetch campaigns from platform API.
     * Returns array of campaign data.
     */
    abstract public function fetchCampaigns(string $dateFrom, string $dateTo): array;

    /**
     * Parse raw API response into normalized format.
     */
    abstract protected function normalizeRow(array $raw): array;

    // =====================================================
    // SYNC — Fetch from API → Upsert to DB
    // =====================================================

    /**
     * Sync data from platform API to ads_performance table.
     * Called by n8n workflow (W04-W07) every 6/12 hours.
     */
    public function sync(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(2)->toDateString();
        $dateTo = $dateTo ?? now()->toDateString();

        $platform = $this->platform();

        Log::info("Ads sync started: {$platform->value}", [
            'from' => $dateFrom,
            'to' => $dateTo,
        ]);

        try {
            $rawData = $this->fetchCampaigns($dateFrom, $dateTo);
        } catch (\Exception $e) {
            Log::error("Ads sync failed: {$platform->value}", [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'platform' => $platform->value,
                'error' => $e->getMessage(),
            ];
        }

        $upserted = 0;

        foreach ($rawData as $row) {
            $normalized = $this->normalizeRow($row);

            // Calculate derived metrics
            $clicks = $normalized['clicks'] ?? 0;
            $impressions = $normalized['impressions'] ?? 0;
            $cost = $normalized['cost'] ?? 0;
            $leads = $normalized['leads_count'] ?? 0;

            $normalized['ctr'] = $impressions > 0 ? round($clicks / $impressions, 4) : 0;
            $normalized['cpc'] = $clicks > 0 ? round($cost / $clicks, 4) : 0;
            $normalized['cpl'] = $leads > 0 ? round($cost / $leads, 4) : 0;

            AdsPerformance::updateOrCreate(
                [
                    'platform' => $platform,
                    'campaign_id' => $normalized['campaign_id'],
                    'date' => $normalized['date'],
                ],
                array_merge($normalized, [
                    'platform' => $platform,
                    'raw_data' => $row,
                ])
            );

            $upserted++;
        }

        Log::info("Ads sync completed: {$platform->value}", [
            'rows_upserted' => $upserted,
        ]);

        return [
            'success' => true,
            'platform' => $platform->value,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'rows_synced' => $upserted,
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    /**
     * Make authenticated HTTP request to platform API.
     */
    protected function apiRequest(string $method, string $url, array $params = [], array $headers = []): array
    {
        $response = Http::withHeaders($headers)
            ->timeout(30)
            ->retry(2, 1000)
            ->{$method}($url, $params);

        if (!$response->successful()) {
            throw new \RuntimeException(
                "API request failed [{$response->status()}]: {$response->body()}"
            );
        }

        return $response->json() ?? [];
    }

    /**
     * Get platform stats from local DB (not API).
     */
    public function getStats(string $dateFrom, string $dateTo): array
    {
        return AdsPerformance::where('platform', $this->platform())
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('
                campaign_id,
                campaign_name,
                SUM(impressions) as impressions,
                SUM(clicks) as clicks,
                SUM(cost) as cost,
                SUM(conversions) as conversions,
                SUM(conversion_value) as conversion_value,
                SUM(leads_count) as leads,
                CASE WHEN SUM(clicks) > 0 THEN SUM(cost)/SUM(clicks) ELSE 0 END as cpc,
                CASE WHEN SUM(leads_count) > 0 THEN SUM(cost)/SUM(leads_count) ELSE 0 END as cpl,
                CASE WHEN SUM(impressions) > 0 THEN SUM(clicks)/SUM(impressions) ELSE 0 END as ctr,
                MAX(status) as status
            ')
            ->groupBy('campaign_id', 'campaign_name')
            ->orderByDesc('cost')
            ->get()
            ->toArray();
    }

    /**
     * Daily breakdown for charts.
     */
    public function getDailyBreakdown(string $dateFrom, string $dateTo): array
    {
        return AdsPerformance::where('platform', $this->platform())
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('
                date,
                SUM(impressions) as impressions,
                SUM(clicks) as clicks,
                SUM(cost) as cost,
                SUM(conversions) as conversions,
                SUM(leads_count) as leads
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AbstractPlatformService — базовый класс для всех рекламных платформ.
// sync() — основной метод: запрос API → нормализация → upsert в ads_performance.
// Считает производные метрики: CTR, CPC, CPL.
// getStats() — статистика по кампаниям из локальной БД.
// getDailyBreakdown() — ежедневная разбивка для графиков.
// Наследники реализуют: platform(), fetchCampaigns(), normalizeRow().
// Файл: app/Services/Ads/AbstractPlatformService.php
// ---------------------------------------------------------------
