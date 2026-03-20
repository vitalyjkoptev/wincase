<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // GET /news/sources — all sources
    public function sources(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => [
            'categories' => NewsArticle::getCategories(),
        ]]);
    }

    // GET /news/articles — list with filters + pagination
    public function articles(Request $request): JsonResponse
    {
        $query = NewsArticle::query();

        if ($request->filled('status')) $query->where('status', $request->input('status'));
        if ($request->filled('category')) $query->where('category', $request->input('category'));
        if ($request->filled('language')) $query->where('language', $request->input('language'));
        if ($request->filled('source')) $query->where('source_name', $request->input('source'));
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where('title', 'like', "%{$s}%");
        }

        $paginated = $query->orderByDesc('created_at')
            ->paginate((int) $request->get('per_page', 25));

        // Stats
        $stats = [
            'total' => NewsArticle::count(),
            'published' => NewsArticle::where('status', 'published')->count(),
            'draft' => NewsArticle::where('status', 'draft')->count(),
            'review' => NewsArticle::where('status', 'review')->count(),
        ];

        return response()->json(['success' => true, 'data' => [
            'articles' => $paginated->items(),
            'stats' => $stats,
            'meta' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ],
        ]]);
    }

    // GET /news/articles/{id}
    public function showArticle(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        return response()->json(['success' => true, 'data' => $article]);
    }

    // POST /news/articles — create
    public function storeArticle(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news_articles,slug',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:5',
            'status' => 'nullable|string|in:draft,review,published,scheduled',
        ]);

        $article = NewsArticle::create($request->only([
            'title', 'slug', 'content', 'category', 'language', 'status',
            'source_url', 'source_name', 'site_domain',
        ]));

        return response()->json(['success' => true, 'data' => $article], 201);
    }

    // PUT /news/articles/{id} — update
    public function updateArticle(int $id, Request $request): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update($request->only([
            'title', 'slug', 'content', 'category', 'language', 'status',
            'source_url', 'source_name', 'site_domain',
        ]));
        return response()->json(['success' => true, 'data' => $article->fresh()]);
    }

    // DELETE /news/articles/{id}
    public function deleteArticle(int $id): JsonResponse
    {
        NewsArticle::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Article deleted']);
    }

    // POST /news/articles/{id}/approve
    public function approveArticle(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update(['status' => 'published', 'published_at' => now()]);
        return response()->json(['success' => true, 'data' => $article->fresh()]);
    }

    // POST /news/articles/{id}/reject
    public function rejectArticle(int $id): JsonResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update(['status' => 'draft']);
        return response()->json(['success' => true, 'data' => $article->fresh()]);
    }

    // GET /news/statistics
    public function statistics(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 30);
        $since = now()->subDays($days)->toDateString();

        return response()->json(['success' => true, 'data' => [
            'total' => NewsArticle::count(),
            'published' => NewsArticle::where('status', 'published')->count(),
            'draft' => NewsArticle::where('status', 'draft')->count(),
            'recent' => NewsArticle::where('created_at', '>=', $since)->count(),
            'by_category' => NewsArticle::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')->orderByDesc('count')->get()->toArray(),
            'by_language' => NewsArticle::selectRaw('language, COUNT(*) as count')
                ->groupBy('language')->get()->toArray(),
            'by_site' => NewsArticle::selectRaw('site_domain, COUNT(*) as count')
                ->whereNotNull('site_domain')
                ->groupBy('site_domain')->orderByDesc('count')->get()->toArray(),
        ]]);
    }

    // GET /news/categories
    public function categories(): JsonResponse
    {
        $cats = NewsArticle::getCategories();
        $counts = NewsArticle::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')->pluck('count', 'category')->toArray();

        $result = [];
        foreach ($cats as $slug => $name) {
            $result[] = [
                'slug' => $slug,
                'name' => $name,
                'articles_count' => $counts[$slug] ?? 0,
                'status' => 'active',
            ];
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    // GET /news/schedule
    public function schedule(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => [
            'schedule' => [
                'critical' => 'immediate',
                'high' => '5min',
                'medium' => '15min',
                'low' => '30min',
            ],
        ]]);
    }

    // GET /news/feed — placeholder (no news_feed_logs table)
    public function feedHistory(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'data' => []]);
    }
}
