<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\SocialPlatformEnum;
use App\Http\Controllers\Controller;
use App\Services\Social\SocialOrchestrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function __construct(
        protected SocialOrchestrationService $social
    ) {}

    // =====================================================
    // 1. GET /api/v1/social/accounts — All 8 platform stats
    // =====================================================

    public function accounts(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->social->getAccountsOverview(),
        ]);
    }

    // =====================================================
    // 2. POST /api/v1/social/publish — Unified posting
    // =====================================================

    public function publish(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:5000',
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'string|in:' . implode(',', array_column(SocialPlatformEnum::cases(), 'value')),
            'media_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'link' => 'nullable|url',
            'title' => 'nullable|string|max:200',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $content = $request->only(['text', 'media_url', 'video_url', 'link', 'title', 'scheduled_at']);
        $platforms = $request->input('platforms');

        $results = $this->social->publishToMultiple($content, $platforms);

        $allSuccess = collect($results)->every(fn ($r) => $r['success'] ?? false);

        return response()->json([
            'success' => $allSuccess,
            'message' => $allSuccess ? 'Published to all platforms.' : 'Some platforms failed.',
            'data' => $results,
        ], $allSuccess ? 201 : 207);
    }

    // =====================================================
    // 3. GET /api/v1/social/inbox — Unified Inbox
    // =====================================================

    public function inbox(Request $request): JsonResponse
    {
        $limit = min((int) $request->get('limit', 10), 50);

        return response()->json([
            'success' => true,
            'data' => $this->social->getUnifiedInbox($limit),
        ]);
    }

    // =====================================================
    // 4. GET /api/v1/social/posts — Recent posts
    // =====================================================

    public function posts(Request $request): JsonResponse
    {
        $platform = $request->get('platform');
        $limit = min((int) $request->get('limit', 20), 100);

        return response()->json([
            'success' => true,
            'data' => $this->social->getRecentPosts($platform, $limit),
        ]);
    }

    // =====================================================
    // 5. GET /api/v1/social/posts/{id}/analytics — Post metrics
    // =====================================================

    public function postAnalytics(int $id): JsonResponse
    {
        $data = $this->social->getPostAnalytics($id);

        return response()->json([
            'success' => $data['success'],
            'data' => $data,
        ]);
    }

    // =====================================================
    // 6. GET /api/v1/social/calendar — Content calendar
    // =====================================================

    public function calendar(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());

        return response()->json([
            'success' => true,
            'data' => $this->social->getContentCalendar($dateFrom, $dateTo),
        ]);
    }

    // =====================================================
    // 7. POST /api/v1/social/sync — Sync all account stats
    // =====================================================

    public function sync(): JsonResponse
    {
        $results = $this->social->syncAllAccounts();

        return response()->json([
            'success' => true,
            'message' => 'Accounts synced.',
            'data' => $results,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// SocialController — 7 API endpoints.
//   GET  /social/accounts         — 8 аккаунтов + followers
//   POST /social/publish          — Unified Posting (массовый постинг)
//   GET  /social/inbox            — Unified Inbox (FB, IG, YT, TG)
//   GET  /social/posts            — последние посты (?platform=)
//   GET  /social/posts/{id}/analytics — метрики конкретного поста
//   GET  /social/calendar         — контент-план по датам
//   POST /social/sync             — ручная синхронизация аккаунтов
// Файл: app/Http/Controllers/Api/V1/SocialController.php
// ---------------------------------------------------------------
