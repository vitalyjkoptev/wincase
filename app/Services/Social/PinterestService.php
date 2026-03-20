<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class PinterestService extends AbstractSocialService
{
    protected string $baseUrl = 'https://api.pinterest.com/v5';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::PINTEREST;
    }

    public function publish(array $content): ?string
    {
        $token = config('services.pinterest.access_token');
        $boardId = config('services.pinterest.board_id');

        $params = [
            'board_id' => $boardId,
            'title' => substr($content['title'] ?? $content['text'] ?? '', 0, 100),
            'description' => $content['text'] ?? '',
            'link' => $content['link'] ?? 'https://wincase.pro',
            'alt_text' => $content['alt_text'] ?? $content['text'] ?? '',
        ];

        if (!empty($content['media_url'])) {
            $params['media_source'] = [
                'source_type' => 'image_url',
                'url' => $content['media_url'],
            ];
        }

        $response = Http::withToken($token)->post("{$this->baseUrl}/pins", $params);
        return $response->json('id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = config('services.pinterest.access_token');
        $response = Http::withToken($token)->get("{$this->baseUrl}/pins/{$platformPostId}/analytics", [
            'start_date' => now()->subDays(30)->toDateString(),
            'end_date' => now()->toDateString(),
            'metric_types' => 'IMPRESSION,PIN_CLICK,SAVE,OUTBOUND_CLICK',
        ]);

        $data = $response->json('all.summary_metrics') ?? [];

        return [
            'impressions' => $data['IMPRESSION'] ?? 0,
            'reach' => $data['IMPRESSION'] ?? 0,
            'clicks' => $data['PIN_CLICK'] ?? 0,
            'saves' => $data['SAVE'] ?? 0,
            'shares' => 0,
            'likes' => 0,
        ];
    }

    public function fetchAccountStats(): array
    {
        $token = config('services.pinterest.access_token');
        $response = Http::withToken($token)->get("{$this->baseUrl}/user_account");

        return [
            'followers' => $response->json('follower_count') ?? 0,
            'following' => $response->json('following_count') ?? 0,
            'posts_count' => $response->json('pin_count') ?? 0,
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        return []; // Pinterest does not have messaging API
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// PinterestService — Pinterest API v5.
// publish(): создание Pin с image_url, link, description.
// fetchPostAnalytics(): IMPRESSION, PIN_CLICK, SAVE, OUTBOUND_CLICK.
// fetchAccountStats(): follower_count, pin_count.
// Файл: app/Services/Social/PinterestService.php
// ---------------------------------------------------------------
