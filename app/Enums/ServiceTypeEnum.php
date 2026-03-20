<?php

namespace App\Enums;

enum ServiceTypeEnum: string
{
    case KARTA_POBYTU = 'karta_pobytu';
    case TEMP_RESIDENCE = 'temp_residence';
    case PERMANENT_RESIDENCE = 'permanent_residence';
    case KARTA_POBYTU_EXCHANGE = 'karta_pobytu_exchange';
    case KARTA_POLAKA = 'karta_polaka';
    case EU_BLUE_CARD = 'eu_blue_card';
    case CITIZENSHIP = 'citizenship';
    case WORK_PERMIT = 'work_permit';
    case FAMILY_REUNIFICATION = 'family_reunification';
    case VISA = 'visa';
    case TEMPORARY_PROTECTION = 'temporary_protection';
    case BUSINESS = 'business';
    case BUSINESS_REGISTRATION = 'business_registration';
    case JOB_CENTRE = 'job_centre';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::KARTA_POBYTU => 'Karta Pobytu (Residence Permit)',
            self::TEMP_RESIDENCE => 'Temporary Residence Permit',
            self::PERMANENT_RESIDENCE => 'Permanent Residence Permit',
            self::KARTA_POBYTU_EXCHANGE => 'Karta Pobytu Exchange',
            self::KARTA_POLAKA => 'Karta Polaka',
            self::EU_BLUE_CARD => 'EU Blue Card',
            self::CITIZENSHIP => 'Citizenship',
            self::WORK_PERMIT => 'Work Permit',
            self::FAMILY_REUNIFICATION => 'Family Reunification',
            self::VISA => 'Visa',
            self::TEMPORARY_PROTECTION => 'Temporary Protection',
            self::BUSINESS => 'Business',
            self::BUSINESS_REGISTRATION => 'Business Registration',
            self::JOB_CENTRE => 'Job Centre',
            self::OTHER => 'Other',
        };
    }

    public function averagePrice(): float
    {
        return match ($this) {
            self::KARTA_POBYTU => 500.00,
            self::TEMP_RESIDENCE => 500.00,
            self::PERMANENT_RESIDENCE => 800.00,
            self::KARTA_POBYTU_EXCHANGE => 400.00,
            self::KARTA_POLAKA => 600.00,
            self::EU_BLUE_CARD => 1200.00,
            self::CITIZENSHIP => 1500.00,
            self::WORK_PERMIT => 400.00,
            self::FAMILY_REUNIFICATION => 700.00,
            self::VISA => 350.00,
            self::TEMPORARY_PROTECTION => 300.00,
            self::BUSINESS => 800.00,
            self::BUSINESS_REGISTRATION => 800.00,
            self::JOB_CENTRE => 200.00,
            self::OTHER => 0.00,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum типов иммиграционных услуг WinCase (7 значений).
// Основные: karta_pobytu, citizenship, work_permit, temporary_protection,
// business, job_centre, other.
// Метод averagePrice() — средний чек по услуге в EUR для прогнозов ROI.
// Файл: app/Enums/ServiceTypeEnum.php
// ---------------------------------------------------------------
