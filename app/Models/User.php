<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'status', 'phone', 'department', 'avatar_url',
        'fcm_token', 'device_platform',
        'employee_id', 'position', 'salary', 'contract_type',
        'hire_date', 'contract_end_date', 'work_schedule',
        'emergency_contact', 'emergency_phone',
        'two_factor_enabled', 'two_factor_secret', 'two_factor_recovery_codes',
        'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'two_factor_secret', 'two_factor_recovery_codes',
        'salary', 'fcm_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // НЕ используем 'password' => 'hashed' — все сервисы хешируют через Hash::make() явно.
            // Cast 'hashed' вызывал проблемы: двойной хеш при updateOrCreate, путаница с dirty attributes.
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'hire_date' => 'date',
            'contract_end_date' => 'date',
            'work_schedule' => 'array',
            'salary' => 'decimal:2',
        ];
    }

    // ---- Relations ----

    public function assignedClients(): HasMany { return $this->hasMany(Client::class, 'assigned_to'); }
    public function assignedCases(): HasMany { return $this->hasMany(ClientCase::class, 'assigned_to'); }
    public function assignedTasks(): HasMany { return $this->hasMany(Task::class, 'assigned_to'); }
    public function createdTasks(): HasMany { return $this->hasMany(Task::class, 'created_by'); }
    public function timeTracking(): HasMany { return $this->hasMany(EmployeeTimeTracking::class); }
    public function kpis(): HasMany { return $this->hasMany(EmployeeKpi::class); }
    public function sentMessages(): HasMany { return $this->hasMany(StaffMessage::class, 'sender_id'); }
    public function receivedMessages(): HasMany { return $this->hasMany(StaffMessage::class, 'receiver_id'); }

    // ---- Scopes ----

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeByRole($q, string $role) { return $q->where('role', $role); }
    public function scopeBoss($q) { return $q->where('role', 'boss'); }
    public function scopeStaff($q) { return $q->where('role', 'staff'); }
    public function scopeEmployees($q) { return $q->whereIn('role', ['boss', 'staff']); }
    public function scopeByDepartment($q, string $dept) { return $q->where('department', $dept); }

    // ---- Role checks ----

    public function isBoss(): bool { return $this->role === 'boss'; }
    public function isStaff(): bool { return $this->role === 'staff'; }
    public function isClient(): bool { return $this->role === 'user'; }

    // ---- Employee helpers ----

    public function getTodayScheduleAttribute(): ?string
    {
        if (!$this->work_schedule) return null;
        $day = strtolower(now()->format('D'));
        return $this->work_schedule[$day] ?? null;
    }

    public function getActiveCasesCountAttribute(): int
    {
        return $this->assignedCases()->whereNotIn('status', ['completed', 'closed', 'cancelled'])->count();
    }

    public function getPendingTasksCountAttribute(): int
    {
        return $this->assignedTasks()->whereIn('status', ['pending', 'in_progress'])->count();
    }
}
