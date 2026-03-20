<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\Core\DocumentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function __construct(protected DocumentsService $docs) {}

    /**
     * GET /api/v1/documents — List all documents with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Document::with(['client', 'uploader']);

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }
        if ($request->filled('case_id')) {
            $query->where('case_id', $request->input('case_id'));
        }
        if ($request->filled('type')) {
            $query->byType($request->input('type'));
        }
        if ($request->filled('status')) {
            $query->byStatus($request->input('status'));
        }
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('original_filename', 'like', "%{$s}%");
            });
        }

        $perPage = (int) $request->get('per_page', 25);
        $paginated = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $paginated->items(),
                'meta' => [
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/documents/expiring — Documents expiring within N days.
     */
    public function expiring(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->docs->getExpiringDocuments(
                (int) $request->get('days', 30)
            ),
        ]);
    }

    /**
     * GET /api/v1/documents/{id} — Show single document.
     */
    public function show(int $id): JsonResponse
    {
        $document = Document::with(['client', 'uploader', 'verifier'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $document,
        ]);
    }

    /**
     * POST /api/v1/documents — Upload a new document.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'file' => 'required|file|max:20480',
            'type' => 'nullable|string',
            'case_id' => 'nullable|exists:cases,id',
            'name' => 'nullable|string|max:200',
            'expires_at' => 'nullable|date',
        ]);

        $doc = $this->docs->upload($request->all(), $request->file('file'));

        return response()->json([
            'success' => true,
            'data' => $doc->toArray(),
        ], 201);
    }

    /**
     * PUT /api/v1/documents/{id} — Update document metadata.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:200',
            'type' => 'nullable|string',
            'status' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $document = Document::findOrFail($id);
        $document->update($request->only('name', 'type', 'status', 'expires_at', 'notes'));

        return response()->json([
            'success' => true,
            'data' => $document->fresh()->toArray(),
        ]);
    }

    /**
     * GET /api/v1/documents/{id}/download — Get temporary download URL.
     */
    public function download(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'url' => $this->docs->getDownloadUrl($id),
            ],
        ]);
    }

    /**
     * GET /api/v1/documents/admin-vault — All docs grouped by client for admin vault.
     */
    public function adminVault(): JsonResponse
    {
        $clients = \App\Models\Client::with(['documents' => function ($q) {
            $q->orderByDesc('created_at');
        }])->whereHas('documents')->get();

        $result = $clients->map(function ($client) {
            return [
                'id' => $client->id,
                'name' => trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')),
                'documents' => $client->documents->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'name' => $doc->name ?? $doc->original_filename,
                        'type' => $doc->type,
                        'status' => $doc->status ?? 'pending_review',
                        'expires_at' => $doc->expires_at?->toDateString(),
                        'created_at' => $doc->created_at?->toIso8601String(),
                    ];
                }),
            ];
        });

        return response()->json(['success' => true, 'clients' => $result]);
    }

    /**
     * GET /api/v1/documents/{id}/preview — Preview info for a document.
     */
    public function preview(int $id): JsonResponse
    {
        $doc = Document::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $doc->id,
                'name' => $doc->name ?? $doc->original_filename,
                'type' => $doc->type,
                'mime_type' => $doc->mime_type ?? 'application/octet-stream',
                'preview_url' => $doc->file_path ? url('/storage/' . $doc->file_path) : null,
                'size' => $doc->file_size ?? null,
            ],
        ]);
    }

    /**
     * PUT/POST /api/v1/documents/{id}/status — Change document verification status.
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending_review,approved,rejected,needs_correction',
            'notes' => 'nullable|string|max:2000',
        ]);

        $doc = Document::findOrFail($id);
        $doc->update([
            'status' => $request->input('status'),
            'notes' => $request->input('notes', $doc->notes),
            'verified_by' => $request->user()?->id,
            'verified_at' => now(),
        ]);

        return response()->json(['success' => true, 'data' => $doc->fresh()->toArray()]);
    }

    /**
     * DELETE /api/v1/documents/{id} — Delete document.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->docs->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Document deleted.',
        ]);
    }
}
