<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Client overrides (optional — defaults from lead)
            'client_name' => 'nullable|string|max:200',
            'client_email' => 'nullable|email|max:100',
            'client_phone' => 'nullable|string|max:30',
            'client_passport' => 'nullable|string|max:50',
            'client_address' => 'nullable|string|max:500',

            // Case options
            'create_case' => 'nullable|boolean',
            'case_notes' => 'nullable|string|max:2000',
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// ConvertLeadRequest — валидация конвертации лида в клиента.
// Опциональные override-поля для клиента (имя, email, телефон, паспорт).
// create_case — создать ли дело (default: true в контроллере).
// case_notes — примечания к делу.
// Файл: app/Http/Requests/ConvertLeadRequest.php
// ---------------------------------------------------------------
