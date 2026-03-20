# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 6 of 6: API Keys / Deployment / Implementation Plan / .env Template

---

## 15. API KEYS & REGISTRATIONS (21 services)

**Single registration credentials for ALL services:**
- Email: wincasetop@gmail.com
- Phone: +48 579 266 493
- Domains: wincase.pro, wincase-legalization.com, wincase-job.com, wincase.org

| Service | Key Type | Storage |
|---------|----------|---------|
| Google Ads | OAuth 2.0 + Dev Token | Laravel .env |
| Google Search Console | OAuth 2.0 | Laravel .env |
| Google Analytics 4 | OAuth 2.0 | Laravel .env |
| Google Tag Manager | OAuth 2.0 | Laravel .env |
| Google Business Profile | OAuth 2.0 | Laravel .env |
| Google Maps API | API Key | Laravel .env |
| Google Places API | API Key | Laravel .env |
| Meta Business Suite | System User Token | Laravel .env |
| Meta Threads API | OAuth 2.0 (via Meta) | Laravel .env |
| TikTok Business Center | Access Token | Laravel .env |
| Pinterest Business | OAuth 2.0 | Laravel .env |
| YouTube Data API | API Key + OAuth | Laravel .env |
| Telegram Bot | Bot Token (@BotFather) | Laravel .env |
| WhatsApp Cloud API | System User Token | Laravel .env |
| Ahrefs API | API Key | Laravel .env |
| LinkedIn API | OAuth 2.0 | Laravel .env |
| Brevo (Sendinblue) | API Key | Laravel .env |
| Stripe | Secret Key | Laravel .env |
| OpenAI | API Key | Laravel .env |
| EUIPO (trademark) | — | Documents |
| UPRP (trademark PL) | — | Documents |

---

## 16. .ENV TEMPLATE

```env
# === WINCASE CRM v4.0 === Laravel 12 + PHP 8.4 === .env Configuration ===

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

# --- Queue ---
QUEUE_CONNECTION=redis

# --- WebSocket (Laravel Reverb — native, no Pusher needed) ---
BROADCAST_DRIVER=reverb
REVERB_APP_ID=wincase-crm
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=https

# --- Google OAuth 2.0 (Shared) ---
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

# --- Google Ads ---
GOOGLE_ADS_DEVELOPER_TOKEN=
GOOGLE_ADS_CLIENT_CUSTOMER_ID=
GOOGLE_ADS_REFRESH_TOKEN=

# --- Google Search Console (4 domains) ---
GSC_PROPERTY_WINCASE_PRO=
GSC_PROPERTY_LEGALIZATION=
GSC_PROPERTY_JOB=
GSC_PROPERTY_ORG=

# --- Google Analytics 4 (4 properties) ---
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

# --- Meta Business Suite (FB + IG + Threads) ---
META_APP_ID=
META_APP_SECRET=
META_SYSTEM_USER_TOKEN=
META_PAGE_ID=
META_IG_USER_ID=
META_THREADS_USER_ID=
META_PIXEL_ID=

# --- TikTok Business ---
TIKTOK_APP_ID=
TIKTOK_APP_SECRET=
TIKTOK_ACCESS_TOKEN=
TIKTOK_PIXEL_ID=
TIKTOK_ADVERTISER_ID=

# --- Pinterest Business ---
PINTEREST_APP_ID=
PINTEREST_APP_SECRET=
PINTEREST_ACCESS_TOKEN=

# --- YouTube Data API ---
YOUTUBE_API_KEY=
YOUTUBE_CHANNEL_ID=

# --- Telegram Bot ---
TELEGRAM_BOT_TOKEN=
TELEGRAM_CHANNEL_ID=

# --- WhatsApp Cloud API ---
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

# --- Brevo (Sendinblue) ---
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

# --- Mail ---
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
```

---

## 17. DEPLOYMENT (VPS Hostinger)

### 17.1 Server Requirements
- **OS:** Ubuntu 24.04 LTS
- **RAM:** 8 GB minimum
- **CPU:** 4 vCPU
- **Disk:** 80 GB NVMe SSD
- **PHP:** 8.4 (with extensions: bcmath, ctype, curl, dom, fileinfo, gd, intl, json, mbstring, openssl, pdo_mysql, tokenizer, xml, zip, redis)
- **Node.js:** 22 LTS (npm 10+ or pnpm 10+)
- **Composer:** 2.8+
- **MySQL:** 8.4 LTS
- **Redis:** 7.4
- **Nginx:** latest stable
- **n8n:** 1.x (self-hosted via Docker or native)
- **SSL:** Certbot (Let's Encrypt)
- **Laravel Reverb:** native WebSocket server (replaces Pusher)

### 17.2 Deployment Commands (macOS Terminal → VPS)

```bash
# SSH to VPS
ssh root@<VPS_IP>

# Clone project
cd /var/www
git clone git@github.com:wincase/wincase-crm.git
cd wincase-crm/backend

# Install Laravel 12 dependencies (requires PHP 8.4 + Composer 2.8+)
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate

# Run migrations (MySQL 8.4)
php artisan migrate --force

# Run seeders (initial data)
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=BrandListingsSeeder
php artisan db:seed --class=SEONetworkSitesSeeder

# Cache (Laravel 12 optimized)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Queue worker (supervisor)
php artisan queue:work redis --daemon

# Start Laravel Reverb WebSocket server (native, no Pusher)
php artisan reverb:start --host=0.0.0.0 --port=8080

# Build Vue.js 3.5 frontend (Vite 7 + Node.js 22 LTS)
cd ../frontend
npm install
npm run build

# Copy build to Nginx public
cp -r dist/* /var/www/wincase-crm/backend/public/admin/

# Nginx config (4 domains) — php8.4-fpm
# See docker/nginx/default.conf

# SSL certificates
certbot --nginx -d wincase.pro -d wincase-legalization.com -d wincase-job.com -d wincase.org

# Cron (Laravel scheduler)
# Add to crontab:
# * * * * * cd /var/www/wincase-crm/backend && php artisan schedule:run >> /dev/null 2>&1

# Reload all services after deploy (Laravel 12 feature)
php artisan reload
```

---

## 18. IMPLEMENTATION PLAN — 8 PHASES

| Phase | Module | Duration | Priority |
|-------|--------|----------|----------|
| **Phase 1** | Database: 7 new tables + migrations + updates to existing tables | 1 week | CRITICAL |
| **Phase 2** | Leads Module: Model, Controller, Service (routing + conversion + funnel), n8n W01-W03 | 2 weeks | CRITICAL |
| **Phase 3** | Ads Module: 5 services (Google/Meta/TikTok/Pinterest/YouTube), Controller, n8n W04-W07 | 2 weeks | HIGH |
| **Phase 4** | SEO Module: GSC/GA4/Ahrefs services, Controller, n8n W08-W09, W20 | 1.5 weeks | HIGH |
| **Phase 5** | Social Module update: Threads + LinkedIn services, UnifiedPosting, UnifiedInbox, n8n W11, W22 | 1.5 weeks | HIGH |
| **Phase 6** | Brand Module: Trademark, Listings, Reviews, Wikipedia, KnowledgePanel, n8n W15, W21 | 1 week | MEDIUM |
| **Phase 7** | Dashboard: 8 Vue.js widgets + 8 API endpoints + KPIService + Redis caching + WebSocket | 2 weeks | HIGH |
| **Phase 8** | Flutter Mobile: Dashboard + Leads + Notifications screens, Push notifications | 1.5 weeks | MEDIUM |

**Total estimated duration: 12-13 weeks**

### 18.1 Phase Dependencies

```
Phase 1 (DB) ──────────► Phase 2 (Leads) ──────► Phase 7 (Dashboard)
     │                        │                         │
     ├──────────────────► Phase 3 (Ads) ───────────────┤
     │                        │                         │
     ├──────────────────► Phase 4 (SEO) ───────────────┤
     │                        │                         │
     ├──────────────────► Phase 5 (Social) ────────────┤
     │                                                  │
     └──────────────────► Phase 6 (Brand) ─────────────┘
                                                        │
                                                        ▼
                                                  Phase 8 (Flutter)
```

Phase 1 (Database) must be completed first. Phases 2-6 can run in parallel. Phase 7 (Dashboard) depends on all data modules (2-6). Phase 8 (Flutter) depends on Phase 7.

---

## 19. SUMMARY

| Metric | Value |
|--------|-------|
| Domains | 4 (wincase.pro, wincase-legalization.com, wincase-job.com, wincase.org) |
| Social Platforms | 8 (FB, IG, Threads, TikTok, YouTube, Telegram, Pinterest, LinkedIn) |
| Languages | 8 (PL, EN, RU, UA, HI, TL, ES, TR) |
| DB Tables (total) | 21 (14 existing + 7 new) |
| API Endpoints | 30+ (new) + existing |
| n8n Workflows | 22 |
| API Keys/Tokens | 21 services |
| Landing Pages | 14+ |
| Vue.js Components | 55+ |
| Laravel Services | 35+ |
| Flutter Screens | ~15 |
| Tech Stack | Laravel 12 + PHP 8.4 + Vue.js 3.5 + Vite 7 + Flutter 3.29+ + MySQL 8.4 + Redis 7.4 + n8n + Reverb |
| Estimated Duration | 12-13 weeks (8 phases) |

---

**Document Version:** v4.0 FINAL
**Created:** 2026-02-16
**Source:** WINCASE_CRM_v4_FINAL_UA.docx + previous chat history
**Previous Chat:** https://claude.ai/chat/82c5c9fe-d96a-4923-aa3c-bab54ca32a46

---

<!-- Аннотация (RU):
ЧАСТЬ 6 из 6 (финальная) технического задания WINCASE CRM v4.0.
Содержит: список 21 API сервиса для регистрации (все на единый email/телефон),
полный шаблон .env файла со всеми ключами (Google OAuth, Ads, GSC 4 домена, GA4 4 property,
Meta+Threads, TikTok, Pinterest, YouTube, Telegram, WhatsApp, LinkedIn, Ahrefs, Brevo, Stripe, P24, OpenAI, reCAPTCHA, n8n),
требования к серверу VPS Hostinger (Ubuntu 24.04, 8GB RAM, PHP 8.4, MySQL 8.4, Redis 7.4, Node 22 LTS),
команды деплоя через macOS Terminal → SSH → VPS,
план реализации из 8 фаз (12-13 недель): БД → Лиды → Реклама → SEO → Соцсети → Бренд → Dashboard → Flutter.
Зависимости фаз: Phase 1 (DB) первая, Phases 2-6 параллельно, Phase 7 Dashboard зависит от всех, Phase 8 Flutter последняя.
-->
