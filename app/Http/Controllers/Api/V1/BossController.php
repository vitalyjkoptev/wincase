<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Boss\BossService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BossController extends Controller
{
    public function __construct(protected BossService $boss) {}

    // =====================================================
    // MULTICHAT — unified inbox, all clients, all channels
    // =====================================================

    public function multichat(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getMultichatConversations(
                $request->only('channel', 'search')
            ),
        ]);
    }

    public function multichatMessages(Request $request, int $clientId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getMultichatMessages(
                $clientId,
                (int) $request->get('per_page', 50)
            ),
        ]);
    }

    public function multichatSend(Request $request, int $clientId): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'channel' => 'nullable|string|in:portal,whatsapp,telegram,email,sms,facebook,instagram,viber,tiktok',
            'case_id' => 'nullable|exists:cases,id',
        ]);

        $message = $this->boss->sendMultichatMessage(
            $request->user()->id, $clientId, $request->all()
        );

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->id,
                'client_id' => $message->client_id,
                'channel' => $message->channel,
                'direction' => $message->direction,
                'body' => $message->body,
                'sender_name' => $request->user()->name,
                'sender_role' => $request->user()->role,
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ], 201);
    }

    // =====================================================
    // WORKERS — all employees overview
    // =====================================================

    public function workers(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getWorkers(
                $request->only('search', 'department')
            ),
        ]);
    }

    // =====================================================
    // ALL CLIENTS — boss sees everyone
    // =====================================================

    public function clients(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getAllClients(
                $request->only('status', 'search', 'assigned_to', 'per_page')
            ),
        ]);
    }

    // =====================================================
    // FINANCES — P&L for the boss
    // =====================================================

    public function finances(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getFinanceSummary(
                $request->input('month')
            ),
        ]);
    }
}
