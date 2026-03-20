<?php

namespace App\Services\SEO;

use App\Models\SeoData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GscService
{
    protected string $baseUrl = 'https://www.googleapis.com/webmasters/v3';
    protected string $searchAnalyticsUrl = 'https://searchconsole.googleapis.com/v1';

    // =====================================================
    // SYNC ALL 4 DOMAINS
    // =====================================================

    /**
     * Sync GSC data for all 4 WinCase domains.
     * Called by n8n W08 daily at 6:00.
     */
    public function syncAll(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(3)->toDateString();
        $dateTo = $dateTo ?? now()->subDay()->toDateString();
        $results = [];

        foreach (SeoData::DOMAINS as $domain) {
            $results[$domain] = $this->syncDomain($domain, $dateFrom, $dateTo);
        }

        return $results;
    }

    // =====================================================
    // SYNC SINGLE DOMAIN
    // =====================================================

    public function syncDomain(string $domain, string $dateFrom, string $dateTo): array
    {
        $accessToken = $this->getAccessToken();
        $siteUrl = "sc-domain:{$domain}";

        try {
            $response = Http::withToken($accessToken)
                ->post("{$this->searchAnalyticsUrl}/sites/{$siteUrl}/searchAnalytics/query", [
                    'startDate' => $dateFrom,
                    'endDate' => $dateTo,
                    'dimensions' => ['date'],
                    'rowLimit' => 25000,
                    'type' => 'web',
                ]);

            if (!$response->successful()) {
                throw new \RuntimeException("GSC API error [{$response->status()}]: {$response->body()}");
            }

            $rows = $response->json('rows') ?? [];
            $upserted = 0;

            foreach ($rows as $row) {
                $date = $row['keys'][0] ?? null;
                if (!$date) continue;

                SeoData::updateOrCreate(
                    [
                        'domain' => $domain,
                        'date' => $date,
                        'source' => 'gsc',
                    ],
                    [
                        'clicks' => (int) ($row['clicks'] ?? 0),
                        'impressions' => (int) ($row['impressions'] ?? 0),
                        'avg_position' => round((float) ($row['position'] ?? 0), 2),
                        'raw_data' => $row,
                    ]
                );
                $upserted++;
            }

            return ['success' => true, 'domain' => $domain, 'rows' => $upserted];

        } catch (\Exception $e) {
            Log::error("GSC sync failed: {$domain}", ['error' => $e->getMessage()]);
            return ['success' => false, 'domain' => $domain, 'error' => $e->getMessage()];
        }
    }

    // =====================================================
    // TOP KEYWORDS
    // =====================================================

    /**
     * Fetch top keywords for a domain from GSC.
     */
    public function getTopKeywords(string $domain, int $limit = 50, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(28)->toDateString();
        $dateTo = $dateTo ?? now()->subDay()->toDateString();
        $accessToken = $this->getAccessToken();
        $siteUrl = "sc-domain:{$domain}";

        $response = Http::withToken($accessToken)
            ->post("{$this->searchAnalyticsUrl}/sites/{$siteUrl}/searchAnalytics/query", [
                'startDate' => $dateFrom,
                'endDate' => $dateTo,
                'dimensions' => ['query'],
                'rowLimit' => $limit,
                'type' => 'web',
            ]);

        $rows = $response->json('rows') ?? [];

        return array_map(fn ($row) => [
            'keyword' => $row['keys'][0] ?? '',
            'clicks' => (int) ($row['clicks'] ?? 0),
            'impressions' => (int) ($row['impressions'] ?? 0),
            'ctr' => round((float) ($row['ctr'] ?? 0) * 100, 2),
            'position' => round((float) ($row['position'] ?? 0), 1),
        ], $rows);
    }

    // =====================================================
    // TOP PAGES
    // =====================================================

    public function getTopPages(string $domain, int $limit = 20, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(28)->toDateString();
        $dateTo = $dateTo ?? now()->subDay()->toDateString();
        $accessToken = $this->getAccessToken();
        $siteUrl = "sc-domain:{$domain}";

        $response = Http::withToken($accessToken)
            ->post("{$this->searchAnalyticsUrl}/sites/{$siteUrl}/searchAnalytics/query", [
                'startDate' => $dateFrom,
                'endDate' => $dateTo,
                'dimensions' => ['page'],
                'rowLimit' => $limit,
                'type' => 'web',
            ]);

        $rows = $response->json('rows') ?? [];

        return array_map(fn ($row) => [
            'page' => $row['keys'][0] ?? '',
            'clicks' => (int) ($row['clicks'] ?? 0),
            'impressions' => (int) ($row['impressions'] ?? 0),
            'ctr' => round((float) ($row['ctr'] ?? 0) * 100, 2),
            'position' => round((float) ($row['position'] ?? 0), 1),
        ], $rows);
    }

    // =====================================================
    // AUTH
    // =====================================================

    protected function getAccessToken(): string
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => config('services.google.refresh_token'),
            'grant_type' => 'refresh_token',
        ]);

        return $response->json('access_token')
            ?? throw new \RuntimeException('Failed to refresh Google token for GSC');
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// GscService — Google Search Console API для 4 доменов WinCase.
// syncAll() — синхронизация всех доменов (W08, daily 6:00).
// syncDomain() — upsert в seo_data (UNIQUE: domain+date+source=gsc).
// getTopKeywords() — топ-50 ключевых слов: clicks, impressions, CTR, position.
// getTopPages() — топ-20 страниц по кликам.
// OAuth 2.0 refresh_token → access_token автоматически.
// Файл: app/Services/SEO/GscService.php
// ---------------------------------------------------------------
