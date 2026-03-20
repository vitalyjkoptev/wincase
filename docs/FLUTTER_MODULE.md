# WINCASE CRM v4.0 — Flutter Mobile App (Phase 9)
## Flutter 3.29+ | Dart 3.7+ | iOS + Android

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Flutter Mobile App                         │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  Screens (UI Layer)                                   │   │
│  │  ┌──────────┬────────┬────────┬──────────────────┐   │   │
│  │  │Dashboard │ Leads  │  POS   │  Notifications    │   │   │
│  │  │(KPI Grid │(List/  │(Pending│  (FCM Push)       │   │   │
│  │  │ +Cards)  │Detail/ │/Recv/  │                   │   │   │
│  │  │          │Create) │Approve)│                   │   │   │
│  │  └──────────┴────────┴────────┴──────────────────┘   │   │
│  └──────────────────────────┬───────────────────────────┘   │
│                             │                                │
│  ┌──────────────────────────┴───────────────────────────┐   │
│  │  Providers (State Layer — Riverpod)                   │   │
│  │  AuthProvider │ DashboardProvider │ LeadsProvider │    │   │
│  │  PosProvider  │ NotificationsProvider                 │   │
│  └──────────────────────────┬───────────────────────────┘   │
│                             │                                │
│  ┌──────────────────────────┴───────────────────────────┐   │
│  │  Core (Infrastructure Layer)                          │   │
│  │  ApiClient (Dio + Sanctum) │ GoRouter │ FCM Service   │   │
│  └──────────────────────────────────────────────────────┘   │
└───────────────────────────────┬─────────────────────────────┘
                                │ HTTPS / WSS
                                ▼
                    Laravel 12 Backend API
                    (101+ endpoints, Reverb WS)
```

---

## Screens (5 main + 3 nested = 8 total)

| Screen | Route | Purpose |
|--------|-------|---------|
| **Login** | `/login` | Email + Password → Sanctum token |
| **Dashboard** | `/dashboard` | 12 KPI cards (3x4 grid) + 5 section summary cards |
| **Leads List** | `/leads` | Filterable list with status chips, FAB → create |
| **Lead Detail** | `/leads/:id` | Full info, status change buttons, convert to client |
| **Lead Create** | `/leads/create` | Form: name, phone, email, source, service, language |
| **POS** | `/pos` | Tabs (Pending/Completed), FAB → Receive Payment |
| **POS Detail** | `/pos/:id` | Amount card, full info, Approve/Reject buttons |
| **Notifications** | `/notifications` | Push notifications list with type icons |

---

## State Management (Riverpod)

| Provider | Type | Purpose |
|----------|------|---------|
| `authProvider` | StateNotifier | Login/logout, token management |
| `dashboardProvider` | StateNotifier | KPI + 6 sections, WebSocket updates |
| `leadsProvider` | StateNotifier | CRUD, filter, convert to client |
| `posProvider` | StateNotifier | Transactions, receive, approve, reject |
| `notificationsProvider` | FutureProvider | GET /notifications |

---

## API Integration

| Feature | Method | Endpoint |
|---------|--------|----------|
| Login | POST | `/auth/login` |
| Logout | POST | `/auth/logout` |
| Full Dashboard | GET | `/dashboard` |
| KPI Bar | GET | `/dashboard/kpi` |
| Leads List | GET | `/leads` |
| Lead Detail | GET | `/leads/:id` |
| Create Lead | POST | `/leads` |
| Update Lead | PATCH | `/leads/:id` |
| Convert Lead | POST | `/leads/:id/convert` |
| POS History | GET | `/pos/history` |
| POS Pending | GET | `/pos/pending` |
| Receive Payment | POST | `/pos/receive` |
| Approve POS | POST | `/pos/:id/approve` |
| Reject POS | POST | `/pos/:id/reject` |
| POS Detail | GET | `/pos/:id` |
| Notifications | GET | `/notifications` |

---

## Push Notifications (Firebase Cloud Messaging)

### Flow
```
Laravel Event → FCM HTTP v1 API → Firebase → iOS/Android
                                         ↓
                              FlutterLocalNotificationsPlugin
                                         ↓
                              Tap → GoRouter deep link
```

### Notification Types
| Type | Trigger | Action |
|------|---------|--------|
| `lead` | New lead created | Navigate to `/leads/:id` |
| `payment` | POS payment received | Navigate to `/pos/:id` |
| `case` | Case status change | Navigate to case detail |
| `task` | Task deadline approaching | Navigate to task |
| `system` | System alert | Show notification |

### Topics
| Topic | Subscribers |
|-------|-------------|
| `managers` | All managers |
| `admins` | Admin users |
| `pos_operators` | POS terminal operators |

---

## Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| flutter_riverpod | ^2.6.1 | State management |
| go_router | ^14.8.0 | Navigation + auth guard |
| dio | ^5.7.0 | HTTP client |
| flutter_secure_storage | ^9.2.4 | Token storage (Keychain/Keystore) |
| firebase_messaging | ^15.2.0 | Push notifications |
| flutter_local_notifications | ^18.0.1 | Foreground notifications |
| fl_chart | ^0.70.2 | Dashboard charts |
| intl | ^0.19.0 | Number/date formatting |
| web_socket_channel | ^3.0.2 | Reverb WebSocket |

---

## Files Created (Phase 9)

```
wincase-flutter/
├── pubspec.yaml                                    # Dependencies
├── lib/
│   ├── main.dart                                   # Entry point, Material 3 theme
│   ├── core/
│   │   ├── api_client.dart                         # Dio + Sanctum auth interceptors
│   │   └── app_router.dart                         # GoRouter + auth guard + bottom nav
│   ├── models/
│   │   └── models.dart                             # KpiData, Lead, PosTransaction, Notification
│   ├── providers/
│   │   ├── auth_provider.dart                      # Login/logout state
│   │   ├── dashboard_provider.dart                 # KPI + sections + WebSocket
│   │   ├── leads_provider.dart                     # CRUD + filter + convert
│   │   └── pos_provider.dart                       # Transactions + approve/reject
│   ├── screens/
│   │   ├── auth/login_screen.dart                  # Email + password login
│   │   ├── dashboard/dashboard_screen.dart         # 12 KPI grid + 5 section cards
│   │   ├── leads/leads_list_screen.dart            # List + status filter chips
│   │   ├── leads/lead_detail_screen.dart           # Detail + status actions + convert
│   │   ├── leads/lead_create_screen.dart           # Create form (7 fields)
│   │   ├── pos/pos_screen.dart                     # Tabs + receive + approve/reject
│   │   ├── pos/pos_detail_screen.dart              # Transaction detail + actions
│   │   └── notifications/notifications_screen.dart # Push notifications list
│   └── services/
│       └── push_notification_service.dart           # FCM + local notifications
└── FLUTTER_MODULE.md                                # This documentation
```

---

## Setup & Run

```bash
# Prerequisites
flutter --version  # 3.29+
dart --version     # 3.7+

# Clone and install
cd wincase-flutter
flutter pub get

# Firebase setup
flutterfire configure  # Select iOS + Android project

# Run
flutter run                    # Debug
flutter run --release          # Release
flutter build apk --release    # Android APK
flutter build ios --release    # iOS (requires Xcode)

# Code generation (models)
dart run build_runner build --delete-conflicting-outputs
```

---

## Design System

| Element | Value |
|---------|-------|
| Brand Color | `#1F3864` (Navy) |
| Accent | `#2E75B6` (Blue) |
| Design System | Material 3 |
| Theme | Light + Dark (auto) |
| Card Radius | 12px |
| Button Radius | 8px |
| Bottom Nav | 4 tabs (Dashboard, Leads, POS, Alerts) |

<!--
Аннотация (RU):
Flutter Mobile App WINCASE CRM v4.0.
8 экранов: Login, Dashboard (12 KPI + 5 cards), Leads (list/detail/create),
POS (pending/receive/approve), Notifications.
Riverpod (state), GoRouter (nav + auth guard), Dio (HTTP + Sanctum),
Firebase (push), Material 3 (light + dark).
16 файлов Dart + pubspec.yaml + docs.
Файл: docs/FLUTTER_MODULE.md
-->
