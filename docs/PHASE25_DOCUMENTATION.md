# =====================================================
# PHASE 25: TICKET SYSTEM + ADMIN PANEL V2
# WinCase CRM v4.0
# =====================================================

## OVERVIEW

Phase 25 delivers two major modules:
1. **Complete Ticket System** — 8 types, 13 categories, 9 statuses, SLA, escalation, auto-routing
2. **Admin Panel V2** — 20 screens, sidebar with 8 sections, RBAC, maintenance tools

## FILES STRUCTURE

```
Phase 25 — 15 files, ~4,200 lines
│
├── BACKEND (Laravel)
│   ├── database/migrations/2026_02_20_200001_create_tickets_tables.php    (Migration: 3 tables)
│   ├── app/Services/Tickets/TicketService.php                              (Business logic)
│   ├── app/Http/Controllers/Api/V1/TicketController.php                    (14 endpoints)
│   ├── app/Models/Ticket.php                                               (Model + relations)
│   ├── app/Models/TicketMessage.php                                        (Message model)
│   ├── app/Config/TicketWorkflows.php                                      (n8n W36-W38)
│   └── database/seeders/TicketSlaSeeder.php                                (SLA rules)
│
├── FRONTEND (Vue 3.5 + TypeScript + Tailwind)
│   ├── src/layouts/AppLayout.vue                                           (Sidebar + header)
│   ├── src/components/NotificationsPanel.vue                               (Bell + dropdown)
│   ├── src/views/tickets/TicketsView.vue                                   (Board + List + Detail)
│   ├── src/views/maintenance/MaintenanceView.vue                           (5 tabs)
│   ├── src/views/DashboardView.vue                                         (12 KPIs)
│   ├── src/views/crm/LeadsView.vue                                         (14 sources, 10 statuses)
│   ├── src/views/crm/ClientsView.vue                                       (Table + detail)
│   ├── src/views/crm/CasesView.vue                                         (10 statuses, deadline)
│   ├── src/views/crm/TasksView.vue                                         (Checklist + priority)
│   ├── src/views/crm/DocumentsView.vue                                     (17 types, expiry)
│   ├── src/views/crm/CalendarView.vue                                      (7 event types)
│   ├── src/views/finance/POSView.vue                                       (POS terminal + VAT)
│   ├── src/views/finance/AccountingView.vue                                (Invoices, VAT, Reports)
│   ├── src/views/marketing/AdsView.vue                                     (5 platforms)
│   ├── src/views/marketing/SEOView.vue                                     (Keywords, positions)
│   ├── src/views/marketing/SocialView.vue                                  (8 platforms)
│   ├── src/views/marketing/BrandView.vue                                   (54 directories)
│   ├── src/views/marketing/LandingsView.vue                                (4 domains)
│   ├── src/views/analytics/ReportsView.vue                                 (8 report types)
│   ├── src/views/admin/UsersView.vue                                       (RBAC, 5 roles)
│   ├── src/views/admin/AuditView.vue                                       (8 action types)
│   ├── src/views/admin/SystemView.vue                                      (Health + metrics)
│   ├── src/views/admin/SettingsView.vue                                    (Profile, 13 integrations)
│   └── src/router/index.ts                                                 (28 routes, RBAC guard)
```

## 1. TICKET SYSTEM

### Database: 3 Tables

| Table | Columns | Description |
|-------|---------|-------------|
| tickets | 30+ cols | Main: ticket_number, type, category, priority, status, SLA, assigned, client |
| ticket_messages | 9 cols | Thread: reply, internal_note, status_change, assignment, escalation, system |
| ticket_sla_rules | 6 cols | SLA: priority → first_response_hours + resolution_hours |

### Types (8)
client_request, internal_task, bug_report, feature_request, complaint, billing, legal, technical

### Categories (13)
visa_immigration, documents, payment, consultation, website, crm_system, news_pipeline, advertising, seo, social_media, server_hosting, mobile_app, general

### Statuses (9)
new → open → in_progress → waiting_client / waiting_internal / on_hold → resolved → closed / reopened

### SLA Rules
| Priority | First Response | Resolution |
|----------|---------------|------------|
| Critical | 1 hour | 4 hours |
| High | 2 hours | 8 hours |
| Medium | 4 hours | 24 hours |
| Low | 8 hours | 72 hours |

### API Endpoints (14)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /v1/tickets | List with 10 filters |
| GET | /v1/tickets/my | Assigned to current user |
| GET | /v1/tickets/stats | 13 dashboard metrics |
| GET | /v1/tickets/types | Available enums |
| GET | /v1/tickets/overdue | SLA breached tickets |
| GET | /v1/tickets/{id} | Detail with messages |
| POST | /v1/tickets | Create ticket |
| PUT | /v1/tickets/{id} | Update ticket |
| POST | /v1/tickets/{id}/status | Change status (9 options) |
| POST | /v1/tickets/{id}/assign | Assign to user |
| POST | /v1/tickets/{id}/escalate | Escalate to management |
| POST | /v1/tickets/{id}/message | Add reply / internal note |
| DELETE | /v1/tickets/{id} | Soft delete |

### n8n Workflows (W36-W38)
| ID | Name | Schedule | Description |
|----|------|----------|-------------|
| W36 | SLA Breach Monitor | */15 min | Auto-escalate overdue → Telegram alert |
| W37 | Ticket Router | Webhook | Auto-assign by category + least-loaded staff |
| W38 | Daily Digest | 20:30 | Stats summary → Telegram + Email |

## 2. ADMIN PANEL V2

### Sidebar Menu — 8 Sections, 27 Items

| Section | Items | Roles |
|---------|-------|-------|
| 🏠 Main | Dashboard | All |
| 👥 CRM | Leads, Clients, Cases, Tasks, Documents, Calendar, Tickets | All (varies) |
| 💰 Finance | POS Terminal, Accounting | admin, accountant |
| 📢 Marketing | Advertising, SEO, Social Media, Brand, Landings | admin, manager |
| 📰 Content | News Pipeline | admin, manager |
| 📊 Analytics | Reports | All |
| ⚙️ Admin | Users, Audit Log, System Health | admin only |
| 🔧 Maintenance | Maintenance, Backups, Server Logs, Settings | admin (settings=all) |

### 20 Screen Views

| # | Screen | Features |
|---|--------|----------|
| 1 | Dashboard | 12 KPI cards, Ticket overview, Recent leads, Chart placeholders |
| 2 | Leads | 6 KPI, Table (7 cols), 14 sources, 10 statuses, Detail slide-over, Create |
| 3 | Clients | Table (6 cols), Detail with cases list, Create modal |
| 4 | Cases | Table (6 cols), 10 statuses, Deadline indicator, Create modal |
| 5 | Tasks | My/All toggle, Checklist with priority border, Complete toggle, Create |
| 6 | Documents | 17 doc types, Expiry tracking (color coded), Upload |
| 7 | Calendar | 7 event types, Month nav, List/Week/Month toggle, Color legend |
| 8 | Tickets | Board (Kanban 5 cols), List (8 cols), My Tickets, Detail panel, Create |
| 9 | POS | 4 KPI, Payments table (7 cols), Approve, Create modal, 5 methods |
| 10 | Accounting | 3 tabs (Invoices, VAT 23%, Reports), VAT summary 4 KPI |
| 11 | Advertising | 5 KPI, 5 Platform cards (Google/Meta/TikTok/LinkedIn/Twitter), Campaigns |
| 12 | SEO | 5 KPI, Keywords table (6 cols), Position color coding |
| 13 | Social Media | 3 tabs (Overview, Posts, Inbox), 8 Platform cards |
| 14 | Brand | 5 KPI, 54 directories, Reviews list with stars |
| 15 | Landings | 4 KPI, 4 Domain cards with languages |
| 16 | Reports | 8 Report type cards, Recent reports table, Download |
| 17 | Users | Table (7 cols), Inline role change, 2FA status, Toggle active, Create |
| 18 | Audit | Table (6 cols), 8 action types with icons, Filters |
| 19 | System | Services grid (status dots), Server metrics (CPU/RAM/Disk bars), Uptime |
| 20 | Maintenance | 5 tabs: Tools, Backups, Logs, Scheduler (20 jobs), Domains (14) |
| 21 | Settings | 3 tabs: Profile, Notifications (6 toggles), Integrations (13) |
| 22 | Notifications | Bell icon, Dropdown, 9 notification types, Mark read/all |

### RBAC — 5 Roles

| Role | Access |
|------|--------|
| admin | All screens |
| manager | CRM + Marketing + News + Reports |
| operator | CRM + POS + Tickets |
| accountant | Dashboard + POS + Accounting + Reports |
| viewer | Dashboard + CRM (read-only) + Reports |

### Router — 28 Routes
All lazy-loaded, AppLayout wrapper, RBAC navigation guard, route-level role restrictions.

## TOTAL PROJECT STATUS

| Metric | Count |
|--------|-------|
| Phases | 25 |
| Files | ~240 |
| Lines of code | ~39,000 |
| API Endpoints | 226+ |
| Database Tables | 48 |
| n8n Workflows | 38 (W01-W38) |
| Vue Screens | 22 |
| Vue Routes | 28 |

---
Generated: 2026-02-20 | WinCase CRM v4.0 | WebWave Developers
