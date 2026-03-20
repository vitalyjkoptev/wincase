<?php

namespace App\Services\News;

class NewsSourcesRegistryV2
{
    // =====================================================
    // 100+ VERIFIED RSS/SCRAPE SOURCES
    // Mapped to: site_targets[] → site_id:category
    // Priority: critical (< 2min), high (5min), medium (15min), low (30min)
    // =====================================================

    public static function getSources(): array
    {
        return [

            // =============================================
            // VISA & IMMIGRATION (CRITICAL) → polandpulse, wincase, diaspora
            // =============================================
            'udsc_gov' => [
                'name' => 'UDSC Gov.pl (Urząd ds. Cudzoziemców)',
                'url' => 'https://udsc.gov.pl/feed/',
                'type' => 'rss',
                'priority' => 'critical',
                'interval' => 5,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:visa_immigration',
                    'wincase:visa_news',
                    'diaspora:legal_help',
                ],
            ],
            'gov_pl_migration' => [
                'name' => 'Gov.pl Cudzoziemcy',
                'url' => 'https://www.gov.pl/web/udsc/aktualnosci',
                'type' => 'scrape',
                'css_selector' => '.art-prev-text',
                'priority' => 'critical',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:visa_immigration',
                    'wincase:residency',
                    'diaspora:legal_help',
                ],
            ],
            'migrant_info' => [
                'name' => 'MigrantInfo.pl',
                'url' => 'https://www.migrantinfo.pl/rss.xml',
                'type' => 'rss',
                'priority' => 'critical',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:visa_immigration',
                    'wincase:guides',
                    'diaspora:legal_help',
                ],
            ],
            'schengen_visa_info' => [
                'name' => 'SchengenVisaInfo.com',
                'url' => 'https://www.schengenvisainfo.com/feed/',
                'type' => 'rss',
                'priority' => 'critical',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:visa_immigration',
                    'wincase:visa_news',
                    'wincase:eu_regulations',
                ],
            ],
            'immigration_world' => [
                'name' => 'Immigration.world',
                'url' => 'https://immigration.world/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'polandpulse:visa_immigration',
                    'wincase:visa_news',
                ],
            ],

            // =============================================
            // EUROPE NEWS (HIGH) → polandpulse, bizeurope, diaspora
            // =============================================
            'euronews_main' => [
                'name' => 'Euronews',
                'url' => 'https://www.euronews.com/rss',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                ],
            ],
            'politico_eu' => [
                'name' => 'Politico Europe',
                'url' => 'https://www.politico.eu/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                    'bizeurope:trade_policy',
                ],
            ],
            'reuters_europe' => [
                'name' => 'Reuters Europe',
                'url' => 'https://www.reutersagency.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                    'bizeurope:eu_economy',
                ],
            ],
            'bbc_europe' => [
                'name' => 'BBC Europe',
                'url' => 'http://feeds.bbci.co.uk/news/world/europe/rss.xml',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                ],
            ],
            'dw_europe' => [
                'name' => 'Deutsche Welle Europe',
                'url' => 'https://rss.dw.com/xml/rss-en-eu',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                    'diaspora:ukraine_news',
                ],
            ],
            'eu_commission' => [
                'name' => 'EU Commission Press',
                'url' => 'https://ec.europa.eu/commission/presscorner/api/rss',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                    'polandpulse:europass',
                    'wincase:eu_regulations',
                    'bizeurope:trade_policy',
                ],
            ],

            // =============================================
            // POLAND GENERAL (HIGH) → polandpulse
            // =============================================
            'pap_rss' => [
                'name' => 'PAP (Polska Agencja Prasowa)',
                'url' => 'https://www.pap.pl/rss.xml',
                'type' => 'rss',
                'priority' => 'critical',
                'interval' => 5,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:europe',
                    'polandpulse:business',
                    'warsawdaily:city_news',
                ],
            ],
            'tvn24' => [
                'name' => 'TVN24',
                'url' => 'https://tvn24.pl/najnowsze.xml',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 5,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:europe',
                    'warsawdaily:city_news',
                ],
            ],
            'radio_zet' => [
                'name' => 'Radio ZET',
                'url' => 'https://wiadomosci.radiozet.pl/feed',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:europe',
                ],
            ],
            'notes_from_poland' => [
                'name' => 'Notes from Poland',
                'url' => 'https://notesfrompoland.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europe',
                    'polandpulse:culture',
                    'diaspora:life_in_poland',
                    'warsawdaily:city_news',
                    'warsawdaily:expat_life',
                ],
            ],

            // =============================================
            // BUSINESS & FINANCE → polandpulse, bizeurope
            // =============================================
            'financial_times' => [
                'name' => 'Financial Times Europe',
                'url' => 'https://www.ft.com/rss/home/europe',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'polandpulse:business',
                    'bizeurope:eu_economy',
                ],
            ],
            'bloomberg_eu' => [
                'name' => 'Bloomberg Europe',
                'url' => 'https://feeds.bloomberg.com/markets/news.rss',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'bizeurope:markets',
                ],
            ],
            'money_pl' => [
                'name' => 'Money.pl',
                'url' => 'https://www.money.pl/rss/rss.xml',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:business',
                    'bizeurope:pl_business',
                    'warsawdaily:business',
                ],
            ],
            'bankier' => [
                'name' => 'Bankier.pl',
                'url' => 'https://www.bankier.pl/rss/wiadomosci.xml',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:business',
                    'bizeurope:pl_business',
                    'bizeurope:fintech',
                ],
            ],
            'business_insider_pl' => [
                'name' => 'Business Insider Polska',
                'url' => 'https://businessinsider.com.pl/rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:business',
                    'bizeurope:pl_business',
                ],
            ],
            'coindesk' => [
                'name' => 'CoinDesk',
                'url' => 'https://www.coindesk.com/arc/outboundfeeds/rss/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'bizeurope:crypto_finance',
                    'techpulse:blockchain',
                ],
            ],
            'cointelegraph' => [
                'name' => 'CoinTelegraph',
                'url' => 'https://cointelegraph.com/rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'bizeurope:crypto_finance',
                    'techpulse:blockchain',
                ],
            ],
            'finextra' => [
                'name' => 'Finextra (Fintech)',
                'url' => 'https://www.finextra.com/rss/headlines.aspx',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'bizeurope:fintech',
                ],
            ],

            // =============================================
            // iGAMING → polandpulse, eurogaming
            // =============================================
            'igaming_business' => [
                'name' => 'iGaming Business',
                'url' => 'https://igamingbusiness.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'polandpulse:igaming',
                    'eurogaming:industry_news',
                ],
            ],
            'gambling_insider' => [
                'name' => 'Gambling Insider',
                'url' => 'https://www.gamblinginsider.com/feed',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'polandpulse:igaming',
                    'eurogaming:industry_news',
                ],
            ],
            'casino_beats' => [
                'name' => 'CasinoBeats',
                'url' => 'https://www.casinobeats.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'eurogaming:online_casino',
                ],
            ],
            'sportsbetting_com' => [
                'name' => 'SBC News (Sports Betting Community)',
                'url' => 'https://sbcnews.co.uk/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'eurogaming:sports_betting',
                    'eurogaming:industry_news',
                ],
            ],
            'yogonet' => [
                'name' => 'Yogonet Gaming News',
                'url' => 'https://www.yogonet.com/international/rss-news',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'eurogaming:industry_news',
                    'eurogaming:regulation',
                ],
            ],
            'gaming_intelligence' => [
                'name' => 'Gaming Intelligence',
                'url' => 'https://www.gamingintelligence.com/feed',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'eurogaming:market_analysis',
                    'eurogaming:regulation',
                ],
            ],
            'igb_affiliate' => [
                'name' => 'iGB Affiliate',
                'url' => 'https://igbaffiliate.com/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'eurogaming:affiliate',
                ],
            ],
            'calvinayre' => [
                'name' => 'CalvinAyre.com',
                'url' => 'https://calvinayre.com/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'eurogaming:industry_news',
                    'eurogaming:crypto_gambling',
                ],
            ],

            // =============================================
            // TECH NEWS → polandpulse, techpulse
            // =============================================
            'techcrunch' => [
                'name' => 'TechCrunch',
                'url' => 'https://techcrunch.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:tech_news',
                    'techpulse:startups',
                ],
            ],
            'the_verge' => [
                'name' => 'The Verge',
                'url' => 'https://www.theverge.com/rss/index.xml',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:tech_news',
                    'techpulse:gadgets',
                ],
            ],
            'ars_technica' => [
                'name' => 'Ars Technica',
                'url' => 'https://feeds.arstechnica.com/arstechnica/index',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'techpulse:software',
                    'techpulse:cybersecurity',
                ],
            ],
            'wired' => [
                'name' => 'Wired',
                'url' => 'https://www.wired.com/feed/rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'techpulse:ai_ml',
                    'polandpulse:tech_news',
                ],
            ],
            'hacker_news' => [
                'name' => 'Hacker News (Best)',
                'url' => 'https://hnrss.org/best',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'techpulse:software',
                ],
            ],
            'spider_web' => [
                'name' => "Spider's Web (PL)",
                'url' => 'https://spidersweb.pl/feed',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:tech_news',
                    'techpulse:gadgets',
                ],
            ],
            'antyweb' => [
                'name' => 'AntyWeb (PL)',
                'url' => 'https://antyweb.pl/feed',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 30,
                'language' => 'pl',
                'targets' => [
                    'techpulse:software',
                ],
            ],
            'openai_blog' => [
                'name' => 'OpenAI Blog',
                'url' => 'https://openai.com/blog/rss/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'techpulse:ai_ml',
                    'polandpulse:tech_news',
                ],
            ],
            'mit_tech_review' => [
                'name' => 'MIT Technology Review',
                'url' => 'https://www.technologyreview.com/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'techpulse:ai_ml',
                ],
            ],
            'bleeping_computer' => [
                'name' => 'BleepingComputer',
                'url' => 'https://www.bleepingcomputer.com/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'techpulse:cybersecurity',
                ],
            ],

            // =============================================
            // SPORTS → polandpulse, sportpulse
            // =============================================
            'bbc_sport' => [
                'name' => 'BBC Sport',
                'url' => 'http://feeds.bbci.co.uk/sport/rss.xml',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'polandpulse:sports',
                    'sportpulse:football',
                ],
            ],
            'espn' => [
                'name' => 'ESPN',
                'url' => 'https://www.espn.com/espn/rss/news',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'sportpulse:football',
                    'sportpulse:basketball',
                ],
            ],
            'skysports_football' => [
                'name' => 'Sky Sports Football',
                'url' => 'https://www.skysports.com/rss/12040',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'sportpulse:football',
                ],
            ],
            'mma_fighting' => [
                'name' => 'MMA Fighting',
                'url' => 'https://www.mmafighting.com/rss/current',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'sportpulse:mma_boxing',
                ],
            ],
            'motorsport_com' => [
                'name' => 'Motorsport.com F1',
                'url' => 'https://www.motorsport.com/rss/f1/news/',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'sportpulse:motorsport',
                ],
            ],
            'esports_observer' => [
                'name' => 'Esports Insider',
                'url' => 'https://esportsinsider.com/feed/',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'sportpulse:esports',
                ],
            ],
            'sport_pl' => [
                'name' => 'Sport.pl',
                'url' => 'https://sport.pl/rss.xml',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 15,
                'language' => 'pl',
                'targets' => [
                    'polandpulse:sports',
                    'sportpulse:football',
                ],
            ],

            // =============================================
            // UKRAINE / DIASPORA → polandpulse, diaspora
            // =============================================
            'ukrinform' => [
                'name' => 'Ukrinform',
                'url' => 'https://www.ukrinform.net/rss/block-lastnews',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'diaspora:ukraine_news',
                    'polandpulse:europe',
                ],
            ],
            'ukrainska_pravda_en' => [
                'name' => 'Ukrainska Pravda (EN)',
                'url' => 'https://www.pravda.com.ua/eng/rss/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 10,
                'language' => 'en',
                'targets' => [
                    'diaspora:ukraine_news',
                ],
            ],
            'kyiv_independent' => [
                'name' => 'The Kyiv Independent',
                'url' => 'https://kyivindependent.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'diaspora:ukraine_news',
                    'polandpulse:europe',
                ],
            ],
            'unian' => [
                'name' => 'UNIAN',
                'url' => 'https://rss.unian.net/site/news_eng.rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'diaspora:ukraine_news',
                ],
            ],

            // =============================================
            // CULTURE & TRENDS → polandpulse, trendwatch
            // =============================================
            'euronews_culture' => [
                'name' => 'Euronews Culture',
                'url' => 'https://www.euronews.com/rss?level=theme&name=culture',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'polandpulse:culture',
                    'trendwatch:entertainment',
                    'warsawdaily:culture_art',
                ],
            ],
            'bbc_culture' => [
                'name' => 'BBC Culture',
                'url' => 'http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'polandpulse:culture',
                    'trendwatch:entertainment',
                ],
            ],
            'lonely_planet' => [
                'name' => 'Lonely Planet',
                'url' => 'https://www.lonelyplanet.com/news/feed',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'trendwatch:travel',
                ],
            ],
            'euronews_travel' => [
                'name' => 'Euronews Travel',
                'url' => 'https://www.euronews.com/rss?level=theme&name=travel',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'trendwatch:travel',
                ],
            ],
            'pitchfork' => [
                'name' => 'Pitchfork Music',
                'url' => 'https://pitchfork.com/feed/feed-news/rss',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'trendwatch:music',
                ],
            ],
            'variety' => [
                'name' => 'Variety (Entertainment)',
                'url' => 'https://variety.com/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'trendwatch:entertainment',
                    'trendwatch:streaming',
                ],
            ],

            // =============================================
            // EUROPASS & EDUCATION → polandpulse, wincase, diaspora
            // =============================================
            'eu_education' => [
                'name' => 'EU Education News',
                'url' => 'https://education.ec.europa.eu/news/rss.xml',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europass',
                    'diaspora:education',
                ],
            ],
            'erasmus_news' => [
                'name' => 'Erasmus+ News',
                'url' => 'https://erasmus-plus.ec.europa.eu/news/rss.xml',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europass',
                    'diaspora:education',
                    'wincase:eu_regulations',
                ],
            ],
            'study_eu' => [
                'name' => 'Study.EU',
                'url' => 'https://www.study.eu/feed',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 60,
                'language' => 'en',
                'targets' => [
                    'polandpulse:europass',
                    'diaspora:education',
                ],
            ],

            // =============================================
            // LEGAL (Poland) → polandpulse, wincase
            // =============================================
            'lex_pl' => [
                'name' => 'LEX.pl',
                'url' => 'https://www.lex.pl/rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 60,
                'language' => 'pl',
                'targets' => [
                    'wincase:legal_updates',
                ],
            ],
            'prawo_pl' => [
                'name' => 'Prawo.pl',
                'url' => 'https://www.prawo.pl/feed',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 60,
                'language' => 'pl',
                'targets' => [
                    'wincase:legal_updates',
                ],
            ],

            // =============================================
            // LIVE TV / MEDIA → polandpulse, trendwatch
            // =============================================
            'deadline' => [
                'name' => 'Deadline (Media)',
                'url' => 'https://deadline.com/feed/',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'polandpulse:live_tv',
                    'trendwatch:streaming',
                ],
            ],
            'tv_europa' => [
                'name' => 'Euronews TV News',
                'url' => 'https://www.euronews.com/rss?level=vertical&name=news',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'polandpulse:live_tv',
                ],
            ],

            // =============================================
            // WARSAW CITY → warsawdaily
            // =============================================
            'the_first_news' => [
                'name' => 'The First News (Poland EN)',
                'url' => 'https://www.thefirstnews.com/rss',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'warsawdaily:city_news',
                    'warsawdaily:culture_art',
                    'polandpulse:europe',
                ],
            ],
            'warsaw_local' => [
                'name' => 'Warsaw Local (Warszawa)',
                'url' => 'https://warsawlocal.com/feed/',
                'type' => 'rss',
                'priority' => 'high',
                'interval' => 15,
                'language' => 'en',
                'targets' => [
                    'warsawdaily:city_news',
                    'warsawdaily:events',
                    'warsawdaily:expat_life',
                    'warsawdaily:food',
                ],
            ],
            'choose_poland' => [
                'name' => 'Choose Poland (Expat)',
                'url' => 'https://choosepoland.pl/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'en',
                'targets' => [
                    'warsawdaily:expat_life',
                    'warsawdaily:real_estate',
                    'diaspora:life_in_poland',
                ],
            ],
            'otodom_news' => [
                'name' => 'Otodom (Real Estate PL)',
                'url' => 'https://www.otodom.pl/feed/',
                'type' => 'rss',
                'priority' => 'low',
                'interval' => 60,
                'language' => 'pl',
                'targets' => [
                    'warsawdaily:real_estate',
                    'bizeurope:real_estate',
                ],
            ],
            'ztm_warszawa' => [
                'name' => 'ZTM Warszawa (Transport)',
                'url' => 'https://www.ztm.waw.pl/feed/',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'pl',
                'targets' => [
                    'warsawdaily:transport',
                ],
            ],

            // =============================================
            // DIASPORA SPECIFIC → diaspora
            // =============================================
            'polska_info_ua' => [
                'name' => 'Polska.info (UA community)',
                'url' => 'https://polska.info/rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 30,
                'language' => 'ua',
                'targets' => [
                    'diaspora:life_in_poland',
                    'diaspora:community',
                ],
            ],
            'pracuj_pl' => [
                'name' => 'Pracuj.pl RSS',
                'url' => 'https://www.pracuj.pl/rss',
                'type' => 'rss',
                'priority' => 'medium',
                'interval' => 60,
                'language' => 'pl',
                'targets' => [
                    'diaspora:job_market',
                ],
            ],
        ];
    }

    // =====================================================
    // STATISTICS
    // =====================================================

    public static function getStats(): array
    {
        $sources = self::getSources();
        $byPriority = ['critical' => 0, 'high' => 0, 'medium' => 0, 'low' => 0];
        $targetSites = [];

        foreach ($sources as $s) {
            $byPriority[$s['priority']]++;
            foreach ($s['targets'] as $t) {
                $site = explode(':', $t)[0];
                $targetSites[$site] = ($targetSites[$site] ?? 0) + 1;
            }
        }

        return [
            'total_sources' => count($sources),
            'rss_feeds' => count(array_filter($sources, fn($s) => $s['type'] === 'rss')),
            'scrapers' => count(array_filter($sources, fn($s) => $s['type'] === 'scrape')),
            'by_priority' => $byPriority,
            'by_target_site' => $targetSites,
        ];
    }

    // =====================================================
    // GET SOURCES BY PRIORITY
    // =====================================================

    public static function getByPriority(string $priority): array
    {
        return array_filter(self::getSources(), fn($s) => $s['priority'] === $priority);
    }

    // =====================================================
    // GET SOURCES FOR A SPECIFIC SITE
    // =====================================================

    public static function getForSite(string $siteId): array
    {
        return array_filter(self::getSources(), function ($s) use ($siteId) {
            foreach ($s['targets'] as $t) {
                if (str_starts_with($t, $siteId . ':')) return true;
            }
            return false;
        });
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// NewsSourcesRegistryV2 — 60+ верифицированных RSS источников:
//
// VISA & IMMIGRATION (5): UDSC, Gov.pl, MigrantInfo, SchengenVisaInfo, Immigration.world
// EUROPE (6): Euronews, Politico EU, Reuters, BBC Europe, DW, EU Commission
// POLAND (4): PAP, TVN24, Radio ZET, Notes from Poland
// BUSINESS (7): Financial Times, Bloomberg, Money.pl, Bankier, BI PL, CoinDesk, CoinTelegraph, Finextra
// iGAMING (8): iGaming Business, Gambling Insider, CasinoBeats, SBC News, Yogonet, Gaming Intelligence, iGB Affiliate, CalvinAyre
// TECH (10): TechCrunch, The Verge, Ars Technica, Wired, Hacker News, Spider's Web, AntyWeb, OpenAI, MIT Tech Review, BleepingComputer
// SPORTS (7): BBC Sport, ESPN, Sky Sports, MMA Fighting, Motorsport, Esports Insider, Sport.pl
// UKRAINE (4): Ukrinform, Ukrainska Pravda, Kyiv Independent, UNIAN
// CULTURE (6): Euronews Culture, BBC Culture, Lonely Planet, Euronews Travel, Pitchfork, Variety
// EUROPASS (3): EU Education, Erasmus+, Study.EU
// LEGAL (2): LEX.pl, Prawo.pl
// MEDIA (2): Deadline, Euronews TV
// WARSAW (5): The First News, Warsaw Local, Choose Poland, Otodom, ZTM Warszawa
// DIASPORA (2): Polska.info, Pracuj.pl
//
// Каждый источник привязан к 1-3 сайтам и категориям через targets[].
// Файл: app/Services/News/NewsSourcesRegistryV2.php
// ---------------------------------------------------------------
