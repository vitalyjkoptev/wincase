<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{
    protected $table = 'vacancies';

    protected $fillable = [
        'employer_id', 'title', 'category', 'description', 'requirements',
        'salary_from', 'salary_to', 'currency', 'city',
        'employment_type', 'work_permit_provided', 'accommodation_provided', 'transport_provided',
        'status', 'views', 'source', 'source_url', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'salary_from' => 'integer',
            'salary_to' => 'integer',
            'views' => 'integer',
            'work_permit_provided' => 'boolean',
            'accommodation_provided' => 'boolean',
            'transport_provided' => 'boolean',
            'expires_at' => 'date',
        ];
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeDraft($q) { return $q->where('status', 'draft'); }
}
