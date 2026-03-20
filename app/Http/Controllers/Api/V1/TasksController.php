<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\Core\TasksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function __construct(protected TasksService $tasks) {}

    /**
     * GET /api/v1/tasks — List all tasks with filters.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->tasks->list(
                $request->only('status', 'assigned_to', 'case_id', 'priority', 'due_before', 'overdue')
            ),
        ]);
    }

    /**
     * GET /api/v1/tasks/my — Current user's active tasks.
     */
    public function my(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->tasks->getMyTasks(auth()->id()),
        ]);
    }

    /**
     * GET /api/v1/tasks/{id} — Show single task.
     */
    public function show(int $id): JsonResponse
    {
        $task = Task::with(['assignee', 'case', 'client', 'creator'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    /**
     * POST /api/v1/tasks — Create new task.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'case_id' => 'nullable|exists:cases,id',
            'priority' => 'nullable|string',
            'description' => 'nullable|string|max:5000',
        ]);

        $data = array_merge($request->all(), [
            'created_by' => auth()->id(),
            'status' => $request->input('status', 'pending'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->tasks->create($data)->toArray(),
        ], 201);
    }

    /**
     * PUT /api/v1/tasks/{id} — Update task.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->tasks->update($id, $request->all())->toArray(),
        ]);
    }

    /**
     * POST /api/v1/tasks/{id}/status — Change task status.
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|string|in:pending,in_progress,completed,cancelled']);

        $task = Task::findOrFail($id);

        $data = ['status' => $request->input('status')];

        if ($request->input('status') === 'completed') {
            $data['completed_at'] = now();
        }

        $task->update($data);

        return response()->json([
            'success' => true,
            'data' => $task->fresh()->toArray(),
        ]);
    }

    /**
     * POST /api/v1/tasks/{id}/assign — Reassign task to another user.
     */
    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        $task = Task::findOrFail($id);
        $task->update(['assigned_to' => $request->input('assigned_to')]);

        return response()->json([
            'success' => true,
            'data' => $task->fresh()->load('assignee')->toArray(),
        ]);
    }

    /**
     * DELETE /api/v1/tasks/{id} — Delete task.
     */
    public function destroy(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted.',
        ]);
    }
}
