# WINCASE CRM v4.0 — News Pipeline (Phase 17)

## Full Automation: Parse → AI Rewrite → Publish → Live Feed

---

## Architecture

```
┌──────────────────────────────────────────────────────────────────────┐
│                         n8n WORKFLOWS                                │
│                                                                      │
│  W23 (5min)     W24 (15min)    W25 (10min)   W26 (5min)   W27(30m) │
│  Critical       High+Medium    AI Rewrite    Publisher    Low Prio   │
│  PAP,UDSC       TVN24,BBC      Claude/GPT    WP+Laravel   Sport,Tech│
│  Gov.pl         Reuters...     Plagiarism    Auto-post    Secondary  │
└──────┬──────────────┬──────────────┬──────────────┬──────────┬──────┘
       │              │              │              │          │
       ▼              ▼              ▼              ▼          ▼
┌──────────────────────────────────────────────────────────────────────┐
│                    Laravel 12 API (14 endpoints)                      │
│                                                                      │
│  NewsParserService    AIRewriterService    NewsPublisherService       │
│  ├─ RSS (24 feeds)    ├─ Claude Sonnet     ├─ WordPress REST API     │
│  ├─ Scraper (3 gov)   ├─ OpenAI fallback   ├─ Laravel API            │
│  ├─ Dedup (URL+85%)   ├─ Plagiarism 3-way  ├─ Image upload           │
│  └─ Full content      ├─ SEO generation    └─ Category mapping       │
│                       └─ 8-lang translate                            │
└──────────────────────────────┬───────────────────────────────────────┘
                               │
                    WebSocket (Reverb)
                    Channel: news-feed
                               │
                               ▼
┌──────────────────────────────────────────────────────────────────────┐
│              Vue.js Admin Panel — NewsLiveFeedView.vue                │
│                                                                      │
│  📡 Live Feed    📰 Articles    📊 Statistics    🔌 Sources          │
│  ┌──────────┐   ┌──────────┐   ┌──────────┐   ┌──────────┐         │
│  │ Real-time│   │ Filters  │   │ 7-day    │   │ 27 RSS   │         │
│  │ events   │   │ Approve  │   │ charts   │   │ feeds    │         │
│  │ chat     │   │ Reject   │   │ by cat   │   │ config   │         │
│  │ terminal │   │ Rewrite  │   │ by src   │   │          │         │
│  └──────────┘   └──────────┘   └──────────┘   └──────────┘         │
└──────────────────────────────────────────────────────────────────────┘
```

---

## News Sources — 27 Verified RSS/Scrape Channels

### Poland General (7 sources, check: 5-15 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| PAP (Polska Agencja Prasowa) | RSS | 🔴 Critical | PL |
| TVN24 | RSS | 🔴 High | PL |
| Polsat News | RSS | 🔴 High | PL |
| Onet.pl | RSS | 🟡 Medium | PL |
| Wirtualna Polska | RSS | 🟡 Medium | PL |
| Interia Fakty | RSS | 🟡 Medium | PL |
| Radio ZET | RSS | 🟡 Medium | PL |

### Immigration & Legal (5 sources, check: 15-60 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| UDSC (Urząd do Spraw Cudzoziemców) | Scrape | 🔴 CRITICAL | PL |
| Gov.pl — Cudzoziemcy | Scrape | 🔴 CRITICAL | PL |
| MigrantInfo.pl | Scrape | 🔴 High | PL |
| LEX.pl — Prawo | RSS | 🟡 Medium | PL |
| Prawo.pl | RSS | 🟡 Medium | PL |

### Business & Finance (5 sources, check: 15-30 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| Money.pl | RSS | 🟡 Medium | PL |
| Bankier.pl | RSS | 🟡 Medium | PL |
| Business Insider PL | RSS | 🟡 Medium | PL |
| Forsal.pl | RSS | 🟢 Low | PL |
| Puls Biznesu | RSS | 🟢 Low | PL |

### EU & International (5 sources, check: 10-15 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| Reuters Europe | RSS | 🔴 High | EN |
| BBC Europe | RSS | 🔴 High | EN |
| Euronews | RSS | 🟡 Medium | EN |
| Politico EU | RSS | 🟡 Medium | EN |
| Deutsche Welle | RSS | 🟡 Medium | EN |

### Ukraine (3 sources, check: 10-15 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| Ukrinform | RSS | 🔴 High | UA |
| Ukrainska Pravda | RSS | 🔴 High | UA |
| UNIAN | RSS | 🟡 Medium | UA |

### Technology (2 sources, check: 30 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| Spider's Web | RSS | 🟢 Low | PL |
| AntyWeb | RSS | 🟢 Low | PL |

### Sport (2 sources, check: 15 min)
| Source | Type | Priority | Language |
|--------|------|----------|----------|
| Sport.pl | RSS | 🟢 Low | PL |
| Przegląd Sportowy | RSS | 🟢 Low | PL |

---

## Publication Timeline (Priority-Based)

```
┌─────────────┬────────────────┬──────────────────────────────────────┐
│ Priority    │ End-to-End     │ Sources                              │
├─────────────┼────────────────┼──────────────────────────────────────┤
│ 🔴 Critical │ < 2 minutes    │ PAP, UDSC, Gov.pl (immigration)     │
│ 🟠 High     │ < 5 minutes    │ TVN24, Polsat, Reuters, BBC, UA     │
│ 🟡 Medium   │ < 15 minutes   │ Onet, WP, Bankier, Euronews, DW    │
│ 🟢 Low      │ < 30 minutes   │ Sport, Tech, secondary business     │
└─────────────┴────────────────┴──────────────────────────────────────┘
```

---

## AI Rewriter Pipeline

```
Source Article
     │
     ▼
┌─────────────────────────────┐
│ 1. Fetch Full Content       │ ← DOM parsing, 7 CSS selectors
│    (if description only)    │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│ 2. AI Rewrite               │ ← Claude Sonnet (primary)
│    - 100% unique text       │    OpenAI GPT-4o (fallback)
│    - Keep facts/dates/names │
│    - Change structure       │
│    - Generate SEO meta      │
│    - JSON output            │
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│ 3. Plagiarism Check         │ ← 3-method weighted score:
│    - 5-word N-gram (50%)    │    N-gram overlap 50%
│    - similar_text (20%)     │    Similarity 20%
│    - Word overlap (30%)     │    Word overlap 30%
│                             │
│    Score < 15% → AUTO-PUBLISH
│    Score ≥ 15% → NEEDS REVIEW
└──────────────┬──────────────┘
               ▼
┌─────────────────────────────┐
│ 4. Translate (optional)     │ ← Same AI pipeline
│    PL↔EN↔UA↔RU↔HI          │    Rewrite + translate simultaneously
│    Creates child articles   │
└─────────────────────────────┘
```

---

## Category → Target Site Mapping

| Category | Target Sites | Auto-Translate | Auto-Publish |
|----------|-------------|----------------|--------------|
| poland_general | polandpulse.news | EN, UA, RU | ✅ |
| business | polandpulse.news | EN | ✅ |
| immigration | polandpulse + wincase.pro | EN, UA, RU, HI | ✅ (CRITICAL) |
| legal | polandpulse + wincase.pro | EN, UA | ✅ |
| eu_international | polandpulse.news | PL, UA | ✅ |
| ukraine | polandpulse.news | PL, EN | ✅ |
| technology | polandpulse.news | EN | ✅ |
| sport | polandpulse.news | EN | ✅ |

---

## n8n Workflows (W23-W27)

| ID | Name | Trigger | Frequency | Sources |
|----|------|---------|-----------|---------|
| W23 | Critical Parser | Cron | 5 min | PAP, UDSC, Gov.pl → parse → rewrite → publish |
| W24 | High+Medium Parser | Cron | 15 min | TVN24, Polsat, Reuters, BBC, Onet, WP... |
| W25 | AI Rewriter Batch | Cron+Event | 10 min | parsed → Claude/OpenAI → plagiarism |
| W26 | Auto Publisher | Cron | 5 min | rewritten → WordPress/Laravel API |
| W27 | Low Priority Parser | Cron | 30 min | Sport.pl, AntyWeb, Forsal... |

---

## API Endpoints (14)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /news/sources | All 27 sources + stats + mappings |
| GET | /news/articles | List with filters (status/category/source/priority) |
| GET | /news/articles/:id | Article detail + publish logs + translations |
| POST | /news/parse | Trigger parsing (optional: ?priority=critical) |
| POST | /news/parse/:sourceKey | Parse single source |
| POST | /news/rewrite/:id | AI rewrite single article |
| POST | /news/rewrite-batch | Batch rewrite (10 articles) |
| POST | /news/translate/:id | Translate to target language |
| POST | /news/publish | Auto-publish all ready articles |
| POST | /news/articles/:id/approve | Manual approve for publishing |
| POST | /news/articles/:id/reject | Reject article |
| GET | /news/statistics | Dashboard stats (N days) |
| GET | /news/schedule | Publish schedule config |
| GET | /news/feed | Live feed history (last N events) |

---

## Database Tables (3)

### news_articles (30+ columns)
- Source: source_key, source_name, source_url (unique)
- Original: original_title, original_content, original_description, original_language
- Rewritten: rewritten_title, rewritten_content, rewritten_description, rewritten_language
- SEO: seo_meta_title, seo_meta_description, seo_keywords, seo_slug
- Quality: plagiarism_score (0-100)
- Status: parsed → rewritten → published (9 statuses)
- Publishing: published_to (JSON array), published_urls (JSON array)
- Relations: parent_article_id (for translations)

### news_publish_logs
- news_article_id, target_site, status (success/failed), published_url

### news_feed_logs
- event_type, article_id, source, target_site, title, language, message (JSON)

---

## Vue.js Admin Panel — News Pipeline Page

### Live Feed Tab (📡)
- Dark terminal theme (bg-gray-900, monospace font)
- Real-time WebSocket events via Reverb channel `news-feed`
- Fallback: polling every 5 seconds
- Event types with icons and colors:
  - 📥 **parsed** (blue) — "Parsed from TVN24: title..."
  - ✍️ **rewritten** (green) — "Rewritten [PL] plagiarism: 3.2%: title..."
  - 🌐 **translated** (purple) — "Translated PL→EN: title..."
  - 🚀 **published** (emerald) — "Published → polandpulse.news [EN]: title..."
  - ❌ **error** (red) — "Error [source]: message"
- Max 200 events in memory, timeAgo display

### Articles Tab (📰)
- Filterable table: status, category, priority
- Columns: title, source, category, status badge, plagiarism %, actions
- Approve/Reject buttons for needs_review articles
- Priority indicators: 🔴🟠🟡🟢

### Statistics Tab (📊)
- KPI cards: total parsed, published, needs_review, avg plagiarism
- By Category breakdown
- Top Sources ranking

### Action Buttons
- 🔴 Parse Critical — immediate UDSC/PAP/Gov.pl check
- Parse All — trigger full parsing cycle
- ✍️ Rewrite Batch — process 10 pending articles
- 🚀 Publish — publish all ready articles

---

## Files Created (Phase 17)

```
news-module/
├── services/
│   ├── NewsSourcesRegistry.php    # 27 sources, 8 categories, mappings
│   ├── NewsParserService.php      # RSS parser + web scraper + dedup
│   ├── AIRewriterService.php      # Claude/OpenAI rewrite + plagiarism
│   └── NewsPublisherService.php   # WP REST API + Laravel API publisher
├── controllers/
│   └── NewsController.php         # 14 endpoints + NewsFeedEvent
├── routes/
│   └── api_news_routes.php        # 14 routes + 3 models
├── migrations/
│   └── create_news_tables.php     # 3 tables (articles, publish_logs, feed_logs)
├── n8n/
│   └── news_workflows.php         # W23-W27 automation workflows
├── vue/
│   └── NewsLiveFeedView.vue       # Admin panel: live feed + articles + stats
└── NEWS_PIPELINE_MODULE.md        # This documentation
```

<!--
Аннотация (RU):
Phase 17 — News Pipeline: полная автоматизация парсинга → AI рерайтинга → публикации.
27 верифицированных RSS/Scrape источников (8 категорий).
AI рерайтинг через Claude Sonnet / OpenAI GPT-4o с проверкой плагиата (3 метода).
Автопубликация на WordPress + Laravel сайты.
5 n8n workflows (W23-W27) для полной автоматизации.
Live Feed в Vue.js админке через WebSocket (Reverb).
14 API endpoints, 3 таблицы БД.
Файл: docs/NEWS_PIPELINE_MODULE.md
-->
