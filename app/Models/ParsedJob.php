<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParsedJob extends Model
{
    protected $table = 'parsed_jobs';
    public $timestamps = false;

    protected $fillable = [
        'source', 'source_url', 'title', 'company', 'city', 'salary',
        'description', 'parsed_data', 'status', 'vacancy_id', 'google_sheet_row',
    ];

    protected function casts(): array
    {
        return [
            'parsed_data' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
