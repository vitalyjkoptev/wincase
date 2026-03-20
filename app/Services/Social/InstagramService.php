<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class InstagramService extends AbstractSocialService
{
    protected string $baseUrl = 'https://graph.facebook.com/v19.0';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::INSTAGRAM;
    }

    public function publish(array $content): ?string
    {
        $igUserId = config('services.instagram.user_id');
        $token = config('services.instagram.token');

        // Step 1: Create media container
        $params = ['access_token' => $token];

        if (!empty($content['media_url'])) {
            $params['image_url'] = $content['media_url'];
            $params['caption'] = $content['text'] ?? '';
        } elseif (!empty($content['video_url'])) {
            $params['video_url'] = $content['video_url'];
            $params['caption'] = $content['text'] ?? '';
            $params['media_type'] = 'REELS';
        } else {
            return null;
        }

        $container = Http::post("{$this->baseUrl}/{$igUserId}/media", $params);
        $containerId = $container->json('id');

        if (!$containerId) return null;

        // Step 2: Publish container
        $publish = Http::post("{$this->baseUrl}/{$igUserId}/media_publish", [
            'access_token' => $token,
            'creation_id' => $containerId,
        ]);

        return $publish->json('id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = config('services.instagram.token');
        $response = Http::get("{$this->baseUrl}/{$platformPostId}/insights", [
            'access_token' => $token,
            'metric' => 'impressions,reach,likes,comments,saved,shares',
        ]);

        $data = collect($response->json('data') ?? []);
        $getValue = fn ($name) => $data->firstWhere('name', $name)['values'][0]['value'] ?? 0;

        return [
            'impressions' => $getValue('impressions'),
            'reach' => $getValue('reach'),
            'likes' => $getValue('likes'),
            'comments' => $getValue('comments'),
            'saves' => $getValue('saved'),
            'shares' => $getValue('shares'),
        ];
    }

    public function fetchAccountStats(): array
    {
        $igUserId = config('services.instagram.user_id');
        $token = config('services.instagram.token');
        $response = Http::get("{$this->baseUrl}/{$igUserId}", [
            'access_token' => $token,
            'fields' => 'followers_count,follows_count,media_count',
        ]);

        return [
            'followers' => $response->json('followers_count') ?? 0,
            'following' => $response->json('follows_count') ?? 0,
            'posts_count' => $response->json('media_count') ?? 0,
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        $igUserId = config('services.instagram.user_id');
        $token = config('services.instagram.token');
        $response = Http::get("{$this->baseUrl}/{$igUserId}/conversations", [
            'access_token' => $token,
            'platform' => 'instagram',
            'fields' => 'participants,messages{message,from,created_time}',
            'limit' => $limit,
        ]);

        return array_map(fn ($conv) => [
            'id' => $conv['id'],
            'platform' => 'instagram',
            'messages' => $conv['messages']['data'] ?? [],
        ], $response->json('data') ?? []);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// InstagramService — Graph API v19.0 (IG Business).
// publish(): 2-step (create container → publish). Поддержка IMAGE + REELS.
// fetchPostAnalytics(): impressions, reach, likes, comments, saved, shares.
// fetchInbox(): IG Direct Messages через Conversations API.
// Файл: app/Services/Social/InstagramService.php
// ---------------------------------------------------------------
