# WINCASE CRM v4.0 — Dashboard Module (Phase 8)
## Laravel 12 + PHP 8.4 + Redis + Reverb WebSocket

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Vue.js 3.5 Frontend                       │
│  ┌─────────┬───────┬────────┬─────┬───────┬─────┬────────┐  │
│  │  KPI    │ Cases │ Leads  │ Fin │  Ads  │ SMM │  SEO   │  │
│  │  Bar    │       │ Funnel │ POS │       │     │        │  │
│  └────┬────┴───┬───┴───┬────┴──┬──┴───┬───┴──┬──┴──┬─────┘  │
│       └────────┴───────┴──────┴──────┴──────┴─────┘         │
│                          ▲                                    │
│                 Laravel Echo (Reverb WS)                      │
│                 channel: dashboard                            │
│                 event: .dashboard.updated                     │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────┴──────────────────────────────────┐
│                    Laravel 12 Backend                         │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐    │
│  │           DashboardCacheService (Redis)              │    │
│  │  TTL: KPI=60s, Sections=300s, Full=120s              │    │
│  │  invalidateAndBroadcast() → Cache::forget → event()  │    │
│  └──────────────────────┬──────────────────────────────┘    │
│                         │                                    │
│  ┌──────────────────────┴──────────────────────────────┐    │
│  │              DashboardService                        │    │
│  │  getKpiBar() → 12 cards                             │    │
│  │  8 section methods: Cases, Leads, Finance, Ads,     │    │
│  │  Social, SEO, Accounting, Automation                 │    │
│  └──────────────────────┬──────────────────────────────┘    │
│                         │                                    │
│  ┌──────────────────────┴──────────────────────────────┐    │
│  │            MySQL 8.4 (Direct DB Queries)             │    │
│  │  leads, cases, invoices, pos_transactions,           │    │
│  │  ads_performance, seo_data, social_*, tax_reports    │    │
│  └─────────────────────────────────────────────────────┘    │
└──────────────────────────────────────────────────────────────┘
```

---

## KPI Bar (12 Cards)

| # | Card | Source | Update Trigger |
|---|------|--------|----------------|
| 1 | Today Leads | leads table | New lead created |
| 2 | Active Cases | cases table | Case status change |
| 3 | Monthly Revenue | invoices (paid) | Invoice payment |
| 4 | Avg Response Time | leads (first_contacted_at) | Lead contacted |
| 5 | Ad Spend 7d | ads_performance | Ads sync (W04-W07) |
| 6 | Organic Users 7d | seo_data (ga4) | GA4 sync (W08) |
| 7 | Social Followers | social_accounts | Social sync (W11) |
| 8 | Conversion Rate 30d | leads (client_id) | Lead converted |
| 9 | Pending Tasks | tasks table | Task created/updated |
| 10 | Active Clients | clients table | Client status change |
| 11 | POS Pending | pos_transactions | POS payment received |
| 12 | Monthly Tax Burden | tax_reports | Tax calculated (W21) |

---

## 8 Dashboard Sections

### 1. Cases
- Cases by status (pie chart)
- Manager workload (bar chart, top 10)
- Deadline alerts (next 7 days)

### 2. Leads & Funnel
- 7-stage funnel (NEW → CONTACTED → CONSULTATION → CONTRACT → PAID)
- Daily trend (14 days line chart)
- Source breakdown (bar chart)
- Conversion rate, unassigned count

### 3. Finance & POS
- Revenue chart (6 months)
- Invoice status breakdown
- POS summary: pending count, cash vs card, approved amount

### 4. Ads Performance
- 5-platform overview (7 days): spend, leads, CPL
- Daily spend trend (30 days)
- Budget plan vs actual (this month)

### 5. Social Media
- 8 accounts: followers, last sync
- Posts last 7 days count
- Top post by engagement (30 days)

### 6. SEO Dashboard
- GSC 7d: clicks, impressions, avg position
- GA4 7d: users, sessions
- Domain Authority (4 domains)
- SEO Network: active sites, avg DA, articles

### 7. Accounting & Tax
- Current month tax burden by report type
- YTD cumulative tax
- Upcoming deadlines: ZUS (10th), PIT (20th), JPK_VAT (25th)
- Top 5 expense categories

### 8. AI & Automation
- 22 n8n workflows status
- Last sync timestamps
- Recent errors

---

## Real-Time Updates (Reverb WebSocket)

### Flow
```
Model Event → Observer → DashboardCacheService::invalidateAndBroadcast()
  → Cache::forget()
  → Fresh query
  → event(new DashboardUpdated($section, $data))
  → Reverb WebSocket → Vue.js Echo listener
```

### Trigger Map
| Model Change | Dashboard Sections Invalidated |
|-------------|-------------------------------|
| Lead created/updated | kpi, leads |
| Case status change | kpi, cases |
| Invoice paid | kpi, finance |
| POS transaction | kpi, finance |
| Ads sync completed | kpi, ads |
| Social post/sync | social |
| SEO data sync | seo |
| Tax report calculated | kpi, accounting |
| Expense added | accounting |
| Task created | kpi |

### Vue.js Listener Example
```javascript
Echo.private('dashboard')
  .listen('.dashboard.updated', (e) => {
    store.updateSection(e.section, e.data);
  });
```

---

## Files Created (Phase 8)

```
dashboard-module/
├── services/
│   ├── DashboardService.php           # 12 KPI + 8 sections + full dashboard
│   ├── DashboardCacheService.php      # Redis caching + broadcast triggers
│   └── DashboardUpdated.php           # Reverb WebSocket broadcast event
├── controllers/
│   └── DashboardController.php        # 10 API endpoints
├── routes/
│   ├── api_dashboard_routes.php       # 10 routes (auth:sanctum)
│   └── channels.php                   # Private channel auth
└── DASHBOARD_MODULE.md                # This documentation
```

---

## API Endpoints (10)

| Method | Endpoint | Description | Cache TTL |
|--------|----------|-------------|-----------|
| `GET` | `/api/v1/dashboard` | Full dashboard (all sections) | 120s |
| `GET` | `/api/v1/dashboard/kpi` | 12 KPI cards | 60s |
| `GET` | `/api/v1/dashboard/cases` | Cases section | 300s |
| `GET` | `/api/v1/dashboard/leads` | Leads & funnel section | 300s |
| `GET` | `/api/v1/dashboard/finance` | Finance & POS section | 300s |
| `GET` | `/api/v1/dashboard/ads` | Ads performance section | 300s |
| `GET` | `/api/v1/dashboard/social` | Social media section | 300s |
| `GET` | `/api/v1/dashboard/seo` | SEO dashboard section | 300s |
| `GET` | `/api/v1/dashboard/accounting` | Accounting & tax section | 300s |
| `GET` | `/api/v1/dashboard/automation` | AI & automation section | 300s |

---

## Installation

```bash
# Copy files
cp services/DashboardService.php backend/app/Services/Dashboard/
cp services/DashboardCacheService.php backend/app/Services/Dashboard/
cp services/DashboardUpdated.php backend/app/Events/DashboardUpdated.php
cp controllers/DashboardController.php backend/app/Http/Controllers/Api/V1/

# Append routes
cat routes/api_dashboard_routes.php >> backend/routes/api.php
cat routes/channels.php >> backend/routes/channels.php

# Reverb setup (if not done)
php artisan install:broadcasting
php artisan reverb:start

# Verify
php artisan route:list --path=dashboard
```

<!--
Аннотация (RU):
Модуль Dashboard WINCASE CRM v4.0.
12 KPI карточек в header bar. 8 секций: Cases, Leads, Finance, Ads, Social, SEO, Accounting, Automation.
Redis кеширование (TTL: KPI 60s, секции 300s). Real-time через Reverb WebSocket.
DashboardCacheService — invalidateAndBroadcast() при изменении данных.
triggerMap() — маппинг Model → sections для автоматической инвалидации кеша.
7 файлов: 3 services/events, 1 controller, 2 routes, 1 docs. 10 API endpoints.
Файл: docs/DASHBOARD_MODULE.md
-->
