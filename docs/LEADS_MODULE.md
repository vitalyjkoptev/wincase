# WINCASE CRM v4.0 — Leads Module (Phase 2)
## Laravel 12 + PHP 8.4

---

## Business Flow

```
     ┌─────────────────────────────────────────────────────────┐
     │                    14 LEAD SOURCES                       │
     │                                                         │
     │  FORMS (4 domains)    ADS (Google/Meta/TikTok)          │
     │  WhatsApp / Telegram  Threads DM                        │
     │  Phone / Walk-in      Referral                          │
     └────────────────────┬────────────────────────────────────┘
                          │
                          ▼
          ┌───────────────────────────────┐
          │  POST /api/v1/leads           │
          │  ───────────────────────      │
          │  Rate Limit: 10/min/IP        │
          │  Honeypot: "website" field    │
          │  reCAPTCHA v3: score ≥ 0.5    │
          │  CORS: 4 WinCase domains      │
          └───────────────┬───────────────┘
                          │
                          ▼
                ┌─────────────────┐
                │ DUPLICATE CHECK │──── Found? → 409 Conflict
                │ (phone, 30 days)│
                └────────┬────────┘
                         │ New
                         ▼
           ┌──────────────────────────┐
           │  LANGUAGE DETECTION      │
           │  URL path → country → en │
           └────────────┬─────────────┘
                        │
           ┌────────────▼─────────────┐
           │  PRIORITY DETECTION      │
           │  Paid ads → HIGH         │
           │  Walk-in → URGENT        │
           │  Phone → HIGH            │
           │  Others → MEDIUM         │
           └────────────┬─────────────┘
                        │
           ┌────────────▼─────────────────────────────────┐
           │  AUTO-ROUTING (8 rules, first match wins)    │
           │  ────────────────────────────────────────     │
           │  1. Paid lead (gclid/fbclid) → least busy    │
           │  2. RU/UA language → Manager RU/UA            │
           │  3. HI/TL language → Manager Asian            │
           │  4. EN from India/PH → Manager Asian          │
           │  5. ES/TR language → Manager ES/TR            │
           │  6. Job Centre → Manager Job Centre           │
           │  7. Walk-in → URGENT, any available           │
           │  8. Off-hours → Round Robin for morning       │
           │  ─── Fallback: Round Robin (Redis counter) ── │
           └────────────┬─────────────────────────────────┘
                        │
                        ▼
              ┌──────────────────┐
              │   LEAD CREATED   │
              │   + ASSIGNED     │
              │   Status: NEW    │
              └────────┬─────────┘
                       │
                       ▼
     ┌─────────────────────────────────────────┐
     │              FUNNEL                      │
     │  NEW → CONTACTED → CONSULTATION          │
     │      → CONTRACT → PAID (success)         │
     │  or → REJECTED / SPAM (at any stage)     │
     └─────────────────────────────────────────┘
                       │
                       ▼ (on CONVERT action)
     ┌─────────────────────────────────────────┐
     │  LEAD → CLIENT                           │
     │  ─────────────                           │
     │  1. Find client by phone (or create new) │
     │  2. Create Case (optional)               │
     │  3. Link lead → client_id + case_id      │
     │  4. Status → CONTRACT                    │
     │  5. After POS/Invoice → PAID             │
     └─────────────────────────────────────────┘
```

---

## Files Created (Phase 2)

```
leads-module/
├── services/
│   ├── LeadService.php               # Main orchestration: CRUD, funnel, stats
│   ├── LeadRoutingService.php         # 8 routing rules + round robin
│   └── LeadConversionService.php      # Lead → Client + Case conversion
├── controllers/
│   └── LeadController.php             # 8 API endpoints
├── requests/
│   ├── StoreLeadRequest.php           # Public + admin creation validation
│   ├── UpdateLeadRequest.php          # Admin update validation
│   └── ConvertLeadRequest.php         # Conversion validation
├── middleware/
│   ├── HoneypotMiddleware.php         # Anti-bot hidden field
│   ├── RecaptchaMiddleware.php        # Google reCAPTCHA v3
│   ├── CorsDomainsMiddleware.php      # Restrict to 4 WinCase domains
│   └── rate_limiter_config.php        # 10/min/IP for AppServiceProvider
├── routes/
│   └── api_leads_routes.php           # 8 routes (1 public + 7 admin)
└── LEADS_MODULE.md                    # This documentation
```

---

## API Endpoints (8)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| `POST` | `/api/v1/leads` | **Public** (throttle+honeypot+captcha) | Create lead from any source |
| `GET` | `/api/v1/leads` | Admin | List with 10 filters |
| `GET` | `/api/v1/leads/funnel` | Admin | Funnel stages + conversion rates |
| `GET` | `/api/v1/leads/stats` | Admin | Quick stats + channels + daily trend |
| `GET` | `/api/v1/leads/{id}` | Admin | Lead details + meta |
| `PATCH` | `/api/v1/leads/{id}` | Admin | Update lead (auto-timestamps) |
| `DELETE` | `/api/v1/leads/{id}` | Admin | Soft delete |
| `POST` | `/api/v1/leads/{id}/convert` | Admin | Convert to CRM client + case |

### Filters (GET /leads)

| Param | Type | Example |
|-------|------|---------|
| `status` | string | `new`, `contacted`, `consultation` |
| `source` | string | `google_ads`, `facebook_ads`, `walk_in` |
| `language` | string | `ru`, `ua`, `en`, `hi` |
| `assigned_to` | int | User ID |
| `unassigned` | bool | `true` — no manager assigned |
| `priority` | string | `urgent`, `high`, `medium`, `low` |
| `from` / `to` | date | Date range |
| `search` | string | Search name/phone/email |
| `active_only` | bool | Exclude rejected/spam/paid |
| `per_page` | int | 1-100 (default: 20) |

---

## Security (Public Endpoint)

| Layer | Protection |
|-------|-----------|
| **Rate Limit** | 10 requests/min per IP (`throttle:lead_submit`) |
| **Honeypot** | Hidden field `website` — if filled, silent 200 OK (bot trap) |
| **reCAPTCHA v3** | Google score ≥ 0.5, fail-open on service error |
| **CORS** | Only 4 WinCase domains + www variants allowed |
| **Validation** | 25 fields validated, phone required, GDPR consent tracked |
| **Duplicate** | Same phone within 30 days → 409 Conflict |

---

## Installation

```bash
# Copy files
cp services/*.php backend/app/Services/Leads/
cp controllers/LeadController.php backend/app/Http/Controllers/Api/V1/
cp requests/*.php backend/app/Http/Requests/
cp middleware/HoneypotMiddleware.php backend/app/Http/Middleware/
cp middleware/RecaptchaMiddleware.php backend/app/Http/Middleware/
cp middleware/CorsDomainsMiddleware.php backend/app/Http/Middleware/

# Append routes
cat routes/api_leads_routes.php >> backend/routes/api.php

# Add rate limiter to AppServiceProvider::boot()
# (see middleware/rate_limiter_config.php)

# Register middleware in bootstrap/app.php:
# ->withMiddleware(function (Middleware $middleware) {
#     $middleware->alias([
#         'honeypot' => \App\Http\Middleware\HoneypotMiddleware::class,
#         'recaptcha' => \App\Http\Middleware\RecaptchaMiddleware::class,
#         'cors.wincase' => \App\Http\Middleware\CorsDomainsMiddleware::class,
#     ]);
# })

# Verify
php artisan route:list --path=leads
```

---

## Updated Project Statistics

| Metric | Before | After Leads Module |
|--------|--------|-------------------|
| API Endpoints | 57+ | **65+** (+8 leads) |
| Services | 40+ | **43+** (+LeadService, +LeadRouting, +LeadConversion) |
| Middleware | — | **+3** (Honeypot, reCAPTCHA, CORS) |
| Form Requests | — | **+3** (Store, Update, Convert) |
| Total PHP Files | 51 | **62** (+11 leads) |

<!--
Аннотация (RU):
Полная документация модуля лидов WINCASE CRM v4.0.
14 источников лидов, 4 уровня защиты публичного endpoint
(rate limit, honeypot, reCAPTCHA, CORS), 8 правил авто-маршрутизации,
воронка 7 стадий, конвертация лид→клиент+дело.
11 файлов: 3 сервиса, 1 контроллер, 3 Form Request, 3 middleware, 1 routes.
Файл: docs/LEADS_MODULE.md
-->
