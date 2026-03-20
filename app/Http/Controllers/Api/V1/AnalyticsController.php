<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analytics
    ) {}

    public function sales(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->analytics->getSalesAnalytics(),
        ]);
    }

    public function traffic(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->analytics->getTrafficAnalytics(),
        ]);
    }

    public function performance(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->analytics->getPerformanceAnalytics(),
        ]);
    }

    public function quota(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->analytics->getQuotaAnalytics(),
        ]);
    }
}
