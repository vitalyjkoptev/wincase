<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Boss\BossService;
use App\Services\Staff\StaffService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(
        protected StaffService $staff,
        protected BossService $boss,
    ) {}

    // =====================================================
    // MULTICHAT — unified inbox for staff's clients
    // =====================================================

    public function multichat(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getStaffMultichatConversations(
                $request->user()->id,
                $request->only('channel', 'search')
            ),
        ]);
    }

    public function multichatMessages(Request $request, int $clientId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->boss->getStaffMultichatMessages(
                $request->user()->id,
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

        $message = $this->boss->sendStaffMultichatMessage(
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
    // DASHBOARD
    // =====================================================

    public function dashboard(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getDashboard($request->user()->id),
        ]);
    }

    // =====================================================
    // MY CLIENTS
    // =====================================================

    public function myClients(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getMyClients(
                $request->user()->id,
                $request->only('status', 'search', 'per_page')
            ),
        ]);
    }

    public function clientDetail(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getClientDetail($request->user()->id, $id),
        ]);
    }

    // =====================================================
    // CLIENT COMMUNICATION — all syncs to boss CRM
    // =====================================================

    public function clientMessages(Request $request, int $clientId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getClientMessages(
                $request->user()->id, $clientId,
                (int) $request->get('per_page', 50)
            ),
        ]);
    }

    public function sendClientMessage(Request $request, int $clientId): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'channel' => 'nullable|string|in:app,whatsapp,telegram,email,sms',
            'case_id' => 'nullable|exists:cases,id',
        ]);

        $message = $this->staff->sendClientMessage(
            $request->user()->id, $clientId, $request->all()
        );

        return response()->json(['success' => true, 'data' => $message->toArray()], 201);
    }

    public function markClientRead(Request $request, int $clientId): JsonResponse
    {
        $count = $this->staff->markClientMessagesRead($request->user()->id, $clientId);
        return response()->json(['success' => true, 'marked_read' => $count]);
    }

    public function logClientCall(Request $request, int $clientId): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:2000',
            'direction' => 'nullable|string|in:inbound,outbound',
            'case_id' => 'nullable|exists:cases,id',
        ]);

        $message = $this->staff->logClientCall(
            $request->user()->id, $clientId, $request->all()
        );

        return response()->json(['success' => true, 'data' => $message->toArray()], 201);
    }

    // =====================================================
    // BOSS CHAT — encrypted channel
    // =====================================================

    public function bossChat(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getBossChat(
                $request->user()->id,
                (int) $request->get('per_page', 50)
            ),
        ]);
    }

    public function sendToBoss(Request $request): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'case_id' => 'nullable|exists:cases,id',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $message = $this->staff->sendToBoss(
            $request->user()->id,
            $request->input('body'),
            $request->input('case_id'),
            $request->input('client_id')
        );

        return response()->json(['success' => true, 'data' => $message->toArray()], 201);
    }

    // =====================================================
    // TEAM MESSAGES
    // =====================================================

    public function teamConversations(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getTeamConversations($request->user()->id),
        ]);
    }

    public function teamMessages(Request $request, int $partnerId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getTeamMessages(
                $request->user()->id, $partnerId,
                (int) $request->get('per_page', 50)
            ),
        ]);
    }

    public function sendTeamMessage(Request $request, int $partnerId): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'case_id' => 'nullable|exists:cases,id',
        ]);

        $message = $this->staff->sendTeamMessage(
            $request->user()->id, $partnerId,
            $request->input('body'),
            $request->input('case_id')
        );

        return response()->json(['success' => true, 'data' => $message->toArray()], 201);
    }

    // =====================================================
    // MY CASES
    // =====================================================

    public function myCases(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getMyCases(
                $request->user()->id,
                $request->only('status', 'service_type', 'search', 'per_page')
            ),
        ]);
    }

    public function caseDetail(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getCaseDetail($request->user()->id, $id),
        ]);
    }

    public function addCaseNote(Request $request, int $caseId): JsonResponse
    {
        $request->validate(['body' => 'required|string|max:5000']);

        $note = $this->staff->addCaseNote(
            $request->user()->id, $caseId, $request->input('body')
        );

        return response()->json(['success' => true, 'data' => $note->toArray()], 201);
    }

    public function updateCaseStatus(Request $request, int $caseId): JsonResponse
    {
        $request->validate(['status' => 'required|string']);

        $case = $this->staff->updateCaseStatus(
            $request->user()->id, $caseId, $request->input('status')
        );

        return response()->json(['success' => true, 'data' => $case->toArray()]);
    }

    // =====================================================
    // MY TASKS
    // =====================================================

    public function myTasks(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getMyTasks(
                $request->user()->id,
                $request->only('status', 'due_date', 'priority', 'case_id', 'per_page', 'include_completed')
            ),
        ]);
    }

    public function completeTask(Request $request, int $taskId): JsonResponse
    {
        $task = $this->staff->completeTask($request->user()->id, $taskId);
        return response()->json(['success' => true, 'data' => $task->toArray()]);
    }

    public function updateTaskStatus(Request $request, int $taskId): JsonResponse
    {
        $request->validate(['status' => 'required|string|in:pending,in_progress,completed']);

        $task = $this->staff->updateTaskStatus(
            $request->user()->id, $taskId, $request->input('status')
        );

        return response()->json(['success' => true, 'data' => $task->toArray()]);
    }

    // =====================================================
    // DOCUMENTS
    // =====================================================

    public function myDocuments(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getMyDocuments(
                $request->user()->id,
                $request->only('type', 'status', 'client_id', 'case_id', 'expiring', 'per_page')
            ),
        ]);
    }

    public function uploadDocument(Request $request): JsonResponse
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'file' => 'required|file|max:20480',
            'type' => 'nullable|string',
            'name' => 'nullable|string|max:200',
            'case_id' => 'nullable|exists:cases,id',
            'expires_at' => 'nullable|date',
        ]);

        $doc = $this->staff->uploadDocument(
            $request->user()->id,
            $request->all(),
            $request->file('file')
        );

        return response()->json(['success' => true, 'data' => $doc->toArray()], 201);
    }

    public function requestDocument(Request $request, int $clientId): JsonResponse
    {
        $request->validate([
            'document_type' => 'required|string',
            'message' => 'nullable|string|max:2000',
            'due_date' => 'nullable|date',
            'case_id' => 'nullable|exists:cases,id',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
        ]);

        $result = $this->staff->requestDocumentFromClient(
            $request->user()->id, $clientId, $request->all()
        );

        return response()->json(['success' => true, 'data' => $result], 201);
    }

    // =====================================================
    // TIME TRACKING
    // =====================================================

    public function clockIn(Request $request): JsonResponse
    {
        $entry = $this->staff->clockIn($request->user()->id, $request->ip());
        return response()->json(['success' => true, 'data' => $entry->toArray()]);
    }

    public function clockOut(Request $request): JsonResponse
    {
        $entry = $this->staff->clockOut($request->user()->id);
        if (!$entry) {
            return response()->json(['success' => false, 'message' => 'Not clocked in'], 400);
        }
        return response()->json(['success' => true, 'data' => $entry->toArray()]);
    }

    public function timeHistory(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getTimeHistory(
                $request->user()->id,
                $request->input('month')
            ),
        ]);
    }

    // =====================================================
    // CALENDAR
    // =====================================================

    public function calendar(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getCalendar(
                $request->user()->id,
                $request->input('month')
            ),
        ]);
    }

    // =====================================================
    // PROFILE
    // =====================================================

    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getProfile($request->user()->id),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'nullable|string|max:30',
            'avatar_url' => 'nullable|url|max:500',
            'emergency_contact' => 'nullable|string|max:200',
            'emergency_phone' => 'nullable|string|max:30',
        ]);

        $user = $this->staff->updateProfile($request->user()->id, $request->all());

        return response()->json(['success' => true, 'data' => $user->toArray()]);
    }

    // =====================================================
    // KNOWLEDGE BASE
    // =====================================================

    public function knowledgeBase(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->staff->getKnowledgeBase(),
        ]);
    }
}
