<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffMessage extends Model
{
    protected $fillable = [
        'sender_id', 'receiver_id', 'body', 'type',
        'is_encrypted', 'read_at', 'case_id', 'client_id',
    ];

    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'receiver_id'); }
    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }

    public function scopeUnread($q) { return $q->whereNull('read_at'); }
    public function scopeBossChatFor($q, int $userId) {
        return $q->where('type', 'boss_chat')
            ->where(fn ($sub) => $sub->where('sender_id', $userId)->orWhere('receiver_id', $userId));
    }
    public function scopeConversation($q, int $userId1, int $userId2) {
        return $q->where(fn ($sub) =>
            $sub->where(fn ($s) => $s->where('sender_id', $userId1)->where('receiver_id', $userId2))
                ->orWhere(fn ($s) => $s->where('sender_id', $userId2)->where('receiver_id', $userId1))
        );
    }
}
