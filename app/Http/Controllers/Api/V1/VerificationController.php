<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientVerification;
use App\Services\Verification\AuthologicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function __construct(
        protected AuthologicService $authologic,
    ) {}

    /**
     * POST /verification/start/{clientId} — start identity verification for a client
     */
    public function start(Request $request, int $clientId): JsonResponse
    {
        $client = Client::findOrFail($clientId);

        $type = $request->input('type', 'identity');
        $strategy = $request->input('strategy'); // optional override

        if ($strategy) {
            // Override strategy in config for this request
            config(['services.authologic.strategy' => $strategy]);
            $this->authologic = new AuthologicService();
        }

        $result = $this->authologic->startVerification(
            $client,
            $type,
            $request->user()?->id
        );

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['error']], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'verification_id' => $result['verification_id'],
                'conversation_id' => $result['conversation_id'],
                'url' => $result['url'],
                'status' => $result['status'],
            ],
        ]);
    }

    /**
     * GET /verification/status/{id} — check verification status
     */
    public function status(int $id): JsonResponse
    {
        $verification = ClientVerification::with('client:id,first_name,last_name,email')
            ->findOrFail($id);

        $conversationId = $verification->result_data['conversation_id'] ?? null;

        // If pending and has conversation_id — refresh from Authologic
        if ($verification->status === 'pending' && $conversationId) {
            $result = $this->authologic->getConversation($conversationId);
            if ($result['success']) {
                $authStatus = $result['data']['status'] ?? 'UNKNOWN';
                $verification->update([
                    'result_data' => array_merge($verification->result_data ?? [], [
                        'authologic_status' => $authStatus,
                        'last_checked' => now()->toIso8601String(),
                    ]),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $verification->id,
                'client_id' => $verification->client_id,
                'client_name' => $verification->client
                    ? trim($verification->client->first_name . ' ' . $verification->client->last_name)
                    : null,
                'type' => $verification->type,
                'status' => $verification->status,
                'verified_at' => $verification->verified_at?->toIso8601String(),
                'expires_at' => $verification->expires_at?->toDateString(),
                'notes' => $verification->notes,
                'provider' => $verification->result_data['provider'] ?? null,
                'authologic_status' => $verification->result_data['authologic_status'] ?? null,
                'conversation_id' => $verification->result_data['conversation_id'] ?? null,
                'url' => $verification->result_data['url'] ?? null,
                'person_data' => $verification->result_data['person_data'] ?? null,
                'created_at' => $verification->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * POST /verification/callback — webhook callback from Authologic (PUBLIC — no auth!)
     */
    public function callback(Request $request): JsonResponse
    {
        $conversationId = $request->input('cid') ?? $request->input('conversationId');

        if (!$conversationId) {
            Log::warning('Verification callback: missing conversation ID', [
                'query' => $request->query(),
                'body' => $request->all(),
            ]);
            return response()->json(['success' => false, 'message' => 'Missing conversation ID'], 400);
        }

        Log::info('Verification callback received', [
            'conversation_id' => $conversationId,
            'body' => $request->all(),
        ]);

        $result = $this->authologic->handleCallback($conversationId);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['error']], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $result['status'],
                'verification_id' => $result['verification_id'],
            ],
        ]);
    }

    /**
     * GET /verification/strategies — list available verification methods
     */
    public function strategies(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'strategies' => $this->authologic->getStrategies(),
                'current' => config('services.authologic.strategy'),
                'configured' => $this->authologic->isConfigured(),
                'sandbox' => config('services.authologic.sandbox', true),
            ],
        ]);
    }

    /**
     * GET /verification/client/{clientId} — verification history for a client
     */
    public function clientHistory(int $clientId): JsonResponse
    {
        $client = Client::findOrFail($clientId);

        $verifications = ClientVerification::where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($v) => [
                'id' => $v->id,
                'type' => $v->type,
                'status' => $v->status,
                'provider' => $v->result_data['provider'] ?? 'manual',
                'authologic_status' => $v->result_data['authologic_status'] ?? null,
                'verified_at' => $v->verified_at?->toIso8601String(),
                'expires_at' => $v->expires_at?->toDateString(),
                'notes' => $v->notes,
                'person_data' => $v->result_data['person_data'] ?? null,
                'created_at' => $v->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'client' => [
                    'id' => $client->id,
                    'name' => trim($client->first_name . ' ' . $client->last_name),
                    'email' => $client->email,
                ],
                'verifications' => $verifications,
                'has_verified' => $verifications->where('status', 'verified')->isNotEmpty(),
            ],
        ]);
    }

    /**
     * GET /verification/list — all verifications (admin view)
     */
    public function index(Request $request): JsonResponse
    {
        $query = ClientVerification::with('client:id,first_name,last_name,email')
            ->orderByDesc('created_at');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($clientId = $request->input('client_id')) {
            $query->where('client_id', $clientId);
        }

        $verifications = $query->paginate($request->input('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $verifications->through(fn($v) => [
                'id' => $v->id,
                'client_id' => $v->client_id,
                'client_name' => $v->client
                    ? trim($v->client->first_name . ' ' . $v->client->last_name)
                    : 'Unknown',
                'type' => $v->type,
                'status' => $v->status,
                'provider' => $v->result_data['provider'] ?? 'manual',
                'verified_at' => $v->verified_at?->toIso8601String(),
                'notes' => $v->notes,
                'created_at' => $v->created_at?->toIso8601String(),
            ]),
            'meta' => [
                'total' => $verifications->total(),
                'per_page' => $verifications->perPage(),
                'current_page' => $verifications->currentPage(),
            ],
        ]);
    }

    /**
     * POST /verification/{id}/manual — manual verification (boss marks as verified/rejected)
     */
    public function manualVerify(Request $request, int $id): JsonResponse
    {
        $verification = ClientVerification::findOrFail($id);

        $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $verification->update([
            'status' => $request->input('status'),
            'verified_by' => $request->user()->id,
            'verified_at' => $request->input('status') === 'verified' ? now() : null,
            'notes' => $request->input('notes', $verification->notes),
            'result_data' => array_merge($verification->result_data ?? [], [
                'manual_verification' => true,
                'verified_by_user' => $request->user()->id,
                'verified_at' => now()->toIso8601String(),
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->input('status') === 'verified'
                ? 'Verification approved manually.'
                : 'Verification rejected.',
            'data' => [
                'id' => $verification->id,
                'status' => $verification->status,
            ],
        ]);
    }

    /**
     * GET /verification/stats — verification statistics
     */
    public function stats(): JsonResponse
    {
        $total = ClientVerification::count();
        $pending = ClientVerification::where('status', 'pending')->count();
        $verified = ClientVerification::where('status', 'verified')->count();
        $rejected = ClientVerification::where('status', 'rejected')->count();

        $byType = ClientVerification::selectRaw('type, status, COUNT(*) as cnt')
            ->groupBy('type', 'status')
            ->get()
            ->groupBy('type')
            ->map(fn($group) => $group->pluck('cnt', 'status'));

        $recent = ClientVerification::with('client:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn($v) => [
                'id' => $v->id,
                'client_name' => $v->client
                    ? trim($v->client->first_name . ' ' . $v->client->last_name)
                    : 'Unknown',
                'type' => $v->type,
                'status' => $v->status,
                'created_at' => $v->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'pending' => $pending,
                'verified' => $verified,
                'rejected' => $rejected,
                'by_type' => $byType,
                'recent' => $recent,
                'authologic_configured' => $this->authologic->isConfigured(),
            ],
        ]);
    }
}
