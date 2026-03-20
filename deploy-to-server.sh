#!/bin/bash
# =====================================================
# WINCASE CRM — Deploy via SCP to VPS-1 (65.109.108.79)
# Usage: bash deploy-to-server.sh
# =====================================================

set -e

SERVER="wincase-root"
REMOTE_PATH="/home/wincase/admin-app"
LOCAL_PATH="/Users/webwavedeveloper/Herd/wincase"

echo "=== WINCASE CRM Deploy ==="
echo "Server: $SERVER:$REMOTE_PATH"
echo ""

# =====================================================
# 1. PHP Backend (app/, config/, bootstrap/)
# =====================================================
echo "[1/7] Uploading app/ ..."
scp -r "$LOCAL_PATH/app/" "$SERVER:$REMOTE_PATH/app/"

echo "[2/7] Uploading config/ ..."
scp -r "$LOCAL_PATH/config/" "$SERVER:$REMOTE_PATH/config/"

echo "[3/7] Uploading bootstrap/app.php ..."
scp "$LOCAL_PATH/bootstrap/app.php" "$SERVER:$REMOTE_PATH/bootstrap/app.php"

# =====================================================
# 2. Routes
# =====================================================
echo "[4/7] Uploading routes/ ..."
scp "$LOCAL_PATH/routes/web.php" "$SERVER:$REMOTE_PATH/routes/web.php"
scp "$LOCAL_PATH/routes/api_routes.php" "$SERVER:$REMOTE_PATH/routes/api_routes.php"
[ -f "$LOCAL_PATH/routes/api_staff_routes.php" ] && scp "$LOCAL_PATH/routes/api_staff_routes.php" "$SERVER:$REMOTE_PATH/routes/api_staff_routes.php"

# =====================================================
# 3. Database (migrations + seeders)
# =====================================================
echo "[5/7] Uploading database/ ..."
scp -r "$LOCAL_PATH/database/migrations/" "$SERVER:$REMOTE_PATH/database/migrations/"
scp -r "$LOCAL_PATH/database/seeders/" "$SERVER:$REMOTE_PATH/database/seeders/"

# =====================================================
# 4. Views (blade templates)
# =====================================================
echo "[6/7] Uploading views/ ..."
scp -r "$LOCAL_PATH/resources/views/" "$SERVER:$REMOTE_PATH/resources/views/"

# =====================================================
# 5. Public assets (JS/CSS)
# =====================================================
echo "[7/7] Uploading public assets ..."
scp "$LOCAL_PATH/public/assets/js/crm-api.js" "$SERVER:$REMOTE_PATH/public/assets/js/crm-api.js" 2>/dev/null || true
scp -r "$LOCAL_PATH/public/assets/css/" "$SERVER:$REMOTE_PATH/public/assets/css/"
scp -r "$LOCAL_PATH/public/assets/images/" "$SERVER:$REMOTE_PATH/public/assets/images/" 2>/dev/null || true
scp -r "$LOCAL_PATH/public/assets/js/" "$SERVER:$REMOTE_PATH/public/assets/js/" 2>/dev/null || true

echo ""
echo "=== Files uploaded! ==="
echo ""
echo "Now SSH to server and run:"
echo ""
echo "  ssh $SERVER"
echo "  cd $REMOTE_PATH"
echo ""
echo "  # 1. Run alignment migration (adds columns + 17 new tables)"
echo "  php artisan migrate --force"
echo ""
echo "  # 2. Clear all caches"
echo "  php artisan config:cache && php artisan route:cache && php artisan view:cache"
echo ""
echo "  # 3. Restart Apache + PHP-FPM (OPcache)"
echo "  /usr/local/cpanel/scripts/restartsrv_httpd && /usr/local/cpanel/scripts/restartsrv_apache_php_fpm"
echo ""
echo "Migration will:"
echo "  - Add missing columns to leads, cases, clients, invoices, tasks, users"
echo "  - Sync: name from first_name+last_name, service_type from type, total_amount from total"
echo "  - Create 17 new tables (ads, seo, social, pos, tax, etc.)"
echo "  - Fix roles: admin/director -> boss"
echo ""
