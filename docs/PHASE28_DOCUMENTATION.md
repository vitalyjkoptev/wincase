# =====================================================
# PHASE 28: CI/CD + DOCKER + MONITORING
# WinCase CRM v4.0
# =====================================================

## OVERVIEW

Phase 28 delivers production infrastructure: CI/CD pipeline (GitHub Actions),
Docker Compose stack (10 services), monitoring health checks, and management scripts.

## FILE STRUCTURE

```
Phase 28 — 8 files, ~2,500 lines
│
├── .github/workflows/ci-cd.yml           (CI/CD Pipeline — 5 jobs)
├── docker-compose.production.yml          (10 services stack)
├── docker/
│   ├── Dockerfile                         (Multi-stage: base → composer → node → production)
│   ├── entrypoint.sh                      (Migrate + cache on startup)
│   ├── nginx/default.conf                 (3 server blocks + rate limiting + CORS)
│   ├── php/php-custom.ini                 (Upload/memory/timezone)
│   ├── php/opcache.ini                    (JIT 128MB, 20K files)
│   ├── manage.sh                          (16 management commands)
│   └── .dockerignore                      (Build context exclusions)
├── .env.docker                            (Environment template)
├── app/Http/Controllers/Api/V1/
│   └── HealthController.php               (8-check health endpoint)
└── PHASE28_DOCUMENTATION.md
```

## 1. CI/CD PIPELINE (GitHub Actions)

### 5 Jobs

| # | Job | Trigger | Duration | Services |
|---|-----|---------|----------|----------|
| 1 | 🧪 Backend Tests | push/PR | ~3 min | MySQL 8.4, Redis 7 |
| 2 | 🏗️ Frontend Build | push/PR | ~2 min | Node 20 |
| 3 | 📱 Flutter Build | main only | ~5 min | Flutter 3.29 |
| 4 | 🚀 Deploy Staging | staging push | ~1 min | SSH |
| 5 | 🚀 Deploy Production | main push | ~2 min | SSH + Telegram |

### Job 1: Backend Tests
- PHP 8.4 + 15 extensions
- MySQL 8.4 + Redis 7 (service containers)
- PHPUnit parallel + coverage → Codecov
- PHPStan level 5 (static analysis)
- Laravel Pint (code style)

### Job 2: Frontend Build
- Node 20 + npm ci
- ESLint + TypeScript check
- Vite production build
- Upload dist/ as artifact (7 days)

### Job 3: Flutter Build
- Flutter 3.29 stable
- flutter analyze + build APK release
- Upload APK as artifact (30 days)

### Job 4: Deploy Staging
- SSH to staging VPS
- git pull → composer → migrate → cache

### Job 5: Deploy Production
- SSH deploy with maintenance mode
- git pull → composer → npm build → migrate → optimize
- Restart workers + Reverb
- Telegram notification (success/failure)

### GitHub Secrets Required

| Secret | Description |
|--------|-------------|
| PRODUCTION_HOST | VPS 1 IP |
| PRODUCTION_USER | SSH user (root) |
| PRODUCTION_SSH_KEY | SSH private key |
| STAGING_HOST | Staging VPS IP |
| STAGING_USER | SSH user |
| STAGING_SSH_KEY | SSH private key |
| TELEGRAM_BOT_TOKEN | Bot for notifications |
| TELEGRAM_ADMIN_CHAT | Chat ID for alerts |
| CODECOV_TOKEN | Coverage upload |

## 2. DOCKER COMPOSE STACK (10 Services)

```
┌────────────────────────────────────────┐
│          DOCKER COMPOSE STACK          │
│                                        │
│  ┌─────────┐  ┌──────────┐  ┌───────┐│
│  │  nginx   │  │   app    │  │reverb ││
│  │  :80/443 │──│ PHP-FPM  │  │ :8080 ││
│  └─────────┘  │  :9000   │  └───────┘│
│               └──────────┘           │
│  ┌─────────┐  ┌──────────┐  ┌───────┐│
│  │  mysql   │  │  redis   │  │  n8n  ││
│  │  8.4     │  │  7       │  │ :5678 ││
│  └─────────┘  └──────────┘  └───────┘│
│                                        │
│  ┌──────────────────────────┐         │
│  │ worker (×3) │ scheduler  │         │
│  └──────────────────────────┘         │
│                                        │
│  ┌─────────┐  ┌──────────┐           │
│  │ certbot │  │  backup   │           │
│  │ SSL     │  │  daily    │           │
│  └─────────┘  └──────────┘           │
└────────────────────────────────────────┘
```

### Services Detail

| # | Service | Image | Purpose | Health |
|---|---------|-------|---------|--------|
| 1 | app | PHP 8.4-FPM Alpine | Laravel API | php-fpm-healthcheck |
| 2 | nginx | 1.27-alpine | Web + proxy | wget spider |
| 3 | mysql | 8.4 | Database | mysqladmin ping |
| 4 | redis | 7-alpine | Cache/Queue/Session | redis-cli ping |
| 5 | worker | PHP 8.4 (×3) | Queue processing | — |
| 6 | scheduler | PHP 8.4 | Cron (60s loop) | — |
| 7 | reverb | PHP 8.4 | WebSocket | — |
| 8 | n8n | n8nio/n8n:latest | Workflows | — |
| 9 | certbot | certbot:latest | SSL renew (12h) | — |
| 10 | backup | mysql:8.4 | Daily DB dump | — |

### Volumes (8)
mysql-data, redis-data, app-storage, app-bootstrap, n8n-data, certbot-webroot, certbot-certs, backup-data

### Network
Bridge: 172.28.0.0/16

## 3. DOCKERFILE (Multi-stage)

| Stage | Base | Purpose | Size |
|-------|------|---------|------|
| base | php:8.4-fpm-alpine | PHP + 12 extensions | ~120MB |
| composer | base + composer:2 | Install PHP deps | temp |
| node | node:20-alpine | Build Vue admin | temp |
| production | base | Final image + code + dist | ~150MB |

PHP Extensions: pdo_mysql, redis, mbstring, xml, zip, bcmath, intl, gd, opcache, pcntl, sockets

## 4. NGINX CONFIGURATION

### Rate Limiting
| Zone | Rate | Burst | Scope |
|------|------|-------|-------|
| api | 60 req/min | 20 | /api/* |
| auth | 10 req/min | 5 | /api/v1/auth/* |

### Server Blocks
1. **api.wincase.pro** — Laravel PHP-FPM, CORS, WebSocket proxy (/app → reverb:8080)
2. **admin.wincase.pro** — Vue SPA (try_files → index.html)
3. **n8n.wincase.pro** — Reverse proxy → n8n:5678

### Security
- TLS 1.2/1.3, HSTS 1 year
- X-Frame-Options, X-Content-Type-Options, X-XSS-Protection
- CORS: only admin.wincase.pro and wincase.pro origins
- Gzip level 5, static 30-day cache

## 5. HEALTH CHECK API

### GET /api/v1/system/health — 8 Checks

| Check | Metrics |
|-------|---------|
| MySQL | latency_ms, version, connections |
| Redis | latency_ms, version, memory_used, connected_clients, keys |
| Cache | write/read/delete test |
| Queue | driver, jobs_pending, failed_jobs |
| Storage | writable, disk_free, disk_total, usage_percent |
| n8n | reachable, latency_ms |
| Laravel | version, php_version, environment, drivers |
| Server | hostname, uptime, memory (total/used/available), cpu_load |

### GET /api/v1/system/ping — Lightweight
Returns `{"status":"ok"}` for load balancers.

## 6. MANAGEMENT SCRIPT (16 commands)

```bash
bash manage.sh start      # Start all
bash manage.sh stop       # Stop all
bash manage.sh restart    # Restart
bash manage.sh build      # Build images
bash manage.sh deploy     # Git pull + build + rolling restart + migrate
bash manage.sh status     # Container status + health + disk
bash manage.sh logs [svc] # Tail logs
bash manage.sh shell      # App container shell
bash manage.sh artisan .. # Run artisan
bash manage.sh tinker     # Laravel tinker
bash manage.sh mysql      # MySQL CLI
bash manage.sh redis      # Redis CLI
bash manage.sh backup     # DB + Redis + storage backup
bash manage.sh restore f  # Restore from file
bash manage.sh scale N    # Scale workers
bash manage.sh prune      # Clean Docker resources
```

## DEPLOYMENT WORKFLOW

```
Developer → git push main
    ↓
GitHub Actions:
  1. PHPUnit tests (MySQL + Redis)
  2. Vue build (Vite)
  3. Flutter APK (main only)
  4. SSH deploy to VPS
  5. Telegram notification
    ↓
VPS:
  docker compose up -d
  OR
  bash manage.sh deploy
```

## TOTAL PROJECT STATUS

| Metric | Count |
|--------|-------|
| Phases | 28 |
| Files | ~258 |
| Lines of code | ~44,500 |
| API Endpoints | 228+ |
| Database Tables | 48 |
| n8n Workflows | 38 |
| Docker Services | 10 |
| CI/CD Jobs | 5 |
| VPS Servers | 3 |

---
Generated: 2026-02-21 | WinCase CRM v4.0 | WebWave Developers
