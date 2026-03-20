<?php

namespace App\Services\News;

class NewsSitesRegistry
{
    // =====================================================
    // 8 NEWS SITES — each with unique menu/categories
    // Target publishing destinations for parsed content
    // =====================================================

    public static function getSites(): array
    {
        return [

            // =============================================
            // 1. POLANDPULSE.NEWS — General Poland/Europe
            // Main news portal, broadest coverage
            // =============================================
            'polandpulse' => [
                'site_id' => 'polandpulse',
                'domain' => 'polandpulse.news',
                'name' => 'Poland Pulse',
                'type' => 'wordpress',
                'api_url' => 'https://polandpulse.news/wp-json/wp/v2',
                'languages' => ['en', 'pl', 'ua', 'ru'],
                'categories' => [
                    'culture'          => ['wp_id' => 10, 'label_en' => 'Culture',             'label_pl' => 'Kultura'],
                    'europe'           => ['wp_id' => 20, 'label_en' => 'Europe',              'label_pl' => 'Europa'],
                    'visa_immigration' => ['wp_id' => 30, 'label_en' => 'Visa & Immigration',  'label_pl' => 'Wizy i Imigracja'],
                    'business'         => ['wp_id' => 40, 'label_en' => 'Business',            'label_pl' => 'Biznes'],
                    'trends'           => ['wp_id' => 50, 'label_en' => 'Trends',              'label_pl' => 'Trendy'],
                    'igaming'          => ['wp_id' => 60, 'label_en' => 'iGaming',             'label_pl' => 'iGaming'],
                    'tech_news'        => ['wp_id' => 70, 'label_en' => 'Tech News',           'label_pl' => 'Technologie'],
                    'sports'           => ['wp_id' => 80, 'label_en' => 'Sports',              'label_pl' => 'Sport'],
                    'europass'         => ['wp_id' => 90, 'label_en' => 'Europass & Education', 'label_pl' => 'Europass'],
                    'live_tv'          => ['wp_id' => 100, 'label_en' => 'Live TV & Media',    'label_pl' => 'TV na żywo'],
                ],
            ],

            // =============================================
            // 2. WINCASE.PRO/BLOG — Immigration Bureau Blog
            // Visa, legal, work permits, residency
            // =============================================
            'wincase' => [
                'site_id' => 'wincase',
                'domain' => 'wincase.pro',
                'name' => 'WinCase Blog',
                'type' => 'laravel',
                'api_url' => 'https://wincase.pro/api/v1/blog',
                'languages' => ['en', 'pl', 'ua', 'ru', 'hi'],
                'categories' => [
                    'work_permits'    => ['wp_id' => 1, 'label_en' => 'Work Permits',       'label_pl' => 'Pozwolenia na pracę'],
                    'residency'       => ['wp_id' => 2, 'label_en' => 'Residency',          'label_pl' => 'Pobyt'],
                    'visa_news'       => ['wp_id' => 3, 'label_en' => 'Visa News',          'label_pl' => 'Wiadomości wizowe'],
                    'eu_regulations'  => ['wp_id' => 4, 'label_en' => 'EU Regulations',     'label_pl' => 'Regulacje UE'],
                    'legal_updates'   => ['wp_id' => 5, 'label_en' => 'Legal Updates',      'label_pl' => 'Aktualizacje prawne'],
                    'guides'          => ['wp_id' => 6, 'label_en' => 'Guides & Tips',      'label_pl' => 'Poradniki'],
                    'success_stories' => ['wp_id' => 7, 'label_en' => 'Success Stories',    'label_pl' => 'Historie sukcesu'],
                ],
            ],

            // =============================================
            // 3. EUROGAMINGPOST.COM — iGaming Industry
            // Gambling, betting, casino, regulation
            // =============================================
            'eurogaming' => [
                'site_id' => 'eurogaming',
                'domain' => 'eurogamingpost.com',
                'name' => 'EuroGaming Post',
                'type' => 'wordpress',
                'api_url' => 'https://eurogamingpost.com/wp-json/wp/v2',
                'languages' => ['en'],
                'categories' => [
                    'industry_news'   => ['wp_id' => 10, 'label_en' => 'Industry News'],
                    'regulation'      => ['wp_id' => 20, 'label_en' => 'Regulation & Compliance'],
                    'online_casino'   => ['wp_id' => 30, 'label_en' => 'Online Casino'],
                    'sports_betting'  => ['wp_id' => 40, 'label_en' => 'Sports Betting'],
                    'crypto_gambling' => ['wp_id' => 50, 'label_en' => 'Crypto & Blockchain'],
                    'affiliate'       => ['wp_id' => 60, 'label_en' => 'Affiliate & Marketing'],
                    'tech_platforms'  => ['wp_id' => 70, 'label_en' => 'Tech & Platforms'],
                    'market_analysis' => ['wp_id' => 80, 'label_en' => 'Market Analysis'],
                ],
            ],

            // =============================================
            // 4. TECHPULSE.NEWS — Technology & Startups
            // AI, software, gadgets, startups
            // =============================================
            'techpulse' => [
                'site_id' => 'techpulse',
                'domain' => 'techpulse.news',
                'name' => 'TechPulse',
                'type' => 'wordpress',
                'api_url' => 'https://techpulse.news/wp-json/wp/v2',
                'languages' => ['en', 'pl'],
                'categories' => [
                    'ai_ml'          => ['wp_id' => 10, 'label_en' => 'AI & Machine Learning'],
                    'startups'       => ['wp_id' => 20, 'label_en' => 'Startups & Funding'],
                    'software'       => ['wp_id' => 30, 'label_en' => 'Software & Apps'],
                    'cybersecurity'  => ['wp_id' => 40, 'label_en' => 'Cybersecurity'],
                    'blockchain'     => ['wp_id' => 50, 'label_en' => 'Blockchain & Web3'],
                    'gadgets'        => ['wp_id' => 60, 'label_en' => 'Gadgets & Hardware'],
                    'cloud'          => ['wp_id' => 70, 'label_en' => 'Cloud & DevOps'],
                    'reviews'        => ['wp_id' => 80, 'label_en' => 'Reviews'],
                ],
            ],

            // =============================================
            // 5. BIZEUROPE.NEWS — European Business & Finance
            // Economy, markets, EU policy, fintech
            // =============================================
            'bizeurope' => [
                'site_id' => 'bizeurope',
                'domain' => 'bizeurope.news',
                'name' => 'BizEurope',
                'type' => 'wordpress',
                'api_url' => 'https://bizeurope.news/wp-json/wp/v2',
                'languages' => ['en', 'pl'],
                'categories' => [
                    'eu_economy'     => ['wp_id' => 10, 'label_en' => 'EU Economy'],
                    'markets'        => ['wp_id' => 20, 'label_en' => 'Markets & Investing'],
                    'fintech'        => ['wp_id' => 30, 'label_en' => 'Fintech & Banking'],
                    'real_estate'    => ['wp_id' => 40, 'label_en' => 'Real Estate'],
                    'crypto_finance' => ['wp_id' => 50, 'label_en' => 'Crypto & DeFi'],
                    'trade_policy'   => ['wp_id' => 60, 'label_en' => 'Trade & Policy'],
                    'entrepreneurship' => ['wp_id' => 70, 'label_en' => 'Entrepreneurship'],
                    'pl_business'    => ['wp_id' => 80, 'label_en' => 'Poland Business'],
                ],
            ],

            // =============================================
            // 6. SPORTPULSE.NEWS — European Sports
            // Football, MMA, esports, olympics
            // =============================================
            'sportpulse' => [
                'site_id' => 'sportpulse',
                'domain' => 'sportpulse.news',
                'name' => 'SportPulse',
                'type' => 'wordpress',
                'api_url' => 'https://sportpulse.news/wp-json/wp/v2',
                'languages' => ['en', 'pl'],
                'categories' => [
                    'football'       => ['wp_id' => 10, 'label_en' => 'Football / Soccer'],
                    'mma_boxing'     => ['wp_id' => 20, 'label_en' => 'MMA & Boxing'],
                    'esports'        => ['wp_id' => 30, 'label_en' => 'Esports'],
                    'tennis'         => ['wp_id' => 40, 'label_en' => 'Tennis'],
                    'basketball'     => ['wp_id' => 50, 'label_en' => 'Basketball'],
                    'motorsport'     => ['wp_id' => 60, 'label_en' => 'Motorsport / F1'],
                    'olympics'       => ['wp_id' => 70, 'label_en' => 'Olympics'],
                    'betting_odds'   => ['wp_id' => 80, 'label_en' => 'Betting & Odds'],
                ],
            ],

            // =============================================
            // 7. DIASPORA.NEWS — Ukrainian/Migrant Community
            // Life in Poland for immigrants
            // =============================================
            'diaspora' => [
                'site_id' => 'diaspora',
                'domain' => 'diaspora.news',
                'name' => 'Diaspora News',
                'type' => 'wordpress',
                'api_url' => 'https://diaspora.news/wp-json/wp/v2',
                'languages' => ['ua', 'en', 'pl', 'ru'],
                'categories' => [
                    'ukraine_news'   => ['wp_id' => 10, 'label_en' => 'Ukraine News',         'label_ua' => 'Новини України'],
                    'life_in_poland' => ['wp_id' => 20, 'label_en' => 'Life in Poland',       'label_ua' => 'Життя в Польщі'],
                    'job_market'     => ['wp_id' => 30, 'label_en' => 'Job Market',           'label_ua' => 'Ринок праці'],
                    'legal_help'     => ['wp_id' => 40, 'label_en' => 'Legal Help',           'label_ua' => 'Юридична допомога'],
                    'education'      => ['wp_id' => 50, 'label_en' => 'Education',            'label_ua' => 'Освіта'],
                    'community'      => ['wp_id' => 60, 'label_en' => 'Community Events',     'label_ua' => 'Спільнота'],
                    'housing'        => ['wp_id' => 70, 'label_en' => 'Housing & Rent',       'label_ua' => 'Житло'],
                    'integration'    => ['wp_id' => 80, 'label_en' => 'Integration & Culture', 'label_ua' => 'Інтеграція'],
                ],
            ],

            // =============================================
            // 8. WARSAWDAILY.ORG — Warsaw City News
            // Local Warsaw news, events, expat life, culture
            // =============================================
            'warsawdaily' => [
                'site_id' => 'warsawdaily',
                'domain' => 'warsawdaily.org',
                'name' => 'Warsaw Daily',
                'type' => 'wordpress',
                'api_url' => 'https://warsawdaily.org/wp-json/wp/v2',
                'languages' => ['en', 'pl'],
                'categories' => [
                    'city_news'    => ['wp_id' => 2, 'label_en' => 'City News',          'label_pl' => 'Wiadomości miejskie'],
                    'events'       => ['wp_id' => 3, 'label_en' => 'Events',             'label_pl' => 'Wydarzenia'],
                    'business'     => ['wp_id' => 4, 'label_en' => 'Business',           'label_pl' => 'Biznes'],
                    'culture_art'  => ['wp_id' => 5, 'label_en' => 'Culture & Art',      'label_pl' => 'Kultura i Sztuka'],
                    'food'         => ['wp_id' => 6, 'label_en' => 'Food & Restaurants', 'label_pl' => 'Jedzenie i Restauracje'],
                    'expat_life'   => ['wp_id' => 7, 'label_en' => 'Expat Life',         'label_pl' => 'Życie expatów'],
                    'transport'    => ['wp_id' => 8, 'label_en' => 'Transport',          'label_pl' => 'Transport'],
                    'real_estate'  => ['wp_id' => 9, 'label_en' => 'Real Estate',        'label_pl' => 'Nieruchomości'],
                ],
            ],

            // =============================================
            // 9. TRENDWATCH.NEWS — Lifestyle, Culture, Trends
            // Entertainment, travel, food, fashion
            // =============================================
            'trendwatch' => [
                'site_id' => 'trendwatch',
                'domain' => 'trendwatch.news',
                'name' => 'TrendWatch',
                'type' => 'wordpress',
                'api_url' => 'https://trendwatch.news/wp-json/wp/v2',
                'languages' => ['en', 'pl'],
                'categories' => [
                    'entertainment'  => ['wp_id' => 10, 'label_en' => 'Entertainment'],
                    'travel'         => ['wp_id' => 20, 'label_en' => 'Travel Europe'],
                    'food_drinks'    => ['wp_id' => 30, 'label_en' => 'Food & Drinks'],
                    'fashion'        => ['wp_id' => 40, 'label_en' => 'Fashion & Style'],
                    'health'         => ['wp_id' => 50, 'label_en' => 'Health & Wellness'],
                    'streaming'      => ['wp_id' => 60, 'label_en' => 'Streaming & TV'],
                    'music'          => ['wp_id' => 70, 'label_en' => 'Music'],
                    'viral'          => ['wp_id' => 80, 'label_en' => 'Viral & Trending'],
                ],
            ],
        ];
    }

    // =====================================================
    // GET SITE BY ID
    // =====================================================

    public static function getSite(string $siteId): ?array
    {
        return self::getSites()[$siteId] ?? null;
    }

    // =====================================================
    // GET ALL SITE IDS
    // =====================================================

    public static function getSiteIds(): array
    {
        return array_keys(self::getSites());
    }

    // =====================================================
    // SITE STATISTICS
    // =====================================================

    public static function getStats(): array
    {
        $sites = self::getSites();
        $totalCats = 0;
        foreach ($sites as $s) $totalCats += count($s['categories']);
        return [
            'total_sites' => count($sites),
            'total_categories' => $totalCats,
            'wordpress_sites' => count(array_filter($sites, fn($s) => $s['type'] === 'wordpress')),
            'laravel_sites' => count(array_filter($sites, fn($s) => $s['type'] === 'laravel')),
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NewsSitesRegistry — 9 новостных сайтов с уникальными меню:
// 1. polandpulse.news — General (10 категорий: culture, europe, visa, business, trends, igaming, tech, sports, europass, live tv)
// 2. wincase.pro — Immigration blog (7 категорий: work permits, residency, visa, EU regs, legal, guides, success)
// 3. eurogamingpost.com — iGaming (8: industry, regulation, casino, betting, crypto, affiliate, tech, analysis)
// 4. techpulse.news — Tech (8: AI, startups, software, cybersecurity, blockchain, gadgets, cloud, reviews)
// 5. bizeurope.news — Business (8: EU economy, markets, fintech, real estate, crypto, trade, entrepreneurship, PL)
// 6. sportpulse.news — Sports (8: football, MMA, esports, tennis, basketball, motorsport, olympics, betting)
// 7. diaspora.news — Ukrainian diaspora (8: Ukraine, life in PL, jobs, legal, education, community, housing, integration)
// 8. warsawdaily.org — Warsaw City (8: city news, events, business, culture & art, food, expat life, transport, real estate)
// 9. trendwatch.news — Lifestyle (8: entertainment, travel, food, fashion, health, streaming, music, viral)
// ИТОГО: 73 уникальные категории. 8 WordPress + 1 Laravel.
// Файл: app/Services/News/NewsSitesRegistry.php
// ---------------------------------------------------------------
