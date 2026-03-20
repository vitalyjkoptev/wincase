# WINCASE CRM v4.0 — Vue.js Admin Panel (Phase 16)

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Vue.js 3.5 + Vite 6                       │
│                                                              │
│  ┌─────────┐  ┌──────────┐  ┌─────────┐  ┌──────────────┐  │
│  │ Router   │  │ Pinia    │  │ Axios   │  │ TailwindCSS  │  │
│  │ 15 routes│  │ 5 stores │  │ Bearer  │  │ Brand theme  │  │
│  │ Auth     │  │ Reactive │  │ Token   │  │ #1F3864      │  │
│  │ RBAC     │  │ State    │  │ Sanctum │  │ Responsive   │  │
│  └─────────┘  └──────────┘  └─────────┘  └──────────────┘  │
│                                                              │
│  ┌─────────────────────────────────────────────────────────┐│
│  │                    13 Views                              ││
│  │  Login | Dashboard | Leads (3) | Clients (2)            ││
│  │  Cases (2) | POS | Tasks | Calendar | Settings          ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
         │
         ▼ HTTPS (Bearer Token)
┌─────────────────────────────────────────────────────────────┐
│  Laravel 12 API (169+ endpoints)                             │
└─────────────────────────────────────────────────────────────┘
```

## Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| Vue.js | 3.5.13+ | UI Framework (Composition API + `<script setup>`) |
| Vite | 6.0+ | Build tool, dev server, HMR |
| TypeScript | 5.7+ | Type safety |
| Pinia | 2.3+ | State management |
| Vue Router | 4.5+ | Client-side routing, auth guard, RBAC |
| TailwindCSS | 3.4+ | Utility-first CSS, brand theme |
| Axios | 1.7+ | HTTP client, Bearer token, interceptors |
| Chart.js | 4.4+ | Dashboard charts |
| vue-chartjs | 5.3+ | Vue wrapper for Chart.js |
| @heroicons/vue | 2.2+ | SVG icons (outline set) |
| @headlessui/vue | 1.7+ | Accessible UI components |
| vue-toastification | 2.0+ | Toast notifications |
| vue-i18n | 10.0+ | i18n (PL, EN, RU) |
| dayjs | 1.11+ | Date formatting |
| @vueuse/core | 11.3+ | Composable utilities |

## Routes (15 main + nested)

| Route | View | RBAC Ability | Description |
|-------|------|-------------|-------------|
| `/login` | LoginView | Public | Email + password + 2FA |
| `/dashboard` | DashboardView | dashboard:read | 12 KPI + 5 sections |
| `/leads` | LeadsListView | leads:read | Table + search + filters |
| `/leads/create` | LeadCreateView | leads:write | Create form (7 fields) |
| `/leads/:id` | LeadDetailView | leads:read | Detail + status transitions |
| `/clients` | ClientsListView | clients:read | Table + nationality filter |
| `/clients/:id` | ClientDetailView | clients:read | 4 tabs (Profile/Cases/Docs/Timeline) |
| `/cases` | CasesListView | cases:read | Table + progress + deadlines |
| `/cases/:id` | CaseDetailView | cases:read | 6-stage progress, 5 tabs |
| `/pos` | PosView | pos:read | Pending/Completed + receive |
| `/tasks` | TasksView | tasks:read | Kanban-lite (3 columns) |
| `/calendar` | CalendarView | calendar:read | Monthly grid, 7 event types |
| `/settings` | SettingsView | * (admin) | Users, System, Brand, Integrations |

## Pinia Stores (5)

| Store | Key State | Actions |
|-------|-----------|---------|
| `auth` | token, user, permissions | login, fetchMe, logout |
| `dashboard` | kpi, leads, finance, ads, social, seo | load, refreshKpi |
| `leads` | leads[], selected, filters | load, create, updateStatus, convert |
| `cases` | cases[], selected, filters | load, create, changeStatus, assign |
| `clients` | clients[], selected, filters | load, create, update |

## Auth Flow

```
Login → POST /auth/login → Token stored (localStorage)
  ↓ (if 2FA enabled)
  → Show 2FA input → re-POST with code → Token
  ↓
Router guard → fetchMe() → user + permissions loaded
  ↓
Each route checks: auth.user.permissions.includes(route.meta.ability)
```

## Design System

| Element | Value |
|---------|-------|
| Brand Primary | `#1F3864` (Navy) |
| Brand Light | `#2B4F8C` |
| Accent | `#D4A843` (Gold) |
| Font | Inter |
| Card Radius | 12px (rounded-xl) |
| Button Radius | 8px (rounded-lg) |
| Sidebar Width | 256px (w-64) |
| Topbar Height | 64px (h-16) |
| Breakpoints | sm:640 md:768 lg:1024 xl:1280 |

## Files Structure

```
frontend/
├── package.json                         # Dependencies
├── vite.config.ts                       # Vite + proxy config
├── tailwind.config.js                   # Brand colors, Inter font
├── src/
│   ├── main.ts                          # App entry (Pinia, Router, i18n, Toast)
│   ├── App.vue                          # Root component
│   ├── composables/
│   │   ├── useApi.ts                    # Axios + Bearer + error handling
│   │   └── useAuth.ts                   # Auth composable (isAdmin, hasAbility)
│   ├── stores/
│   │   ├── auth.ts                      # Auth state (token, user, login/logout)
│   │   ├── dashboard.ts                 # Dashboard data
│   │   ├── leads.ts                     # Leads CRUD + filters
│   │   ├── cases.ts                     # Cases CRUD + status
│   │   └── clients.ts                   # Clients CRUD + search
│   ├── router/
│   │   └── index.ts                     # 15 routes + auth guard + RBAC
│   ├── layouts/
│   │   └── AdminLayout.vue              # Sidebar + Topbar + RBAC nav
│   └── views/
│       ├── auth/LoginView.vue           # Login + 2FA
│       ├── dashboard/DashboardView.vue  # 12 KPI + 5 sections
│       ├── leads/
│       │   ├── LeadsListView.vue        # Table + filters
│       │   ├── LeadDetailView.vue       # Status transitions + convert
│       │   └── LeadCreateView.vue       # Create form
│       ├── clients/
│       │   ├── ClientsListView.vue      # Table + nationality
│       │   └── ClientDetailView.vue     # 4 tabs
│       ├── cases/
│       │   ├── CasesListView.vue        # Table + progress
│       │   └── CaseDetailView.vue       # 6-stage progress, 5 tabs
│       ├── pos/PosView.vue              # Pending/Completed + receive
│       ├── tasks/TasksView.vue          # Kanban-lite
│       ├── calendar/CalendarView.vue    # Monthly grid
│       └── settings/SettingsView.vue    # Admin panel
```

## Commands

```bash
# Install
cd frontend
npm install

# Development
npm run dev          # → http://localhost:3000

# Type check
npm run type-check

# Lint
npm run lint

# Production build
npm run build        # → dist/

# Preview production
npm run preview
```

<!--
Аннотация (RU):
Phase 16 — Vue.js 3.5 Admin Panel.
13 views (Login, Dashboard, Leads×3, Clients×2, Cases×2, POS, Tasks, Calendar, Settings).
5 Pinia stores (auth, dashboard, leads, cases, clients).
Vue Router с auth guard + RBAC ability check.
TailwindCSS с brand theme (#1F3864 navy, #D4A843 gold).
i18n: PL, EN, RU. Axios + Sanctum Bearer token.
Файл: docs/VUE_FRONTEND_MODULE.md
-->
