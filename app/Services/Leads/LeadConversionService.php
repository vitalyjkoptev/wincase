<?php

namespace App\Services\Leads;

use App\Enums\LeadStatusEnum;
use App\Models\Case_;
use App\Models\Client;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadConversionService
{
    // =====================================================
    // CONVERT LEAD → CLIENT + CASE
    // =====================================================

    /**
     * Convert a lead into a CRM client (+ optionally create a case).
     * Runs in a DB transaction for atomicity.
     */
    public function convert(
        Lead $lead,
        array $clientData = [],
        bool $createCase = true,
        ?string $caseNotes = null
    ): array {
        if ($lead->status === LeadStatusEnum::PAID || $lead->client_id) {
            return [
                'success' => false,
                'message' => 'Lead is already converted.',
            ];
        }

        if ($lead->status === LeadStatusEnum::SPAM || $lead->status === LeadStatusEnum::REJECTED) {
            return [
                'success' => false,
                'message' => 'Cannot convert a rejected or spam lead.',
            ];
        }

        return DB::transaction(function () use ($lead, $clientData, $createCase, $caseNotes) {

            // 1. Find existing client by phone OR create new
            $client = $this->findOrCreateClient($lead, $clientData);

            // 2. Create case (if requested)
            $case = null;
            if ($createCase) {
                $case = $this->createCase($lead, $client, $caseNotes);
            }

            // 3. Mark lead as converted
            $lead->convertToClient($client->id, $case?->id);

            // 4. Update lead status to CONTRACT (or PAID if already paid)
            if ($lead->status !== LeadStatusEnum::PAID) {
                $lead->update(['status' => LeadStatusEnum::CONTRACT]);
            }

            Log::info('Lead converted to client', [
                'lead_id' => $lead->id,
                'client_id' => $client->id,
                'case_id' => $case?->id,
                'was_new_client' => $client->wasRecentlyCreated,
            ]);

            return [
                'success' => true,
                'message' => $client->wasRecentlyCreated
                    ? 'New client created from lead.'
                    : 'Lead linked to existing client.',
                'client' => $client,
                'case' => $case,
                'lead' => $lead->fresh(),
            ];
        });
    }

    // =====================================================
    // FIND OR CREATE CLIENT
    // =====================================================

    protected function findOrCreateClient(Lead $lead, array $overrides = []): Client
    {
        // Try to find by phone (primary identifier for immigration bureau)
        $existingClient = Client::where('phone', $lead->phone)->first();

        if ($existingClient) {
            // Update email if missing
            if (!$existingClient->email && $lead->email) {
                $existingClient->update(['email' => $lead->email]);
            }

            return $existingClient;
        }

        // Create new client
        return Client::create(array_merge([
            'name' => $lead->name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'language' => $lead->language,
            'country' => $lead->country,
            'city' => $lead->city,
            'source' => $lead->source?->value,
            'notes' => $this->buildClientNotes($lead),
        ], $overrides));
    }

    // =====================================================
    // CREATE CASE
    // =====================================================

    protected function createCase(Lead $lead, Client $client, ?string $notes = null): Case_
    {
        return Case_::create([
            'client_id' => $client->id,
            'assigned_to' => $lead->assigned_to,
            'service_type' => $lead->service_type?->value ?? 'other',
            'status' => 'new',
            'priority' => $lead->priority?->value ?? 'medium',
            'notes' => $notes ?? $this->buildCaseNotes($lead),
        ]);
    }

    // =====================================================
    // NOTES BUILDERS
    // =====================================================

    protected function buildClientNotes(Lead $lead): string
    {
        $notes = [];
        $notes[] = "Converted from lead #{$lead->id}";
        $notes[] = "Source: {$lead->source?->label()}";

        if ($lead->fullUtm) {
            $notes[] = "UTM: {$lead->fullUtm}";
        }

        if ($lead->landing_page) {
            $notes[] = "Landing: {$lead->landing_page}";
        }

        if ($lead->message) {
            $notes[] = "Message: {$lead->message}";
        }

        return implode("\n", $notes);
    }

    protected function buildCaseNotes(Lead $lead): string
    {
        $notes = [];
        $notes[] = "Auto-created from lead #{$lead->id}";
        $notes[] = "Service: {$lead->service_type?->label()}";
        $notes[] = "Source: {$lead->source?->label()}";

        if ($lead->notes) {
            $notes[] = "Lead notes: {$lead->notes}";
        }

        return implode("\n", $notes);
    }

    // =====================================================
    // BULK OPERATIONS
    // =====================================================

    /**
     * Mark lead as paid (final conversion step — after POS/invoice payment).
     */
    public function markAsPaid(Lead $lead, ?int $invoiceId = null): Lead
    {
        $lead->update([
            'status' => LeadStatusEnum::PAID,
            'converted_at' => $lead->converted_at ?? now(),
        ]);

        Log::info('Lead marked as paid', [
            'lead_id' => $lead->id,
            'client_id' => $lead->client_id,
        ]);

        return $lead->fresh();
    }

    /**
     * Check for duplicate leads by phone (within last 30 days).
     */
    public function isDuplicate(string $phone, ?string $email = null): ?Lead
    {
        return Lead::where('phone', $phone)
            ->where('created_at', '>=', now()->subDays(30))
            ->active()
            ->first();
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadConversionService — конвертация лида в клиента + дело CRM.
// convert(): DB transaction — ищет клиента по телефону (или создаёт нового),
// создаёт Case, помечает лид как сконвертированный.
// findOrCreateClient() — дедупликация по телефону (основной идентификатор
// для иммиграционного бюро — паспорт может отсутствовать на этапе лида).
// createCase() — авто-создание дела с привязкой менеджера от лида.
// markAsPaid() — финальный шаг (после оплаты через POS/Invoice).
// isDuplicate() — проверка дубликатов за 30 дней по телефону.
// Файл: app/Services/Leads/LeadConversionService.php
// ---------------------------------------------------------------
