<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Document;
use App\Services\Core\ClientsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function __construct(protected ClientsService $clients) {}

    /**
     * GET /api/v1/clients — List with filters & pagination.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->clients->list(
                $request->only('status', 'search', 'nationality', 'language', 'assigned_to'),
                (int) $request->get('per_page', 25)
            ),
        ]);
    }

    /**
     * GET /api/v1/clients/{id} — Show full profile + timeline + stats.
     */
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->clients->show($id),
        ]);
    }

    /**
     * POST /api/v1/clients — Create new client.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:200',
            'nationality' => 'nullable|string|size:2',
            'preferred_language' => 'nullable|string|size:2',
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->clients->create($request->all())->toArray(),
        ], 201);
    }

    /**
     * PUT /api/v1/clients/{id} — Update client.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->clients->update($id, $request->all())->toArray(),
        ]);
    }

    /**
     * GET /api/v1/clients/{id}/cases — Client's cases.
     */
    public function cases(int $id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $cases = ClientCase::where('client_id', $client->id)
            ->with(['assignee'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cases,
        ]);
    }

    /**
     * GET /api/v1/clients/{id}/documents — Client's documents.
     */
    public function documents(int $id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $documents = Document::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
        ]);
    }

    /**
     * GET /api/v1/clients/{id}/timeline — Client event timeline.
     */
    public function timeline(int $id): JsonResponse
    {
        $data = $this->clients->show($id);

        return response()->json([
            'success' => true,
            'data' => $data['timeline'] ?? [],
        ]);
    }

    /**
     * DELETE /api/v1/clients/{id} — Archive / soft-delete client.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->clients->archive($id);

        return response()->json([
            'success' => true,
            'message' => 'Client archived.',
        ]);
    }
}
