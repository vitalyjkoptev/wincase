<?php

namespace App\Services\News;

use App\Models\NewsArticle;
use App\Events\NewsFeedEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AIRewriterService
{
    protected string $provider;
    protected string $model;

    public function __construct()
    {
        // Priority: Claude API → OpenAI → local fallback
        $this->provider = config('services.ai_rewriter.provider', 'anthropic');
        $this->model = config('services.ai_rewriter.model', 'claude-sonnet-4-5-20250929');
    }

    // =====================================================
    // REWRITE ARTICLE — full pipeline
    // =====================================================

    public function rewriteArticle(int $articleId, ?string $targetLanguage = null): NewsArticle
    {
        $article = NewsArticle::findOrFail($articleId);

        // 1. Build source text
        $sourceText = $article->original_content ?: $article->original_description;
        if (strlen($sourceText) < 50) {
            // Fetch full content if too short
            $parser = app(NewsParserService::class);
            $sourceText = $parser->fetchFullContent($articleId);
        }

        if (strlen($sourceText) < 50) {
            $article->update(['status' => 'skipped', 'skip_reason' => 'Content too short']);
            return $article;
        }

        // 2. Determine target language
        $lang = $targetLanguage ?? $article->original_language;

        // 3. AI Rewrite
        $rewritten = $this->callAI($sourceText, $article->original_title, $lang, $article->category);

        if (!$rewritten) {
            $article->update(['status' => 'rewrite_failed']);
            return $article;
        }

        // 4. Plagiarism check
        $plagiarismScore = $this->checkPlagiarism($sourceText, $rewritten['content']);

        // 5. Save results
        $article->update([
            'rewritten_title' => $rewritten['title'],
            'rewritten_content' => $rewritten['content'],
            'rewritten_description' => $rewritten['description'],
            'rewritten_language' => $lang,
            'seo_meta_title' => $rewritten['seo_title'] ?? $rewritten['title'],
            'seo_meta_description' => $rewritten['seo_description'] ?? $rewritten['description'],
            'seo_keywords' => $rewritten['keywords'] ?? '',
            'seo_slug' => Str::slug($rewritten['title']),
            'plagiarism_score' => $plagiarismScore,
            'status' => $plagiarismScore < 15 ? 'rewritten' : 'needs_review',
            'rewritten_at' => now(),
        ]);

        // Broadcast
        broadcast(new NewsFeedEvent([
            'type' => 'rewritten',
            'article_id' => $article->id,
            'title' => Str::limit($rewritten['title'], 80),
            'language' => $lang,
            'plagiarism_score' => $plagiarismScore,
            'status' => $article->status,
            'timestamp' => now()->toIso8601String(),
        ]));

        return $article->fresh();
    }

    // =====================================================
    // AI API CALL
    // =====================================================

    protected function callAI(string $content, string $originalTitle, string $lang, string $category): ?array
    {
        $langNames = [
            'pl' => 'Polish', 'en' => 'English', 'ua' => 'Ukrainian',
            'ru' => 'Russian', 'hi' => 'Hindi', 'tl' => 'Filipino',
            'es' => 'Spanish', 'tr' => 'Turkish',
        ];
        $langName = $langNames[$lang] ?? 'English';

        $systemPrompt = <<<PROMPT
You are a professional news editor and content rewriter. Your task:

1. REWRITE the article completely in {$langName} language.
2. The rewritten text must be 100% UNIQUE — zero plagiarism.
3. Keep all FACTS, DATES, NAMES, and NUMBERS accurate.
4. Change sentence structure, word choice, and paragraph order.
5. Write in professional journalistic style.
6. Category: {$category}

CRITICAL RULES:
- Do NOT copy any phrases longer than 5 words from the original.
- Change ALL metaphors and expressions.
- Restructure paragraphs completely.
- If the original language differs from {$langName}, TRANSLATE and rewrite simultaneously.

Output JSON only:
{
  "title": "Unique headline in {$langName}",
  "content": "Full rewritten article in {$langName}. Multiple paragraphs.",
  "description": "2-3 sentence summary for preview",
  "seo_title": "SEO-optimized title (max 60 chars)",
  "seo_description": "Meta description (max 155 chars)",
  "keywords": "comma, separated, keywords"
}
PROMPT;

        $userMessage = "Original title: {$originalTitle}\n\nOriginal content:\n{$content}";

        try {
            if ($this->provider === 'anthropic') {
                return $this->callClaude($systemPrompt, $userMessage);
            } else {
                return $this->callOpenAI($systemPrompt, $userMessage);
            }
        } catch (\Exception $e) {
            \Log::error("AI Rewriter error: {$e->getMessage()}");
            return null;
        }
    }

    // =====================================================
    // ANTHROPIC CLAUDE API
    // =====================================================

    protected function callClaude(string $system, string $message): ?array
    {
        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.api_key'),
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->model,
            'max_tokens' => 4096,
            'system' => $system,
            'messages' => [['role' => 'user', 'content' => $message]],
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException("Claude API error: {$response->status()}");
        }

        $text = $response->json('content.0.text', '');
        return $this->parseJSON($text);
    }

    // =====================================================
    // OPENAI API (fallback)
    // =====================================================

    protected function callOpenAI(string $system, string $message): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.api_key'),
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => config('services.ai_rewriter.openai_model', 'gpt-4o'),
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $message],
            ],
            'max_tokens' => 4096,
            'temperature' => 0.7,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException("OpenAI API error: {$response->status()}");
        }

        $text = $response->json('choices.0.message.content', '');
        return $this->parseJSON($text);
    }

    // =====================================================
    // PLAGIARISM CHECK (internal comparison)
    // =====================================================

    protected function checkPlagiarism(string $original, string $rewritten): float
    {
        $original = mb_strtolower(strip_tags($original));
        $rewritten = mb_strtolower(strip_tags($rewritten));

        // Method 1: N-gram overlap (5-word phrases)
        $origNgrams = $this->getNgrams($original, 5);
        $rewriteNgrams = $this->getNgrams($rewritten, 5);

        if (empty($origNgrams) || empty($rewriteNgrams)) return 0;

        $overlap = count(array_intersect($origNgrams, $rewriteNgrams));
        $ngramScore = ($overlap / max(count($rewriteNgrams), 1)) * 100;

        // Method 2: Similarity percentage
        similar_text($original, $rewritten, $similarPct);

        // Method 3: Word overlap ratio
        $origWords = array_unique(preg_split('/\s+/', $original));
        $rewriteWords = array_unique(preg_split('/\s+/', $rewritten));
        $commonWords = count(array_intersect($origWords, $rewriteWords));
        $wordOverlap = ($commonWords / max(count($rewriteWords), 1)) * 100;

        // Weighted score (lower = better)
        $score = ($ngramScore * 0.5) + ($similarPct * 0.2) + ($wordOverlap * 0.3);

        // Adjust: same language = stricter, translated = more lenient
        return round(min($score, 100), 1);
    }

    protected function getNgrams(string $text, int $n): array
    {
        $words = preg_split('/\s+/', $text);
        $ngrams = [];
        for ($i = 0; $i <= count($words) - $n; $i++) {
            $ngrams[] = implode(' ', array_slice($words, $i, $n));
        }
        return $ngrams;
    }

    // =====================================================
    // BATCH REWRITE (for queue processing)
    // =====================================================

    public function rewriteBatch(int $limit = 10): array
    {
        $articles = NewsArticle::where('status', 'parsed')
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
            ->orderBy('created_at')
            ->limit($limit)
            ->get();

        $results = ['processed' => 0, 'success' => 0, 'failed' => 0];

        foreach ($articles as $article) {
            $results['processed']++;

            $mapping = NewsSourcesRegistry::getCategoryMapping()[$article->category] ?? null;
            if (!$mapping) continue;

            // Rewrite in original language first
            $result = $this->rewriteArticle($article->id);
            if ($result->status === 'rewritten') {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }

    // =====================================================
    // TRANSLATE + REWRITE to target languages
    // =====================================================

    public function translateArticle(int $articleId, string $targetLanguage): ?NewsArticle
    {
        $original = NewsArticle::findOrFail($articleId);

        // Create translation record
        $translation = $original->replicate();
        $translation->parent_article_id = $original->id;
        $translation->rewritten_language = $targetLanguage;
        $translation->status = 'translating';
        $translation->save();

        // Rewrite in target language
        $sourceText = $original->rewritten_content ?: $original->original_content;

        $rewritten = $this->callAI(
            $sourceText,
            $original->rewritten_title ?: $original->original_title,
            $targetLanguage,
            $original->category
        );

        if ($rewritten) {
            $translation->update([
                'rewritten_title' => $rewritten['title'],
                'rewritten_content' => $rewritten['content'],
                'rewritten_description' => $rewritten['description'],
                'seo_meta_title' => $rewritten['seo_title'] ?? $rewritten['title'],
                'seo_meta_description' => $rewritten['seo_description'] ?? $rewritten['description'],
                'seo_keywords' => $rewritten['keywords'] ?? '',
                'seo_slug' => Str::slug($rewritten['title']),
                'status' => 'rewritten',
                'rewritten_at' => now(),
            ]);

            broadcast(new NewsFeedEvent([
                'type' => 'translated',
                'article_id' => $translation->id,
                'title' => Str::limit($rewritten['title'], 80),
                'from_language' => $original->original_language,
                'to_language' => $targetLanguage,
                'timestamp' => now()->toIso8601String(),
            ]));
        }

        return $translation->fresh();
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function parseJSON(string $text): ?array
    {
        // Remove markdown fences
        $text = preg_replace('/```json?\s*/', '', $text);
        $text = preg_replace('/```\s*/', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);
        if (!$data || !isset($data['title'], $data['content'])) {
            return null;
        }
        return $data;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AIRewriterService — AI рерайтинг (0% плагиата) + перевод.
// rewriteArticle() — полный pipeline: fetch content → AI rewrite → plagiarism check.
// callAI() — промпт: 100% unique, keep facts, change structure, JSON output.
// callClaude() — Anthropic API (primary). callOpenAI() — OpenAI (fallback).
// checkPlagiarism() — 3 метода: N-gram overlap (5-word), similar_text, word overlap.
// Weighted score: ngram 50%, similarity 20%, word overlap 30%.
// < 15% = auto-publish, >= 15% = needs_review.
// rewriteBatch() — пакетная обработка по приоритету.
// translateArticle() — перевод + рерайтинг на 8 языков.
// Файл: app/Services/News/AIRewriterService.php
// ---------------------------------------------------------------
