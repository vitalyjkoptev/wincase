<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\ActivityTimeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class AuditService
{
    // =====================================================
    // LOG ACTION — universal audit entry
    // =====================================================

    public static function log(
        string $action,
        string $entityType,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
    ): AuditLog {
        $user = Auth::user();

        return AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'system',
            'user_role' => $user?->role ?? 'system',
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues ? self::sanitize($oldValues) : null,
            'new_values' => $newValues ? self::sanitize($newValues) : null,
            'description' => $description ?? self::generateDescription($action, $entityType, $entityId),
            'ip_address' => Request::ip(),
            'user_agent' => Str::limit(Request::userAgent(), 500),
            'request_url' => Str::limit(Request::fullUrl(), 500),
            'request_method' => Request::method(),
            'session_id' => session()->getId(),
        ]);
    }

    // =====================================================
    // PRE-DEFINED ACTIONS
    // =====================================================

    // Auth events
    public static function logLogin(): void
    {
        self::log('login', 'auth', Auth::id(), description: 'User logged in');
    }

    public static function logLogout(): void
    {
        self::log('logout', 'auth', Auth::id(), description: 'User logged out');
    }

    public static function logFailedLogin(string $email): void
    {
        self::log('login_failed', 'auth', null, newValues: ['email' => $email], description: "Failed login attempt: {$email}");
    }

    public static function log2FAEnabled(): void
    {
        self::log('2fa_enabled', 'auth', Auth::id(), description: '2FA enabled');
    }

    public static function logPasswordChanged(): void
    {
        self::log('password_changed', 'auth', Auth::id(), description: 'Password changed');
    }

    // CRUD events
    public static function logCreated(string $entity, int $id, array $data = []): void
    {
        self::log('created', $entity, $id, newValues: $data, description: "{$entity} #{$id} created");
    }

    public static function logUpdated(string $entity, int $id, array $old, array $new): void
    {
        $changed = self::getChangedFields($old, $new);
        if (empty($changed)) return;
        self::log('updated', $entity, $id, oldValues: $changed['old'], newValues: $changed['new'],
            description: "{$entity} #{$id} updated: " . implode(', ', array_keys($changed['new'])));
    }

    public static function logDeleted(string $entity, int $id, array $data = []): void
    {
        self::log('deleted', $entity, $id, oldValues: $data, description: "{$entity} #{$id} deleted");
    }

    // Status changes
    public static function logStatusChanged(string $entity, int $id, string $from, string $to): void
    {
        self::log('status_changed', $entity, $id,
            oldValues: ['status' => $from], newValues: ['status' => $to],
            description: "{$entity} #{$id}: {$from} → {$to}");
    }

    // Assignment
    public static function logAssigned(string $entity, int $id, ?int $fromUser, int $toUser): void
    {
        self::log('assigned', $entity, $id,
            oldValues: ['assigned_to' => $fromUser],
            newValues: ['assigned_to' => $toUser],
            description: "{$entity} #{$id} assigned to user #{$toUser}");
    }

    // Role/Permission changes
    public static function logRoleChanged(int $userId, string $from, string $to): void
    {
        self::log('role_changed', 'user', $userId,
            oldValues: ['role' => $from], newValues: ['role' => $to],
            description: "User #{$userId} role: {$from} → {$to}");
    }

    // Data export
    public static function logExport(string $reportType, string $format): void
    {
        self::log('exported', 'report', null,
            newValues: ['type' => $reportType, 'format' => $format],
            description: "Report exported: {$reportType} ({$format})");
    }

    // Document access
    public static function logDocumentAccess(int $docId, string $action = 'viewed'): void
    {
        self::log("document_{$action}", 'document', $docId, description: "Document #{$docId} {$action}");
    }

    // =====================================================
    // ACTIVITY TIMELINE (for entity pages)
    // =====================================================

    public static function addTimeline(
        string $entityType,
        int $entityId,
        string $action,
        ?string $note = null,
        ?array $metadata = null,
    ): ActivityTimeline {
        return ActivityTimeline::create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()?->name ?? 'System',
            'action' => $action,
            'note' => $note,
            'metadata' => $metadata,
        ]);
    }

    public static function getTimeline(string $entityType, int $entityId, int $limit = 50): array
    {
        return ActivityTimeline::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    // =====================================================
    // QUERY / SEARCH AUDIT LOGS
    // =====================================================

    public static function query(array $filters = [], int $perPage = 30)
    {
        $q = AuditLog::query();

        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        if (!empty($filters['action'])) $q->where('action', $filters['action']);
        if (!empty($filters['entity_type'])) $q->where('entity_type', $filters['entity_type']);
        if (!empty($filters['entity_id'])) $q->where('entity_id', $filters['entity_id']);
        if (!empty($filters['date_from'])) $q->where('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $q->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        if (!empty($filters['ip_address'])) $q->where('ip_address', $filters['ip_address']);
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(function ($sub) use ($s) {
                $sub->where('description', 'like', "%{$s}%")
                    ->orWhere('user_name', 'like', "%{$s}%");
            });
        }

        return $q->orderByDesc('created_at')->paginate($perPage);
    }

    // =====================================================
    // SECURITY AUDIT REPORT
    // =====================================================

    public static function getSecurityReport(int $days = 7): array
    {
        $since = now()->subDays($days);

        return [
            'failed_logins' => AuditLog::where('action', 'login_failed')
                ->where('created_at', '>=', $since)->count(),
            'successful_logins' => AuditLog::where('action', 'login')
                ->where('created_at', '>=', $since)->count(),
            'password_changes' => AuditLog::where('action', 'password_changed')
                ->where('created_at', '>=', $since)->count(),
            'role_changes' => AuditLog::where('action', 'role_changed')
                ->where('created_at', '>=', $since)->count(),
            'data_exports' => AuditLog::where('action', 'exported')
                ->where('created_at', '>=', $since)->count(),
            'document_access' => AuditLog::where('action', 'like', 'document_%')
                ->where('created_at', '>=', $since)->count(),
            'failed_logins_by_ip' => AuditLog::where('action', 'login_failed')
                ->where('created_at', '>=', $since)
                ->selectRaw('ip_address, COUNT(*) as attempts')
                ->groupBy('ip_address')
                ->orderByDesc('attempts')
                ->limit(10)->get()->toArray(),
            'active_users' => AuditLog::where('action', 'login')
                ->where('created_at', '>=', $since)
                ->distinct('user_id')->count('user_id'),
            'top_actions' => AuditLog::where('created_at', '>=', $since)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderByDesc('count')
                ->limit(15)->get()->toArray(),
        ];
    }

    // =====================================================
    // STATISTICS
    // =====================================================

    public static function getStats(int $days = 30): array
    {
        $since = now()->subDays($days);

        return [
            'total_events' => AuditLog::where('created_at', '>=', $since)->count(),
            'by_entity' => AuditLog::where('created_at', '>=', $since)
                ->selectRaw('entity_type, COUNT(*) as count')
                ->groupBy('entity_type')->orderByDesc('count')->get()->toArray(),
            'by_action' => AuditLog::where('created_at', '>=', $since)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')->orderByDesc('count')->get()->toArray(),
            'by_user' => AuditLog::where('created_at', '>=', $since)
                ->selectRaw('user_name, COUNT(*) as count')
                ->groupBy('user_name')->orderByDesc('count')->limit(10)->get()->toArray(),
            'daily_activity' => AuditLog::where('created_at', '>=', $since)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')->get()->toArray(),
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected static function sanitize(array $data): array
    {
        $sensitive = ['password', 'password_confirmation', 'token', 'secret', 'api_key',
            'two_factor_secret', 'two_factor_recovery_codes', 'credit_card'];
        foreach ($sensitive as $key) {
            if (isset($data[$key])) $data[$key] = '********';
        }
        return $data;
    }

    protected static function getChangedFields(array $old, array $new): array
    {
        $changedOld = [];
        $changedNew = [];
        foreach ($new as $key => $value) {
            if (!isset($old[$key]) || $old[$key] !== $value) {
                $changedOld[$key] = $old[$key] ?? null;
                $changedNew[$key] = $value;
            }
        }
        return ['old' => $changedOld, 'new' => $changedNew];
    }

    protected static function generateDescription(string $action, string $entity, ?int $id): string
    {
        $label = ucfirst(str_replace('_', ' ', $entity));
        $idStr = $id ? " #{$id}" : '';
        return "{$label}{$idStr}: {$action}";
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AuditService — полный аудит лог всех действий в CRM.
// log() — универсальная запись: user, action, entity, old/new values, IP, UA.
// Pre-defined actions: login/logout/failed_login, CRUD, status_changed, assigned,
// role_changed, exported, document_access.
// sanitize() — маскировка паролей/токенов/ключей → '********'.
// getChangedFields() — diff между old/new (только изменённые поля).
// Activity Timeline — для страниц сущностей (client/case/lead).
// Security Report: failed logins, by IP, role changes, exports.
// Stats: by entity, action, user, daily trend.
// Файл: app/Services/Audit/AuditService.php
// ---------------------------------------------------------------
