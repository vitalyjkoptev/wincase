<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    protected $fillable = [
        'report_type', 'format', 'frequency', 'recipients', 'parameters',
        'active', 'last_run_at', 'next_run_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'parameters' => 'array',
        'active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];
}
