<?php

namespace Database\Seeders;

use App\Models\Landing;
use Illuminate\Database\Seeder;

class LandingsSeeder extends Seeder
{
    public function run(): void
    {
        $landings = [
            // === wincase.pro — Main landings ===
            ['domain' => 'wincase.pro', 'path' => '/karta-pobytu', 'language' => 'pl', 'title' => 'Karta Pobytu — Residence Permit', 'audience' => 'Foreigners in Poland (PL)', 'traffic_sources' => ['google_ads', 'organic']],
            ['domain' => 'wincase.pro', 'path' => '/residence-permit', 'language' => 'en', 'title' => 'Residence Permit in Poland', 'audience' => 'English-speaking expats', 'traffic_sources' => ['google_ads', 'organic', 'facebook_ads']],
            ['domain' => 'wincase.pro', 'path' => '/vid-na-zhytelstvo', 'language' => 'ru', 'title' => 'Вид на жительство в Польше', 'audience' => 'Russian-speaking community', 'traffic_sources' => ['google_ads', 'facebook_ads', 'telegram']],
            ['domain' => 'wincase.pro', 'path' => '/posvidka-na-prozhyvannia', 'language' => 'ua', 'title' => 'Посвідка на проживання в Польщі', 'audience' => 'Ukrainian community', 'traffic_sources' => ['google_ads', 'facebook_ads', 'telegram']],
            ['domain' => 'wincase.pro', 'path' => '/citizenship', 'language' => 'en', 'title' => 'Polish Citizenship — Full Guide', 'audience' => 'Long-term residents', 'traffic_sources' => ['organic', 'google_ads']],
            ['domain' => 'wincase.pro', 'path' => '/work-permit', 'language' => 'en', 'title' => 'Work Permit Poland', 'audience' => 'Employers & employees', 'traffic_sources' => ['google_ads', 'organic']],
            ['domain' => 'wincase.pro', 'path' => '/niwas-ki-ijazat', 'language' => 'hi', 'title' => 'पोलैंड में निवास परमिट', 'audience' => 'Hindi-speaking workers', 'traffic_sources' => ['facebook_ads', 'tiktok_ads']],
            ['domain' => 'wincase.pro', 'path' => '/permiso-residencia', 'language' => 'es', 'title' => 'Permiso de Residencia en Polonia', 'audience' => 'Spanish-speaking community', 'traffic_sources' => ['facebook_ads', 'organic']],
            ['domain' => 'wincase.pro', 'path' => '/oturma-izni', 'language' => 'tr', 'title' => 'Polonya Oturma İzni', 'audience' => 'Turkish community', 'traffic_sources' => ['google_ads', 'facebook_ads']],
            ['domain' => 'wincase.pro', 'path' => '/pahintulot-sa-paninirahan', 'language' => 'tl', 'title' => 'Residence Permit sa Poland', 'audience' => 'Filipino community', 'traffic_sources' => ['facebook_ads', 'tiktok_ads']],

            // === wincase-legalization.com — A/B test ===
            ['domain' => 'wincase-legalization.com', 'path' => '/legalization', 'language' => 'en', 'title' => 'Legalization Services Poland', 'audience' => 'All foreigners (EN)', 'traffic_sources' => ['google_ads'], 'ab_variant' => 'A'],
            ['domain' => 'wincase-legalization.com', 'path' => '/legalization-v2', 'language' => 'en', 'title' => 'Legalization Services Poland (V2)', 'audience' => 'All foreigners (EN)', 'traffic_sources' => ['google_ads'], 'ab_variant' => 'B'],

            // === wincase-job.com — Job Centre ===
            ['domain' => 'wincase-job.com', 'path' => '/find-job', 'language' => 'en', 'title' => 'Find Job in Poland', 'audience' => 'Job seekers', 'traffic_sources' => ['google_ads', 'organic', 'linkedin']],
            ['domain' => 'wincase-job.com', 'path' => '/hire-foreigners', 'language' => 'pl', 'title' => 'Zatrudnij Obcokrajowca', 'audience' => 'Polish employers', 'traffic_sources' => ['google_ads', 'linkedin']],

            // === wincase.org — Corporate / SaaS ===
            ['domain' => 'wincase.org', 'path' => '/about', 'language' => 'en', 'title' => 'About WinCase — Immigration Bureau', 'audience' => 'Partners & B2B', 'traffic_sources' => ['organic', 'linkedin']],
        ];

        foreach ($landings as $landing) {
            Landing::updateOrCreate(
                [
                    'domain' => $landing['domain'],
                    'path' => $landing['path'],
                ],
                [
                    'language' => $landing['language'],
                    'title' => $landing['title'],
                    'audience' => $landing['audience'],
                    'traffic_sources' => $landing['traffic_sources'],
                    'ab_variant' => $landing['ab_variant'] ?? null,
                    'is_active' => true,
                ]
            );
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Сидер LandingsSeeder — 15 лендинговых страниц на 4 доменах.
// wincase.pro: 10 страниц на 8 языках (PL/EN/RU/UA/HI/ES/TR/TL).
// wincase-legalization.com: 2 страницы A/B тест.
// wincase-job.com: 2 страницы (соискатели + работодатели).
// wincase.org: 1 корпоративная страница.
// updateOrCreate по domain+path — безопасно перезапускать.
// Файл: database/seeders/LandingsSeeder.php
// ---------------------------------------------------------------
