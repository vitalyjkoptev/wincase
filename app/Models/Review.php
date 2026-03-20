<?php

namespace App\Models;

use App\Enums\ReviewPlatformEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'author_name',
        'rating',
        'text',
        'reply',
        'replied_at',
        'published_at',
        'language',
        'platform_review_id',
    ];

    protected function casts(): array
    {
        return [
            'platform' => ReviewPlatformEnum::class,
            'rating' => 'integer',
            'replied_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeByPlatform($query, ReviewPlatformEnum $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('reply');
    }

    public function scopeNegative($query)
    {
        return $query->where('rating', '<=', 2);
    }

    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('published_at', '>=', now()->subDays($days));
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getIsAnsweredAttribute(): bool
    {
        return $this->reply !== null;
    }

    public function getSentimentAttribute(): string
    {
        if ($this->rating === null) {
            return 'neutral';
        }

        return match (true) {
            $this->rating >= 4 => 'positive',
            $this->rating >= 3 => 'neutral',
            default => 'negative',
        };
    }

    // =====================================================
    // METHODS
    // =====================================================

    public function addReply(string $replyText): void
    {
        $this->update([
            'reply' => $replyText,
            'replied_at' => now(),
        ]);
    }

    // =====================================================
    // STATIC AGGREGATIONS
    // =====================================================

    public static function averageByPlatform(): array
    {
        return static::selectRaw('
                platform,
                COUNT(*) as total_reviews,
                AVG(rating) as avg_rating,
                SUM(CASE WHEN reply IS NOT NULL THEN 1 ELSE 0 END) as replied_count,
                SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as positive_count,
                SUM(CASE WHEN rating <= 2 THEN 1 ELSE 0 END) as negative_count
            ')
            ->groupBy('platform')
            ->get()
            ->toArray();
    }

    public static function overallRating(): float
    {
        return round((float) static::avg('rating'), 1);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Модель Review — отзывы с 6 платформ (Google, Trustpilot, FB, GoWork,
// Clutch, ProvenExpert). PHP 8.4 backed enum ReviewPlatformEnum.
// Скоупы: byPlatform, unanswered, negative (<=2), positive (>=4), recent.
// Аксессоры: isAnswered, sentiment (positive/neutral/negative).
// Метод addReply() — ответ на отзыв с timestamp.
// Статические: averageByPlatform() — сводка, overallRating() — общий рейтинг.
// Файл: app/Models/Review.php
// ---------------------------------------------------------------
