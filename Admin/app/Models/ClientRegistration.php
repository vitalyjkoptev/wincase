<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRegistration extends Model
{
    protected $fillable = [
        'status',
        // Personal
        'first_name', 'last_name', 'middle_name', 'maiden_name',
        'date_of_birth', 'place_of_birth', 'gender', 'nationality', 'citizenship',
        'phone', 'phone2', 'messenger', 'email', 'password', 'preferred_language',
        // Documents
        'passport_number', 'passport_issue_date', 'passport_expiry_date',
        'passport_authority', 'passport_country',
        'pesel', 'nip', 'regon', 'national_id', 'driver_license', 'prev_passport', 'zus_number',
        // Address Poland
        'pl_street', 'pl_apartment', 'pl_postal_code', 'pl_city', 'pl_voivodeship',
        'zameldowanie', 'pl_living_since',
        // Home address
        'home_address', 'home_country', 'home_phone',
        // Correspondence
        'same_correspondence_address', 'corr_street', 'corr_city',
        // Immigration
        'immigration_status', 'stay_purpose', 'arrival_date', 'permit_from', 'permit_until',
        'permit_number', 'karta_pobytu', 'previous_application', 'prev_app_details',
        'entry_ban', 'criminal_record', 'service_needed', 'immigration_notes',
        // Family
        'marital_status', 'num_children', 'dependents_in_poland', 'family_members',
        // Education & Work
        'education_level', 'field_of_study', 'institution', 'graduation_year', 'education_country',
        'polish_level', 'other_languages',
        'employment_status', 'profession', 'employer_name', 'employer_nip', 'employer_address',
        'employment_since', 'salary', 'work_permit_type', 'work_permit_expiry',
        'health_insurance', 'bank_account_poland', 'tax_residency',
        // Agreements
        'agreed_terms', 'agreed_rodo', 'agreed_poa', 'agreed_data_sharing', 'agreed_marketing',
        'digital_signature', 'agreements_signed_at',
        // Meta
        'ip_address', 'user_agent',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'pl_living_since' => 'date',
        'arrival_date' => 'date',
        'permit_from' => 'date',
        'permit_until' => 'date',
        'employment_since' => 'date',
        'work_permit_expiry' => 'date',
        'agreements_signed_at' => 'datetime',
        'family_members' => 'array',
        'entry_ban' => 'boolean',
        'criminal_record' => 'boolean',
        'same_correspondence_address' => 'boolean',
        'agreed_terms' => 'boolean',
        'agreed_rodo' => 'boolean',
        'agreed_poa' => 'boolean',
        'agreed_data_sharing' => 'boolean',
        'agreed_marketing' => 'boolean',
        'bank_account_poland' => 'boolean',
        'salary' => 'decimal:2',
        'num_children' => 'integer',
        'dependents_in_poland' => 'integer',
        'graduation_year' => 'integer',
    ];

    protected $hidden = ['password'];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
