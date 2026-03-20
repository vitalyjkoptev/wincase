<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoNetworkSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'name',
        'language',
        'cms',
        'hosting',
        'domain_authority',
        'articles_total',
        'articles_with_backlink',
        'last_article_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'last_article_at' => 'datetime',
        ];
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    public function scopeNeedsArticle($query, int $daysSinceLastArticle = 30)
    {
        return $query->where('status', 'active')
            ->where(function ($q) use ($daysSinceLastArticle) {
                $q->whereNull('last_article_at')
                  ->orWhere('last_article_at', '<', now()->subDays($daysSinceLastArticle));
            });
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getBacklinkRatioAttribute(): float
    {
        return $this->articles_total > 0
            ? round(($this->articles_with_backlink / $this->articles_total) * 100, 1)
            : 0;
    }

    public function getDaysSinceLastArticleAttribute(): ?int
    {
        return $this->last_article_at
            ? (int) $this->last_article_at->diffInDays(now())
            : null;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель SeoNetworkSite — 8 сателлитных SEO-сайтов сети WinCase.
// Скоупы: active, byLanguage, needsArticle (не публиковали >N дней).
// Аксессоры: backlinkRatio (% статей с обратной ссылкой),
// daysSinceLastArticle — дней с последней публикации.
// Файл: app/Models/SeoNetworkSite.php
// ---------------------------------------------------------------
