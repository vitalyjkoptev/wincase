<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboard
    ) {}

    // =====================================================
    // 1. GET /api/v1/dashboard — Full dashboard (all sections)
    // =====================================================

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getFullDashboard(),
        ]);
    }

    // =====================================================
    // 2. GET /api/v1/dashboard/kpi — KPI bar only (12 cards)
    // =====================================================

    public function kpi(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getKpiBar(),
        ]);
    }

    // =====================================================
    // 3. GET /api/v1/dashboard/cases
    // =====================================================

    public function cases(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getCasesSection(),
        ]);
    }

    // =====================================================
    // 4. GET /api/v1/dashboard/leads
    // =====================================================

    public function leads(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getLeadsSection(),
        ]);
    }

    // =====================================================
    // 5. GET /api/v1/dashboard/finance
    // =====================================================

    public function finance(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getFinanceSection(),
        ]);
    }

    // =====================================================
    // 6. GET /api/v1/dashboard/ads
    // =====================================================

    public function ads(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getAdsSection(),
        ]);
    }

    // =====================================================
    // 7. GET /api/v1/dashboard/social
    // =====================================================

    public function social(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getSocialSection(),
        ]);
    }

    // =====================================================
    // 8. GET /api/v1/dashboard/seo
    // =====================================================

    public function seo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getSeoSection(),
        ]);
    }

    // =====================================================
    // 9. GET /api/v1/dashboard/accounting
    // =====================================================

    public function accounting(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getAccountingSection(),
        ]);
    }

    // =====================================================
    // 10. GET /api/v1/dashboard/automation
    // =====================================================

    public function automation(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->getAutomationSection(),
        ]);
    }

    // =====================================================
    // 11. GET /api/v1/dashboard/realtime
    // =====================================================

    public function realtime(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'online_users' => 0,
                'active_sessions' => 0,
                'pending_leads' => \App\Models\Lead::where('status', 'new')->count(),
                'pending_tasks' => \App\Models\Task::where('status', 'pending')->count(),
                'unread_messages' => \App\Models\Notification::whereNull('read_at')->count(),
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// DashboardController — 10 API endpoints.
//   GET /dashboard           — полный dashboard (все 9 секций + KPI)
//   GET /dashboard/kpi       — 12 KPI карточек (header bar)
//   GET /dashboard/cases     — Cases: статусы, нагрузка, дедлайны
//   GET /dashboard/leads     — Leads: funnel, trend, sources
//   GET /dashboard/finance   — Finance: revenue, invoices, POS
//   GET /dashboard/ads       — Ads: 5 платформ, бюджет, тренд
//   GET /dashboard/social    — Social: accounts, posts, top post
//   GET /dashboard/seo       — SEO: GSC, GA4, DA, network
//   GET /dashboard/accounting — Tax: burden, deadlines, expenses
//   GET /dashboard/automation — n8n: sync status, errors
// Файл: app/Http/Controllers/Api/V1/DashboardController.php
// ---------------------------------------------------------------
