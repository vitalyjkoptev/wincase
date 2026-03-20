<?php

namespace App\Services\Brand;

use App\Models\Review;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewsHubService
{
    // =====================================================
    // SYNC REVIEWS — all platforms (W10 — every 2 hours)
    // =====================================================

    public function syncAll(): array
    {
        $results = [];
        $results['google'] = $this->syncGoogle();
        $results['trustpilot'] = $this->syncTrustpilot();
        $results['facebook'] = $this->syncFacebook();
        $results['gowork'] = $this->syncGoWork();

        return $results;
    }

    // =====================================================
    // GOOGLE REVIEWS (Places API)
    // =====================================================

    protected function syncGoogle(): int
    {
        $placeId = config('services.google.place_id');
        $apiKey = config('services.google.api_key');

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'key' => $apiKey,
                'fields' => 'reviews,rating,user_ratings_total',
            ]);

            $reviews = $response->json('result.reviews') ?? [];
            $synced = 0;

            foreach ($reviews as $review) {
                Review::updateOrCreate(
                    ['platform' => 'google', 'platform_review_id' => md5($review['author_name'] . $review['time'])],
                    [
                        'author_name' => $review['author_name'] ?? 'Anonymous',
                        'rating' => $review['rating'] ?? 0,
                        'text' => $review['text'] ?? '',
                        'language' => $review['language'] ?? 'pl',
                        'published_at' => isset($review['time']) ? now()->setTimestamp($review['time']) : now(),
                    ]
                );
                $synced++;
            }

            return $synced;
        } catch (\Exception $e) {
            Log::error('Google Reviews sync failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    // =====================================================
    // TRUSTPILOT (Business API)
    // =====================================================

    protected function syncTrustpilot(): int
    {
        $businessId = config('services.trustpilot.business_id');
        $apiKey = config('services.trustpilot.api_key');

        try {
            $response = Http::withHeaders(['apikey' => $apiKey])
                ->get("https://api.trustpilot.com/v1/business-units/{$businessId}/reviews", [
                    'perPage' => 50,
                    'orderBy' => 'createdat.desc',
                ]);

            $reviews = $response->json('reviews') ?? [];
            $synced = 0;

            foreach ($reviews as $review) {
                Review::updateOrCreate(
                    ['platform' => 'trustpilot', 'platform_review_id' => $review['id']],
                    [
                        'author_name' => $review['consumer']['displayName'] ?? 'Anonymous',
                        'rating' => $review['stars'] ?? 0,
                        'text' => $review['text'] ?? '',
                        'language' => $review['language'] ?? 'en',
                        'published_at' => isset($review['createdAt']) ? now()->parse($review['createdAt']) : now(),
                        'reply_text' => $review['businessReply']['message'] ?? null,
                        'replied_at' => isset($review['businessReply']['createdAt'])
                            ? now()->parse($review['businessReply']['createdAt']) : null,
                    ]
                );
                $synced++;
            }

            return $synced;
        } catch (\Exception $e) {
            Log::error('Trustpilot sync failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    // =====================================================
    // FACEBOOK (Page Ratings via Graph API)
    // =====================================================

    protected function syncFacebook(): int
    {
        $pageId = config('services.facebook.page_id');
        $token = config('services.facebook.page_token');

        try {
            $response = Http::get("https://graph.facebook.com/v19.0/{$pageId}/ratings", [
                'access_token' => $token,
                'limit' => 50,
            ]);

            $reviews = $response->json('data') ?? [];
            $synced = 0;

            foreach ($reviews as $review) {
                Review::updateOrCreate(
                    ['platform' => 'facebook', 'platform_review_id' => $review['reviewer']['id'] ?? md5(json_encode($review))],
                    [
                        'author_name' => $review['reviewer']['name'] ?? 'Anonymous',
                        'rating' => $review['rating'] ?? ($review['recommendation_type'] === 'positive' ? 5 : 2),
                        'text' => $review['review_text'] ?? '',
                        'language' => 'pl',
                        'published_at' => isset($review['created_time']) ? now()->parse($review['created_time']) : now(),
                    ]
                );
                $synced++;
            }

            return $synced;
        } catch (\Exception $e) {
            Log::error('Facebook Reviews sync failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    // =====================================================
    // GOWORK.PL (Scraping via n8n — data pushed to API)
    // =====================================================

    protected function syncGoWork(): int
    {
        // GoWork doesn't have API — reviews scraped by n8n and pushed via internal endpoint
        // This method processes queued reviews from gowork_queue
        return 0;
    }

    // =====================================================
    // AGGREGATED STATS
    // =====================================================

    public function getAggregatedStats(): array
    {
        $platforms = ['google', 'trustpilot', 'facebook', 'gowork'];
        $stats = [];
        $totalReviews = 0;
        $totalRatingSum = 0;

        foreach ($platforms as $platform) {
            $reviews = Review::where('platform', $platform);
            $count = $reviews->count();
            $avgRating = $count > 0 ? round($reviews->avg('rating'), 1) : 0;

            $stats[$platform] = [
                'count' => $count,
                'avg_rating' => $avgRating,
                'recent' => Review::where('platform', $platform)
                    ->orderByDesc('published_at')
                    ->limit(3)
                    ->get(['author_name', 'rating', 'text', 'published_at'])
                    ->toArray(),
                'unreplied' => Review::where('platform', $platform)
                    ->whereNull('reply_text')
                    ->where('rating', '<=', 3)
                    ->count(),
            ];

            $totalReviews += $count;
            $totalRatingSum += $avgRating * $count;
        }

        return [
            'total_reviews' => $totalReviews,
            'overall_rating' => $totalReviews > 0 ? round($totalRatingSum / $totalReviews, 1) : 0,
            'platforms' => $stats,
            'needs_reply' => Review::whereNull('reply_text')->where('rating', '<=', 3)->count(),
            'last_sync' => Review::max('updated_at'),
        ];
    }

    // =====================================================
    // RECENT + FILTERED
    // =====================================================

    public function getRecentReviews(?string $platform = null, ?int $minRating = null, int $limit = 20): array
    {
        $query = Review::orderByDesc('published_at');

        if ($platform) $query->where('platform', $platform);
        if ($minRating !== null) $query->where('rating', '>=', $minRating);

        return $query->limit($limit)->get()->toArray();
    }

    /**
     * Flag review as needing response.
     */
    public function flagForResponse(int $id): Review
    {
        $review = Review::findOrFail($id);
        $review->update(['needs_response' => true]);
        return $review;
    }

    /**
     * Save reply to review (manual, then publish via platform).
     */
    public function saveReply(int $id, string $replyText): Review
    {
        $review = Review::findOrFail($id);
        $review->update([
            'reply_text' => $replyText,
            'replied_at' => now(),
            'needs_response' => false,
        ]);
        return $review;
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// ReviewsHubService — агрегация отзывов с 4 платформ.
// syncAll() — Google (Places API), Trustpilot (Business API),
// Facebook (Graph API ratings), GoWork (через n8n scraping).
// getAggregatedStats() — total reviews, avg rating, needs_reply, per platform.
// getRecentReviews() — с фильтрами platform + minRating.
// saveReply() — сохранение ответа на отзыв (публикация вручную).
// Запуск: W10 каждые 2 часа.
// Файл: app/Services/Brand/ReviewsHubService.php
// ---------------------------------------------------------------
