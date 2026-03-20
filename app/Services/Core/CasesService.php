<?php

namespace App\Services\Core;

use App\Models\ClientCase;
use Illuminate\Database\Eloquent\Builder;

class CasesService
{
    // =====================================================
    // LIST + FILTER
    // =====================================================

    public function list(array $filters = [], int $perPage = 25): array
    {
        $query = ClientCase::with(['client', 'assignee']);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['service_type'])) $query->where('service_type', $filters['service_type']);
        if (!empty($filters['assigned_to'])) $query->where('assigned_to', $filters['assigned_to']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function (Builder $q) use ($s) {
                $q->where('case_number', 'like', "%{$s}%")
                  ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$s}%"));
            });
        }

        $paginated = $query->orderByDesc('updated_at')->paginate($perPage);

        return [
            'data' => $paginated->items(),
            'meta' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ],
        ];
    }

    // =====================================================
    // SHOW
    // =====================================================

    public function show(int $id): array
    {
        $case = ClientCase::with([
            'client',
            'assignee',
            'documents',
            'tasks',
            'notes',
            'invoices',
        ])->findOrFail($id);

        return [
            'case' => $case->toArray(),
            'progress' => $this->calculateProgress($case),
        ];
    }

    // =====================================================
    // CREATE
    // =====================================================

    public function create(array $data): ClientCase
    {
        $data['case_number'] = $this->generateCaseNumber($data['service_type'] ?? 'GEN');
        $data['status'] = $data['status'] ?? 'active';

        $case = ClientCase::create($data);

        return $case;
    }

    // =====================================================
    // UPDATE
    // =====================================================

    public function update(int $id, array $data): ClientCase
    {
        $case = ClientCase::findOrFail($id);
        $oldStatus = $case->status;

        $case->update($data);

        // Log status change
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            $case->notes()->create([
                'user_id' => auth()->id(),
                'body' => "Status changed: {$oldStatus} → {$data['status']}",
                'type' => 'status_change',
            ]);
        }

        return $case->fresh();
    }

    // =====================================================
    // STATUS TRANSITIONS
    // =====================================================

    public function changeStatus(int $id, string $newStatus): ClientCase
    {
        $case = ClientCase::findOrFail($id);
        $allowed = $this->allowedTransitions($case->status);

        if (!in_array($newStatus, $allowed)) {
            throw new \InvalidArgumentException(
                "Cannot transition from '{$case->status}' to '{$newStatus}'. Allowed: " . implode(', ', $allowed)
            );
        }

        return $this->update($id, ['status' => $newStatus]);
    }

    protected function allowedTransitions(string $current): array
    {
        return match ($current) {
            'active' => ['pending_docs', 'submitted', 'on_hold', 'cancelled'],
            'pending_docs' => ['active', 'submitted', 'cancelled'],
            'submitted' => ['in_review', 'returned', 'cancelled'],
            'in_review' => ['approved', 'returned', 'rejected'],
            'returned' => ['active', 'submitted', 'cancelled'],
            'approved' => ['completed'],
            'rejected' => ['active', 'closed'],
            'on_hold' => ['active', 'cancelled'],
            'completed' => ['closed'],
            'cancelled' => [],
            'closed' => [],
            default => [],
        };
    }

    // =====================================================
    // ASSIGN
    // =====================================================

    public function assign(int $id, int $userId): ClientCase
    {
        return $this->update($id, ['assigned_to' => $userId]);
    }

    // =====================================================
    // DEADLINES
    // =====================================================

    public function getUpcomingDeadlines(int $days = 7): array
    {
        return ClientCase::where('status', 'active')
            ->whereNotNull('deadline')
            ->where('deadline', '<=', now()->addDays($days)->toDateString())
            ->with(['client', 'assignee'])
            ->orderBy('deadline')
            ->get()
            ->toArray();
    }

    // =====================================================
    // STATISTICS
    // =====================================================

    public function getStatistics(): array
    {
        $byStatus = ClientCase::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status')->toArray();

        $byService = ClientCase::selectRaw('service_type, COUNT(*) as count')
            ->groupBy('service_type')->orderByDesc('count')->get()->toArray();

        $avgDuration = ClientCase::whereIn('status', ['completed', 'closed'])
            ->whereNotNull('completed_at')
            ->selectRaw('service_type, AVG(DATEDIFF(completed_at, created_at)) as avg_days')
            ->groupBy('service_type')
            ->get()
            ->toArray();

        $managerLoad = ClientCase::where('status', 'active')
            ->selectRaw('assigned_to, COUNT(*) as count')
            ->groupBy('assigned_to')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();

        return [
            'total' => array_sum($byStatus),
            'by_status' => $byStatus,
            'by_service' => $byService,
            'avg_duration_days' => $avgDuration,
            'manager_workload' => $managerLoad,
        ];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    protected function calculateProgress(ClientCase $case): array
    {
        $stages = ['active', 'pending_docs', 'submitted', 'in_review', 'approved', 'completed'];
        $current = array_search($case->status, $stages);
        $total = count($stages) - 1;

        return [
            'current_stage' => $case->status,
            'stage_index' => max($current, 0),
            'total_stages' => $total,
            'pct' => $total > 0 ? round((max($current, 0) / $total) * 100) : 0,
            'stages' => $stages,
        ];
    }

    protected function generateCaseNumber(string $serviceType): string
    {
        $prefix = strtoupper(substr($serviceType, 0, 3));
        $year = now()->format('y');
        $seq = ClientCase::whereYear('created_at', now()->year)->count() + 1;

        return "{$prefix}-{$year}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// CasesService — управление делами клиентов.
// list() — фильтры: status, service_type, assigned_to, priority, search.
// show() — дело + client + documents + tasks + notes + progress bar.
// create() — auto case_number (LEG-25-0001).
// changeStatus() — валидация переходов (10 статусов, allowedTransitions).
// Workflow: active → pending_docs → submitted → in_review → approved → completed.
// getUpcomingDeadlines() — дела с дедлайном в ближайшие N дней.
// getStatistics() — by status, by service, avg duration, manager workload.
// Файл: app/Services/Core/CasesService.php
// ---------------------------------------------------------------
