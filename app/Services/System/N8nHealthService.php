<?php

namespace App\Services\System;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class N8nHealthService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.n8n.url', 'http://localhost:5678');
        $this->apiKey = config('services.n8n.api_key', '');
    }

    // =====================================================
    // WORKFLOWS STATUS
    // =====================================================

    public function getAllWorkflowsStatus(): array
    {
        $registry = require base_path('n8n/workflows_registry.php');
        $statuses = [];

        try {
            $response = Http::withHeaders(['X-N8N-API-KEY' => $this->apiKey])
                ->get("{$this->baseUrl}/api/v1/workflows");

            $n8nWorkflows = collect($response->json('data') ?? []);

            foreach ($registry as $id => $workflow) {
                $n8nWf = $n8nWorkflows->firstWhere('name', $workflow['name']);

                $statuses[$id] = [
                    'id' => $id,
                    'name' => $workflow['name'],
                    'module' => $workflow['module'],
                    'frequency' => $workflow['frequency'],
                    'n8n_active' => $n8nWf['active'] ?? false,
                    'n8n_id' => $n8nWf['id'] ?? null,
                    'last_updated' => $n8nWf['updatedAt'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            // n8n unreachable — return registry with unknown status
            foreach ($registry as $id => $workflow) {
                $statuses[$id] = [
                    'id' => $id,
                    'name' => $workflow['name'],
                    'module' => $workflow['module'],
                    'frequency' => $workflow['frequency'],
                    'n8n_active' => null,
                    'error' => 'n8n unreachable',
                ];
            }
        }

        return $statuses;
    }

    // =====================================================
    // RECENT EXECUTIONS
    // =====================================================

    public function getRecentExecutions(int $limit = 20): array
    {
        try {
            $response = Http::withHeaders(['X-N8N-API-KEY' => $this->apiKey])
                ->get("{$this->baseUrl}/api/v1/executions", [
                    'limit' => $limit,
                    'includeData' => false,
                ]);

            return collect($response->json('data') ?? [])->map(fn ($exec) => [
                'id' => $exec['id'],
                'workflow_name' => $exec['workflowData']['name'] ?? 'Unknown',
                'status' => $exec['finished'] ? ($exec['stoppedAt'] ? 'success' : 'running') : 'failed',
                'started_at' => $exec['startedAt'] ?? null,
                'finished_at' => $exec['stoppedAt'] ?? null,
                'mode' => $exec['mode'] ?? 'unknown',
            ])->toArray();
        } catch (\Exception $e) {
            return ['error' => 'n8n unreachable: ' . $e->getMessage()];
        }
    }

    // =====================================================
    // HEALTH CHECK
    // =====================================================

    public function healthCheck(): array
    {
        $checks = [
            'n8n' => $this->checkN8n(),
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'disk' => $this->checkDisk(),
            'api' => true,
        ];

        return [
            'status' => !in_array(false, $checks) ? 'healthy' : 'degraded',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    protected function checkN8n(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/healthz");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function checkDatabase(): bool
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function checkRedis(): bool
    {
        try {
            Cache::store('redis')->put('health_check', true, 10);
            return Cache::store('redis')->get('health_check') === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function checkDisk(): bool
    {
        $free = disk_free_space('/');
        $total = disk_total_space('/');
        return ($free / $total) > 0.1; // > 10% free
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// N8nHealthService — мониторинг 22 n8n workflows + system health.
// getAllWorkflowsStatus() — статус каждого workflow (active/inactive) через n8n API.
// getRecentExecutions() — последние выполнения (success/failed/running).
// healthCheck() — проверка: n8n, MySQL, Redis, disk space, API.
// Файл: app/Services/System/N8nHealthService.php
// ---------------------------------------------------------------
