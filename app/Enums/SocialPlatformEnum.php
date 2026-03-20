<?php

namespace App\Enums;

enum SocialPlatformEnum: string
{
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case THREADS = 'threads';
    case TIKTOK = 'tiktok';
    case YOUTUBE = 'youtube';
    case TELEGRAM = 'telegram';
    case PINTEREST = 'pinterest';
    case LINKEDIN = 'linkedin';

    public function label(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Facebook',
            self::INSTAGRAM => 'Instagram',
            self::THREADS => 'Threads',
            self::TIKTOK => 'TikTok',
            self::YOUTUBE => 'YouTube',
            self::TELEGRAM => 'Telegram',
            self::PINTEREST => 'Pinterest',
            self::LINKEDIN => 'LinkedIn',
        };
    }

    public function handle(): string
    {
        return match ($this) {
            self::FACEBOOK => 'facebook.com/profile.php?id=100083419746646',
            self::INSTAGRAM => '@wincase.legalization.pl',
            self::THREADS => '@wincase.legalization.pl',
            self::TIKTOK => '@wincase.legalization.pl',
            self::YOUTUBE => '@WinCase',
            self::TELEGRAM => '@WinCasePro',
            self::PINTEREST => '@wincasepro',
            self::LINKEDIN => 'linkedin.com/company/wincase',
        };
    }

    public function url(): string
    {
        return match ($this) {
            self::FACEBOOK => 'https://www.facebook.com/profile.php?id=100083419746646',
            self::INSTAGRAM => 'https://www.instagram.com/wincase.legalization.pl',
            self::THREADS => 'https://www.threads.net/@wincase.legalization.pl',
            self::TIKTOK => 'https://www.tiktok.com/@wincase.legalization.pl',
            self::YOUTUBE => 'https://www.youtube.com/@WinCase',
            self::TELEGRAM => 'https://t.me/WinCasePro',
            self::PINTEREST => 'https://www.pinterest.com/wincasepro',
            self::LINKEDIN => 'https://www.linkedin.com/company/wincase',
        };
    }

    public function serviceClass(): string
    {
        return match ($this) {
            self::FACEBOOK => \App\Services\Social\FacebookService::class,
            self::INSTAGRAM => \App\Services\Social\InstagramService::class,
            self::THREADS => \App\Services\Social\ThreadsService::class,
            self::TIKTOK => \App\Services\Social\TikTokService::class,
            self::YOUTUBE => \App\Services\Social\YouTubeService::class,
            self::TELEGRAM => \App\Services\Social\TelegramService::class,
            self::PINTEREST => \App\Services\Social\PinterestService::class,
            self::LINKEDIN => \App\Services\Social\LinkedInService::class,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum 8 социальных платформ WinCase. handle() — аккаунт/ссылка.
// serviceClass() — соответствующий Service для API интеграции.
// Файл: app/Enums/SocialPlatformEnum.php
// ---------------------------------------------------------------
