<?php

namespace App\Services\SEO;

use App\Models\SeoData;
use App\Models\SeoNetworkSite;

class SeoOrchestrationService
{
    public function __construct(
        protected GscService $gsc,
        protected GA4Service $ga4,
        protected AhrefsService $ahrefs,
        protected SeoNetworkService $network,
    ) {}

    // =====================================================
    // OVERVIEW — All 4 domains combined
    // =====================================================

    public function getOverview(string $dateFrom, string $dateTo): array
    {
        $domainsData = [];

        foreach (SeoData::DOMAINS as $domain) {
            $gscData = SeoData::where('domain', $domain)
                ->where('source', 'gsc')
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->selectRaw('SUM(clicks) as clicks, SUM(impressions) as impressions, AVG(avg_position) as avg_position')
                ->first();

            $ga4Data = SeoData::where('domain', $domain)
                ->where('source', 'ga4')
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->selectRaw('SUM(users) as users, SUM(sessions) as sessions, SUM(conversions) as conversions')
                ->first();

            $latestAhrefs = SeoData::where('domain', $domain)
                ->where('source', 'ahrefs')
                ->orderByDesc('date')
                ->first();

            $domainsData[] = [
                'domain' => $domain,
                'gsc' => [
                    'clicks' => (int) ($gscData->clicks ?? 0),
                    'impressions' => (int) ($gscData->impressions ?? 0),
                    'avg_position' => round((float) ($gscData->avg_position ?? 0), 1),
                    'ctr' => ($gscData->impressions ?? 0) > 0
                        ? round(($gscData->clicks / $gscData->impressions) * 100, 2) : 0,
                ],
                'ga4' => [
                    'users' => (int) ($ga4Data->users ?? 0),
                    'sessions' => (int) ($ga4Data->sessions ?? 0),
                    'conversions' => (int) ($ga4Data->conversions ?? 0),
                ],
                'ahrefs' => [
                    'domain_authority' => (int) ($latestAhrefs->domain_authority ?? 0),
                    'backlinks' => (int) ($latestAhrefs->backlinks ?? 0),
                    'referring_domains' => (int) ($latestAhrefs->referring_domains ?? 0),
                ],
            ];
        }

        // Totals
        $totals = [
            'clicks' => array_sum(array_column(array_column($domainsData, 'gsc'), 'clicks')),
            'impressions' => array_sum(array_column(array_column($domainsData, 'gsc'), 'impressions')),
            'users' => array_sum(array_column(array_column($domainsData, 'ga4'), 'users')),
            'sessions' => array_sum(array_column(array_column($domainsData, 'ga4'), 'sessions')),
            'conversions' => array_sum(array_column(array_column($domainsData, 'ga4'), 'conversions')),
            'max_da' => max(array_column(array_column($domainsData, 'ahrefs'), 'domain_authority')),
        ];

        return [
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
            'domains' => $domainsData,
            'totals' => $totals,
        ];
    }

    // =====================================================
    // KEYWORDS — Top keywords for a domain
    // =====================================================

    public function getKeywords(string $domain, int $limit = 50, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return [
            'domain' => $domain,
            'keywords' => $this->gsc->getTopKeywords($domain, $limit, $dateFrom, $dateTo),
        ];
    }

    // =====================================================
    // BACKLINKS — Trend + new/lost
    // =====================================================

    public function getBacklinks(string $domain, int $days = 30): array
    {
        return [
            'domain' => $domain,
            'trend' => $this->ahrefs->getDaTrend($domain, (int) ceil($days / 7)),
            'changes' => $this->ahrefs->getBacklinksChange($domain, $days),
        ];
    }

    // =====================================================
    // SEO NETWORK — 8 satellite sites
    // =====================================================

    public function getNetwork(): array
    {
        return $this->network->getNetworkOverview();
    }

    // =====================================================
    // REVIEWS — From reviews table (shared with Brand)
    // =====================================================

    public function getReviews(): array
    {
        $reviews = \App\Models\Review::selectRaw('
                platform,
                COUNT(*) as total,
                AVG(rating) as avg_rating,
                SUM(CASE WHEN reply IS NOT NULL THEN 1 ELSE 0 END) as replied,
                MAX(published_at) as latest_at
            ')
            ->groupBy('platform')
            ->get()
            ->toArray();

        $totalCount = array_sum(array_column($reviews, 'total'));
        $weightedAvg = $totalCount > 0
            ? array_sum(array_map(fn ($r) => $r['avg_rating'] * $r['total'], $reviews)) / $totalCount
            : 0;

        return [
            'platforms' => $reviews,
            'totals' => [
                'total_reviews' => $totalCount,
                'avg_rating' => round($weightedAvg, 1),
                'reply_rate' => $totalCount > 0
                    ? round(array_sum(array_column($reviews, 'replied')) / $totalCount * 100, 1) : 0,
            ],
        ];
    }

    // =====================================================
    // BRAND — Trademark + Wikipedia + Knowledge Panel
    // =====================================================

    public function getBrandStatus(): array
    {
        $listings = \App\Models\BrandListing::selectRaw('
                status,
                COUNT(*) as count,
                SUM(CASE WHEN nap_consistent = 1 THEN 1 ELSE 0 END) as nap_ok
            ')
            ->groupBy('status')
            ->get()
            ->toArray();

        $totalListings = array_sum(array_column($listings, 'count'));
        $totalNapOk = array_sum(array_column($listings, 'nap_ok'));

        return [
            'listings' => $listings,
            'nap_consistency' => $totalListings > 0
                ? round(($totalNapOk / $totalListings) * 100, 1) : 0,
            'total_listed' => $totalListings,
        ];
    }

    // =====================================================
    // SYNC ALL (for n8n W08 + W09)
    // =====================================================

    public function syncDaily(?string $dateFrom = null, ?string $dateTo = null): array
    {
        return [
            'gsc' => $this->gsc->syncAll($dateFrom, $dateTo),
            'ga4' => $this->ga4->syncAll($dateFrom, $dateTo),
        ];
    }

    public function syncWeekly(): array
    {
        return [
            'ahrefs' => $this->ahrefs->syncAll(),
            'network' => $this->network->checkArticleStatus(),
        ];
    }

    // =====================================================
    // DASHBOARD WIDGET
    // =====================================================

    public function getDashboardStats(): array
    {
        $last7 = now()->subDays(7)->toDateString();
        $today = now()->toDateString();

        $gsc = SeoData::where('source', 'gsc')
            ->whereBetween('date', [$last7, $today])
            ->selectRaw('SUM(clicks) as clicks, SUM(impressions) as impressions')
            ->first();

        $ga4 = SeoData::where('source', 'ga4')
            ->whereBetween('date', [$last7, $today])
            ->selectRaw('SUM(users) as users')
            ->first();

        $mainDa = SeoData::where('domain', 'wincase.pro')
            ->where('source', 'ahrefs')
            ->orderByDesc('date')
            ->value('domain_authority') ?? 0;

        return [
            'period' => '7d',
            'gsc_clicks' => (int) ($gsc->clicks ?? 0),
            'gsc_impressions' => (int) ($gsc->impressions ?? 0),
            'organic_users' => (int) ($ga4->users ?? 0),
            'main_da' => (int) $mainDa,
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// SeoOrchestrationService — единый сервис SEO модуля.
// getOverview() — сводка 4 доменов: GSC (clicks, impressions, position),
// GA4 (users, sessions, conversions), Ahrefs (DA, backlinks, ref.domains).
// getKeywords() — топ keywords через GscService.
// getBacklinks() — тренд DA + new/lost через AhrefsService.
// getNetwork() — 8 сателлитов через SeoNetworkService.
// getReviews() — агрегация отзывов из reviews таблицы.
// getBrandStatus() — NAP consistency из brand_listings.
// syncDaily() — GSC + GA4 (W08). syncWeekly() — Ahrefs + network (W09, W20).
// getDashboardStats() — виджет: clicks, users, DA за 7 дней.
// Файл: app/Services/SEO/SeoOrchestrationService.php
// ---------------------------------------------------------------
