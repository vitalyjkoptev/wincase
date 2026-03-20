<?php

namespace Database\Seeders;

use App\Models\SeoNetworkSite;
use Illuminate\Database\Seeder;

class SeoNetworkSitesSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'domain' => 'legalizacja-polska.pl',
                'name' => 'Legalizacja w Polsce — blog',
                'language' => 'pl',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'karta-pobytu.info',
                'name' => 'Karta Pobytu — informacje',
                'language' => 'pl',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'work-permit-poland.com',
                'name' => 'Work Permit Poland — EN blog',
                'language' => 'en',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'vnzh-polsha.com',
                'name' => 'ВНЖ Польша — RU блог',
                'language' => 'ru',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'praca-dla-obcokrajowcow.pl',
                'name' => 'Praca dla obcokrajowców',
                'language' => 'pl',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'posvidka-polshcha.com',
                'name' => 'Посвідка Польща — UA блог',
                'language' => 'ua',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'immigration-warsaw.com',
                'name' => 'Immigration Warsaw — EN portal',
                'language' => 'en',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
            [
                'domain' => 'visa-polska.com',
                'name' => 'Visa Polska — multilingual',
                'language' => 'en',
                'cms' => 'WordPress',
                'hosting' => 'Hostinger',
                'status' => 'active',
            ],
        ];

        foreach ($sites as $site) {
            SeoNetworkSite::updateOrCreate(
                ['domain' => $site['domain']],
                $site
            );
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Сидер SeoNetworkSitesSeeder — 8 сателлитных SEO-сайтов сети WinCase.
// Языки: pl (3), en (2), ru (1), ua (1), multilingual (1).
// Все на WordPress + Hostinger для единообразного управления.
// updateOrCreate по домену — безопасно перезапускать.
// Файл: database/seeders/SeoNetworkSitesSeeder.php
// ---------------------------------------------------------------
