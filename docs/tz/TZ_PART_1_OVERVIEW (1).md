# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 1 of 6: Overview / Architecture / Domains / Infrastructure

---

## 1. PROJECT OVERVIEW

### 1.1 Project Name
**WINCASE CRM** — Immigration Bureau CRM System with Integrated Marketing Platform

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
- **Payments:** Stripe + Przelewy24
- **Build Tools:** Vite 7, Tailwind CSS 4, TypeScript 5.x
- **Package Manager:** Composer 2.8+, npm 10+ / pnpm 10+
- **Node.js:** 22 LTS

### 1.4 Project Scope (v4.0)
WINCASE CRM v4.0 extends the existing CRM (clients, cases, documents, finance, calendar) with 6 new marketing modules: Leads Management, Ads Performance, SEO & Domain Authority, Landings, Social Media (8 platforms including Threads), Brand & Reputation. The system serves 4 domains and supports 8 languages. Built on Laravel 12 + PHP 8.4 for maximum performance and security support through February 2027.

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
   │ 30+ endpoints│ │ 55+ comps  │ │              │
   └──────┬───────┘ └─────┬──────┘ └──────┬───────┘
          │               │               │
          ▼               │               ▼
   ┌──────────────┐       │        ┌──────────────┐
   │ MySQL 8.4    │◄──────┘        │ External APIs│
   │ 21 tables    │                │ Google, Meta │
   │              │                │ TikTok, etc. │
   └──────┬───────┘                └──────────────┘
          │
   ┌──────┴───────┐
   │ Redis 7      │
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

### 2.3 DNS & Hosting

All 4 domains are registered at Hostinger, hosted on VPS Hostinger (same server), with Let's Encrypt SSL auto-renewal. Each domain has its own Google Search Console property and GA4 property (G-XXXX1 through G-XXXX4).

### 2.4 Landing Pages Map (14+ pages)

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
ЧАСТЬ 1 из 6 технического задания WINCASE CRM v4.0.
Содержит: обзор проекта, данные компании, технологический стек (Laravel 12 + Vue.js 3.5 + Flutter + MySQL 8.4 + Redis 7.4 + n8n),
архитектуру системы (4 домена, 1 VPS сервер Hostinger Ubuntu 24.04), карту 14+ лендингов на 8 языках,
описание 8 социальных сетей с детальной интеграцией Threads API (Meta).
Следующая часть: Модули CRM Dashboard + Menu.
-->
