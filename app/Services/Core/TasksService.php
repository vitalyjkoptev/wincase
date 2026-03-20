<?php

namespace App\Services\Core;

use App\Models\Task;

class TasksService
{
    public function list(array $filters = []): array
    {
        $query = Task::with(['assignee', 'case']);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['assigned_to'])) $query->where('assigned_to', $filters['assigned_to']);
        if (!empty($filters['case_id'])) $query->where('case_id', $filters['case_id']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['due_before'])) $query->where('due_date', '<=', $filters['due_before']);
        if (!empty($filters['overdue'])) {
            $query->where('status', '!=', 'completed')
                  ->where('due_date', '<', now()->toDateString());
        }

        return $query->orderBy('due_date')->orderByDesc('priority')->get()->toArray();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task->fresh();
    }

    public function complete(int $id): Task
    {
        $task = Task::findOrFail($id);
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        return $task;
    }

    public function getMyTasks(int $userId): array
    {
        return Task::where('assigned_to', $userId)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->with('case')
            ->get()
            ->toArray();
    }

    public function getOverdue(): array
    {
        return Task::where('status', '!=', 'completed')
            ->where('due_date', '<', now()->toDateString())
            ->with(['assignee', 'case'])
            ->orderBy('due_date')
            ->get()
            ->toArray();
    }

    public function getStatistics(): array
    {
        return [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('status', '!=', 'completed')
                ->where('due_date', '<', now()->toDateString())->count(),
            'due_today' => Task::where('due_date', now()->toDateString())
                ->where('status', '!=', 'completed')->count(),
            'due_this_week' => Task::whereBetween('due_date', [
                now()->toDateString(),
                now()->addDays(7)->toDateString()
            ])->where('status', '!=', 'completed')->count(),
        ];
    }
}
