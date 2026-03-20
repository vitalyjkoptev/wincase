# WINCASE CRM v4.0 — Auth & Deployment (Phase 14-15)

---

## Phase 14: Auth & RBAC

### 5 Roles × Abilities Matrix

| Ability | Admin | Manager | Operator | Accountant | Viewer |
|---------|:-----:|:-------:|:--------:|:----------:|:------:|
| clients:read/write | ✅ | ✅ | read | read | read |
| cases:read/write | ✅ | ✅ | read | — | read |
| cases:assign | ✅ | ✅ | — | — | — |
| leads:read/write | ✅ | ✅ | ✅ | — | read |
| leads:convert | ✅ | ✅ | — | — | — |
| tasks:read/write | ✅ | ✅ | ✅ | — | — |
| documents:read/write | ✅ | ✅ | ✅ | — | — |
| calendar:read/write | ✅ | ✅ | ✅ | — | — |
| pos:read/write | ✅ | ✅ | ✅ | read | — |
| pos:approve | ✅ | ✅ | — | — | — |
| dashboard:read | ✅ | ✅ | ✅ | ✅ | ✅ |
| accounting:* | ✅ | — | — | ✅ | — |
| social/ads/seo:read | ✅ | ✅ | — | — | — |
| users:* | ✅ | — | — | — | — |

### 2FA (TOTP)
- Google Authenticator compatible
- 30-second window (±1 step tolerance)
- 8 recovery codes (one-time use)

### Security
- Brute-force: 5 attempts → 15 min lock (Redis)
- Password reset: 64-char token, 1 hour TTL
- Single session (old tokens revoked on login)
- Role change → force re-login

### Auth API (18 endpoints)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | /auth/login | No | Email + password + optional 2FA |
| POST | /auth/logout | Yes | Revoke current token |
| GET | /auth/me | Yes | Current user + permissions |
| POST | /auth/2fa/enable | Yes | Get QR code + secret |
| POST | /auth/2fa/confirm | Yes | Verify code, enable 2FA |
| POST | /auth/2fa/disable | Yes | Requires password |
| POST | /auth/password/forgot | No | Send reset link |
| POST | /auth/password/reset | No | Reset with token |
| GET | /users | Admin | List users |
| GET | /users/roles | Admin | Available roles |
| GET | /users/team-stats | Admin | Team statistics |
| GET | /users/:id | Admin | User profile |
| POST | /users | Admin | Create user |
| PUT | /users/:id | Admin | Update user |
| POST | /users/:id/role | Admin | Change role |
| POST | /users/:id/deactivate | Admin | Deactivate |
| POST | /users/:id/activate | Admin | Activate |
| DELETE | /users/:id | Admin | Delete user |

---

## Phase 15: Deployment

### Infrastructure (7 Docker Services)

| Service | Image | Port | Purpose |
|---------|-------|------|---------|
| app | PHP 8.4 FPM Alpine | 9000 | Laravel 12 application |
| nginx | Nginx 1.27 Alpine | 80, 443 | Reverse proxy + SSL |
| mysql | MySQL 8.4 | 3306 | Primary database |
| redis | Redis 7 Alpine | 6379 | Cache + Queue + Sessions |
| queue | PHP 8.4 FPM | — | Laravel queue worker |
| scheduler | PHP 8.4 FPM | — | Cron (every 60s) |
| reverb | PHP 8.4 FPM | 8080 | WebSocket server |
| n8n | n8nio/n8n | 5678 | Workflow automation |

### Nginx Rate Limits

| Zone | Rate | Endpoints |
|------|------|-----------|
| auth | 5/min | /auth/login, /password/* |
| api | 30/s | /api/* (all other) |
| tracking | 60/s | /landings/track, /convert |

### SSL/TLS
- Let's Encrypt certificates
- TLS 1.2 + 1.3
- HSTS (1 year)
- Auto-renew monthly

### CI/CD Pipeline (GitHub Actions)

```
Push to main
     │
     ├── Job 1: Test
     │   ├── PHP 8.4 + MySQL 8.4 + Redis 7
     │   ├── PHPUnit (min 70% coverage)
     │   └── PHPStan level 6
     │
     ├── Job 2: Lint
     │   ├── ESLint
     │   └── TypeScript check
     │
     └── Job 3: Deploy (if main + tests pass)
         ├── SSH → git pull
         ├── composer install --no-dev
         ├── migrate + cache
         ├── npm build
         ├── docker restart
         └── Telegram notification ✅/❌
```

### Security Hardening
- UFW firewall (SSH, 80, 443, 8080)
- Fail2Ban (SSH: 3 attempts, Nginx: 10)
- OPcache JIT enabled (64MB buffer)
- MySQL: InnoDB 512MB buffer, slow query log
- Redis: 256MB maxmemory, allkeys-lru

---

## Files Created (Phase 14-15)

```
auth-module/
├── services/
│   ├── AuthService.php              # Login, 2FA TOTP, password reset
│   └── UsersService.php             # CRUD, roles, team stats
├── controllers/
│   └── AuthUsersControllers.php     # 18 endpoints
├── routes/
│   └── api_auth_routes.php          # 18 routes
└── migrations/
    └── extend_users_table.php       # role, 2FA, status fields

deploy/
├── docker/
│   ├── docker-compose.yml           # 7 services
│   ├── Dockerfile                   # PHP 8.4 FPM Alpine
│   ├── .env.example                 # 80+ env variables
│   ├── php.ini                      # PHP production config
│   ├── opcache.ini                  # JIT enabled
│   └── my.cnf                       # MySQL 8.4 optimized
├── nginx/
│   └── default.conf                 # API + WS + n8n + SSL + rate limits
├── ci/
│   └── deploy.yml                   # GitHub Actions (test → lint → deploy)
└── deploy.sh                        # First-time VPS setup script
```

<!--
Аннотация (RU):
Phase 14 — Auth & RBAC: 5 ролей (admin/manager/operator/accountant/viewer),
2FA TOTP (Google Authenticator), 8 recovery codes, brute-force protection.
18 API endpoints (8 auth + 10 users admin).
Phase 15 — Deployment: Docker Compose (8 сервисов), Nginx (SSL + rate limits),
CI/CD (GitHub Actions: PHPUnit + PHPStan + deploy + Telegram).
deploy.sh — полная настройка VPS (Ubuntu 24.04, Hostinger).
Файл: docs/AUTH_DEPLOY_MODULE.md
-->
