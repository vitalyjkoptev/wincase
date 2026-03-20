<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    protected $table = 'employers';

    protected $fillable = [
        'company_name', 'nip', 'regon', 'contact_name', 'position',
        'email', 'phone', 'city', 'industry', 'password',
        'logo', 'website', 'description', 'status', 'verified_at',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    public function scopeActive($q) { return $q->where('status', 'active'); }
}
