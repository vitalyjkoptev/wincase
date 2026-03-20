<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AdsPlatformEnum;
use App\Http\Controllers\Controller;
use App\Services\Ads\AdsOrchestrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function __construct(
        protected AdsOrchestrationService $ads
    ) {}

    // =====================================================
    // 1. GET /api/v1/ads/overview — All platforms summary
    // =====================================================

    public function overview(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        return response()->json([
            'success' => true,
            'data' => $this->ads->getOverview($dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 2. GET /api/v1/ads/{platform} — Single platform data
    // =====================================================

    public function platform(Request $request, string $platform): JsonResponse
    {
        $enum = AdsPlatformEnum::tryFrom($platform);

        if (!$enum) {
            return response()->json([
                'success' => false,
                'message' => "Unknown platform: {$platform}. Valid: " . implode(', ', array_column(AdsPlatformEnum::cases(), 'value')),
            ], 404);
        }

        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        return response()->json([
            'success' => true,
            'data' => $this->ads->getPlatformData($enum, $dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 3. GET /api/v1/ads/{platform}/campaigns — Campaigns list
    // =====================================================

    public function campaigns(Request $request, string $platform): JsonResponse
    {
        $enum = AdsPlatformEnum::tryFrom($platform);

        if (!$enum) {
            return response()->json([
                'success' => false,
                'message' => "Unknown platform: {$platform}",
            ], 404);
        }

        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        return response()->json([
            'success' => true,
            'data' => $this->ads->getCampaigns($enum, $dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 4. GET /api/v1/ads/budget — Budget plan vs actual
    // =====================================================

    public function budget(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        return response()->json([
            'success' => true,
            'data' => $this->ads->getBudgetAnalysis($dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 5. POST /api/v1/ads/sync — Trigger manual sync
    // =====================================================

    public function sync(Request $request): JsonResponse
    {
        $platforms = $request->get('platforms'); // null = all
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $results = $this->ads->syncAll($dateFrom, $dateTo, $platforms);

        return response()->json([
            'success' => true,
            'message' => 'Sync completed.',
            'data' => $results,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AdsController — 5 API endpoints модуля рекламы.
//   GET  /ads/overview              — сводка всех 5 платформ + totals
//   GET  /ads/{platform}            — одна платформа: кампании + daily
//   GET  /ads/{platform}/campaigns  — список кампаний с метриками
//   GET  /ads/budget                — план vs факт по бюджету
//   POST /ads/sync                  — ручная синхронизация (admin)
// Query params: ?date_from=&date_to= (default: 30 дней).
// Файл: app/Http/Controllers/Api/V1/AdsController.php
// ---------------------------------------------------------------
