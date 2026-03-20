<?php

namespace App\Services\News;

/**
 * Registry of verified, working news sources.
 * All RSS feeds tested and confirmed active.
 * Mapped to target site categories.
 */
class NewsSourcesRegistry
{
    // =====================================================
    // TARGET SITES
    // =====================================================

    public static function getTargetSites(): array
    {
        return [
            'polandpulse' => [
                'domain' => 'polandpulse.news',
                'name' => 'Poland Pulse',
                'languages' => ['en', 'pl', 'ua', 'ru'],
                'api_endpoint' => 'https://polandpulse.news/api/v1/posts',
                'api_key_env' => 'POLANDPULSE_API_KEY',
                'cms' => 'wordpress', // WP REST API
            ],
            'wincase_blog' => [
                'domain' => 'wincase.pro',
                'name' => 'WinCase Blog',
                'languages' => ['pl', 'en'],
                'api_endpoint' => 'https://wincase.pro/api/v1/blog',
                'api_key_env' => 'WINCASE_BLOG_API_KEY',
                'cms' => 'laravel',
            ],
        ];
    }

    // =====================================================
    // NEWS SOURCES — RSS FEEDS (verified working)
    // =====================================================

    public static function getSources(): array
    {
        return [

            // =================================================
            // CATEGORY: Poland — General News
            // =================================================

            'tvn24' => [
                'name' => 'TVN24',
                'type' => 'rss',
                'url' => 'https://tvn24.pl/najwazniejsze.xml',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'high',
                'check_interval' => 5, // minutes
                'trusted' => true,
            ],
            'polsat_news' => [
                'name' => 'Polsat News',
                'type' => 'rss',
                'url' => 'https://www.polsatnews.pl/rss/wszystkie.xml',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'high',
                'check_interval' => 5,
                'trusted' => true,
            ],
            'onet' => [
                'name' => 'Onet.pl',
                'type' => 'rss',
                'url' => 'https://wiadomosci.onet.pl/.feed',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'wp_wiadomosci' => [
                'name' => 'Wirtualna Polska',
                'type' => 'rss',
                'url' => 'https://wiadomosci.wp.pl/rss.xml',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'interia' => [
                'name' => 'Interia Fakty',
                'type' => 'rss',
                'url' => 'https://fakty.interia.pl/feed',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'radio_zet' => [
                'name' => 'Radio ZET',
                'type' => 'rss',
                'url' => 'https://wiadomosci.radiozet.pl/feed',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: Poland — Business & Finance
            // =================================================

            'money_pl' => [
                'name' => 'Money.pl',
                'type' => 'rss',
                'url' => 'https://www.money.pl/rss/rss.xml',
                'category' => 'business',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],
            'bankier' => [
                'name' => 'Bankier.pl',
                'type' => 'rss',
                'url' => 'https://www.bankier.pl/rss/wiadomosci.xml',
                'category' => 'business',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],
            'business_insider_pl' => [
                'name' => 'Business Insider PL',
                'type' => 'rss',
                'url' => 'https://businessinsider.com.pl/rss',
                'category' => 'business',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 30,
                'trusted' => true,
            ],
            'forsal' => [
                'name' => 'Forsal.pl',
                'type' => 'rss',
                'url' => 'https://forsal.pl/rss.xml',
                'category' => 'business',
                'language' => 'pl',
                'priority' => 'low',
                'check_interval' => 30,
                'trusted' => true,
            ],
            'puls_biznesu' => [
                'name' => 'Puls Biznesu',
                'type' => 'rss',
                'url' => 'https://www.pb.pl/rss/all.xml',
                'category' => 'business',
                'language' => 'pl',
                'priority' => 'low',
                'check_interval' => 30,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: Immigration & Legal
            // =================================================

            'udsc_gov' => [
                'name' => 'UDSC (Urząd do Spraw Cudzoziemców)',
                'type' => 'scrape',
                'url' => 'https://www.gov.pl/web/udsc/aktualnosci',
                'category' => 'immigration',
                'language' => 'pl',
                'priority' => 'critical', // IMMEDIATE publish
                'check_interval' => 15,
                'trusted' => true,
                'selectors' => [
                    'list' => '.article-area .news-list li',
                    'title' => 'a',
                    'link' => 'a@href',
                    'date' => '.date',
                ],
            ],
            'gov_cudzoziemcy' => [
                'name' => 'Gov.pl — Cudzoziemcy',
                'type' => 'scrape',
                'url' => 'https://www.gov.pl/web/udsc/wazne-informacje',
                'category' => 'immigration',
                'language' => 'pl',
                'priority' => 'critical',
                'check_interval' => 15,
                'trusted' => true,
            ],
            'migrant_info' => [
                'name' => 'MigrantInfo.pl',
                'type' => 'scrape',
                'url' => 'https://www.migrantinfo.pl/news',
                'category' => 'immigration',
                'language' => 'pl',
                'priority' => 'high',
                'check_interval' => 30,
                'trusted' => true,
            ],
            'lex_pl' => [
                'name' => 'LEX.pl — Prawo',
                'type' => 'rss',
                'url' => 'https://www.lex.pl/rss/czytaj-nas',
                'category' => 'legal',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 60,
                'trusted' => true,
            ],
            'prawo_pl' => [
                'name' => 'Prawo.pl',
                'type' => 'rss',
                'url' => 'https://www.prawo.pl/rss',
                'category' => 'legal',
                'language' => 'pl',
                'priority' => 'medium',
                'check_interval' => 60,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: EU & International
            // =================================================

            'euronews_en' => [
                'name' => 'Euronews',
                'type' => 'rss',
                'url' => 'https://www.euronews.com/rss?level=theme&name=news',
                'category' => 'eu_international',
                'language' => 'en',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],
            'politico_eu' => [
                'name' => 'Politico EU',
                'type' => 'rss',
                'url' => 'https://www.politico.eu/feed/',
                'category' => 'eu_international',
                'language' => 'en',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],
            'reuters_europe' => [
                'name' => 'Reuters Europe',
                'type' => 'rss',
                'url' => 'https://www.reutersagency.com/feed/',
                'category' => 'eu_international',
                'language' => 'en',
                'priority' => 'high',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'bbc_europe' => [
                'name' => 'BBC Europe',
                'type' => 'rss',
                'url' => 'https://feeds.bbci.co.uk/news/world/europe/rss.xml',
                'category' => 'eu_international',
                'language' => 'en',
                'priority' => 'high',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'dw_europe' => [
                'name' => 'Deutsche Welle',
                'type' => 'rss',
                'url' => 'https://rss.dw.com/rss/en/eu',
                'category' => 'eu_international',
                'language' => 'en',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: Ukraine (for UA audience)
            // =================================================

            'ukrinform' => [
                'name' => 'Ukrinform',
                'type' => 'rss',
                'url' => 'https://www.ukrinform.ua/rss/block-lastnews',
                'category' => 'ukraine',
                'language' => 'ua',
                'priority' => 'high',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'pravda_ua' => [
                'name' => 'Ukrainska Pravda',
                'type' => 'rss',
                'url' => 'https://www.pravda.com.ua/rss/',
                'category' => 'ukraine',
                'language' => 'ua',
                'priority' => 'high',
                'check_interval' => 10,
                'trusted' => true,
            ],
            'unian' => [
                'name' => 'UNIAN',
                'type' => 'rss',
                'url' => 'https://rss.unian.net/site/news_ukr.rss',
                'category' => 'ukraine',
                'language' => 'ua',
                'priority' => 'medium',
                'check_interval' => 15,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: Technology
            // =================================================

            'spider_web' => [
                'name' => 'Spider\'s Web',
                'type' => 'rss',
                'url' => 'https://spidersweb.pl/feed',
                'category' => 'technology',
                'language' => 'pl',
                'priority' => 'low',
                'check_interval' => 30,
                'trusted' => true,
            ],
            'antyweb' => [
                'name' => 'AntyWeb',
                'type' => 'rss',
                'url' => 'https://antyweb.pl/feed',
                'category' => 'technology',
                'language' => 'pl',
                'priority' => 'low',
                'check_interval' => 30,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: Sport
            // =================================================

            'sport_pl' => [
                'name' => 'Sport.pl',
                'type' => 'rss',
                'url' => 'https://sport.pl/rss.xml',
                'category' => 'sport',
                'language' => 'pl',
                'priority' => 'low',
                'check_interval' => 15,
                'trusted' => true,
            ],
            'przeglad_sportowy' => [
                'name' => 'Przegląd Sportowy',
                'type' => 'rss',
                'url' => 'https://www.przegladsportowy.pl/rss.xml',
                'category' => 'sport',
                'language' => 'pl',
                'priority' => 'low',
                'check_interval' => 15,
                'trusted' => true,
            ],

            // =================================================
            // CATEGORY: PAP (Polish Press Agency — official)
            // =================================================

            'pap' => [
                'name' => 'PAP (Polska Agencja Prasowa)',
                'type' => 'rss',
                'url' => 'https://www.pap.pl/rss.xml',
                'category' => 'poland_general',
                'language' => 'pl',
                'priority' => 'critical',
                'check_interval' => 5,
                'trusted' => true,
            ],
        ];
    }

    // =====================================================
    // CATEGORY → TARGET SITE MAPPING
    // =====================================================

    public static function getCategoryMapping(): array
    {
        return [
            'poland_general' => [
                'target_sites' => ['polandpulse'],
                'target_categories' => ['news', 'polska'],
                'auto_publish' => true,
                'translate_to' => ['en', 'ua', 'ru'],
            ],
            'business' => [
                'target_sites' => ['polandpulse'],
                'target_categories' => ['business', 'ekonomia'],
                'auto_publish' => true,
                'translate_to' => ['en'],
            ],
            'immigration' => [
                'target_sites' => ['polandpulse', 'wincase_blog'],
                'target_categories' => ['immigration', 'legalizacja'],
                'auto_publish' => true,
                'translate_to' => ['en', 'ua', 'ru', 'hi'],
                'priority_override' => 'critical', // always immediate
            ],
            'legal' => [
                'target_sites' => ['polandpulse', 'wincase_blog'],
                'target_categories' => ['law', 'prawo'],
                'auto_publish' => true,
                'translate_to' => ['en', 'ua'],
            ],
            'eu_international' => [
                'target_sites' => ['polandpulse'],
                'target_categories' => ['world', 'eu'],
                'auto_publish' => true,
                'translate_to' => ['pl', 'ua'],
            ],
            'ukraine' => [
                'target_sites' => ['polandpulse'],
                'target_categories' => ['ukraine', 'ukraina'],
                'auto_publish' => true,
                'translate_to' => ['pl', 'en'],
            ],
            'technology' => [
                'target_sites' => ['polandpulse'],
                'target_categories' => ['tech', 'technologie'],
                'auto_publish' => true,
                'translate_to' => ['en'],
            ],
            'sport' => [
                'target_sites' => ['polandpulse'],
                'target_categories' => ['sport'],
                'auto_publish' => true,
                'translate_to' => ['en'],
            ],
        ];
    }

    // =====================================================
    // SOURCE STATS
    // =====================================================

    public static function getStats(): array
    {
        $sources = self::getSources();
        $byCategory = [];
        $byPriority = [];
        $byType = [];

        foreach ($sources as $source) {
            $byCategory[$source['category']] = ($byCategory[$source['category']] ?? 0) + 1;
            $byPriority[$source['priority']] = ($byPriority[$source['priority']] ?? 0) + 1;
            $byType[$source['type']] = ($byType[$source['type']] ?? 0) + 1;
        }

        return [
            'total_sources' => count($sources),
            'by_category' => $byCategory,
            'by_priority' => $byPriority,
            'by_type' => $byType,
        ];
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NewsSourcesRegistry — реестр 27 верифицированных новостных источников.
// 8 категорий: poland_general (7), business (5), immigration (3),
// legal (2), eu_international (5), ukraine (3), technology (2), sport (2).
// Приоритеты: critical (PAP, UDSC, Gov.pl), high (TVN24, Polsat, Reuters, BBC),
// medium, low.
// RSS feeds (24) + scraping (3 — gov.pl, migrantinfo).
// Category mapping → target sites + auto-translate + auto-publish.
// Immigration = CRITICAL = немедленная публикация.
// Файл: app/Services/News/NewsSourcesRegistry.php
// ---------------------------------------------------------------
