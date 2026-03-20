<?php

namespace App\Enums;

enum LeadSourceEnum: string
{
    case GOOGLE_ADS = 'google_ads';
    case FACEBOOK_ADS = 'facebook_ads';
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case TIKTOK_ADS = 'tiktok_ads';
    case TIKTOK = 'tiktok';
    case PINTEREST_ADS = 'pinterest_ads';
    case YOUTUBE_ADS = 'youtube_ads';
    case LINKEDIN = 'linkedin';
    case THREADS = 'threads';
    case ORGANIC = 'organic';
    case WEBSITE = 'website';
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case REFERRAL = 'referral';
    case PARTNER = 'partner';
    case WALK_IN = 'walk_in';
    case PHONE = 'phone';

    public function label(): string
    {
        return match ($this) {
            self::GOOGLE_ADS => 'Google Ads',
            self::FACEBOOK_ADS => 'Facebook / Instagram Ads',
            self::FACEBOOK => 'Facebook (Organic)',
            self::INSTAGRAM => 'Instagram (Organic)',
            self::TIKTOK_ADS => 'TikTok Ads',
            self::TIKTOK => 'TikTok (Organic)',
            self::PINTEREST_ADS => 'Pinterest Ads',
            self::YOUTUBE_ADS => 'YouTube Ads',
            self::LINKEDIN => 'LinkedIn',
            self::THREADS => 'Threads (Organic)',
            self::ORGANIC => 'Organic Search',
            self::WEBSITE => 'Website',
            self::TELEGRAM => 'Telegram',
            self::WHATSAPP => 'WhatsApp',
            self::REFERRAL => 'Referral',
            self::PARTNER => 'Partner',
            self::WALK_IN => 'Walk-in (Office)',
            self::PHONE => 'Phone Call',
        };
    }

    public function isPaid(): bool
    {
        return in_array($this, [
            self::GOOGLE_ADS,
            self::FACEBOOK_ADS,
            self::TIKTOK_ADS,
            self::PINTEREST_ADS,
            self::YOUTUBE_ADS,
        ]);
    }

    public function icon(): string
    {
        return match ($this) {
            self::GOOGLE_ADS => 'google',
            self::FACEBOOK_ADS => 'facebook',
            self::FACEBOOK => 'facebook',
            self::INSTAGRAM => 'instagram',
            self::TIKTOK_ADS => 'tiktok',
            self::TIKTOK => 'tiktok',
            self::PINTEREST_ADS => 'pinterest',
            self::YOUTUBE_ADS => 'youtube',
            self::LINKEDIN => 'linkedin',
            self::THREADS => 'threads',
            self::ORGANIC => 'search',
            self::WEBSITE => 'globe',
            self::TELEGRAM => 'telegram',
            self::WHATSAPP => 'whatsapp',
            self::REFERRAL => 'user-plus',
            self::PARTNER => 'handshake',
            self::WALK_IN => 'building',
            self::PHONE => 'phone',
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum источников лидов (12 значений).
// Включает платные каналы (Google/Meta/TikTok/Pinterest/YouTube Ads),
// органические (Threads, SEO, Telegram, WhatsApp, Referral),
// и офлайн (Walk-in, Phone).
// Методы: label() — человекочитаемое имя, isPaid() — платный ли канал,
// icon() — иконка для UI.
// Файл: app/Enums/LeadSourceEnum.php
// ---------------------------------------------------------------
