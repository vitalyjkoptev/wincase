# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 6 of 7: API Keys / Deployment / Implementation Plan / .env Template

---

## 18. API KEYS & REGISTRATIONS (21 services)

**Single registration credentials for ALL services:**
- Email: wincasetop@gmail.com
- Phone: +48 579 266 493
- Domains: wincase.pro, wincase-legalization.com, wincase-job.com, wincase.org

| Service | Key Type | Storage |
|---------|----------|---------|
| Google Ads | OAuth 2.0 + Dev Token | .env |
| Google Search Console | OAuth 2.0 | .env |
| Google Analytics 4 | OAuth 2.0 | .env |
| Google Tag Manager | OAuth 2.0 | .env |
| Google Business Profile | OAuth 2.0 | .env |
| Google Maps API | API Key | .env |
| Google Places API | API Key | .env |
| Meta Business Suite | System User Token | .env |
| Meta Threads API | OAuth 2.0 (via Meta) | .env |
| TikTok Business Center | Access Token | .env |
| Pinterest Business | OAuth 2.0 | .env |
| YouTube Data API | API Key + OAuth | .env |
| Telegram Bot | Bot Token | .env |
| WhatsApp Cloud API | System User Token | .env |
| Ahrefs API | API Key | .env |
| LinkedIn API | OAuth 2.0 | .env |
| Brevo (Sendinblue) | API Key | .env |
| Stripe | Secret Key | .env |
| Przelewy24 | Merchant ID + CRC | .env |
| OpenAI | API Key | .env |
| EUIPO / UPRP (trademark) | — | Documents |

---

## 19. .ENV TEMPLATE

```env
# === WINCASE CRM v4.0 === Laravel 12 + PHP 8.4 ===

APP_NAME=WinCaseCRM
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://wincase.pro

# --- Database (MySQL 8.4 LTS) ---
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wincase_crm
DB_USERNAME=
DB_PASSWORD=

# --- Redis 7.4 ---
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
QUEUE_CONNECTION=redis

# --- WebSocket (Laravel Reverb) ---
BROADCAST_DRIVER=reverb
REVERB_APP_ID=wincase-crm
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=https

# --- Google OAuth 2.0 ---
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

# --- Google Ads ---
GOOGLE_ADS_DEVELOPER_TOKEN=
GOOGLE_ADS_CLIENT_CUSTOMER_ID=
GOOGLE_ADS_REFRESH_TOKEN=

# --- GSC (4 domains) ---
GSC_PROPERTY_WINCASE_PRO=
GSC_PROPERTY_LEGALIZATION=
GSC_PROPERTY_JOB=
GSC_PROPERTY_ORG=

# --- GA4 (4 properties) ---
GA4_PROPERTY_WINCASE_PRO=
GA4_PROPERTY_LEGALIZATION=
GA4_PROPERTY_JOB=
GA4_PROPERTY_ORG=

# --- Google Maps + Places ---
GOOGLE_MAPS_API_KEY=
GOOGLE_PLACES_API_KEY=

# --- Google Business Profile ---
GBP_ACCOUNT_ID=
GBP_LOCATION_ID=

# --- Meta (FB + IG + Threads) ---
META_APP_ID=
META_APP_SECRET=
META_SYSTEM_USER_TOKEN=
META_PAGE_ID=
META_IG_USER_ID=
META_THREADS_USER_ID=
META_PIXEL_ID=

# --- TikTok ---
TIKTOK_APP_ID=
TIKTOK_APP_SECRET=
TIKTOK_ACCESS_TOKEN=
TIKTOK_PIXEL_ID=
TIKTOK_ADVERTISER_ID=

# --- Pinterest ---
PINTEREST_APP_ID=
PINTEREST_APP_SECRET=
PINTEREST_ACCESS_TOKEN=

# --- YouTube ---
YOUTUBE_API_KEY=
YOUTUBE_CHANNEL_ID=

# --- Telegram ---
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHANNEL_ID=

# --- WhatsApp ---
WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_BUSINESS_ACCOUNT_ID=
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_VERIFY_TOKEN=

# --- LinkedIn ---
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_ACCESS_TOKEN=
LINKEDIN_COMPANY_ID=

# --- Ahrefs ---
AHREFS_API_KEY=

# --- Brevo ---
BREVO_API_KEY=
BREVO_SENDER_EMAIL=wincasetop@gmail.com
BREVO_SENDER_NAME=WinCase

# --- Stripe ---
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

# --- Przelewy24 ---
P24_MERCHANT_ID=
P24_POS_ID=
P24_CRC=
P24_API_KEY=

# --- OpenAI ---
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o

# --- reCAPTCHA v3 ---
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=

# --- n8n ---
N8N_BASE_URL=https://n8n.wincase.pro
N8N_API_KEY=
```

---

## 20. DEPLOYMENT (VPS Hostinger)

### 20.1 Server Requirements
- **OS:** Ubuntu 24.04 LTS | **RAM:** 8 GB | **CPU:** 4 vCPU | **Disk:** 80 GB NVMe SSD
- **PHP:** 8.4 (bcmath, ctype, curl, dom, fileinfo, gd, intl, mbstring, openssl, pdo_mysql, redis, zip)
- **Node.js:** 22 LTS | **Composer:** 2.8+ | **MySQL:** 8.4 LTS | **Redis:** 7.4 | **Nginx:** latest

### 20.2 Deployment Commands

```bash
ssh root@<VPS_IP>
cd /var/www
git clone git@github.com:wincase/wincase-crm.git
cd wincase-crm/backend

composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
php artisan migrate --force

# Seeders
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=BrandListingsSeeder
php artisan db:seed --class=SeoNetworkSitesSeeder
php artisan db:seed --class=LandingsSeeder

# Cache
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache

# Queue + WebSocket
php artisan queue:work redis --daemon
php artisan reverb:start --host=0.0.0.0 --port=8080

# Frontend
cd ../frontend && npm install && npm run build
cp -r dist/* /var/www/wincase-crm/backend/public/admin/

# SSL
certbot --nginx -d wincase.pro -d wincase-legalization.com -d wincase-job.com -d wincase.org

# Cron
# * * * * * cd /var/www/wincase-crm/backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 21. IMPLEMENTATION PLAN — 9 PHASES

| Phase | Module | Duration | Priority |
|-------|--------|----------|----------|
| **Phase 1** | Database: 18 new tables + migrations + enums + seeders | 1 week | CRITICAL |
| **Phase 2** | Leads Module: Controller, Service, n8n W01-W03 | 2 weeks | CRITICAL |
| **Phase 3** | POS Terminal: Controller, Service, receipt generation, staging flow | 1.5 weeks | CRITICAL |
| **Phase 4** | Accounting & Tax: Calculator, ReportService, config, all endpoints | 2 weeks | CRITICAL |
| **Phase 5** | Ads Module: 5 platform services, Controller, n8n W04-W07 | 2 weeks | HIGH |
| **Phase 6** | SEO Module: GSC/GA4/Ahrefs services, Controller, n8n W08-W09 | 1.5 weeks | HIGH |
| **Phase 7** | Social + Brand: Threads/LinkedIn, UnifiedPosting, Reviews, n8n W11,W22 | 2 weeks | HIGH |
| **Phase 8** | Dashboard: 9 Vue.js sections + 8 API + KPIService + Redis + Reverb | 2 weeks | HIGH |
| **Phase 9** | Flutter Mobile: Dashboard + Leads + POS + Notifications | 1.5 weeks | MEDIUM |

**Total estimated duration: 15-16 weeks**

### 21.1 Phase Dependencies

```
Phase 1 (DB) ──► Phase 2 (Leads) ────────────────► Phase 8 (Dashboard)
     │                                                     │
     ├──────► Phase 3 (POS) ──► Phase 4 (Accounting) ────┤
     │                                                     │
     ├──────► Phase 5 (Ads) ──────────────────────────────┤
     ├──────► Phase 6 (SEO) ──────────────────────────────┤
     ├──────► Phase 7 (Social+Brand) ─────────────────────┘
     │                                                     │
     │                                                     ▼
     └──────────────────────────────────────────── Phase 9 (Flutter)
```

Phase 1 first. Phases 2-7 can partially overlap. Phase 4 (Accounting) depends on Phase 3 (POS) for revenue data. Phase 8 (Dashboard) depends on all data modules. Phase 9 (Flutter) last.

---

<!-- Аннотация (RU):
ЧАСТЬ 6 из 7 — 21 API сервис, .env шаблон, деплой VPS Hostinger (Ubuntu 24.04, PHP 8.4, MySQL 8.4),
план реализации 9 фаз (15-16 недель): БД → Лиды → POS → Бухгалтерия → Реклама → SEO →
Соцсети+Бренд → Dashboard → Flutter. POS и бухгалтерия — CRITICAL priority.
Файл: docs/TZ_PART_6_DEPLOY_PLAN.md
-->
