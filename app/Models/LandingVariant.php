<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingVariant extends Model
{
    protected $fillable = [
        'landing_page_id', 'variant_name', 'headline', 'cta_text',
        'cta_color', 'custom_css', 'traffic_pct', 'is_active',
        'visits_count', 'conversions_count',
    ];

    protected function casts(): array
    {
        return [
            'traffic_pct' => 'integer',
            'is_active' => 'boolean',
            'visits_count' => 'integer',
            'conversions_count' => 'integer',
        ];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(LandingVisit::class);
    }

    public function getConversionRateAttribute(): float
    {
        return $this->visits_count > 0
            ? round(($this->conversions_count / $this->visits_count) * 100, 2)
            : 0;
    }
}
