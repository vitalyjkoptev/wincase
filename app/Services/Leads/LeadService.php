<?php

namespace App\Services\Leads;

use App\Enums\LeadSourceEnum;
use App\Enums\LeadStatusEnum;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class LeadService
{
    public function __construct(
        protected LeadRoutingService $routing,
        protected LeadConversionService $conversion
    ) {}

    // =====================================================
    // CREATE LEAD (from any channel)
    // =====================================================

    /**
     * Create a new lead from form submission, webhook, or manual entry.
     * Handles: duplicate check, language detection, priority, routing.
     */
    public function createLead(array $data, ?int $operatorId = null): array
    {
        // 1. Check for duplicate (same phone in last 30 days)
        $duplicate = $this->conversion->isDuplicate($data['phone'] ?? '', $data['email'] ?? null);

        if ($duplicate) {
            return [
                'success' => false,
                'message' => 'Duplicate lead found (same phone within 30 days).',
                'duplicate_lead_id' => $duplicate->id,
                'existing_lead' => $duplicate,
            ];
        }

        // 2. Detect language if not provided
        if (empty($data['language'])) {
            $data['language'] = $this->routing->detectLanguage($data);
        }

        // 3. Detect priority from source
        if (empty($data['priority'])) {
            $source = LeadSourceEnum::tryFrom($data['source'] ?? 'organic');
            if ($source) {
                $data['priority'] = $this->routing->detectPriority($source)->value;
            }
        }

        // 4. Set GDPR consent timestamp
        if (!empty($data['gdpr_consent'])) {
            $data['gdpr_consent_at'] = now();
        }

        // 5. Create lead
        $lead = Lead::create($data);

        // 6. Auto-route to manager
        $this->routing->route($lead);

        Log::info('New lead created', [
            'lead_id' => $lead->id,
            'source' => $lead->source?->value,
            'language' => $lead->language,
            'assigned_to' => $lead->assigned_to,
        ]);

        return [
            'success' => true,
            'message' => 'Lead created and routed.',
            'lead' => $lead->fresh()->load('assignedManager'),
        ];
    }

    // =====================================================
    // UPDATE LEAD
    // =====================================================

    public function updateLead(Lead $lead, array $data): Lead
    {
        // Track status transitions
        $oldStatus = $lead->status;

        $lead->update($data);

        $newStatus = $lead->fresh()->status;

        // Auto-set timestamps on status change
        if ($oldStatus !== $newStatus) {
            $this->handleStatusTransition($lead, $oldStatus, $newStatus);
        }

        return $lead->fresh()->load('assignedManager');
    }

    protected function handleStatusTransition(Lead $lead, LeadStatusEnum $old, LeadStatusEnum $new): void
    {
        match ($new) {
            LeadStatusEnum::CONTACTED => $lead->update(['first_contact_at' => $lead->first_contact_at ?? now()]),
            LeadStatusEnum::CONSULTATION => $lead->update(['consultation_at' => $lead->consultation_at ?? now()]),
            LeadStatusEnum::PAID => $lead->update(['converted_at' => $lead->converted_at ?? now()]),
            default => null,
        };

        Log::info('Lead status changed', [
            'lead_id' => $lead->id,
            'from' => $old->value,
            'to' => $new->value,
        ]);
    }

    // =====================================================
    // LIST WITH FILTERS
    // =====================================================

    public function getLeads(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Lead::with('assignedManager')
            ->orderByDesc('created_at');

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Source filter
        if (!empty($filters['source'])) {
            $query->bySource(LeadSourceEnum::from($filters['source']));
        }

        // Language filter
        if (!empty($filters['language'])) {
            $query->byLanguage($filters['language']);
        }

        // Assigned manager
        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        // Unassigned only
        if (!empty($filters['unassigned'])) {
            $query->unassigned();
        }

        // Priority
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        // Date range
        if (!empty($filters['from'])) {
            $query->where('created_at', '>=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $query->where('created_at', '<=', $filters['to']);
        }

        // Search (name, phone, email)
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('phone', 'LIKE', "%{$s}%")
                  ->orWhere('email', 'LIKE', "%{$s}%");
            });
        }

        // Active only
        if (!empty($filters['active_only'])) {
            $query->active();
        }

        return $query->paginate($perPage);
    }

    // =====================================================
    // FUNNEL DATA
    // =====================================================

    /**
     * Get funnel stages with counts, percentages, avg time.
     */
    public function getFunnelData(int $days = 30): array
    {
        $since = now()->subDays($days);

        $total = Lead::where('created_at', '>=', $since)->count();

        if ($total === 0) {
            return ['total' => 0, 'stages' => [], 'period_days' => $days];
        }

        $stages = [];

        foreach (LeadStatusEnum::cases() as $status) {
            if ($status === LeadStatusEnum::SPAM) {
                continue;
            }

            $count = Lead::where('created_at', '>=', $since)
                ->where(function ($q) use ($status) {
                    // Count all leads that reached this stage (including those who passed through)
                    $q->where('status', $status);

                    // Also count leads at later stages (they passed through this one)
                    foreach (LeadStatusEnum::cases() as $later) {
                        if ($later->funnelOrder() > $status->funnelOrder() && $later !== LeadStatusEnum::SPAM) {
                            $q->orWhere('status', $later);
                        }
                    }
                })
                ->count();

            // Average time to reach this stage (from creation)
            $avgTime = null;
            $timestampField = match ($status) {
                LeadStatusEnum::CONTACTED => 'first_contact_at',
                LeadStatusEnum::CONSULTATION => 'consultation_at',
                LeadStatusEnum::PAID => 'converted_at',
                default => null,
            };

            if ($timestampField) {
                $avgTime = Lead::where('created_at', '>=', $since)
                    ->whereNotNull($timestampField)
                    ->selectRaw("AVG(TIMESTAMPDIFF(HOUR, created_at, {$timestampField})) as avg_hours")
                    ->value('avg_hours');
            }

            $stages[] = [
                'status' => $status->value,
                'label' => $status->label(),
                'color' => $status->color(),
                'count' => $count,
                'percentage' => round(($count / $total) * 100, 1),
                'avg_time_hours' => $avgTime ? round($avgTime, 1) : null,
                'funnel_order' => $status->funnelOrder(),
            ];
        }

        usort($stages, fn ($a, $b) => $a['funnel_order'] <=> $b['funnel_order']);

        return [
            'total' => $total,
            'period_days' => $days,
            'stages' => $stages,
            'conversion_rate' => round(
                (Lead::where('created_at', '>=', $since)->paid()->count() / $total) * 100,
                1
            ),
        ];
    }

    // =====================================================
    // CHANNEL STATS
    // =====================================================

    /**
     * Get stats broken down by source channel.
     */
    public function getChannelStats(int $days = 30): array
    {
        $since = now()->subDays($days);

        $channels = Lead::where('created_at', '>=', $since)
            ->selectRaw("
                source,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as converted,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = 'spam' THEN 1 ELSE 0 END) as spam,
                AVG(CASE WHEN first_contact_at IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, created_at, first_contact_at)
                    ELSE NULL END) as avg_response_min
            ")
            ->groupBy('source')
            ->get()
            ->map(function ($row) {
                $source = LeadSourceEnum::tryFrom($row->source);

                return [
                    'source' => $row->source,
                    'label' => $source?->label() ?? $row->source,
                    'icon' => $source?->icon(),
                    'is_paid' => $source?->isPaid() ?? false,
                    'total' => $row->total,
                    'converted' => $row->converted,
                    'rejected' => $row->rejected,
                    'spam' => $row->spam,
                    'conversion_rate' => $row->total > 0
                        ? round(($row->converted / $row->total) * 100, 1) : 0,
                    'avg_response_min' => $row->avg_response_min
                        ? round($row->avg_response_min, 0) : null,
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        return [
            'period_days' => $days,
            'channels' => $channels,
            'total_leads' => array_sum(array_column($channels, 'total')),
            'total_converted' => array_sum(array_column($channels, 'converted')),
        ];
    }

    // =====================================================
    // DAILY TREND
    // =====================================================

    public function getDailyTrend(int $days = 30): array
    {
        $since = now()->subDays($days);

        return Lead::where('created_at', '>=', $since)
            ->selectRaw("DATE(created_at) as date, source, COUNT(*) as count")
            ->groupBy('date', 'source')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($dayGroup, $date) {
                $bySource = $dayGroup->pluck('count', 'source')->toArray();
                return [
                    'date' => $date,
                    'total' => $dayGroup->sum('count'),
                    'by_source' => $bySource,
                ];
            })
            ->values()
            ->toArray();
    }

    // =====================================================
    // QUICK STATS (for dashboard widget)
    // =====================================================

    public function getQuickStats(): array
    {
        return [
            'today' => Lead::today()->count(),
            'this_week' => Lead::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => Lead::thisMonth()->count(),
            'active' => Lead::active()->count(),
            'unassigned' => Lead::unassigned()->active()->count(),
            'high_priority' => Lead::highPriority()->active()->count(),
            'avg_response_min' => Lead::thisMonth()
                ->whereNotNull('first_contact_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_contact_at)) as avg')
                ->value('avg'),
            'conversion_rate_30d' => $this->getConversionRate(30),
        ];
    }

    protected function getConversionRate(int $days): float
    {
        $since = now()->subDays($days);
        $total = Lead::where('created_at', '>=', $since)->count();

        if ($total === 0) {
            return 0;
        }

        $converted = Lead::where('created_at', '>=', $since)->paid()->count();

        return round(($converted / $total) * 100, 1);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadService — основной сервис модуля лидов (оркестрация).
//
// createLead(): проверка дубликатов (30 дней), определение языка,
// приоритета, GDPR → создание → авто-маршрутизация через LeadRoutingService.
//
// updateLead(): обновление + авто-установка timestamp при смене статуса
// (contacted → first_contact_at, consultation → consultation_at и т.д.).
//
// getLeads(): пагинация с 10 фильтрами (status, source, language,
// assigned_to, unassigned, priority, from/to, search, active_only).
//
// getFunnelData(): воронка — 7 стадий с count, percentage, avg time.
// getChannelStats(): аналитика по каналам — total, converted, rejected,
// conversion_rate, avg_response_min.
// getDailyTrend(): ежедневный тренд лидов по источникам (для графика).
// getQuickStats(): виджет dashboard — today, week, month, unassigned, response time.
//
// Файл: app/Services/Leads/LeadService.php
// ---------------------------------------------------------------
