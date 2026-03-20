<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeKpi extends Model
{
    protected $table = 'employee_kpis';

    protected $fillable = [
        'user_id', 'period',
        'cases_completed', 'cases_opened', 'tasks_completed', 'tasks_overdue',
        'clients_served', 'avg_case_duration_days', 'client_satisfaction',
        'hours_worked', 'manager_notes',
    ];

    protected function casts(): array
    {
        return [
            'avg_case_duration_days' => 'decimal:1',
            'client_satisfaction' => 'decimal:2',
            'hours_worked' => 'decimal:1',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function scopeForUser($q, int $userId) { return $q->where('user_id', $userId); }
    public function scopeForPeriod($q, string $period) { return $q->where('period', $period); }
}
