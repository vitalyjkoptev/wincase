<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'type', 'case_id', 'client_id', 'lead_id',
        'assigned_to', 'created_by', 'priority', 'status',
        'due_date', 'completed_at', 'reminder_at',
    ];

    protected function casts(): array
    {
        return ['due_date' => 'date', 'completed_at' => 'datetime', 'reminder_at' => 'datetime'];
    }

    public function case(): BelongsTo { return $this->belongsTo(ClientCase::class, 'case_id'); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeInProgress($q) { return $q->where('status', 'in_progress'); }
    public function scopeCompleted($q) { return $q->where('status', 'completed'); }
    public function scopeOverdue($q) { return $q->where('due_date', '<', now())->where('status', '!=', 'completed'); }
    public function scopeAssignedTo($q, int $userId) { return $q->where('assigned_to', $userId); }

    public function complete(): void { $this->update(['status' => 'completed', 'completed_at' => now()]); }
}
