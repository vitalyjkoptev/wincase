<?php

namespace App\Services\SEO;

use App\Models\SeoData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AhrefsService
{
    protected string $baseUrl = 'https://api.ahrefs.com/v3';

    // =====================================================
    // SYNC ALL 4 DOMAINS
    // =====================================================

    /**
     * Sync DA + backlinks for all domains.
     * Called by n8n W09, weekly Mon 8:00.
     */
    public function syncAll(): array
    {
        $results = [];
        $date = now()->toDateString();

        foreach (SeoData::DOMAINS as $domain) {
            $results[$domain] = $this->syncDomain($domain, $date);
        }

        return $results;
    }

    public function syncDomain(string $domain, string $date): array
    {
        $apiKey = config('services.ahrefs.api_key');

        if (!$apiKey) {
            return ['success' => false, 'domain' => $domain, 'error' => 'No Ahrefs API key'];
        }

        try {
            // Domain Rating (DR)
            $drResponse = Http::withToken($apiKey, 'Bearer')
                ->get("{$this->baseUrl}/site-explorer/domain-rating", [
                    'target' => $domain,
                    'date' => $date,
                ]);

            $dr = $drResponse->json('domain_rating') ?? $drResponse->json('domainRating') ?? 0;

            // Backlinks overview
            $blResponse = Http::withToken($apiKey, 'Bearer')
                ->get("{$this->baseUrl}/site-explorer/backlinks-stats", [
                    'target' => $domain,
                    'date' => $date,
                ]);

            $backlinks = (int) ($blResponse->json('live') ?? $blResponse->json('backlinks') ?? 0);
            $refDomains = (int) ($blResponse->json('live_refdomains') ?? $blResponse->json('refdomains') ?? 0);

            SeoData::updateOrCreate(
                [
                    'domain' => $domain,
                    'date' => $date,
                    'source' => 'ahrefs',
                ],
                [
                    'domain_authority' => (int) round($dr),
                    'backlinks' => $backlinks,
                    'referring_domains' => $refDomains,
                    'raw_data' => [
                        'domain_rating' => $dr,
                        'backlinks_live' => $backlinks,
                        'refdomains_live' => $refDomains,
                    ],
                ]
            );

            return [
                'success' => true,
                'domain' => $domain,
                'dr' => round($dr),
                'backlinks' => $backlinks,
                'referring_domains' => $refDomains,
            ];

        } catch (\Exception $e) {
            Log::error("Ahrefs sync failed: {$domain}", ['error' => $e->getMessage()]);
            return ['success' => false, 'domain' => $domain, 'error' => $e->getMessage()];
        }
    }

    // =====================================================
    // NEW/LOST BACKLINKS
    // =====================================================

    public function getBacklinksChange(string $domain, int $days = 30): array
    {
        $apiKey = config('services.ahrefs.api_key');

        if (!$apiKey) return ['new' => 0, 'lost' => 0];

        try {
            $response = Http::withToken($apiKey, 'Bearer')
                ->get("{$this->baseUrl}/site-explorer/backlinks-new-lost-counters", [
                    'target' => $domain,
                    'date_from' => now()->subDays($days)->toDateString(),
                    'date_to' => now()->toDateString(),
                ]);

            return [
                'new_backlinks' => (int) ($response->json('new') ?? 0),
                'lost_backlinks' => (int) ($response->json('lost') ?? 0),
                'new_refdomains' => (int) ($response->json('new_refdomains') ?? 0),
                'lost_refdomains' => (int) ($response->json('lost_refdomains') ?? 0),
            ];
        } catch (\Exception $e) {
            return ['new_backlinks' => 0, 'lost_backlinks' => 0, 'error' => $e->getMessage()];
        }
    }

    // =====================================================
    // DA TREND (from local DB)
    // =====================================================

    public function getDaTrend(string $domain, int $weeks = 12): array
    {
        return SeoData::where('domain', $domain)
            ->where('source', 'ahrefs')
            ->where('date', '>=', now()->subWeeks($weeks)->toDateString())
            ->orderBy('date')
            ->get(['date', 'domain_authority', 'backlinks', 'referring_domains'])
            ->toArray();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AhrefsService — Domain Rating (DR), бэклинки, referring domains.
// syncAll() — еженедельная синхронизация 4 доменов (W09, Mon 8:00).
// Два API-запроса на домен: domain-rating + backlinks-stats.
// getBacklinksChange() — новые/потерянные бэклинки за N дней.
// getDaTrend() — тренд DA из локальной БД за 12 недель.
// Файл: app/Services/SEO/AhrefsService.php
// ---------------------------------------------------------------
