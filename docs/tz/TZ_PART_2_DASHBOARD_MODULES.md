# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 2 of 6: Consolidated Dashboard + Web Panel Modules

---

## 4. CONSOLIDATED DASHBOARD — 8 SECTIONS

The main page (Dashboard) combines ALL data from all modules and services into a single screen. The administrator sees: CRM, cases, finances, 8 social networks, Google Ads, Meta Ads, TikTok Ads, SEO, 4 domains, leads — on one page. Each block = widget. Widgets are draggable and configurable.

**Real-time updates:** Every 60 sec polling OR WebSocket (Laravel Reverb — native first-party WebSocket server)
**API:** `GET /api/v1/dashboard/kpi`

### 4.1 Section Layout

| Section | Position | Data Sources |
|---------|----------|--------------|
| Header KPI Bar | Top, full width | CRM + Cases + Finance + Leads |
| Cases & Clients | Left top, 50% | cases, clients, hearings, tasks |
| Leads & Conversions | Right top, 50% | leads, utm_tracking, forms |
| Finance | Left center, 50% | invoices, payments |
| Marketing — Ads | Right center, 50% | Google Ads, Meta, TikTok, Pinterest, YouTube APIs |
| Social Media (8) | Left bottom, 50% | social_accounts, social_posts, social_analytics |
| SEO & Domain (4 domains) | Right bottom, 50% | GSC API, GA4 API, Ahrefs/Moz API |
| Notifications + Tasks | Right sidebar (fold) | notifications, tasks, calendar |

### 4.2 Header KPI Bar — 10 Cards

| KPI | Source | Formula / API |
|-----|--------|---------------|
| Active Cases | cases | COUNT WHERE status IN (new, in_progress, pending, under_review) |
| New Leads (today) | leads | COUNT WHERE date = today |
| Lead Conversion | leads | converted / total * 100 (30 days) |
| Revenue (month) | payments | SUM(amount) WHERE month = current AND status = completed |
| Pending Payment | invoices | SUM(amount) WHERE status = pending |
| Google Ads CPL | ads_performance | cost / leads WHERE platform = google_ads (7 days) |
| Meta CPL | ads_performance | cost / leads WHERE platform = meta_ads (7 days) |
| Social Reach (7d) | social_analytics | SUM(reach) all 8 platforms for 7 days |
| Google Reviews | Google Places API | rating + count |
| Domain Authority | seo_data | DA (wincase.pro) latest |

### 4.3 Section: Cases & Clients
- **Mini Kanban Board:** 5 columns (New / In Progress / Pending / Under Review / Completed) with case count. Click = navigate to Cases module
- **Today's Tasks:** list from tasks + hearings + calendar with checkboxes
- **Last 5 Clients:** name, photo, case type, assigned lawyer
- **Next 5 Hearings:** date, time, client, court, countdown timer

### 4.4 Section: Leads & Conversions

**4.4.1 Lead Funnel:**

| Stage | Count | Conversion | Avg Time |
|-------|-------|------------|----------|
| New Lead (all channels) | 120 | 100% | 0 days |
| First Contact (WhatsApp/Call) | 96 | 80% | 0.5 days |
| Consultation Conducted | 54 | 45% | 2 days |
| Contract Signed | 41 | 34% | 5 days |
| Payment Received | 38 | 32% | 7 days |

**4.4.2 Leads by Channel (pie chart):**

| Channel | % Leads | CPL | Conv |
|---------|---------|-----|------|
| Google Ads | 32% | 2.8 EUR | 38% |
| Facebook/Instagram Ads | 22% | 3.5 EUR | 28% |
| TikTok Ads | 10% | 1.5 EUR | 22% |
| Threads (organic) | 3% | 0 EUR | 30% |
| Organic Google (SEO) | 15% | 0 EUR | 45% |
| Telegram | 5% | 0 EUR | 50% |
| WhatsApp direct | 5% | 0 EUR | 55% |
| Referral | 3% | 0 EUR | 60% |
| YouTube | 3% | 1.0 EUR | 30% |
| Pinterest | 2% | 2.0 EUR | 20% |

- **Line chart:** leads per day for 30 days (each channel = separate line)
- **Table:** last 10 leads (name, phone, channel, date, status, manager)

### 4.5 Section: Finance
- Revenue current month / previous month / % change
- Pending payment / Overdue > 7 days (highlighted)
- Average check (30 days) / Client LTV
- **Bar chart:** revenue by month (12 months). Green = paid, orange = pending
- **Top-5 unpaid invoices:** client, amount, days overdue, Reminder button

### 4.6 Section: Marketing — Ads Performance
- Unified table: all 5 platforms (Google, Meta, TikTok, Pinterest, YouTube)
- Columns: Platform, Spent, Impressions, Clicks, CTR, Leads, CPL, Status
- **Graphs:** 1) Line: daily spend. 2) Pie: budget allocation. 3) Bar: CPL by platform
- **Quick actions:** pause/resume, change budget, external link

### 4.7 Section: Social Media (8 platforms)
- Table: Platform, Account, Followers, Posts 7d, Reach 7d, Engagement, Status
- All 8 platforms: FB, IG, Threads, TikTok, YouTube, Telegram, Pinterest, LinkedIn
- **Scheduled posts:** next 5 with thumbnail, platforms, date, status
- **Quick actions:** Create post (AI), Unified Inbox (unread per platform)

### 4.8 Section: SEO & Domain Authority (4 domains)
- DA for each of 4 domains + trend graph
- Backlinks total + new/lost
- GSC Clicks/Impressions (7d) — all 4 domains
- Average Position (top keywords)
- Organic Users (30d) from GA4
- Google Reviews rating + count
- **Top-10 keywords, SEO network (8 sites), Brand status**

---

## 5. WEB PANEL — FULL MODULE LIST

| Module | Sub-modules | Status |
|--------|-------------|--------|
| Dashboard | Consolidated panel (section 4 above) | **UPDATED** |
| Clients | Profiles, history, segmentation, verification | Existing |
| Cases | Kanban, statuses, documents, hearings | Existing |
| Calendar | Meetings, hearings, Google Calendar sync | Existing |
| Documents | Upload, OCR, templates, e-signature | Existing |
| Finance | Invoices, payments, Stripe/P24/PayPal | Existing |
| **Leads** | All leads, funnel, UTM, channels, auto-assignment | **NEW** |
| **Landings** | 14+ landings, forms, A/B tests, conversions (4 domains) | **NEW** |
| **Ads Campaigns** | Google Ads, Meta, TikTok, Pinterest, YouTube | **NEW** |
| Social Media | 8 platforms: FB, IG, Threads, TikTok, YT, TG, Pinterest, LinkedIn | **UPDATED** |
| **SEO & Domain** | GSC, GA4, DA (4 domains), backlinks, SEO network, Keywords | **NEW** |
| **Brand** | Trademark, catalogs, reviews, Wikipedia, Knowledge Panel | **NEW** |
| AI Automation | n8n, AI generation (text, images), chatbot | Existing |
| Analytics | All reports: cases, finance, marketing, SEO, social | **UPDATED** |
| Content | Media library, templates, content plan (8 platforms) | **UPDATED** |
| Communications | Chats, email, WhatsApp, Telegram, Threads DM | **UPDATED** |
| Settings | API keys, users, roles, domains | Existing |

---

<!-- Аннотация (RU):
ЧАСТЬ 2 из 6 технического задания WINCASE CRM v4.0.
Содержит: структуру консолидированной панели (Dashboard) из 8 секций — Header KPI Bar (10 карточек),
Справи та клієнти (мини-канбан), Ліди та конверсії (воронка, каналы, pie/line charts),
Фінанси (дохід, неоплачені, bar chart), Маркетинг Ads Performance (5 платформ),
Соціальні мережі (8 акаунтів), SEO та Domain (4 домени), Сповіщення.
Полный список 17 модулей Web Panel: 6 существующих + 5 новых + 6 обновлённых.
Следующая часть: Модули подробно (Leads, Ads, SEO, Brand).
-->
