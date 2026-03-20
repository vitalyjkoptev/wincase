# WINCASE CRM v4.0 — POS Module
## Point of Sale Terminal + Cash Receiver + Staging Zone
### Laravel 12 + PHP 8.4

---

## Business Logic

```
┌─────────────────────────────────────────────────────────────────┐
│                        OFFICE FLOW                              │
│                                                                 │
│  Client arrives → fills documents → pays (cash / card / BLIK)   │
│                                                                 │
│                    ┌───────────────────┐                         │
│                    │  POS TERMINAL     │                         │
│                    │  (Cash + Card)    │                         │
│                    └────────┬──────────┘                         │
│                             │                                   │
│                      receipt printed                             │
│                      WC-260217-0001                              │
│                             │                                   │
│                    ┌────────▼──────────┐                         │
│                    │ pos_transactions  │ ◄── STAGING TABLE       │
│                    │ (SEPARATE from    │     (НЕ засоряет CRM)   │
│                    │  invoices/payments│                         │
│                    └────────┬──────────┘                         │
│                             │                                   │
│               ┌─────────────┴─────────────┐                     │
│               │    OWNER'S DECISION       │                     │
│               │    (Виталий решает)       │                     │
│               └─────┬───────────────┬─────┘                     │
│                     │               │                           │
│              ✅ APPROVE        ❌ REJECT                        │
│                     │               │                           │
│              VAT 23%          Pending Refund                    │
│              calculated              │                          │
│                     │          ┌─────▼──────┐                   │
│              ┌──────▼──────┐   │  REFUNDED  │                   │
│              │ CREATE CRM: │   │  Money back│                   │
│              │ • Client    │   └────────────┘                   │
│              │ • Invoice   │                                    │
│              │ • Payment   │                                    │
│              └─────────────┘                                    │
│                                                                 │
│              CRM is CLEAN — only confirmed payments             │
└─────────────────────────────────────────────────────────────────┘
```

---

## Status Flow

```
RECEIVED ──→ UNDER_REVIEW ──→ APPROVED ──→ INVOICED ✅
   │              │                            │
   │              └──→ REJECTED ──→ REFUNDED ❌│
   │                                           │
   └──→ APPROVED ──→ INVOICED ✅               │
   └──→ REJECTED ──→ REFUNDED ❌               │
```

| Status | Meaning | CRM Impact | Action |
|--------|---------|------------|--------|
| `received` | Client paid, receipt printed | NONE | Awaits owner |
| `under_review` | Owner is reviewing | NONE | Optional step |
| `approved` | Owner said YES | VAT calculated | Ready for CRM |
| `invoiced` | Invoice + Payment created | Client + Invoice + Payment in CRM | FINAL ✅ |
| `rejected` | Owner said NO | NONE | Pending refund |
| `refunded` | Money returned to client | NONE | FINAL ❌ |

---

## Files Structure

```
app/
├── Enums/
│   ├── PosTransactionStatusEnum.php     # 6 statuses + transitions graph
│   └── PaymentMethodEnum.php            # cash, card_terminal, bank_transfer, blik
├── Models/
│   └── PosTransaction.php               # 10 scopes, 5 accessors, 6 business methods
├── Services/
│   └── PosService.php                   # Full orchestration (receive → approve → CRM)
└── Http/Controllers/Api/V1/
    └── PosController.php                # 11 API endpoints

database/migrations/
└── 2026_02_17_000001_create_pos_transactions_table.php  # 28 columns, 6 indexes

routes/
└── api.php                              # 11 POS routes (append)
```

---

## API Endpoints (11)

### Operator Actions
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/pos/receive` | Register payment (cash/card) |

### Owner Decision
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/pos/pending` | List all awaiting decision |
| `PATCH` | `/api/v1/pos/{id}/review` | Mark "under review" |
| `PATCH` | `/api/v1/pos/{id}/approve` | Approve → calculate VAT |
| `PATCH` | `/api/v1/pos/{id}/reject` | Reject → pending refund |
| `POST` | `/api/v1/pos/{id}/process` | Create Invoice + Payment in CRM |
| `PATCH` | `/api/v1/pos/{id}/refund` | Confirm refund completed |

### Reports
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/pos/daily-report` | Daily summary (pending/approved/rejected) |
| `GET` | `/api/v1/pos/tax-report` | Monthly VAT report |
| `GET` | `/api/v1/pos/history` | Full history with filters & search |
| `GET` | `/api/v1/pos/{id}` | Single transaction details |

---

## Tax Calculation (VAT Poland)

```
amount = 500.00 PLN (client pays)
tax_rate = 23% (VAT)
net_amount = 500.00 / 1.23 = 406.50 PLN
tax_amount = 500.00 - 406.50 = 93.50 PLN
```

Tax is calculated **only on approve()**, NOT on receive. This ensures:
- No tax liability until owner confirms
- Rejected/refunded transactions have zero tax impact

---

## Receipt Format

```
receipt_number: WC-260217-0001
               ── ────── ────
               │   │      │
               │   │      └── Daily sequence (0001, 0002, ...)
               │   └── Date (YYMMDD)
               └── Prefix (WinCase)
```

## Invoice Format (on approval)

```
invoice_number: FV/2026/02/0001
                ── ──── ── ────
                │   │    │   │
                │   │    │   └── Monthly sequence
                │   │    └── Month
                │   └── Year
                └── Prefix (Faktura VAT)
```

---

## Database Schema: pos_transactions

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint PK | Auto-increment |
| `receipt_number` | varchar(30) UNIQUE | Auto: WC-260217-0001 |
| `terminal_transaction_id` | varchar(100) | Card terminal reference |
| `client_name` | varchar(200) | Client full name |
| `client_phone` | varchar(30) | Client phone (required) |
| `client_email` | varchar(100) | Optional |
| `client_passport` | varchar(50) | Optional (for immigration) |
| `service_type` | varchar(30) | ServiceTypeEnum value |
| `service_description` | text | Free-text description |
| `documents_submitted` | JSON | Array of document names |
| `payment_method` | varchar(20) | cash / card_terminal / bank_transfer / blik |
| `amount` | decimal(10,2) | Gross amount (client pays) |
| `currency` | varchar(3) | PLN (default) / EUR / USD |
| `tax_rate` | decimal(5,2) | 23.00 (VAT Poland default) |
| `tax_amount` | decimal(10,2) | Calculated on approve |
| `net_amount` | decimal(10,2) | Calculated on approve |
| `status` | varchar(20) | PosTransactionStatusEnum |
| `decision_notes` | text | Owner's notes on decision |
| `decided_by` | FK → users | Who made the decision |
| `decided_at` | timestamp | When decision was made |
| `refund_amount` | decimal(10,2) | Amount to refund (= amount) |
| `refund_method` | varchar(20) | How refund was done |
| `refund_reason` | text | Why rejected |
| `refunded_at` | timestamp | When refund completed |
| `client_id` | FK → clients | ONLY after invoiced |
| `case_id` | FK → cases | ONLY after invoiced |
| `invoice_id` | FK → invoices | ONLY after invoiced |
| `payment_id` | FK → payments | ONLY after invoiced |
| `received_by` | FK → users | Operator who took payment |

**28 columns, 6 indexes, soft deletes**

---

## Usage Examples

### 1. Operator registers cash payment
```bash
curl -X POST /api/v1/pos/receive \
  -H "Authorization: Bearer {token}" \
  -d '{
    "client_name": "Oleksiy Shevchenko",
    "client_phone": "+48500111222",
    "service_type": "karta_pobytu",
    "payment_method": "cash",
    "amount": 500.00,
    "documents_submitted": ["Passport copy", "Lease agreement", "Work contract"]
  }'
```

### 2. Owner checks pending
```bash
curl -X GET /api/v1/pos/pending \
  -H "Authorization: Bearer {token}"
```

### 3. Owner approves → creates CRM records
```bash
# Step 1: Approve
curl -X PATCH /api/v1/pos/42/approve \
  -d '{"notes": "Regular client, karta pobytu standard"}'

# Step 2: Process → CRM
curl -X POST /api/v1/pos/42/process \
  -d '{"case_id": 15}'
```

### 4. Owner rejects → refund
```bash
# Step 1: Reject
curl -X PATCH /api/v1/pos/43/reject \
  -d '{"reason": "Client documents incomplete, cannot proceed"}'

# Step 2: Confirm refund
curl -X PATCH /api/v1/pos/43/refund \
  -d '{"refund_method": "cash"}'
```

---

## Installation

```bash
# Copy files to project
cp enums/PosTransactionStatusEnum.php backend/app/Enums/
cp enums/PaymentMethodEnum.php backend/app/Enums/
cp migrations/*.php backend/database/migrations/
cp models/PosTransaction.php backend/app/Models/
cp services/PosService.php backend/app/Services/
cp services/PosController.php backend/app/Http/Controllers/Api/V1/

# Append routes to api.php
cat services/api_pos_routes.php >> backend/routes/api.php

# Run migration
cd backend
php artisan migrate

# Verify
php artisan route:list --path=pos
```

---

## Integration with Dashboard (Phase 7)

POS widget on Dashboard shows:
- **Pending count** (badge with count, orange if > 0)
- **Total pending amount** (how much money awaits decision)
- **Oldest waiting** (hours since oldest pending transaction)
- **Today's cash flow** (approved cash + card)
- **Monthly VAT** (total tax for current month)

---

## Updated Project Statistics

| Metric | Before POS | After POS |
|--------|------------|-----------|
| Tables | 21 + 7 = 28 | **29** (+1 pos_transactions) |
| API Endpoints | 30+ | **41+** (+11 POS) |
| Enums | 9 | **11** (+2 POS) |
| Services | 35+ | **36+** (+1 PosService) |

<!--
Аннотация (RU):
Полная документация POS-модуля WINCASE CRM v4.0.
POS-терминал + приёмник наличных со staging-зоной.
Бизнес-логика: клиент платит → данные в отдельную таблицу →
владелец решает (approve → CRM + налог ИЛИ reject → refund).
CRM не засоряется временными данными.
11 API endpoints, 2 enum, 1 миграция, 1 модель, 1 сервис, 1 контроллер.
Файл: docs/POS_MODULE.md
-->
