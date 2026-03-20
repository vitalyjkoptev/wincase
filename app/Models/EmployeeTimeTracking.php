<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeTimeTracking extends Model
{
    protected $table = 'employee_time_tracking';

    protected $fillable = [
        'user_id', 'clock_in', 'clock_out',
        'hours_worked', 'type', 'notes', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'hours_worked' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function scopeForUser($q, int $userId) { return $q->where('user_id', $userId); }
    public function scopeToday($q) { return $q->whereDate('clock_in', now()->toDateString()); }
    public function scopeThisMonth($q) {
        return $q->whereMonth('clock_in', now()->month)->whereYear('clock_in', now()->year);
    }
    public function scopeOpen($q) { return $q->whereNull('clock_out'); }
}
