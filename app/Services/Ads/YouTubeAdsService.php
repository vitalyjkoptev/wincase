<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;

class YouTubeAdsService extends AbstractPlatformService
{
    protected string $baseUrl = 'https://googleads.googleapis.com/v17';

    protected function platform(): AdsPlatformEnum
    {
        return AdsPlatformEnum::YOUTUBE_ADS;
    }

    // =====================================================
    // FETCH CAMPAIGNS (YouTube via Google Ads API)
    // =====================================================

    /**
     * YouTube Ads are managed via Google Ads.
     * Filter by advertising_channel_type = VIDEO.
     */
    public function fetchCampaigns(string $dateFrom, string $dateTo): array
    {
        $customerId = config('services.google_ads.customer_id');
        $devToken = config('services.google_ads.developer_token');
        $accessToken = $this->getAccessToken();

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
                metrics.video_views,
                metrics.video_view_rate,
                metrics.average_cpv
            FROM campaign
            WHERE segments.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
              AND campaign.advertising_channel_type = 'VIDEO'
              AND campaign.status != 'REMOVED'
            ORDER BY segments.date DESC
        ";

        $response = $this->apiRequest('post', "{$this->baseUrl}/customers/{$customerId}/googleAds:searchStream", [
            'query' => $gaql,
        ], [
            'Authorization' => "Bearer {$accessToken}",
            'developer-token' => $devToken,
        ]);

        $rows = [];
        foreach ($response as $batch) {
            foreach ($batch['results'] ?? [] as $row) {
                $rows[] = $row;
            }
        }

        return $rows;
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
            'leads_count' => (int) ($metrics['conversions'] ?? 0),
            'status' => strtolower($campaign['status'] ?? 'active') === 'enabled' ? 'active' : 'paused',
        ];
    }

    // =====================================================
    // YOUTUBE-SPECIFIC METRICS
    // =====================================================

    /**
     * Fetch YouTube-specific video metrics (views, CPV, view rate).
     */
    public function getVideoMetrics(string $dateFrom, string $dateTo): array
    {
        return $this->fetchCampaigns($dateFrom, $dateTo);
    }

    protected function getAccessToken(): string
    {
        $response = $this->apiRequest('post', 'https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => config('services.google_ads.refresh_token'),
            'grant_type' => 'refresh_token',
        ]);

        return $response['access_token'] ?? throw new \RuntimeException('Failed to refresh Google token');
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// YouTubeAdsService — YouTube реклама через Google Ads API v17.
// Фильтр: advertising_channel_type = 'VIDEO' (только видео-кампании).
// Дополнительные метрики: video_views, video_view_rate, average_cpv.
// Синхронизация каждые 12 часов (W07 вместе с Pinterest).
// Файл: app/Services/Ads/YouTubeAdsService.php
// ---------------------------------------------------------------
