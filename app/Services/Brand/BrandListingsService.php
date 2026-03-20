<?php

namespace App\Services\Brand;

use App\Enums\BrandListingStatusEnum;
use App\Models\BrandListing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrandListingsService
{
    // =====================================================
    // CANONICAL NAP (Name, Address, Phone)
    // =====================================================

    protected array $canonicalNap = [
        'name' => 'WinCase — Biuro Imigracyjne',
        'name_en' => 'WinCase — Immigration Bureau',
        'address' => 'ul. Hoza 66/68 lok. 211, 00-682 Warszawa, Poland',
        'phone' => '+48 579 266 493',
        'email' => 'wincasetop@gmail.com',
        'website' => 'https://wincase.pro',
        'hours' => 'Mon-Fri 9:00-18:00, Sat 10:00-14:00',
    ];

    // =====================================================
    // 50+ DIRECTORIES — grouped by category
    // =====================================================

    protected array $directoryGroups = [
        'google' => ['Google Business Profile', 'Google Maps'],
        'social' => ['Facebook', 'Instagram', 'LinkedIn', 'TikTok', 'YouTube', 'Pinterest', 'Threads'],
        'reviews' => ['Trustpilot', 'GoWork.pl', 'Opinie.pl', 'Firmy.net'],
        'legal' => ['Panorama Firm', 'PKD.pl', 'Regon.stat.gov.pl', 'KRS Online', 'CEIDG'],
        'maps' => ['Apple Maps', 'Bing Places', 'Waze', 'HERE WeGo', 'TomTom Places'],
        'directories_pl' => [
            'Pkt.pl', 'Zumi.pl', 'Oferteo.pl', 'Aleo.com', 'Baza-Firm.com.pl',
            'Katalog-Firm.info', 'Cylex.pl', 'Yellowpages.pl', 'Polskie-Firmy.net',
            'InfoHandel.pl', 'Branżowa.pl', 'FirmyPolskie.pl',
        ],
        'directories_int' => [
            'Yelp', 'Foursquare', 'Hotfrog', 'Cylex.com', 'eLocalFinder',
            'Tuugo', 'Find-Us-Here', 'Brownbook.net', 'Hub.biz',
        ],
        'legal_specific' => [
            'Izba Adwokacka', 'LegalPlanet', 'Prawnik.pl', 'Kancelarie.pl',
            'SzukajPrawnika.pl', 'Adwokat.pl',
        ],
        'immigration' => [
            'Expat.com', 'InterNations', 'Justlanded.com', 'MigrantInfo.pl',
            'Poradnik-Imigranta.pl',
        ],
    ];

    // =====================================================
    // NAP CONSISTENCY CHECK (W16 — Weekly Friday)
    // =====================================================

    /**
     * Check NAP consistency for all listings.
     * Returns: consistent count, inconsistent count, details.
     */
    public function checkNapConsistency(): array
    {
        $listings = BrandListing::where('status', '!=', 'not_submitted')->get();
        $results = ['consistent' => 0, 'inconsistent' => 0, 'not_checked' => 0, 'details' => []];

        foreach ($listings as $listing) {
            $issues = [];

            if ($listing->listed_name && $listing->listed_name !== $this->canonicalNap['name']
                && $listing->listed_name !== $this->canonicalNap['name_en']) {
                $issues[] = 'name_mismatch';
            }
            if ($listing->listed_address && !str_contains($listing->listed_address, 'Hoza 66')) {
                $issues[] = 'address_mismatch';
            }
            if ($listing->listed_phone && $listing->listed_phone !== $this->canonicalNap['phone']) {
                $issues[] = 'phone_mismatch';
            }

            if (empty($issues)) {
                $results['consistent']++;
                $listing->update(['nap_consistent' => true, 'last_checked_at' => now()]);
            } else {
                $results['inconsistent']++;
                $listing->update(['nap_consistent' => false, 'nap_issues' => $issues, 'last_checked_at' => now()]);
                $results['details'][] = [
                    'directory' => $listing->directory_name,
                    'issues' => $issues,
                    'url' => $listing->listing_url,
                ];
            }
        }

        return $results;
    }

    // =====================================================
    // OVERVIEW
    // =====================================================

    public function getOverview(): array
    {
        $listings = BrandListing::all();
        $total = $listings->count();
        $listed = $listings->where('status', BrandListingStatusEnum::LISTED->value)->count();
        $pending = $listings->where('status', BrandListingStatusEnum::PENDING->value)->count();
        $notListed = $listings->where('status', BrandListingStatusEnum::NOT_LISTED->value)->count();
        $napConsistent = $listings->where('nap_consistent', true)->count();

        return [
            'total_directories' => $total,
            'listed' => $listed,
            'pending' => $pending,
            'not_listed' => $notListed,
            'nap_consistent' => $napConsistent,
            'nap_inconsistent' => $total - $napConsistent - $notListed,
            'nap_score' => $total > 0 ? round(($napConsistent / max($total - $notListed, 1)) * 100, 1) : 0,
            'canonical_nap' => $this->canonicalNap,
            'directory_groups' => $this->directoryGroups,
        ];
    }

    // =====================================================
    // LISTINGS BY GROUP
    // =====================================================

    public function getListingsByGroup(): array
    {
        $listings = BrandListing::orderBy('directory_name')->get();
        $grouped = [];

        foreach ($this->directoryGroups as $group => $directories) {
            $grouped[$group] = [
                'label' => ucfirst(str_replace('_', ' ', $group)),
                'directories' => [],
            ];
            foreach ($directories as $dir) {
                $listing = $listings->firstWhere('directory_name', $dir);
                $grouped[$group]['directories'][] = [
                    'name' => $dir,
                    'status' => $listing?->status ?? 'not_submitted',
                    'nap_consistent' => $listing?->nap_consistent ?? null,
                    'url' => $listing?->listing_url,
                    'last_checked_at' => $listing?->last_checked_at?->toIso8601String(),
                ];
            }
        }

        return $grouped;
    }

    // =====================================================
    // CRUD
    // =====================================================

    public function updateListing(int $id, array $data): BrandListing
    {
        $listing = BrandListing::findOrFail($id);
        $listing->update($data);
        return $listing->fresh();
    }

    public function createListing(array $data): BrandListing
    {
        return BrandListing::create($data);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// BrandListingsService — управление 50+ каталогами/директориями.
// canonicalNap — эталонные данные NAP (Name, Address, Phone).
// directoryGroups — 10 групп: google, social, reviews, legal, maps,
// directories_pl (12), directories_int (9), legal_specific (6), immigration (5).
// checkNapConsistency() — проверка NAP во всех листингах (W16).
// getOverview() — статистика: verified, claimed, nap_score%.
// getListingsByGroup() — группированный список всех 50+ каталогов.
// Файл: app/Services/Brand/BrandListingsService.php
// ---------------------------------------------------------------
