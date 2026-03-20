# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 2 of 7: Consolidated Dashboard + Web Panel Modules

---

## 4. CONSOLIDATED DASHBOARD — 9 SECTIONS

The main page (Dashboard) combines ALL data from all modules into a single screen. The administrator sees: CRM, cases, finances, POS pending, accounting/tax, 8 social networks, ads, SEO, leads — on one page. Each block = widget. Widgets are draggable and configurable.

**Real-time updates:** Every 60 sec polling OR WebSocket (Laravel Reverb)
**API:** `GET /api/v1/dashboard/kpi`

### 4.1 Section Layout

| Section | Position | Data Sources |
|---------|----------|--------------|
| Header KPI Bar | Top, full width | CRM + Cases + Finance + Leads + POS |
| Cases & Clients | Left top, 50% | cases, clients, hearings, tasks |
| Leads & Conversions | Right top, 50% | leads, utm_tracking, forms |
| Finance & POS | Left center, 50% | invoices, payments, pos_transactions |
| Marketing — Ads | Right center, 50% | Google Ads, Meta, TikTok, Pinterest, YouTube APIs |
| Social Media (8) | Left bottom, 50% | social_accounts, social_posts, social_analytics |
| SEO & Domain (4 domains) | Right bottom, 50% | GSC API, GA4 API, Ahrefs/Moz API |
| Accounting & Tax | Bottom center, full | accounting_periods, tax_reports, expenses |
| Notifications + Tasks | Right sidebar (fold) | notifications, tasks, calendar |

### 4.2 Header KPI Bar — 12 Cards

| KPI | Source | Formula / API |
|-----|--------|---------------|
| Active Cases | cases | COUNT WHERE status IN (new, in_progress, pending, under_review) |
| New Leads (today) | leads | COUNT WHERE date = today |
| Lead Conversion | leads | converted / total × 100 (30 days) |
| Revenue (month) | payments + pos_transactions | SUM(amount) WHERE month = current AND status = completed/invoiced |
| POS Pending | pos_transactions | COUNT + SUM(amount) WHERE status IN (received, under_review) |
| Pending Payment | invoices | SUM(amount) WHERE status = pending |
| Google Ads CPL | ads_performance | cost / leads WHERE platform = google_ads (7 days) |
| Meta CPL | ads_performance | cost / leads WHERE platform = meta_ads (7 days) |
| Social Reach (7d) | social_analytics | SUM(reach) all 8 platforms for 7 days |
| Google Reviews | Google Places API | rating + count |
| Domain Authority | seo_data | DA (wincase.pro) latest |
| Monthly Tax Burden | accounting_periods | total_tax_burden WHERE month = current |

### 4.3 Section: Cases & Clients
- **Mini Kanban Board:** 5 columns (New / In Progress / Pending / Under Review / Completed) with case count
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

### 4.5 Section: Finance & POS
- Revenue current month / previous month / % change (invoices + POS invoiced)
- **POS Pending widget:** count, total amount, oldest waiting (hours) — orange badge
- **POS Today:** cash amount, card amount, approved, rejected
- Pending payment / Overdue > 7 days (highlighted)
- Average check (30 days) / Client LTV
- **Bar chart:** revenue by month (12 months). Green = paid, orange = pending, blue = POS
- **Top-5 unpaid invoices:** client, amount, days overdue, Reminder button

### 4.6 Section: Marketing — Ads Performance
- Unified table: all 5 platforms (Google, Meta, TikTok, Pinterest, YouTube)
- Columns: Platform, Spent, Impressions, Clicks, CTR, Leads, CPL, Status
- **Graphs:** 1) Line: daily spend. 2) Pie: budget allocation. 3) Bar: CPL by platform

### 4.7 Section: Social Media (8 platforms)
- Table: Platform, Account, Followers, Posts 7d, Reach 7d, Engagement, Status
- All 8 platforms: FB, IG, Threads, TikTok, YouTube, Telegram, Pinterest, LinkedIn
- **Scheduled posts:** next 5 with thumbnail, platforms, date, status

### 4.8 Section: SEO & Domain Authority (4 domains)
- DA for each of 4 domains + trend graph
- Backlinks total + new/lost, GSC Clicks/Impressions (7d)
- Top-10 keywords, SEO network (8 sites), Brand status

### 4.9 Section: Accounting & Tax
- **Current month:** gross revenue, total tax burden, net after all, effective rate %
- **Deadlines widget:** next ZUS (10th), PIT advance (20th), JPK_VAT (25th) — countdown
- **Year-to-date:** cumulative revenue, cumulative taxes, comparison with previous year
- **Quick actions:** Generate monthly report, Compare regimes, View expenses

---

## 5. WEB PANEL — FULL MODULE LIST (19)

| Module | Sub-modules | Status |
|--------|-------------|--------|
| Dashboard | Consolidated panel (9 sections above) | v4.0 |
| Clients | Profiles, history, segmentation, verification | Core |
| Cases | Kanban, statuses, documents, hearings | Core |
| Calendar | Meetings, hearings, Google Calendar sync | Core |
| Documents | Upload, OCR, templates, e-signature | Core |
| Finance | Invoices, payments, Stripe/P24 | Core |
| POS Terminal | Cash/card payments, staging zone, receipts, refunds | v4.0 |
| Accounting & Tax | Polish taxes (PIT/CIT/VAT/ZUS), reports, expenses, tax calculator | v4.0 |
| Leads | All leads, funnel, UTM, channels, auto-assignment | v4.0 |
| Landings | 15+ landings, forms, A/B tests, conversions (4 domains) | v4.0 |
| Ads Campaigns | Google Ads, Meta, TikTok, Pinterest, YouTube | v4.0 |
| Social Media | 8 platforms: FB, IG, Threads, TikTok, YT, TG, Pinterest, LinkedIn | v4.0 |
| SEO & Domain | GSC, GA4, DA (4 domains), backlinks, SEO network, Keywords | v4.0 |
| Brand | Trademark, catalogs, reviews, Wikipedia, Knowledge Panel | v4.0 |
| AI Automation | n8n, AI generation (text, images), chatbot | v4.0 |
| Analytics | All reports: cases, finance, marketing, SEO, social, POS, tax | v4.0 |
| Content | Media library, templates, content plan (8 platforms) | v4.0 |
| Communications | Chats, email, WhatsApp, Telegram, Threads DM | v4.0 |
| Settings | API keys, users, roles, domains, tax config | Core |

---

<!-- Аннотация (RU):
ЧАСТЬ 2 из 7 — Dashboard (9 секций) + 19 модулей Web Panel.
Dashboard теперь включает POS Pending widget (ожидающие решения владельца),
секцию Finance & POS (cash/card/approved/rejected), секцию Accounting & Tax
(налоговая нагрузка, дедлайны ZUS/PIT/VAT, year-to-date кумулятивные).
12 KPI-карточек включая POS Pending и Monthly Tax Burden.
19 модулей: Core (6) + v4.0 (13) включая POS Terminal и Accounting & Tax.
Файл: docs/TZ_PART_2_DASHBOARD_MODULES.md
-->
