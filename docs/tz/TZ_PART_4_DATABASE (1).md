# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 4 of 7: Database Schema — 32 Tables

---

## 13. DATABASE OVERVIEW

**Engine:** MySQL 8.4 LTS
**Charset:** utf8mb4_unicode_ci
**Total Tables:** 32 (14 core + 18 v4.0)
**Soft Deletes:** enabled on all main entities
**Enums:** 16 PHP 8.4 backed enums (stored as VARCHAR)

### 13.1 Core Tables (14)

| # | Table | Description | Key Relations |
|---|-------|-------------|---------------|
| 1 | users | Admins, managers, lawyers | — |
| 2 | clients | Client profiles | hasMany: cases, invoices |
| 3 | cases | Immigration cases | belongsTo: client, user |
| 4 | hearings | Court hearings | belongsTo: case |
| 5 | tasks | Task assignments | belongsTo: user, case |
| 6 | documents | Uploaded documents | belongsTo: client, case |
| 7 | invoices | Financial invoices | belongsTo: client |
| 8 | payments | Payment records | belongsTo: invoice |
| 9 | notifications | System notifications | belongsTo: user |
| 10 | calendar_events | Calendar entries | belongsTo: user, case |
| 11 | social_accounts | Connected social accounts (+ threads, linkedin) | — |
| 12 | social_posts | Published posts (+ threads_id, linkedin_post_id) | belongsTo: social_account |
| 13 | social_analytics | Per-post analytics | belongsTo: social_post |
| 14 | content_calendar | Content planning (+ platforms JSON) | — |

### 13.2 Marketing Tables (7)

| # | Table | Columns | Description |
|---|-------|---------|-------------|
| 15 | leads | 31 | All leads from 14 sources, UTM, click IDs, funnel |
| 16 | ads_performance | 16 | Daily ads metrics per campaign (5 platforms) |
| 17 | seo_data | 14 | GSC/GA4/Ahrefs data per domain |
| 18 | reviews | 11 | Reviews from 6 platforms |
| 19 | seo_network_sites | 11 | 8 satellite SEO sites |
| 20 | brand_listings | 10 | 50+ directory listings NAP check |
| 21 | landings | 13 | 15+ landing pages tracking |

### 13.3 POS Terminal Tables (1)

| # | Table | Columns | Description |
|---|-------|---------|-------------|
| 22 | pos_transactions | 28 | Staging zone for office payments (cash/card/BLIK) |

### 13.4 Accounting Tables (3)

| # | Table | Columns | Description |
|---|-------|---------|-------------|
| 23 | accounting_periods | 20 | Monthly accounting periods with tax calculations |
| 24 | tax_reports | 16 | Generated reports for tax office (11 types) |
| 25 | expenses | 14 | Business expense records for tax deduction |

### 13.5 ALTER to Existing Tables (3 operations)

| Table | Change |
|-------|--------|
| social_accounts | +threads_user_id, +linkedin_company_id |
| social_posts | +threads_id, +linkedin_post_id |
| content_calendar | +platforms JSON |

---

## 14. KEY TABLE SCHEMAS

### 14.1 leads (31 columns, 12 indexes)
Contact info (name, phone, email), service_type, source (12 channels), UTM (5 fields), click IDs (gclid/fbclid/ttclid), landing_page, visitor info (language, device, IP, country, city), CRM status (7 funnel statuses), assigned_to, priority, conversion timestamps (first_contact, consultation, converted), CRM link (client_id, case_id), GDPR consent, soft deletes.

### 14.2 ads_performance (16 columns)
platform, campaign_id, campaign_name, date, impressions, clicks, cost, conversions, conversion_value, CPC, CPL, CTR, leads_count, status, raw_data JSON. UNIQUE(platform, campaign_id, date).

### 14.3 seo_data (14 columns)
domain (4 domains), date, source (gsc/ga4/ahrefs/moz), GSC metrics (clicks, impressions, avg_position), GA4 metrics (users, sessions, conversions), SEO metrics (DA, backlinks, referring_domains), raw_data JSON. UNIQUE(domain, date, source).

### 14.4 pos_transactions (28 columns, 6 indexes)
receipt_number (UNIQUE, auto: WC-260217-0001), terminal_transaction_id, client info (name, phone, email, passport), service_type, documents_submitted JSON, payment (method, amount, currency, tax_rate, tax_amount, net_amount), status (6 stages: received → invoiced OR → refunded), decision flow (decided_by, decided_at, decision_notes), refund fields (refund_amount, refund_method, refund_reason, refunded_at), CRM link (client_id, case_id, invoice_id, payment_id — NULL until invoiced), received_by, soft deletes.

### 14.5 accounting_periods (20 columns)
year, month, period_type, tax_regime, gross/net revenue, total costs, taxable income, VAT (output, input, due), income_tax_advance, income_tax_cumulative, ZUS (social, health, total), total_tax_burden, net_income_after_tax, status (open/closed), closed_at/by. UNIQUE(year, month, tax_regime).

### 14.6 tax_reports (16 columns)
report_type (11 types: jpk_vat, pit_advance, pit_annual, ryczalt_monthly/annual, cit_advance/annual, zus_dra, cash_flow, profit_loss, tax_summary), year, month, quarter, tax_regime, report_data JSON, line_items JSON, totals (revenue, costs, tax, vat, zus), status (draft → generated → submitted), file_path.

### 14.7 expenses (14 columns)
date, category (15 categories), description, vendor, vendor_nip, invoice_number, net_amount, vat_rate, vat_amount, gross_amount, payment_method, is_tax_deductible, deductible_percentage, file_path.

---

## 15. PHP 8.4 BACKED ENUMS (16)

| Enum | Values | Used In |
|------|--------|---------|
| LeadSourceEnum | 12 sources | leads.source |
| LeadStatusEnum | 7 statuses | leads.status |
| ServiceTypeEnum | 7 services | leads, pos_transactions |
| AdsPlatformEnum | 5 platforms | ads_performance |
| SocialPlatformEnum | 8 platforms | social_* tables |
| ReviewPlatformEnum | 6 platforms | reviews |
| PriorityEnum | 4 levels | leads.priority |
| CaseStatusEnum | 5 statuses | cases.status |
| BrandListingStatusEnum | 4 statuses | brand_listings |
| PosTransactionStatusEnum | 6 statuses | pos_transactions.status |
| PaymentMethodEnum | 4 methods | pos_transactions.payment_method |
| TaxRegimeEnum | 6 regimes | accounting_periods, tax_reports |
| VatRateEnum | 6 rates | expenses, invoices |
| RyczaltRateEnum | 9 rates | tax calculations |

---

<!-- Аннотация (RU):
ЧАСТЬ 4 из 7 — полная схема БД: 32 таблицы.
14 core (CRM) + 7 marketing (leads, ads, seo, reviews, network, listings, landings)
+ 1 POS (pos_transactions: 28 колонок, staging-зона платежей)
+ 3 accounting (accounting_periods, tax_reports, expenses)
+ 3 ALTER к существующим (social_accounts, social_posts, content_calendar).
16 PHP 8.4 backed enums. Все таблицы с описанием колонок и индексов.
Файл: docs/TZ_PART_4_DATABASE.md
-->
