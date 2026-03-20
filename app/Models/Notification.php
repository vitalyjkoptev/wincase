<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'client_id', 'type', 'title', 'body',
        'data', 'channels', 'priority', 'status',
        'channel_results', 'sent_at', 'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'channels' => 'array',
        'channel_results' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
