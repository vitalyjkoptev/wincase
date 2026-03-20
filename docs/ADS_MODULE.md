# WINCASE CRM v4.0 — Ads Module (Phase 5)
## Laravel 12 + PHP 8.4

---

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    AdsOrchestrationService                       │
│  (unified overview, budget analysis, sync all, dashboard stats)  │
└──────┬────────┬────────┬─────────┬──────────┬───────────────────┘
       │        │        │         │          │
       ▼        ▼        ▼         ▼          ▼
 ┌──────────┐┌──────┐┌───────┐┌──────────┐┌─────────┐
 │ Google   ││ Meta ││TikTok ││Pinterest ││YouTube  │
 │ Ads      ││ Ads  ││ Ads   ││ Ads      ││ Ads     │
 │ Service  ││Svc   ││ Svc   ││ Svc      ││ Svc     │
 └────┬─────┘└──┬───┘└───┬───┘└────┬─────┘└────┬────┘
      │         │        │         │            │
      ▼         ▼        ▼         ▼            ▼
  Google     Graph   TikTok   Pinterest    Google
  Ads API    API     Biz API  API v5       Ads API
  v17        v19.0   v1.3                  (VIDEO)
      │         │        │         │            │
      └─────────┴────────┴─────────┴────────────┘
                         │
                         ▼
               ┌──────────────────┐
               │ ads_performance  │ ← UPSERT (platform+campaign_id+date)
               │ (MySQL 8.4)     │
               └──────────────────┘
```

---

## Sync Schedule (n8n Workflows)

| Workflow | Platforms | Frequency | API |
|----------|-----------|-----------|-----|
| W04 | Google Ads | Every 6h | Google Ads API v17 (GAQL) |
| W05 | Meta Ads (FB/IG) | Every 6h | Graph API v19.0 /insights |
| W06 | TikTok Ads | Every 6h | Business API v1.3 /report |
| W07 | Pinterest + YouTube | Every 12h | Pinterest v5 + Google Ads (VIDEO) |

Each sync: fetch last 2 days → normalize → upsert to `ads_performance` (UNIQUE: platform + campaign_id + date).

---

## Offline Conversions (Server-Side Events)

| Trigger | Platform | Service Method | Click ID |
|---------|----------|---------------|----------|
| Lead created (W18) | Facebook CAPI | MetaAdsService::sendCapiEvent('Lead') | fbclid |
| Lead created (W19) | TikTok Events | TikTokAdsService::sendEvent('Lead') | ttclid |
| Lead paid (W17) | Google Ads | GoogleAdsService::uploadOfflineConversion() | gclid |
| Lead paid | Facebook CAPI | MetaAdsService::sendCapiEvent('Purchase') | fbclid |

All events hash email/phone via SHA-256 for privacy compliance.

---

## Files Created (Phase 5)

```
ads-module/
├── services/
│   ├── AbstractPlatformService.php    # Base: sync, getStats, dailyBreakdown
│   ├── GoogleAdsService.php           # GAQL + offline conversions
│   ├── MetaAdsService.php             # Graph API + Facebook CAPI
│   ├── TikTokAdsService.php           # Business API + Events API
│   ├── PinterestAdsService.php        # Pinterest API v5
│   ├── YouTubeAdsService.php          # Google Ads (VIDEO channel)
│   └── AdsOrchestrationService.php    # Unified: overview, budget, sync
├── controllers/
│   └── AdsController.php              # 5 API endpoints
├── config/
│   └── ads.php                        # Budget plan, sync config
├── routes/
│   └── api_ads_routes.php             # 5 routes (auth:sanctum)
└── ADS_MODULE.md                      # This documentation
```

---

## API Endpoints (5)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/ads/overview` | All 5 platforms: summary + totals |
| `GET` | `/api/v1/ads/budget` | Budget plan vs actual spend |
| `GET` | `/api/v1/ads/{platform}` | Single platform: campaigns + daily chart |
| `GET` | `/api/v1/ads/{platform}/campaigns` | Campaign list with aggregated metrics |
| `POST` | `/api/v1/ads/sync` | Manual sync trigger |

**Platforms:** `google_ads`, `meta_ads`, `tiktok_ads`, `pinterest_ads`, `youtube_ads`
**Query params:** `?date_from=&date_to=` (default: last 30 days)

---

## Installation

```bash
# Copy files
cp services/*.php backend/app/Services/Ads/
cp controllers/AdsController.php backend/app/Http/Controllers/Api/V1/
cp config/ads.php backend/config/

# Append routes
cat routes/api_ads_routes.php >> backend/routes/api.php

# Add to .env
ADS_BUDGET_GOOGLE=3000
ADS_BUDGET_META=2000
ADS_BUDGET_TIKTOK=1500
ADS_BUDGET_PINTEREST=500
ADS_BUDGET_YOUTUBE=1000
GOOGLE_ADS_CONVERSION_ACTION_ID=123456789

# Cache config
php artisan config:cache
php artisan route:list --path=ads
```

---

## Derived Metrics (auto-calculated in AbstractPlatformService)

| Metric | Formula |
|--------|---------|
| CTR | clicks / impressions |
| CPC | cost / clicks |
| CPL | cost / leads_count |
| ROAS | conversion_value / cost |

<!--
Аннотация (RU):
Модуль рекламных кампаний WINCASE CRM v4.0.
5 платформ: Google Ads (GAQL + offline conversions), Meta Ads (Graph API + CAPI),
TikTok Ads (Business API + Events), Pinterest (API v5), YouTube (Google Ads VIDEO).
AbstractPlatformService — базовый класс: sync → normalize → upsert.
AdsOrchestrationService — unified: overview, budget analysis, dashboard stats.
Offline conversions: gclid (Google), fbclid (Facebook CAPI), ttclid (TikTok Events).
10 файлов, 5 API endpoints.
Файл: docs/ADS_MODULE.md
-->
