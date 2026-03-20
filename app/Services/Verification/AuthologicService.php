<?php

namespace App\Services\Verification;

use App\Models\Client;
use App\Models\ClientVerification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthologicService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $strategy;
    protected string $returnUrl;
    protected string $callbackUrl;

    public function __construct()
    {
        $cfg = config('services.authologic');
        $this->apiUrl = $cfg['api_url'];
        $this->apiKey = $cfg['api_key'] ?? '';
        $this->apiSecret = $cfg['api_secret'] ?? '';
        $this->strategy = $cfg['strategy'] ?? 'public:sandbox';
        $this->returnUrl = url($cfg['return_url'] ?? '/verification/complete');
        $this->callbackUrl = url($cfg['callback_url'] ?? '/api/v1/verification/callback');
    }

    /**
     * Create verification conversation for a client.
     * Returns: ['conversation_id' => '...', 'url' => '...', 'status' => '...']
     */
    public function startVerification(Client $client, string $type = 'identity', ?int $initiatedBy = null): array
    {
        // Required identity fields depending on verification type
        $requireFields = match ($type) {
            'identity' => ['PERSON_NAME_FIRSTNAME', 'PERSON_NAME_LASTNAME', 'PERSON_ID_NUMBER_PESEL'],
            'identity_full' => [
                'PERSON_NAME_FIRSTNAME', 'PERSON_NAME_LASTNAME',
                'PERSON_ID_NUMBER_PESEL', 'PERSON_DOB',
                'PERSON_NATIONALITY', 'PERSON_ID_DOCUMENT_NUMBER',
            ],
            'address' => ['PERSON_NAME_FIRSTNAME', 'PERSON_NAME_LASTNAME', 'PERSON_ADDRESS'],
            default => ['PERSON_NAME_FIRSTNAME', 'PERSON_NAME_LASTNAME'],
        };

        $payload = [
            'returnUrl' => $this->returnUrl . '?cid={conversationId}&client_id=' . $client->id,
            'callbackUrl' => $this->callbackUrl . '?cid={conversationId}',
            'strategy' => $this->strategy,
            'userKey' => 'client-' . $client->id,
            'query' => [
                'identity' => [
                    'requireOneOf' => [$requireFields],
                ],
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->withHeaders([
                    'Accept' => 'application/vnd.authologic.v1.1+json',
                    'Content-Type' => 'application/vnd.authologic.v1.1+json',
                ])
                ->post($this->apiUrl . '/conversations', $payload);

            if (!$response->successful()) {
                Log::error('Authologic: Failed to create conversation', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'client_id' => $client->id,
                ]);

                return [
                    'success' => false,
                    'error' => 'Failed to create verification session: ' . $response->body(),
                ];
            }

            $data = $response->json();

            // Save to client_verifications
            $verification = ClientVerification::create([
                'client_id' => $client->id,
                'type' => $type,
                'status' => 'pending',
                'verified_by' => $initiatedBy,
                'notes' => 'Authologic conversation: ' . ($data['id'] ?? 'unknown'),
                'result_data' => [
                    'provider' => 'authologic',
                    'conversation_id' => $data['id'] ?? null,
                    'status' => $data['status'] ?? 'CREATED',
                    'url' => $data['url'] ?? null,
                    'strategy' => $this->strategy,
                    'created_at' => now()->toIso8601String(),
                ],
            ]);

            return [
                'success' => true,
                'verification_id' => $verification->id,
                'conversation_id' => $data['id'] ?? null,
                'url' => $data['url'] ?? null,
                'status' => $data['status'] ?? 'CREATED',
            ];

        } catch (\Throwable $e) {
            Log::error('Authologic: Exception', [
                'message' => $e->getMessage(),
                'client_id' => $client->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check conversation status / get verification results.
     */
    public function getConversation(string $conversationId): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->withHeaders([
                    'Accept' => 'application/vnd.authologic.v1.1+json',
                ])
                ->get($this->apiUrl . '/conversations/' . $conversationId);

            if (!$response->successful()) {
                return ['success' => false, 'error' => 'API error: ' . $response->status()];
            }

            return ['success' => true, 'data' => $response->json()];

        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Handle callback from Authologic — update verification record.
     */
    public function handleCallback(string $conversationId): array
    {
        $result = $this->getConversation($conversationId);

        if (!$result['success']) {
            return $result;
        }

        $data = $result['data'];
        $status = $data['status'] ?? 'UNKNOWN';

        // Find verification by conversation_id in result_data
        $verification = ClientVerification::where('result_data->conversation_id', $conversationId)->first();

        if (!$verification) {
            Log::warning('Authologic callback: verification not found', ['conversation_id' => $conversationId]);
            return ['success' => false, 'error' => 'Verification not found'];
        }

        // Map Authologic status to our status
        $ourStatus = match ($status) {
            'FINISHED' => 'verified',
            'FAILED', 'CANCELED' => 'rejected',
            'IN_PROGRESS', 'CREATED' => 'pending',
            default => 'pending',
        };

        // Extract identity data
        $identityData = $data['result']['identity'] ?? [];
        $personData = [];

        foreach ($identityData as $field) {
            if (isset($field['name'], $field['value'])) {
                $personData[$field['name']] = $field['value'];
            }
        }

        $verification->update([
            'status' => $ourStatus,
            'verified_at' => $ourStatus === 'verified' ? now() : null,
            'result_data' => array_merge($verification->result_data ?? [], [
                'authologic_status' => $status,
                'person_data' => $personData,
                'finished_at' => now()->toIso8601String(),
                'raw_response' => $data,
            ]),
        ]);

        // If verified — update client fields from identity data
        if ($ourStatus === 'verified' && $verification->client) {
            $client = $verification->client;
            $updates = [];

            if (!empty($personData['PERSON_NAME_FIRSTNAME']) && empty($client->first_name)) {
                $updates['first_name'] = $personData['PERSON_NAME_FIRSTNAME'];
            }
            if (!empty($personData['PERSON_NAME_LASTNAME']) && empty($client->last_name)) {
                $updates['last_name'] = $personData['PERSON_NAME_LASTNAME'];
            }
            if (!empty($personData['PERSON_ID_NUMBER_PESEL']) && empty($client->pesel)) {
                $updates['pesel'] = $personData['PERSON_ID_NUMBER_PESEL'];
            }
            if (!empty($personData['PERSON_DOB']) && empty($client->date_of_birth)) {
                $updates['date_of_birth'] = $personData['PERSON_DOB'];
            }
            if (!empty($personData['PERSON_NATIONALITY']) && empty($client->nationality)) {
                $updates['nationality'] = $personData['PERSON_NATIONALITY'];
            }

            if (!empty($updates)) {
                $client->update($updates);
            }
        }

        return [
            'success' => true,
            'status' => $ourStatus,
            'verification_id' => $verification->id,
            'person_data' => $personData,
        ];
    }

    /**
     * Check if Authologic is properly configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->apiSecret);
    }

    /**
     * Get available verification strategies.
     */
    public function getStrategies(): array
    {
        return [
            ['id' => 'public:sandbox', 'name' => 'Sandbox (testing)', 'description' => 'Test mode — no real verification'],
            ['id' => 'public:pl:mobywatel', 'name' => 'mObywatel', 'description' => 'Polish digital ID via mObywatel app'],
            ['id' => 'public:pl:profil_zaufany', 'name' => 'Profil Zaufany', 'description' => 'Polish Trusted Profile (ePUAP)'],
            ['id' => 'public:pl:bank', 'name' => 'Bank Verification', 'description' => 'Verify via Polish bank login'],
            ['id' => 'public:id_document', 'name' => 'ID Document Scan', 'description' => 'Passport/ID card photo verification'],
        ];
    }
}
