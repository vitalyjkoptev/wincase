<?php

// =====================================================
// FILE: app/Http/Controllers/Api/V1/NewsControllerV2.php
// Enhanced News Pipeline Controller — multi-site
// =====================================================

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{NewsArticle, NewsFeedLog};
use App\Services\News\{NewsSourcesRegistryV2, NewsSitesRegistry, AIRewriterV2, NewsPublisherV2};
use Illuminate\Http\{JsonResponse, Request};

class NewsControllerV2 extends Controller
{
    public function __construct(
        protected AIRewriterV2 $rewriter,
        protected NewsPublisherV2 $publisher,
    ) {}

    // GET /news/sites — all 8 target sites with categories
    public function sites(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'sites' => NewsSitesRegistry::getSites(),
                'stats' => NewsSitesRegistry::getStats(),
            ],
        ]);
    }

    // GET /news/sources — all 60+ RSS sources with target mappings
    public function sources(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'sources' => NewsSourcesRegistryV2::getSources(),
                'stats' => NewsSourcesRegistryV2::getStats(),
            ],
        ]);
    }

    // GET /news/articles — paginated articles with filters
    public function articles(Request $request): JsonResponse
    {
        $q = NewsArticle::query();

        if ($f = $request->get('status')) $q->where('status', $f);
        if ($f = $request->get('source_key')) $q->where('source_key', $f);
        if ($f = $request->get('site')) {
            $q->where('published_sites', 'like', "%{$f}%");
        }
        if ($f = $request->get('priority')) {
            $sources = NewsSourcesRegistryV2::getByPriority($f);
            $q->whereIn('source_key', array_keys($sources));
        }
        if ($f = $request->get('search')) {
            $q->where(fn($sub) => $sub
                ->where('original_title', 'like', "%{$f}%")
                ->orWhere('rewritten_title', 'like', "%{$f}%"));
        }

        $perPage = min((int) $request->get('per_page', 30), 100);
        $paginated = $q->orderByDesc('parsed_at')->paginate($perPage);

        return response()->json(['success' => true, 'data' => [
            'data' => $paginated->items(),
            'meta' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ],
        ]]);
    }

    // GET /news/articles/{id} — article detail with publish results
    public function showArticle(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        return response()->json(['success' => true, 'data' => $article]);
    }

    // POST /news/rewrite/{id} — AI rewrite single article
    public function rewriteArticle(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $result = $this->rewriter->rewrite($article);
        return response()->json(['success' => true, 'data' => $result]);
    }

    // POST /news/rewrite-batch — batch rewrite (10 articles)
    public function rewriteBatch(): JsonResponse
    {
        $results = $this->rewriter->rewriteBatch();
        return response()->json(['success' => true, 'data' => $results]);
    }

    // POST /news/publish — publish all ready articles
    public function publishReady(): JsonResponse
    {
        $results = $this->publisher->publishAllReady();
        return response()->json(['success' => true, 'data' => $results]);
    }

    // POST /news/publish/{id} — publish single article immediately
    public function publishArticle(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $results = $this->publisher->publishImmediate($article);
        return response()->json(['success' => true, 'data' => $results]);
    }

    // POST /news/articles/{id}/approve — approve for publishing
    public function approve(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update(['status' => 'rewritten']);
        return response()->json(['success' => true, 'data' => $article->fresh()]);
    }

    // POST /news/articles/{id}/reject — reject article
    public function reject(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'data' => $article->fresh()]);
    }

    // GET /news/feed — live feed history (latest 100)
    public function feedHistory(Request $request): JsonResponse
    {
        $limit = min((int) $request->get('limit', 100), 500);
        $logs = NewsFeedLog::orderByDesc('created_at')->limit($limit)->get();
        return response()->json(['success' => true, 'data' => $logs]);
    }

    // GET /news/statistics — enhanced stats
    public function statistics(): JsonResponse
    {
        $today = now()->startOfDay();
        return response()->json(['success' => true, 'data' => [
            'total_articles' => NewsArticle::count(),
            'today_parsed' => NewsArticle::where('parsed_at', '>=', $today)->count(),
            'today_published' => NewsArticle::where('published_at', '>=', $today)->count(),
            'by_status' => NewsArticle::selectRaw('status, COUNT(*) as count')->groupBy('status')->get(),
            'avg_plagiarism' => round(NewsArticle::whereNotNull('plagiarism_score')->avg('plagiarism_score') ?? 0, 1),
            'by_site' => $this->countBySite(),
            'sources' => NewsSourcesRegistryV2::getStats(),
            'sites' => NewsSitesRegistry::getStats(),
        ]]);
    }

    protected function countBySite(): array
    {
        $counts = [];
        foreach (NewsSitesRegistry::getSiteIds() as $siteId) {
            $counts[$siteId] = NewsArticle::where('published_sites', 'like', "%{$siteId}%")->count();
        }
        return $counts;
    }
}
// Extracted: NewsFeedEvent → app/Events/NewsFeedEvent.php
// Extracted: NewsFeedLog model → app/Models/NewsFeedLog.php
