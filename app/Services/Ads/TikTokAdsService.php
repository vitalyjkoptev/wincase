<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;

class TikTokAdsService extends AbstractPlatformService
{
    protected string $baseUrl = 'https://business-api.tiktok.com/open_api/v1.3';

    protected function platform(): AdsPlatformEnum
    {
        return AdsPlatformEnum::TIKTOK_ADS;
    }

    // =====================================================
    // FETCH CAMPAIGNS
    // =====================================================

    public function fetchCampaigns(string $dateFrom, string $dateTo): array
    {
        $advertiserId = config('services.tiktok.advertiser_id');
        $token = config('services.tiktok.access_token');

        $response = $this->apiRequest('get', "{$this->baseUrl}/report/integrated/get/", [
            'advertiser_id' => $advertiserId,
            'report_type' => 'BASIC',
            'data_level' => 'AUCTION_CAMPAIGN',
            'dimensions' => '["campaign_id", "stat_time_day"]',
            'metrics' => '["campaign_name","spend","impressions","clicks","conversion","cost_per_conversion","result"]',
            'start_date' => $dateFrom,
            'end_date' => $dateTo,
            'page' => 1,
            'page_size' => 500,
        ], [
            'Access-Token' => $token,
        ]);

        return $response['data']['list'] ?? [];
    }

    // =====================================================
    // NORMALIZE
    // =====================================================

    protected function normalizeRow(array $raw): array
    {
        $dimensions = $raw['dimensions'] ?? [];
        $metrics = $raw['metrics'] ?? [];

        return [
            'campaign_id' => $dimensions['campaign_id'] ?? '',
            'campaign_name' => $metrics['campaign_name'] ?? 'Unknown',
            'date' => $dimensions['stat_time_day'] ?? now()->toDateString(),
            'impressions' => (int) ($metrics['impressions'] ?? 0),
            'clicks' => (int) ($metrics['clicks'] ?? 0),
            'cost' => round((float) ($metrics['spend'] ?? 0), 2),
            'conversions' => (int) ($metrics['conversion'] ?? 0),
            'conversion_value' => round((float) ($metrics['cost_per_conversion'] ?? 0) * (int) ($metrics['conversion'] ?? 0), 2),
            'leads_count' => (int) ($metrics['result'] ?? $metrics['conversion'] ?? 0),
            'status' => 'active',
        ];
    }

    // =====================================================
    // TIKTOK EVENTS API
    // =====================================================

    /**
     * Send server-side event via TikTok Events API.
     * Called on lead creation (W19).
     */
    public function sendEvent(
        string $eventName,
        array $userData,
        ?string $ttclid = null,
        ?float $value = null
    ): array {
        $pixelId = config('services.tiktok.pixel_id');
        $token = config('services.tiktok.access_token');

        return $this->apiRequest('post', "{$this->baseUrl}/pixel/track/", [
            'pixel_code' => $pixelId,
            'event' => $eventName,
            'event_id' => uniqid('wc_', true),
            'timestamp' => now()->toIso8601String(),
            'context' => [
                'user_agent' => $userData['user_agent'] ?? '',
                'ip' => $userData['ip_address'] ?? '',
            ],
            'properties' => array_filter([
                'content_type' => 'product',
                'value' => $value,
                'currency' => 'PLN',
                'query' => $userData['service_type'] ?? null,
            ]),
            'user' => array_filter([
                'ttclid' => $ttclid,
                'email' => isset($userData['email']) ? hash('sha256', strtolower($userData['email'])) : null,
                'phone' => isset($userData['phone']) ? hash('sha256', preg_replace('/[^0-9]/', '', $userData['phone'])) : null,
            ]),
        ], [
            'Access-Token' => $token,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// TikTokAdsService — синхронизация с TikTok Business API v1.3.
// fetchCampaigns() — report/integrated/get с разбивкой по дням и кампаниям.
// sendEvent() — TikTok Events API (серверные события):
//   Lead, Purchase — хеширует email/phone SHA-256, передаёт ttclid.
// Файл: app/Services/Ads/TikTokAdsService.php
// ---------------------------------------------------------------
