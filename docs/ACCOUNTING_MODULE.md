# WINCASE CRM v4.0 — Accounting & Tax Module
## All Polish Taxes + Report Generation for Tax Office
### Laravel 12 + PHP 8.4

---

## Business Logic

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ACCOUNTING FLOW                                   │
│                                                                     │
│  Revenue Sources:                                                   │
│  ┌──────────────┐   ┌──────────────┐                                │
│  │ POS Terminal  │   │ CRM Invoices │                                │
│  │ (approved)    │   │ (paid)       │                                │
│  └───────┬──────┘   └──────┬───────┘                                │
│          └─────────┬───────┘                                        │
│                    ▼                                                │
│          ┌────────────────────┐                                     │
│          │ MONTHLY ACCOUNTING │                                     │
│          │   PERIOD           │                                     │
│          └────────┬───────────┘                                     │
│                   │                                                 │
│     ┌─────────────┼─────────────┬──────────────┐                    │
│     ▼             ▼             ▼              ▼                    │
│  ┌──────┐    ┌─────────┐   ┌───────┐    ┌──────────┐               │
│  │ PIT  │    │ VAT     │   │ ZUS   │    │ Expenses │               │
│  │ /CIT │    │ JPK_VAT │   │ DRA   │    │ (costs)  │               │
│  └──┬───┘    └────┬────┘   └───┬───┘    └────┬─────┘               │
│     │             │            │              │                     │
│     └─────────────┴────────────┴──────────────┘                     │
│                        │                                            │
│               ┌────────▼────────┐                                   │
│               │ GENERATE REPORT │ ◄── Owner clicks "Generate"       │
│               │ (Full tax calc) │                                   │
│               └────────┬────────┘                                   │
│                        │                                            │
│               ┌────────▼────────┐                                   │
│               │ DOWNLOAD / PRINT│ ◄── PDF / JSON                    │
│               └────────┬────────┘                                   │
│                        │                                            │
│               ┌────────▼────────┐                                   │
│               │ SUBMIT MANUALLY │ ◄── Owner sends to US / ZUS       │
│               │ (Mark as sent)  │     (Urząd Skarbowy)              │
│               └─────────────────┘                                   │
└─────────────────────────────────────────────────────────────────────┘
```

---

## All Polish Taxes Covered

### 1. PIT — Personal Income Tax (JDG / Sole Proprietor)

| Regime | Rate | Tax Base | Tax-Free | PIT Form | Best For |
|--------|------|----------|----------|----------|----------|
| **Skala podatkowa** | 12% / 32% | Profit (revenue - costs) | 30 000 PLN | PIT-36 | Low income (<120k PLN) |
| **Liniowy** | 19% flat | Profit (revenue - costs) | None | PIT-36L | High income (>150k PLN) |
| **Ryczałt** | 2%-17% by activity | Revenue (NO cost deduction) | None | PIT-28 | Low costs, immigration = **15%** |

#### Ryczałt Rates by Activity (PKD Code)

| Rate | Activity Type | PKD Examples |
|------|--------------|-------------|
| **17%** | Unregulated liberal professions | — |
| **15%** | **Legal, consulting, immigration, bookkeeping** | **69.10.Z, 70.22.Z, 78.10.Z** |
| **14%** | Healthcare services | 86.xx |
| **12%** | IT, programming, software | 62.01.Z, 62.02.Z |
| **10%** | Property rental | 68.20.Z |
| **8.5%** | General services, education, regulated professions | 85.xx, 74.xx |
| **5.5%** | Construction, manufacturing | 41.xx |
| **3%** | Trade (retail/wholesale), gastronomy | 47.xx, 56.xx |
| **2%** | Agricultural products | 01.xx |

**WinCase default: Ryczałt 15%** (immigration consulting = PKD 69.10.Z / 70.22.Z)

### 2. CIT — Corporate Income Tax (Sp. z o.o.)

| Type | Rate | Condition |
|------|------|-----------|
| Standard | 19% | Default |
| Small taxpayer | 9% | Revenue < ~8.6M PLN |
| Estonian CIT | 10%/20% | Tax only on distribution |

### 3. VAT — Value Added Tax

| Rate | Name | Used For |
|------|------|----------|
| **23%** | Standard | **Immigration services, consulting, IT, marketing, office rent** |
| **8%** | Reduced | Construction (residential), restaurants, transport, hotels |
| **5%** | Super-reduced | Books, basic food, social housing |
| **0%** | Zero-rated | Export, intra-EU supplies, international transport |
| **zw** | Exempt | Financial, insurance, medical, education |

**WinCase: VAT 23%** on all immigration services. GTU code: **GTU_12**.

**Key rules:**
- Registration threshold: 200 000 PLN annual revenue (must register above)
- JPK_VAT filing: monthly by 25th of next month
- VAT payment: by 25th of next month
- Reverse charge for EU clients (0%)

### 4. ZUS — Social Security (Zakład Ubezpieczeń Społecznych)

#### Social Contributions (same for all regimes)

| Contribution | Rate | Monthly (2026) |
|-------------|------|----------------|
| Pension (emerytalna) | 19.52% of base | ~902 PLN |
| Disability (rentowa) | 8.00% of base | ~370 PLN |
| Sickness (chorobowa) | 2.45% voluntary | ~113 PLN |
| Accident (wypadkowa) | 1.67% | ~77 PLN |
| Labor Fund (FP) | 2.45% | ~113 PLN |
| FGŚP | 0.10% | ~5 PLN |
| **Total social** | — | **~1 575 PLN/month** |

#### Health Insurance (NFZ) — varies by regime

| Regime | Rate | Base | Monthly (approx.) |
|--------|------|------|-------------------|
| Skala podatkowa | 9% of income | profit | 9% × income (min 315 PLN) |
| Liniowy | 4.9% of income | profit | 4.9% × income (min 315 PLN) |
| **Ryczałt** | Fixed bracket | revenue | **498 / 831 / 1 494 PLN** |

**Ryczałt health brackets (2026):**
- Revenue ≤ 60 000 PLN/year → **498 PLN/month**
- Revenue 60 001 – 300 000 PLN/year → **831 PLN/month**
- Revenue > 300 000 PLN/year → **1 494 PLN/month**

#### ZUS Relief Stages

| Stage | Duration | Social | Health | Total (approx.) |
|-------|----------|--------|--------|-----------------|
| **Ulga na start** | First 6 months | 0 PLN | ~498 PLN | ~498 PLN |
| **Mały ZUS Plus** | Next 24 months | ~475 PLN | ~498 PLN | ~973 PLN |
| **Full ZUS** | After 30 months | ~1 575 PLN | ~831 PLN | ~2 406 PLN |

### 5. Other Taxes

| Tax | Rate | When |
|-----|------|------|
| PCC (Civil Law Transactions) | 2% / 0.5% | Property sale / loan |
| Real Estate Tax (business) | 34 PLN/m² | Annual, buildings used for business |
| Withholding Tax (non-residents) | 20% services / 19% dividends | Paying non-resident contractors |

---

## Files Structure

```
app/
├── Enums/
│   ├── TaxRegimeEnum.php              # 6 tax regimes (JDG + corporate)
│   ├── VatRateEnum.php                # 6 VAT rates with calc methods
│   └── RyczaltRateEnum.php            # 9 ryczałt rates by PKD
├── Models/
│   ├── AccountingPeriod.php           # Monthly periods + cumulative YTD
│   ├── TaxReport.php                  # 11 report types, status flow
│   └── Expense.php                    # 15 categories, deduction calc
├── Services/Accounting/
│   ├── TaxCalculatorService.php       # ALL tax calcs (PIT/CIT/VAT/ZUS)
│   └── TaxReportService.php           # Report generation (5 types)
└── Http/Controllers/Api/V1/
    └── AccountingController.php       # 16 API endpoints

config/
└── polish_tax.php                     # All rates, thresholds, deadlines (2026)

database/migrations/
└── 2026_02_17_000010_create_accounting_tables.php  # 3 tables

routes/
└── api.php                            # 16 accounting routes (append)
```

---

## API Endpoints (16)

### Report Generation
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/accounting/reports/monthly` | Full monthly tax report (PIT+VAT+ZUS) |
| `POST` | `/api/v1/accounting/reports/vat` | JPK_VAT report |
| `POST` | `/api/v1/accounting/reports/zus` | ZUS DRA declaration |
| `POST` | `/api/v1/accounting/reports/annual` | Annual PIT declaration (PIT-36/PIT-36L/PIT-28) |
| `POST` | `/api/v1/accounting/reports/profit-loss` | P&L report (internal) |

### Report Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/accounting/reports` | List reports (filterable) |
| `GET` | `/api/v1/accounting/reports/{id}` | View report details |
| `PATCH` | `/api/v1/accounting/reports/{id}/submit` | Mark as submitted to tax office |

### Tax Calculator
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/accounting/calculate` | Quick tax calculation |
| `POST` | `/api/v1/accounting/compare-regimes` | Compare regimes (Skala vs Liniowy vs Ryczałt) |

### Expenses
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/accounting/expenses` | List expenses |
| `POST` | `/api/v1/accounting/expenses` | Add expense |
| `DELETE` | `/api/v1/accounting/expenses/{id}` | Delete expense |

### Periods & Config
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/accounting/periods` | Monthly periods + YTD cumulative |
| `PATCH` | `/api/v1/accounting/periods/{id}/close` | Close monthly period |
| `GET` | `/api/v1/accounting/tax-config` | All tax rates for UI |

---

## Example: Monthly Tax Calculation (Ryczałt 15%)

```
Revenue:    25 000 PLN (gross, immigration services)
Regime:     Ryczałt 15%

VAT:
  Net:      25 000 / 1.23 = 20 325.20 PLN
  VAT:      25 000 - 20 325.20 = 4 674.80 PLN
  Input:    500 PLN (office rent, hosting)
  VAT Due:  4 674.80 - 500 = 4 174.80 PLN  ← pay by 25th

Income Tax (Ryczałt):
  Revenue:  25 000 PLN (gross)
  Rate:     15%
  Tax:      25 000 × 15% = 3 750 PLN
  Health deduction (50%): -415.50 PLN
  Tax Due:  3 334.50 PLN  ← pay by 20th

ZUS (full, revenue bracket 60-300k):
  Social:   1 575 PLN
  Health:   831 PLN
  Total:    2 406 PLN  ← pay by 10th

═══════════════════════════════════════
Total Monthly Tax Burden:
  PIT (ryczałt):    3 334.50 PLN
  VAT:              4 174.80 PLN
  ZUS:              2 406.00 PLN
  ─────────────────────────────
  TOTAL:            9 915.30 PLN
  Net after all:    15 084.70 PLN
  Effective rate:   39.7% of gross
═══════════════════════════════════════
```

---

## Tax Filing Deadlines Calendar

| Day | What | Where |
|-----|------|-------|
| **10th** | ZUS DRA + payment | ZUS PUE portal |
| **20th** | PIT advance payment (monthly) | Tax office bank account |
| **25th** | JPK_VAT filing + VAT payment | e-Deklaracje / bank |
| **Feb 28** | PIT-28 annual (ryczałt) | e-Deklaracje |
| **Apr 30** | PIT-36/PIT-36L annual | e-Deklaracje |

---

## Installation

```bash
# Copy files to project
cp config/polish_tax.php backend/config/
cp enums/*.php backend/app/Enums/
cp migrations/*.php backend/database/migrations/
cp models/*.php backend/app/Models/
mkdir -p backend/app/Services/Accounting
cp services/TaxCalculatorService.php backend/app/Services/Accounting/
cp services/TaxReportService.php backend/app/Services/Accounting/
cp services/AccountingController.php backend/app/Http/Controllers/Api/V1/

# Append routes
cat services/api_accounting_routes.php >> backend/routes/api.php

# Run migration
cd backend
php artisan migrate

# Verify
php artisan route:list --path=accounting
php artisan tinker --execute="echo 'Tax config loaded: ' . config('polish_tax.tax_year')"
```

---

## Updated Project Statistics

| Metric | Before | After Accounting |
|--------|--------|-----------------|
| Tables | 29 | **32** (+3: accounting_periods, tax_reports, expenses) |
| API Endpoints | 41+ | **57+** (+16 accounting) |
| Enums | 11 | **14** (+3: TaxRegime, VatRate, RyczaltRate) |
| Services | 36+ | **38+** (+TaxCalculator, +TaxReportService) |
| Config files | — | **+1** (config/polish_tax.php) |

<!--
Аннотация (RU):
Полная документация бухгалтерского модуля WINCASE CRM v4.0.
Все налоги Польши 2026: PIT (skala 12%/32%, liniowy 19%, рычалт 2-17%),
CIT (19%/9%/эстонский), VAT (23%/8%/5%/0%/zw), ZUS (социальные ~1575 PLN +
здоровье по режиму), PCC, налог на недвижимость, withholding tax.
Для WinCase: рычалт 15% (immigration consulting, PKD 69.10.Z/70.22.Z).
5 типов отчётов, 16 API endpoints, 3 таблицы, налоговый калькулятор,
сравнение режимов. Отправка в налоговую — вручную.
Файл: docs/ACCOUNTING_MODULE.md
-->
