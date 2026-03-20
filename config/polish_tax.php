<?php

// =====================================================
// FILE: config/polish_tax.php
// All Polish tax rates, thresholds, ZUS contributions for 2026
// Updated annually by accountant / owner
// =====================================================

return [

    // === CURRENT TAX YEAR ===
    'tax_year' => 2026,

    // === COMPANY INFO (for invoice/report headers) ===
    'company' => [
        'name' => 'WinCase Immigration Bureau',
        'nip' => '',
        'regon' => '',
        'address' => 'Warsaw, Poland',
        'phone' => '+48 579 266 493',
        'email' => 'wincasetop@gmail.com',
        'bank_name' => '',
        'bank_iban' => '',
        'bank_swift' => '',
        'entity_type' => 'jdg',
        'tax_regime' => 'ryczalt',
        'pkd_codes' => ['69.10.Z', '70.22.Z', '78.10.Z'],
    ],

    // === PIT — Personal Income Tax (JDG) ===
    'pit' => [
        // Skala podatkowa (progressive)
        'skala' => [
            'threshold' => 120000.00,
            'rate_lower' => 12.00,
            'rate_upper' => 32.00,
            'tax_free_amount' => 30000.00,
            'tax_reducing_amount' => 3600.00,
        ],
        // Liniowy (flat)
        'liniowy' => [
            'rate' => 19.00,
        ],
        // Ryczałt — rates by activity type
        'ryczalt' => [
            'rates' => [
        '17.0' => 'Wolne zawody (unregulated liberal professions)',
        '15.0' => 'Legal, consulting, bookkeeping, immigration services',
        '14.0' => 'Healthcare services',
        '12.0' => 'IT, programming, software development',
        '10.0' => 'Rental income (property)',
        '8.5'  => 'General services, liberal professions (regulated), education',
        '5.5'  => 'Construction, manufacturing',
        '3.0'  => 'Trade (retail/wholesale), gastronomy',
        '2.0'  => 'Agricultural products',
            ],
            'revenue_limit' => 2000000 * 4.30,
            'default_rate_immigration' => 15.0,
        ],
        'advance_payment_deadline' => 20,
        'annual_filing_deadline' => '04-30',
    ],

    // === CIT — Corporate Income Tax (Sp. z o.o.) ===
    'cit' => [
        'standard_rate' => 19.00,
        'small_taxpayer_rate' => 9.00,
        'small_taxpayer_limit' => 2000000 * 4.30,
        'estonian_cit_rate_small' => 10.00,
        'estonian_cit_rate_standard' => 20.00,
        'minimum_tax_rate' => 10.00,
        'annual_filing_deadline' => '03-31',
    ],

    // === VAT — Value Added Tax ===
    'vat' => [
        'standard' => 23.00,
        'reduced' => 8.00,
        'super_reduced' => 5.00,
        'zero' => 0.00,
        'exempt' => 0.00,
        'registration_threshold' => 200000.00,
        'small_taxpayer_limit' => 8569000.00,
        'jpk_vat_deadline' => 25,
        'payment_deadline' => 25,
        'reporting_period' => 'monthly',

        // GTU codes for JPK_VAT (immigration bureau)
        'default_gtu' => 'GTU_12',

        // Reverse charge for EU clients
        'reverse_charge_eu' => true,
    ],

    // === ZUS — Social Security (Zakład Ubezpieczeń Społecznych) ===
    'zus' => [
        // Base for full contributions (60% of avg. projected salary 2026)
        'base_full' => 4620.00,

        // Contributions on full base (monthly)
        'pension' => [
            'rate' => 19.52,
            'employee_rate' => 9.76,
            'employer_rate' => 9.76,
        ],
        'disability' => [
            'rate' => 8.00,
            'employee_rate' => 1.50,
            'employer_rate' => 6.50,
        ],
        'sickness' => [
            'rate' => 2.45,
            'voluntary' => true,
        ],
        'accident' => [
            'rate' => 1.67,
        ],
        'labor_fund' => [
            'rate' => 2.45,
        ],
        'fgsp' => [
            'rate' => 0.10,
        ],

        // Total monthly social contributions (approximate 2026)
        'total_social_monthly' => 1575.00,

        // Health insurance (NFZ)
        'health' => [
            'skala_rate' => 9.00,
            'liniowy_rate' => 4.90,
            'minimum_monthly' => 315.00,
            // Ryczałt health: fixed by revenue brackets
            'ryczalt_brackets' => [
                ['max_revenue' => 60000, 'monthly' => 498.00],
                ['max_revenue' => 300000, 'monthly' => 831.00],
                ['max_revenue' => PHP_FLOAT_MAX, 'monthly' => 1494.00],
            ],
        ],

        // Preferential ZUS (first 24 months of business)
        'preferential' => [
            'base' => 1380.00,
            'total_social_monthly' => 475.00,
        ],

        // Ulga na start (first 6 months — only health insurance)
        'ulga_na_start' => [
            'months' => 6,
            'social_contributions' => 0.00,
        ],

        // Monthly deadlines
        'payment_deadline_jdg' => 10,
        'payment_deadline_employer' => 15,
        'dra_filing_deadline' => 10,
    ],

    // === OTHER TAXES ===
    'other' => [
        // PCC — Tax on Civil Law Transactions
        'pcc' => [
            'sale_rate' => 2.00,
            'loan_rate' => 0.50,
        ],

        // Real Estate Tax (buildings used for business)
        'real_estate' => [
            'rate_per_sqm_business' => 34.00,
            'rate_per_sqm_residential' => 1.15,
            'rate_per_sqm_land_business' => 1.38,
        ],

        // Vehicle tax (if applicable)
        'vehicle' => [
            'deduction_limit_combustion' => 100000.00,
            'deduction_limit_hybrid' => 150000.00,
            'deduction_limit_electric' => 225000.00,
            'vat_deduction_mixed_use' => 50.00,
            'vat_deduction_business_only' => 100.00,
        ],

        // Withholding tax (for non-resident payments)
        'withholding' => [
            'rate_services' => 20.00,
            'rate_dividends' => 19.00,
            'rate_interest' => 20.00,
        ],
    ],

    // === REPORTING DEADLINES ===
    'deadlines' => [
        'jpk_vat_monthly' => 25,
        'pit_advance_monthly' => 20,
        'pit_advance_quarterly' => 20,
        'zus_jdg' => 10,
        'zus_employer' => 15,
        'pit_annual' => '04-30',
        'cit_annual' => '03-31',
        'pit28_annual' => '02-28',
    ],

    // === INVOICE SETTINGS ===
    'invoice' => [
        'prefix' => 'FV',
        'correction_prefix' => 'FK',
        'proforma_prefix' => 'FP',
        'currency' => 'PLN',
        'default_vat_rate' => 23.00,
        'default_payment_days' => 14,
        'ksef_enabled' => false,
    ],

    // === KSEF — National e-Invoicing System (upcoming) ===
    'ksef' => [
        'enabled' => false,
        'mandatory_from' => '2026-02-01',
        'api_url' => 'https://ksef.mf.gov.pl/api',
        'test_api_url' => 'https://ksef-test.mf.gov.pl/api',
    ],
];

// ---------------------------------------------------------------
// Аннотация (RU):
// Конфигурационный файл всех налогов Польши на 2026 год.
// Содержит:
//   PIT: skala podatkowa (12%/32%, необлагаемый минимум 30 000 PLN),
//        liniowy (19%), рычалт (9 ставок от 2% до 17%).
//   CIT: стандартный 19%, малый налогоплательщик 9%, эстонский CIT.
//   VAT: 23%/8%/5%/0%/zwolniony, порог регистрации 200 000 PLN,
//        JPK_VAT дедлайн 25-е число, GTU_12 для юридических услуг.
//   ZUS: полная база 4 620 PLN, общие взносы ~1 575 PLN/мес,
//        здоровье (NFZ): 9%/4.9%/фиксированные для рычалта,
//        льготный ZUS (24 мес), Ulga na start (6 мес).
//   Прочие: PCC (2%/0.5%), налог на недвижимость, авто вычеты,
//        удержание для нерезидентов (20%/19%).
//   Дедлайны: JPK_VAT 25-е, PIT аванс 20-е, ZUS 10/15-е.
//   KSeF: электронная система счетов (подготовлено, пока отключено).
// Обновляется ежегодно (ставки ZUS, пороги, дедлайны).
// Файл: config/polish_tax.php
// ---------------------------------------------------------------
