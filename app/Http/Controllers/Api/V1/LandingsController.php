<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Landings\LandingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandingsController extends Controller
{
    public function __construct(
        protected LandingsService $landings
    ) {}

    // =====================================================
    // 1. GET /api/v1/landings — List all landing pages
    // =====================================================

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->landings->getAllLandings(
                $request->get('domain'),
                $request->get('status')
            ),
        ]);
    }

    // =====================================================
    // 2. GET /api/v1/landings/{id} — Landing detail
    // =====================================================

    public function show(int $id): JsonResponse
    {
        $landing = $this->landings->getLanding($id);

        return response()->json([
            'success' => true,
            'data' => $landing?->toArray(),
        ]);
    }

    // =====================================================
    // 3. POST /api/v1/landings — Create landing page
    // =====================================================

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'domain' => 'required|string',
            'slug' => 'required|string|max:200',
            'language' => 'required|string|size:2',
            'title' => 'required|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'status' => 'in:draft,active,paused,archived',
        ]);

        $landing = $this->landings->createLanding($request->all());

        return response()->json([
            'success' => true,
            'data' => $landing->toArray(),
        ], 201);
    }

    // =====================================================
    // 4. PUT /api/v1/landings/{id} — Update landing page
    // =====================================================

    public function update(Request $request, int $id): JsonResponse
    {
        $landing = $this->landings->updateLanding($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $landing->toArray(),
        ]);
    }

    // =====================================================
    // 5. POST /api/v1/landings/{id}/variants — Create A/B variant
    // =====================================================

    public function createVariant(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'variant_name' => 'required|string|max:50',
            'traffic_pct' => 'required|integer|min:1|max:100',
            'headline' => 'nullable|string|max:200',
            'cta_text' => 'nullable|string|max:100',
            'cta_color' => 'nullable|string|max:20',
        ]);

        $variant = $this->landings->createVariant($id, $request->all());

        return response()->json([
            'success' => true,
            'data' => $variant->toArray(),
        ], 201);
    }

    // =====================================================
    // 6. POST /api/v1/landings/track — Record visit (public)
    // =====================================================

    public function track(Request $request): JsonResponse
    {
        $request->validate([
            'landing_id' => 'required|integer',
        ]);

        $result = $this->landings->recordVisit($request->integer('landing_id'), [
            'ip' => $request->ip(),
            'utm_source' => $request->get('utm_source'),
            'utm_medium' => $request->get('utm_medium'),
            'utm_campaign' => $request->get('utm_campaign'),
            'utm_content' => $request->get('utm_content'),
            'referer' => $request->header('Referer'),
            'user_agent' => $request->userAgent(),
            'language' => $request->get('language', 'pl'),
            'device_type' => $request->get('device_type', 'desktop'),
        ]);

        return response()->json(['success' => true, 'data' => $result]);
    }

    // =====================================================
    // 7. POST /api/v1/landings/convert — Record conversion (public)
    // =====================================================

    public function convert(Request $request): JsonResponse
    {
        $request->validate(['variant_id' => 'required|integer']);

        $this->landings->recordConversion($request->integer('variant_id'));

        return response()->json(['success' => true, 'message' => 'Conversion recorded']);
    }

    // =====================================================
    // 8. GET /api/v1/landings/analytics — Full analytics
    // =====================================================

    public function analytics(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->landings->getAnalytics(
                $request->get('domain'),
                (int) $request->get('days', 30)
            ),
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LandingsController — 8 API endpoints.
//   GET    /landings             — список лендингов (?domain=&status=)
//   GET    /landings/:id         — детали + variants
//   POST   /landings             — создание лендинга
//   PUT    /landings/:id         — обновление
//   POST   /landings/:id/variants — создание A/B варианта
//   POST   /landings/track       — запись посещения (public, UTM)
//   POST   /landings/convert     — запись конверсии (public)
//   GET    /landings/analytics   — аналитика (?domain=&days=)
// Файл: app/Http/Controllers/Api/V1/LandingsController.php
// ---------------------------------------------------------------
