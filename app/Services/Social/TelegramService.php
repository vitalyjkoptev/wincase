<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class TelegramService extends AbstractSocialService
{
    protected string $baseUrl = 'https://api.telegram.org/bot';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::TELEGRAM;
    }

    protected function botUrl(string $method): string
    {
        return $this->baseUrl . config('services.telegram.bot_token') . "/{$method}";
    }

    public function publish(array $content): ?string
    {
        $chatId = config('services.telegram.channel_id'); // @WinCasePro

        if (!empty($content['media_url'])) {
            $response = Http::post($this->botUrl('sendPhoto'), [
                'chat_id' => $chatId,
                'photo' => $content['media_url'],
                'caption' => $content['text'] ?? '',
                'parse_mode' => 'HTML',
            ]);
        } elseif (!empty($content['video_url'])) {
            $response = Http::post($this->botUrl('sendVideo'), [
                'chat_id' => $chatId,
                'video' => $content['video_url'],
                'caption' => $content['text'] ?? '',
                'parse_mode' => 'HTML',
            ]);
        } else {
            $response = Http::post($this->botUrl('sendMessage'), [
                'chat_id' => $chatId,
                'text' => $content['text'] ?? '',
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => empty($content['link']),
            ]);
        }

        return (string) ($response->json('result.message_id') ?? null);
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        // Telegram Bot API doesn't provide post analytics
        // Use TGStat API or manual tracking
        return [
            'impressions' => 0, 'reach' => 0, 'likes' => 0,
            'comments' => 0, 'shares' => 0,
        ];
    }

    public function fetchAccountStats(): array
    {
        $chatId = config('services.telegram.channel_id');
        $response = Http::get($this->botUrl('getChatMembersCount'), ['chat_id' => $chatId]);

        return [
            'followers' => $response->json('result') ?? 0,
            'following' => 0,
            'posts_count' => 0,
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        // Fetch updates from bot (personal messages to bot)
        $response = Http::get($this->botUrl('getUpdates'), [
            'limit' => $limit,
            'allowed_updates' => json_encode(['message']),
        ]);

        return array_map(fn ($update) => [
            'id' => $update['update_id'],
            'platform' => 'telegram',
            'messages' => [[
                'from' => $update['message']['from']['first_name'] ?? 'Unknown',
                'message' => $update['message']['text'] ?? '',
                'created_time' => date('c', $update['message']['date'] ?? time()),
            ]],
        ], $response->json('result') ?? []);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// TelegramService — Telegram Bot API.
// publish(): sendMessage/sendPhoto/sendVideo в канал @WinCasePro (HTML parse_mode).
// fetchAccountStats(): getChatMembersCount.
// fetchInbox(): getUpdates — личные сообщения боту.
// Аналитика постов недоступна через Bot API (использовать TGStat).
// Файл: app/Services/Social/TelegramService.php
// ---------------------------------------------------------------
