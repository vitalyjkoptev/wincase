<?php

namespace App\Http\Requests;

use App\Enums\LeadStatusEnum;
use App\Enums\PriorityEnum;
use App\Enums\ServiceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:100',
            'phone' => 'sometimes|string|max:30',
            'email' => 'nullable|email|max:100',
            'service_type' => ['sometimes', Rule::enum(ServiceTypeEnum::class)],
            'message' => 'nullable|string|max:2000',
            'status' => ['sometimes', Rule::enum(LeadStatusEnum::class)],
            'assigned_to' => 'nullable|integer|exists:users,id',
            'priority' => ['sometimes', Rule::enum(PriorityEnum::class)],
            'notes' => 'nullable|string|max:2000',
            'language' => 'sometimes|string|max:5',
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// UpdateLeadRequest — валидация обновления лида (только admin).
// Все поля 'sometimes' — обновляются только переданные.
// Статус, приоритет, service_type — валидация через PHP 8.4 enums.
// Файл: app/Http/Requests/UpdateLeadRequest.php
// ---------------------------------------------------------------
