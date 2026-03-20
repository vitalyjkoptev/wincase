#!/bin/bash
# =====================================================
# FILE: deploy.sh
# WINCASE CRM — First-time server setup (Hostinger VPS)
# Run: chmod +x deploy.sh && ./deploy.sh
# =====================================================

set -e

echo "🚀 WINCASE CRM v4.0 — Server Setup"
echo "===================================="

# =====================================================
# 1. System packages (Ubuntu 24.04)
# =====================================================
echo "📦 Installing system packages..."
apt update && apt upgrade -y
apt install -y \
    curl git zip unzip nginx certbot python3-certbot-nginx \
    docker.io docker-compose-v2 \
    ufw fail2ban

# =====================================================
# 2. Firewall
# =====================================================
echo "🔒 Configuring firewall..."
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 8080/tcp   # Reverb WebSocket
ufw --force enable

# =====================================================
# 3. Fail2Ban
# =====================================================
echo "🛡️ Configuring Fail2Ban..."
cat > /etc/fail2ban/jail.local << 'EOF'
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5

[sshd]
enabled = true
port = ssh
filter = sshd
maxretry = 3

[nginx-limit-req]
enabled = true
port = http,https
filter = nginx-limit-req
logpath = /var/log/nginx/api.error.log
maxretry = 10
EOF
systemctl enable fail2ban
systemctl start fail2ban

# =====================================================
# 4. Clone repository
# =====================================================
echo "📥 Cloning repository..."
mkdir -p /opt/wincase-crm
cd /opt/wincase-crm
git clone https://github.com/wincase/wincase-crm.git .

# =====================================================
# 5. Environment
# =====================================================
echo "⚙️ Setting up environment..."
cp .env.example .env
echo "⚠️  Edit .env with your production values!"
echo "   nano /opt/wincase-crm/.env"

# =====================================================
# 6. SSL certificates (Let's Encrypt)
# =====================================================
echo "🔐 Setting up SSL..."
certbot certonly --standalone \
    -d api.wincase.pro \
    -d n8n.wincase.pro \
    --non-interactive --agree-tos \
    -m wincasetop@gmail.com

mkdir -p docker/nginx/ssl
cp /etc/letsencrypt/live/api.wincase.pro/fullchain.pem docker/nginx/ssl/wincase.pro.crt
cp /etc/letsencrypt/live/api.wincase.pro/privkey.pem docker/nginx/ssl/wincase.pro.key

# Auto-renew cron
echo "0 0 1 * * certbot renew --quiet && docker compose restart nginx" | crontab -

# =====================================================
# 7. Docker build & start
# =====================================================
echo "🐳 Building and starting containers..."
docker compose build --no-cache
docker compose up -d

# Wait for MySQL
echo "⏳ Waiting for MySQL..."
sleep 15

# =====================================================
# 8. Laravel setup
# =====================================================
echo "🔧 Laravel setup..."
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force --seed
docker compose exec app php artisan storage:link
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Create admin user
docker compose exec app php artisan tinker --execute="
    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'wincasetop@gmail.com',
        'password' => bcrypt('CHANGE_ME_IMMEDIATELY'),
        'role' => 'admin',
        'status' => 'active',
    ]);
"

# =====================================================
# 9. Verify
# =====================================================
echo ""
echo "✅ WINCASE CRM v4.0 deployed!"
echo "===================================="
echo "🌐 API:       https://api.wincase.pro"
echo "🔌 WebSocket: wss://api.wincase.pro/app"
echo "⚡ n8n:       https://n8n.wincase.pro"
echo ""
echo "⚠️  IMPORTANT:"
echo "   1. Edit .env with real credentials"
echo "   2. Change admin password immediately"
echo "   3. Configure n8n workflows"
echo "   4. Setup Firebase for Flutter push"
echo ""
docker compose ps

# ---------------------------------------------------------------
# Аннотация (RU):
# deploy.sh — первоначальная настройка VPS (Hostinger, Ubuntu 24.04).
# 1. Системные пакеты (Docker, Nginx, Certbot, fail2ban).
# 2. Firewall (UFW): SSH, 80, 443, 8080.
# 3. Fail2Ban: SSH (3 попытки), Nginx rate limit (10).
# 4. Clone репозитория.
# 5. SSL (Let's Encrypt) для api.wincase.pro + n8n.wincase.pro.
# 6. Docker build + up (7 сервисов).
# 7. Laravel: migrate, seed, cache, admin user.
# Файл: deploy.sh
# ---------------------------------------------------------------
