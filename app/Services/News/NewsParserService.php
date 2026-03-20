<?php

namespace App\Services\News;

use App\Models\NewsArticle;
use App\Models\NewsSource;
use App\Events\NewsFeedEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsParserService
{
    // =====================================================
    // PARSE ALL SOURCES BY PRIORITY
    // =====================================================

    public function parseAll(?string $priority = null): array
    {
        $sources = NewsSourcesRegistry::getSources();
        $results = ['parsed' => 0, 'new' => 0, 'duplicates' => 0, 'errors' => 0];

        // Sort by priority: critical → high → medium → low
        $priorityOrder = ['critical' => 0, 'high' => 1, 'medium' => 2, 'low' => 3];
        uasort($sources, fn ($a, $b) => ($priorityOrder[$a['priority']] ?? 9) <=> ($priorityOrder[$b['priority']] ?? 9));

        foreach ($sources as $key => $source) {
            if ($priority && $source['priority'] !== $priority) continue;

            try {
                $articles = match ($source['type']) {
                    'rss' => $this->parseRSS($source),
                    'scrape' => $this->parseScrape($source),
                    default => [],
                };

                foreach ($articles as $article) {
                    $results['parsed']++;

                    if ($this->isDuplicate($article['url'], $article['title'])) {
                        $results['duplicates']++;
                        continue;
                    }

                    $saved = NewsArticle::create([
                        'source_key' => $key,
                        'source_name' => $source['name'],
                        'source_url' => $article['url'],
                        'original_title' => $article['title'],
                        'original_content' => $article['content'] ?? '',
                        'original_description' => $article['description'] ?? '',
                        'original_language' => $source['language'],
                        'category' => $source['category'],
                        'priority' => $source['priority'],
                        'image_url' => $article['image'] ?? null,
                        'published_at' => $article['published_at'] ?? now(),
                        'status' => 'parsed',
                    ]);

                    $results['new']++;

                    // Broadcast to live feed
                    broadcast(new NewsFeedEvent([
                        'type' => 'parsed',
                        'article_id' => $saved->id,
                        'source' => $source['name'],
                        'title' => Str::limit($article['title'], 80),
                        'category' => $source['category'],
                        'priority' => $source['priority'],
                        'timestamp' => now()->toIso8601String(),
                    ]));
                }
            } catch (\Exception $e) {
                $results['errors']++;
                Log::error("News parser error [{$key}]: {$e->getMessage()}");

                broadcast(new NewsFeedEvent([
                    'type' => 'error',
                    'source' => $source['name'] ?? $key,
                    'message' => Str::limit($e->getMessage(), 100),
                    'timestamp' => now()->toIso8601String(),
                ]));
            }
        }

        return $results;
    }

    // =====================================================
    // PARSE SINGLE SOURCE
    // =====================================================

    public function parseSource(string $sourceKey): array
    {
        $sources = NewsSourcesRegistry::getSources();
        if (!isset($sources[$sourceKey])) {
            throw new \InvalidArgumentException("Unknown source: {$sourceKey}");
        }

        $source = $sources[$sourceKey];
        return match ($source['type']) {
            'rss' => $this->parseRSS($source),
            'scrape' => $this->parseScrape($source),
            default => [],
        };
    }

    // =====================================================
    // RSS PARSER
    // =====================================================

    protected function parseRSS(array $source): array
    {
        $response = Http::timeout(15)
            ->withHeaders(['User-Agent' => 'WinCase-NewsBot/1.0'])
            ->get($source['url']);

        if (!$response->successful()) {
            throw new \RuntimeException("RSS fetch failed: HTTP {$response->status()}");
        }

        $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            throw new \RuntimeException("Invalid XML from {$source['name']}");
        }

        $articles = [];

        // Standard RSS 2.0
        $items = $xml->channel->item ?? $xml->item ?? [];

        foreach ($items as $item) {
            $title = trim((string) $item->title);
            $link = trim((string) $item->link);

            if (empty($title) || empty($link)) continue;

            // Extract image from various sources
            $image = null;
            $namespaces = $item->getNameSpaces(true);

            // media:content
            if (isset($namespaces['media'])) {
                $media = $item->children($namespaces['media']);
                if (isset($media->content)) {
                    $image = (string) $media->content->attributes()->url;
                } elseif (isset($media->thumbnail)) {
                    $image = (string) $media->thumbnail->attributes()->url;
                }
            }

            // enclosure
            if (!$image && isset($item->enclosure)) {
                $encType = (string) $item->enclosure->attributes()->type;
                if (str_starts_with($encType, 'image/')) {
                    $image = (string) $item->enclosure->attributes()->url;
                }
            }

            // Description content
            $description = strip_tags((string) ($item->description ?? ''));
            $content = '';

            // content:encoded
            if (isset($namespaces['content'])) {
                $contentNs = $item->children($namespaces['content']);
                $content = strip_tags((string) ($contentNs->encoded ?? ''));
            }

            // Published date
            $pubDate = null;
            if (!empty((string) $item->pubDate)) {
                try {
                    $pubDate = new \DateTime((string) $item->pubDate);
                } catch (\Exception) {
                    $pubDate = now();
                }
            }

            $articles[] = [
                'title' => $title,
                'url' => $link,
                'description' => Str::limit($description, 500),
                'content' => Str::limit($content, 5000),
                'image' => $image,
                'published_at' => $pubDate,
            ];
        }

        return $articles;
    }

    // =====================================================
    // WEB SCRAPER (for gov.pl, migrantinfo, etc.)
    // =====================================================

    protected function parseScrape(array $source): array
    {
        $response = Http::timeout(20)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; WinCase-Bot/1.0)',
                'Accept-Language' => 'pl,en;q=0.9',
            ])
            ->get($source['url']);

        if (!$response->successful()) {
            throw new \RuntimeException("Scrape failed: HTTP {$response->status()}");
        }

        $html = $response->body();
        $articles = [];

        // Use DOM parser
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8"?>' . $html);
        $xpath = new \DOMXPath($dom);

        // Generic article extraction
        $articleNodes = $xpath->query('//article | //div[contains(@class,"news")] | //div[contains(@class,"article")] | //li[contains(@class,"news")]');

        foreach ($articleNodes as $node) {
            $linkNodes = $xpath->query('.//a[@href]', $node);
            if ($linkNodes->length === 0) continue;

            $link = $linkNodes->item(0)->getAttribute('href');
            $title = trim($linkNodes->item(0)->textContent);

            if (empty($title) || strlen($title) < 10) continue;

            // Make absolute URL
            if (str_starts_with($link, '/')) {
                $parsed = parse_url($source['url']);
                $link = $parsed['scheme'] . '://' . $parsed['host'] . $link;
            }

            // Try to extract description
            $descNodes = $xpath->query('.//p | .//span[contains(@class,"desc")]', $node);
            $desc = $descNodes->length > 0 ? trim($descNodes->item(0)->textContent) : '';

            // Try to extract image
            $imgNodes = $xpath->query('.//img[@src]', $node);
            $image = $imgNodes->length > 0 ? $imgNodes->item(0)->getAttribute('src') : null;

            $articles[] = [
                'title' => $title,
                'url' => $link,
                'description' => Str::limit($desc, 500),
                'content' => '',
                'image' => $image,
                'published_at' => now(),
            ];
        }

        return array_slice($articles, 0, 20); // limit per scrape
    }

    // =====================================================
    // FETCH FULL ARTICLE CONTENT
    // =====================================================

    public function fetchFullContent(int $articleId): string
    {
        $article = NewsArticle::findOrFail($articleId);

        $response = Http::timeout(20)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; WinCase-Bot/1.0)'])
            ->get($article->source_url);

        if (!$response->successful()) return '';

        $html = $response->body();
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8"?>' . $html);
        $xpath = new \DOMXPath($dom);

        // Extract article body from common selectors
        $selectors = [
            '//article//div[contains(@class,"content")]',
            '//article//div[contains(@class,"body")]',
            '//div[contains(@class,"article-content")]',
            '//div[contains(@class,"post-content")]',
            '//div[contains(@class,"entry-content")]',
            '//article',
            '//main//div[contains(@class,"text")]',
        ];

        $content = '';
        foreach ($selectors as $sel) {
            $nodes = $xpath->query($sel);
            if ($nodes->length > 0) {
                $content = $this->extractText($nodes->item(0));
                if (strlen($content) > 200) break;
            }
        }

        if ($content) {
            $article->update(['original_content' => Str::limit($content, 10000)]);
        }

        return $content;
    }

    // =====================================================
    // DUPLICATE DETECTION
    // =====================================================

    protected function isDuplicate(string $url, string $title): bool
    {
        // Exact URL match
        if (NewsArticle::where('source_url', $url)->exists()) {
            return true;
        }

        // Similar title (>85% match) in last 48h
        $recentArticles = NewsArticle::where('created_at', '>=', now()->subHours(48))
            ->pluck('original_title')
            ->toArray();

        foreach ($recentArticles as $existing) {
            similar_text(mb_strtolower($title), mb_strtolower($existing), $pct);
            if ($pct > 85) return true;
        }

        return false;
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function extractText(\DOMNode $node): string
    {
        $text = '';
        foreach ($node->childNodes as $child) {
            if ($child->nodeName === '#text') {
                $text .= trim($child->textContent) . ' ';
            } elseif (in_array($child->nodeName, ['p', 'h1', 'h2', 'h3', 'h4', 'li'])) {
                $text .= trim($child->textContent) . "\n\n";
            }
        }
        return trim($text);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NewsParserService — парсинг 27 источников (RSS + scraping).
// parseAll() — все источники по приоритету (critical→high→medium→low).
// parseRSS() — RSS 2.0 парсер с media:content, enclosure, content:encoded.
// parseScrape() — DOM scraper для gov.pl, migrantinfo (article, div.news).
// fetchFullContent() — получение полного текста по 7 CSS-селекторам.
// isDuplicate() — дубликаты: exact URL + similar_text >85% за 48ч.
// Broadcast NewsFeedEvent для Live Feed в админке.
// Файл: app/Services/News/NewsParserService.php
// ---------------------------------------------------------------
