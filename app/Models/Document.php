<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'client_id', 'case_id', 'uploaded_by',
        'documentable_type', 'documentable_id',
        'type', 'name', 'original_filename', 'original_name', 'file_path', 'file_size', 'mime_type',
        'status', 'expires_at', 'verified_at', 'verified_by', 'rejection_reason', 'notes',
    ];

    protected function casts(): array
    {
        return ['expires_at' => 'date', 'verified_at' => 'datetime', 'file_size' => 'integer'];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }

    public function scopeExpiring($q, int $days = 30) { return $q->whereNotNull('expires_at')->where('expires_at', '<=', now()->addDays($days)); }
    public function scopeByType($q, string $type) { return $q->where('type', $type); }
    public function scopeByStatus($q, string $status) { return $q->where('status', $status); }
}
