<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    /**
     * GET /api/v1/system/health — System health check.
     */
    public function health(): JsonResponse
    {
        $checks = [];

        // Database
        try {
            DB::connection()->getPdo();
            $checks['database'] = ['status' => 'ok', 'message' => 'Connected'];
        } catch (\Throwable $e) {
            $checks['database'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        // Cache
        try {
            Cache::put('health_check', true, 10);
            $val = Cache::get('health_check');
            $checks['cache'] = ['status' => $val ? 'ok' : 'error', 'message' => $val ? 'Working' : 'Read failed'];
            Cache::forget('health_check');
        } catch (\Throwable $e) {
            $checks['cache'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        // Disk space
        $freeBytes = disk_free_space(storage_path());
        $totalBytes = disk_total_space(storage_path());
        $usedPercent = $totalBytes > 0 ? round((1 - $freeBytes / $totalBytes) * 100, 1) : 0;
        $checks['disk'] = [
            'status' => $usedPercent < 90 ? 'ok' : 'warning',
            'used_percent' => $usedPercent,
            'free_gb' => round($freeBytes / 1073741824, 2),
        ];

        // PHP & Laravel info
        $checks['php_version'] = PHP_VERSION;
        $checks['laravel_version'] = app()->version();
        $checks['environment'] = app()->environment();
        $checks['debug_mode'] = config('app.debug');
        $checks['uptime'] = now()->toIso8601String();

        $allOk = collect($checks)
            ->filter(fn ($v) => is_array($v) && isset($v['status']))
            ->every(fn ($v) => $v['status'] === 'ok');

        return response()->json([
            'success' => true,
            'status' => $allOk ? 'healthy' : 'degraded',
            'data' => $checks,
        ]);
    }

    /**
     * GET /api/v1/system/settings — System settings (non-sensitive).
     */
    public function settings(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'app_env' => config('app.env'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'mail_driver' => config('mail.default'),
                'queue_driver' => config('queue.default'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'filesystem_disk' => config('filesystems.default'),
                'max_upload_size_mb' => (int) ini_get('upload_max_filesize'),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ],
        ]);
    }

    /**
     * POST /api/v1/system/cache/clear — Clear application cache.
     */
    public function clearCache(): JsonResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return response()->json([
            'success' => true,
            'message' => 'All caches cleared (config, route, view, cache).',
        ]);
    }

    /**
     * POST /api/v1/system/cache/optimize — Optimize & cache configuration.
     */
    public function optimizeCache(): JsonResponse
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return response()->json([
            'success' => true,
            'message' => 'Application optimized (config, route, view cached).',
        ]);
    }

    /**
     * GET /api/v1/system/maintenance/status — Check maintenance mode status.
     */
    public function maintenanceStatus(): JsonResponse
    {
        $isDown = app()->isDownForMaintenance();

        return response()->json([
            'success' => true,
            'data' => [
                'maintenance_mode' => $isDown,
                'status' => $isDown ? 'enabled' : 'disabled',
            ],
        ]);
    }

    /**
     * POST /api/v1/system/maintenance/enable — Enable maintenance mode.
     */
    public function maintenanceEnable(Request $request): JsonResponse
    {
        $secret = $request->input('secret', '');

        $args = [];
        if (!empty($secret)) {
            $args['--secret'] = $secret;
        }

        Artisan::call('down', $args);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance mode enabled.',
            'data' => [
                'maintenance_mode' => true,
                'secret' => $secret ?: null,
            ],
        ]);
    }

    /**
     * POST /api/v1/system/maintenance/disable — Disable maintenance mode.
     */
    public function maintenanceDisable(): JsonResponse
    {
        Artisan::call('up');

        return response()->json([
            'success' => true,
            'message' => 'Maintenance mode disabled. Application is live.',
            'data' => [
                'maintenance_mode' => false,
            ],
        ]);
    }
}
