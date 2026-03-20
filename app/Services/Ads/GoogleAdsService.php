<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;

class GoogleAdsService extends AbstractPlatformService
{
    protected string $baseUrl = 'https://googleads.googleapis.com/v17';

    protected function platform(): AdsPlatformEnum
    {
        return AdsPlatformEnum::GOOGLE_ADS;
    }

    // =====================================================
    // FETCH CAMPAIGNS FROM GOOGLE ADS API
    // =====================================================

    public function fetchCampaigns(string $dateFrom, string $dateTo): array
    {
        $customerId = config('services.google_ads.customer_id');
        $devToken = config('services.google_ads.developer_token');
        $refreshToken = config('services.google_ads.refresh_token');

        $accessToken = $this->getAccessToken($refreshToken);

        // GAQL (Google Ads Query Language) query
        $gaql = "
            SELECT
                campaign.id,
                campaign.name,
                campaign.status,
                segments.date,
                metrics.impressions,
                metrics.clicks,
                metrics.cost_micros,
                metrics.conversions,
                metrics.conversions_value,
                metrics.all_conversions
            FROM campaign
            WHERE segments.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
              AND campaign.status != 'REMOVED'
            ORDER BY segments.date DESC
        ";

        $response = $this->apiRequest('post', "{$this->baseUrl}/customers/{$customerId}/googleAds:searchStream", [
            'query' => $gaql,
        ], [
            'Authorization' => "Bearer {$accessToken}",
            'developer-token' => $devToken,
        ]);

        return $this->flattenSearchStreamResponse($response);
    }

    // =====================================================
    // NORMALIZE
    // =====================================================

    protected function normalizeRow(array $raw): array
    {
        $campaign = $raw['campaign'] ?? [];
        $metrics = $raw['metrics'] ?? [];
        $segments = $raw['segments'] ?? [];

        return [
            'campaign_id' => (string) ($campaign['id'] ?? ''),
            'campaign_name' => $campaign['name'] ?? 'Unknown',
            'date' => $segments['date'] ?? now()->toDateString(),
            'impressions' => (int) ($metrics['impressions'] ?? 0),
            'clicks' => (int) ($metrics['clicks'] ?? 0),
            'cost' => round(($metrics['costMicros'] ?? 0) / 1_000_000, 2),
            'conversions' => (int) ($metrics['conversions'] ?? 0),
            'conversion_value' => round((float) ($metrics['conversionsValue'] ?? 0), 2),
            'leads_count' => (int) ($metrics['allConversions'] ?? 0),
            'status' => strtolower($campaign['status'] ?? 'active') === 'enabled' ? 'active' : 'paused',
        ];
    }

    // =====================================================
    // OFFLINE CONVERSION IMPORT
    // =====================================================

    /**
     * Send offline conversion back to Google Ads.
     * Called when lead.status = 'paid' (W17 workflow).
     */
    public function uploadOfflineConversion(string $gclid, float $value, string $conversionAction, ?string $conversionTime = null): array
    {
        $customerId = config('services.google_ads.customer_id');
        $accessToken = $this->getAccessToken(config('services.google_ads.refresh_token'));

        $conversionTime = $conversionTime ?? now()->format('Y-m-d H:i:sP');

        $response = $this->apiRequest('post', "{$this->baseUrl}/customers/{$customerId}/offlineConversions:upload", [
            'conversions' => [[
                'gclid' => $gclid,
                'conversionAction' => "customers/{$customerId}/conversionActions/{$conversionAction}",
                'conversionDateTime' => $conversionTime,
                'conversionValue' => $value,
                'currencyCode' => 'PLN',
            ]],
            'partialFailure' => true,
        ], [
            'Authorization' => "Bearer {$accessToken}",
            'developer-token' => config('services.google_ads.developer_token'),
        ]);

        return $response;
    }

    // =====================================================
    // AUTH HELPERS
    // =====================================================

    protected function getAccessToken(string $refreshToken): string
    {
        $response = $this->apiRequest('post', 'https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        return $response['access_token'] ?? throw new \RuntimeException('Failed to refresh Google token');
    }

    protected function flattenSearchStreamResponse(array $response): array
    {
        $rows = [];

        foreach ($response as $batch) {
            foreach ($batch['results'] ?? [] as $row) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// GoogleAdsService — синхронизация с Google Ads API v17.
// fetchCampaigns() — GAQL запрос: кампании + метрики + сегменты по дням.
// cost_micros → PLN (делим на 1M). Status: ENABLED → active.
// uploadOfflineConversion() — отправка оффлайн-конверсии по gclid
// (когда лид оплатил — W17 workflow). Валюта: PLN.
// OAuth 2.0: refresh_token → access_token автоматически.
// Файл: app/Services/Ads/GoogleAdsService.php
// ---------------------------------------------------------------
