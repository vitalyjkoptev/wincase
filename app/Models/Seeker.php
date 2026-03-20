<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seeker extends Model
{
    protected $table = 'seekers';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'nationality',
        'date_of_birth', 'job_category', 'experience', 'polish_level',
        'preferred_city', 'work_permit', 'password', 'avatar', 'cv_file',
        'skills', 'bio', 'status',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function scopeActive($q) { return $q->where('status', 'active'); }
}
