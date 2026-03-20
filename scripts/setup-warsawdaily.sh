#!/bin/bash
# =====================================================
# Warsaw Daily (warsawdaily.org) — Full Setup
# VPS-2 (65.109.108.82), cPanel account: wdaily
# Run: ssh root@65.109.108.82 'bash -s' < scripts/setup-warsawdaily.sh
# =====================================================
set -e

WP=/usr/local/bin/wp
DOCROOT="/home/wdaily/public_html"
USER="wdaily"
DOMAIN="warsawdaily.org"

echo "========================================="
echo " Warsaw Daily — Setup & Template Install"
echo " Domain: $DOMAIN"
echo " VPS-2: 65.109.108.82"
echo "========================================="

# --- 1. Fix categories (wp_id mapping) ---
echo "[1/8] Fixing categories with correct wp_id..."

# Delete default 'Uncategorized' category
$WP term delete category 1 --path="$DOCROOT" --allow-root 2>/dev/null || true

# Create categories with specific IDs matching NewsSitesRegistry
declare -A CATS=(
    ["City News"]="city-news"
    ["Events"]="events"
    ["Business"]="business"
    ["Culture & Art"]="culture-art"
    ["Food & Restaurants"]="food"
    ["Expat Life"]="expat-life"
    ["Transport"]="transport"
    ["Real Estate"]="real-estate"
)

for NAME in "${!CATS[@]}"; do
    SLUG="${CATS[$NAME]}"
    $WP term create category "$NAME" --slug="$SLUG" --path="$DOCROOT" --allow-root 2>/dev/null || echo "  Category '$NAME' already exists"
done

# Get actual wp_ids and display mapping
echo ""
echo "  Category ID Mapping:"
for SLUG in city-news events business culture-art food expat-life transport real-estate; do
    ID=$($WP term get category --by=slug "$SLUG" --field=term_id --path="$DOCROOT" --allow-root 2>/dev/null || echo "?")
    echo "    $SLUG => wp_id: $ID"
done
echo ""

# --- 2. Install Elementor ---
echo "[2/8] Installing Elementor..."
$WP plugin install elementor --activate --path="$DOCROOT" --allow-root 2>/dev/null || echo "  Elementor already installed"

# --- 3. Install required plugins for NeoNews template ---
echo "[3/8] Installing required plugins..."
$WP plugin install elementskit-lite --activate --path="$DOCROOT" --allow-root 2>/dev/null || true
$WP plugin install skyboot-custom-icons-for-elementor --activate --path="$DOCROOT" --allow-root 2>/dev/null || true

# Install Hello Elementor theme (best for Elementor templates)
echo "[4/8] Installing Hello Elementor theme..."
$WP theme install hello-elementor --activate --path="$DOCROOT" --allow-root 2>/dev/null || true

# --- 5. Install additional useful plugins ---
echo "[5/8] Installing additional plugins..."
$WP plugin install \
    wordpress-seo \
    wp-super-cache \
    classic-editor \
    duplicate-post \
    wp-mail-smtp \
    --activate --path="$DOCROOT" --allow-root 2>/dev/null || true

# --- 6. WordPress Settings ---
echo "[6/8] Configuring WordPress settings..."
$WP option update blogname "Warsaw Daily" --path="$DOCROOT" --allow-root
$WP option update blogdescription "Warsaw City News — Events, Culture, Expat Life" --path="$DOCROOT" --allow-root
$WP option update timezone_string "Europe/Warsaw" --path="$DOCROOT" --allow-root
$WP option update date_format "F j, Y" --path="$DOCROOT" --allow-root
$WP option update time_format "H:i" --path="$DOCROOT" --allow-root
$WP option update posts_per_page 12 --path="$DOCROOT" --allow-root
$WP option update default_comment_status "closed" --path="$DOCROOT" --allow-root
$WP option update default_pingback_flag 0 --path="$DOCROOT" --allow-root
$WP option update default_ping_status "closed" --path="$DOCROOT" --allow-root
$WP option update show_on_front "page" --path="$DOCROOT" --allow-root

# Create static pages
echo "[7/8] Creating pages..."
HP_ID=$($WP post create --post_type=page --post_title='Home' --post_status=publish --porcelain --path="$DOCROOT" --allow-root)
$WP option update page_on_front "$HP_ID" --path="$DOCROOT" --allow-root

BLOG_ID=$($WP post create --post_type=page --post_title='News' --post_status=publish --porcelain --path="$DOCROOT" --allow-root)
$WP option update page_for_posts "$BLOG_ID" --path="$DOCROOT" --allow-root

$WP post create --post_type=page --post_title='About Us' --post_status=publish --path="$DOCROOT" --allow-root
$WP post create --post_type=page --post_title='Contact' --post_status=publish --path="$DOCROOT" --allow-root
$WP post create --post_type=page --post_title='Privacy Policy' --post_status=publish --path="$DOCROOT" --allow-root

# Create menu
$WP menu create "Main Menu" --path="$DOCROOT" --allow-root 2>/dev/null || true
$WP menu location assign "Main Menu" primary --path="$DOCROOT" --allow-root 2>/dev/null || true

# Add menu items
$WP menu item add-custom "Main Menu" "Home" "https://$DOMAIN/" --path="$DOCROOT" --allow-root 2>/dev/null || true

for CAT_SLUG in city-news events business culture-art food expat-life transport real-estate; do
    CAT_ID=$($WP term get category --by=slug "$CAT_SLUG" --field=term_id --path="$DOCROOT" --allow-root 2>/dev/null || echo "")
    if [ -n "$CAT_ID" ]; then
        CAT_NAME=$($WP term get category "$CAT_ID" --field=name --path="$DOCROOT" --allow-root 2>/dev/null)
        $WP menu item add-custom "Main Menu" "$CAT_NAME" "https://$DOMAIN/category/$CAT_SLUG/" --path="$DOCROOT" --allow-root 2>/dev/null || true
    fi
done

$WP menu item add-custom "Main Menu" "Contact" "https://$DOMAIN/contact/" --path="$DOCROOT" --allow-root 2>/dev/null || true

# --- 8. Create Application Password for n8n autoposting ---
echo "[8/8] Creating Application Password..."
APP_PASS=$($WP user application-password create wincase_admin "n8n-autopost" --porcelain --path="$DOCROOT" --allow-root 2>/dev/null || echo "ALREADY_EXISTS")
echo ""
echo "==========================================="
echo "  >>> WARSAWDAILY.ORG SETUP COMPLETE! <<<"
echo "==========================================="
echo "  URL:     https://$DOMAIN"
echo "  Admin:   https://$DOMAIN/wp-admin/"
echo "  API:     https://$DOMAIN/wp-json/wp/v2/"
echo ""
echo "  App Password for n8n: $APP_PASS"
echo "  (Add to .env as WARSAWDAILY_WP_PASSWORD)"
echo ""
echo "  NEXT STEPS:"
echo "  1. Upload NeoNews template via wp-admin > Templates > Import Kit"
echo "  2. Upload Warsaw Daily logo via wp-admin > Media"
echo "  3. Activate Elementor Pro license if available"
echo "==========================================="

# Fix ownership
chown -R "$USER":"$USER" "$DOCROOT"
