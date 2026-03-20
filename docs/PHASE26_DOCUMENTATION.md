# =====================================================
# PHASE 26: FULL DEPLOYMENT — VPS + 7 NEWS SITES
# WinCase CRM v4.0
# =====================================================

## 3 SCRIPTS

### 1. deploy.sh — Main Deploy (VPS 1)

**Run:** `sudo bash deploy.sh`
**Time:** ~15 minutes
**Server:** Hostinger VPS 1 (Ubuntu 24.04)

| Step | Action | Details |
|------|--------|---------|
| 1 | System packages | curl, git, ufw, fail2ban, certbot, supervisor |
| 2 | Nginx | Web server |
| 3 | PHP 8.4-FPM | Pool: 30 workers, 512MB, OPcache 256MB |
| 4 | MySQL 8.4 | DB: wincase_crm, InnoDB buffer 1GB |
| 5 | Redis 7 | 512MB, allkeys-lru, password auth |
| 6 | Node.js 20 | For Vue admin build |
| 7 | Composer | PHP dependency manager |
| 8 | Application | git clone, composer install, npm build |
| 9 | Environment | .env with 80+ variables |
| 10 | Laravel | migrate, seed, config/route/view cache |
| 11 | Nginx vhosts | api.wincase.pro, admin.wincase.pro, wincase.pro |
| 12 | SSL | Let's Encrypt (4 domains) |
| 13 | Supervisor | 3 queue workers + scheduler + Reverb |
| 14 | Cron | Scheduler, daily backup 2AM, cleanup 30d |
| 15 | n8n | Docker container, nginx proxy, SSL |
| 16 | Firewall | UFW: 22, 80, 443 only |
| 17 | Fail2Ban | SSH 3 tries, Nginx 5 tries |
| 18 | Log rotation | Daily, 30 days retention |
| 19 | WebSocket | Laravel Reverb on port 8080 |
| 20 | Health check | All services + credentials output |

### 2. update.sh — Quick Redeploy

**Run:** `sudo bash update.sh`
**Time:** ~60 seconds
**Downtime:** Minimal (maintenance mode)

Steps: maintenance ON → git pull → composer → npm build → migrate → cache → restart workers → UP

### 3. setup-news-sites.sh — 7 WordPress Sites (VPS 2+3)

**Run:** `sudo bash setup-news-sites.sh`
**Time:** ~10 minutes

For each of 7 sites:
1. MySQL database + user
2. WordPress download + install (WP-CLI)
3. wp-config.php (debug off, cache on, file edit off)
4. Permalink: /%postname%/
5. Theme install
6. Plugins: Yoast SEO, Cache, Security
7. Categories (8-10 per site, 65 total)
8. REST API Application Password
9. Nginx vhost + security headers + gzip
10. SSL Let's Encrypt

## SERVER ARCHITECTURE

```
┌─────────────────────────────────────────────┐
│  HOSTINGER VPS 1 — wincase.pro              │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐    │
│  │ Laravel  │ │ Vue SPA  │ │   n8n    │    │
│  │ API      │ │ Admin    │ │ Docker   │    │
│  │ :80/443  │ │ :80/443  │ │ :5678    │    │
│  └──────────┘ └──────────┘ └──────────┘    │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐    │
│  │ MySQL    │ │ Redis    │ │ Reverb   │    │
│  │ 8.4      │ │ 7        │ │ WS:8080  │    │
│  └──────────┘ └──────────┘ └──────────┘    │
│  Supervisor: 3 workers + scheduler          │
│  Domains: api/admin/wincase.pro, n8n        │
│  + kancelaria.pro, workinpoland.pl,         │
│    pozwolenie.pl                            │
├─────────────────────────────────────────────┤
│  HOSTINGER VPS 2 — News Sites (Group 1)     │
│  ┌────────────────┐ ┌──────────────────┐    │
│  │ polandpulse    │ │ eurogamingpost   │    │
│  │ .news          │ │ .com             │    │
│  └────────────────┘ └──────────────────┘    │
│  ┌────────────────┐                         │
│  │ techpulse      │                         │
│  │ .news          │                         │
│  └────────────────┘                         │
│  WordPress + MySQL + PHP-FPM + Nginx        │
├─────────────────────────────────────────────┤
│  HOSTINGER VPS 3 — News Sites (Group 2)     │
│  ┌────────────────┐ ┌──────────────────┐    │
│  │ bizeurope      │ │ sportpulse       │    │
│  │ .news          │ │ .news            │    │
│  └────────────────┘ └──────────────────┘    │
│  ┌────────────────┐ ┌──────────────────┐    │
│  │ diaspora       │ │ trendwatch       │    │
│  │ .news          │ │ .news            │    │
│  └────────────────┘ └──────────────────┘    │
│  WordPress + MySQL + PHP-FPM + Nginx        │
└─────────────────────────────────────────────┘
```

## DEPLOYMENT ORDER

```bash
# Step 1: Deploy CRM (VPS 1)
ssh root@vps1-ip
bash deploy.sh

# Step 2: Setup News Sites (VPS 2)
ssh root@vps2-ip
bash setup-news-sites.sh   # polandpulse, eurogaming, techpulse

# Step 3: Setup News Sites (VPS 3)
ssh root@vps3-ip
bash setup-news-sites.sh   # bizeurope, sportpulse, diaspora, trendwatch

# Step 4: Configure .env on VPS 1 with WP credentials from VPS 2+3
nano /var/www/wincase/.env
# Add: POLANDPULSE_WP_USER, POLANDPULSE_WP_PASSWORD, etc.

# Step 5: Import n8n workflows (38 total)
# Open https://n8n.wincase.pro → Import → upload workflow JSONs

# Step 6: Verify
curl https://api.wincase.pro/api/v1/system/health
```

## CREDENTIALS LOCATIONS

| File | Content |
|------|---------|
| `/var/www/wincase/.env` | All API keys, DB, Redis |
| `/root/wincase-credentials/news-sites.txt` | WP logins, DB, REST API keys |
| Console output from deploy.sh | MySQL + Redis passwords |

## DAILY OPERATIONS

| Time | Action | Script |
|------|--------|--------|
| Every minute | Laravel scheduler | cron + supervisor |
| Every 5 min | News: parse critical + publish | n8n W28 + W33 |
| Every 10 min | News: AI rewrite batch | n8n W32 |
| Every 15 min | SLA breach check | scheduler |
| 2:00 AM | MySQL backup | cron |
| 3:00 AM | Old logs cleanup | scheduler |
| 3:00 AM | SSL renew check | certbot |
| 20:00 | News daily digest | n8n W35 |
| 20:30 | Ticket daily digest | n8n W38 |

## TOTAL PROJECT

| Metric | Count |
|--------|-------|
| Phases | 26 |
| Files | ~245 |
| Lines of code | ~40,000 |
| API Endpoints | 226+ |
| Database Tables | 48 |
| n8n Workflows | 38 |
| VPS Servers | 3 |
| Domains | 14 |
| WordPress Sites | 7 |
| SSL Certificates | 14+ |

---
Generated: 2026-02-21 | WinCase CRM v4.0 | WebWave Developers
