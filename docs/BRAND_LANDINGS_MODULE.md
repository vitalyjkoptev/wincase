# WINCASE CRM v4.0 — Brand & Landings Module (Phase 10-11)
## Laravel 12 + PHP 8.4

---

## Phase 10: Brand & Reputation

### Architecture
```
┌──────────────────────────────────────────────────────────────┐
│                  Brand & Reputation Module                     │
│                                                               │
│  ┌─────────────────────────┐  ┌────────────────────────────┐ │
│  │  BrandListingsService    │  │  ReviewsHubService          │ │
│  │  54 directories in       │  │  4 platforms:                │ │
│  │  10 groups               │  │  Google, Trustpilot,         │ │
│  │  NAP consistency check   │  │  Facebook, GoWork.pl         │ │
│  │  W16 — weekly Friday     │  │  W10 — every 2 hours         │ │
│  └─────────────────────────┘  └────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### 54 Directories in 10 Groups

| Group | Count | Examples |
|-------|-------|---------|
| Google | 2 | GBP, Google Maps |
| Social | 7 | Facebook, Instagram, LinkedIn, TikTok, YouTube, Pinterest, Threads |
| Reviews | 4 | Trustpilot, GoWork.pl, Opinie.pl, Firmy.net |
| Legal | 5 | Panorama Firm, KRS, CEIDG, Regon |
| Maps | 5 | Apple Maps, Bing Places, Waze, HERE, TomTom |
| PL Directories | 12 | Pkt.pl, Zumi.pl, Oferteo.pl, Aleo.com, etc. |
| INT Directories | 9 | Yelp, Foursquare, Hotfrog, Cylex, etc. |
| Legal Specific | 6 | Prawnik.pl, Kancelarie.pl, Adwokat.pl, etc. |
| Immigration | 5 | Expat.com, InterNations, MigrantInfo.pl |
| **Total** | **55** | |

### NAP Consistency
- **Canonical NAP:** WinCase — Biuro Imigracyjne, ul. Hoza 66/68 lok. 211, 00-682 Warszawa, +48 579 266 493
- **Check frequency:** Weekly (W16, Friday)
- **Score:** % of listings with matching NAP data

### Reviews Hub — 4 Platforms

| Platform | API | Sync | Features |
|----------|-----|------|----------|
| Google | Places API | W10 (2h) | Rating, text, author |
| Trustpilot | Business API | W10 (2h) | Stars, text, reply |
| Facebook | Graph API v19.0 | W10 (2h) | Rating/recommendation |
| GoWork.pl | n8n scraping | W10 (2h) | Scraped reviews |

### Brand API Endpoints (7)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/brand/overview` | 54 listings + NAP score |
| `GET` | `/api/v1/brand/listings` | Grouped listings (10 groups) |
| `POST` | `/api/v1/brand/nap-check` | Run NAP consistency check |
| `GET` | `/api/v1/brand/reviews` | Aggregated review stats |
| `GET` | `/api/v1/brand/reviews/list` | Filtered reviews list |
| `POST` | `/api/v1/brand/reviews/{id}/reply` | Save reply to review |
| `POST` | `/api/v1/brand/reviews/sync` | Manual sync all platforms |

---

## Phase 11: Landings Module

### Architecture
```
┌──────────────────────────────────────────────────────────────┐
│                     Landings Module                            │
│                                                               │
│  ┌─────────────────────────────────────────────────────────┐ │
│  │  4 Domains × Multiple Pages × Multiple Languages         │ │
│  │                                                           │ │
│  │  wincase-legalization.com  (5 pages × 5 langs = 25)      │ │
│  │  wincase-job.com           (4 pages × 5 langs = 20)      │ │
│  │  wincase.pro               (5 pages × 3 langs = 15)      │ │
│  │  wincase.org               (2 pages × 2 langs =  4)      │ │
│  │                                          Total: ~64       │ │
│  └─────────────────────────────────────────────────────────┘ │
│                                                               │
│  ┌──────────────┐  ┌──────────────┐  ┌───────────────────┐  │
│  │  A/B Testing  │  │  Visit Track  │  │  UTM Analytics     │  │
│  │  Variants     │  │  Device, IP   │  │  Source, Campaign   │  │
│  │  Traffic %    │  │  Referer      │  │  Conversion Rate    │  │
│  └──────────────┘  └──────────────┘  └───────────────────┘  │
└──────────────────────────────────────────────────────────────┘
```

### Landing Matrix

| Domain | Pages | Languages | Potential |
|--------|-------|-----------|-----------|
| wincase-legalization.com | main, karta-pobytu, zezwolenie-na-prace, obywatelstwo, blue-card | pl, en, ru, ua, hi | 25 |
| wincase-job.com | main, praca-w-polsce, work-permit, staffing | pl, en, ua, hi, tl | 20 |
| wincase.pro | home, about, services, contact, consultation | pl, en, ru | 15 |
| wincase.org | main, partners | pl, en | 4 |

### A/B Testing
- Multiple variants per landing (A, B, C...)
- Weighted random traffic split (traffic_pct %)
- Separate tracking: visits_count, conversions_count per variant
- Automatic CR calculation

### Visit Tracking
- UTM parameters: source, medium, campaign, content
- Device type: desktop, mobile, tablet
- Language detection
- Referer tracking

### Landings API Endpoints (8)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| `GET` | `/api/v1/landings` | Yes | List all landings |
| `GET` | `/api/v1/landings/:id` | Yes | Landing detail + variants |
| `POST` | `/api/v1/landings` | Yes | Create landing |
| `PUT` | `/api/v1/landings/:id` | Yes | Update landing |
| `POST` | `/api/v1/landings/:id/variants` | Yes | Create A/B variant |
| `POST` | `/api/v1/landings/track` | **No** | Record visit (public) |
| `POST` | `/api/v1/landings/convert` | **No** | Record conversion (public) |
| `GET` | `/api/v1/landings/analytics` | Yes | Full analytics |

---

## Database Tables (Phase 10-11)

| Table | Columns | Description |
|-------|---------|-------------|
| brand_listings | 14 | 54 directories, NAP data, consistency |
| reviews | 13 | Reviews from 4 platforms |
| landing_pages | 10 | Landing pages, 4 domains |
| landing_variants | 11 | A/B test variants |
| landing_visits | 10 | Visit tracking with UTM |

---

## Files Created (Phase 10-11)

```
brand-module/
├── services/
│   ├── BrandListingsService.php     # 54 directories, NAP check
│   └── ReviewsHubService.php        # 4 platforms review sync
├── controllers/
│   └── BrandController.php          # 7 API endpoints
├── routes/
│   └── api_brand_routes.php         # 7 routes
├── models/
│   └── BrandModels.php              # BrandListing + Review
├── migrations/
│   └── 2026_02_19_100001.php        # brand_listings + reviews
└── seeders/
    └── BrandListingsSeeder.php      # 54 directories seed

landings-module/
├── services/
│   └── LandingsService.php          # CRUD, A/B test, analytics
├── controllers/
│   └── LandingsController.php       # 8 API endpoints
├── routes/
│   └── api_landings_routes.php      # 8 routes (2 public)
├── models/
│   └── LandingModels.php            # 3 models
└── migrations/
    └── 2026_02_19_100002.php        # 3 tables
```

---

## Installation

```bash
# Brand Module
cp brand-module/services/*.php backend/app/Services/Brand/
cp brand-module/controllers/*.php backend/app/Http/Controllers/Api/V1/
cp brand-module/models/*.php backend/app/Models/  # split into separate files
cp brand-module/migrations/*.php backend/database/migrations/
cp brand-module/seeders/*.php backend/database/seeders/
cat brand-module/routes/api_brand_routes.php >> backend/routes/api.php

# Landings Module
cp landings-module/services/*.php backend/app/Services/Landings/
cp landings-module/controllers/*.php backend/app/Http/Controllers/Api/V1/
cp landings-module/models/*.php backend/app/Models/  # split into separate files
cp landings-module/migrations/*.php backend/database/migrations/
cat landings-module/routes/api_landings_routes.php >> backend/routes/api.php

# Run migrations + seeders
php artisan migrate
php artisan db:seed --class=BrandListingsSeeder

# Verify
php artisan route:list --path=brand
php artisan route:list --path=landings
```

<!--
Аннотация (RU):
Phase 10 — Brand & Reputation: 54 каталога (10 групп), NAP consistency check,
Reviews Hub (Google, Trustpilot, Facebook, GoWork). 7 API endpoints.
Phase 11 — Landings: 4 домена, ~64 лендинга, A/B тестирование,
visit tracking (UTM, device), conversion analytics. 8 API endpoints.
5 новых таблиц: brand_listings, reviews, landing_pages, landing_variants, landing_visits.
Файл: docs/BRAND_LANDINGS_MODULE.md
-->
