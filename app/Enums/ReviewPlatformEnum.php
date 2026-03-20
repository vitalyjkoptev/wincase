<?php

namespace App\Enums;

enum ReviewPlatformEnum: string
{
    case GOOGLE = 'google';
    case TRUSTPILOT = 'trustpilot';
    case FACEBOOK = 'facebook';
    case GOWORK = 'gowork';
    case CLUTCH = 'clutch';
    case PROVENEXPERT = 'provenexpert';

    public function label(): string
    {
        return match ($this) {
            self::GOOGLE => 'Google Reviews',
            self::TRUSTPILOT => 'Trustpilot',
            self::FACEBOOK => 'Facebook Reviews',
            self::GOWORK => 'GoWork.pl',
            self::CLUTCH => 'Clutch',
            self::PROVENEXPERT => 'ProvenExpert',
        };
    }

    public function url(): string
    {
        return match ($this) {
            self::GOOGLE => 'https://g.page/wincase-legalization',
            self::TRUSTPILOT => 'https://trustpilot.com/review/wincase.pro',
            self::FACEBOOK => 'https://www.facebook.com/profile.php?id=100083419746646',
            self::GOWORK => 'https://gowork.pl/wincase',
            self::CLUTCH => 'https://clutch.co/profile/wincase',
            self::PROVENEXPERT => 'https://provenexpert.com/wincase',
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum платформ отзывов (6 значений). url() — ссылка на профиль WinCase.
// Используется в таблице reviews и модуле Brand & Reputation.
// Файл: app/Enums/ReviewPlatformEnum.php
// ---------------------------------------------------------------
