<?php

namespace App\Services\News;

use App\Models\NewsArticle;
use App\Models\NewsPublishLog;
use App\Events\NewsFeedEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsPublisherService
{
    // =====================================================
    // AUTO-PUBLISH PIPELINE
    // =====================================================

    public function publishReady(): array
    {
        $results = ['published' => 0, 'failed' => 0, 'queued' => 0];

        // Get rewritten articles ready for publishing
        $articles = NewsArticle::where('status', 'rewritten')
            ->where('plagiarism_score', '<', 15)
            ->whereNull('parent_article_id') // originals only, not translations
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
            ->orderBy('published_at')
            ->limit(20)
            ->get();

        foreach ($articles as $article) {
            $mapping = NewsSourcesRegistry::getCategoryMapping()[$article->category] ?? null;
            if (!$mapping || !$mapping['auto_publish']) continue;

            foreach ($mapping['target_sites'] as $siteKey) {
                $site = NewsSourcesRegistry::getTargetSites()[$siteKey] ?? null;
                if (!$site) continue;

                try {
                    $publishResult = $this->publishToSite($article, $site, $siteKey, $mapping);

                    if ($publishResult['success']) {
                        $results['published']++;
                    } else {
                        $results['failed']++;
                    }
                } catch (\Exception $e) {
                    $results['failed']++;
                    $this->logPublish($article, $siteKey, 'failed', $e->getMessage());

                    broadcast(new NewsFeedEvent([
                        'type' => 'publish_error',
                        'article_id' => $article->id,
                        'site' => $siteKey,
                        'error' => Str::limit($e->getMessage(), 100),
                        'timestamp' => now()->toIso8601String(),
                    ]));
                }
            }

            // Queue translations
            if (!empty($mapping['translate_to'])) {
                foreach ($mapping['translate_to'] as $lang) {
                    if ($lang === $article->original_language) continue;
                    $results['queued']++;

                    // Dispatch translation job
                    dispatch(new \App\Jobs\TranslateAndPublishJob($article->id, $lang));
                }
            }
        }

        return $results;
    }

    // =====================================================
    // PUBLISH TO SPECIFIC SITE
    // =====================================================

    protected function publishToSite(NewsArticle $article, array $site, string $siteKey, array $mapping): array
    {
        $result = match ($site['cms']) {
            'wordpress' => $this->publishToWordPress($article, $site, $mapping),
            'laravel' => $this->publishToLaravel($article, $site, $mapping),
            default => throw new \RuntimeException("Unknown CMS: {$site['cms']}"),
        };

        if ($result['success']) {
            $article->update([
                'status' => 'published',
                'published_to' => array_merge($article->published_to ?? [], [$siteKey]),
                'published_urls' => array_merge($article->published_urls ?? [], [$result['url'] ?? '']),
                'last_published_at' => now(),
            ]);

            $this->logPublish($article, $siteKey, 'success', null, $result['url'] ?? null);

            broadcast(new NewsFeedEvent([
                'type' => 'published',
                'article_id' => $article->id,
                'title' => Str::limit($article->rewritten_title, 80),
                'source' => $article->source_name,
                'target_site' => $site['domain'],
                'category' => $article->category,
                'language' => $article->rewritten_language,
                'url' => $result['url'] ?? null,
                'timestamp' => now()->toIso8601String(),
            ]));
        }

        return $result;
    }

    // =====================================================
    // WORDPRESS REST API
    // =====================================================

    protected function publishToWordPress(NewsArticle $article, array $site, array $mapping): array
    {
        $apiKey = config("services.news_sites.{$site['api_key_env']}") ?: env($site['api_key_env']);
        $endpoint = rtrim($site['api_endpoint'], '/');

        // WP REST API v2
        $wpEndpoint = preg_replace('/\/api\/v1\/posts$/', '/wp-json/wp/v2/posts', $endpoint);

        $payload = [
            'title' => $article->rewritten_title,
            'content' => $this->formatHTMLContent($article->rewritten_content),
            'excerpt' => $article->rewritten_description,
            'status' => 'publish',
            'slug' => $article->seo_slug,
            'categories' => $this->getWPCategoryIds($mapping['target_categories'] ?? []),
            'meta' => [
                '_yoast_wpseo_title' => $article->seo_meta_title,
                '_yoast_wpseo_metadesc' => $article->seo_meta_description,
                '_yoast_wpseo_focuskw' => $article->seo_keywords,
            ],
        ];

        // Upload featured image if available
        if ($article->image_url) {
            $mediaId = $this->uploadWPMedia($wpEndpoint, $article->image_url, $apiKey);
            if ($mediaId) $payload['featured_media'] = $mediaId;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($wpEndpoint, $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'post_id' => $response->json('id'),
                'url' => $response->json('link'),
            ];
        }

        throw new \RuntimeException("WP publish failed: {$response->status()} — {$response->body()}");
    }

    // =====================================================
    // LARAVEL API
    // =====================================================

    protected function publishToLaravel(NewsArticle $article, array $site, array $mapping): array
    {
        $apiKey = config("services.news_sites.{$site['api_key_env']}") ?: env($site['api_key_env']);

        $payload = [
            'title' => $article->rewritten_title,
            'content' => $article->rewritten_content,
            'description' => $article->rewritten_description,
            'slug' => $article->seo_slug,
            'category' => $mapping['target_categories'][0] ?? 'news',
            'language' => $article->rewritten_language,
            'seo_title' => $article->seo_meta_title,
            'seo_description' => $article->seo_meta_description,
            'seo_keywords' => $article->seo_keywords,
            'image_url' => $article->image_url,
            'status' => 'published',
            'source' => $article->source_name,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($site['api_endpoint'], $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'post_id' => $response->json('data.id'),
                'url' => $response->json('data.url'),
            ];
        }

        throw new \RuntimeException("Laravel publish failed: {$response->status()}");
    }

    // =====================================================
    // SCHEDULE PUBLISHER
    // =====================================================

    public function getPublishSchedule(): array
    {
        return [
            'critical' => ['interval' => 'immediate', 'description' => 'Publish within 2 min of parsing (immigration, gov, PAP)'],
            'high' => ['interval' => '5_min', 'description' => 'Publish within 5 min (TVN24, Polsat, Reuters, BBC)'],
            'medium' => ['interval' => '15_min', 'description' => 'Batch publish every 15 min'],
            'low' => ['interval' => '30_min', 'description' => 'Batch publish every 30 min'],
        ];
    }

    // =====================================================
    // STATISTICS
    // =====================================================

    public function getStatistics(int $days = 7): array
    {
        $since = now()->subDays($days);

        $byStatus = NewsArticle::where('created_at', '>=', $since)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $byCategory = NewsArticle::where('created_at', '>=', $since)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        $bySource = NewsArticle::where('created_at', '>=', $since)
            ->selectRaw('source_name, COUNT(*) as count')
            ->groupBy('source_name')
            ->orderByDesc('count')
            ->limit(15)
            ->get()
            ->toArray();

        $daily = NewsArticle::where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->toArray();

        $avgPlagiarism = NewsArticle::where('created_at', '>=', $since)
            ->whereNotNull('plagiarism_score')
            ->avg('plagiarism_score');

        $publishLogs = NewsPublishLog::where('created_at', '>=', $since)
            ->selectRaw('target_site, status, COUNT(*) as count')
            ->groupBy('target_site', 'status')
            ->get()
            ->toArray();

        return [
            'total_parsed' => array_sum($byStatus),
            'by_status' => $byStatus,
            'by_category' => $byCategory,
            'by_source' => $bySource,
            'daily_trend' => $daily,
            'avg_plagiarism_score' => round($avgPlagiarism ?? 0, 1),
            'publish_logs' => $publishLogs,
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function formatHTMLContent(string $content): string
    {
        $paragraphs = array_filter(explode("\n", $content));
        return implode("\n", array_map(fn ($p) => "<p>" . trim($p) . "</p>", $paragraphs));
    }

    protected function getWPCategoryIds(array $categories): array
    {
        // Cached WP category mapping
        return Cache::remember('wp_category_map', 3600, function () {
            return [
                'news' => 1, 'polska' => 2, 'business' => 3, 'ekonomia' => 4,
                'immigration' => 5, 'legalizacja' => 6, 'law' => 7, 'prawo' => 8,
                'world' => 9, 'eu' => 10, 'ukraine' => 11, 'ukraina' => 12,
                'tech' => 13, 'technologie' => 14, 'sport' => 15,
            ];
        });
    }

    protected function uploadWPMedia(string $baseUrl, string $imageUrl, string $apiKey): ?int
    {
        try {
            $image = Http::get($imageUrl);
            if (!$image->successful()) return null;

            $mediaUrl = preg_replace('/\/posts$/', '/media', $baseUrl);
            $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Disposition' => "attachment; filename=\"news-" . Str::random(8) . ".{$ext}\"",
                'Content-Type' => $image->header('Content-Type'),
            ])->withBody($image->body(), $image->header('Content-Type'))
              ->post($mediaUrl);

            return $response->json('id');
        } catch (\Exception) {
            return null;
        }
    }

    protected function logPublish(NewsArticle $article, string $siteKey, string $status, ?string $error = null, ?string $url = null): void
    {
        NewsPublishLog::create([
            'news_article_id' => $article->id,
            'target_site' => $siteKey,
            'status' => $status,
            'error_message' => $error,
            'published_url' => $url,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NewsPublisherService — авто-публикация на target sites.
// publishReady() — берёт rewritten (plagiarism <15%) → публикует по маппингу.
// publishToWordPress() — WP REST API v2 (title, content, excerpt, categories, SEO meta, featured image).
// publishToLaravel() — Laravel API (title, content, slug, category, language).
// Publish schedule: critical=immediate, high=5min, medium=15min, low=30min.
// getStatistics() — by status, category, source, daily trend, avg plagiarism.
// Broadcast NewsFeedEvent на каждом этапе.
// Файл: app/Services/News/NewsPublisherService.php
// ---------------------------------------------------------------
