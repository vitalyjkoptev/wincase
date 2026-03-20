#!/bin/bash
# =====================================================
# VPS-4 (65.109.108.107) — SEO Satellites + Landings
# 3 WordPress sites
# Run: ssh root@65.109.108.107 'bash -s' < setup-vps4.sh
# =====================================================
set -e

WP_BIN=/usr/local/bin/wp
WP="php -d memory_limit=512M $WP_BIN"

echo "========================================="
echo " VPS-4 Setup — SEO + Landings"
echo " IP: 65.109.108.107"
echo "========================================="

# --- 1. WHM Basic Setup ---
echo "[1/7] Setting WHM hostname..."
whmapi1 sethostname hostname=vps4.wincase.eu 2>/dev/null || true

# --- 2. Install WP-CLI ---
echo "[2/7] Installing WP-CLI..."
if [ ! -f "$WP_BIN" ]; then
    curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    mv wp-cli.phar "$WP_BIN"
fi
echo "WP-CLI: $($WP --version --allow-root 2>/dev/null || echo 'installed')"

# --- 3. Create cPanel accounts ---
echo "[3/7] Creating cPanel accounts..."

declare -a USERS=("wpermit" "biuroimm" "pobytst")
declare -a DOMAINS=("work-permit-poland.com" "biuro-imigracyjne.com" "pobyt-stalowy.com")

for i in "${!USERS[@]}"; do
    USER="${USERS[$i]}"
    DOMAIN="${DOMAINS[$i]}"
    echo "  Creating: $DOMAIN ($USER)"
    whmapi1 createacct domain="$DOMAIN" username="$USER" plan=default contactemail=wincasetop@gmail.com 2>/dev/null || echo "  Account $USER already exists"
done

# --- 4. Install WordPress ---
echo "[4/7] Installing WordPress..."

ADMIN_USER="wincase_admin"
ADMIN_EMAIL="wincasetop@gmail.com"
ADMIN_PASS="WinCase$(openssl rand -hex 8)!"

echo ""
echo "==========================================="
echo "  >>> ADMIN CREDENTIALS — SAVE THIS! <<<"
echo "  User:  $ADMIN_USER"
echo "  Pass:  $ADMIN_PASS"
echo "==========================================="
echo ""

declare -a TITLES=(
    "Work Permit Poland — Your Complete Guide"
    "Biuro Imigracyjne — Legalizacja Pobytu w Polsce"
    "Pobyt Staly w Polsce — Przewodnik"
)

declare -a LOCALES=("en_US" "pl_PL" "pl_PL")

# Write extra-php to temp file
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
    LOCALE="${LOCALES[$i]}"
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
    $WP core download --path="$DOCROOT" --locale="$LOCALE" --force --allow-root
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

    # Install plugins
    $WP plugin install \
        wordpress-seo \
        wp-super-cache \
        redirection \
        classic-editor \
        --activate --path="$DOCROOT" --allow-root

    # Install GeneratePress theme
    $WP theme install generatepress --activate --path="$DOCROOT" --allow-root

    # Settings
    $WP option update blogdescription "$TITLE" --path="$DOCROOT" --allow-root
    $WP option update timezone_string "Europe/Warsaw" --path="$DOCROOT" --allow-root
    $WP option update date_format "Y-m-d" --path="$DOCROOT" --allow-root
    $WP option update posts_per_page 10 --path="$DOCROOT" --allow-root
    $WP option update default_comment_status "closed" --path="$DOCROOT" --allow-root
    $WP option update default_pingback_flag 0 --path="$DOCROOT" --allow-root
    $WP option update default_ping_status "closed" --path="$DOCROOT" --allow-root

    # Create Application Password for n8n autoposting
    APP_PASS=$($WP user application-password create "$ADMIN_USER" "n8n-autopost" --porcelain --path="$DOCROOT" --allow-root 2>/dev/null || echo "CREATE_MANUALLY")
    echo "  App Password: $APP_PASS"

    # Create pages based on language
    if [ "$LOCALE" = "pl_PL" ]; then
        $WP post create --post_type=page --post_title='O nas' --post_status=publish --path="$DOCROOT" --allow-root
        $WP post create --post_type=page --post_title='Kontakt' --post_status=publish --path="$DOCROOT" --allow-root
        $WP post create --post_type=page --post_title='Polityka prywatnosci' --post_status=publish --path="$DOCROOT" --allow-root
    else
        $WP post create --post_type=page --post_title='About' --post_status=publish --path="$DOCROOT" --allow-root
        $WP post create --post_type=page --post_title='Contact' --post_status=publish --path="$DOCROOT" --allow-root
        $WP post create --post_type=page --post_title='Privacy Policy' --post_status=publish --path="$DOCROOT" --allow-root
    fi

    # Fix ownership
    chown -R "$USER":"$USER" "$DOCROOT"

    echo "  DONE: https://$DOMAIN"
done

rm -f "$EXTRA_PHP_FILE"

# --- 5. Create categories ---
echo "[5/7] Creating categories..."

# work-permit-poland.com (EN)
for CAT in "Type A Permit|type-a" "Type B Permit|type-b" "Seasonal Work|seasonal" "EU Blue Card|blue-card" "Documents Required|documents" "Step by Step Guide|step-by-step" "Employer Obligations|employer" "FAQ|faq"; do
    IFS='|' read -r NAME SLUG <<< "$CAT"
    $WP term create category "$NAME" --slug="$SLUG" --path=/home/wpermit/public_html --allow-root 2>/dev/null || true
done

# biuro-imigracyjne.com (PL)
for CAT in "Legalizacja pobytu|legalizacja" "Zezwolenie na prace|zezwolenie" "Karta pobytu|karta-pobytu" "Obywatelstwo|obywatelstwo" "PESEL i meldunek|pesel-meldunek" "Tlumaczenia|tlumaczenia" "Porady prawne|porady" "Cennik uslug|cennik"; do
    IFS='|' read -r NAME SLUG <<< "$CAT"
    $WP term create category "$NAME" --slug="$SLUG" --path=/home/biuroimm/public_html --allow-root 2>/dev/null || true
done

# pobyt-stalowy.com (PL)
for CAT in "Pobyt staly — warunki|warunki" "Dokumenty|dokumenty" "Procedura krok po kroku|procedura" "Odmowa pobytu|odmowa" "Pobyt rezydenta UE|rezydent-ue" "Aktualnosci|aktualnosci"; do
    IFS='|' read -r NAME SLUG <<< "$CAT"
    $WP term create category "$NAME" --slug="$SLUG" --path=/home/pobytst/public_html --allow-root 2>/dev/null || true
done

echo "  Categories created for all 3 sites"

# --- 6. System cron ---
echo "[6/7] Setting up system cron..."
(crontab -l 2>/dev/null | grep -v "wp cron"; cat <<'CRON'
# WordPress Cron — VPS-4
*/5 * * * * /usr/local/bin/wp cron event run --due-now --path=/home/wpermit/public_html --allow-root > /dev/null 2>&1
*/5 * * * * /usr/local/bin/wp cron event run --due-now --path=/home/biuroimm/public_html --allow-root > /dev/null 2>&1
*/5 * * * * /usr/local/bin/wp cron event run --due-now --path=/home/pobytst/public_html --allow-root > /dev/null 2>&1
CRON
) | crontab -

# --- 7. AutoSSL ---
echo "[7/7] Running AutoSSL..."
/usr/local/cpanel/bin/autossl_check --all 2>/dev/null || echo "AutoSSL will run on next schedule"

echo ""
echo "========================================="
echo " VPS-4 SETUP COMPLETE!"
echo "========================================="
echo " Sites:"
echo "   https://work-permit-poland.com"
echo "   https://biuro-imigracyjne.com"
echo "   https://pobyt-stalowy.com"
echo ""
echo " Admin: $ADMIN_USER"
echo " Password: $ADMIN_PASS"
echo "========================================="
