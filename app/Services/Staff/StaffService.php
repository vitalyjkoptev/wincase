<?php

namespace App\Services\Staff;

use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Document;
use App\Models\EmployeeKpi;
use App\Models\EmployeeTimeTracking;
use App\Models\Hearing;
use App\Models\Message;
use App\Models\StaffMessage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class StaffService
{
    // =====================================================
    // DASHBOARD — unified view for employee
    // Everything syncs to boss CRM automatically
    // =====================================================

    public function getDashboard(int $userId): array
    {
        $user = User::findOrFail($userId);

        // Today's tasks
        $todayTasks = Task::where('assigned_to', $userId)
            ->where('status', '!=', 'completed')
            ->where(fn ($q) => $q->whereDate('due_date', now()->toDateString())
                ->orWhere(fn ($q2) => $q2->where('due_date', '<', now()->toDateString())))
            ->with('case.client')
            ->orderByDesc('priority')
            ->orderBy('due_date')
            ->get();

        // Active cases with client info
        $activeCases = ClientCase::where('assigned_to', $userId)
            ->whereNotIn('status', ['completed', 'closed', 'cancelled'])
            ->with(['client', 'tasks' => fn ($q) => $q->where('status', '!=', 'completed')->limit(3)])
            ->orderBy('deadline')
            ->get();

        // Unified inbox: boss messages + client messages
        $bossMessages = StaffMessage::where('receiver_id', $userId)
            ->whereNull('read_at')
            ->with('sender:id,name,avatar_url,role')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'source' => 'staff',
                'type' => $m->type,
                'from_name' => $m->sender->name,
                'from_role' => $m->sender->role,
                'avatar_url' => $m->sender->avatar_url,
                'preview' => mb_substr($m->body, 0, 100),
                'case_id' => $m->case_id,
                'client_id' => $m->client_id,
                'created_at' => $m->created_at->toIso8601String(),
            ]);

        $clientMessages = Message::whereHas('client', fn ($q) => $q->where('assigned_to', $userId))
            ->whereNull('read_at')
            ->where('direction', 'inbound')
            ->with('client:id,name,phone,nationality')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'source' => 'client',
                'type' => $m->channel ?? 'chat',
                'from_name' => $m->client->name,
                'from_role' => 'client',
                'avatar_url' => null,
                'preview' => mb_substr($m->body, 0, 100),
                'case_id' => $m->case_id,
                'client_id' => $m->client_id,
                'created_at' => $m->created_at->toIso8601String(),
            ]);

        $inbox = $bossMessages->concat($clientMessages)->sortByDesc('created_at')->values()->take(10);

        // Upcoming deadlines
        $deadlines = ClientCase::where('assigned_to', $userId)
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now()->toDateString())
            ->where('deadline', '<=', now()->addDays(14)->toDateString())
            ->whereNotIn('status', ['completed', 'closed', 'cancelled'])
            ->with('client:id,name,phone')
            ->orderBy('deadline')
            ->limit(10)
            ->get();

        // Expiring documents (for employee's clients)
        $expiringDocs = Document::whereIn('client_id',
                Client::where('assigned_to', $userId)->pluck('id'))
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays(30)->toDateString())
            ->where('expires_at', '>=', now()->toDateString())
            ->with('client:id,name')
            ->orderBy('expires_at')
            ->limit(5)
            ->get();

        // Time tracking status
        $timeEntry = EmployeeTimeTracking::where('user_id', $userId)
            ->whereDate('clock_in', now()->toDateString())
            ->whereNull('clock_out')
            ->first();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'department' => $user->department,
                'position' => $user->position,
                'avatar_url' => $user->avatar_url,
                'today_schedule' => $user->today_schedule,
            ],
            'stats' => [
                'my_clients' => Client::where('assigned_to', $userId)->where('status', 'active')->count(),
                'active_cases' => $activeCases->count(),
                'tasks_today' => $todayTasks->where('due_date', '>=', now()->startOfDay())->count(),
                'tasks_overdue' => $todayTasks->where('due_date', '<', now()->startOfDay())->count(),
                'unread_total' => $bossMessages->count() + $clientMessages->count(),
                'expiring_docs' => $expiringDocs->count(),
            ],
            'today_tasks' => $todayTasks->toArray(),
            'active_cases' => $activeCases->toArray(),
            'inbox' => $inbox->toArray(),
            'deadlines' => $deadlines->toArray(),
            'expiring_documents' => $expiringDocs->toArray(),
            'time_tracking' => [
                'is_clocked_in' => $timeEntry !== null,
                'clock_in_time' => $timeEntry?->clock_in?->toIso8601String(),
            ],
        ];
    }

    // =====================================================
    // MY CLIENTS — full client management
    // =====================================================

    public function getMyClients(int $userId, array $filters = []): array
    {
        $query = Client::where('assigned_to', $userId)
            ->with(['cases' => fn ($q) => $q->latest()->limit(3)]);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('first_name', 'like', "%{$s}%")
                ->orWhere('last_name', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"));
        }

        return $query->orderByDesc('updated_at')
            ->paginate((int) ($filters['per_page'] ?? 20))
            ->toArray();
    }

    public function getClientDetail(int $userId, int $clientId): array
    {
        $client = Client::where('assigned_to', $userId)
            ->with(['cases.tasks', 'documents', 'messages' => fn ($q) => $q->latest()->limit(20)])
            ->findOrFail($clientId);

        return [
            'client' => $client->toArray(),
            'stats' => [
                'total_cases' => $client->cases->count(),
                'active_cases' => $client->cases->whereNotIn('status', ['completed', 'closed', 'cancelled'])->count(),
                'pending_tasks' => $client->cases->flatMap->tasks->where('status', '!=', 'completed')->count(),
                'documents_count' => $client->documents->count(),
                'unread_messages' => $client->messages->whereNull('read_at')->where('direction', 'inbound')->count(),
            ],
        ];
    }

    // =====================================================
    // CLIENT COMMUNICATION — syncs to CRM
    // Employee sends message to client → logged in CRM
    // =====================================================

    public function getClientMessages(int $userId, int $clientId, int $perPage = 50): array
    {
        // Verify access
        Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        return Message::where('client_id', $clientId)
            ->with(['user:id,name,avatar_url'])
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->toArray();
    }

    public function sendClientMessage(int $userId, int $clientId, array $data): Message
    {
        // Verify access
        Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        // Message is saved to CRM — boss sees it automatically
        return Message::create([
            'client_id' => $clientId,
            'case_id' => $data['case_id'] ?? null,
            'user_id' => $userId,
            'channel' => $data['channel'] ?? 'app',
            'direction' => 'outbound',
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'],
            'type' => $data['type'] ?? 'message',
            'sent_at' => now(),
        ]);
    }

    public function markClientMessagesRead(int $userId, int $clientId): int
    {
        Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        return Message::where('client_id', $clientId)
            ->where('direction', 'inbound')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    // =====================================================
    // BOSS COMMUNICATION — encrypted channel
    // =====================================================

    public function getBossChat(int $userId, int $perPage = 50): array
    {
        $boss = User::where('role', 'boss')->first();
        if (!$boss) return ['messages' => [], 'boss' => null];

        $messages = StaffMessage::where('type', 'boss_chat')
            ->conversation($userId, $boss->id)
            ->with(['sender:id,name,avatar_url'])
            ->orderByDesc('created_at')
            ->paginate($perPage);

        // Auto mark read
        StaffMessage::where('type', 'boss_chat')
            ->where('sender_id', $boss->id)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return [
            'messages' => $messages->toArray(),
            'boss' => ['id' => $boss->id, 'name' => $boss->name, 'avatar_url' => $boss->avatar_url],
        ];
    }

    public function sendToBoss(int $userId, string $body, ?int $caseId = null, ?int $clientId = null): StaffMessage
    {
        $bossId = User::where('role', 'boss')->value('id');

        return StaffMessage::create([
            'sender_id' => $userId,
            'receiver_id' => $bossId,
            'body' => $body,
            'type' => 'boss_chat',
            'is_encrypted' => true,
            'case_id' => $caseId,
            'client_id' => $clientId,
        ]);
    }

    // =====================================================
    // TEAM MESSAGES — colleague communication
    // =====================================================

    public function getTeamConversations(int $userId): array
    {
        $conversations = StaffMessage::where(fn ($q) => $q->where('sender_id', $userId)->orWhere('receiver_id', $userId))
            ->where('type', 'message')
            ->selectRaw('
                CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as partner_id,
                MAX(created_at) as last_at,
                SUM(CASE WHEN receiver_id = ? AND read_at IS NULL THEN 1 ELSE 0 END) as unread
            ', [$userId, $userId])
            ->groupByRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END', [$userId])
            ->orderByDesc('last_at')
            ->get();

        $partners = User::whereIn('id', $conversations->pluck('partner_id'))
            ->get(['id', 'name', 'avatar_url', 'role', 'department'])->keyBy('id');

        return $conversations->map(fn ($c) => [
            'partner' => $partners[$c->partner_id]?->toArray(),
            'last_message_at' => $c->last_at,
            'unread_count' => (int) $c->unread,
        ])->toArray();
    }

    public function getTeamMessages(int $userId, int $partnerId, int $perPage = 50): array
    {
        return StaffMessage::conversation($userId, $partnerId)
            ->where('type', 'message')
            ->with(['sender:id,name,avatar_url'])
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->toArray();
    }

    public function sendTeamMessage(int $senderId, int $receiverId, string $body, ?int $caseId = null): StaffMessage
    {
        return StaffMessage::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'body' => $body,
            'type' => 'message',
            'case_id' => $caseId,
        ]);
    }

    // =====================================================
    // MY CASES
    // =====================================================

    public function getMyCases(int $userId, array $filters = []): array
    {
        $query = ClientCase::where('assigned_to', $userId)
            ->with(['client:id,name,phone,nationality', 'tasks' => fn ($q) => $q->where('status', '!=', 'completed')]);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['service_type'])) $query->where('service_type', $filters['service_type']);
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(fn ($q) => $q->where('case_number', 'like', "%{$s}%")
                ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$s}%")));
        }

        return $query->orderBy('deadline')
            ->orderByDesc('updated_at')
            ->paginate((int) ($filters['per_page'] ?? 20))
            ->toArray();
    }

    public function getCaseDetail(int $userId, int $caseId): array
    {
        $case = ClientCase::where('assigned_to', $userId)
            ->with(['client', 'documents', 'tasks', 'invoices', 'hearings', 'notes' => fn ($q) => $q->latest()->limit(20)])
            ->findOrFail($caseId);

        $stages = ['active', 'pending_docs', 'submitted', 'in_review', 'approved', 'completed'];
        $current = max(array_search($case->status, $stages), 0);

        return [
            'case' => $case->toArray(),
            'progress' => [
                'current_stage' => $case->status,
                'stage_index' => $current,
                'total_stages' => count($stages) - 1,
                'pct' => count($stages) > 1 ? round(($current / (count($stages) - 1)) * 100) : 0,
                'stages' => $stages,
            ],
        ];
    }

    /**
     * Add note to case — visible to boss in CRM
     */
    public function addCaseNote(int $userId, int $caseId, string $body): Message
    {
        $case = ClientCase::where('assigned_to', $userId)->findOrFail($caseId);

        return Message::create([
            'client_id' => $case->client_id,
            'case_id' => $caseId,
            'user_id' => $userId,
            'channel' => 'internal',
            'direction' => 'internal',
            'body' => $body,
            'type' => 'note',
            'sent_at' => now(),
        ]);
    }

    /**
     * Update case status — boss sees the change in CRM + auto-note
     */
    public function updateCaseStatus(int $userId, int $caseId, string $newStatus): ClientCase
    {
        $case = ClientCase::where('assigned_to', $userId)->findOrFail($caseId);
        $oldStatus = $case->status;
        $case->update(['status' => $newStatus]);

        // Auto-log status change (boss sees this)
        Message::create([
            'client_id' => $case->client_id,
            'case_id' => $caseId,
            'user_id' => $userId,
            'channel' => 'system',
            'direction' => 'internal',
            'body' => "Status: {$oldStatus} → {$newStatus}",
            'type' => 'status_change',
            'sent_at' => now(),
        ]);

        return $case->fresh();
    }

    // =====================================================
    // MY TASKS
    // =====================================================

    public function getMyTasks(int $userId, array $filters = []): array
    {
        $query = Task::where('assigned_to', $userId)
            ->with(['case.client:id,name,phone']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        } elseif (empty($filters['include_completed'])) {
            $query->where('status', '!=', 'completed');
        }
        if (!empty($filters['due_date'])) $query->whereDate('due_date', $filters['due_date']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['case_id'])) $query->where('case_id', $filters['case_id']);

        return $query->orderBy('due_date')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->paginate((int) ($filters['per_page'] ?? 50))
            ->toArray();
    }

    public function completeTask(int $userId, int $taskId): Task
    {
        $task = Task::where('assigned_to', $userId)->findOrFail($taskId);
        $task->update(['status' => 'completed', 'completed_at' => now()]);

        // Log for boss CRM
        if ($task->case_id) {
            Message::create([
                'client_id' => $task->client_id,
                'case_id' => $task->case_id,
                'user_id' => $userId,
                'channel' => 'system',
                'direction' => 'internal',
                'body' => "Task completed: {$task->title}",
                'type' => 'task_completed',
                'sent_at' => now(),
            ]);
        }

        return $task->fresh();
    }

    public function updateTaskStatus(int $userId, int $taskId, string $status): Task
    {
        $task = Task::where('assigned_to', $userId)->findOrFail($taskId);
        $data = ['status' => $status];
        if ($status === 'completed') $data['completed_at'] = now();
        $task->update($data);
        return $task->fresh();
    }

    // =====================================================
    // DOCUMENTS — view, upload, request from client
    // All actions visible to boss in CRM
    // =====================================================

    public function getMyDocuments(int $userId, array $filters = []): array
    {
        $clientIds = Client::where('assigned_to', $userId)->pluck('id');

        $query = Document::whereIn('client_id', $clientIds)
            ->with(['client:id,name', 'case:id,case_number']);

        if (!empty($filters['type'])) $query->where('type', $filters['type']);
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['client_id'])) $query->where('client_id', $filters['client_id']);
        if (!empty($filters['case_id'])) $query->where('case_id', $filters['case_id']);
        if (!empty($filters['expiring'])) {
            $query->whereNotNull('expires_at')
                ->where('expires_at', '<=', now()->addDays(30)->toDateString());
        }

        return $query->orderByDesc('created_at')
            ->paginate((int) ($filters['per_page'] ?? 25))
            ->toArray();
    }

    /**
     * Upload document from phone (photo/scan) — saved in CRM
     */
    public function uploadDocument(int $userId, array $data, $file): Document
    {
        // Verify employee has access to this client
        $client = Client::where('id', $data['client_id'])->where('assigned_to', $userId)->firstOrFail();

        $path = $file->store(
            "documents/{$data['client_id']}/" . now()->format('Y/m'),
            'private'
        );

        $doc = Document::create([
            'client_id' => $data['client_id'],
            'case_id' => $data['case_id'] ?? null,
            'type' => $data['type'] ?? 'other',
            'name' => $data['name'] ?? $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $userId,
            'status' => 'pending_review',
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        // Auto-log for boss CRM
        Message::create([
            'client_id' => $data['client_id'],
            'case_id' => $data['case_id'] ?? null,
            'user_id' => $userId,
            'channel' => 'system',
            'direction' => 'internal',
            'body' => "Document uploaded: {$doc->name} ({$doc->type})",
            'type' => 'document_uploaded',
            'sent_at' => now(),
        ]);

        return $doc;
    }

    /**
     * Request document from client — creates task + sends message
     */
    public function requestDocumentFromClient(int $userId, int $clientId, array $data): array
    {
        $client = Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        // Create task
        $task = Task::create([
            'title' => "Collect: {$data['document_type']} — {$client->name}",
            'description' => $data['notes'] ?? null,
            'type' => 'document_collection',
            'client_id' => $clientId,
            'case_id' => $data['case_id'] ?? null,
            'assigned_to' => $userId,
            'created_by' => $userId,
            'priority' => $data['priority'] ?? 'medium',
            'due_date' => $data['due_date'] ?? now()->addDays(7),
            'status' => 'pending',
        ]);

        // Send message to client (visible in CRM)
        $message = Message::create([
            'client_id' => $clientId,
            'case_id' => $data['case_id'] ?? null,
            'user_id' => $userId,
            'channel' => $data['channel'] ?? 'app',
            'direction' => 'outbound',
            'body' => $data['message'] ?? "Please provide: {$data['document_type']}",
            'type' => 'document_request',
            'sent_at' => now(),
        ]);

        return ['task' => $task->toArray(), 'message' => $message->toArray()];
    }

    // =====================================================
    // TIME TRACKING
    // =====================================================

    public function clockIn(int $userId, ?string $ip = null): EmployeeTimeTracking
    {
        $existing = EmployeeTimeTracking::where('user_id', $userId)
            ->whereDate('clock_in', now()->toDateString())
            ->whereNull('clock_out')
            ->first();

        if ($existing) return $existing;

        return EmployeeTimeTracking::create([
            'user_id' => $userId,
            'clock_in' => now(),
            'type' => 'regular',
            'ip_address' => $ip,
        ]);
    }

    public function clockOut(int $userId): ?EmployeeTimeTracking
    {
        $entry = EmployeeTimeTracking::where('user_id', $userId)
            ->whereNull('clock_out')
            ->latest('clock_in')
            ->first();

        if (!$entry) return null;

        $hours = now()->diffInMinutes($entry->clock_in) / 60;
        $entry->update(['clock_out' => now(), 'hours_worked' => round($hours, 2)]);

        return $entry->fresh();
    }

    public function getTimeHistory(int $userId, ?string $month = null): array
    {
        $query = EmployeeTimeTracking::where('user_id', $userId);

        if ($month) {
            [$year, $m] = explode('-', $month);
            $query->whereMonth('clock_in', $m)->whereYear('clock_in', $year);
        } else {
            $query->thisMonth();
        }

        $entries = $query->orderByDesc('clock_in')->get();

        return [
            'entries' => $entries->toArray(),
            'total_hours' => round($entries->sum('hours_worked'), 1),
            'days_worked' => $entries->whereNotNull('clock_out')
                ->unique(fn ($e) => $e->clock_in->toDateString())->count(),
        ];
    }

    // =====================================================
    // PROFILE
    // =====================================================

    public function getProfile(int $userId): array
    {
        $user = User::findOrFail($userId);

        $kpi = EmployeeKpi::where('user_id', $userId)
            ->where('period', now()->format('Y-m'))
            ->first();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'department' => $user->department,
                'position' => $user->position,
                'employee_id' => $user->employee_id,
                'avatar_url' => $user->avatar_url,
                'hire_date' => $user->hire_date?->toDateString(),
                'contract_type' => $user->contract_type,
                'work_schedule' => $user->work_schedule,
            ],
            'kpi' => $kpi?->toArray(),
            'stats' => [
                'active_clients' => $user->assignedClients()->where('status', 'active')->count(),
                'active_cases' => $user->active_cases_count,
                'pending_tasks' => $user->pending_tasks_count,
                'total_completed' => ClientCase::where('assigned_to', $userId)
                    ->whereIn('status', ['completed', 'closed'])->count(),
            ],
        ];
    }

    public function updateProfile(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);
        $allowed = ['phone', 'avatar_url', 'emergency_contact', 'emergency_phone'];
        $user->update(array_intersect_key($data, array_flip($allowed)));
        return $user->fresh();
    }

    // =====================================================
    // CALENDAR — tasks + deadlines + hearings merged
    // =====================================================

    public function getCalendar(int $userId, ?string $month = null): array
    {
        $events = collect();

        // Tasks
        $tasks = Task::where('assigned_to', $userId)
            ->whereNotNull('due_date')
            ->where('status', '!=', 'completed')
            ->with('case.client:id,name')
            ->get();

        foreach ($tasks as $t) {
            $events->push([
                'id' => "task-{$t->id}",
                'title' => $t->title,
                'date' => $t->due_date->toDateString(),
                'type' => 'task',
                'priority' => $t->priority,
                'entity_id' => $t->id,
                'case_number' => $t->case?->case_number,
                'client_name' => $t->case?->client?->name,
            ]);
        }

        // Case deadlines
        $cases = ClientCase::where('assigned_to', $userId)
            ->whereNotNull('deadline')
            ->whereNotIn('status', ['completed', 'closed', 'cancelled'])
            ->with('client:id,name')
            ->get();

        foreach ($cases as $c) {
            $events->push([
                'id' => "deadline-{$c->id}",
                'title' => "Deadline: {$c->case_number}",
                'date' => $c->deadline->toDateString(),
                'type' => 'deadline',
                'priority' => 'high',
                'entity_id' => $c->id,
                'case_number' => $c->case_number,
                'client_name' => $c->client?->name,
            ]);
        }

        // Hearings
        $hearings = Hearing::whereHas('case', fn ($q) => $q->where('assigned_to', $userId))
            ->where('date', '>=', now()->subDays(7)->toDateString())
            ->with('case.client:id,name')
            ->get();

        foreach ($hearings as $h) {
            $events->push([
                'id' => "hearing-{$h->id}",
                'title' => "Hearing: {$h->case->case_number}",
                'date' => $h->date->toDateString(),
                'type' => 'hearing',
                'priority' => 'urgent',
                'entity_id' => $h->id,
                'case_number' => $h->case?->case_number,
                'client_name' => $h->case?->client?->name,
                'location' => $h->location ?? null,
                'time' => $h->time ?? null,
            ]);
        }

        if ($month) {
            $events = $events->filter(fn ($e) => str_starts_with($e['date'], $month));
        }

        return $events->sortBy('date')->values()->toArray();
    }

    // =====================================================
    // KNOWLEDGE BASE
    // =====================================================

    public function getKnowledgeBase(): array
    {
        return [
            ['category' => 'Immigration Procedures', 'items' => [
                ['title' => 'Karta Pobytu — Step by Step', 'type' => 'guide'],
                ['title' => 'Work Permit (Zezwolenie)', 'type' => 'guide'],
                ['title' => 'PESEL Registration', 'type' => 'guide'],
                ['title' => 'Meldunek Procedure', 'type' => 'guide'],
                ['title' => 'EU Blue Card', 'type' => 'guide'],
                ['title' => 'Permanent Residence (Pobyt Stały)', 'type' => 'guide'],
            ]],
            ['category' => 'Document Checklists', 'items' => [
                ['title' => 'Temporary Residence', 'type' => 'checklist'],
                ['title' => 'Permanent Residence', 'type' => 'checklist'],
                ['title' => 'Work Permit', 'type' => 'checklist'],
                ['title' => 'Family Reunification', 'type' => 'checklist'],
                ['title' => 'Student Visa', 'type' => 'checklist'],
            ]],
            ['category' => 'Templates', 'items' => [
                ['title' => 'Pełnomocnictwo (Power of Attorney)', 'type' => 'template'],
                ['title' => 'Client Welcome Letter', 'type' => 'template'],
                ['title' => 'Document Request Letter', 'type' => 'template'],
                ['title' => 'Appeal Template', 'type' => 'template'],
            ]],
            ['category' => 'Legal References', 'items' => [
                ['title' => 'Ustawa o cudzoziemcach', 'type' => 'reference'],
                ['title' => 'Labour Code', 'type' => 'reference'],
                ['title' => 'Voivodeship Contacts', 'type' => 'reference'],
            ]],
        ];
    }

    // =====================================================
    // QUICK ACTIONS — frequently used shortcuts
    // =====================================================

    public function logClientCall(int $userId, int $clientId, array $data): Message
    {
        Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        return Message::create([
            'client_id' => $clientId,
            'case_id' => $data['case_id'] ?? null,
            'user_id' => $userId,
            'channel' => 'phone',
            'direction' => $data['direction'] ?? 'outbound',
            'body' => $data['notes'] ?? 'Phone call',
            'type' => 'call_log',
            'sent_at' => now(),
        ]);
    }
}
