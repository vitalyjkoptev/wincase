<?php

namespace App\Services\News;

use App\Models\NewsArticle;
use App\Models\NewsFeedLog;
use App\Events\NewsFeedEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIRewriterV2
{
    protected string $provider;
    protected string $model;

    public function __construct()
    {
        $this->provider = config('services.ai_rewriter.provider', 'anthropic');
        $this->model = config('services.ai_rewriter.model', 'claude-sonnet-4-5-20250929');
    }

    // =====================================================
    // REWRITE SINGLE ARTICLE — deep unique content
    // =====================================================

    public function rewrite(NewsArticle $article): NewsArticle
    {
        $this->logFeed($article, 'rewrite_start', "🔄 Rewriting: {$article->original_title}");

        $site = $this->determinePrimarySite($article);
        $siteName = NewsSitesRegistry::getSite($site)['name'] ?? 'News Portal';

        $prompt = $this->buildPrompt($article, $siteName);

        try {
            $response = $this->callAI($prompt);
            $parsed = $this->parseResponse($response);

            // Check plagiarism via comparison
            $plagiarismScore = $this->calculatePlagiarism(
                $article->original_content,
                $parsed['content']
            );

            // If plagiarism > 20%, do second pass
            if ($plagiarismScore > 20) {
                $this->logFeed($article, 'rewrite_retry', "⚠️ Plagiarism {$plagiarismScore}% — doing second pass");
                $deeperPrompt = $this->buildDeeperPrompt($parsed['content'], $article->original_content);
                $response2 = $this->callAI($deeperPrompt);
                $parsed = $this->parseResponse($response2);
                $plagiarismScore = $this->calculatePlagiarism($article->original_content, $parsed['content']);
            }

            // If still > 30%, third pass with maximum rewrite
            if ($plagiarismScore > 30) {
                $this->logFeed($article, 'rewrite_retry_3', "🔴 Plagiarism {$plagiarismScore}% — third pass (maximum rewrite)");
                $maxPrompt = $this->buildMaxRewritePrompt($parsed['content']);
                $response3 = $this->callAI($maxPrompt);
                $parsed = $this->parseResponse($response3);
                $plagiarismScore = $this->calculatePlagiarism($article->original_content, $parsed['content']);
            }

            $article->update([
                'rewritten_title' => $parsed['title'],
                'rewritten_content' => $parsed['content'],
                'rewritten_excerpt' => $parsed['excerpt'] ?? \Str::limit(strip_tags($parsed['content']), 250),
                'plagiarism_score' => $plagiarismScore,
                'ai_provider' => $this->provider,
                'ai_model' => $this->model,
                'status' => $plagiarismScore < 15 ? 'rewritten' : 'needs_review',
                'rewritten_at' => now(),
                'priority_score' => $this->calculatePriority($article),
            ]);

            $status = $plagiarismScore < 15 ? '✅' : '⚠️';
            $this->logFeed($article, 'rewrite_done',
                "{$status} Rewritten: {$parsed['title']} (plagiarism: {$plagiarismScore}%)");

        } catch (\Exception $e) {
            $article->update(['status' => 'rewrite_failed', 'error_log' => $e->getMessage()]);
            $this->logFeed($article, 'rewrite_failed', "❌ Failed: {$e->getMessage()}");
            Log::error("AI Rewrite failed: {$e->getMessage()}", ['article_id' => $article->id]);
        }

        return $article->fresh();
    }

    // =====================================================
    // BATCH REWRITE (10 articles per run)
    // =====================================================

    public function rewriteBatch(int $limit = 10): array
    {
        $articles = NewsArticle::where('status', 'parsed')
            ->orderByRaw("FIELD(priority_score, 'critical', 'high', 'medium', 'low')")
            ->orderBy('parsed_at', 'asc')
            ->limit($limit)
            ->get();

        $results = ['rewritten' => 0, 'needs_review' => 0, 'failed' => 0];

        foreach ($articles as $article) {
            $result = $this->rewrite($article);
            match ($result->status) {
                'rewritten' => $results['rewritten']++,
                'needs_review' => $results['needs_review']++,
                default => $results['failed']++,
            };
            usleep(500000); // 0.5s delay between API calls
        }

        $this->logFeed(null, 'batch_rewrite',
            "🔄 Batch: {$results['rewritten']} ok, {$results['needs_review']} review, {$results['failed']} failed");

        return $results;
    }

    // =====================================================
    // AI PROMPT — main rewrite
    // =====================================================

    protected function buildPrompt(NewsArticle $article, string $siteName): string
    {
        return <<<PROMPT
You are a professional news journalist for "{$siteName}".
Completely rewrite the article below into an original, unique piece.

STRICT REQUIREMENTS:
1. ZERO plagiarism — do NOT copy any phrases from the original. Rephrase everything.
2. Change sentence structure, word order, and vocabulary completely.
3. Keep all FACTS, dates, names, numbers accurate.
4. Write in a professional journalistic style.
5. Create a NEW compelling headline (different wording from original).
6. Add context or background where appropriate.
7. The rewritten article should be similar length to original (±20%).
8. Language: English (unless original is in another language, then match it).

ORIGINAL TITLE: {$article->original_title}

ORIGINAL CONTENT:
{$article->original_content}

SOURCE: {$article->source_name} ({$article->original_url})

Respond ONLY in this exact format:
TITLE: [your new headline]
EXCERPT: [2-3 sentence summary]
CONTENT:
[your fully rewritten article in HTML paragraphs]
PROMPT;
    }

    protected function buildDeeperPrompt(string $firstRewrite, string $original): string
    {
        return <<<PROMPT
The article below was rewritten but still has too much similarity to the original.
Do a DEEP rewrite — completely restructure paragraphs, use different vocabulary, change the narrative angle.

FIRST REWRITE (needs more changes):
{$firstRewrite}

ORIGINAL (must not match):
{$original}

INSTRUCTIONS:
1. Use COMPLETELY different phrasing — no shared 3-word sequences with original.
2. Restructure paragraph order.
3. Use synonyms for all key terms.
4. Change passive→active voice or vice versa.
5. Keep all facts accurate.

Respond in format:
TITLE: [new headline]
EXCERPT: [summary]
CONTENT:
[deeply rewritten HTML article]
PROMPT;
    }

    protected function buildMaxRewritePrompt(string $content): string
    {
        return <<<PROMPT
Rewrite this article from scratch. Imagine you read it, closed the browser, and now write your own version from memory. Use completely different words, structure, and style. Keep facts accurate.

ARTICLE TO REWRITE FROM MEMORY:
{$content}

Respond in format:
TITLE: [completely new headline]
EXCERPT: [summary]
CONTENT:
[your original version in HTML]
PROMPT;
    }

    // =====================================================
    // AI API CALL (Anthropic / OpenAI)
    // =====================================================

    protected function callAI(string $prompt): string
    {
        if ($this->provider === 'anthropic') {
            $response = Http::withHeaders([
                'x-api-key' => config('services.anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.anthropic.com/v1/messages', [
                'model' => $this->model,
                'max_tokens' => 4096,
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            if (!$response->successful()) {
                throw new \Exception("Anthropic API error: {$response->status()}");
            }
            return $response->json('content.0.text', '');
        }

        // OpenAI fallback
        $response = Http::withToken(config('services.openai.api_key'))
            ->timeout(120)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o'),
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 4096,
                'temperature' => 0.8,
            ]);

        if (!$response->successful()) {
            throw new \Exception("OpenAI API error: {$response->status()}");
        }
        return $response->json('choices.0.message.content', '');
    }

    // =====================================================
    // PARSE AI RESPONSE
    // =====================================================

    protected function parseResponse(string $response): array
    {
        $title = '';
        $excerpt = '';
        $content = '';

        if (preg_match('/TITLE:\s*(.+)/i', $response, $m)) $title = trim($m[1]);
        if (preg_match('/EXCERPT:\s*(.+?)(?=CONTENT:)/is', $response, $m)) $excerpt = trim($m[1]);
        if (preg_match('/CONTENT:\s*(.+)/is', $response, $m)) $content = trim($m[1]);

        if (empty($content)) $content = $response;
        if (empty($title)) $title = \Str::limit(strip_tags($content), 100);

        return compact('title', 'excerpt', 'content');
    }

    // =====================================================
    // PLAGIARISM CALCULATION (trigram comparison)
    // =====================================================

    protected function calculatePlagiarism(string $original, string $rewritten): int
    {
        $original = $this->normalizeText($original);
        $rewritten = $this->normalizeText($rewritten);

        if (empty($original) || empty($rewritten)) return 0;

        $origTrigrams = $this->getTrigrams($original);
        $rewriteTrigrams = $this->getTrigrams($rewritten);

        if (empty($origTrigrams)) return 0;

        $commonCount = 0;
        foreach ($rewriteTrigrams as $trigram) {
            if (isset($origTrigrams[$trigram])) {
                $commonCount++;
            }
        }

        $totalRewrite = count($rewriteTrigrams);
        if ($totalRewrite === 0) return 0;

        return (int) round(($commonCount / $totalRewrite) * 100);
    }

    protected function getTrigrams(string $text): array
    {
        $words = explode(' ', $text);
        $trigrams = [];
        for ($i = 0; $i < count($words) - 2; $i++) {
            $key = $words[$i] . ' ' . $words[$i + 1] . ' ' . $words[$i + 2];
            $trigrams[$key] = true;
        }
        return $trigrams;
    }

    protected function normalizeText(string $text): string
    {
        $text = strip_tags($text);
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        return preg_replace('/\s+/', ' ', trim($text));
    }

    // =====================================================
    // PRIORITY SCORING
    // =====================================================

    protected function calculatePriority(NewsArticle $article): int
    {
        $source = NewsSourcesRegistryV2::getSources()[$article->source_key] ?? null;
        $base = match ($source['priority'] ?? 'low') {
            'critical' => 100,
            'high' => 75,
            'medium' => 50,
            'low' => 25,
            default => 10,
        };

        // Fresher articles get higher score
        $ageHours = now()->diffInHours($article->parsed_at);
        $freshness = max(0, 20 - $ageHours); // 0-20 bonus for articles < 20h old

        // Multi-site targets = higher value
        $targetCount = count($source['targets'] ?? []);
        $targetBonus = min($targetCount * 5, 15); // 0-15 bonus

        return $base + $freshness + $targetBonus;
    }

    // =====================================================
    // DETERMINE PRIMARY SITE
    // =====================================================

    protected function determinePrimarySite(NewsArticle $article): string
    {
        $source = NewsSourcesRegistryV2::getSources()[$article->source_key] ?? null;
        $targets = $source['targets'] ?? [];
        if (empty($targets)) return 'polandpulse';
        return explode(':', $targets[0])[0];
    }

    // =====================================================
    // LIVE FEED LOG
    // =====================================================

    protected function logFeed(?NewsArticle $article, string $action, string $message): void
    {
        $log = NewsFeedLog::create([
            'article_id' => $article?->id,
            'source_key' => $article?->source_key,
            'source_name' => $article?->source_name,
            'action' => $action,
            'message' => $message,
            'title' => $article?->rewritten_title ?: $article?->original_title,
        ]);

        try {
            broadcast(new NewsFeedEvent([
                'id' => $log->id,
                'action' => $action,
                'message' => $message,
                'title' => $log->title,
                'source' => $article?->source_name,
                'timestamp' => now()->toIso8601String(),
            ]));
        } catch (\Exception) {}
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AIRewriterV2 — глубокий рерайт с антиплагиатом.
// 3-pass система: 1) стандартный рерайт → 2) если plagiarism>20% → deeper → 3) если >30% → max rewrite "from memory"
// calculatePlagiarism() — trigram comparison (3-word sequences), scores 0-100%.
// Порог публикации: < 15% (auto-publish), 15-30% (needs_review), > 30% (3rd pass).
// Priority scoring: base (critical=100, high=75) + freshness (0-20) + targets (0-15).
// Providers: Anthropic Claude Sonnet (primary), OpenAI GPT-4o (fallback).
// Batch: 10 articles/run, 0.5s delay. All actions → NewsFeedLog + WebSocket.
// Файл: app/Services/News/AIRewriterV2.php
// ---------------------------------------------------------------
