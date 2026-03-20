<?php

namespace App\Services\Core;

use App\Models\Client;
use App\Models\CaseModel;
use App\Models\Invoice;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClientsService
{
    // =====================================================
    // LIST + SEARCH
    // =====================================================

    public function list(array $filters = [], int $perPage = 25): array
    {
        $query = Client::with([
            'cases' => fn ($q) => $q->latest()->limit(3),
            'assignedManager:id,name,role',
        ]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('passport_number', 'like', "%{$search}%");
            });
        }
        if (!empty($filters['nationality'])) {
            $query->where('nationality', $filters['nationality']);
        }
        if (!empty($filters['language'])) {
            $query->where('preferred_language', $filters['language']);
        }
        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        $paginated = $query->orderByDesc('updated_at')->paginate($perPage);

        return [
            'data' => $paginated->items(),
            'meta' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ],
        ];
    }

    // =====================================================
    // SHOW — full profile with timeline
    // =====================================================

    public function show(int $id): array
    {
        $client = Client::with([
            'cases.assignee',
            'invoices',
            'documents',
        ])->findOrFail($id);

        $timeline = $this->buildTimeline($client);

        return [
            'client' => $client->toArray(),
            'timeline' => $timeline,
            'stats' => [
                'total_cases' => $client->cases->count(),
                'active_cases' => $client->cases->where('status', 'active')->count(),
                'total_paid' => (float) $client->invoices->where('status', 'paid')->sum('total_amount'),
                'total_outstanding' => (float) $client->invoices->whereIn('status', ['sent', 'overdue'])->sum('total_amount'),
                'documents_count' => $client->documents->count(),
            ],
        ];
    }

    // =====================================================
    // CREATE
    // =====================================================

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    // =====================================================
    // UPDATE
    // =====================================================

    public function update(int $id, array $data): Client
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client->fresh();
    }

    // =====================================================
    // ARCHIVE / ACTIVATE
    // =====================================================

    public function archive(int $id): Client
    {
        $client = Client::findOrFail($id);
        $client->update(['status' => 'archived']);
        return $client;
    }

    public function activate(int $id): Client
    {
        $client = Client::findOrFail($id);
        $client->update(['status' => 'active']);
        return $client;
    }

    // =====================================================
    // STATISTICS
    // =====================================================

    public function getStatistics(): array
    {
        $byStatus = Client::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $byNationality = Client::selectRaw('nationality, COUNT(*) as count')
            ->groupBy('nationality')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();

        $byLanguage = Client::selectRaw('preferred_language, COUNT(*) as count')
            ->groupBy('preferred_language')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        $monthlyNew = Client::where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();

        return [
            'total' => array_sum($byStatus),
            'by_status' => $byStatus,
            'by_nationality' => $byNationality,
            'by_language' => $byLanguage,
            'monthly_new' => $monthlyNew,
        ];
    }

    // =====================================================
    // TIMELINE BUILDER
    // =====================================================

    protected function buildTimeline(Client $client): array
    {
        $events = [];

        // Client created
        $events[] = [
            'type' => 'client_created',
            'date' => $client->created_at->toIso8601String(),
            'description' => 'Client profile created',
        ];

        // Cases
        foreach ($client->cases as $case) {
            $events[] = [
                'type' => 'case_opened',
                'date' => $case->created_at->toIso8601String(),
                'description' => "Case opened: {$case->service_type}",
                'id' => $case->id,
            ];
        }

        // Invoices
        foreach ($client->invoices as $inv) {
            $events[] = [
                'type' => 'invoice_' . $inv->status,
                'date' => ($inv->paid_at ?? $inv->created_at)->toIso8601String(),
                'description' => "Invoice #{$inv->invoice_number}: {$inv->total_amount} PLN ({$inv->status})",
                'id' => $inv->id,
            ];
        }

        // Sort by date descending
        usort($events, fn ($a, $b) => strcmp($b['date'], $a['date']));

        return $events;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// ClientsService — управление клиентами.
// list() — поиск по name/email/phone/passport, фильтры status/nationality/language.
// show() — полный профиль + timeline (cases, invoices) + stats.
// create(), update(), archive(), activate() — CRUD.
// getStatistics() — по статусу, национальности, языку, ежемесячно.
// buildTimeline() — хронология событий клиента.
// Файл: app/Services/Core/ClientsService.php
// ---------------------------------------------------------------
