<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class FacebookService extends AbstractSocialService
{
    protected string $baseUrl = 'https://graph.facebook.com/v19.0';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::FACEBOOK;
    }

    public function publish(array $content): ?string
    {
        $pageId = config('services.facebook.page_id');
        $token = config('services.facebook.page_token');

        $params = ['access_token' => $token];

        if (!empty($content['media_url'])) {
            $params['url'] = $content['media_url'];
            $params['caption'] = $content['text'] ?? '';
            $endpoint = "{$this->baseUrl}/{$pageId}/photos";
        } elseif (!empty($content['link'])) {
            $params['message'] = $content['text'] ?? '';
            $params['link'] = $content['link'];
            $endpoint = "{$this->baseUrl}/{$pageId}/feed";
        } else {
            $params['message'] = $content['text'] ?? '';
            $endpoint = "{$this->baseUrl}/{$pageId}/feed";
        }

        $response = Http::post($endpoint, $params);
        return $response->json('id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = config('services.facebook.page_token');
        $response = Http::get("{$this->baseUrl}/{$platformPostId}/insights", [
            'access_token' => $token,
            'metric' => 'post_impressions,post_engaged_users,post_clicks,post_reactions_by_type_total',
        ]);

        $data = collect($response->json('data') ?? []);

        return [
            'impressions' => $data->firstWhere('name', 'post_impressions')['values'][0]['value'] ?? 0,
            'reach' => $data->firstWhere('name', 'post_engaged_users')['values'][0]['value'] ?? 0,
            'clicks' => $data->firstWhere('name', 'post_clicks')['values'][0]['value'] ?? 0,
            'likes' => 0,
            'comments' => 0,
            'shares' => 0,
        ];
    }

    public function fetchAccountStats(): array
    {
        $pageId = config('services.facebook.page_id');
        $token = config('services.facebook.page_token');
        $response = Http::get("{$this->baseUrl}/{$pageId}", [
            'access_token' => $token,
            'fields' => 'followers_count,fan_count,posts{created_time}',
        ]);

        return [
            'followers' => $response->json('followers_count') ?? 0,
            'following' => 0,
            'posts_count' => count($response->json('posts.data') ?? []),
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        $pageId = config('services.facebook.page_id');
        $token = config('services.facebook.page_token');
        $response = Http::get("{$this->baseUrl}/{$pageId}/conversations", [
            'access_token' => $token,
            'fields' => 'participants,messages{message,from,created_time}',
            'limit' => $limit,
        ]);

        return array_map(fn ($conv) => [
            'id' => $conv['id'],
            'platform' => 'facebook',
            'messages' => $conv['messages']['data'] ?? [],
        ], $response->json('data') ?? []);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// FacebookService — Graph API v19.0.
// publish(): текст, фото, ссылка на страницу FB.
// fetchPostAnalytics(): impressions, engaged_users, clicks.
// fetchAccountStats(): followers_count, fan_count.
// fetchInbox(): conversations + messages (Unified Inbox).
// Файл: app/Services/Social/FacebookService.php
// ---------------------------------------------------------------
