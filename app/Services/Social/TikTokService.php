<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class TikTokService extends AbstractSocialService
{
    protected string $baseUrl = 'https://open.tiktokapis.com/v2';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::TIKTOK;
    }

    public function publish(array $content): ?string
    {
        $token = config('services.tiktok.creator_token');

        // TikTok Content Posting API — video only
        if (empty($content['video_url'])) return null;

        // Step 1: Init upload
        $init = Http::withToken($token)->post("{$this->baseUrl}/post/publish/content/init/", [
            'post_info' => [
                'title' => $content['text'] ?? '',
                'privacy_level' => 'PUBLIC_TO_EVERYONE',
                'disable_duet' => false,
                'disable_stitch' => false,
                'disable_comment' => false,
            ],
            'source_info' => [
                'source' => 'PULL_FROM_URL',
                'video_url' => $content['video_url'],
            ],
        ]);

        return $init->json('data.publish_id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = config('services.tiktok.creator_token');
        $response = Http::withToken($token)->post("{$this->baseUrl}/video/query/", [
            'filters' => ['video_ids' => [$platformPostId]],
            'fields' => ['like_count', 'comment_count', 'share_count', 'view_count'],
        ]);

        $video = $response->json('data.videos.0') ?? [];

        return [
            'impressions' => $video['view_count'] ?? 0,
            'reach' => $video['view_count'] ?? 0,
            'likes' => $video['like_count'] ?? 0,
            'comments' => $video['comment_count'] ?? 0,
            'shares' => $video['share_count'] ?? 0,
            'video_views' => $video['view_count'] ?? 0,
        ];
    }

    public function fetchAccountStats(): array
    {
        $token = config('services.tiktok.creator_token');
        $response = Http::withToken($token)->get("{$this->baseUrl}/user/info/", [
            'fields' => ['follower_count', 'following_count', 'video_count'],
        ]);

        $data = $response->json('data.user') ?? [];

        return [
            'followers' => $data['follower_count'] ?? 0,
            'following' => $data['following_count'] ?? 0,
            'posts_count' => $data['video_count'] ?? 0,
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        return []; // TikTok API does not support DM access
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// TikTokService — Content Posting API v2.
// publish(): только видео через PULL_FROM_URL (TikTok требует video).
// fetchPostAnalytics(): views, likes, comments, shares.
// fetchInbox(): пустой — TikTok не даёт доступ к DM.
// Файл: app/Services/Social/TikTokService.php
// ---------------------------------------------------------------
