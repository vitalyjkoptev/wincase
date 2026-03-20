<?php

namespace App\Services\Boss;

use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Document;
use App\Models\EmployeeTimeTracking;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BossService
{
    // =====================================================
    // MULTICHAT — all clients, all channels, unified inbox
    // Boss sees EVERYTHING. Every message, every channel.
    // =====================================================

    public function getMultichatConversations(array $filters = []): array
    {
        // Get last message per client, grouped
        $subquery = Message::selectRaw('
                client_id,
                MAX(id) as last_message_id,
                MAX(created_at) as last_message_at,
                SUM(CASE WHEN direction = "inbound" AND read_at IS NULL THEN 1 ELSE 0 END) as unread_count
            ')
            ->whereNotNull('client_id')
            ->groupBy('client_id');

        if (!empty($filters['channel']) && $filters['channel'] !== 'all') {
            $subquery->where('channel', $filters['channel']);
        }

        $conversations = DB::table(DB::raw("({$subquery->toSql()}) as conv"))
            ->mergeBindings($subquery->getQuery())
            ->join('clients', 'clients.id', '=', 'conv.client_id')
            ->join('messages', 'messages.id', '=', 'conv.last_message_id')
            ->leftJoin('users', 'users.id', '=', 'clients.assigned_to')
            ->leftJoin('cases', function ($j) {
                $j->on('cases.client_id', '=', 'clients.id')
                    ->whereNotIn('cases.status', ['completed', 'closed', 'cancelled']);
            })
            ->select([
                'clients.id as client_id',
                'clients.name as client_name',
                'clients.avatar_url as client_avatar',
                'clients.phone as client_phone',
                'clients.email as client_email',
                'users.name as assigned_worker_name',
                'users.id as assigned_worker_id',
                'messages.channel as last_channel',
                'messages.body as last_message',
                'conv.last_message_at',
                'conv.unread_count',
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT m2.channel) FROM messages m2 WHERE m2.client_id = clients.id ORDER BY m2.created_at DESC LIMIT 50) as active_channels_str'),
                'cases.case_number',
                'cases.status as case_status',
            ])
            ->orderByDesc('conv.last_message_at');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $conversations->where(function ($q) use ($s) {
                $q->where('clients.name', 'like', "%{$s}%")
                    ->orWhere('clients.phone', 'like', "%{$s}%")
                    ->orWhere('clients.email', 'like', "%{$s}%")
                    ->orWhere('messages.body', 'like', "%{$s}%");
            });
        }

        return $conversations->limit(100)->get()->map(fn ($row) => [
            'client_id' => $row->client_id,
            'client_name' => $row->client_name,
            'client_avatar' => $row->client_avatar,
            'client_phone' => $row->client_phone,
            'client_email' => $row->client_email,
            'assigned_worker_name' => $row->assigned_worker_name,
            'assigned_worker_id' => $row->assigned_worker_id,
            'last_channel' => $row->last_channel ?? 'portal',
            'last_message' => mb_substr($row->last_message ?? '', 0, 120),
            'last_message_at' => $row->last_message_at,
            'unread_count' => (int) $row->unread_count,
            'active_channels' => $row->active_channels_str
                ? array_unique(explode(',', $row->active_channels_str))
                : [],
            'case_number' => $row->case_number,
            'case_status' => $row->case_status,
        ])->toArray();
    }

    /**
     * Get messages for a specific client across all channels (boss sees all)
     */
    public function getMultichatMessages(int $clientId, int $perPage = 50): array
    {
        return Message::where('client_id', $clientId)
            ->with(['user:id,name,avatar_url,role'])
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->through(fn ($m) => [
                'id' => $m->id,
                'client_id' => $m->client_id,
                'channel' => $m->channel ?? 'portal',
                'direction' => $m->direction,
                'body' => $m->body,
                'sender_name' => $m->direction === 'outbound' ? ($m->user?->name ?? 'System') : null,
                'sender_role' => $m->direction === 'outbound' ? ($m->user?->role ?? 'system') : 'client',
                'attachment_url' => $m->attachment_url ?? null,
                'attachment_type' => $m->attachment_type ?? null,
                'created_at' => $m->created_at->toIso8601String(),
                'read_at' => $m->read_at?->toIso8601String(),
            ])
            ->toArray();
    }

    /**
     * Boss sends a message to any client, any channel
     */
    public function sendMultichatMessage(int $bossId, int $clientId, array $data): Message
    {
        return Message::create([
            'client_id' => $clientId,
            'case_id' => $data['case_id'] ?? null,
            'user_id' => $bossId,
            'channel' => $data['channel'] ?? 'portal',
            'direction' => 'outbound',
            'body' => $data['body'],
            'type' => 'message',
            'sent_at' => now(),
        ]);
    }

    // =====================================================
    // WORKERS — overview of all employees
    // =====================================================

    public function getWorkers(array $filters = []): array
    {
        $query = User::whereIn('role', ['staff', 'admin_staff'])
            ->where('status', 'active');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
                ->orWhere('position', 'like', "%{$s}%"));
        }

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        return $query->get()->map(function (User $u) {
            $timeEntry = EmployeeTimeTracking::where('user_id', $u->id)
                ->whereDate('clock_in', now()->toDateString())
                ->whereNull('clock_out')
                ->first();

            return [
                'id' => $u->id,
                'name' => $u->name,
                'avatar_url' => $u->avatar_url,
                'position' => $u->position,
                'department' => $u->department,
                'is_online' => $u->last_login_at && $u->last_login_at->diffInMinutes(now()) < 10,
                'is_clocked_in' => $timeEntry !== null,
                'active_clients' => Client::where('assigned_to', $u->id)->where('status', 'active')->count(),
                'active_cases' => ClientCase::where('assigned_to', $u->id)->active()->count(),
                'tasks_overdue' => Task::where('assigned_to', $u->id)
                    ->where('status', '!=', 'completed')
                    ->where('due_date', '<', now()->toDateString())->count(),
                'unread_messages' => Message::whereHas('client', fn ($q) => $q->where('assigned_to', $u->id))
                    ->where('direction', 'inbound')
                    ->whereNull('read_at')->count(),
                'last_active_at' => $u->last_login_at?->toIso8601String(),
            ];
        })->toArray();
    }

    // =====================================================
    // ALL CLIENTS — boss sees everyone
    // =====================================================

    public function getAllClients(array $filters = []): array
    {
        $query = Client::with([
            'assignedManager:id,name,avatar_url',
            'cases' => fn ($q) => $q->active()->latest()->limit(2),
        ]);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('first_name', 'like', "%{$s}%")
                ->orWhere('last_name', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"));
        }
        if (!empty($filters['assigned_to'])) $query->where('assigned_to', $filters['assigned_to']);

        return $query->orderByDesc('updated_at')
            ->paginate((int) ($filters['per_page'] ?? 50))
            ->toArray();
    }

    // =====================================================
    // FINANCES — P&L summary for boss
    // =====================================================

    public function getFinanceSummary(?string $month = null): array
    {
        $year = now()->year;
        $m = now()->month;
        if ($month) {
            [$year, $m] = explode('-', $month);
        }

        $invoices = Invoice::whereYear('created_at', $year)->whereMonth('created_at', $m);
        $payments = Payment::whereYear('created_at', $year)->whereMonth('created_at', $m);

        $totalRevenue = (clone $payments)->where('status', 'completed')->sum('amount');
        $totalExpenses = 0; // expenses tracked separately if needed

        $totalInvoices = (clone $invoices)->count();
        $paidInvoices = (clone $invoices)->where('status', 'paid')->count();
        $pendingInvoices = (clone $invoices)->where('status', 'pending')->count();
        $overdueInvoices = (clone $invoices)->where('status', 'overdue')->count();
        $pendingAmount = (clone $invoices)->where('status', 'pending')->sum('amount');

        $recentPayments = Payment::with('client:id,name')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $m)
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'client_name' => $p->client?->name ?? 'Unknown',
                'amount' => (float) $p->amount,
                'method' => $p->method ?? 'cash',
                'status' => $p->status ?? 'pending',
                'date' => $p->created_at->toIso8601String(),
            ]);

        return [
            'total_revenue' => (float) $totalRevenue,
            'total_expenses' => (float) $totalExpenses,
            'net_profit' => (float) ($totalRevenue - $totalExpenses),
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'pending_invoices' => $pendingInvoices,
            'overdue_invoices' => $overdueInvoices,
            'pending_amount' => (float) $pendingAmount,
            'recent_payments' => $recentPayments->toArray(),
        ];
    }

    // =====================================================
    // STAFF MULTICHAT — scoped to assigned clients only
    // Used by worker/staff role
    // =====================================================

    public function getStaffMultichatConversations(int $userId, array $filters = []): array
    {
        $clientIds = Client::where('assigned_to', $userId)->pluck('id');

        $subquery = Message::selectRaw('
                client_id,
                MAX(id) as last_message_id,
                MAX(created_at) as last_message_at,
                SUM(CASE WHEN direction = "inbound" AND read_at IS NULL THEN 1 ELSE 0 END) as unread_count
            ')
            ->whereIn('client_id', $clientIds)
            ->groupBy('client_id');

        if (!empty($filters['channel']) && $filters['channel'] !== 'all') {
            $subquery->where('channel', $filters['channel']);
        }

        $conversations = DB::table(DB::raw("({$subquery->toSql()}) as conv"))
            ->mergeBindings($subquery->getQuery())
            ->join('clients', 'clients.id', '=', 'conv.client_id')
            ->join('messages', 'messages.id', '=', 'conv.last_message_id')
            ->leftJoin('cases', function ($j) {
                $j->on('cases.client_id', '=', 'clients.id')
                    ->whereNotIn('cases.status', ['completed', 'closed', 'cancelled']);
            })
            ->select([
                'clients.id as client_id',
                'clients.name as client_name',
                'clients.avatar_url as client_avatar',
                'clients.phone as client_phone',
                'clients.email as client_email',
                'messages.channel as last_channel',
                'messages.body as last_message',
                'conv.last_message_at',
                'conv.unread_count',
                DB::raw('(SELECT GROUP_CONCAT(DISTINCT m2.channel) FROM messages m2 WHERE m2.client_id = clients.id ORDER BY m2.created_at DESC LIMIT 50) as active_channels_str'),
                'cases.case_number',
                'cases.status as case_status',
            ])
            ->orderByDesc('conv.last_message_at');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $conversations->where(function ($q) use ($s) {
                $q->where('clients.name', 'like', "%{$s}%")
                    ->orWhere('clients.phone', 'like', "%{$s}%")
                    ->orWhere('messages.body', 'like', "%{$s}%");
            });
        }

        return $conversations->limit(100)->get()->map(fn ($row) => [
            'client_id' => $row->client_id,
            'client_name' => $row->client_name,
            'client_avatar' => $row->client_avatar,
            'client_phone' => $row->client_phone,
            'client_email' => $row->client_email,
            'assigned_worker_name' => null,
            'assigned_worker_id' => $userId,
            'last_channel' => $row->last_channel ?? 'portal',
            'last_message' => mb_substr($row->last_message ?? '', 0, 120),
            'last_message_at' => $row->last_message_at,
            'unread_count' => (int) $row->unread_count,
            'active_channels' => $row->active_channels_str
                ? array_unique(explode(',', $row->active_channels_str))
                : [],
            'case_number' => $row->case_number,
            'case_status' => $row->case_status,
        ])->toArray();
    }

    public function getStaffMultichatMessages(int $userId, int $clientId, int $perPage = 50): array
    {
        // Verify this client is assigned to the user
        Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        return $this->getMultichatMessages($clientId, $perPage);
    }

    public function sendStaffMultichatMessage(int $userId, int $clientId, array $data): Message
    {
        // Verify access
        Client::where('id', $clientId)->where('assigned_to', $userId)->firstOrFail();

        return Message::create([
            'client_id' => $clientId,
            'case_id' => $data['case_id'] ?? null,
            'user_id' => $userId,
            'channel' => $data['channel'] ?? 'portal',
            'direction' => 'outbound',
            'body' => $data['body'],
            'type' => 'message',
            'sent_at' => now(),
        ]);
    }
}
