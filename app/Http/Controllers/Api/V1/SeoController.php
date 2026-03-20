<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SeoData;
use App\Services\SEO\SeoOrchestrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function __construct(
        protected SeoOrchestrationService $seo
    ) {}

    // =====================================================
    // 1. GET /api/v1/seo/overview — KPIs for all 4 domains
    // =====================================================

    public function overview(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        return response()->json([
            'success' => true,
            'data' => $this->seo->getOverview($dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 2. GET /api/v1/seo/keywords — Top keywords from GSC
    // =====================================================

    public function keywords(Request $request): JsonResponse
    {
        $domain = $request->get('domain', 'wincase.pro');
        $limit = min((int) $request->get('limit', 50), 500);

        if (!in_array($domain, SeoData::DOMAINS)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid domain. Valid: ' . implode(', ', SeoData::DOMAINS),
            ], 400);
        }

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        return response()->json([
            'success' => true,
            'data' => $this->seo->getKeywords($domain, $limit, $dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 3. GET /api/v1/seo/network — 8 SEO satellite sites
    // =====================================================

    public function network(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->seo->getNetwork(),
        ]);
    }

    // =====================================================
    // 4. GET /api/v1/seo/backlinks — Backlinks trend
    // =====================================================

    public function backlinks(Request $request): JsonResponse
    {
        $domain = $request->get('domain', 'wincase.pro');
        $days = min((int) $request->get('days', 30), 365);

        if (!in_array($domain, SeoData::DOMAINS)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid domain.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $this->seo->getBacklinks($domain, $days),
        ]);
    }

    // =====================================================
    // 5. GET /api/v1/seo/reviews — Reviews from all platforms
    // =====================================================

    public function reviews(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->seo->getReviews(),
        ]);
    }

    // =====================================================
    // 6. GET /api/v1/seo/brand — Trademark, Wikipedia, KP
    // =====================================================

    public function brand(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->seo->getBrandStatus(),
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// SeoController — 6 API endpoints модуля SEO.
//   GET /seo/overview   — сводка 4 доменов (GSC + GA4 + Ahrefs)
//   GET /seo/keywords   — топ ключевые слова (?domain=&limit=)
//   GET /seo/network    — 8 сателлитных сайтов
//   GET /seo/backlinks  — тренд DA + new/lost бэклинки (?domain=&days=)
//   GET /seo/reviews    — отзывы всех платформ (агрегация)
//   GET /seo/brand      — статус листингов, NAP consistency
// Файл: app/Http/Controllers/Api/V1/SeoController.php
// ---------------------------------------------------------------
