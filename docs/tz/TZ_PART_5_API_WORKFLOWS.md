# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 5 of 6: API Endpoints (30+) + n8n Workflows (22)

---

## 13. API ENDPOINTS

**Base URL:** `https://wincase.pro/api/v1/`
**Auth:** Laravel Sanctum (Bearer Token) — all endpoints except Public POST
**Format:** JSON
**Pagination:** cursor-based, 20 per page default

### 13.1 Leads API (8 endpoints)

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | /api/v1/leads | List (pagination, filters by status/source/date/manager) | Admin |
| POST | /api/v1/leads | Create (from form/webhook) | **Public*** |
| GET | /api/v1/leads/{id} | Detail view | Admin |
| PATCH | /api/v1/leads/{id} | Update (status, assigned_to, notes) | Admin |
| DELETE | /api/v1/leads/{id} | Soft delete | Admin |
| POST | /api/v1/leads/{id}/convert | Convert to client + case | Admin |
| GET | /api/v1/leads/funnel | Funnel data (stages, counts, conversion rates) | Admin |
| GET | /api/v1/leads/stats | By channels, days, conversions | Admin |

**Public POST Security:**
- Rate limit: 10/min/IP
- Honeypot field validation
- reCAPTCHA v3 (score ≥ 0.5)
- CORS: wincase.pro, wincase-legalization.com, wincase-job.com, wincase.org only

### 13.2 Ads API (4 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/ads/overview | All platforms summary table |
| GET | /api/v1/ads/{platform} | Single platform data |
| GET | /api/v1/ads/{platform}/campaigns | Campaigns list for platform |
| GET | /api/v1/ads/budget | Budget plan vs actual |

**Query Parameters:** `?date_from=&date_to=&platform=&campaign_id=`

### 13.3 SEO API (6 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/seo/overview | KPIs for all 4 domains |
| GET | /api/v1/seo/keywords | Top keywords from GSC |
| GET | /api/v1/seo/network | 8 SEO satellite sites |
| GET | /api/v1/seo/backlinks | Backlinks trend |
| GET | /api/v1/seo/reviews | Reviews from all platforms |
| GET | /api/v1/seo/brand | Trademark, Wikipedia, Knowledge Panel |

**Query Parameters:** `?domain=&date_from=&date_to=&source=`

### 13.4 Dashboard API (8 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/dashboard/kpi | 10 KPI cards data |
| GET | /api/v1/dashboard/cases | Kanban + tasks + hearings |
| GET | /api/v1/dashboard/leads | Funnel + channels + latest 10 |
| GET | /api/v1/dashboard/finance | Revenue + unpaid + chart data |
| GET | /api/v1/dashboard/ads | All platforms summary |
| GET | /api/v1/dashboard/social | 8 platforms + Threads |
| GET | /api/v1/dashboard/seo | SEO 4 domains + DA |
| GET | /api/v1/dashboard/notifications | Notifications + tasks |

**Caching:** Redis, TTL 60 seconds. WebSocket push for real-time updates via Laravel Reverb (native first-party WebSocket).

### 13.5 Social API (6 endpoints, updated with Threads)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/social/accounts | All 8 accounts with stats |
| POST | /api/v1/social/posts | Create post (cross-posting to selected platforms) |
| POST | /api/v1/social/posts/threads | Publish specifically to Threads |
| GET | /api/v1/social/analytics | Analytics across all platforms |
| GET | /api/v1/social/analytics/threads | Threads-specific analytics |
| GET | /api/v1/social/inbox | Unified inbox (all platforms DMs/comments) |

### 13.6 Existing API (already implemented)

| Group | Endpoints | Description |
|-------|-----------|-------------|
| Clients | GET/POST/PATCH/DELETE /api/v1/clients | CRUD + search + segment |
| Cases | GET/POST/PATCH/DELETE /api/v1/cases | CRUD + kanban + hearings |
| Calendar | GET/POST/PATCH/DELETE /api/v1/calendar | Events + Google sync |
| Documents | GET/POST/DELETE /api/v1/documents | Upload + OCR + templates |
| Finance | GET/POST/PATCH /api/v1/invoices, /payments | Invoices + payments |
| Content | GET/POST /api/v1/content | Media library + templates |
| Settings | GET/PATCH /api/v1/settings | Config + users + roles |

---

## 14. n8n WORKFLOWS — COMPLETE LIST (22)

### 14.1 Lead Workflows (W01—W03)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W01 | Lead Processing (all channels + Threads) | Webhook | Real-time |
| W02 | Lead Follow-up (no contact 30min) | Cron | Every 30 min |
| W03 | Lead Weekly Report | Cron | Mon 9:00 |

### 14.2 Ads Sync Workflows (W04—W07)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W04 | Google Ads Data Sync | Cron | Every 6h |
| W05 | Meta Ads Data Sync | Cron | Every 6h |
| W06 | TikTok Ads Data Sync | Cron | Every 6h |
| W07 | Pinterest + YouTube Ads Sync | Cron | Every 12h |

### 14.3 SEO Workflows (W08—W09, W20)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W08 | GSC + GA4 Daily Sync (4 domains) | Cron | Daily 6:00 |
| W09 | SEO Weekly Report (DA, backlinks) | Cron | Mon 8:00 |
| W20 | SEO Network Article Check | Cron | Weekly |

### 14.4 Social & Content Workflows (W10—W12, W22)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W10 | Google Reviews Monitor | Cron | Every 2h |
| W11 | Social Autopost (8 platforms + Threads) | Scheduled | content_calendar |
| W12 | AI Content Generation | HTTP Trigger | On demand |
| W22 | Threads Autopost + Analytics Sync | Cron | content_calendar + 12h |

### 14.5 Communication Workflows (W13—W14)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W13 | WhatsApp Auto-Reply | Webhook | Real-time |
| W14 | Telegram Bot Handler | Webhook | Real-time |

### 14.6 Conversion & Reporting Workflows (W15—W19, W21)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W15 | Review Request Chain | Event | After case completion |
| W16 | Monthly Report (all channels) | Cron | 1st of month, 9:00 |
| W17 | Offline Conversion (Google Ads) | Event | lead.status = paid |
| W18 | Facebook CAPI Events | Event | lead.created |
| W19 | TikTok Events API | Event | lead.created |
| W21 | NAP Consistency Check (4 domains) | Cron | Monthly |

---

<!-- Аннотация (RU):
ЧАСТЬ 5 из 6 технического задания WINCASE CRM v4.0.
Содержит полный список API endpoints (30+): Leads API (8), Ads API (4), SEO API (6),
Dashboard API (8), Social API (6 + Threads), плюс существующие Clients/Cases/Finance/etc.
Все новые endpoints используют Laravel Sanctum авторизацию, кроме Public POST для лидов
(защищённый rate limit 10/min/IP, honeypot, reCAPTCHA v3, CORS 4 доменов).
Dashboard API использует Redis кэш (60 сек TTL) + WebSocket через Laravel Echo.
Полный список 22 n8n workflows: обработка лидов (W01-W03), синхронизация рекламы (W04-W07),
SEO мониторинг (W08-W09, W20), социальные сети (W10-W12, W22), коммуникации (W13-W14),
конверсии и отчёты (W15-W19, W21).
Следующая часть: API ключи + Deployment + Implementation Plan.
-->
