# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 1 of 7: Overview / Architecture / Domains / Infrastructure

**Version:** v4.0 FINAL | **Date:** 2026-02-17

---

## SYSTEM INCLUDES

| Component | Scope |
|-----------|-------|
| **CRM Core** | Clients, Cases, Documents, Calendar, Tasks, Notifications |
| **Finance** | Invoices, Payments, Stripe, Przelewy24 |
| **POS Terminal** | Cash + card payments at office, staging zone, receipt generation, refund flow |
| **Accounting & Tax** | All Polish taxes (PIT/CIT/VAT/ZUS), tax calculator, report generation, ryczałt/skala/liniowy |
| **Leads Management** | 14 sources, auto-routing, funnel, UTM/click tracking, conversion to client |
| **Ads Campaigns** | Google Ads, Meta Ads, TikTok Ads, Pinterest Ads, YouTube Ads — sync + offline conversions |
| **SEO & Domain Authority** | GSC + GA4 for 4 domains, keywords, backlinks, SEO network (8 satellite sites) |
| **Social Media** | 8 platforms: Facebook, Instagram, Threads, TikTok, YouTube, Telegram, Pinterest, LinkedIn |
| **Landings** | 14+ landing pages on 4 domains, 8 languages, A/B testing, conversion tracking |
| **Brand & Reputation** | Trademark UPRP+EUIPO, 50+ directory listings, NAP check, Reviews Hub, Wikipedia, Knowledge Panel |
| **AI Automation** | n8n 22 workflows, OpenAI content generation, chatbot |
| **Dashboard** | Consolidated panel — 9 sections: KPI, Cases, Leads, Finance, POS, Ads, Social, SEO, Accounting |
| **Mobile** | Flutter app (iOS + Android): Dashboard, Leads, POS, Notifications |

**Total: 32 database tables, 57+ API endpoints, 16 enums, 22 n8n workflows, 8 languages, 4 domains, 8 social platforms**

---

## 1. PROJECT OVERVIEW

### 1.1 Project Name
**WINCASE CRM** — Immigration Bureau CRM System with Integrated Marketing Platform, POS Terminal & Accounting

### 1.2 Company Details
- **Name:** WinCase — Immigration Bureau | Biuro Imigracyjne
- **Address:** ul. Hoza 66/68 lok. 211, 00-682 Warszawa, Poland
- **Phone:** +48 579 266 493
- **Email:** wincasetop@gmail.com
- **WhatsApp:** wa.me/48579266493
- **Working Hours:** Mon-Fri 9:00-18:00, Sat 10:00-14:00

### 1.3 Technology Stack
- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Vue.js 3.5 (Composition API, Pinia 3, Vite 7)
- **Mobile:** Flutter 3.29+ (iOS + Android)
- **Database:** MySQL 8.4 LTS
- **Cache:** Redis 7.4
- **Automation:** n8n 1.x (self-hosted)
- **Server:** VPS Hostinger (Ubuntu 24.04 LTS)
- **SSL:** Let's Encrypt (auto-renew via Certbot)
- **WebSocket:** Laravel Reverb (native first-party WebSocket)
- **AI:** OpenAI API (GPT-4o, content generation)
- **Email:** Brevo (Sendinblue)
- **Payments:** Stripe + Przelewy24 + POS Terminal (cash + card)
- **Build Tools:** Vite 7, Tailwind CSS 4, TypeScript 5.x
- **Package Manager:** Composer 2.8+, npm 10+ / pnpm 10+
- **Node.js:** 22 LTS

### 1.4 Project Scope (v4.0)
WINCASE CRM v4.0 is a full-cycle immigration bureau platform: CRM (clients, cases, documents), finance (invoices, payments), POS terminal (office cash/card payments with staging zone for owner approval), accounting (all Polish taxes — PIT, CIT, VAT, ZUS with report generation), marketing (leads from 14 channels, ads on 5 platforms, SEO for 4 domains, social media on 8 platforms including Threads, brand management with 50+ directories). The system serves 4 domains, supports 8 languages, and is built on Laravel 12 + PHP 8.4.

---

## 2. ARCHITECTURE

### 2.1 High-Level Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                        4 DOMAINS (FRONTEND)                      │
│  wincase.pro │ wincase-legalization.com │ wincase-job.com │ .org │
└──────────┬──────────────┬───────────────┬───────────────┬────────┘
           │              │               │               │
           ▼              ▼               ▼               ▼
┌──────────────────────────────────────────────────────────────────┐
│                    NGINX REVERSE PROXY (VPS)                     │
│                    SSL Termination + Rate Limiting                │
└──────────────────────────┬───────────────────────────────────────┘
                           │
           ┌───────────────┼───────────────┐
           ▼               ▼               ▼
   ┌──────────────┐ ┌────────────┐ ┌──────────────┐
   │ Laravel 12   │ │ Vue.js SPA │ │ n8n Engine   │
   │ API Backend  │ │ Admin Panel│ │ 22 Workflows │
   │ 57+ endpoints│ │ 60+ comps  │ │              │
   └──────┬───────┘ └─────┬──────┘ └──────┬───────┘
          │               │               │
          ▼               │               ▼
   ┌──────────────┐       │        ┌──────────────┐
   │ MySQL 8.4    │◄──────┘        │ External APIs│
   │ 32 tables    │                │ Google, Meta │
   │              │                │ TikTok, etc. │
   └──────┬───────┘                └──────────────┘
          │
   ┌──────┴───────┐
   │ Redis 7.4    │
   │ Cache+Queue  │
   │ WebSocket    │
   └──────────────┘
```

### 2.2 Domain Architecture

| Domain | Purpose | Technology | Traffic Sources |
|--------|---------|------------|-----------------|
| wincase.pro | Main site + landings (8 languages) | Laravel 12 + Blade | Google Ads, SEO, Maps, Social |
| wincase-legalization.com | Legalization A/B test mirror | Laravel 12 + Blade | Google Ads A/B |
| wincase-job.com | Job Centre platform | Vue.js 3.5 (SPA) | All channels — Job Centre |
| wincase.org | Corporate + future SaaS | Laravel + Vue.js | PR, LinkedIn, Organic |

### 2.3 Landing Pages Map (15+ pages)

| URL | Language | Audience | Traffic Sources |
|-----|----------|----------|-----------------|
| wincase.pro/ | PL | Local Polish | Google Ads PL, SEO, Maps |
| wincase.pro/ru/karta-pobytu | RU | Russian-speaking | Google Ads RU, Telegram, FB |
| wincase.pro/ua/karta-pobytu | UA | Ukrainians | Google Ads UA, Telegram, FB |
| wincase.pro/en/work-permit | EN | All non-EU | Google Ads EN, FB, TikTok |
| wincase.pro/hi | HI+EN | India (45K) | Google Ads HI, FB, TikTok |
| wincase.pro/tl | TL+EN | Philippines (29K) | Google Ads TL, FB, TikTok |
| wincase.pro/es | ES | Latin America | Google Ads ES, FB, TikTok |
| wincase.pro/tr | TR | Turkey (26K) | Google Ads TR, FB |
| wincase.pro/consultation | Multi | All (booking) | All channels |
| wincase.pro/checklist | Multi | All (documents) | SEO, Email |
| wincase.pro/reviews | Multi | All (trust) | Sitelinks, SEO |
| wincase.pro/blog | Multi | SEO traffic | Organic |
| wincase-legalization.com | PL/RU/EN | A/B test | Google Ads A/B |
| wincase-job.com | Multi (8) | Job seekers | All Job Centre channels |
| wincase.org | EN/PL | Corporate / SaaS | PR, LinkedIn, Organic |

---

## 3. SOCIAL MEDIA — 8 PLATFORMS

| Platform | Account | API | CRM Features |
|----------|---------|-----|--------------|
| Facebook | WinCase - Легалізація в Польщі | Graph API v19.0 | Posts, Stories, comments, Lead Forms, Messenger, analytics |
| Instagram | @wincase.legalization.pl | Instagram Graph API | Posts, Reels, Stories, Direct, Insights, Shopping |
| Threads | @wincase.legalization.pl | Threads API (Meta) | Text posts, images, carousel, replies, analytics, reposts |
| TikTok | @wincase.legalization.pl | TikTok Business API | Video, Spark Ads, analytics, Lead Forms, Events API |
| YouTube | @WinCase | YouTube Data API v3 | Video, Shorts, playlists, Community, analytics, Live |
| Telegram | @WinCasePro | Telegram Bot API | Bot, channel, publications, notifications, Login |
| Pinterest | @wincasepro | Pinterest API v5 | Pins, boards, Rich Pins, analytics, auto-posting |
| LinkedIn | WinCase | LinkedIn API | Company page, articles, Job Centre vacancies, analytics |

### 3.1 Threads Integration Details

Threads (by Meta) serves as a text-based social network for: expert content (text posts), quick updates (immigration news), engagement (Q&A replies), cross-posting from Instagram.

**API Endpoints used:**
- `POST /v1.0/{user_id}/threads` — Publish text + optional image/video
- `POST /v1.0/{user_id}/threads` — Carousel (up to 10 images)
- `POST /v1.0/{thread_id}/replies` — Reply to a thread
- `GET /v1.0/{user_id}/threads` — List published threads
- `GET /v1.0/{thread_id}/insights` — Views, likes, replies, reposts, quotes
- `GET /v1.0/me` — Followers count, username, bio

### 3.2 Threads CRM Module Features
- Publish via Unified Social Dashboard (together with all 7 other platforms)
- AI text generation for Threads (OpenAI: short, engaging, with CTA)
- Auto-posting: n8n workflow — content_calendar → Threads API
- Cross-posting: Instagram Reel caption → Threads text post (automatic)
- Analytics: views, likes, replies, reposts stored in social_analytics table
- Unified Inbox: replies on Threads = incoming messages in CRM

---

<!-- Аннотация (RU):
ЧАСТЬ 1 из 7 технического задания WINCASE CRM v4.0 FINAL.
Содержит: обзор проекта, данные компании, технологический стек (Laravel 12 + Vue.js 3.5 + Flutter + MySQL 8.4 + Redis 7.4 + n8n).
Архитектура системы: 4 домена, 1 VPS Hostinger Ubuntu 24.04, 32 таблицы, 57+ API endpoints.
Система включает: CRM, финансы, POS-терминал, бухгалтерию (все налоги Польши), лиды (14 каналов),
рекламу (5 платформ), SEO (4 домена), 8 соцсетей (включая Threads), бренд (50+ каталогов),
лендинги (15+ страниц, 8 языков), AI автоматизация (22 n8n workflows).
Файл: docs/TZ_PART_1_OVERVIEW.md
-->
