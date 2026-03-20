<?php

namespace App\Services\Social;

use App\Enums\SocialPlatformEnum;
use Illuminate\Support\Facades\Http;

class LinkedInService extends AbstractSocialService
{
    protected string $baseUrl = 'https://api.linkedin.com/v2';
    protected string $restUrl = 'https://api.linkedin.com/rest';

    protected function platform(): SocialPlatformEnum
    {
        return SocialPlatformEnum::LINKEDIN;
    }

    public function publish(array $content): ?string
    {
        $token = config('services.linkedin.access_token');
        $orgId = config('services.linkedin.organization_id');

        $payload = [
            'author' => "urn:li:organization:{$orgId}",
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $content['text'] ?? '',
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        // Add article/link
        if (!empty($content['link'])) {
            $payload['specificContent']['com.linkedin.ugc.ShareContent']['shareMediaCategory'] = 'ARTICLE';
            $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [[
                'status' => 'READY',
                'originalUrl' => $content['link'],
                'title' => ['text' => $content['title'] ?? ''],
                'description' => ['text' => substr($content['text'] ?? '', 0, 200)],
            ]];
        }

        $response = Http::withToken($token)
            ->withHeaders(['X-Restli-Protocol-Version' => '2.0.0'])
            ->post("{$this->baseUrl}/ugcPosts", $payload);

        return $response->json('id');
    }

    public function fetchPostAnalytics(string $platformPostId): array
    {
        $token = config('services.linkedin.access_token');
        $orgId = config('services.linkedin.organization_id');

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/organizationalEntityShareStatistics", [
                'q' => 'organizationalEntity',
                'organizationalEntity' => "urn:li:organization:{$orgId}",
                'shares' => [$platformPostId],
            ]);

        $stats = $response->json('elements.0.totalShareStatistics') ?? [];

        return [
            'impressions' => $stats['impressionCount'] ?? 0,
            'reach' => $stats['uniqueImpressionsCount'] ?? 0,
            'clicks' => $stats['clickCount'] ?? 0,
            'likes' => $stats['likeCount'] ?? 0,
            'comments' => $stats['commentCount'] ?? 0,
            'shares' => $stats['shareCount'] ?? 0,
            'engagement_rate' => $stats['engagement'] ?? 0,
        ];
    }

    public function fetchAccountStats(): array
    {
        $token = config('services.linkedin.access_token');
        $orgId = config('services.linkedin.organization_id');

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/organizationalEntityFollowerStatistics", [
                'q' => 'organizationalEntity',
                'organizationalEntity' => "urn:li:organization:{$orgId}",
            ]);

        $followers = $response->json('elements.0.followerCounts.organicFollowerCount') ?? 0;

        return [
            'followers' => $followers,
            'following' => 0,
            'posts_count' => 0,
        ];
    }

    public function fetchInbox(int $limit = 20): array
    {
        // LinkedIn Messaging API requires specific approval
        return [];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LinkedInService — LinkedIn Marketing API v2.
// publish(): UGC Post от имени организации (текст + ARTICLE).
// fetchPostAnalytics(): impressions, clicks, likes, comments, shares, engagement.
// fetchAccountStats(): organicFollowerCount.
// fetchInbox(): недоступен без special LinkedIn API approval.
// Файл: app/Services/Social/LinkedInService.php
// ---------------------------------------------------------------
