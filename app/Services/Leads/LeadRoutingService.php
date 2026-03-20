<?php

namespace App\Services\Leads;

use App\Enums\LeadSourceEnum;
use App\Enums\PriorityEnum;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LeadRoutingService
{
    /**
     * Routing rules executed in order. First match wins.
     * Format: ['condition' => callable, 'assignment' => [...], 'priority' => PriorityEnum]
     */
    protected array $rules = [];

    public function __construct()
    {
        $this->registerRules();
    }

    // =====================================================
    // MAIN ROUTING METHOD
    // =====================================================

    /**
     * Route a lead to the appropriate manager.
     * Returns assigned user_id or null if no rule matched (round robin fallback).
     */
    public function route(Lead $lead): ?int
    {
        foreach ($this->rules as $rule) {
            if (($rule['condition'])($lead)) {
                $userId = $this->resolveAssignment($rule['assignment'], $lead);

                if ($userId) {
                    $priority = $rule['priority'] ?? null;
                    $lead->assignTo($userId, $priority);

                    Log::info('Lead routed', [
                        'lead_id' => $lead->id,
                        'rule' => $rule['name'],
                        'assigned_to' => $userId,
                        'priority' => $priority?->value,
                    ]);

                    return $userId;
                }
            }
        }

        // Fallback: Round Robin
        $userId = $this->roundRobin();

        if ($userId) {
            $lead->assignTo($userId);

            Log::info('Lead routed (round robin)', [
                'lead_id' => $lead->id,
                'assigned_to' => $userId,
            ]);
        }

        return $userId;
    }

    // =====================================================
    // RULE REGISTRATION
    // =====================================================

    protected function registerRules(): void
    {
        // Rule 1: Paid lead from ads → HIGH priority
        $this->rules[] = [
            'name' => 'paid_lead_high_priority',
            'condition' => fn (Lead $lead) => $lead->source?->isPaid() && $lead->hasClickId,
            'assignment' => ['role' => 'staff', 'strategy' => 'least_active_leads'],
            'priority' => PriorityEnum::HIGH,
        ];

        // Rule 2: Russian / Ukrainian speaking → Manager RU/UA
        $this->rules[] = [
            'name' => 'language_ru_ua',
            'condition' => fn (Lead $lead) => in_array($lead->language, ['ru', 'ua']),
            'assignment' => ['role' => 'staff', 'language_group' => 'ru_ua'],
            'priority' => null,
        ];

        // Rule 3: Hindi / Tagalog → Manager Asian
        $this->rules[] = [
            'name' => 'language_hi_tl',
            'condition' => fn (Lead $lead) => in_array($lead->language, ['hi', 'tl']),
            'assignment' => ['role' => 'staff', 'language_group' => 'asian'],
            'priority' => null,
        ];

        // Rule 4: English from India/Philippines → Manager Asian
        $this->rules[] = [
            'name' => 'language_en_asian_country',
            'condition' => fn (Lead $lead) => $lead->language === 'en'
                && in_array($lead->country, ['IN', 'PH', 'BD', 'NP', 'LK']),
            'assignment' => ['role' => 'staff', 'language_group' => 'asian'],
            'priority' => null,
        ];

        // Rule 5: Spanish / Turkish → Manager ES/TR
        $this->rules[] = [
            'name' => 'language_es_tr',
            'condition' => fn (Lead $lead) => in_array($lead->language, ['es', 'tr']),
            'assignment' => ['role' => 'staff', 'language_group' => 'es_tr'],
            'priority' => null,
        ];

        // Rule 6: Job Centre service → Manager Job Centre
        $this->rules[] = [
            'name' => 'service_job_centre',
            'condition' => fn (Lead $lead) => $lead->service_type?->value === 'job_centre',
            'assignment' => ['role' => 'staff', 'department' => 'job_centre'],
            'priority' => null,
        ];

        // Rule 7: Walk-in (office) → URGENT priority, any available
        $this->rules[] = [
            'name' => 'walk_in_urgent',
            'condition' => fn (Lead $lead) => $lead->source === LeadSourceEnum::WALK_IN,
            'assignment' => ['role' => 'staff', 'strategy' => 'least_active_leads'],
            'priority' => PriorityEnum::URGENT,
        ];

        // Rule 8: Off-hours (after 18:00 or weekend) → mark for morning
        $this->rules[] = [
            'name' => 'off_hours',
            'condition' => fn (Lead $lead) => $this->isOffHours(),
            'assignment' => ['role' => 'staff', 'strategy' => 'round_robin'],
            'priority' => PriorityEnum::MEDIUM,
        ];
    }

    // =====================================================
    // ASSIGNMENT STRATEGIES
    // =====================================================

    protected function resolveAssignment(array $assignment, Lead $lead): ?int
    {
        $query = User::where('is_active', true)->where('role', $assignment['role'] ?? 'manager');

        // Filter by language group
        if (isset($assignment['language_group'])) {
            $query->where('language_group', $assignment['language_group']);
        }

        // Filter by department
        if (isset($assignment['department'])) {
            $query->where('department', $assignment['department']);
        }

        $managers = $query->pluck('id')->toArray();

        if (empty($managers)) {
            return null;
        }

        // Apply strategy
        $strategy = $assignment['strategy'] ?? 'least_active_leads';

        return match ($strategy) {
            'least_active_leads' => $this->leastActiveLeads($managers),
            'round_robin' => $this->roundRobinFrom($managers),
            default => $managers[0],
        };
    }

    /**
     * Assign to manager with fewest active leads.
     */
    protected function leastActiveLeads(array $managerIds): ?int
    {
        $leadCounts = Lead::whereIn('assigned_to', $managerIds)
            ->active()
            ->selectRaw('assigned_to, COUNT(*) as lead_count')
            ->groupBy('assigned_to')
            ->pluck('lead_count', 'assigned_to')
            ->toArray();

        // Managers with 0 leads won't appear in query — add them
        foreach ($managerIds as $id) {
            if (!isset($leadCounts[$id])) {
                $leadCounts[$id] = 0;
            }
        }

        asort($leadCounts);

        return array_key_first($leadCounts);
    }

    /**
     * Global round robin across all active managers.
     */
    protected function roundRobin(): ?int
    {
        $managers = User::where('is_active', true)
            ->where('role', 'staff')
            ->pluck('id')
            ->toArray();

        if (empty($managers)) {
            return null;
        }

        return $this->roundRobinFrom($managers);
    }

    /**
     * Round robin from a specific set of managers.
     * Uses Redis to track the last assigned index.
     */
    protected function roundRobinFrom(array $managerIds): ?int
    {
        if (empty($managerIds)) {
            return null;
        }

        sort($managerIds);
        $cacheKey = 'lead_rr_' . md5(implode(',', $managerIds));
        $lastIndex = (int) Cache::get($cacheKey, -1);
        $nextIndex = ($lastIndex + 1) % count($managerIds);

        Cache::put($cacheKey, $nextIndex, now()->addDay());

        return $managerIds[$nextIndex];
    }

    // =====================================================
    // HELPERS
    // =====================================================

    /**
     * Check if current time is outside working hours.
     * Working hours: Mon-Fri 9:00-18:00, Sat 10:00-14:00.
     */
    protected function isOffHours(): bool
    {
        $now = now()->timezone('Europe/Warsaw');
        $dayOfWeek = (int) $now->format('N');
        $hour = (int) $now->format('G');

        // Sunday
        if ($dayOfWeek === 7) {
            return true;
        }

        // Saturday: 10:00-14:00
        if ($dayOfWeek === 6) {
            return $hour < 10 || $hour >= 14;
        }

        // Mon-Fri: 9:00-18:00
        return $hour < 9 || $hour >= 18;
    }

    /**
     * Determine language from lead data (IP, URL, explicit).
     */
    public function detectLanguage(array $data): string
    {
        // Explicit language from form
        if (!empty($data['language'])) {
            return $data['language'];
        }

        // Detect from landing page URL
        $landingPage = $data['landing_page'] ?? '';
        $langPatterns = [
            '/\/ru\// ' => 'ru',
            '/\/ua\//' => 'ua',
            '/\/en\//' => 'en',
            '/\/hi\//' => 'hi',  // Hindi (India)
            '/\/tl\//' => 'tl',  // Tagalog (Philippines)
            '/\/es\//' => 'es',  // Spanish (LatAm)
            '/\/tr\//' => 'tr',  // Turkish
        ];

        foreach ($langPatterns as $pattern => $lang) {
            if (preg_match(trim($pattern), $landingPage)) {
                return $lang;
            }
        }

        // Detect from country code
        $country = $data['country'] ?? '';
        $countryLangMap = [
            'UA' => 'ua', 'RU' => 'ru', 'BY' => 'ru', 'KZ' => 'ru',
            'IN' => 'hi', 'PH' => 'tl', 'BD' => 'hi', 'NP' => 'hi',
            'MX' => 'es', 'CO' => 'es', 'AR' => 'es', 'PE' => 'es',
            'TR' => 'tr',
            'PL' => 'pl',
        ];

        return $countryLangMap[$country] ?? 'en';
    }

    /**
     * Determine priority from source.
     */
    public function detectPriority(LeadSourceEnum $source): PriorityEnum
    {
        if ($source->isPaid()) {
            return PriorityEnum::HIGH;
        }

        return match ($source) {
            LeadSourceEnum::WALK_IN => PriorityEnum::URGENT,
            LeadSourceEnum::PHONE => PriorityEnum::HIGH,
            LeadSourceEnum::WHATSAPP, LeadSourceEnum::TELEGRAM => PriorityEnum::MEDIUM,
            default => PriorityEnum::MEDIUM,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadRoutingService — автоматическая маршрутизация лидов.
// 8 правил (выполняются по порядку, первое совпадение = назначение):
//   1. Платный лид (gclid/fbclid/ttclid) → HIGH приоритет, менее загруженный
//   2. Русский/Украинский → Менеджер RU/UA
//   3. Hindi/Tagalog → Менеджер Asian
//   4. English из Индии/Филиппин → Менеджер Asian
//   5. Испанский/Турецкий → Менеджер ES/TR
//   6. Job Centre → Менеджер Job Centre
//   7. Walk-in (офис) → URGENT, любой свободный
//   8. Нерабочее время → Round Robin, MEDIUM
// Стратегии: leastActiveLeads (менее загруженный), roundRobin (по кругу через Redis).
// detectLanguage() — определение языка по URL/стране/форме.
// detectPriority() — приоритет по источнику (платный=HIGH, walk-in=URGENT).
// Файл: app/Services/Leads/LeadRoutingService.php
// ---------------------------------------------------------------
