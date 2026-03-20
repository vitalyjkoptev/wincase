<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hearing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * GET /api/v1/calendar — List events with filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Hearing::with(['case', 'client', 'creator']);

        if ($request->filled('start')) {
            $query->where('hearing_date', '>=', $request->input('start'));
        }
        if ($request->filled('end')) {
            $query->where('hearing_date', '<=', $request->input('end'));
        }
        if ($request->filled('user_id')) {
            $query->where('created_by', $request->input('user_id'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }
        if ($request->filled('case_id')) {
            $query->where('case_id', $request->input('case_id'));
        }
        if ($request->filled('status')) {
            $query->byStatus($request->input('status'));
        }

        $events = $query->orderBy('hearing_date')->orderBy('hearing_time')->get();

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * GET /api/v1/calendar/upcoming — Upcoming events for current user.
     */
    public function upcoming(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 7);

        $events = Hearing::where('hearing_date', '>=', now()->toDateString())
            ->where('hearing_date', '<=', now()->addDays($days)->toDateString())
            ->where('status', 'scheduled')
            ->with(['case', 'client'])
            ->orderBy('hearing_date')
            ->orderBy('hearing_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * GET /api/v1/calendar/{id} — Show single event.
     */
    public function show(int $id): JsonResponse
    {
        $event = Hearing::with(['case', 'client', 'creator'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $event,
        ]);
    }

    /**
     * POST /api/v1/calendar — Create new event (hearing/appointment).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'case_id' => 'nullable|exists:cases,id',
            'client_id' => 'nullable|exists:clients,id',
            'hearing_date' => 'required|date',
            'hearing_time' => 'nullable|string',
            'type' => 'required|string',
            'office_name' => 'nullable|string|max:200',
            'room_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
            'interpreter_needed' => 'nullable|boolean',
            'interpreter_language' => 'nullable|string|size:2',
        ]);

        $data = array_merge($request->all(), [
            'created_by' => auth()->id(),
            'status' => 'scheduled',
        ]);

        $event = Hearing::create($data);

        return response()->json([
            'success' => true,
            'data' => $event->toArray(),
        ], 201);
    }

    /**
     * PUT /api/v1/calendar/{id} — Update event.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $event = Hearing::findOrFail($id);
        $event->update($request->only([
            'hearing_date', 'hearing_time', 'type', 'office_name',
            'room_number', 'notes', 'interpreter_needed', 'interpreter_language',
            'status', 'result',
        ]));

        return response()->json([
            'success' => true,
            'data' => $event->fresh()->toArray(),
        ]);
    }

    /**
     * POST /api/v1/calendar/{id}/cancel — Cancel event.
     */
    public function cancel(int $id): JsonResponse
    {
        $event = Hearing::findOrFail($id);
        $event->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'data' => $event->fresh()->toArray(),
            'message' => 'Event cancelled.',
        ]);
    }

    /**
     * DELETE /api/v1/calendar/{id} — Delete event.
     */
    public function destroy(int $id): JsonResponse
    {
        Hearing::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted.',
        ]);
    }
}
