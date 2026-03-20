<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'landing_variant_id', 'visitor_ip', 'utm_source', 'utm_medium',
        'utm_campaign', 'utm_content', 'referer', 'user_agent',
        'language', 'device_type',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(LandingVariant::class, 'landing_variant_id');
    }
}
