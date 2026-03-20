<?php

namespace App\Services\System;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemHealthService
{
    // =====================================================
    // FULL HEALTH CHECK
    // =====================================================

    public function getHealth(): array
    {
        return [
            'status' => 'operational',
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'database' => $this->checkDatabase(),
                'redis' => $this->checkRedis(),
                'storage' => $this->checkStorage(),
                'queue' => $this->checkQueue(),
                'reverb' => $this->checkReverb(),
                'n8n' => $this->checkN8n(),
                'mail' => $this->checkMail(),
            ],
            'server' => $this->getServerMetrics(),
            'application' => $this->getAppMetrics(),
        ];
    }

    // =====================================================
    // SERVICE CHECKS
    // =====================================================

    protected function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $latency = round((microtime(true) - $start) * 1000, 1);

            $size = DB::select("SELECT SUM(data_length + index_length) / 1024 / 1024 AS size_mb FROM information_schema.tables WHERE table_schema = ?", [config('database.connections.mysql.database')]);

            $connections = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            $maxConn = DB::select("SHOW VARIABLES LIKE 'max_connections'");

            return [
                'status' => 'healthy',
                'latency_ms' => $latency,
                'size_mb' => round($size[0]->size_mb ?? 0, 1),
                'connections' => (int) ($connections[0]->Value ?? 0),
                'max_connections' => (int) ($maxConn[0]->Value ?? 200),
                'version' => DB::select('SELECT VERSION() as v')[0]->v ?? 'unknown',
            ];
        } catch (\Exception $e) {
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    protected function checkRedis(): array
    {
        try {
            $start = microtime(true);
            Redis::ping();
            $latency = round((microtime(true) - $start) * 1000, 1);

            $info = Redis::info();
            return [
                'status' => 'healthy',
                'latency_ms' => $latency,
                'used_memory_mb' => round(($info['used_memory'] ?? 0) / 1024 / 1024, 1),
                'max_memory_mb' => round(($info['maxmemory'] ?? 0) / 1024 / 1024, 1),
                'connected_clients' => (int) ($info['connected_clients'] ?? 0),
                'keys' => (int) ($info['db0']['keys'] ?? Redis::dbsize()),
                'uptime_days' => round(($info['uptime_in_seconds'] ?? 0) / 86400, 1),
                'version' => $info['redis_version'] ?? 'unknown',
            ];
        } catch (\Exception $e) {
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    protected function checkStorage(): array
    {
        try {
            $disk = disk_total_space('/');
            $free = disk_free_space('/');
            $used = $disk - $free;

            return [
                'status' => ($free / $disk) < 0.1 ? 'warning' : 'healthy',
                'total_gb' => round($disk / 1073741824, 1),
                'used_gb' => round($used / 1073741824, 1),
                'free_gb' => round($free / 1073741824, 1),
                'usage_pct' => round(($used / $disk) * 100, 1),
                'uploads_mb' => round($this->getDirectorySize(storage_path('app/public')) / 1048576, 1),
                'reports_mb' => round($this->getDirectorySize(storage_path('app/reports')) / 1048576, 1),
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }

    protected function checkQueue(): array
    {
        try {
            $queueSize = Redis::llen('queues:default');
            $failedCount = DB::table('failed_jobs')->count();

            return [
                'status' => $queueSize > 100 ? 'warning' : 'healthy',
                'pending_jobs' => (int) $queueSize,
                'failed_jobs' => $failedCount,
                'driver' => config('queue.default'),
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }

    protected function checkReverb(): array
    {
        try {
            $response = Http::timeout(5)->get('http://reverb:8080/health');
            return ['status' => $response->successful() ? 'healthy' : 'down'];
        } catch (\Exception) {
            return ['status' => 'unknown', 'note' => 'WebSocket server unreachable'];
        }
    }

    protected function checkN8n(): array
    {
        try {
            $response = Http::timeout(5)
                ->withBasicAuth(config('services.n8n.user'), config('services.n8n.password'))
                ->get(config('services.n8n.url', 'http://n8n:5678') . '/api/v1/workflows');

            if ($response->successful()) {
                $workflows = $response->json('data', []);
                $active = collect($workflows)->where('active', true)->count();
                return [
                    'status' => 'healthy',
                    'total_workflows' => count($workflows),
                    'active_workflows' => $active,
                    'url' => config('services.n8n.public_url', 'https://n8n.wincase.pro'),
                ];
            }
            return ['status' => 'down', 'http_status' => $response->status()];
        } catch (\Exception $e) {
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    protected function checkMail(): array
    {
        return [
            'status' => config('mail.mailers.smtp.host') ? 'configured' : 'not_configured',
            'driver' => config('mail.default'),
            'from' => config('mail.from.address'),
        ];
    }

    // =====================================================
    // SERVER METRICS
    // =====================================================

    protected function getServerMetrics(): array
    {
        $load = sys_getloadavg();

        return [
            'hostname' => gethostname(),
            'php_version' => PHP_VERSION,
            'os' => PHP_OS,
            'cpu_load' => [
                '1min' => round($load[0] ?? 0, 2),
                '5min' => round($load[1] ?? 0, 2),
                '15min' => round($load[2] ?? 0, 2),
            ],
            'memory' => [
                'php_usage_mb' => round(memory_get_usage(true) / 1048576, 1),
                'php_peak_mb' => round(memory_get_peak_usage(true) / 1048576, 1),
                'php_limit' => ini_get('memory_limit'),
            ],
            'uptime' => $this->getUptime(),
        ];
    }

    protected function getAppMetrics(): array
    {
        return [
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'timezone' => config('app.timezone'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'opcache_enabled' => function_exists('opcache_get_status') && (opcache_get_status(false)['opcache_enabled'] ?? false),
            'table_counts' => $this->getTableCounts(),
        ];
    }

    // =====================================================
    // SYSTEM SETTINGS MANAGEMENT
    // =====================================================

    public function getSettings(): array
    {
        return [
            'general' => [
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'debug' => config('app.debug'),
            ],
            'mail' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ],
            'integrations' => [
                'google_ads' => ['configured' => !empty(config('services.google_ads.client_id'))],
                'meta_ads' => ['configured' => !empty(config('services.meta.access_token'))],
                'tiktok_ads' => ['configured' => !empty(config('services.tiktok.access_token'))],
                'google_search_console' => ['configured' => !empty(config('services.gsc.property_url'))],
                'google_analytics' => ['configured' => !empty(config('services.ga4.property_id'))],
                'ahrefs' => ['configured' => !empty(config('services.ahrefs.api_key'))],
                'whatsapp' => ['configured' => !empty(config('services.whatsapp.token'))],
                'telegram' => ['configured' => !empty(config('services.telegram.bot_token'))],
                'firebase' => ['configured' => !empty(config('services.firebase.project_id'))],
                'sendgrid' => ['configured' => !empty(config('mail.mailers.smtp.host'))],
                'smsapi' => ['configured' => !empty(config('services.smsapi.token'))],
                'anthropic_ai' => ['configured' => !empty(config('services.anthropic.api_key'))],
                'openai' => ['configured' => !empty(config('services.openai.api_key'))],
            ],
            'news_pipeline' => [
                'ai_provider' => config('services.ai_rewriter.provider', 'anthropic'),
                'ai_model' => config('services.ai_rewriter.model', 'claude-sonnet-4-5-20250929'),
                'auto_publish' => true,
                'plagiarism_threshold' => 15,
            ],
            'security' => [
                'sanctum_expiration' => config('sanctum.expiration'),
                '2fa_enabled' => true,
                'rate_limiting' => [
                    'auth' => '5/min',
                    'api' => '60/min',
                ],
            ],
        ];
    }

    // =====================================================
    // MAINTENANCE MODE
    // =====================================================

    public function enableMaintenance(string $reason = 'Scheduled maintenance'): void
    {
        \Artisan::call('down', ['--render' => 'errors.503', '--secret' => 'wincase-admin-access']);
        Cache::put('maintenance_reason', $reason, now()->addHours(4));
    }

    public function disableMaintenance(): void
    {
        \Artisan::call('up');
        Cache::forget('maintenance_reason');
    }

    public function getMaintenanceStatus(): array
    {
        return [
            'active' => app()->isDownForMaintenance(),
            'reason' => Cache::get('maintenance_reason'),
        ];
    }

    // =====================================================
    // CACHE MANAGEMENT
    // =====================================================

    public function clearCache(string $type = 'all'): array
    {
        $cleared = [];
        if (in_array($type, ['all', 'config'])) { \Artisan::call('config:clear'); $cleared[] = 'config'; }
        if (in_array($type, ['all', 'route'])) { \Artisan::call('route:clear'); $cleared[] = 'route'; }
        if (in_array($type, ['all', 'view'])) { \Artisan::call('view:clear'); $cleared[] = 'view'; }
        if (in_array($type, ['all', 'cache'])) { \Artisan::call('cache:clear'); $cleared[] = 'cache'; }
        if (in_array($type, ['all', 'event'])) { \Artisan::call('event:clear'); $cleared[] = 'event'; }
        return ['cleared' => $cleared];
    }

    public function optimizeCache(): array
    {
        \Artisan::call('config:cache');
        \Artisan::call('route:cache');
        \Artisan::call('view:cache');
        return ['optimized' => ['config', 'route', 'view']];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function getDirectorySize(string $path): int
    {
        if (!is_dir($path)) return 0;
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    protected function getUptime(): string
    {
        if (PHP_OS === 'Linux' && file_exists('/proc/uptime')) {
            $seconds = (int) file_get_contents('/proc/uptime');
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            return "{$days}d {$hours}h";
        }
        return 'N/A';
    }

    protected function getTableCounts(): array
    {
        try {
            $tables = ['users', 'leads', 'clients', 'cases', 'invoices', 'documents',
                'tasks', 'news_articles', 'notifications', 'audit_logs'];
            $counts = [];
            foreach ($tables as $t) {
                try { $counts[$t] = DB::table($t)->count(); } catch (\Exception) { $counts[$t] = 0; }
            }
            return $counts;
        } catch (\Exception) {
            return [];
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// SystemHealthService — полная диагностика системы.
// Health checks: Database (latency, size, connections), Redis (memory, keys, uptime),
// Storage (disk usage), Queue (pending/failed), Reverb (WebSocket), n8n (workflows), Mail.
// Server metrics: CPU load, PHP memory, uptime.
// App metrics: Laravel version, env, debug, opcache, table counts.
// Settings: general, mail, 13 integrations status, news pipeline, security.
// Maintenance mode: enable/disable with reason.
// Cache: clear (config/route/view/cache/event), optimize (cache all).
// Файл: app/Services/System/SystemHealthService.php
// ---------------------------------------------------------------
