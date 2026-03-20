<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobParserSource extends Model
{
    protected $table = 'job_parser_sources';

    protected $fillable = [
        'name', 'domain', 'parse_url', 'parse_type', 'selectors',
        'category', 'is_active', 'last_parsed_at', 'total_parsed', 'last_error',
    ];

    protected function casts(): array
    {
        return [
            'selectors' => 'array',
            'is_active' => 'boolean',
            'last_parsed_at' => 'datetime',
        ];
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
