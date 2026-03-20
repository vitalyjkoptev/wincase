<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // --- Existing CRM Seeders ---
            UserSeeder::class,
            // ClientSeeder::class,       // uncomment if exists
            // CaseSeeder::class,         // uncomment if exists

            // --- NEW v4.0 Seeders ---
            BrandListingsSeeder::class,   // 50+ directory listings (NAP check)
            SeoNetworkSitesSeeder::class, // 8 satellite SEO sites
            LandingsSeeder::class,        // 15 landing pages across 4 domains
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Обновлённый DatabaseSeeder для WINCASE CRM v4.0.
// Порядок запуска: 1) Users, 2) BrandListings (50+), 3) SeoNetworkSites (8),
// 4) Landings (15). Все сидеры используют updateOrCreate — безопасно для повторного запуска.
// Команда: php artisan db:seed
// Файл: database/seeders/DatabaseSeeder.php
// ---------------------------------------------------------------
