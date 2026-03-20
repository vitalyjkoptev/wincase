<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use App\Models\SocialAccount;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Log;

class SocialOrchestrationService
{
    protected array $services = [];

    public function __construct(
        protected FacebookService $facebook,
        protected InstagramService $instagram,
        protected ThreadsService $threads,
        protected TikTokService $tiktok,
        protected YouTubeService $youtube,
        protected TelegramService $telegram,
        protected PinterestService $pinterest,
        protected LinkedInService $linkedin,
    ) {
        $this->services = [
            SocialPlatformEnum::FACEBOOK->value => $this->facebook,
            SocialPlatformEnum::INSTAGRAM->value => $this->instagram,
            SocialPlatformEnum::THREADS->value => $this->threads,
            SocialPlatformEnum::TIKTOK->value => $this->tiktok,
            SocialPlatformEnum::YOUTUBE->value => $this->youtube,
            SocialPlatformEnum::TELEGRAM->value => $this->telegram,
            SocialPlatformEnum::PINTEREST->value => $this->pinterest,
            SocialPlatformEnum::LINKEDIN->value => $this->linkedin,
        ];
    }

    // =====================================================
    // UNIFIED POSTING — Publish to multiple platforms at once
    // =====================================================

    /**
     * Publish content to selected platforms simultaneously.
     * Called by n8n W13 or admin panel.
     */
    public function publishToMultiple(array $content, array $platformKeys): array
    {
        $results = [];

        foreach ($platformKeys as $key) {
            $service = $this->services[$key] ?? null;
            if (!$service) {
                $results[$key] = ['success' => false, 'error' => 'Unknown platform'];
                continue;
            }

            try {
                $postId = $service->publish($content);
                $post = $service->savePost($content, $postId);

                $results[$key] = [
                    'success' => $postId !== null,
                    'platform_post_id' => $postId,
                    'local_post_id' => $post->id,
                ];
            } catch (\Exception $e) {
                Log::error("Social publish failed: {$key}", ['error' => $e->getMessage()]);
                $results[$key] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    // =====================================================
    // UNIFIED INBOX — Messages from all platforms
    // =====================================================

    /**
     * Fetch messages from all platforms that support inbox.
     * Platforms with inbox: Facebook, Instagram, YouTube (comments), Telegram.
     */
    public function getUnifiedInbox(int $limitPerPlatform = 10): array
    {
        $inboxPlatforms = [
            SocialPlatformEnum::FACEBOOK,
            SocialPlatformEnum::INSTAGRAM,
            SocialPlatformEnum::YOUTUBE,
            SocialPlatformEnum::TELEGRAM,
        ];

        $allMessages = [];

        foreach ($inboxPlatforms as $platform) {
            $service = $this->services[$platform->value] ?? null;
            if (!$service) continue;

            try {
                $messages = $service->fetchInbox($limitPerPlatform);
                $allMessages = array_merge($allMessages, $messages);
            } catch (\Exception $e) {
                Log::warning("Inbox fetch failed: {$platform->value}", ['error' => $e->getMessage()]);
            }
        }

        return [
            'messages' => $allMessages,
            'total' => count($allMessages),
        ];
    }

    // =====================================================
    // ACCOUNTS OVERVIEW — Followers, stats per platform
    // =====================================================

    public function getAccountsOverview(): array
    {
        $accounts = SocialAccount::orderBy('platform')->get();

        $totalFollowers = $accounts->sum('followers');

        return [
            'accounts' => $accounts->map(fn ($acc) => [
                'platform' => $acc->platform,
                'label' => SocialPlatformEnum::tryFrom($acc->platform)?->label() ?? $acc->platform,
                'handle' => SocialPlatformEnum::tryFrom($acc->platform)?->handle() ?? '',
                'followers' => $acc->followers,
                'following' => $acc->following,
                'posts_count' => $acc->posts_count,
                'last_synced_at' => $acc->last_synced_at?->toIso8601String(),
            ])->toArray(),
            'total_followers' => $totalFollowers,
            'platform_count' => $accounts->count(),
        ];
    }

    // =====================================================
    // SYNC ALL ACCOUNT STATS
    // =====================================================

    /**
     * Sync followers/stats for all 8 platforms.
     * Called by n8n W11 daily.
     */
    public function syncAllAccounts(): array
    {
        $results = [];

        foreach ($this->services as $key => $service) {
            try {
                $account = $service->syncAccountStats();
                $results[$key] = [
                    'success' => $account !== null,
                    'followers' => $account?->followers ?? 0,
                ];
            } catch (\Exception $e) {
                $results[$key] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    // =====================================================
    // POST ANALYTICS
    // =====================================================

    public function getPostAnalytics(int $postId): array
    {
        $post = SocialPost::findOrFail($postId);
        $service = $this->services[$post->platform] ?? null;

        if (!$service || !$post->platform_post_id) {
            return ['success' => false, 'message' => 'Cannot fetch analytics'];
        }

        $metrics = $service->fetchPostAnalytics($post->platform_post_id);
        $service->saveAnalytics($post, $metrics);

        return [
            'success' => true,
            'post' => $post->toArray(),
            'metrics' => $metrics,
        ];
    }

    // =====================================================
    // RECENT POSTS
    // =====================================================

    public function getRecentPosts(?string $platform = null, int $limit = 20): array
    {
        $query = SocialPost::with('analytics')
            ->orderByDesc('published_at');

        if ($platform) {
            $query->where('platform', $platform);
        }

        return $query->limit($limit)->get()->toArray();
    }

    // =====================================================
    // CONTENT CALENDAR
    // =====================================================

    public function getContentCalendar(string $dateFrom, string $dateTo): array
    {
        return SocialPost::whereBetween('scheduled_at', [$dateFrom, $dateTo])
            ->orWhereBetween('published_at', [$dateFrom, $dateTo])
            ->orderBy('scheduled_at')
            ->orderBy('published_at')
            ->get()
            ->groupBy(fn ($post) => ($post->scheduled_at ?? $post->published_at)?->toDateString())
            ->map(fn ($dayPosts, $date) => [
                'date' => $date,
                'posts' => $dayPosts->toArray(),
            ])
            ->values()
            ->toArray();
    }

    // =====================================================
    // DASHBOARD WIDGET
    // =====================================================

    public function getDashboardStats(): array
    {
        $totalFollowers = SocialAccount::sum('followers');

        $last7Posts = SocialPost::where('published_at', '>=', now()->subDays(7))
            ->count();

        $topPost = SocialPost::with('analytics')
            ->whereHas('analytics')
            ->where('published_at', '>=', now()->subDays(30))
            ->orderByDesc(function ($query) {
                $query->select('engagement_rate')
                    ->from('social_analytics')
                    ->whereColumn('social_analytics.social_post_id', 'social_posts.id')
                    ->limit(1);
            })
            ->first();

        return [
            'total_followers' => $totalFollowers,
            'posts_last_7d' => $last7Posts,
            'platforms_active' => SocialAccount::where('last_synced_at', '>=', now()->subDay())->count(),
            'top_post' => $topPost?->toArray(),
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// SocialOrchestrationService — единый сервис 8 социальных платформ.
// publishToMultiple() — постинг на выбранные платформы одновременно.
// getUnifiedInbox() — сообщения из FB, IG, YouTube, Telegram.
// getAccountsOverview() — followers/stats всех аккаунтов.
// syncAllAccounts() — синхронизация followers (W11 daily).
// getPostAnalytics() — метрики поста через API платформы.
// getRecentPosts() — последние посты (?platform= фильтр).
// getContentCalendar() — контент-план по датам.
// getDashboardStats() — виджет: total followers, posts 7d, top post.
// Файл: app/Services/Social/SocialOrchestrationService.php
// ---------------------------------------------------------------
