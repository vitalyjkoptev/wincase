# WINCASE CRM v4.0 — Core CRM + n8n Workflows (Phase 12-13)

---

## Phase 12: Core CRM Backend

### 5 Modules — 35 API Endpoints

| Module | Endpoints | Key Features |
|--------|-----------|-------------|
| **Clients** | 8 | Search (name/email/phone/passport), filters, timeline, nationality/language stats |
| **Cases** | 8 | 10-status workflow, auto case_number (LEG-25-0001), progress bar, deadlines |
| **Tasks** | 7 | My tasks, overdue tracking, due_today/due_this_week, priority |
| **Documents** | 6 | 17 document types, private storage, 30-min temp URLs, expiry alerts |
| **Calendar** | 6 | 7 event types, today schedule, upcoming events, per-user |

### Case Status Workflow (10 statuses)
```
active → pending_docs → submitted → in_review → approved → completed → closed
   ↓         ↓              ↓           ↓
on_hold  cancelled      returned     rejected
```

### Document Types (17)
passport, visa, residence_card (Karta Pobytu), work_permit, PESEL, meldunek,
contract, tax_document, diploma, marriage_cert, birth_cert, bank_statement,
insurance, photo (3.5x4.5), power_of_attorney, application_form, other

### Calendar Event Types (7)
consultation, meeting, deadline, court (Urząd hearing), reminder, follow_up, appointment

---

## Phase 13: n8n Workflows Registry

### 22 Workflows by Module

| ID | Workflow | Trigger | Frequency |
|----|----------|---------|-----------|
| **Leads** | | | |
| W01 | Lead Capture — Multi-Source | Webhook | Real-time |
| W02 | Lead Auto-Response | W01 event | Real-time (30s) |
| W03 | Lead Nurturing Follow-up | Cron | Every 4h |
| **Ads** | | | |
| W04 | Google Ads Sync | Cron | Every 6h |
| W05 | Meta Ads Sync | Cron | Every 6h |
| W06 | TikTok Ads Sync | Cron | Every 6h |
| W07 | Pinterest + YouTube Sync | Cron | Daily 06:00 |
| **SEO** | | | |
| W08 | GSC + GA4 Sync | Cron | Daily 07:00 |
| W09 | Ahrefs + Network Sync | Cron | Weekly Mon 08:00 |
| **Social/Brand** | | | |
| W10 | Reviews Sync (4 platforms) | Cron | Every 2h |
| W11 | Social Accounts Sync | Cron | Daily 09:00 |
| W12 | Social Post Analytics | Cron | Every 4h |
| W13 | Scheduled Post Publisher | Cron | Every 5 min |
| W14 | Unified Inbox Poll | Cron | Every 10 min |
| W15 | Brand Mentions Monitor | Cron | Every 3h |
| W16 | NAP Consistency Check | Cron | Weekly Fri 10:00 |
| **Accounting** | | | |
| W17 | Bank Statement Import | Cron | Daily 22:00 |
| W18 | Invoice Generator | Event | Real-time (POS) |
| W19 | Tax Report Generator | Cron | Monthly 1st 06:00 |
| **System** | | | |
| W20 | Document Expiry Alert | Cron | Daily 08:00 |
| W21 | Case Deadline Alert | Cron | Daily 08:00 |
| W22 | System Health Monitor | Cron | Every 15 min |

### System Health Checks
- n8n instance reachability
- MySQL connection
- Redis availability
- Disk space (>10% free)
- API response

---

## Files Created (Phase 12-13)

```
core-module/
├── services/
│   ├── ClientsService.php              # Clients CRUD, search, timeline, stats
│   ├── CasesService.php                # Cases lifecycle, 10 statuses, deadlines
│   └── TasksDocumentsCalendar.php      # Tasks + Documents + Calendar (3 services)
├── controllers/
│   └── CoreControllers.php             # 5 controllers, 35 endpoints
└── routes/
    └── api_core_routes.php             # 35 routes (auth:sanctum)

n8n-module/
├── workflows_registry.php              # 22 workflows config + descriptions
├── N8nHealthService.php                # Health monitoring + execution tracking
└── CORE_N8N_MODULE.md                  # This documentation
```

---

## API Endpoints Summary (35 Core CRM)

### Clients (8)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /clients | List + search + filter |
| GET | /clients/statistics | By status/nationality/language |
| GET | /clients/:id | Full profile + timeline |
| POST | /clients | Create client |
| PUT | /clients/:id | Update client |
| POST | /clients/:id/archive | Archive client |
| POST | /clients/:id/activate | Re-activate |

### Cases (8)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /cases | List + filter |
| GET | /cases/deadlines | Upcoming deadlines |
| GET | /cases/statistics | By status/service/duration |
| GET | /cases/:id | Detail + progress bar |
| POST | /cases | Create (auto case_number) |
| PUT | /cases/:id | Update |
| POST | /cases/:id/status | Change status (validated) |
| POST | /cases/:id/assign | Assign to manager |

### Tasks (7)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /tasks | List + filter |
| GET | /tasks/my | Current user tasks |
| GET | /tasks/overdue | All overdue tasks |
| GET | /tasks/statistics | Stats |
| POST | /tasks | Create task |
| PUT | /tasks/:id | Update |
| POST | /tasks/:id/complete | Mark complete |

### Documents (6)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /documents/client/:id | By client |
| GET | /documents/case/:id | By case |
| GET | /documents/expiring | Expiring soon |
| POST | /documents/upload | Upload (max 20MB) |
| GET | /documents/:id/download | Temp download URL |
| DELETE | /documents/:id | Delete |

### Calendar (6)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /calendar | Events (date range) |
| GET | /calendar/today | Today schedule |
| GET | /calendar/upcoming | Next 7 days |
| POST | /calendar | Create event |
| PUT | /calendar/:id | Update |
| DELETE | /calendar/:id | Delete |

<!--
Аннотация (RU):
Phase 12 — Core CRM Backend: Clients (CRUD + timeline + stats),
Cases (10 статусов, workflow transitions, auto-numbering),
Tasks (my tasks, overdue, priority), Documents (17 типов, private storage, expiry),
Calendar (7 типов событий). 35 API endpoints.
Phase 13 — 22 n8n Workflows: Leads (W01-03), Ads (W04-07), SEO (W08-09),
Social/Brand (W10-16), Accounting (W17-19), System (W20-22).
N8nHealthService — мониторинг здоровья системы.
Файл: docs/CORE_N8N_MODULE.md
-->
