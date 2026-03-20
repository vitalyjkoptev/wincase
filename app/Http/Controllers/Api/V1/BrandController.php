<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Brand\BrandListingsService;
use App\Services\Brand\ReviewsHubService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(
        protected BrandListingsService $listings,
        protected ReviewsHubService $reviews,
    ) {}

    // =====================================================
    // 1. GET /api/v1/brand/overview — Listings + NAP stats
    // =====================================================

    public function overview(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->listings->getOverview(),
        ]);
    }

    // =====================================================
    // 2. GET /api/v1/brand/listings — Grouped by category
    // =====================================================

    public function listings(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->listings->getListingsByGroup(),
        ]);
    }

    // =====================================================
    // 3. POST /api/v1/brand/nap-check — Run NAP consistency
    // =====================================================

    public function napCheck(): JsonResponse
    {
        $results = $this->listings->checkNapConsistency();

        return response()->json([
            'success' => true,
            'message' => "NAP check complete: {$results['consistent']} consistent, {$results['inconsistent']} issues.",
            'data' => $results,
        ]);
    }

    // =====================================================
    // 4. GET /api/v1/brand/reviews — Aggregated stats
    // =====================================================

    public function reviewsStats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reviews->getAggregatedStats(),
        ]);
    }

    // =====================================================
    // 5. GET /api/v1/brand/reviews/list — Recent reviews
    // =====================================================

    public function reviewsList(Request $request): JsonResponse
    {
        $platform = $request->get('platform');
        $minRating = $request->has('min_rating') ? (int) $request->get('min_rating') : null;
        $limit = min((int) $request->get('limit', 20), 100);

        return response()->json([
            'success' => true,
            'data' => $this->reviews->getRecentReviews($platform, $minRating, $limit),
        ]);
    }

    // =====================================================
    // 6. POST /api/v1/brand/reviews/{id}/reply — Save reply
    // =====================================================

    public function replyToReview(Request $request, int $id): JsonResponse
    {
        $request->validate(['reply_text' => 'required|string|max:2000']);

        $review = $this->reviews->saveReply($id, $request->input('reply_text'));

        return response()->json([
            'success' => true,
            'message' => 'Reply saved.',
            'data' => $review->toArray(),
        ]);
    }

    // =====================================================
    // 7. POST /api/v1/brand/reviews/sync — Manual sync
    // =====================================================

    public function syncReviews(): JsonResponse
    {
        $results = $this->reviews->syncAll();

        return response()->json([
            'success' => true,
            'message' => 'Reviews synced.',
            'data' => $results,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// BrandController — 7 API endpoints.
//   GET  /brand/overview          — 50+ каталогов + NAP score
//   GET  /brand/listings          — группированный список (10 групп)
//   POST /brand/nap-check         — запуск проверки NAP consistency
//   GET  /brand/reviews           — агрегированная статистика отзывов
//   GET  /brand/reviews/list      — список отзывов (?platform=&min_rating=)
//   POST /brand/reviews/{id}/reply — сохранение ответа на отзыв
//   POST /brand/reviews/sync      — ручная синхронизация отзывов
// Файл: app/Http/Controllers/Api/V1/BrandController.php
// ---------------------------------------------------------------
