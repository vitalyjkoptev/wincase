# WINCASE CRM v4.0 — SEO Module (Phase 6)
## Laravel 12 + PHP 8.4

---

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                   SeoOrchestrationService                        │
│  (overview, keywords, backlinks, network, reviews, brand, sync)  │
└──────┬──────────┬──────────┬──────────────┬─────────────────────┘
       │          │          │              │
       ▼          ▼          ▼              ▼
 ┌──────────┐┌────────┐┌──────────┐┌──────────────┐
 │   GSC    ││  GA4   ││ Ahrefs   ││ SeoNetwork   │
 │ Service  ││Service ││ Service  ││ Service      │
 └────┬─────┘└───┬────┘└────┬─────┘└──────┬───────┘
      │          │          │             │
      ▼          ▼          ▼             ▼
  GSC API     GA4 Data   Ahrefs API   sitemap.xml
  (4 domains) API (4)    v3            (8 sites)
      │          │          │             │
      └──────────┴──────────┴─────────────┘
                        │
                        ▼
               ┌──────────────────┐
               │    seo_data      │ ← UPSERT (domain+date+source)
               │ seo_network_sites│
               │ reviews          │
               │ brand_listings   │
               └──────────────────┘
```

---

## Data Sources & Sync Schedule

| Source | API | Domains | Frequency | n8n |
|--------|-----|---------|-----------|-----|
| GSC | Search Console API | 4 | Daily 6:00 | W08 |
| GA4 | Analytics Data API v1beta | 4 | Daily 6:00 | W08 |
| Ahrefs | Ahrefs API v3 | 4 + 8 network | Weekly Mon 8:00 | W09 |
| Network | sitemap.xml check | 8 satellites | Weekly | W20 |
| Reviews | Google/Trustpilot/FB/GoWork | — | Every 2h | W10 |

---

## 4 Domains Monitored

| Domain | GSC Property | GA4 Property |
|--------|-------------|--------------|
| wincase.pro | sc-domain:wincase.pro | GA4_PROPERTY_WINCASE_PRO |
| wincase-legalization.com | sc-domain:wincase-legalization.com | GA4_PROPERTY_LEGALIZATION |
| wincase-job.com | sc-domain:wincase-job.com | GA4_PROPERTY_JOB |
| wincase.org | sc-domain:wincase.org | GA4_PROPERTY_ORG |

---

## Files Created (Phase 6)

```
seo-module/
├── services/
│   ├── GscService.php                 # Google Search Console — sync, keywords, pages
│   ├── GA4Service.php                 # Google Analytics 4 — sync, traffic, landings
│   ├── AhrefsService.php             # Domain Rating, backlinks, new/lost
│   ├── SeoNetworkService.php          # 8 satellite sites management
│   └── SeoOrchestrationService.php    # Unified: overview, dashboard, sync
├── controllers/
│   └── SeoController.php             # 6 API endpoints
├── routes/
│   └── api_seo_routes.php            # 6 routes (auth:sanctum)
└── SEO_MODULE.md                      # This documentation
```

---

## API Endpoints (6)

| Method | Endpoint | Query Params | Description |
|--------|----------|-------------|-------------|
| `GET` | `/api/v1/seo/overview` | `?date_from=&date_to=` | All 4 domains: GSC + GA4 + DA |
| `GET` | `/api/v1/seo/keywords` | `?domain=&limit=&date_from=&date_to=` | Top keywords from GSC |
| `GET` | `/api/v1/seo/network` | — | 8 satellite sites + stats |
| `GET` | `/api/v1/seo/backlinks` | `?domain=&days=` | DA trend + new/lost backlinks |
| `GET` | `/api/v1/seo/reviews` | — | Reviews aggregation (all platforms) |
| `GET` | `/api/v1/seo/brand` | — | NAP consistency + listing status |

---

## Metrics Collected

### GSC (per domain, daily)
clicks, impressions, avg_position, CTR → `seo_data` (source='gsc')

### GA4 (per domain, daily)
users, sessions, conversions, new_users, bounce_rate, avg_session_duration → `seo_data` (source='ga4')

### Ahrefs (per domain, weekly)
domain_authority (DR), backlinks, referring_domains, new/lost → `seo_data` (source='ahrefs')

### SEO Network (8 sites)
domain, DA, articles_total, articles_with_backlink, last_article_at → `seo_network_sites`

---

## Installation

```bash
# Copy files
cp services/*.php backend/app/Services/SEO/
cp controllers/SeoController.php backend/app/Http/Controllers/Api/V1/

# Append routes
cat routes/api_seo_routes.php >> backend/routes/api.php

# Add GA4 properties to .env
GA4_PROPERTY_WINCASE_PRO=123456789
GA4_PROPERTY_LEGALIZATION=234567890
GA4_PROPERTY_JOB=345678901
GA4_PROPERTY_ORG=456789012

# Add Ahrefs key
AHREFS_API_KEY=your_ahrefs_api_key

# Verify
php artisan route:list --path=seo
```

<!--
Аннотация (RU):
Модуль SEO WINCASE CRM v4.0.
4 домена: GSC (clicks, impressions, position) + GA4 (users, sessions, conversions)
+ Ahrefs (DA, backlinks, referring domains).
8 сателлитных сайтов: проверка sitemap, DA, статьи с бэклинками.
Reviews Hub: Google, Trustpilot, Facebook, GoWork.
Brand: NAP consistency 50+ каталогов.
8 файлов: 5 сервисов, 1 контроллер, 1 routes, 1 docs.
Файл: docs/SEO_MODULE.md
-->
