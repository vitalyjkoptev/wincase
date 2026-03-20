<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hearing extends Model
{
    protected $fillable = [
        'case_id', 'client_id', 'hearing_date', 'hearing_time', 'office_name', 'room_number',
        'type', 'status', 'interpreter_needed', 'interpreter_language',
        'result', 'notes', 'reminder_sent', 'created_by',
    ];

    protected function casts(): array
    {
        return ['hearing_date' => 'date', 'interpreter_needed' => 'boolean', 'reminder_sent' => 'boolean'];
    }

    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopeUpcoming($q) { return $q->where('hearing_date', '>=', now())->where('status', 'scheduled')->orderBy('hearing_date'); }
    public function scopeByStatus($q, string $s) { return $q->where('status', $s); }
}
