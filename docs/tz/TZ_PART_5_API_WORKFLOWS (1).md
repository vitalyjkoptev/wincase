# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 5 of 7: API Endpoints (57+) + n8n Workflows (22)

---

## 16. API ENDPOINTS

**Base URL:** `https://wincase.pro/api/v1/`
**Auth:** Laravel Sanctum (Bearer Token) — all endpoints except Public POST
**Format:** JSON
**Pagination:** cursor-based, 20 per page default

### 16.1 Leads API (8 endpoints)

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | /api/v1/leads | List (filters: status/source/date/manager) | Admin |
| POST | /api/v1/leads | Create (from form/webhook) | **Public*** |
| GET | /api/v1/leads/{id} | Detail view | Admin |
| PATCH | /api/v1/leads/{id} | Update (status, assigned_to, notes) | Admin |
| DELETE | /api/v1/leads/{id} | Soft delete | Admin |
| POST | /api/v1/leads/{id}/convert | Convert to client + case | Admin |
| GET | /api/v1/leads/funnel | Funnel data (stages, counts, rates) | Admin |
| GET | /api/v1/leads/stats | By channels, days, conversions | Admin |

**Public POST Security:** rate limit 10/min/IP, honeypot, reCAPTCHA v3 (≥0.5), CORS 4 domains.

### 16.2 POS API (11 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/v1/pos/receive | Register payment (operator) |
| GET | /api/v1/pos/pending | List awaiting owner decision |
| GET | /api/v1/pos/{id} | Transaction details |
| PATCH | /api/v1/pos/{id}/review | Mark for review |
| PATCH | /api/v1/pos/{id}/approve | Approve → calculate VAT |
| PATCH | /api/v1/pos/{id}/reject | Reject → pending refund |
| POST | /api/v1/pos/{id}/process | Create Invoice + Payment in CRM |
| PATCH | /api/v1/pos/{id}/refund | Confirm refund |
| GET | /api/v1/pos/daily-report | Daily summary |
| GET | /api/v1/pos/tax-report | Monthly tax breakdown |
| GET | /api/v1/pos/history | History with filters + search |

### 16.3 Accounting API (16 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/v1/accounting/reports/monthly | Full monthly tax report |
| POST | /api/v1/accounting/reports/vat | JPK_VAT report |
| POST | /api/v1/accounting/reports/zus | ZUS DRA declaration |
| POST | /api/v1/accounting/reports/annual | Annual PIT declaration |
| POST | /api/v1/accounting/reports/profit-loss | P&L report |
| GET | /api/v1/accounting/reports | List reports |
| GET | /api/v1/accounting/reports/{id} | Report details |
| PATCH | /api/v1/accounting/reports/{id}/submit | Mark submitted |
| POST | /api/v1/accounting/calculate | Quick tax calculation |
| POST | /api/v1/accounting/compare-regimes | Compare regimes |
| GET | /api/v1/accounting/expenses | List expenses |
| POST | /api/v1/accounting/expenses | Add expense |
| DELETE | /api/v1/accounting/expenses/{id} | Delete expense |
| GET | /api/v1/accounting/periods | Monthly periods + YTD |
| PATCH | /api/v1/accounting/periods/{id}/close | Close period |
| GET | /api/v1/accounting/tax-config | Tax rates for UI |

### 16.4 Ads API (4 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/ads/overview | All platforms summary |
| GET | /api/v1/ads/{platform} | Single platform data |
| GET | /api/v1/ads/{platform}/campaigns | Campaigns list |
| GET | /api/v1/ads/budget | Budget plan vs actual |

### 16.5 SEO API (6 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/seo/overview | KPIs for all 4 domains |
| GET | /api/v1/seo/keywords | Top keywords from GSC |
| GET | /api/v1/seo/network | 8 SEO satellite sites |
| GET | /api/v1/seo/backlinks | Backlinks trend |
| GET | /api/v1/seo/reviews | Reviews all platforms |
| GET | /api/v1/seo/brand | Trademark, Wikipedia, Knowledge Panel |

### 16.6 Dashboard API (8 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/dashboard/kpi | 12 KPI cards (incl. POS + tax) |
| GET | /api/v1/dashboard/cases | Kanban + tasks + hearings |
| GET | /api/v1/dashboard/leads | Funnel + channels + latest 10 |
| GET | /api/v1/dashboard/finance | Revenue + POS + unpaid |
| GET | /api/v1/dashboard/ads | All platforms summary |
| GET | /api/v1/dashboard/social | 8 platforms + Threads |
| GET | /api/v1/dashboard/seo | SEO 4 domains + DA |
| GET | /api/v1/dashboard/notifications | Notifications + tasks |

### 16.7 Social API (6 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/social/accounts | All 8 accounts with stats |
| POST | /api/v1/social/posts | Create post (cross-posting) |
| POST | /api/v1/social/posts/threads | Publish to Threads |
| GET | /api/v1/social/analytics | Analytics all platforms |
| GET | /api/v1/social/analytics/threads | Threads-specific analytics |
| GET | /api/v1/social/inbox | Unified inbox |

### 16.8 Existing API (core)

| Group | Endpoints |
|-------|-----------|
| Clients | GET/POST/PATCH/DELETE /api/v1/clients |
| Cases | GET/POST/PATCH/DELETE /api/v1/cases |
| Calendar | GET/POST/PATCH/DELETE /api/v1/calendar |
| Documents | GET/POST/DELETE /api/v1/documents |
| Finance | GET/POST/PATCH /api/v1/invoices, /payments |
| Content | GET/POST /api/v1/content |
| Settings | GET/PATCH /api/v1/settings |

**Total: 57+ new endpoints + existing core**

---

## 17. n8n WORKFLOWS — COMPLETE LIST (22)

### 17.1 Lead Workflows (W01—W03)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W01 | Lead Processing (all channels + Threads) | Webhook | Real-time |
| W02 | Lead Follow-up (no contact 30min) | Cron | Every 30 min |
| W03 | Lead Weekly Report | Cron | Mon 9:00 |

### 17.2 Ads Sync Workflows (W04—W07)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W04 | Google Ads Data Sync | Cron | Every 6h |
| W05 | Meta Ads Data Sync | Cron | Every 6h |
| W06 | TikTok Ads Data Sync | Cron | Every 6h |
| W07 | Pinterest + YouTube Ads Sync | Cron | Every 12h |

### 17.3 SEO Workflows (W08—W09, W20)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W08 | GSC + GA4 Daily Sync (4 domains) | Cron | Daily 6:00 |
| W09 | SEO Weekly Report (DA, backlinks) | Cron | Mon 8:00 |
| W20 | SEO Network Article Check | Cron | Weekly |

### 17.4 Social & Content Workflows (W10—W12, W22)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W10 | Google Reviews Monitor | Cron | Every 2h |
| W11 | Social Autopost (8 platforms + Threads) | Scheduled | content_calendar |
| W12 | AI Content Generation | HTTP Trigger | On demand |
| W22 | Threads Autopost + Analytics Sync | Cron | content_calendar + 12h |

### 17.5 Communication Workflows (W13—W14)

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| W13 | WhatsApp Auto-Reply | Webhook | Real-time |
| W14 | Telegram Bot Handler | Webhook | Real-time |

### 17.6 Conversion & Reporting Workflows (W15—W19, W21)

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
ЧАСТЬ 5 из 7 — полный список API endpoints (57+): Leads (8), POS (11),
Accounting (16), Ads (4), SEO (6), Dashboard (8), Social (6) + core.
22 n8n workflows: лиды (W01-W03), реклама (W04-W07), SEO (W08-W09, W20),
соцсети (W10-W12, W22), коммуникации (W13-W14), конверсии (W15-W19, W21).
Dashboard API теперь включает POS pending и tax burden данные.
Файл: docs/TZ_PART_5_API_WORKFLOWS.md
-->
