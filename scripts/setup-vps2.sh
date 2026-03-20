#!/bin/bash
# =====================================================
# VPS-2 (65.109.108.82) — News Network
# 3 WordPress news sites
# Run: ssh root@65.109.108.82 'bash -s' < setup-vps2.sh
# =====================================================
set -e

WP=/usr/local/bin/wp

echo "========================================="
echo " VPS-2 Setup — News Network"
echo " IP: 65.109.108.82"
echo "========================================="

# --- 1. WHM Basic Setup ---
echo "[1/7] Setting WHM hostname..."
whmapi1 sethostname hostname=vps2.wincase.eu 2>/dev/null || true

# --- 2. Install WP-CLI ---
echo "[2/7] Installing WP-CLI..."
if [ ! -f "$WP" ]; then
    curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    mv wp-cli.phar "$WP"
fi
echo "WP-CLI: $($WP --version --allow-root 2>/dev/null || echo 'installed')"

# --- 3. Create cPanel accounts ---
echo "[3/7] Creating cPanel accounts..."

declare -a USERS=("ppulse" "euvisa" "wdaily")
declare -a DOMAINS=("polandpulse.com" "eurovisanews.com" "warsawdaily.org")

for i in "${!USERS[@]}"; do
    USER="${USERS[$i]}"
    DOMAIN="${DOMAINS[$i]}"
    echo "  Creating: $DOMAIN ($USER)"
    whmapi1 createacct domain="$DOMAIN" username="$USER" plan=default contactemail=wincasetop@gmail.com 2>/dev/null || echo "  Account $USER already exists"
done

# --- 4. Install WordPress on each site ---
echo "[4/7] Installing WordPress..."

ADMIN_USER="wincase_admin"
ADMIN_EMAIL="wincasetop@gmail.com"
ADMIN_PASS="WinCase$(openssl rand -hex 8)!"

echo ""
echo "==========================================="
echo "  >>> ADMIN CREDENTIALS — SAVE THIS! <<<"
echo "  User:  $ADMIN_USER"
echo "  Pass:  $ADMIN_PASS"
echo "  Email: $ADMIN_EMAIL"
echo "==========================================="
echo ""

declare -a TITLES=(
    "Poland Pulse — European News Portal"
    "EuroVisa News — EU Immigration & Visa News"
    "Warsaw Daily — Warsaw City News"
)

# Write extra-php to temp file (avoids heredoc issues with sudo)
EXTRA_PHP_FILE=$(mktemp)
cat > "$EXTRA_PHP_FILE" <<'PHPEOF'
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', 'minor');
define('WP_POST_REVISIONS', 5);
define('DISABLE_WP_CRON', true);
PHPEOF

for i in "${!USERS[@]}"; do
    USER="${USERS[$i]}"
    DOMAIN="${DOMAINS[$i]}"
    TITLE="${TITLES[$i]}"
    DOCROOT="/home/$USER/public_html"
    DB_NAME="${USER}_wp"
    DB_USER="${USER}_wpu"
    DB_PASS="$(openssl rand -hex 12)"

    echo ""
    echo "  >>> Installing: $DOMAIN <<<"

    # Clean up old DB/user if re-running, then create fresh
    uapi --user="$USER" Mysql delete_user name="$DB_USER" 2>/dev/null || true
    uapi --user="$USER" Mysql delete_database name="$DB_NAME" 2>/dev/null || true
    uapi --user="$USER" Mysql create_database name="$DB_NAME" 2>/dev/null || true
    uapi --user="$USER" Mysql create_user name="$DB_USER" password="$DB_PASS" 2>/dev/null || true
    uapi --user="$USER" Mysql set_privileges_on_database user="$DB_USER" database="$DB_NAME" privileges=ALL 2>/dev/null || true
    echo "  DB: $DB_NAME / $DB_USER created"

    # Download WordPress (as root, fix ownership after)
    $WP core download --path="$DOCROOT" --locale=en_US --force --allow-root
    chown -R "$USER":"$USER" "$DOCROOT"

    # Create wp-config.php
    $WP config create \
        --path="$DOCROOT" \
        --dbname="$DB_NAME" \
        --dbuser="$DB_USER" \
        --dbpass="$DB_PASS" \
        --dbhost=localhost \
        --force \
        --allow-root \
        --extra-php < "$EXTRA_PHP_FILE"
    chown "$USER":"$USER" "$DOCROOT/wp-config.php"

    # Install WordPress
    $WP core install \
        --path="$DOCROOT" \
        --url="https://$DOMAIN" \
        --title="$TITLE" \
        --admin_user="$ADMIN_USER" \
        --admin_password="$ADMIN_PASS" \
        --admin_email="$ADMIN_EMAIL" \
        --skip-email \
        --allow-root

    # Permalinks
    $WP rewrite structure '/%postname%/' --path="$DOCROOT" --allow-root

    # Remove default plugins/themes
    $WP plugin delete hello akismet --path="$DOCROOT" --allow-root 2>/dev/null || true
    $WP theme delete twentytwentythree twentytwentyfour --path="$DOCROOT" --allow-root 2>/dev/null || true

    # Install essential plugins
    $WP plugin install \
        wordpress-seo \
        wp-super-cache \
        wordfence \
        redirection \
        classic-editor \
        duplicate-post \
        --activate --path="$DOCROOT" --allow-root

    # Install GeneratePress theme
    $WP theme install generatepress --activate --path="$DOCROOT" --allow-root

    # Settings
    $WP option update blogdescription "$TITLE" --path="$DOCROOT" --allow-root
    $WP option update timezone_string "Europe/Warsaw" --path="$DOCROOT" --allow-root
    $WP option update date_format "Y-m-d" --path="$DOCROOT" --allow-root
    $WP option update posts_per_page 12 --path="$DOCROOT" --allow-root
    $WP option update default_comment_status "closed" --path="$DOCROOT" --allow-root
    $WP option update default_pingback_flag 0 --path="$DOCROOT" --allow-root
    $WP option update default_ping_status "closed" --path="$DOCROOT" --allow-root

    # Create Application Password for n8n autoposting
    APP_PASS=$($WP user application-password create "$ADMIN_USER" "n8n-autopost" --porcelain --path="$DOCROOT" --allow-root 2>/dev/null || echo "CREATE_MANUALLY")
    echo "  App Password for $DOMAIN: $APP_PASS"

    # Create standard pages
    $WP post create --post_type=page --post_title='About' --post_status=publish --path="$DOCROOT" --allow-root
    $WP post create --post_type=page --post_title='Contact' --post_status=publish --path="$DOCROOT" --allow-root
    $WP post create --post_type=page --post_title='Privacy Policy' --post_status=publish --path="$DOCROOT" --allow-root

    # Fix ownership of everything
    chown -R "$USER":"$USER" "$DOCROOT"

    echo "  DONE: https://$DOMAIN"
done

rm -f "$EXTRA_PHP_FILE"

# --- 5. Create categories ---
echo "[5/7] Creating categories..."

# polandpulse.com
for CAT in "Culture|culture" "Europe|europe" "Visa & Immigration|visa-immigration" "Business|business" "Trends|trends" "iGaming|igaming" "Tech News|tech-news" "Sports|sports" "Europass & Education|europass" "Live TV & Media|live-tv"; do
    IFS='|' read -r NAME SLUG <<< "$CAT"
    $WP term create category "$NAME" --slug="$SLUG" --path=/home/ppulse/public_html --allow-root 2>/dev/null || true
done

# eurovisanews.com
for CAT in "Visa Updates|visa-updates" "Immigration Policy|immigration-policy" "Work Permits|work-permits" "Residence Cards|residence-cards" "EU Regulations|eu-regulations" "Country Guides|country-guides" "Asylum & Refugees|asylum" "Citizenship|citizenship"; do
    IFS='|' read -r NAME SLUG <<< "$CAT"
    $WP term create category "$NAME" --slug="$SLUG" --path=/home/euvisa/public_html --allow-root 2>/dev/null || true
done

# warsawdaily.org
for CAT in "City News|city-news" "Events|events" "Business|business" "Culture & Art|culture-art" "Food & Restaurants|food" "Expat Life|expat-life" "Transport|transport" "Real Estate|real-estate"; do
    IFS='|' read -r NAME SLUG <<< "$CAT"
    $WP term create category "$NAME" --slug="$SLUG" --path=/home/wdaily/public_html --allow-root 2>/dev/null || true
done

echo "  Categories created for all 3 sites"

# --- 6. Setup WP-Cron via system cron ---
echo "[6/7] Setting up system cron..."
(crontab -l 2>/dev/null | grep -v "wp cron"; cat <<'CRON'
# WordPress Cron — VPS-2
*/5 * * * * /usr/local/bin/wp cron event run --due-now --path=/home/ppulse/public_html --allow-root > /dev/null 2>&1
*/5 * * * * /usr/local/bin/wp cron event run --due-now --path=/home/euvisa/public_html --allow-root > /dev/null 2>&1
*/5 * * * * /usr/local/bin/wp cron event run --due-now --path=/home/wdaily/public_html --allow-root > /dev/null 2>&1
CRON
) | crontab -

# --- 7. AutoSSL ---
echo "[7/7] Running AutoSSL..."
/usr/local/cpanel/bin/autossl_check --all 2>/dev/null || echo "AutoSSL will run on next schedule"

echo ""
echo "========================================="
echo " VPS-2 SETUP COMPLETE!"
echo "========================================="
echo " Sites:"
echo "   https://polandpulse.com"
echo "   https://eurovisanews.com"
echo "   https://warsawdaily.org"
echo ""
echo " Admin: $ADMIN_USER"
echo " Password: $ADMIN_PASS"
echo "========================================="
