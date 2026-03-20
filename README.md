<p align="center">
  <img src="public/images/wincase-logo.png" alt="WinCase CRM" width="200"/>
</p>

<h1 align="center">WinCase CRM v4.0</h1>

<p align="center">
  <strong>Immigration Bureau CRM for Polish Market</strong><br>
  Case management, client portal, document automation & identity verification
</p>

<p align="center">
  <a href="https://wincase.eu"><img src="https://img.shields.io/badge/Website-wincase.eu-0066CC?style=for-the-badge&logo=google-chrome&logoColor=white" alt="Website"/></a>
  <a href="https://wincasejobs.com"><img src="https://img.shields.io/badge/Jobs-wincasejobs.com-00AA55?style=for-the-badge&logo=briefcase&logoColor=white" alt="Jobs"/></a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel"/>
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white" alt="Bootstrap"/>
  <img src="https://img.shields.io/badge/MySQL-MariaDB_10.11-4479A1?style=flat-square&logo=mariadb&logoColor=white" alt="MariaDB"/>
  <img src="https://img.shields.io/badge/API_Endpoints-260+-green?style=flat-square" alt="Endpoints"/>
  <img src="https://img.shields.io/badge/Tables-55+-blue?style=flat-square" alt="Tables"/>
  <img src="https://img.shields.io/badge/License-Proprietary-red?style=flat-square" alt="License"/>
</p>

---

## Overview

WinCase CRM is a comprehensive case management system built for an immigration bureau operating in the Polish market. It handles the full lifecycle of immigration cases — from lead capture to document preparation, government submissions, and client communication.

### Key Capabilities

- **Case Management** — 28 phases from initial consultation to completion
- **3-Role System** — Boss (full access), Staff (16 abilities), Client (portal)
- **Identity Verification** — Authologic integration (mObywatel, Profil Zaufany, bank verification)
- **Document Automation** — Templates, generation, e-signatures, vault storage
- **Client Portal** — Self-service for clients to track cases and upload documents
- **News Network** — 7 news sites + 8 SEO satellites with AI-powered content
- **Mobile App** — Android + iOS with push notifications (Firebase FCM)

## Architecture

```
wincase/
├── app/
│   ├── Http/Controllers/Api/V1/    # 40+ API controllers
│   ├── Models/                      # 55+ Eloquent models
│   ├── Services/                    # Business logic layer
│   │   ├── News/                    # RSS → AI rewrite → WordPress
│   │   ├── Verification/           # Authologic service
│   │   └── Notifications/          # FCM, Telegram, SMS
│   └── Enums/                      # Type-safe enumerations
├── Admin/                           # Herozi Bootstrap 5 admin panel
├── database/
│   ├── migrations/                  # 55+ table schemas
│   └── seeders/                     # Demo data + sites config
├── routes/
│   ├── api.php                      # 316+ API routes
│   └── web.php                      # Admin panel routes
├── mobile/                          # Flutter mobile app
└── config/
    └── services.php                 # All integrations config
```

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12, PHP 8.2+, Sanctum Auth |
| **Database** | MariaDB 10.11 (production), SQLite (local) |
| **Admin Panel** | Herozi template, Bootstrap 5, Blade, Laravel Mix |
| **Mobile** | Flutter 3.x, Dart, Provider state management |
| **Push Notifications** | Firebase Cloud Messaging (FCM) |
| **Identity Verification** | Authologic (mObywatel, Profil Zaufany) |
| **AI Content** | Anthropic Claude API, OpenAI |
| **Hosting** | Hetzner VPS, cPanel, Apache |
| **Monitoring** | Google Analytics GA4, Telegram Bot |

## API Overview

| Module | Endpoints | Description |
|--------|-----------|-------------|
| **Auth** | 8 | Login, register, 2FA, password reset |
| **Cases** | 18 | Full case lifecycle management |
| **Clients** | 12 | Client profiles and portal |
| **Documents** | 15 | Upload, generate, sign, vault |
| **Leads** | 10 | Lead capture and conversion |
| **Finance** | 14 | Invoices, payments, accounting |
| **Calendar** | 8 | Events, reminders, hearings |
| **Staff** | 10 | Employee management |
| **Verification** | 9 | Authologic identity checks |
| **News** | 20 | RSS aggregation, AI rewrite, publish |
| **Dashboard** | 7 | KPI, stats, analytics |
| **Notifications** | 8 | FCM, email, Telegram, SMS |
| **Settings** | 12 | System configuration |
| **Reports** | 10 | Scheduled + generated reports |
| **SEO Network** | 8 | Satellite sites management |
| **Social** | 6 | Social media analytics |
| **Ads** | 6 | Google/Meta ads tracking |

> **Total: 260+ endpoints** across 28 functional phases

## Roles & Permissions

| Role | Access | Description |
|------|--------|-------------|
| **Boss** | Full (`*`) | Complete system access, settings, staff management |
| **Staff** | 16 abilities | CRM operations, case management, client handling |
| **Client** | Portal only | View own cases, upload documents, track progress |

Authentication uses **Laravel Sanctum** with dual mode:
- **Session auth** — Web admin panel
- **Bearer token** — Mobile app & API calls

## Integrations

| Service | Status | Purpose |
|---------|--------|---------|
| **Authologic** | Sandbox | mObywatel / Profil Zaufany / Bank verification |
| **Firebase FCM** | Active | Push notifications to mobile |
| **Telegram Bot** | Active | Admin alerts & notifications |
| **Google Analytics** | Active | GA4 tracking on all pages |
| **Anthropic Claude** | Active | AI content rewriting |
| **WhatsApp** | Configured | Client messaging |
| **SMS (SMSApi)** | Configured | SMS notifications |
| **Twilio** | Configured | Voice/SMS fallback |

## News Network

Automated content pipeline for 7 news sites:

```
27 RSS Sources → AI Rewrite (Claude/GPT) → WordPress Auto-Publish
```

| Site | Niche |
|------|-------|
| polandpulse.news | Poland news |
| eurogamingpost.com | Gaming |
| techpulse.news | Technology |
| bizeurope.news | Business EU |
| sportpulse.news | Sports |
| diaspora.news | Diaspora |
| trendwatch.news | Trends |

## SEO Satellite Network

8 specialized sites for immigration services:

| Domain | Focus |
|--------|-------|
| legalizacja-polska.pl | Legalization in Poland |
| karta-pobytu.info | Residence permit |
| work-permit-poland.com | Work permits (EN) |
| vnzh-polsha.com | Residence permit (RU) |
| praca-dla-obcokrajowcow.pl | Jobs for foreigners |
| posvidka-polshcha.com | Permits (UA) |
| immigration-warsaw.com | Immigration Warsaw (EN) |
| visa-polska.com | Visa services |

## Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- MariaDB 10.11+ / MySQL 8.0+

### Installation

```bash
git clone https://github.com/vitalyjkoptev/wincase.git
cd wincase
composer install
cp docs/env_template.env .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

### Mobile App

```bash
cd mobile
flutter pub get
flutter run
```

## Deployment

Production: Hetzner VPS with cPanel/Apache

```bash
./deploy-to-server.sh
```

## Contact

- **Website:** [wincase.eu](https://wincase.eu)
- **Jobs Portal:** [wincasejobs.com](https://wincasejobs.com)
- **Email:** wincasetop@gmail.com

---

<p align="center">
  <sub>Built with Laravel, Bootstrap & Flutter | Warsaw, Poland</sub>
</p>
