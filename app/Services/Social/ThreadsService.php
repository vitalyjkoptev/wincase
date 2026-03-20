<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class ThreadsService extends AbstractSocialService
{
    protected string $baseUrl = 'https://graph.threads.net/v1.0';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::THREADS;
    }

    public function publish(array $content): ?string
    {
        $userId = config('services.threads.user_id');
        $token = config('services.threads.token');

        // Step 1: Create media container
        $params = [
            'access_token' => $token,
            'media_type' => 'TEXT',
            'text' => $content['text'] ?? '',
        ];

        if (!empty($content['media_url'])) {
            $params['media_type'] = 'IMAGE';
            $params['image_url'] = $content['media_url'];
        }

        if (!empty($content['link'])) {
            $params['text'] .= "\n\n" . $content['link'];
        }

        $container = Http::post("{$this->baseUrl}/{$userId}/threads", $params);
        $containerId = $container->json('id');

        if (!$containerId) return null;

        // Step 2: Publish
        $publish = Http::post("{$this->baseUrl}/{$userId}/threads_publish", [
            'access_token' => $token,
            'creation_id' => $containerId,
        ]);

        return $publish->json('id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = config('services.threads.token');
        $response = Http::get("{$this->baseUrl}/{$platformPostId}/insights", [
            'access_token' => $token,
            'metric' => 'views,likes,replies,reposts,quotes',
        ]);

        $data = collect($response->json('data') ?? []);
        $getValue = fn ($name) => $data->firstWhere('name', $name)['values'][0]['value'] ?? 0;

        return [
            'impressions' => $getValue('views'),
            'reach' => $getValue('views'),
            'likes' => $getValue('likes'),
            'comments' => $getValue('replies'),
            'shares' => $getValue('reposts') + $getValue('quotes'),
        ];
    }

    public function fetchAccountStats(): array
    {
        $userId = config('services.threads.user_id');
        $token = config('services.threads.token');
        $response = Http::get("{$this->baseUrl}/{$userId}/threads_insights", [
            'access_token' => $token,
            'metric' => 'followers_count',
        ]);

        return [
            'followers' => $response->json('data.0.values.0.value') ?? 0,
            'following' => 0,
            'posts_count' => 0,
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        // Threads API does not support DM inbox (as of 2025)
        return [];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// ThreadsService — Threads API v1.0.
// publish(): 2-step (create container → publish). TEXT + IMAGE.
// Ссылки добавляются в текст (Threads не поддерживает link preview).
// fetchPostAnalytics(): views, likes, replies, reposts, quotes.
// fetchInbox(): пустой — Threads API не поддерживает DM.
// Файл: app/Services/Social/ThreadsService.php
// ---------------------------------------------------------------
