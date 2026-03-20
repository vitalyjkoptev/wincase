# WINCASE CRM v4.0 — Phase 1: Database Layer
## Laravel 12 + PHP 8.4 + MySQL 8.4 LTS

---

## Phase 1 Contents

### 1A — Enums (9 files)
```
app/Enums/
├── LeadSourceEnum.php          # 12 lead sources (paid + organic + offline)
├── LeadStatusEnum.php          # 7 funnel statuses (new → paid / rejected / spam)
├── ServiceTypeEnum.php         # 7 immigration service types
├── AdsPlatformEnum.php         # 5 advertising platforms
├── SocialPlatformEnum.php      # 8 social media platforms
├── ReviewPlatformEnum.php      # 6 review platforms
├── PriorityEnum.php            # 4 priority levels
├── CaseStatusEnum.php          # 5 case statuses
└── BrandListingStatusEnum.php  # 4 brand listing statuses
```

### 1B — Migrations (8 files)
```
database/migrations/
├── 2026_02_16_000001_create_leads_table.php                         # 31 columns, 12 indexes
├── 2026_02_16_000002_create_ads_performance_table.php               # daily campaign metrics
├── 2026_02_16_000003_create_seo_data_table.php                      # GSC/GA4/Ahrefs data
├── 2026_02_16_000004_create_reviews_table.php                       # 6-platform reviews
├── 2026_02_16_000005_create_seo_network_sites_table.php             # 8 satellite sites
├── 2026_02_16_000006_create_brand_listings_table.php                # 50+ NAP listings
├── 2026_02_16_000007_create_landings_table.php                      # 14+ landing pages
└── 2026_02_16_000010_update_social_tables_add_threads_linkedin.php  # ALTER existing tables
```

### 1C — Models (7 files)
```
app/Models/
├── Lead.php              # 9 scopes, 4 accessors, 6 business methods
├── AdsPerformance.php    # 5 scopes, ROAS accessor, static aggregations
├── SeoData.php           # 6 scopes, CTR accessor, overview + trend methods
├── Review.php            # 5 scopes, sentiment accessor, averageByPlatform()
├── SeoNetworkSite.php    # 3 scopes, backlink ratio accessor
├── BrandListing.php      # 4 scopes, NAP check method, consistency report
└── Landing.php           # 3 scopes, conversion rate methods
```

### 1D — Seeders + Factories (4 files)
```
database/seeders/
├── DatabaseSeeder.php         # Updated: includes all v4.0 seeders
├── BrandListingsSeeder.php    # 50+ directory listings for NAP check
├── SeoNetworkSitesSeeder.php  # 8 satellite SEO sites
└── LandingsSeeder.php         # 15 landing pages across 4 domains

database/factories/
└── LeadFactory.php            # Test data with 6 states (newLead, paid, etc.)
```

---

## Installation Commands (macOS Terminal)

```bash
# === Step 1: Copy Enums ===
mkdir -p backend/app/Enums
cp enums/*.php backend/app/Enums/

# === Step 2: Copy Migrations ===
cp migrations/*.php backend/database/migrations/

# === Step 3: Copy Models ===
cp models/*.php backend/app/Models/

# === Step 4: Copy Seeders ===
cp seeders/*.php backend/database/seeders/

# === Step 5: Copy Factories ===
cp factories/*.php backend/database/factories/

# === Step 6: Run Migrations ===
cd backend
php artisan migrate

# === Step 7: Run Seeders ===
php artisan db:seed

# === Step 8: Verify ===
php artisan migrate:status
php artisan tinker --execute="echo App\Models\BrandListing::count().' brand listings'"
php artisan tinker --execute="echo App\Models\SeoNetworkSite::count().' SEO network sites'"
php artisan tinker --execute="echo App\Models\Landing::count().' landing pages'"
```

---

## Database Schema Summary (v4.0)

| Table | Type | Columns | Indexes | Records (seeded) |
|-------|------|---------|---------|-------------------|
| leads | NEW | 31 | 12 | 0 (use factory) |
| ads_performance | NEW | 16 | 4 | 0 (API sync) |
| seo_data | NEW | 13 | 3 | 0 (API sync) |
| reviews | NEW | 10 | 3 | 0 (API sync) |
| seo_network_sites | NEW | 11 | 0 | 8 |
| brand_listings | NEW | 10 | 3 | 50+ |
| landings | NEW | 13 | 3 | 15 |
| social_accounts | ALTER | +2 cols | — | — |
| social_posts | ALTER | +2 cols | — | — |
| content_calendar | ALTER | +1 col | — | — |

**Total:** 7 new tables + 3 ALTER = 10 migration operations

---

## Key Laravel 12 / PHP 8.4 Features Used

1. **Backed Enums (PHP 8.4)** — All 9 enum files use `string` backed enums with methods (label, color, icon, etc.). Stored as VARCHAR in MySQL for maximum compatibility.

2. **casts() Method (Laravel 12)** — New `protected function casts(): array` format instead of `$casts` property. Supports native PHP enum casting.

3. **Laravel Reverb** — WebSocket references updated from Pusher to native Reverb throughout.

4. **Carbon 3** — Required by Laravel 12. All datetime casts compatible.

5. **UUIDv7** — Laravel 12 default. Not used in this project (auto-increment IDs for CRM).

---

## Next: Phase 2 — Leads Module
- LeadController (8 API endpoints)
- LeadService (routing, conversion, funnel)
- LeadRequest (validation with reCAPTCHA v3)
- n8n workflows W01-W03

<!--
Аннотация (RU):
Документация Phase 1 — Database Layer для WINCASE CRM v4.0.
Содержит: полную структуру файлов (9 enums, 8 миграций, 7 моделей,
4 сидера/фабрики), команды установки для macOS, сводку по схеме БД,
описание используемых фишек Laravel 12 / PHP 8.4.
Файл: docs/PHASE_1_DATABASE.md
-->
