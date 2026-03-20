# WinCase CRM — Детальный план дальнейших действий

## 📊 ТЕКУЩЕЕ СОСТОЯНИЕ

### Backend (основной проект /Herd/wincase/)
| Компонент | Готово | Проблемы |
|-----------|--------|----------|
| Модели | 14 из 26 | 12 моделей — пустые стабы с namespace `App\Models\Models` (двойной). `Case` — зарезервированное слово PHP |
| Миграции | 16 из 32 | 10 миграций — пустые (только id+timestamps): clients, cases, documents, tasks, invoices, payments, hearings, messages, social_*, n8n |
| Сервисы | 51 из 51 ✅ | Все реализованы (150-500 строк каждый) |
| V1 Контроллеры | 15 из 15 ✅ | Все реализованы, привязаны к сервисам |
| Root Контроллеры | 0 из 10 | Все пустые стабы — дублируют V1, можно удалить |
| Маршруты | 10 из 212 | `api.php` ведёт на пустые стабы. `api_routes.php` с 212 endpoints НЕ подключён |
| User модель | частично | Нет полей role, two_factor, phone, department (миграция есть, модель не обновлена) |

### Admin Panel (/Herd/wincase/Admin/)
| Страница | Статус | Данные |
|----------|--------|--------|
| Dashboard | PARTIAL | Хардкод, 5 KPI + 3 графика + 2 таблицы |
| Leads | PARTIAL | Хардкод таблица + модал Add Lead (без action) |
| Clients | PARTIAL | Хардкод таблица, нет модала добавления |
| Cases | PARTIAL | Хардкод таблица + progress bars |
| Tasks | PARTIAL | Хардкод таблица, нет модала |
| Calendar | PARTIAL | FullCalendar с хардкод-событиями, eventClick=alert() |
| Documents | PARTIAL | Хардкод таблица, нет Upload модала |
| Profile | PARTIAL | Хардкод, нет редактирования |
| POS | PARTIAL | Хардкод таблица 3 строки |
| Invoices | PARTIAL (мин.) | 3 строки, нет фильтров. Кнопка "Create" → 404 |
| Payments | PLACEHOLDER | "Coming soon" |
| Expenses | PLACEHOLDER | "Coming soon" |
| Advertising | PARTIAL | Хардкод + 2 ApexCharts |
| SEO | PLACEHOLDER+ | Только stat-карточки |
| Social Media | PLACEHOLDER+ | Только stat-карточки |
| Brand | PLACEHOLDER+ | Только stat-карточки |
| Landing Pages | PLACEHOLDER+ | Только stat-карточки |
| Articles | PLACEHOLDER+ | Только stat-карточки |
| Create Article | PLACEHOLDER | "Coming soon" |
| Categories | PLACEHOLDER | "Coming soon" |
| Sales Report | PLACEHOLDER | "Coming soon" |
| Traffic Report | PLACEHOLDER | "Coming soon" |
| Performance | PLACEHOLDER | "Coming soon" |
| Users | PARTIAL | Хардкод 3 строки, нет модала |
| Roles | PLACEHOLDER | "Coming soon" |
| Audit Log | PARTIAL | Хардкод 4 строки, нет фильтров |
| System | PARTIAL | Хардкод здоровье, кнопки не подключены |
| Settings | PLACEHOLDER | "Coming soon" |
| Sign In | FULL | Форма готова, action=заглушка |
| Reset Password | FULL | Форма готова, action=заглушка |

---

## 🔧 ПЛАН ДЕЙСТВИЙ (по приоритету)

---

### ФАЗА 1: Починка Backend (критические баги)

#### 1.1 Исправить модель Case → ClientCase
- Переименовать `app/Models/Case.php` → `app/Models/ClientCase.php` (Case — зарезервированное слово PHP)
- Исправить namespace во всех 12 стаб-моделях: `App\Models\Models` → `App\Models`
- Файлы: `Case.php`, `Invoice.php`, `Document.php`, `Task.php`, `Payment.php`, `Message.php`, `Hearing.php`, `SocialPost.php`, `SocialAccount.php`, `N8NWorkflow.php`, `AIGeneration.php`, `ClientVerification.php`

#### 1.2 Дописать пустые миграции
Нужно добавить все поля в таблицы (создать новые миграции ALTER):

**clients:**
- first_name, last_name, email, phone, nationality, passport_number, pesel, date_of_birth
- address, city, postal_code, voivodeship, language, status (active/archived/blacklisted)
- lead_id (FK), assigned_manager_id (FK), company_name, nip, gdpr_consent, notes
- created_at, updated_at, archived_at

**cases (→ client_cases):**
- client_id (FK), lead_id (FK), case_number (auto: WC-2026-0001)
- service_type (enum: work_permit, temp_residence, permanent_residence, eu_blue_card, family_reunification, citizenship, business_registration, visa, card_pobytu_exchange, karta_polaka, other)
- status (enum 10 шагов: new, documents_collecting, documents_review, submission_preparation, submitted_to_office, waiting_for_decision, additional_documents_requested, positive_decision, negative_decision, appeal)
- assigned_manager_id, priority, voivodeship, office_name
- submission_date, decision_date, deadline_date, appeal_deadline
- documents_required (JSON), documents_collected (JSON), progress_percentage
- amount, currency, is_paid, notes
- created_at, updated_at, closed_at

**documents:**
- client_id (FK), case_id (FK), uploaded_by (FK)
- type (enum: passport, visa, work_permit, residence_card, pesel_confirmation, zameldowanie, insurance, employment_contract, tax_certificate, bank_statement, photo, application_form, power_of_attorney, translation, apostille, other)
- original_name, file_path, file_size, mime_type
- status (pending, verified, rejected, expired)
- expiry_date, verified_at, verified_by, rejection_reason, notes
- created_at, updated_at

**tasks:**
- title, description, type (call, meeting, document, deadline, follow_up, other)
- case_id (FK), client_id (FK), lead_id (FK)
- assigned_to (FK), created_by (FK)
- priority (low, medium, high, urgent), status (pending, in_progress, completed, cancelled)
- due_date, completed_at, reminder_at
- created_at, updated_at

**invoices:**
- invoice_number (auto: FV/2026/02/001), client_id (FK), case_id (FK)
- issue_date, due_date, paid_date
- net_amount, vat_rate, vat_amount, gross_amount, currency
- status (draft, sent, paid, overdue, cancelled)
- payment_method, bank_account, notes
- created_by (FK), created_at, updated_at

**payments:**
- invoice_id (FK), client_id (FK), pos_transaction_id (FK)
- amount, currency, payment_method (cash, card, transfer, online)
- payment_date, reference_number, status (pending, completed, failed, refunded)
- received_by (FK), notes
- created_at, updated_at

**hearings:**
- case_id (FK), client_id (FK)
- hearing_date, hearing_time, office_name, room_number
- type (interview, appeal_hearing, document_submission, oath, other)
- status (scheduled, completed, postponed, cancelled)
- interpreter_needed, interpreter_language
- result, notes, reminder_sent
- created_by (FK), created_at, updated_at

**messages:**
- client_id (FK), case_id (FK), user_id (FK)
- channel (email, sms, whatsapp, telegram, internal)
- direction (inbound, outbound)
- subject, body, status (sent, delivered, read, failed)
- sent_at, read_at
- created_at, updated_at

#### 1.3 Дописать модели (fillable, casts, relations)
Каждая из 12 стаб-моделей должна получить:
- `$fillable` массив (все поля из миграции)
- `$casts` (даты, enums, decimals, JSON)
- Relations (belongsTo/hasMany)
- Scopes (byStatus, active, overdue и т.д.)

#### 1.4 Обновить User модель
Добавить поля из миграции `2026_02_19_100003`:
- role, status, two_factor_enabled, two_factor_secret, phone, department, avatar_url, last_login_at, last_login_ip

#### 1.5 Подключить маршруты
- Заменить содержимое `routes/api.php` на `routes/api_routes.php`
- Или подключить `api_routes.php` в `bootstrap/app.php`
- Удалить 10 пустых Root-контроллеров (`app/Http/Controllers/Api/*.php` кроме ApiDocumentation)

---

### ФАЗА 2: Наполнение Admin страниц (PLACEHOLDER → FULL)

#### 2.1 Доработать PARTIAL страницы (заменить хардкод на динамику)
Каждая PARTIAL страница нуждается:
- Подключение к API через `fetch('/api/v1/...')` или через Blade `{{ $variable }}`
- DataTables инициализация (сортировка, поиск, пагинация серверная)
- Рабочие модалы (Add/Edit/Delete)
- Рабочие фильтры
- Рабочие action-кнопки

Порядок доработки (по важности для CRM):
1. **Leads** — главная воронка: таблица из API, модал Add Lead → POST /api/v1/leads, фильтры, действия (Assign, Convert, Delete)
2. **Clients** — таблица из API, модал Add Client, ссылка на Cases
3. **Cases** — таблица из API, workflow статусов, progress bar из documents collected
4. **Tasks** — таблица из API, модал Add Task, отметка Complete
5. **Documents** — таблица из API, Upload модал (Dropzone.js), Download
6. **Calendar** — события из API /calendar/events, модал New Event
7. **Dashboard** — KPI из /dashboard/kpi, графики из /dashboard/charts
8. **POS** — транзакции из API, Approve/Reject кнопки
9. **Invoices** — таблица из API + страница Create Invoice (form)
10. **Users** — CRUD из /users API
11. **Audit Log** — лог из /audit API + фильтры
12. **System** — данные из /system/health, кнопки → POST /system/cache-clear

#### 2.2 Дописать PLACEHOLDER страницы

**Finance:**
- `payments.blade.php` — таблица платежей (как invoices, с фильтрами по дате/методу/статусу)
- `expenses.blade.php` — таблица расходов, модал Add Expense, категории (15 типов), загрузка чеков

**Marketing:**
- `seo.blade.php` — 4 домена × показатели (clicks, impressions, position), графики трендов, таблица keywords
- `social-media.blade.php` — 8 платформ × метрики (followers, posts, engagement), таблица постов
- `brand.blade.php` — Reviews таблица (Google/Facebook/Trustpilot), NAP consistency checker, рейтинг
- `landing-pages.blade.php` — таблица 64 лендингов по 4 доменам, A/B тесты, конверсии

**Content:**
- `articles.blade.php` — таблица статей (источник, статус parse/rewrite/translate/approve/published), действия
- `create-article.blade.php` — Quill editor форма (title, body, category, language, target site)
- `categories.blade.php` — CRUD таблица категорий

**Analytics:**
- `sales.blade.php` — Revenue/Invoices/Payments графики (ApexCharts), таблица топ-клиентов
- `traffic.blade.php` — Organic vs Paid, по доменам, источники трафика
- `performance.blade.php` — KPI команды, время ответа, conversion rates, воронка

**Admin:**
- `roles.blade.php` — таблица ролей (Admin, Manager, Accountant, Viewer) + матрица permissions
- `settings.blade.php` — табы: General (company info), Integrations (API keys), Notifications (templates), Email (SMTP)

---

### ФАЗА 3: Внешние интеграции (что подключать, где регистрироваться)

#### 3.1 Google (обязательно)

| Сервис | Где регистрировать | Какие данные получить | Куда в CRM |
|--------|-------------------|----------------------|------------|
| **Google Ads** | ads.google.com → Settings → API | Client ID, Client Secret, Developer Token, Refresh Token | `config/services.php` → `google_ads.*` → Marketing/Advertising |
| **Google Analytics 4** | analytics.google.com → Admin → Data Streams | Measurement ID (G-XXXXX), API Secret | `config/services.php` → `ga4.*` → Marketing/SEO + Analytics/Traffic |
| **Google Search Console** | search.google.com/search-console → Settings | OAuth2 credentials (через Google Cloud Console) | `config/services.php` → `gsc.*` → Marketing/SEO |
| **Google Business Profile** | business.google.com | OAuth2 + Location ID | `config/services.php` → `google_business.*` → Marketing/Brand (reviews) |
| **Google Maps API** | console.cloud.google.com → APIs → Maps JS API | API Key | `.env` → `GOOGLE_MAPS_KEY` → Calendar (адреса офисов на карте) |

**Все Google сервисы через один проект в Google Cloud Console:**
1. Создать проект на console.cloud.google.com
2. Включить APIs: Google Ads API, Analytics Data API, Search Console API, My Business API, Maps JavaScript API
3. Создать OAuth 2.0 Client ID (Web Application)
4. Настроить consent screen
5. Получить Client ID + Client Secret → `.env`

#### 3.2 Meta (Facebook/Instagram)

| Сервис | Где регистрировать | Данные | Куда в CRM |
|--------|-------------------|--------|------------|
| **Meta Business Suite** | business.facebook.com → Settings → App | App ID, App Secret, Access Token (long-lived) | Marketing/Advertising (Meta Ads), Marketing/Social (FB+IG posts) |
| **Facebook Page** | facebook.com → Page → Settings → Advanced | Page ID, Page Access Token | Marketing/Social + Brand (reviews) |
| **Instagram Business** | через Meta Business Suite | IG Business Account ID | Marketing/Social |
| **Meta Conversions API** | Events Manager → Settings → CAPI | Pixel ID, Access Token | Leads tracking (откуда пришёл лид) |

**Регистрация:**
1. developers.facebook.com → Create App (Business type)
2. Add products: Marketing API, Pages API, Instagram API
3. Generate long-lived Page Access Token
4. `.env` → `META_APP_ID`, `META_APP_SECRET`, `META_ACCESS_TOKEN`, `META_PIXEL_ID`

#### 3.3 Другие рекламные платформы

| Платформа | Регистрация | API данные | Куда |
|-----------|-------------|-----------|------|
| **TikTok Ads** | ads.tiktok.com → Assets → App Management | App ID, Secret, Access Token | Marketing/Advertising |
| **Pinterest Ads** | ads.pinterest.com → Business Hub | App ID, App Secret, Access Token | Marketing/Advertising |
| **YouTube Ads** | Через Google Ads (YouTube campaigns) | Тот же Google Ads API | Marketing/Advertising |

#### 3.4 Social Media APIs

| Платформа | Регистрация | Куда в CRM |
|-----------|-------------|------------|
| **Telegram Bot** | t.me/BotFather → /newbot | `TELEGRAM_BOT_TOKEN` → Уведомления + Social |
| **LinkedIn** | developer.linkedin.com → Create App | Marketing/Social (LinkedIn posts) |
| **Twitter/X** | developer.twitter.com → Projects | Marketing/Social |

#### 3.5 SEO инструменты

| Сервис | Регистрация | Данные | Куда |
|--------|-------------|--------|------|
| **Ahrefs** | ahrefs.com → API → Generate Token | API Token | Marketing/SEO (backlinks, DR) |
| **Moz** | moz.com → API → Access | Access ID + Secret | Marketing/SEO (DA) |

#### 3.6 Reviews / Brand

| Платформ | Куда подключить |
|----------|----------------|
| **Google Reviews** | через Google Business API → Marketing/Brand |
| **Facebook Reviews** | через Meta Pages API → Marketing/Brand |
| **Trustpilot** | trustpilot.com → Business → API | Marketing/Brand |

#### 3.7 Платёжные системы

| Система | Регистрация | Куда |
|---------|-------------|------|
| **Stripe** | stripe.com → API Keys | Finance/POS (online payments) |
| **Przelewy24** | przelewy24.pl → Panel Transakcyjny → API | Finance/POS (Polish payments) |
| **Terminal POS** | Через банк (eService/SIX) | Finance/POS (card terminal) |

#### 3.8 Email & Notifications

| Сервис | Куда |
|--------|------|
| **SMTP** (Mailgun/Sendgrid/Amazon SES) | Уведомления по email, invoice sending |
| **Twilio / SMSapi.pl** | SMS-уведомления клиентам |
| **Pusher / Laravel Echo** | Real-time notifications в админке |

#### 3.9 Автоматизация

| Сервис | Куда |
|--------|------|
| **n8n** (self-hosted) | Автоматизация workflows: лид пришёл → задача создана → менеджер уведомлён |
| **OpenAI API** | Content/Articles (AI rewrite), AI assistant |

---

### ФАЗА 4: Интеграция Admin → Основной проект

#### 4.1 Перенос шаблона
- Скопировать `Admin/resources/views/` → `wincase/resources/views/admin/`
- Скопировать `Admin/public/assets/` → `wincase/public/admin-assets/`
- Настроить отдельный layout для admin (middleware `auth`, role check)
- Admin доступен по `/admin/*`

#### 4.2 Подключение к реальному API
- Все страницы переписать с хардкода на:
  - Blade + Controller (серверный рендеринг): передача `$data` из Service в view
  - Или AJAX (fetch): клиентские запросы к `/api/v1/*` endpoints

#### 4.3 Аутентификация
- Подключить Sanctum SPA auth (csrf-cookie + session)
- Login form → POST /api/v1/auth/login
- Middleware `auth:sanctum` на все admin routes
- Role-based access (Admin видит всё, Manager — CRM+Finance, Accountant — Finance)

---

### ФАЗА 5: Данные для демонстрации

#### 5.1 Seeders (тестовые данные)
Создать DatabaseSeeder с:
- 5 Users (Admin, 2 Managers, Accountant, Viewer)
- 50 Leads (разные статусы, источники, UTM)
- 30 Clients (разные национальности: UA, BY, GE, IN, BD, VN)
- 25 Cases (разные workflow-статусы, типы услуг)
- 80 Tasks (assigned to different users)
- 100 Documents (разные типы, некоторые expiring)
- 15 Invoices + 12 Payments
- 20 POS Transactions
- 10 Hearings
- Ads performance data (3 months, 5 platforms)
- SEO data (4 domains, 3 months)
- 50 Reviews (Google, FB, Trustpilot)

#### 5.2 Factories
- LeadFactory, ClientFactory, CaseFactory, TaskFactory, DocumentFactory
- InvoiceFactory, PaymentFactory, PosTransactionFactory
- AdsPerformanceFactory, SeoDataFactory, ReviewFactory

---

## 📋 ПРИОРИТЕТ ВЫПОЛНЕНИЯ

| # | Задача | Время | Блокирует |
|---|--------|-------|-----------|
| 1 | Починить namespace моделей + Case→ClientCase | 1ч | Всё остальное |
| 2 | Дописать пустые миграции (8 таблиц) | 3ч | Модели, Seeders |
| 3 | Дописать 12 стаб-моделей | 3ч | API, Admin |
| 4 | Обновить User модель | 30мин | Auth |
| 5 | Подключить api_routes.php (212 endpoints) | 1ч | Admin ↔ API |
| 6 | Удалить 10 пустых Root-контроллеров | 15мин | Чистота кода |
| 7 | Создать Seeders + Factories | 3ч | Демо |
| 8 | Дописать 9 PLACEHOLDER страниц Admin | 6ч | Полная админка |
| 9 | Доработать 17 PARTIAL страниц (API подключение) | 8ч | Рабочая админка |
| 10 | Настроить Google Cloud (Ads, GA4, GSC, GBP) | 2ч | Marketing данные |
| 11 | Настроить Meta (FB/IG Ads + Social) | 1ч | Marketing данные |
| 12 | Настроить Telegram Bot | 30мин | Notifications |
| 13 | Интеграция Admin → основной проект | 4ч | Production |
| 14 | Подключить Sanctum Auth | 2ч | Security |
| 15 | Подключить платёжные системы (Stripe/P24) | 3ч | Finance модуль |

**Общая оценка:** ~38 часов работы

---

## 🗂 ГДЕ ЧТО ЛЕЖИТ

```
/Herd/wincase/                    ← Основной проект (Laravel API + будущий фронт)
├── app/Models/                   ← 26 моделей (14 готовых + 12 стабов)
├── app/Services/                 ← 51 сервис (все реализованы ✅)
├── app/Http/Controllers/Api/V1/  ← 15 контроллеров (все реализованы ✅)
├── app/Http/Controllers/Api/     ← 10 пустых стабов (удалить)
├── app/Support/ApiEndpointRegistry.php ← Реестр 212 endpoints
├── database/migrations/          ← 32 миграции (16 полных + 10 пустых)
├── routes/api.php                ← 12 строк → пустые стабы (ЗАМЕНИТЬ)
├── routes/api_routes.php         ← 212 endpoints (ПОДКЛЮЧИТЬ)
├── config/polish_tax.php         ← Налоговый конфиг Польши ✅
├── docs/                         ← Полная документация ✅
│
├── Admin/                        ← Admin Panel (отдельное Laravel приложение)
│   ├── resources/views/crm/      ← 8 CRM страниц
│   ├── resources/views/finance/  ← 4 Finance страницы
│   ├── resources/views/marketing/← 5 Marketing страниц
│   ├── resources/views/content/  ← 3 Content страницы
│   ├── resources/views/analytics/← 3 Analytics страницы
│   ├── resources/views/admin/    ← 5 Admin страниц
│   ├── resources/views/auth/     ← 2 Auth страницы
│   └── public/assets/            ← CSS/JS/Images шаблона
```
