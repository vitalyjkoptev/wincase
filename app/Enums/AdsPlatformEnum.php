<?php

namespace App\Enums;

// =====================================================
// FILE: app/Enums/AdsPlatformEnum.php
// =====================================================

enum AdsPlatformEnum: string
{
    case GOOGLE_ADS = 'google_ads';
    case META_ADS = 'meta_ads';
    case TIKTOK_ADS = 'tiktok_ads';
    case PINTEREST_ADS = 'pinterest_ads';
    case YOUTUBE_ADS = 'youtube_ads';

    public function label(): string
    {
        return match ($this) {
            self::GOOGLE_ADS => 'Google Ads',
            self::META_ADS => 'Meta Ads (FB/IG)',
            self::TIKTOK_ADS => 'TikTok Ads',
            self::PINTEREST_ADS => 'Pinterest Ads',
            self::YOUTUBE_ADS => 'YouTube Ads',
        };
    }

    public function apiClass(): string
    {
        return match ($this) {
            self::GOOGLE_ADS => \App\Services\Ads\GoogleAdsService::class,
            self::META_ADS => \App\Services\Ads\MetaAdsService::class,
            self::TIKTOK_ADS => \App\Services\Ads\TikTokAdsService::class,
            self::PINTEREST_ADS => \App\Services\Ads\PinterestAdsService::class,
            self::YOUTUBE_ADS => \App\Services\Ads\YouTubeAdsService::class,
        };
    }

    public function syncInterval(): int
    {
        return match ($this) {
            self::GOOGLE_ADS, self::META_ADS, self::TIKTOK_ADS => 6,
            self::PINTEREST_ADS, self::YOUTUBE_ADS => 12,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum рекламных платформ (5 значений). apiClass() — сервис для синхронизации.
// syncInterval() — интервал синхронизации в часах (6ч или 12ч).
// Файл: app/Enums/AdsPlatformEnum.php
// ---------------------------------------------------------------
