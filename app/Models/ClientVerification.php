<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientVerification extends Model
{
    protected $fillable = [
        'client_id', 'type', 'status', 'document_id',
        'verified_by', 'verified_at', 'expires_at', 'notes', 'result_data',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'expires_at' => 'date', 'result_data' => 'array'];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function document(): BelongsTo { return $this->belongsTo(Document::class); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }

    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeVerified($q) { return $q->where('status', 'verified'); }
}
