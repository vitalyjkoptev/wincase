<?php

namespace Database\Seeders;

use App\Models\BrandListing;
use Illuminate\Database\Seeder;

class BrandListingsSeeder extends Seeder
{
    public function run(): void
    {
        $listings = [
            // --- Google Ecosystem ---
            ['platform' => 'Google Business Profile', 'url' => 'https://g.page/wincase-legalization', 'domain' => 'wincase.pro'],
            ['platform' => 'Google Maps', 'url' => 'https://maps.google.com/?cid=wincase', 'domain' => 'wincase.pro'],

            // --- Major Directories ---
            ['platform' => 'Yelp', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Yellow Pages PL', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Panorama Firm', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'PKT.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Firmy.net', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Baza Firm', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Zumi.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Aleo.com', 'url' => null, 'domain' => 'wincase.pro'],

            // --- Legal / Immigration Specific ---
            ['platform' => 'Kancelarie.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Oferteo.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Favore.pl', 'url' => null, 'domain' => 'wincase.pro'],

            // --- Review Platforms ---
            ['platform' => 'Trustpilot', 'url' => 'https://trustpilot.com/review/wincase.pro', 'domain' => 'wincase.pro'],
            ['platform' => 'GoWork.pl', 'url' => 'https://gowork.pl/wincase', 'domain' => 'wincase.pro'],
            ['platform' => 'Clutch.co', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'ProvenExpert', 'url' => null, 'domain' => 'wincase.pro'],

            // --- Social Profiles ---
            ['platform' => 'Facebook Page', 'url' => 'https://www.facebook.com/profile.php?id=100083419746646', 'domain' => 'wincase.eu'],
            ['platform' => 'Instagram', 'url' => 'https://instagram.com/wincase.legalization.pl', 'domain' => 'wincase.pro'],
            ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/company/wincase', 'domain' => 'wincase.pro'],
            ['platform' => 'TikTok', 'url' => 'https://tiktok.com/@wincase.legalization.pl', 'domain' => 'wincase.pro'],
            ['platform' => 'YouTube', 'url' => 'https://youtube.com/@WinCase', 'domain' => 'wincase.pro'],
            ['platform' => 'Pinterest', 'url' => 'https://pinterest.com/wincasepro', 'domain' => 'wincase.pro'],
            ['platform' => 'Threads', 'url' => 'https://threads.net/@wincase.legalization.pl', 'domain' => 'wincase.pro'],
            ['platform' => 'Telegram', 'url' => 'https://t.me/WinCasePro', 'domain' => 'wincase.pro'],

            // --- International Directories ---
            ['platform' => 'Apple Maps', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Bing Places', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'HERE WeGo', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'TomTom Places', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Foursquare', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Cylex.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Hotfrog.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => '2FindLocal.com', 'url' => null, 'domain' => 'wincase.pro'],

            // --- Job Centre domain ---
            ['platform' => 'Google Business Profile', 'url' => null, 'domain' => 'wincase-job.com'],
            ['platform' => 'Pracuj.pl', 'url' => null, 'domain' => 'wincase-job.com'],
            ['platform' => 'OLX Praca', 'url' => null, 'domain' => 'wincase-job.com'],
            ['platform' => 'Indeed Poland', 'url' => null, 'domain' => 'wincase-job.com'],
            ['platform' => 'LinkedIn Jobs', 'url' => null, 'domain' => 'wincase-job.com'],
            ['platform' => 'Jooble.pl', 'url' => null, 'domain' => 'wincase-job.com'],

            // --- Legalization domain ---
            ['platform' => 'Google Business Profile', 'url' => null, 'domain' => 'wincase-legalization.com'],
            ['platform' => 'Yelp', 'url' => null, 'domain' => 'wincase-legalization.com'],
            ['platform' => 'Trustpilot', 'url' => null, 'domain' => 'wincase-legalization.com'],

            // --- Corporate domain ---
            ['platform' => 'Google Business Profile', 'url' => null, 'domain' => 'wincase.org'],
            ['platform' => 'LinkedIn Company', 'url' => null, 'domain' => 'wincase.org'],
            ['platform' => 'Crunchbase', 'url' => null, 'domain' => 'wincase.org'],

            // --- Additional PL Directories ---
            ['platform' => 'Regon.stat.gov.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Infoveriti.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'KRS-online.pl', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Europages', 'url' => null, 'domain' => 'wincase.pro'],
            ['platform' => 'Kompass.com', 'url' => null, 'domain' => 'wincase.pro'],
        ];

        $referenceNap = BrandListing::REFERENCE_NAP;

        foreach ($listings as $listing) {
            $domain = $listing['domain'];
            $nap = $referenceNap[$domain] ?? $referenceNap['wincase.pro'];

            BrandListing::updateOrCreate(
                [
                    'platform' => $listing['platform'],
                    'domain' => $domain,
                ],
                [
                    'url' => $listing['url'],
                    'nap_name' => $nap['name'],
                    'nap_address' => $nap['address'],
                    'nap_phone' => $nap['phone'],
                    'nap_consistent' => false,
                    'status' => $listing['url'] ? 'listed' : 'pending',
                ]
            );
        }
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Сидер BrandListingsSeeder — заполняет 50+ каталогов бизнес-листингов
// для всех 4 доменов WinCase. Категории: Google, крупные каталоги,
// юридические, отзывы, соцсети, международные, польские, job-порталы.
// Использует updateOrCreate для идемпотентности (безопасно перезапускать).
// Берёт эталонные NAP из BrandListing::REFERENCE_NAP.
// Файл: database/seeders/BrandListingsSeeder.php
// ---------------------------------------------------------------
