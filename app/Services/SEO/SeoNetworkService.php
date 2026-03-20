<?php

namespace App\Services\SEO;

use App\Models\SeoNetworkSite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SeoNetworkService
{
    // =====================================================
    // GET ALL NETWORK SITES
    // =====================================================

    public function getNetworkOverview(): array
    {
        $sites = SeoNetworkSite::orderByDesc('domain_authority')->get();

        $totalArticles = $sites->sum('articles_total');
        $totalWithBacklink = $sites->sum('articles_with_backlink');

        return [
            'sites' => $sites->toArray(),
            'totals' => [
                'total_sites' => $sites->count(),
                'active_sites' => $sites->where('status', 'active')->count(),
                'total_articles' => $totalArticles,
                'articles_with_backlink' => $totalWithBacklink,
                'backlink_ratio' => $totalArticles > 0
                    ? round(($totalWithBacklink / $totalArticles) * 100, 1) : 0,
                'avg_da' => round($sites->where('status', 'active')->avg('domain_authority'), 0),
            ],
            'needs_article' => SeoNetworkSite::needsArticle(30)
                ->pluck('domain')
                ->toArray(),
        ];
    }

    // =====================================================
    // CHECK ARTICLE STATUS (W20)
    // =====================================================

    /**
     * Check if satellite sites have published new articles.
     * Called by n8n W20, weekly.
     */
    public function checkArticleStatus(): array
    {
        $results = [];

        $sites = SeoNetworkSite::active()->get();

        foreach ($sites as $site) {
            try {
                // Simple HTTP check: fetch sitemap or RSS for latest article date
                $response = Http::timeout(10)->get("https://{$site->domain}/sitemap.xml");

                if ($response->successful()) {
                    $lastMod = $this->extractLatestDateFromSitemap($response->body());

                    if ($lastMod) {
                        $site->update(['last_article_at' => $lastMod]);
                    }

                    $results[$site->domain] = [
                        'success' => true,
                        'last_article_at' => $lastMod,
                        'days_since' => $site->fresh()->daysSinceLastArticle,
                    ];
                } else {
                    $results[$site->domain] = [
                        'success' => false,
                        'error' => "HTTP {$response->status()}",
                    ];
                }
            } catch (\Exception $e) {
                $results[$site->domain] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    // =====================================================
    // UPDATE NETWORK DA
    // =====================================================

    /**
     * Update DA for all network sites via Ahrefs.
     */
    public function updateNetworkDa(AhrefsService $ahrefs): array
    {
        $results = [];
        $sites = SeoNetworkSite::active()->get();

        foreach ($sites as $site) {
            $syncResult = $ahrefs->syncDomain($site->domain, now()->toDateString());

            if ($syncResult['success'] ?? false) {
                $site->update(['domain_authority' => $syncResult['dr'] ?? 0]);
                $results[$site->domain] = $syncResult['dr'] ?? 0;
            }
        }

        return $results;
    }

    // =====================================================
    // CRUD
    // =====================================================

    public function addSite(array $data): SeoNetworkSite
    {
        return SeoNetworkSite::create($data);
    }

    public function updateSite(SeoNetworkSite $site, array $data): SeoNetworkSite
    {
        $site->update($data);
        return $site->fresh();
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function extractLatestDateFromSitemap(string $xml): ?string
    {
        try {
            preg_match_all('/<lastmod>([^<]+)<\/lastmod>/', $xml, $matches);

            if (empty($matches[1])) {
                return null;
            }

            $dates = array_map(fn ($d) => strtotime($d), $matches[1]);
            $latest = max($dates);

            return date('Y-m-d H:i:s', $latest);
        } catch (\Exception $e) {
            return null;
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// SeoNetworkService — управление 8 сателлитными SEO-сайтами.
// getNetworkOverview() — сводка: сайты, DA, статьи, backlink ratio.
// checkArticleStatus() — проверка sitemap.xml на новые публикации (W20).
// updateNetworkDa() — обновление DA через AhrefsService.
// CRUD для добавления/обновления сайтов сети.
// Файл: app/Services/SEO/SeoNetworkService.php
// ---------------------------------------------------------------
