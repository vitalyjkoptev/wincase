# WINCASE CRM v4.0 — Complete Project Documentation

## Immigration Bureau CRM System — Warsaw, Poland
**Version:** 4.0.0 | **Updated:** 2026-02-19
**Contact:** wincasetop@gmail.com | +48 579 266 493

---

## 1. TECHNOLOGY STACK

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | Laravel | 12.x |
| PHP | PHP | 8.4 |
| Database | MySQL | 8.4 |
| Cache/Queue | Redis | 7.x |
| WebSocket | Laravel Reverb | 1.x |
| Frontend | Vue.js + TypeScript | 3.5 + 5.7 |
| Build | Vite | 6.x |
| CSS | TailwindCSS | 3.4 |
| State | Pinia | 2.3 |
| Mobile | Flutter + Dart | 3.29+ |
| State (Mobile) | Riverpod | 2.6 |
| Automation | n8n | Latest |
| AI | Claude Sonnet / GPT-4o | Latest |
| Server | Ubuntu | 24.04 LTS |
| Container | Docker Compose | 3.8 |
| CI/CD | GitHub Actions | Latest |

---

## 2. PROJECT STATISTICS

| Metric | Value |
|--------|-------|
| **Total Modules** | 22 |
| **Total Files** | 215+ |
| **PHP Lines** | 22,500+ |
| **Vue/TypeScript Lines** | 3,100+ |
| **Dart Lines** | 2,607 |
| **API Endpoints** | 212+ |
| **MySQL Tables** | 45 |
| **n8n Workflows** | 27 |
| **Notification Channels** | 6 |
| **Report Types** | 8 |
| **News Sources** | 27 |
| **Languages Supported** | 8 |
| **Scheduled Tasks** | 20+ |
| **Integration APIs** | 13 |

---

## 3. MODULE MAP (22 Modules)

### Phase 1-2: Database & Leads
- **Database**: 45 MySQL tables, 16 enum types, 100+ indexes
- **Leads**: 14 acquisition sources, 4-layer security, webhook receivers
- 14 endpoints, funnel analytics, auto-assignment

### Phase 3: POS Terminal
- Staging payments (pending→approved→completed)
- 6 payment methods: cash, card, BLIK, transfer, POS terminal, crypto
- VAT 23% auto-calculation, receipt PDF generation, 11 endpoints

### Phase 4: Accounting
- Polish tax system 2026: PIT, CIT, VAT, ZUS
- JPK_VAT export, invoice PDF, P&L, Balance, Cash Flow
- Tax calendar with deadlines, 16 endpoints

### Phase 5: Advertising
- 5 platforms: Google Ads, Meta Ads, TikTok Ads, LinkedIn Ads, Twitter Ads
- Cross-platform analytics, budget management, offline conversions
- ROI/CPA/ROAS calculation, 13 endpoints

### Phase 6: SEO
- 4 domains + 8 satellite sites management
- Google Search Console, GA4, Ahrefs integration
- Keyword tracking, sitemap generation, competitor analysis, 10 endpoints

### Phase 7: Social Media
- 8 platforms: Facebook, Instagram, YouTube, LinkedIn, TikTok, X, Pinterest, Telegram
- Unified posting, content calendar, inbox aggregation
- Cross-platform analytics, 15 endpoints

### Phase 8: Dashboard
- 12 KPI cards with Redis cache (1-min TTL)
- Real-time updates via Reverb WebSocket
- 6 sections: leads, finance, ads, social, SEO, system, 8 endpoints

### Phase 9: Flutter Mobile
- 8 screens: Login, Dashboard, Leads, Lead Detail, Tasks, Calendar, Notifications, Settings
- Riverpod state management, Firebase FCM push, 2,607 lines Dart

### Phase 10: Brand & Reputation
- 54 business directories (Google Maps, Yelp, Foursquare, TripAdvisor...)
- NAP consistency checker, Reviews Hub (Google + Facebook + TripAdvisor + Trustpilot)
- Reputation scoring algorithm, 8 endpoints

### Phase 11: Landing Pages
- 4 domains (~64 pages): wincase.pro, kancelaria.pro, workinpoland.pl, pozwolenie.pl
- A/B testing engine, conversion tracking, 7 endpoints

### Phase 12: Core CRM
- Clients: CRUD + cases + documents + timeline, 8 endpoints
- Cases: 10-status workflow (new→active→pending_docs→submitted→in_review→additional_docs→decision→approved→completed→closed)
- Tasks: priority/status, assignment, overdue tracking, 8 endpoints
- Documents: 17 types, max 20MB upload, signed URLs, 7 endpoints
- Calendar: 7 event types, upcoming view, 7 endpoints

### Phase 13: n8n Workflows (W01-W22)
- 22 workflows for automation across all modules
- Lead processing, social posting, SEO monitoring, payment processing

### Phase 14: Auth & RBAC
- Sanctum tokens, 5 roles (admin, manager, operator, accountant, viewer)
- 2FA TOTP, brute-force protection (5 attempts/15 min)
- Role abilities matrix, 18 endpoints (Auth + Users)

### Phase 15: Deployment
- Docker Compose: 8 services (PHP 8.4 FPM, nginx 1.27, MySQL 8.4, Redis 7, queue worker, scheduler, Reverb, n8n)
- SSL via Let's Encrypt, UFW + Fail2Ban security
- GitHub Actions CI/CD pipeline

### Phase 16: Vue Admin Panel
- Vue 3.5 Composition API, Vite 6, TypeScript 5.7
- 5 Pinia stores, 25+ routes with RBAC navigation guard
- 15+ views with TailwindCSS responsive design

### Phase 17: News Pipeline
- 27 verified sources (24 RSS + 3 web scrapers) in 8 categories
- AI rewriter (Claude primary, OpenAI fallback), 0% plagiarism target
- Auto-publish to WordPress (polandpulse.news) + Laravel (wincase.pro/blog)
- 8 languages, live WebSocket feed, 5 n8n workflows (W23-W27), 14 endpoints

### Phase 18: Notifications
- 6 channels: In-App (WebSocket), Email, SMS, WhatsApp, Telegram, Push (FCM)
- 12 notification templates, bulk send, browser notifications
- 7 endpoints

### Phase 19: Reports & Export
- 8 report types: leads, cases, financial, clients, managers, ads ROI, news, doc expiry
- PDF (DomPDF) + XLSX (PhpSpreadsheet) + JSON export
- Scheduled reports (daily/weekly/monthly) → email, 8 endpoints

### Phase 20: Audit Log
- Universal audit: CRUD, auth, status changes, assignments, exports
- Security report: failed logins by IP, role changes, data exports
- Activity timeline per entity, password/token sanitization
- 7 endpoints

### Phase 21: System Health
- 7 service checks: MySQL, Redis, Storage, Queue, Reverb, n8n, Mail
- Server metrics: CPU, memory, OPcache, disk, uptime
- Cache management, maintenance mode, 13 integrations status
- 7 endpoints

### Phase 22-24: Final Integration
- OpenAPI 3.1 specification (Swagger UI)
- PHPUnit test suite: 40+ tests across 7 test files
- Master router: all 212 endpoints consolidated
- Vue router: 25+ routes with lazy loading + RBAC
- Laravel Scheduler: 20+ automated tasks

---

## 4. RBAC — 5 ROLES

| Role | Scope |
|------|-------|
| **admin** | Full access, all modules, user management, system |
| **manager** | CRM + marketing + news + reports (no system/audit) |
| **operator** | CRM + POS + documents (no marketing/admin) |
| **accountant** | Dashboard + POS + accounting + reports |
| **viewer** | Read-only access to CRM data |

---

## 5. DEPLOYMENT CHECKLIST

```bash
# 1. Clone repository
git clone git@github.com:webwavedevelopers/wincase-crm.git
cd wincase-crm

# 2. Environment
cp .env.example .env
# Edit .env with production values

# 3. Docker
docker-compose up -d

# 4. Laravel setup
docker exec -it wincase-php bash
php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan storage:link

# 5. Frontend
cd frontend
npm install && npm run build

# 6. SSL
certbot --nginx -d api.wincase.pro -d admin.wincase.pro

# 7. n8n
# Import 27 workflows via n8n UI

# 8. Firebase
# Upload firebase-credentials.json to storage/

# 9. Cron (scheduler)
# * * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1

# 10. Verify
curl https://api.wincase.pro/api/v1/system/health
```

---

## 6. API OVERVIEW

**Base URL:** `https://api.wincase.pro/api/v1`
**Auth:** Bearer token (Sanctum) — `Authorization: Bearer {token}`
**Format:** JSON — `Content-Type: application/json`

**Response format:**
```json
{
  "success": true,
  "data": { ... },
  "message": "Optional message"
}
```

**Pagination:**
```json
{
  "success": true,
  "data": {
    "data": [...],
    "meta": {
      "total": 150,
      "per_page": 30,
      "current_page": 1,
      "last_page": 5
    }
  }
}
```

**Rate Limiting:**
- Auth endpoints: 5 requests/min
- Webhooks: 60 requests/min
- API (authenticated): 60 requests/min per user

---

## 7. FILE STRUCTURE

```
wincase-crm/
├── app/
│   ├── Console/Kernel.php              # 20+ scheduled tasks
│   ├── Events/                         # NotificationEvent, NewsFeedEvent
│   ├── Http/Controllers/Api/V1/        # 15+ controllers
│   ├── Models/                         # 20+ Eloquent models
│   └── Services/                       # Business logic
│       ├── Accounting/                 # Tax calculations (PL 2026)
│       ├── Ads/                        # 5-platform ads management
│       ├── Audit/                      # Audit logging
│       ├── Auth/                       # Sanctum + 2FA
│       ├── Brand/                      # 54 directories, reputation
│       ├── Dashboard/                  # KPI, Redis cache
│       ├── Landings/                   # A/B testing
│       ├── Leads/                      # 14 sources, funnel
│       ├── News/                       # Parse → Rewrite → Publish
│       ├── Notifications/              # 6 channels
│       ├── POS/                        # Payments, VAT
│       ├── Reports/                    # 8 types, PDF/XLSX
│       ├── SEO/                        # GSC, GA4, Ahrefs
│       ├── Social/                     # 8 platforms
│       └── System/                     # Health, maintenance
├── config/openapi.php                  # Swagger config
├── database/migrations/                # 45 tables
├── routes/api.php                      # Master router (212+ endpoints)
├── tests/Feature/                      # 40+ PHPUnit tests
├── docker-compose.yml                  # 8 Docker services
├── .github/workflows/deploy.yml        # CI/CD
├── frontend/                           # Vue 3.5 + Vite 6
│   ├── src/router/index.ts             # 25+ routes + RBAC
│   ├── src/stores/                     # 5 Pinia stores
│   ├── src/views/                      # 15+ page views
│   └── src/components/                 # Shared components
└── flutter/                            # Flutter 3.29+ mobile app
    └── lib/                            # 8 screens, Riverpod
```

---

## 8. CONTACTS & SUPPORT

| | |
|---|---|
| **Company** | WebWave Developers |
| **Email** | wincasetop@gmail.com |
| **Phone** | +48 579 266 493 |
| **CRM URL** | https://admin.wincase.pro |
| **API URL** | https://api.wincase.pro |
| **n8n URL** | https://n8n.wincase.pro |
| **Swagger** | https://api.wincase.pro/api/documentation |

---

*WinCase CRM v4.0 — Built for Immigration Bureau, Warsaw, Poland*
*22 modules, 212+ API endpoints, 45 MySQL tables, 27 n8n workflows*
*Full-stack: Laravel 12 + Vue 3.5 + Flutter 3.29 + Docker*
