<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;

class PinterestAdsService extends AbstractPlatformService
{
    protected string $baseUrl = 'https://api.pinterest.com/v5';

    protected function platform(): AdsPlatformEnum
    {
        return AdsPlatformEnum::PINTEREST_ADS;
    }

    // =====================================================
    // FETCH CAMPAIGNS
    // =====================================================

    public function fetchCampaigns(string $dateFrom, string $dateTo): array
    {
        $adAccountId = config('services.pinterest.ad_account_id');
        $token = config('services.pinterest.access_token');

        $response = $this->apiRequest('get', "{$this->baseUrl}/ad_accounts/{$adAccountId}/analytics", [
            'start_date' => $dateFrom,
            'end_date' => $dateTo,
            'columns' => 'SPEND_IN_DOLLAR,IMPRESSION_1,CLICKTHROUGH_1,TOTAL_CONVERSIONS,TOTAL_LEAD,CAMPAIGN_ID,CAMPAIGN_NAME',
            'granularity' => 'DAY',
            'level' => 'CAMPAIGN',
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        return $response ?? [];
    }

    // =====================================================
    // NORMALIZE
    // =====================================================

    protected function normalizeRow(array $raw): array
    {
        return [
            'campaign_id' => (string) ($raw['CAMPAIGN_ID'] ?? $raw['campaign_id'] ?? ''),
            'campaign_name' => $raw['CAMPAIGN_NAME'] ?? $raw['campaign_name'] ?? 'Unknown',
            'date' => $raw['DATE'] ?? $raw['date'] ?? now()->toDateString(),
            'impressions' => (int) ($raw['IMPRESSION_1'] ?? $raw['impressions'] ?? 0),
            'clicks' => (int) ($raw['CLICKTHROUGH_1'] ?? $raw['clicks'] ?? 0),
            'cost' => round((float) ($raw['SPEND_IN_DOLLAR'] ?? $raw['spend'] ?? 0), 2),
            'conversions' => (int) ($raw['TOTAL_CONVERSIONS'] ?? 0),
            'conversion_value' => 0,
            'leads_count' => (int) ($raw['TOTAL_LEAD'] ?? 0),
            'status' => 'active',
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// PinterestAdsService — синхронизация с Pinterest API v5.
// fetchCampaigns() — /ad_accounts/{id}/analytics, daily granularity.
// Метрики: SPEND_IN_DOLLAR, IMPRESSION_1, CLICKTHROUGH_1, TOTAL_LEAD.
// Синхронизация каждые 12 часов (W07).
// Файл: app/Services/Ads/PinterestAdsService.php
// ---------------------------------------------------------------
