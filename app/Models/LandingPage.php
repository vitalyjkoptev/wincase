<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingPage extends Model
{
    protected $fillable = [
        'domain', 'slug', 'language', 'title', 'meta_description',
        'service_type', 'status', 'ad_platform', 'target_audience',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(LandingVariant::class);
    }

    public function getUrlAttribute(): string
    {
        return "https://{$this->domain}/{$this->slug}";
    }

    public function getTotalVisitsAttribute(): int
    {
        return $this->variants->sum('visits_count');
    }

    public function getConversionRateAttribute(): float
    {
        $visits = $this->total_visits;
        $conversions = $this->variants->sum('conversions_count');
        return $visits > 0 ? round(($conversions / $visits) * 100, 2) : 0;
    }
}
