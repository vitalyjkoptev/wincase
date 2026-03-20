<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPost extends Model
{
    protected $table = 'social_posts';

    protected $fillable = [
        'social_account_id', 'platform', 'external_id',
        'content', 'media_urls', 'post_type',
        'status', 'scheduled_at', 'published_at',
        'likes', 'comments', 'shares', 'reach', 'impressions',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'media_urls' => 'array', 'scheduled_at' => 'datetime', 'published_at' => 'datetime',
            'likes' => 'integer', 'comments' => 'integer', 'shares' => 'integer',
            'reach' => 'integer', 'impressions' => 'integer',
        ];
    }

    public function account(): BelongsTo { return $this->belongsTo(SocialAccount::class, 'social_account_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function analytics(): HasMany { return $this->hasMany(SocialAnalytics::class); }

    public function scopePublished($q) { return $q->where('status', 'published'); }
    public function scopeScheduled($q) { return $q->where('status', 'scheduled'); }
    public function scopeByPlatform($q, string $p) { return $q->where('platform', $p); }
}
