<?php

namespace App\Http\Requests;

use App\Enums\LeadSourceEnum;
use App\Enums\LeadStatusEnum;
use App\Enums\PriorityEnum;
use App\Enums\ServiceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Contact (required)
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:100',

            // Service
            'service_type' => ['nullable', Rule::enum(ServiceTypeEnum::class)],
            'message' => 'nullable|string|max:2000',

            // Source & Tracking
            'source' => ['required', Rule::enum(LeadSourceEnum::class)],
            'utm_source' => 'nullable|string|max:200',
            'utm_medium' => 'nullable|string|max:200',
            'utm_campaign' => 'nullable|string|max:200',
            'utm_term' => 'nullable|string|max:200',
            'utm_content' => 'nullable|string|max:200',
            'gclid' => 'nullable|string|max:200',
            'fbclid' => 'nullable|string|max:200',
            'ttclid' => 'nullable|string|max:200',
            'landing_page' => 'nullable|url|max:500',

            // Visitor
            'language' => 'nullable|string|max:5',
            'device' => 'nullable|string|in:mobile,desktop,tablet',
            'country' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:100',

            // Assignment (admin only)
            'assigned_to' => 'nullable|integer|exists:users,id',
            'priority' => ['nullable', Rule::enum(PriorityEnum::class)],
            'notes' => 'nullable|string|max:2000',

            // GDPR
            'gdpr_consent' => 'nullable|boolean',

            // Honeypot (anti-spam)
            'website' => 'nullable|max:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'phone.required' => 'Phone number is required.',
            'source.required' => 'Lead source must be specified.',
            'website.max' => 'Spam detected.',
        ];
    }

    /**
     * Get IP address and inject into validated data.
     */
    public function passedValidation(): void
    {
        $this->merge([
            'ip_address' => $this->ip(),
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// StoreLeadRequest — валидация создания лида.
// Обязательные: name, phone, source. Опционально: email, service_type,
// UTM поля (5), click IDs (gclid/fbclid/ttclid), landing_page,
// language, device, country/city, assigned_to, priority, notes, GDPR.
// Honeypot-поле "website" — если заполнено, это бот (max:0).
// IP-адрес инжектируется автоматически.
// Файл: app/Http/Requests/StoreLeadRequest.php
// ---------------------------------------------------------------
