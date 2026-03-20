# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 3 of 7: Module Details — POS / Accounting / Leads / Ads / SEO / Brand / Landings

---

## 6. MODULE: POS TERMINAL

### 6.1 Business Flow

```
Client → Office → Documents → Payment (cash / card / BLIK / transfer)
                                  │
                           ┌──────▼──────────┐
                           │ pos_transactions │ ← STAGING TABLE
                           │ (separate from   │   (CRM stays clean)
                           │  invoices/payments│
                           └──────┬──────────┘
                                  │
                     ┌────────────┴────────────┐
                     │    OWNER'S DECISION      │
                     └────┬──────────────┬──────┘
                          │              │
                   ✅ APPROVE      ❌ REJECT
                   VAT 23% calc    Pending refund
                          │              │
                   Create CRM:     REFUND to client
                   Client+Invoice
                   +Payment
```

### 6.2 Status Flow
`received` → `under_review` → `approved` → `invoiced` (SUCCESS: invoice + tax)
`received` → `under_review` → `rejected` → `refunded` (DECLINE: money back)

### 6.3 Payment Methods
- **Cash** (Gotówka) — physical cash at office
- **Card Terminal** (Terminal) — POS device for debit/credit cards
- **BLIK** — Polish instant mobile payments
- **Bank Transfer** (Przelew) — wire transfer

### 6.4 Receipt Format: `WC-260217-0001` (auto-generated, unique per day)
### 6.5 Invoice Format: `FV/2026/02/0001` (Polish faktura VAT format, on approval)

### 6.6 POS API (11 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/v1/pos/receive | Register payment (operator) |
| GET | /api/v1/pos/pending | List awaiting owner decision |
| GET | /api/v1/pos/{id} | Transaction details |
| PATCH | /api/v1/pos/{id}/review | Mark "under review" |
| PATCH | /api/v1/pos/{id}/approve | Approve → calculate VAT |
| PATCH | /api/v1/pos/{id}/reject | Reject → pending refund |
| POST | /api/v1/pos/{id}/process | Create Invoice + Payment in CRM |
| PATCH | /api/v1/pos/{id}/refund | Confirm refund completed |
| GET | /api/v1/pos/daily-report | Daily summary |
| GET | /api/v1/pos/tax-report | Monthly tax breakdown |
| GET | /api/v1/pos/history | History with filters + search |

---

## 7. MODULE: ACCOUNTING & TAX

### 7.1 Polish Taxes Covered

| Tax | Rates | WinCase Default |
|-----|-------|-----------------|
| PIT Skala podatkowa | 12% (≤120k) / 32% (>120k) | — |
| PIT Liniowy | 19% flat | — |
| PIT Ryczałt | 2%-17% by PKD code | **15% (immigration consulting)** |
| CIT Standard / Small | 19% / 9% | — |
| VAT | 23% / 8% / 5% / 0% / exempt | **23%** |
| ZUS Social | ~1 575 PLN/month (full) | Full / preferential / ulga na start |
| ZUS Health (NFZ) | 9% / 4.9% / fixed bracket | **831 PLN/month (ryczałt, 60-300k)** |

### 7.2 Ryczałt Rates by Activity (PKD)

| Rate | Activity | PKD Examples |
|------|----------|-------------|
| 17% | Unregulated liberal professions | — |
| **15%** | **Legal, consulting, immigration, bookkeeping** | **69.10.Z, 70.22.Z, 78.10.Z** |
| 14% | Healthcare | 86.xx |
| 12% | IT, programming | 62.01.Z, 62.02.Z |
| 10% | Property rental | 68.20.Z |
| 8.5% | General services, education | 85.xx, 74.xx |
| 5.5% | Construction | 41.xx |
| 3% | Trade, gastronomy | 47.xx, 56.xx |
| 2% | Agricultural products | 01.xx |

### 7.3 Report Types (5)
1. **Full Monthly Tax Report** — PIT/CIT + VAT + ZUS combined
2. **JPK_VAT** — for e-Deklaracje (sales/purchases/settlement)
3. **ZUS DRA** — social + health insurance declaration
4. **Annual PIT Declaration** — PIT-36 / PIT-36L / PIT-28
5. **Profit & Loss** — internal management report

### 7.4 Tax Filing Deadlines

| Day | What | Where |
|-----|------|-------|
| 10th | ZUS DRA + payment | ZUS PUE portal |
| 20th | PIT advance payment | Tax office bank account |
| 25th | JPK_VAT + VAT payment | e-Deklaracje / bank |
| Feb 28 | PIT-28 annual (ryczałt) | e-Deklaracje |
| Apr 30 | PIT-36/PIT-36L annual | e-Deklaracje |

### 7.5 Accounting API (16 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/v1/accounting/reports/monthly | Generate monthly report |
| POST | /api/v1/accounting/reports/vat | Generate JPK_VAT |
| POST | /api/v1/accounting/reports/zus | Generate ZUS DRA |
| POST | /api/v1/accounting/reports/annual | Generate annual PIT |
| POST | /api/v1/accounting/reports/profit-loss | Generate P&L |
| GET | /api/v1/accounting/reports | List reports |
| GET | /api/v1/accounting/reports/{id} | Report details |
| PATCH | /api/v1/accounting/reports/{id}/submit | Mark submitted to tax office |
| POST | /api/v1/accounting/calculate | Quick tax calculation |
| POST | /api/v1/accounting/compare-regimes | Compare Skala vs Liniowy vs Ryczałt |
| GET | /api/v1/accounting/expenses | List expenses |
| POST | /api/v1/accounting/expenses | Add expense |
| DELETE | /api/v1/accounting/expenses/{id} | Delete expense |
| GET | /api/v1/accounting/periods | Monthly periods + YTD |
| PATCH | /api/v1/accounting/periods/{id}/close | Close period |
| GET | /api/v1/accounting/tax-config | All rates for UI |

---

## 8. MODULE: LEADS MANAGEMENT

### 8.1 Lead Sources (14 channels)

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
| Phone call | Manual entry / Call tracking | Manual |
| Walk-in (office) | Manual entry → POS | Manual |
| Referral | Manual entry | Manual |

### 8.2 Lead Routing (Auto-Assignment)

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

### 8.3 Lead Funnel Statuses
`new` → `contacted` → `consultation` → `contract` → `paid` → (end)
Alternative: `new` → `rejected` or `spam` at any stage

---

## 9. MODULE: ADS CAMPAIGNS

### 9.1 Platforms & Sync

| Platform | Campaigns | Sync | n8n |
|----------|-----------|------|-----|
| Google Ads | 9 campaigns | Every 6h | W04 |
| Meta Ads | 4 campaigns | Every 6h | W05 |
| TikTok Ads | 4 campaigns | Every 6h | W06 |
| Pinterest Ads | 3 campaigns | Every 12h | W07 |
| YouTube Ads | 5 campaigns | Every 12h | W07 |

### 9.2 Offline Conversions
- Google Ads: lead.status = 'paid' → send gclid + value (W17)
- Facebook CAPI: lead.created → Lead event + fbclid (W18)
- TikTok Events: lead.created → event + ttclid (W19)

---

## 10. MODULE: SEO & DOMAIN AUTHORITY (4 domains)

- GSC Dashboard for 4 domains, GA4 Dashboard (4 properties)
- Domain Authority + trend, Keywords Tracker (top-50 daily)
- Backlinks Monitor (new/lost), SEO Network (8 satellite sites)
- Competitors comparison, Reviews Hub (Google, Trustpilot, Facebook, GoWork)

---

## 11. MODULE: BRAND & REPUTATION

- Trademark: UPRP (Poland) + EUIPO (EU) status tracking
- Business Listings: 50+ directories, NAP consistency check (4 domains)
- Reviews Hub: Google (87), Trustpilot (30), Facebook (25), GoWork (10)
- PR & Media, Wikipedia article status, Knowledge Panel (Wikidata, Schema.org)

---

## 12. MODULE: LANDINGS

- 15+ landing pages across 4 domains, 8 languages
- Form submissions → leads table with UTM + click IDs
- A/B Testing (wincase-legalization.com), Page Speed scores
- Security: rate limit 10/min/IP, honeypot, reCAPTCHA v3, CORS (4 domains only)

---

<!-- Аннотация (RU):
ЧАСТЬ 3 из 7 — детальное описание 7 модулей.
POS Terminal: staging-зона платежей (cash/card/BLIK), 6 статусов, 11 API endpoints.
Accounting & Tax: все налоги Польши (PIT 12%/32%/19%/рычалт 2-17%, CIT 19%/9%,
VAT 23%/8%/5%/0%, ZUS social ~1575 PLN + health), 5 типов отчётов, 16 API endpoints.
Leads: 14 каналов, авто-маршрутизация, воронка 7 статусов.
Ads: 5 платформ, offline conversions. SEO: 4 домена, 8 сателлитов.
Brand: 50+ каталогов, Reviews Hub. Landings: 15+ страниц, A/B.
Файл: docs/TZ_PART_3_MODULES_DETAIL.md
-->
