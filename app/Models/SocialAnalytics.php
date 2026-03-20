<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAnalytics extends Model
{
    protected $table = 'social_analytics';

    protected $fillable = [
        'social_post_id', 'date', 'likes', 'comments', 'shares',
        'reach', 'impressions', 'clicks', 'saves', 'engagement_rate',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'likes' => 'integer',
            'comments' => 'integer',
            'shares' => 'integer',
            'reach' => 'integer',
            'impressions' => 'integer',
            'clicks' => 'integer',
            'saves' => 'integer',
            'engagement_rate' => 'decimal:4',
        ];
    }

    public function post(): BelongsTo { return $this->belongsTo(SocialPost::class, 'social_post_id'); }
}
