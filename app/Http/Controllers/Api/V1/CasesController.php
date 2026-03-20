<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClientCase;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Task;
use App\Services\Core\CasesService;
use App\Services\Core\DocumentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    public function __construct(
        protected CasesService $cases,
        protected DocumentsService $docs,
    ) {}

    /**
     * GET /api/v1/cases — List with filters.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->cases->list(
                $request->only('status', 'service_type', 'assigned_to', 'priority', 'search')
            ),
        ]);
    }

    /**
     * GET /api/v1/cases/{id} — Show case detail + progress.
     */
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->cases->show($id),
        ]);
    }

    /**
     * POST /api/v1/cases — Create new case.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->cases->create($request->all())->toArray(),
        ], 201);
    }

    /**
     * PUT /api/v1/cases/{id} — Update case.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->cases->update($id, $request->all())->toArray(),
        ]);
    }

    /**
     * POST /api/v1/cases/{id}/status — Change case status with transition validation.
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|string']);

        return response()->json([
            'success' => true,
            'data' => $this->cases->changeStatus($id, $request->input('status'))->toArray(),
        ]);
    }

    /**
     * POST /api/v1/cases/{id}/assign — Assign case to a user.
     */
    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        return response()->json([
            'success' => true,
            'data' => $this->cases->assign($id, $request->input('assigned_to'))->toArray(),
        ]);
    }

    /**
     * POST /api/v1/cases/{id}/note — Add a note/message to a case.
     */
    public function addNote(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'type' => 'nullable|string',
        ]);

        $case = ClientCase::findOrFail($id);

        $note = Message::create([
            'case_id' => $case->id,
            'client_id' => $case->client_id,
            'user_id' => auth()->id(),
            'body' => $request->input('body'),
            'type' => $request->input('type', 'note'),
            'channel' => 'internal',
            'direction' => 'outbound',
        ]);

        return response()->json([
            'success' => true,
            'data' => $note->toArray(),
        ], 201);
    }

    /**
     * GET /api/v1/cases/{id}/documents — List documents for a case.
     */
    public function documents(int $id): JsonResponse
    {
        ClientCase::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $this->docs->listByCase($id),
        ]);
    }

    /**
     * POST /api/v1/cases/{id}/document — Upload a document to a case.
     */
    public function uploadDocument(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'type' => 'nullable|string',
            'name' => 'nullable|string|max:200',
        ]);

        $case = ClientCase::findOrFail($id);

        $data = array_merge($request->all(), [
            'client_id' => $case->client_id,
            'case_id' => $case->id,
        ]);

        $doc = $this->docs->upload($data, $request->file('file'));

        return response()->json([
            'success' => true,
            'data' => $doc->toArray(),
        ], 201);
    }

    /**
     * GET /api/v1/cases/{id}/tasks — List tasks for a case.
     */
    public function tasks(int $id): JsonResponse
    {
        ClientCase::findOrFail($id);

        $tasks = Task::where('case_id', $id)
            ->with(['assignee'])
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    /**
     * GET /api/v1/cases/{id}/invoices — List invoices for a case.
     */
    public function invoices(int $id): JsonResponse
    {
        ClientCase::findOrFail($id);

        $invoices = Invoice::where('case_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }

    /**
     * DELETE /api/v1/cases/{id} — Delete (cancel) a case.
     */
    public function destroy(int $id): JsonResponse
    {
        $case = ClientCase::findOrFail($id);
        $case->update(['status' => 'cancelled', 'closed_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Case cancelled.',
        ]);
    }
}
