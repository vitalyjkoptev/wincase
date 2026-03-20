# WINCASE CRM v4.0 — TECHNICAL SPECIFICATION (TZ)

## PART 4 of 6: Database Schema — 21 Tables (14 Existing + 7 New)

---

## 11. DATABASE OVERVIEW

**Engine:** MySQL 8.4 LTS
**Charset:** utf8mb4_unicode_ci
**Total Tables:** 21 (14 existing + 7 new in v4.0)
**Soft Deletes:** enabled on all main entities

### 11.1 Existing Tables (14)

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
| 11 | social_accounts | Connected social accounts | — |
| 12 | social_posts | Published posts | belongsTo: social_account |
| 13 | social_analytics | Per-post analytics | belongsTo: social_post |
| 14 | content_calendar | Content planning | — |

### 11.2 New Tables v4.0 (7)

| # | Table | Columns | Description |
|---|-------|---------|-------------|
| 15 | leads | 31 | All leads from 14 sources |
| 16 | ads_performance | 16 | Daily ads metrics per campaign |
| 17 | seo_data | 14 | GSC/GA4/Ahrefs data per domain |
| 18 | reviews | 11 | Reviews from all platforms |
| 19 | seo_network_sites | 11 | 8 satellite SEO sites |
| 20 | brand_listings | 10 | 50+ directory listings NAP |
| 21 | landings | ~12 | 14+ landing pages tracking |

---

## 12. NEW TABLE SCHEMAS

### 12.1 Table: leads (31 columns)

```sql
CREATE TABLE leads (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Contact Info
    name            VARCHAR(100) NOT NULL,
    phone           VARCHAR(30) NOT NULL,
    email           VARCHAR(100) NULL,
    
    -- Service & Message
    service_type    ENUM('karta_pobytu','citizenship','work_permit',
                         'temporary_protection','business','job_centre','other')
                    DEFAULT 'other',
    message         TEXT NULL,
    
    -- Source Tracking
    source          ENUM('google_ads','facebook_ads','tiktok_ads','pinterest_ads',
                         'youtube_ads','threads','organic','telegram',
                         'whatsapp','referral','walk_in','phone')
                    NOT NULL,
    utm_source      VARCHAR(200) NULL,
    utm_medium      VARCHAR(200) NULL,
    utm_campaign    VARCHAR(200) NULL,
    utm_term        VARCHAR(200) NULL,
    utm_content     VARCHAR(200) NULL,
    gclid           VARCHAR(200) NULL,      -- Google Click ID
    fbclid          VARCHAR(200) NULL,      -- Facebook Click ID
    ttclid          VARCHAR(200) NULL,      -- TikTok Click ID
    landing_page    VARCHAR(500) NULL,      -- URL from any of 4 domains
    
    -- Visitor Info
    language        VARCHAR(5) DEFAULT 'en',  -- ru, ua, en, hi, tl, es, tr, pl
    device          ENUM('mobile','desktop','tablet') NULL,
    ip_address      VARCHAR(45) NULL,
    country         VARCHAR(2) NULL,
    city            VARCHAR(100) NULL,
    
    -- CRM Status
    status          ENUM('new','contacted','consultation','contract',
                         'paid','rejected','spam')
                    DEFAULT 'new',
    assigned_to     BIGINT UNSIGNED NULL,    -- FK → users.id
    priority        ENUM('low','medium','high','urgent') DEFAULT 'medium',
    notes           TEXT NULL,
    
    -- Conversion Timestamps
    first_contact_at    TIMESTAMP NULL,
    consultation_at     TIMESTAMP NULL,
    converted_at        TIMESTAMP NULL,
    
    -- Link to CRM
    client_id       BIGINT UNSIGNED NULL,    -- FK → clients.id
    case_id         BIGINT UNSIGNED NULL,    -- FK → cases.id
    
    -- GDPR
    gdpr_consent    BOOLEAN DEFAULT FALSE,
    gdpr_consent_at TIMESTAMP NULL,
    
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    deleted_at      TIMESTAMP NULL,          -- Soft Delete
    
    -- Foreign Keys
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_status (status),
    INDEX idx_source (source),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_language (language),
    INDEX idx_created_at (created_at),
    INDEX idx_gclid (gclid),
    INDEX idx_fbclid (fbclid),
    INDEX idx_ttclid (ttclid),
    INDEX idx_client_id (client_id),
    INDEX idx_priority (priority),
    INDEX idx_status_created (status, created_at),
    INDEX idx_source_created (source, created_at)
);
```

### 12.2 Table: ads_performance

```sql
CREATE TABLE ads_performance (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    platform            ENUM('google_ads','meta_ads','tiktok_ads',
                              'pinterest_ads','youtube_ads') NOT NULL,
    campaign_id         VARCHAR(100) NOT NULL,
    campaign_name       VARCHAR(200) NOT NULL,
    date                DATE NOT NULL,
    impressions         INT UNSIGNED DEFAULT 0,
    clicks              INT UNSIGNED DEFAULT 0,
    cost                DECIMAL(10,2) DEFAULT 0.00,
    conversions         INT UNSIGNED DEFAULT 0,
    conversion_value    DECIMAL(10,2) DEFAULT 0.00,
    cpc                 DECIMAL(8,4) DEFAULT 0.0000,
    cpl                 DECIMAL(8,4) DEFAULT 0.0000,
    ctr                 DECIMAL(5,4) DEFAULT 0.0000,
    leads_count         INT UNSIGNED DEFAULT 0,
    status              ENUM('active','paused','completed') DEFAULT 'active',
    raw_data            JSON NULL,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    
    UNIQUE KEY unique_campaign_date (platform, campaign_id, date),
    INDEX idx_platform (platform),
    INDEX idx_date (date),
    INDEX idx_platform_date (platform, date)
);
```

### 12.3 Table: seo_data

```sql
CREATE TABLE seo_data (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    domain              VARCHAR(100) NOT NULL,    -- Any of 4 domains or SEO site
    date                DATE NOT NULL,
    source              ENUM('gsc','ga4','ahrefs','moz') NOT NULL,
    clicks              INT UNSIGNED DEFAULT 0,
    impressions         INT UNSIGNED DEFAULT 0,
    avg_position        DECIMAL(6,2) NULL,
    users               INT UNSIGNED DEFAULT 0,
    sessions            INT UNSIGNED DEFAULT 0,
    conversions         INT UNSIGNED DEFAULT 0,
    domain_authority    SMALLINT UNSIGNED NULL,
    backlinks           INT UNSIGNED DEFAULT 0,
    referring_domains   INT UNSIGNED DEFAULT 0,
    raw_data            JSON NULL,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    
    UNIQUE KEY unique_domain_date_source (domain, date, source),
    INDEX idx_domain (domain),
    INDEX idx_date (date)
);
```

### 12.4 Table: reviews

```sql
CREATE TABLE reviews (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    platform            ENUM('google','trustpilot','facebook',
                              'gowork','clutch','provenexpert') NOT NULL,
    author_name         VARCHAR(200) NULL,
    rating              TINYINT UNSIGNED NULL,
    text                TEXT NULL,
    reply               TEXT NULL,
    replied_at          TIMESTAMP NULL,
    published_at        TIMESTAMP NULL,
    language            VARCHAR(5) NULL,
    platform_review_id  VARCHAR(200) NULL,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    
    INDEX idx_platform (platform),
    INDEX idx_rating (rating),
    INDEX idx_published_at (published_at)
);
```

### 12.5 Table: seo_network_sites

```sql
CREATE TABLE seo_network_sites (
    id                      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    domain                  VARCHAR(200) NOT NULL,
    name                    VARCHAR(200) NOT NULL,
    language                VARCHAR(5) NOT NULL,
    cms                     VARCHAR(50) NULL,
    hosting                 VARCHAR(100) NULL,
    domain_authority        SMALLINT UNSIGNED DEFAULT 0,
    articles_total          INT UNSIGNED DEFAULT 0,
    articles_with_backlink  INT UNSIGNED DEFAULT 0,
    last_article_at         TIMESTAMP NULL,
    status                  ENUM('active','inactive','pending') DEFAULT 'active',
    created_at              TIMESTAMP NULL,
    updated_at              TIMESTAMP NULL
);
```

### 12.6 Table: brand_listings

```sql
CREATE TABLE brand_listings (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    platform            VARCHAR(200) NOT NULL,
    url                 VARCHAR(500) NULL,
    nap_name            VARCHAR(200) NULL,
    nap_address         VARCHAR(500) NULL,
    nap_phone           VARCHAR(30) NULL,
    nap_consistent      BOOLEAN DEFAULT FALSE,
    status              ENUM('listed','pending','not_listed','error') DEFAULT 'pending',
    last_checked_at     TIMESTAMP NULL,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    
    INDEX idx_status (status),
    INDEX idx_nap_consistent (nap_consistent)
);
```

### 12.7 Updates to Existing Tables

| Table | Change |
|-------|--------|
| social_accounts.platform | Add ENUM values: 'threads', 'linkedin' |
| social_posts.platform | Add ENUM values: 'threads', 'linkedin' |
| social_analytics.platform | Add ENUM values: 'threads', 'linkedin' |
| social_posts | Add column: threads_id VARCHAR(100) NULL |
| content_calendar | Add column: platforms JSON (array of platforms including threads) |

---

<!-- Аннотация (RU):
ЧАСТЬ 4 из 6 технического задания WINCASE CRM v4.0.
Содержит полную схему базы данных: 14 существующих таблиц (обзор) + 7 новых таблиц с полным SQL.
Таблица leads — 31 колонка: контактные данные, тип услуги, 12 источников, UTM (5 полей),
click IDs (gclid/fbclid/ttclid), воронка (7 статусов), связь с CRM (client_id, case_id), GDPR.
Таблица ads_performance — дневная гранулярность метрик по кампаниям (5 платформ).
Таблица seo_data — GSC/GA4/Ahrefs данные по 4 доменам.
Таблицы reviews, seo_network_sites, brand_listings — отзывы, SEO-сеть, NAP каталоги.
Обновления существующих таблиц: добавление Threads и LinkedIn в social_*.
Следующая часть: API Endpoints (30+).
-->
