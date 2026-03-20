<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\Message;
use App\Services\Leads\LeadConversionService;
use App\Services\Leads\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadsController extends Controller
{
    public function __construct(
        protected LeadService $leadService,
        protected LeadConversionService $conversionService,
    ) {}

    /**
     * POST /api/v1/leads/webhook/{source} — Public webhook for external lead sources.
     */
    public function webhook(Request $request, string $source): JsonResponse
    {
        $data = array_merge($request->all(), [
            'source' => $source,
        ]);

        // Basic validation for webhook payloads
        if (empty($data['name']) && empty($data['phone']) && empty($data['email'])) {
            return response()->json([
                'success' => false,
                'message' => 'At least name, phone, or email is required.',
            ], 422);
        }

        $result = $this->leadService->createLead($data);

        if (!$result['success']) {
            return response()->json($result, 409);
        }

        Log::info('Lead webhook received', [
            'source' => $source,
            'lead_id' => $result['lead']->id ?? null,
        ]);

        return response()->json($result, 201);
    }

    /**
     * GET /api/v1/leads — List with filters & pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'status', 'source', 'language', 'assigned_to',
            'unassigned', 'priority', 'from', 'to',
            'search', 'active_only',
        ]);

        $perPage = min((int) $request->get('per_page', 20), 100);
        $leads = $this->leadService->getLeads($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $leads,
        ]);
    }

    /**
     * GET /api/v1/leads/sources — Available lead sources.
     */
    public function sources(): JsonResponse
    {
        $sources = Lead::selectRaw('source, COUNT(*) as count')
            ->groupBy('source')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'source' => $row->source,
                'count' => $row->count,
            ]);

        return response()->json([
            'success' => true,
            'data' => $sources,
        ]);
    }

    /**
     * GET /api/v1/leads/funnel — Funnel data.
     */
    public function funnel(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 30);
        $days = max(7, min($days, 365));

        return response()->json([
            'success' => true,
            'data' => $this->leadService->getFunnelData($days),
        ]);
    }

    /**
     * GET /api/v1/leads/unassigned — Unassigned active leads.
     */
    public function unassigned(Request $request): JsonResponse
    {
        $perPage = min((int) $request->get('per_page', 20), 100);

        $leads = Lead::unassigned()
            ->active()
            ->with('assignedManager')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $leads,
        ]);
    }

    /**
     * GET /api/v1/leads/statistics — Channel & daily stats.
     */
    public function statistics(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 30);
        $days = max(7, min($days, 365));

        try {
            $data = [
                'quick' => $this->leadService->getQuickStats(),
                'channels' => $this->leadService->getChannelStats($days),
                'daily_trend' => $this->leadService->getDailyTrend($days),
            ];
        } catch (\Throwable $e) {
            report($e);
            $data = ['quick' => [], 'channels' => [], 'daily_trend' => []];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * GET /api/v1/leads/{id} — Show lead detail.
     */
    public function show(Lead $lead): JsonResponse
    {
        $lead->load(['assignedManager', 'client', 'case']);

        return response()->json([
            'success' => true,
            'data' => $lead,
            'meta' => [
                'response_time_min' => $lead->responseTime,
                'is_converted' => $lead->isConverted,
                'has_click_id' => $lead->hasClickId,
                'full_utm' => $lead->fullUtm,
            ],
        ]);
    }

    /**
     * POST /api/v1/leads — Create lead (internal/admin).
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $result = $this->leadService->createLead(
            data: $request->validated(),
            operatorId: $request->user()?->id
        );

        if (!$result['success']) {
            return response()->json($result, 409);
        }

        return response()->json($result, 201);
    }

    /**
     * PUT /api/v1/leads/{id} — Full update.
     */
    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        $lead = $this->leadService->updateLead($lead, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lead updated.',
            'data' => $lead,
        ]);
    }

    /**
     * PATCH /api/v1/leads/{id} — Update status only.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|string']);

        $lead = Lead::findOrFail($id);
        $lead = $this->leadService->updateLead($lead, ['status' => $request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated.',
            'data' => $lead,
        ]);
    }

    /**
     * POST /api/v1/leads/{id}/assign — Assign lead to a manager.
     */
    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->assignTo(
            $request->input('assigned_to'),
            $request->filled('priority')
                ? \App\Enums\PriorityEnum::tryFrom($request->input('priority'))
                : null
        );

        return response()->json([
            'success' => true,
            'message' => 'Lead assigned.',
            'data' => $lead->fresh()->load('assignedManager'),
        ]);
    }

    /**
     * POST /api/v1/leads/{id}/convert — Convert lead to client + case.
     */
    public function convert(ConvertLeadRequest $request, Lead $lead): JsonResponse
    {
        $clientOverrides = array_filter($request->only([
            'client_name', 'client_email', 'client_phone',
            'client_passport', 'client_address',
        ]));

        $clientData = [];
        foreach ($clientOverrides as $key => $value) {
            $clientData[str_replace('client_', '', $key)] = $value;
        }

        $result = $this->conversionService->convert(
            lead: $lead,
            clientData: $clientData,
            createCase: $request->boolean('create_case', true),
            caseNotes: $request->get('case_notes')
        );

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result, 201);
    }

    /**
     * POST /api/v1/leads/{id}/note — Add a note to a lead.
     */
    public function addNote(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $lead = Lead::findOrFail($id);

        $note = Message::create([
            'client_id' => $lead->client_id,
            'case_id' => $lead->case_id,
            'user_id' => auth()->id(),
            'body' => $request->input('body'),
            'type' => 'note',
            'channel' => 'internal',
            'direction' => 'outbound',
        ]);

        // Also append to lead notes field
        $existingNotes = $lead->notes ?? '';
        $timestamp = now()->format('Y-m-d H:i');
        $userName = auth()->user()?->name ?? 'System';
        $lead->update([
            'notes' => trim($existingNotes . "\n[{$timestamp}] {$userName}: " . $request->input('body')),
        ]);

        return response()->json([
            'success' => true,
            'data' => $note->toArray(),
            'message' => 'Note added.',
        ], 201);
    }

    /**
     * DELETE /api/v1/leads/{id} — Soft delete lead.
     */
    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted.',
        ]);
    }
}
