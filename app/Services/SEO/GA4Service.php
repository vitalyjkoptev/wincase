<?php

namespace App\Services\SEO;

use App\Models\SeoData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GA4Service
{
    protected string $baseUrl = 'https://analyticsdata.googleapis.com/v1beta';

    /**
     * GA4 property IDs for each domain (from .env).
     */
    protected function getPropertyId(string $domain): ?string
    {
        return match ($domain) {
            'wincase.pro' => config('services.ga4.property_wincase_pro'),
            'wincase-legalization.com' => config('services.ga4.property_legalization'),
            'wincase-job.com' => config('services.ga4.property_job'),
            'wincase.org' => config('services.ga4.property_org'),
            default => null,
        };
    }

    // =====================================================
    // SYNC ALL 4 DOMAINS
    // =====================================================

    public function syncAll(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(2)->toDateString();
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
        $propertyId = $this->getPropertyId($domain);

        if (!$propertyId) {
            return ['success' => false, 'domain' => $domain, 'error' => 'No GA4 property configured'];
        }

        $accessToken = $this->getAccessToken();

        try {
            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
                    'dateRanges' => [['startDate' => $dateFrom, 'endDate' => $dateTo]],
                    'dimensions' => [['name' => 'date']],
                    'metrics' => [
                        ['name' => 'totalUsers'],
                        ['name' => 'sessions'],
                        ['name' => 'conversions'],
                        ['name' => 'newUsers'],
                        ['name' => 'bounceRate'],
                        ['name' => 'averageSessionDuration'],
                    ],
                ]);

            if (!$response->successful()) {
                throw new \RuntimeException("GA4 API error [{$response->status()}]");
            }

            $rows = $response->json('rows') ?? [];
            $upserted = 0;

            foreach ($rows as $row) {
                $date = $row['dimensionValues'][0]['value'] ?? null;
                if (!$date) continue;

                // GA4 returns date as YYYYMMDD
                $formattedDate = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);

                $metrics = $row['metricValues'] ?? [];

                SeoData::updateOrCreate(
                    [
                        'domain' => $domain,
                        'date' => $formattedDate,
                        'source' => 'ga4',
                    ],
                    [
                        'users' => (int) ($metrics[0]['value'] ?? 0),
                        'sessions' => (int) ($metrics[1]['value'] ?? 0),
                        'conversions' => (int) ($metrics[2]['value'] ?? 0),
                        'raw_data' => [
                            'new_users' => (int) ($metrics[3]['value'] ?? 0),
                            'bounce_rate' => round((float) ($metrics[4]['value'] ?? 0), 4),
                            'avg_session_duration' => round((float) ($metrics[5]['value'] ?? 0), 1),
                        ],
                    ]
                );
                $upserted++;
            }

            return ['success' => true, 'domain' => $domain, 'rows' => $upserted];

        } catch (\Exception $e) {
            Log::error("GA4 sync failed: {$domain}", ['error' => $e->getMessage()]);
            return ['success' => false, 'domain' => $domain, 'error' => $e->getMessage()];
        }
    }

    // =====================================================
    // TRAFFIC BY SOURCE
    // =====================================================

    public function getTrafficSources(string $domain, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(30)->toDateString();
        $dateTo = $dateTo ?? now()->subDay()->toDateString();
        $propertyId = $this->getPropertyId($domain);

        if (!$propertyId) return [];

        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
                'dateRanges' => [['startDate' => $dateFrom, 'endDate' => $dateTo]],
                'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
                'metrics' => [
                    ['name' => 'totalUsers'],
                    ['name' => 'sessions'],
                    ['name' => 'conversions'],
                ],
                'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                'limit' => 20,
            ]);

        return array_map(fn ($row) => [
            'channel' => $row['dimensionValues'][0]['value'] ?? 'Unknown',
            'users' => (int) ($row['metricValues'][0]['value'] ?? 0),
            'sessions' => (int) ($row['metricValues'][1]['value'] ?? 0),
            'conversions' => (int) ($row['metricValues'][2]['value'] ?? 0),
        ], $response->json('rows') ?? []);
    }

    // =====================================================
    // TOP LANDING PAGES
    // =====================================================

    public function getTopLandingPages(string $domain, int $limit = 20, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? now()->subDays(30)->toDateString();
        $dateTo = $dateTo ?? now()->subDay()->toDateString();
        $propertyId = $this->getPropertyId($domain);

        if (!$propertyId) return [];

        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
                'dateRanges' => [['startDate' => $dateFrom, 'endDate' => $dateTo]],
                'dimensions' => [['name' => 'landingPage']],
                'metrics' => [
                    ['name' => 'sessions'],
                    ['name' => 'totalUsers'],
                    ['name' => 'conversions'],
                    ['name' => 'bounceRate'],
                ],
                'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
                'limit' => $limit,
            ]);

        return array_map(fn ($row) => [
            'page' => $row['dimensionValues'][0]['value'] ?? '',
            'sessions' => (int) ($row['metricValues'][0]['value'] ?? 0),
            'users' => (int) ($row['metricValues'][1]['value'] ?? 0),
            'conversions' => (int) ($row['metricValues'][2]['value'] ?? 0),
            'bounce_rate' => round((float) ($row['metricValues'][3]['value'] ?? 0) * 100, 1),
        ], $response->json('rows') ?? []);
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
            ?? throw new \RuntimeException('Failed to refresh Google token for GA4');
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// GA4Service — Google Analytics 4 Data API для 4 доменов.
// syncAll() — синхронизация: users, sessions, conversions, bounce_rate (W08).
// Дата из GA4: YYYYMMDD → YYYY-MM-DD. Property ID из .env для каждого домена.
// getTrafficSources() — каналы трафика (Organic, Paid, Social, Direct...).
// getTopLandingPages() — топ лендингов по сессиям (для Landings module).
// Файл: app/Services/SEO/GA4Service.php
// ---------------------------------------------------------------
