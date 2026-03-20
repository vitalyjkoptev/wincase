# =====================================================
# PHASE 27: FLUTTER V2 — MOBILE APP UPDATE
# WinCase CRM v4.0
# =====================================================

## OVERVIEW

Flutter V2 adds Tickets, News Pipeline, enhanced Dashboard, and Notifications
to the mobile app. Bottom navigation upgraded from 4 to 6 tabs.

## FILE STRUCTURE

```
lib/
├── main.dart                              (App entry, AuthGate, Login, MainNav, Profile)
├── services/
│   └── api_service.dart                   (40+ API methods, secure storage)
├── screens/
│   ├── dashboard_screen.dart              (12 KPIs, ticket stats, quick actions)
│   ├── tickets_screen.dart                (List, Detail+messages, Create)
│   └── news_notifications_leads.dart      (News feed, Notifications, Leads V2)
└── pubspec.yaml                           (25+ dependencies)
```

## SCREENS (6 tabs + sub-screens)

| # | Tab | Screen | Features |
|---|-----|--------|----------|
| 1 | 🏠 Home | Dashboard | 12 KPI grid (3x4), ticket overview (6 stats), 4 quick action buttons |
| 2 | 👤 Leads | Leads List | Card list with status badges, 14 sources, 10 statuses, FAB create |
| 3 | 🎫 Tickets | Tickets | 2 tabs (All/My), stats chips, card list, create bottom sheet |
| 3a | | Ticket Detail | Info header, status change chips, message thread, reply input |
| 4 | 📰 News | News Feed | 4 stats, 8 site filter chips, article cards (plagiarism %, published sites) |
| 5 | 🔔 Alerts | Notifications | 9 types with icons, unread badge, mark read/all, time ago |
| 6 | 👤 Profile | Profile | Avatar, name/email/role, 5 menu items, logout, version info |

## API SERVICE — 40+ Methods

| Group | Methods |
|-------|---------|
| Auth | login, logout, me, token |
| Dashboard | dashboardKpi |
| Leads | leads, leadDetail, createLead, updateLeadStatus |
| Clients | clients, clientDetail |
| Cases | cases, caseDetail |
| Tasks | myTasks, updateTaskStatus, createTask |
| Tickets | tickets, myTickets, ticketStats, ticketDetail, createTicket, changeTicketStatus, assignTicket, escalateTicket, sendTicketMessage |
| News | newsFeed, newsStats |
| Notifications | notifications, markNotificationRead, markAllNotificationsRead, unreadCount |
| Calendar | calendarEvents |
| Documents | documents |
| POS | posPayments, posStats, createPayment |
| System | systemHealth |

## KEY FEATURES

### Authentication
- FlutterSecureStorage for token
- AuthGate: auto-check token on launch → Login or MainNav
- Auto-redirect on 401 (AuthException)

### Tickets (NEW)
- All/My tabs with TabController
- Horizontal stats chips (new/critical/overdue/open/today)
- Card list: ticket_number, priority badge, subject, status, category, assignee
- Detail page: status change chips, message thread (system/internal/reply), reply input
- Create: bottom sheet with subject, priority, type, description

### News Feed (NEW)
- 4 stat counters (published/rewriting/review/total)
- 8 site filter chips with icons
- Article cards: status badge, source, plagiarism %, title, published_sites, category

### Notifications (NEW)
- 9 notification types with emoji icons
- Unread: bold + blue background + blue dot
- Badge on bottom nav tab
- Mark read (tap) + mark all read button
- Time ago display

### Dashboard (ENHANCED)
- 12 KPI cards in 3-column grid
- Ticket overview: 6 colored stat boxes
- Quick actions: New Lead, New Ticket, New Task, New Payment

## DEPENDENCIES (25+)

| Category | Packages |
|----------|----------|
| HTTP | http, dio, web_socket_channel |
| State | provider |
| Storage | flutter_secure_storage, shared_preferences |
| UI | cupertino_icons, flutter_svg, cached_network_image, shimmer, flutter_slidable, badges |
| Navigation | go_router |
| Push | firebase_core, firebase_messaging, flutter_local_notifications |
| Utils | intl, timeago, url_launcher, package_info_plus |
| Charts | fl_chart |
| Files | image_picker, file_picker |
| Network | connectivity_plus |

## BUILD COMMANDS

```bash
# Development
flutter pub get
flutter run

# iOS build
flutter build ios --release

# Android build
flutter build apk --release
flutter build appbundle --release

# Web (optional)
flutter build web --release
```

## TOTAL PROJECT STATUS

| Metric | Count |
|--------|-------|
| Phases | 27 |
| Files | ~250 |
| Lines of code | ~42,000 |
| API Endpoints | 226+ |
| Database Tables | 48 |
| n8n Workflows | 38 |
| Flutter Screens | 6 tabs + 2 sub |
| Vue Screens | 22 |
| VPS Servers | 3 |
| Domains | 14 |

---
Generated: 2026-02-21 | WinCase CRM v4.0 | WebWave Developers
