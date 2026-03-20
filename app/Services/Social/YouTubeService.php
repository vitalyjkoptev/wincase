<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class YouTubeService extends AbstractSocialService
{
    protected string $baseUrl = 'https://www.googleapis.com/youtube/v3';
    protected string $analyticsUrl = 'https://youtubeanalytics.googleapis.com/v2';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::YOUTUBE;
    }

    public function publish(array $content): ?string
    {
        // YouTube upload requires resumable upload protocol
        // In practice, this is handled by n8n workflow or YouTube Studio
        // Here we create metadata for scheduled upload
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->post("{$this->baseUrl}/videos", [
            'snippet' => [
                'title' => $content['title'] ?? $content['text'] ?? '',
                'description' => $content['text'] ?? '',
                'tags' => $content['tags'] ?? ['wincase', 'immigration', 'poland'],
                'categoryId' => '22', // People & Blogs
            ],
            'status' => [
                'privacyStatus' => 'public',
                'selfDeclaredMadeForKids' => false,
            ],
        ], ['part' => 'snippet,status']);

        return $response->json('id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get("{$this->baseUrl}/videos", [
            'part' => 'statistics',
            'id' => $platformPostId,
        ]);

        $stats = $response->json('items.0.statistics') ?? [];

        return [
            'impressions' => 0,
            'reach' => (int) ($stats['viewCount'] ?? 0),
            'likes' => (int) ($stats['likeCount'] ?? 0),
            'comments' => (int) ($stats['commentCount'] ?? 0),
            'shares' => 0,
            'video_views' => (int) ($stats['viewCount'] ?? 0),
        ];
    }

    public function fetchAccountStats(): array
    {
        $token = $this->getAccessToken();
        $channelId = config('services.youtube.channel_id');

        $response = Http::withToken($token)->get("{$this->baseUrl}/channels", [
            'part' => 'statistics',
            'id' => $channelId,
        ]);

        $stats = $response->json('items.0.statistics') ?? [];

        return [
            'followers' => (int) ($stats['subscriberCount'] ?? 0),
            'following' => 0,
            'posts_count' => (int) ($stats['videoCount'] ?? 0),
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        // YouTube comments as inbox
        $token = $this->getAccessToken();
        $channelId = config('services.youtube.channel_id');

        $response = Http::withToken($token)->get("{$this->baseUrl}/commentThreads", [
            'part' => 'snippet',
            'allThreadsRelatedToChannelId' => $channelId,
            'maxResults' => $limit,
            'order' => 'time',
        ]);

        return array_map(fn ($item) => [
            'id' => $item['id'],
            'platform' => 'youtube',
            'messages' => [[
                'from' => $item['snippet']['topLevelComment']['snippet']['authorDisplayName'] ?? '',
                'message' => $item['snippet']['topLevelComment']['snippet']['textDisplay'] ?? '',
                'created_time' => $item['snippet']['topLevelComment']['snippet']['publishedAt'] ?? '',
            ]],
        ], $response->json('items') ?? []);
    }

    protected function getAccessToken(): string
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => config('services.youtube.refresh_token'),
            'grant_type' => 'refresh_token',
        ]);

        return $response->json('access_token')
            ?? throw new \RuntimeException('Failed to refresh YouTube token');
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// YouTubeService — YouTube Data API v3.
// publish(): создание metadata для видео (upload через n8n/Studio).
// fetchPostAnalytics(): viewCount, likeCount, commentCount.
// fetchAccountStats(): subscriberCount, videoCount.
// fetchInbox(): комментарии к видео канала (как Unified Inbox).
// Файл: app/Services/Social/YouTubeService.php
// ---------------------------------------------------------------
