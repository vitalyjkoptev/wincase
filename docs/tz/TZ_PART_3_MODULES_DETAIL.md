# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 3 of 6: New Module Details — Leads / Ads / SEO / Brand / Landings

---

## 6. MODULE: LEADS MANAGEMENT

### 6.1 Lead Sources (14 channels)

| Source | Entry Method | Integration |
|--------|-------------|-------------|
| Form wincase.pro | POST /api/v1/leads | Direct API |
| Form wincase-job.com | POST /api/v1/leads | Direct API |
| Form wincase-legalization.com | POST /api/v1/leads | Direct API |
| Form wincase.org | POST /api/v1/leads | Direct API |
| Facebook Lead Form | Meta Leads API → n8n | n8n webhook |
| Instagram Lead Form | Meta Leads API → n8n | n8n webhook |
| TikTok Lead Form | TikTok Leads API → n8n | n8n webhook |
| Google Ads Lead Form | Google Ads API → n8n | n8n webhook |
| WhatsApp incoming | WhatsApp Cloud API → n8n | n8n webhook |
| Telegram Bot | Telegram Bot API → n8n | n8n webhook |
| Threads DM | Threads API (Meta) → n8n | n8n webhook |
| Phone call +48 579 266 493 | Manual entry / Call tracking | Manual |
| Walk-in (office) | Manual entry | Manual |
| Referral | Manual entry | Manual |

### 6.2 Lead Routing (Auto-Assignment)

| Rule | Condition | Assignment |
|------|-----------|------------|
| By Language | language = ru OR ua | Manager RU/UA |
| By Language | language = en AND country IN (IN, PH) | Manager Asian |
| By Language | language = hi OR tl | Manager Asian |
| By Language | language = es OR tr | Manager ES/TR |
| By Service | service = job_centre | Manager Job Centre |
| Paid Lead | source IN (google_ads, facebook_ads) AND gclid/fbclid | Priority: HIGH |
| Off-hours | created_at NOT IN 9-18 Mon-Fri | Auto-reply WhatsApp |
| Round Robin | Default | Next available |

**Implementation:** `LeadRoutingService.php` executes rules sequentially. First matching rule wins. Default = Round Robin among available managers.

### 6.3 n8n Lead Workflow (W01 — 12 steps)

| Step | Action | n8n Node |
|------|--------|----------|
| 1 | Lead arrives (any channel) | Webhook |
| 2 | Detect language, country (by URL, IP) | Function |
| 3 | Save to DB | HTTP → POST /api/v1/leads |
| 4 | Lead Routing (assign manager) | Switch → PATCH |
| 5 | WhatsApp greeting (in client's language) | WhatsApp Cloud API |
| 6 | Email Brevo (drip start) | Brevo API |
| 7 | Telegram alert to manager | Telegram Bot API |
| 8 | Google Ads: Offline Conversion (if gclid) | Google Ads API |
| 9 | Facebook CAPI: Lead event (if fbclid) | Facebook CAPI |
| 10 | TikTok Events API (if ttclid) | TikTok Events API |
| 11 | Google Sheets: add row | Sheets API |
| 12 | 30 min — if no contact: repeat alert | Wait → Check → Alert |

### 6.4 Lead Funnel Statuses

`new` → `contacted` → `consultation` → `contract` → `paid` → (end)
Alternative: `new` → `rejected` or `spam` at any stage

---

## 7. MODULE: ADS CAMPAIGNS

### 7.1 Features
- **Overview:** Unified table of all 5 platforms + graphs (line, pie, bar)
- **Google Ads:** 9 campaigns (from Marketing Guide 04), keywords, conversions, budget
- **Meta Ads:** 4 campaigns (from Guide 09), Lead Forms, audiences
- **TikTok Ads:** 4 campaigns (from Guide 10), Spark Ads, Events
- **YouTube Ads:** 5 campaigns (from Guide 12), views, CPV
- **Pinterest Ads:** 3 campaigns (from Guide 11), promoted pins
- **Budget Planner:** allocation across platforms, ROI forecast
- **Reports:** weekly/monthly, export PDF/Excel

### 7.2 Data Sync
- **Google Ads, Meta Ads, TikTok Ads:** n8n sync every 6 hours (W04, W05, W06)
- **Pinterest, YouTube Ads:** n8n sync every 12 hours (W07)
- **Storage:** `ads_performance` table (daily granularity per campaign)
- **Metrics:** impressions, clicks, cost, conversions, conversion_value, CPC, CPL, CTR, leads_count

### 7.3 Offline Conversions
- Google Ads: when lead.status = 'paid', send gclid + conversion value (W17)
- Facebook CAPI: on lead creation, send Lead event with fbclid (W18)
- TikTok Events: on lead creation, send event with ttclid (W19)

---

## 8. MODULE: SEO & DOMAIN AUTHORITY (4 domains)

### 8.1 Features
- **GSC Dashboard:** performance of all 4 domains (wincase.pro, wincase-legalization.com, wincase-job.com, wincase.org)
- **GA4 Dashboard:** traffic, behavior, conversions (4 properties)
- **Domain Authority:** DA for each of 4 domains + trend graph
- **Keywords Tracker:** positions of top-50 keywords daily
- **Backlinks Monitor:** new/lost, referring domains
- **SEO Network:** 8 satellite sites — status, DA, articles
- **Competitors:** DA and positions comparison
- **Reviews Hub:** Google, Trustpilot, Facebook, GoWork — unified table

### 8.2 Data Sync
- **GSC + GA4:** daily sync at 6:00 (W08) for all 4 domains
- **SEO Weekly Report:** Mon 8:00, DA + backlinks (W09)
- **SEO Network Article Check:** weekly (W20)
- **Storage:** `seo_data` table (UNIQUE: domain + date + source)

---

## 9. MODULE: BRAND & REPUTATION

### 9.1 Features
- **Trademark:** UPRP (Poland) + EUIPO (EU) status tracking, deadlines, documents
- **Business Listings:** 50+ directories, NAP consistency check for all 4 domains
- **Reviews Hub:** Google (87), Trustpilot (30), Facebook (25), GoWork (10) — unified view
- **Review Requests:** n8n automatic chain after case completion, statistics (W15)
- **PR & Media:** press releases, HARO, journalists database
- **Wikipedia:** article status, sources, editor tracking
- **Knowledge Panel:** Wikidata entity, Crunchbase profile, Schema.org markup, Google claim

### 9.2 NAP Consistency Check
Monthly n8n workflow (W21) checks all 4 domains across 50+ directories:
- Name matches (WinCase — Immigration Bureau | Biuro Imigracyjne)
- Address matches (ul. Hoza 66/68 lok. 211, 00-682 Warszawa)
- Phone matches (+48 579 266 493)
- Result stored in `brand_listings` table with `nap_consistent` boolean

---

## 10. MODULE: LANDINGS

### 10.1 Features
- **Overview:** 14+ landing pages across 4 domains
- **Form Submissions:** all form data → leads table with UTM + click IDs
- **A/B Testing:** wincase-legalization.com variant A vs B, conversion tracking
- **Page Speed:** Lighthouse scores per landing page
- **Conversions:** per landing page, per language, per traffic source

### 10.2 Form Security (Public POST endpoint)
- **Rate limit:** 10 requests/min/IP (`LeadRateLimitMiddleware`)
- **Honeypot:** hidden field check (`HoneypotMiddleware`)
- **reCAPTCHA v3:** score validation (`RecaptchaMiddleware`)
- **CORS:** only 4 wincase domains allowed (`CorsDomainsMiddleware`)

---

<!-- Аннотация (RU):
ЧАСТЬ 3 из 6 технического задания WINCASE CRM v4.0.
Содержит детальное описание 5 новых модулей:
1. Leads Management — 14 источников, авто-маршрутизация по 8 правилам, n8n workflow 12 шагов, воронка 7 статусов
2. Ads Campaigns — 5 рекламных платформ (25 кампаний), синхронизация каждые 6/12ч, offline conversions
3. SEO & Domain Authority — 4 домена в GSC/GA4, Keywords Tracker, Backlinks, SEO-сеть 8 сайтов
4. Brand & Reputation — торговая марка UPRP+EUIPO, 50+ каталогов NAP check, Reviews Hub, Wikipedia, Knowledge Panel
5. Landings — 14+ страниц, формы с защитой (rate limit, honeypot, reCAPTCHA), A/B тесты
Следующая часть: База данных (21 таблица).
-->
