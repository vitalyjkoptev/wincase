<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    /**
     * GET /api/v1/audit/logs — Paginated audit logs.
     */
    public function logs(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 50);

        $query = DB::table('audit_log');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->input('entity_type'));
        }
        if ($request->filled('entity_id')) {
            $query->where('entity_id', $request->input('entity_id'));
        }
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->input('to'));
        }
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
                  ->orWhere('action', 'like', "%{$s}%");
            });
        }

        $logs = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $logs->items(),
                'meta' => [
                    'total' => $logs->total(),
                    'per_page' => $logs->perPage(),
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/audit/security — Security-related events (logins, 2FA, password changes).
     */
    public function security(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 50);

        $securityActions = ['login', 'logout', 'login_failed', 'password_change', '2fa_enable', '2fa_disable'];

        $logs = DB::table('audit_log')
            ->whereIn('action', $securityActions)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $logs->items(),
                'meta' => [
                    'total' => $logs->total(),
                    'per_page' => $logs->perPage(),
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/audit/stats — Audit statistics overview.
     */
    public function stats(): JsonResponse
    {
        $totalLogs = DB::table('audit_log')->count();

        $byAction = DB::table('audit_log')
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        $byEntity = DB::table('audit_log')
            ->selectRaw('entity_type, COUNT(*) as count')
            ->whereNotNull('entity_type')
            ->groupBy('entity_type')
            ->orderByDesc('count')
            ->get();

        $todayCount = DB::table('audit_log')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $topUsers = DB::table('audit_log')
            ->selectRaw('user_id, COUNT(*) as count')
            ->whereNotNull('user_id')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_logs' => $totalLogs,
                'today' => $todayCount,
                'by_action' => $byAction,
                'by_entity' => $byEntity,
                'top_users_7d' => $topUsers,
            ],
        ]);
    }

    /**
     * GET /api/v1/audit/actions — List distinct action types.
     */
    public function actions(): JsonResponse
    {
        $actions = DB::table('audit_log')
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $actions,
        ]);
    }

    /**
     * GET /api/v1/audit/entities — List distinct entity types.
     */
    public function entities(): JsonResponse
    {
        $entities = DB::table('audit_log')
            ->selectRaw('entity_type, COUNT(*) as count')
            ->whereNotNull('entity_type')
            ->groupBy('entity_type')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entities,
        ]);
    }

    /**
     * GET /api/v1/audit/timeline/{entityType}/{entityId} — Timeline for a specific entity.
     */
    public function timeline(string $entityType, int $entityId): JsonResponse
    {
        $logs = DB::table('audit_log')
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * GET /api/v1/audit/user/{userId} — Activity log for a specific user.
     */
    public function userActivity(Request $request, int $userId): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 50);

        $logs = DB::table('audit_log')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $logs->items(),
                'meta' => [
                    'total' => $logs->total(),
                    'per_page' => $logs->perPage(),
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                ],
            ],
        ]);
    }
}
