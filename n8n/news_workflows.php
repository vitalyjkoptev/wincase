<?php

// =====================================================
// FILE: n8n/news_workflows.php
// 5 new workflows for News Pipeline automation
// =====================================================

return [

    // =====================================================
    // W23: CRITICAL NEWS PARSER (immediate)
    // =====================================================
    'W23' => [
        'name' => 'News Parser — Critical (PAP, UDSC, Gov.pl)',
        'trigger' => 'Cron',
        'frequency' => 'Every 5 minutes',
        'module' => 'news',
        'priority' => 'critical',
        'description' => <<<'DESC'
Checks critical sources every 5 min:
1. Fetch RSS/scrape from PAP, UDSC, Gov.pl (immigration)
2. Check for duplicates (URL + title similarity)
3. If NEW → save to DB (status: parsed)
4. IMMEDIATELY trigger W24 (rewrite) for critical articles
5. Broadcast to live feed WebSocket

API call: POST /api/v1/news/parse?priority=critical
DESC,
        'n8n_nodes' => [
            'Cron' => 'Every 5 min',
            'HTTP Request' => 'POST api/v1/news/parse (priority=critical)',
            'IF' => 'Check if new > 0',
            'HTTP Request 2' => 'POST api/v1/news/rewrite-batch (for critical)',
            'HTTP Request 3' => 'POST api/v1/news/publish',
            'Telegram' => 'Notify admin: "🔴 CRITICAL: N new articles from [source]"',
        ],
        'estimated_time' => '< 2 min end-to-end for critical news',
    ],

    // =====================================================
    // W24: HIGH/MEDIUM NEWS PARSER
    // =====================================================
    'W24' => [
        'name' => 'News Parser — High & Medium Sources',
        'trigger' => 'Cron',
        'frequency' => 'Every 15 minutes',
        'module' => 'news',
        'priority' => 'high+medium',
        'description' => <<<'DESC'
Checks high + medium priority sources every 15 min:
1. Parse RSS from TVN24, Polsat, Reuters, BBC, Euronews, etc.
2. Parse RSS from Onet, WP, Interia, Money.pl, Bankier, etc.
3. Deduplicate against existing articles
4. Save new articles (status: parsed)
5. Broadcast to live feed

API call: POST /api/v1/news/parse?priority=high
Then: POST /api/v1/news/parse?priority=medium
DESC,
        'n8n_nodes' => [
            'Cron' => 'Every 15 min',
            'HTTP Request 1' => 'POST parse (priority=high)',
            'HTTP Request 2' => 'POST parse (priority=medium)',
            'Merge' => 'Combine results',
            'IF' => 'new > 0 → trigger rewrite',
        ],
    ],

    // =====================================================
    // W25: AI REWRITER BATCH
    // =====================================================
    'W25' => [
        'name' => 'AI Rewriter — Batch Processing',
        'trigger' => 'Cron + Event',
        'frequency' => 'Every 10 minutes + on W23/W24 completion',
        'module' => 'news',
        'description' => <<<'DESC'
Processes parsed articles through AI rewriting:
1. Get articles with status=parsed (ordered by priority)
2. For each article:
   a. Fetch full content if needed
   b. Send to Claude/OpenAI API for unique rewrite
   c. Run plagiarism check (ngram + similarity + word overlap)
   d. If score < 15% → status: rewritten (ready for publish)
   e. If score >= 15% → status: needs_review
3. Broadcast progress to live feed
4. Queue translations for target languages

API call: POST /api/v1/news/rewrite-batch

Rate limiting: max 10 articles per batch (API cost control)
Estimated: 5-8 sec per article (Claude Sonnet)
DESC,
        'n8n_nodes' => [
            'Cron' => 'Every 10 min',
            'HTTP Request' => 'POST rewrite-batch',
            'IF' => 'success > 0',
            'Wait' => '5 seconds',
            'HTTP Request 2' => 'POST publish (publish ready articles)',
        ],
    ],

    // =====================================================
    // W26: AUTO PUBLISHER
    // =====================================================
    'W26' => [
        'name' => 'Auto Publisher — Rewritten → Sites',
        'trigger' => 'Cron',
        'frequency' => 'Every 5 minutes',
        'module' => 'news',
        'description' => <<<'DESC'
Publishes rewritten articles to target sites:
1. Get articles: status=rewritten, plagiarism<15%
2. Check category → target site mapping
3. Publish to WordPress (WP REST API) or Laravel API
4. Upload featured images
5. Queue translations (translate_to languages)
6. Log success/failure
7. Broadcast to live feed: "📰 Published: [title] → [site]"

Publish priority timeline:
- critical: within 2 min of parsing
- high: within 5 min
- medium: within 15 min
- low: within 30 min

API call: POST /api/v1/news/publish
DESC,
        'n8n_nodes' => [
            'Cron' => 'Every 5 min',
            'HTTP Request' => 'POST publish',
            'IF' => 'published > 0',
            'Telegram' => 'Summary: "Published N articles to [sites]"',
        ],
    ],

    // =====================================================
    // W27: LOW PRIORITY & SPORT/TECH
    // =====================================================
    'W27' => [
        'name' => 'News Parser — Low Priority (Sport, Tech)',
        'trigger' => 'Cron',
        'frequency' => 'Every 30 minutes',
        'module' => 'news',
        'priority' => 'low',
        'description' => <<<'DESC'
Parses low-priority sources every 30 min:
- Sport.pl, Przegląd Sportowy
- Spider's Web, AntyWeb
- Forsal, Puls Biznesu

API call: POST /api/v1/news/parse?priority=low
DESC,
        'n8n_nodes' => [
            'Cron' => 'Every 30 min',
            'HTTP Request' => 'POST parse (priority=low)',
        ],
    ],
];

// ---------------------------------------------------------------
// Аннотация (RU):
// 5 новых n8n workflows для News Pipeline:
// W23 — Critical parser (каждые 5 мин): PAP, UDSC, Gov.pl → parse → rewrite → publish (< 2 мин).
// W24 — High+Medium parser (каждые 15 мин): TVN24, Polsat, Reuters, BBC + Onet, WP, Bankier.
// W25 — AI Rewriter batch (каждые 10 мин): parsed → Claude/OpenAI → plagiarism check → rewritten.
// W26 — Auto Publisher (каждые 5 мин): rewritten → WordPress/Laravel API → sites.
// W27 — Low priority parser (каждые 30 мин): sport, tech, business secondary.
//
// End-to-end timeline: critical news < 2 min, high < 5 min, medium < 15 min, low < 30 min.
// Файл: n8n/news_workflows.php
// ---------------------------------------------------------------
