<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use App\Models\SocialAccount;
use App\Models\SocialAnalytics;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Log;

abstract class AbstractSocialService
{
    abstract protected function platform(): SocialPlatformEnum;

    /**
     * Publish content to this platform.
     * Returns platform-specific post ID.
     */
    abstract public function publish(array $content): ?string;

    /**
     * Fetch analytics for a specific post.
     */
    abstract public function fetchPostAnalytics(string $platformPostId): array;

    /**
     * Fetch account-level stats (followers, etc.).
     */
    abstract public function fetchAccountStats(): array;

    /**
     * Fetch incoming messages / DMs / comments.
     */
    abstract public function fetchInbox(int $limit = 20): array;

    // =====================================================
    // SHARED: Save post record to DB
    // =====================================================

    public function savePost(array $content, ?string $platformPostId): SocialPost
    {
        $account = SocialAccount::where('platform', $this->platform())->first();

        return SocialPost::create([
            'social_account_id' => $account?->id,
            'platform' => $this->platform()->value,
            'platform_post_id' => $platformPostId,
            'type' => $content['type'] ?? 'post',
            'text' => $content['text'] ?? null,
            'media_url' => $content['media_url'] ?? null,
            'link' => $content['link'] ?? null,
            'scheduled_at' => $content['scheduled_at'] ?? null,
            'published_at' => $platformPostId ? now() : null,
            'status' => $platformPostId ? 'published' : 'failed',
        ]);
    }

    // =====================================================
    // SHARED: Save analytics to DB
    // =====================================================

    public function saveAnalytics(SocialPost $post, array $metrics): SocialAnalytics
    {
        return SocialAnalytics::updateOrCreate(
            [
                'social_post_id' => $post->id,
                'platform' => $this->platform()->value,
            ],
            [
                'reach' => $metrics['reach'] ?? 0,
                'impressions' => $metrics['impressions'] ?? 0,
                'likes' => $metrics['likes'] ?? 0,
                'comments' => $metrics['comments'] ?? 0,
                'shares' => $metrics['shares'] ?? 0,
                'saves' => $metrics['saves'] ?? 0,
                'clicks' => $metrics['clicks'] ?? 0,
                'engagement_rate' => $metrics['engagement_rate'] ?? 0,
                'video_views' => $metrics['video_views'] ?? 0,
            ]
        );
    }

    // =====================================================
    // SHARED: Sync account stats
    // =====================================================

    public function syncAccountStats(): ?SocialAccount
    {
        $account = SocialAccount::where('platform', $this->platform())->first();
        if (!$account) return null;

        try {
            $stats = $this->fetchAccountStats();
            $account->update([
                'followers' => $stats['followers'] ?? $account->followers,
                'following' => $stats['following'] ?? $account->following,
                'posts_count' => $stats['posts_count'] ?? $account->posts_count,
                'last_synced_at' => now(),
            ]);
            return $account->fresh();
        } catch (\Exception $e) {
            Log::error("Social sync failed: {$this->platform()->value}", ['error' => $e->getMessage()]);
            return null;
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// AbstractSocialService — базовый класс для 8 социальных платформ.
// Абстрактные методы: publish(), fetchPostAnalytics(), fetchAccountStats(), fetchInbox().
// Общие методы: savePost() → social_posts, saveAnalytics() → social_analytics,
// syncAccountStats() → обновление followers/following в social_accounts.
// Файл: app/Services/Social/AbstractSocialService.php
// ---------------------------------------------------------------
