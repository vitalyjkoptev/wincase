<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialAccount extends Model
{
    protected $fillable = [
        'platform', 'account_name', 'account_id', 'access_token', 'refresh_token',
        'token_expires_at', 'followers', 'following', 'posts_count', 'last_synced_at', 'status', 'meta',
    ];

    protected $hidden = ['access_token', 'refresh_token'];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
            'followers' => 'integer',
            'following' => 'integer',
            'posts_count' => 'integer',
            'meta' => 'array',
        ];
    }

    public function posts(): HasMany { return $this->hasMany(SocialPost::class); }

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeByPlatform($q, string $p) { return $q->where('platform', $p); }
}
