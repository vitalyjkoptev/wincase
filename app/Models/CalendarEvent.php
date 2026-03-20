<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    protected $table = 'calendar_events';

    protected $fillable = [
        'user_id', 'client_id', 'case_id', 'type', 'title',
        'description', 'start_at', 'end_at', 'all_day',
        'location', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'all_day' => 'boolean',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }
}
