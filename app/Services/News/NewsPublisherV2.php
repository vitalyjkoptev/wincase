<?php

namespace App\Services\News;

use App\Models\NewsArticle;
use App\Models\NewsFeedLog;
use App\Events\NewsFeedEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsPublisherV2
{
    // =====================================================
    // PUBLISH ARTICLE TO ALL TARGET SITES
    // Reads targets from source registry, publishes to each
    // =====================================================

    public function publishToTargets(NewsArticle $article): array
    {
        $results = [];
        $source = NewsSourcesRegistryV2::getSources()[$article->source_key] ?? null;
        if (!$source) {
            $this->logFeed($article, 'error', 'Source not found: ' . $article->source_key);
            return ['error' => 'Source key not found'];
        }

        $targets = $source['targets'] ?? [];
        if (empty($targets)) return ['error' => 'No targets defined'];

        foreach ($targets as $targetStr) {
            [$siteId, $categorySlug] = explode(':', $targetStr);
            $site = NewsSitesRegistry::getSite($siteId);
            if (!$site) continue;

            $catConfig = $site['categories'][$categorySlug] ?? null;
            if (!$catConfig) continue;

            try {
                $result = $this->publishToSite($article, $site, $catConfig, $categorySlug);
                $results[$targetStr] = $result;

                $this->logFeed($article, 'published', "→ {$site['domain']} [{$catConfig['label_en']}]", [
                    'site' => $siteId,
                    'category' => $categorySlug,
                    'wp_post_id' => $result['post_id'] ?? null,
                ]);
            } catch (\Exception $e) {
                $results[$targetStr] = ['error' => $e->getMessage()];
                $this->logFeed($article, 'publish_failed', "✗ {$site['domain']}: {$e->getMessage()}", [
                    'site' => $siteId,
                    'category' => $categorySlug,
                ]);
            }
        }

        // Update article status
        $allOk = !empty($results) && collect($results)->every(fn($r) => !isset($r['error']));
        $article->update([
            'status' => $allOk ? 'published' : 'partial',
            'published_at' => now(),
            'publish_results' => $results,
            'published_sites' => array_keys(array_filter($results, fn($r) => !isset($r['error']))),
        ]);

        return $results;
    }

    // =====================================================
    // PUBLISH TO SINGLE SITE
    // =====================================================

    protected function publishToSite(NewsArticle $article, array $site, array $catConfig, string $catSlug): array
    {
        return match ($site['type']) {
            'wordpress' => $this->publishWordPress($article, $site, $catConfig),
            'laravel' => $this->publishLaravel($article, $site, $catConfig, $catSlug),
            default => throw new \Exception("Unknown site type: {$site['type']}"),
        };
    }

    // =====================================================
    // WORDPRESS REST API PUBLISH
    // =====================================================

    protected function publishWordPress(NewsArticle $article, array $site, array $catConfig): array
    {
        $apiUrl = $site['api_url'] . '/posts';
        $envKey = strtoupper($site['site_id']);

        $username = config("services.news_sites.{$site['site_id']}.wp_user",
            env("{$envKey}_WP_USER", 'admin'));
        $password = config("services.news_sites.{$site['site_id']}.wp_password",
            env("{$envKey}_WP_PASSWORD", ''));

        // Prepare content
        $title = $article->rewritten_title ?: $article->original_title;
        $content = $article->rewritten_content ?: $article->original_content;

        // Add source attribution
        $content .= "\n\n<p class=\"source-attribution\"><em>Source: <a href=\"{$article->original_url}\" rel=\"nofollow\" target=\"_blank\">{$article->source_name}</a></em></p>";

        $response = Http::withBasicAuth($username, $password)
            ->timeout(30)
            ->post($apiUrl, [
                'title' => $title,
                'content' => $content,
                'status' => 'publish',
                'categories' => [$catConfig['wp_id']],
                'excerpt' => \Str::limit(strip_tags($content), 300),
                'meta' => [
                    '_source_url' => $article->original_url,
                    '_source_name' => $article->source_name,
                    '_parsed_at' => $article->parsed_at,
                    '_category_slug' => $catConfig['label_en'] ?? '',
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception("WP API error [{$response->status()}]: " . $response->body());
        }

        $data = $response->json();
        return [
            'post_id' => $data['id'] ?? null,
            'url' => $data['link'] ?? null,
            'site' => $site['domain'],
            'category' => $catConfig['label_en'],
        ];
    }

    // =====================================================
    // LARAVEL API PUBLISH (wincase.pro)
    // =====================================================

    protected function publishLaravel(NewsArticle $article, array $site, array $catConfig, string $catSlug): array
    {
        $apiUrl = $site['api_url'] . '/posts';
        $apiKey = config("services.news_sites.{$site['site_id']}.api_key",
            env('WINCASE_BLOG_API_KEY', ''));

        $title = $article->rewritten_title ?: $article->original_title;
        $content = $article->rewritten_content ?: $article->original_content;

        $response = Http::withToken($apiKey)
            ->timeout(30)
            ->post($apiUrl, [
                'title' => $title,
                'content' => $content,
                'category' => $catSlug,
                'source_url' => $article->original_url,
                'source_name' => $article->source_name,
                'language' => $article->language,
                'status' => 'published',
            ]);

        if (!$response->successful()) {
            throw new \Exception("Laravel API error [{$response->status()}]");
        }

        $data = $response->json('data', []);
        return [
            'post_id' => $data['id'] ?? null,
            'url' => $data['url'] ?? null,
            'site' => $site['domain'],
            'category' => $catConfig['label_en'],
        ];
    }

    // =====================================================
    // PUBLISH ALL READY ARTICLES
    // Called by scheduler every 5 minutes
    // =====================================================

    public function publishAllReady(): array
    {
        $articles = NewsArticle::where('status', 'rewritten')
            ->where('plagiarism_score', '<', 15)
            ->orderBy('priority_score', 'desc')
            ->orderBy('parsed_at', 'asc')
            ->limit(20)
            ->get();

        $published = 0;
        $failed = 0;

        foreach ($articles as $article) {
            $results = $this->publishToTargets($article);
            $hasSuccess = collect($results)->contains(fn($r) => !isset($r['error']));
            $hasSuccess ? $published++ : $failed++;
        }

        $this->logFeed(null, 'batch_publish', "Published: {$published}, Failed: {$failed}");
        return ['published' => $published, 'failed' => $failed, 'total' => $articles->count()];
    }

    // =====================================================
    // PRIORITY PUBLISH — immediate for breaking news
    // =====================================================

    public function publishImmediate(NewsArticle $article): array
    {
        $this->logFeed($article, 'priority_publish', "⚡ BREAKING: {$article->rewritten_title}");
        return $this->publishToTargets($article);
    }

    // =====================================================
    // LIVE FEED LOG + WEBSOCKET BROADCAST
    // =====================================================

    protected function logFeed(?NewsArticle $article, string $action, string $message, array $meta = []): void
    {
        $log = NewsFeedLog::create([
            'article_id' => $article?->id,
            'source_key' => $article?->source_key,
            'source_name' => $article?->source_name,
            'action' => $action,
            'message' => $message,
            'meta' => $meta,
            'title' => $article?->rewritten_title ?: $article?->original_title,
        ]);

        // Broadcast to live feed channel
        try {
            broadcast(new NewsFeedEvent([
                'id' => $log->id,
                'action' => $action,
                'message' => $message,
                'title' => $log->title,
                'source' => $article?->source_name,
                'site' => $meta['site'] ?? null,
                'category' => $meta['category'] ?? null,
                'timestamp' => now()->toIso8601String(),
            ]));
        } catch (\Exception $e) {
            Log::warning('Feed broadcast failed: ' . $e->getMessage());
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NewsPublisherV2 — мульти-сайт публикатор.
// publishToTargets() — читает targets[] из source registry, публикует на каждый сайт.
// WordPress: REST API /wp/v2/posts с Basic Auth, categories, meta.
// Laravel: Bearer token API /posts.
// publishAllReady() — scheduler batch (каждые 5 мин), plagiarism < 15%.
// publishImmediate() — приоритетная публикация breaking news.
// logFeed() — запись в NewsFeedLog + WebSocket broadcast для live feed.
// Поддержка: 7 WordPress + 1 Laravel сайт. Partial publish если не все сайты ответили.
// Файл: app/Services/News/NewsPublisherV2.php
// ---------------------------------------------------------------
