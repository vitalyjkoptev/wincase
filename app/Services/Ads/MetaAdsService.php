<?php

namespace App\Services\Ads;

use App\Enums\AdsPlatformEnum;
use Illuminate\Support\Facades\Http;

class MetaAdsService extends AbstractPlatformService
{
    protected string $baseUrl = 'https://graph.facebook.com/v19.0';

    protected function platform(): AdsPlatformEnum
    {
        return AdsPlatformEnum::META_ADS;
    }

    // =====================================================
    // FETCH CAMPAIGNS FROM META ADS API
    // =====================================================

    public function fetchCampaigns(string $dateFrom, string $dateTo): array
    {
        $adAccountId = config('services.meta.ad_account_id');
        $token = config('services.meta.system_user_token');

        $response = $this->apiRequest('get', "{$this->baseUrl}/act_{$adAccountId}/insights", [
            'access_token' => $token,
            'level' => 'campaign',
            'fields' => 'campaign_id,campaign_name,impressions,clicks,spend,actions,action_values,cost_per_action_type',
            'time_range' => json_encode([
                'since' => $dateFrom,
                'until' => $dateTo,
            ]),
            'time_increment' => 1,
            'limit' => 500,
        ]);

        return $response['data'] ?? [];
    }

    // =====================================================
    // NORMALIZE
    // =====================================================

    protected function normalizeRow(array $raw): array
    {
        $leads = $this->extractActionValue($raw['actions'] ?? [], 'lead');
        $conversions = $this->extractActionValue($raw['actions'] ?? [], 'offsite_conversion.fb_pixel_lead');
        $conversionValue = $this->extractActionValue($raw['action_values'] ?? [], 'offsite_conversion.fb_pixel_purchase');

        return [
            'campaign_id' => $raw['campaign_id'] ?? '',
            'campaign_name' => $raw['campaign_name'] ?? 'Unknown',
            'date' => $raw['date_start'] ?? now()->toDateString(),
            'impressions' => (int) ($raw['impressions'] ?? 0),
            'clicks' => (int) ($raw['clicks'] ?? 0),
            'cost' => round((float) ($raw['spend'] ?? 0), 2),
            'conversions' => (int) $conversions,
            'conversion_value' => round((float) $conversionValue, 2),
            'leads_count' => (int) max($leads, $conversions),
            'status' => 'active',
        ];
    }

    // =====================================================
    // FACEBOOK CAPI (Conversions API)
    // =====================================================

    /**
     * Send server-side event via Facebook Conversions API.
     * Called on lead creation (W18) and lead payment.
     */
    public function sendCapiEvent(
        string $eventName,
        array $userData,
        ?string $fbclid = null,
        ?float $value = null,
        ?string $currency = 'PLN'
    ): array {
        $pixelId = config('services.meta.pixel_id');
        $token = config('services.meta.system_user_token');

        $eventData = [
            'event_name' => $eventName,
            'event_time' => time(),
            'event_source_url' => $userData['landing_page'] ?? 'https://wincase.pro',
            'action_source' => 'website',
            'user_data' => array_filter([
                'em' => isset($userData['email']) ? [hash('sha256', strtolower($userData['email']))] : null,
                'ph' => isset($userData['phone']) ? [hash('sha256', $this->normalizePhone($userData['phone']))] : null,
                'fbc' => $fbclid ? "fb.1." . time() . ".{$fbclid}" : null,
                'client_ip_address' => $userData['ip_address'] ?? null,
                'client_user_agent' => $userData['user_agent'] ?? null,
                'country' => isset($userData['country']) ? [hash('sha256', strtolower($userData['country']))] : null,
            ]),
        ];

        if ($value !== null) {
            $eventData['custom_data'] = [
                'value' => $value,
                'currency' => $currency,
            ];
        }

        return $this->apiRequest('post', "{$this->baseUrl}/{$pixelId}/events", [
            'access_token' => $token,
            'data' => [json_encode([$eventData])],
        ]);
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function extractActionValue(array $actions, string $type): float
    {
        foreach ($actions as $action) {
            if (($action['action_type'] ?? '') === $type) {
                return (float) ($action['value'] ?? 0);
            }
        }

        return 0;
    }

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// MetaAdsService — синхронизация с Meta (Facebook/Instagram) Ads API v19.0.
// fetchCampaigns() — insights по кампаниям, daily granularity.
// Парсит actions[] для подсчёта лидов и конверсий.
// sendCapiEvent() — Facebook Conversions API (серверные события):
//   Lead (при создании лида с fbclid),
//   Purchase (при оплате). Хеширует email/phone через SHA-256.
// Файл: app/Services/Ads/MetaAdsService.php
// ---------------------------------------------------------------
