<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'client_id', 'case_id', 'user_id', 'channel', 'direction',
        'subject', 'body', 'status', 'type', 'sent_at', 'read_at',
    ];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime', 'read_at' => 'datetime'];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function scopeUnread($q) { return $q->whereNull('read_at'); }
    public function scopeByChannel($q, string $ch) { return $q->where('channel', $ch); }
    public function scopeInbound($q) { return $q->where('direction', 'inbound'); }
}
