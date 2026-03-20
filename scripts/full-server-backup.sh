#!/bin/bash
# =====================================================
# WINCASE CRM — Full Server Backup (ТОЛЬКО ЧТЕНИЕ!)
# Скачивает ВСЁ с сервера в локальную папку backups/
# Usage: bash scripts/full-server-backup.sh
# =====================================================

set -e

SERVER="wincase-root"
REMOTE_PATH="/home/wincase/admin-app"
DB_NAME="wincase_admin"
DB_USER="wincase_admin"
DB_PASS="c5134cece8e441c585c679f0"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/Users/webwavedeveloper/Herd/wincase/backups/$TIMESTAMP"

echo "=============================================="
echo "  WINCASE — Full Server Backup"
echo "  Server: 65.109.108.79"
echo "  Backup: $BACKUP_DIR"
echo "  Date: $(date)"
echo "=============================================="
echo ""

mkdir -p "$BACKUP_DIR"

# =====================================================
# 1. MySQL DUMP (полная база)
# =====================================================
echo "[1/8] MySQL dump ($DB_NAME) ..."
mkdir -p "$BACKUP_DIR/database"

ssh $SERVER "mysqldump -u$DB_USER -p$DB_PASS $DB_NAME --single-transaction --routines --triggers --quick 2>/dev/null" \
    > "$BACKUP_DIR/database/wincase_admin_full.sql"

DUMP_SIZE=$(du -h "$BACKUP_DIR/database/wincase_admin_full.sql" | awk '{print $1}')
echo "  ✓ Full dump: $DUMP_SIZE"

# Отдельно — только структура (без данных)
ssh $SERVER "mysqldump -u$DB_USER -p$DB_PASS $DB_NAME --no-data --single-transaction 2>/dev/null" \
    > "$BACKUP_DIR/database/wincase_admin_schema_only.sql"
echo "  ✓ Schema-only dump saved"

# Список таблиц + кол-во строк
ssh $SERVER "mysql -u$DB_USER -p$DB_PASS $DB_NAME -N -B -e \"
    SELECT TABLE_NAME, TABLE_ROWS, ROUND(DATA_LENGTH/1024/1024,2) as size_mb
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA='$DB_NAME'
    ORDER BY TABLE_NAME;
\" 2>/dev/null" > "$BACKUP_DIR/database/tables_info.txt"
echo "  ✓ Tables info saved"
echo ""

# =====================================================
# 2. Laravel app/ (controllers, models, services)
# =====================================================
echo "[2/8] Laravel app/ ..."
mkdir -p "$BACKUP_DIR/app"
scp -rq "$SERVER:$REMOTE_PATH/app/" "$BACKUP_DIR/app/"
echo "  ✓ app/ copied"
echo ""

# =====================================================
# 3. Config
# =====================================================
echo "[3/8] config/ ..."
mkdir -p "$BACKUP_DIR/config"
scp -rq "$SERVER:$REMOTE_PATH/config/" "$BACKUP_DIR/config/"
echo "  ✓ config/ copied"
echo ""

# =====================================================
# 4. Routes
# =====================================================
echo "[4/8] routes/ ..."
mkdir -p "$BACKUP_DIR/routes"
scp -rq "$SERVER:$REMOTE_PATH/routes/" "$BACKUP_DIR/routes/"
echo "  ✓ routes/ copied"
echo ""

# =====================================================
# 5. Database (migrations, seeders)
# =====================================================
echo "[5/8] database/ (migrations + seeders) ..."
mkdir -p "$BACKUP_DIR/database/migrations"
mkdir -p "$BACKUP_DIR/database/seeders"
scp -rq "$SERVER:$REMOTE_PATH/database/migrations/" "$BACKUP_DIR/database/migrations/"
scp -rq "$SERVER:$REMOTE_PATH/database/seeders/" "$BACKUP_DIR/database/seeders/"
echo "  ✓ migrations + seeders copied"
echo ""

# =====================================================
# 6. Views (blade templates)
# =====================================================
echo "[6/8] resources/views/ ..."
mkdir -p "$BACKUP_DIR/views"
scp -rq "$SERVER:$REMOTE_PATH/resources/views/" "$BACKUP_DIR/views/"
echo "  ✓ views/ copied"
echo ""

# =====================================================
# 7. Public assets
# =====================================================
echo "[7/8] public/assets/ (js, css, images) ..."
mkdir -p "$BACKUP_DIR/public"
scp -rq "$SERVER:$REMOTE_PATH/public/assets/" "$BACKUP_DIR/public/assets/" 2>/dev/null || echo "  ⚠ partial copy"
# .env на сервере
scp -q "$SERVER:$REMOTE_PATH/.env" "$BACKUP_DIR/.env.server" 2>/dev/null || echo "  ⚠ .env not found"
echo "  ✓ public + .env copied"
echo ""

# =====================================================
# 8. Bootstrap + composer
# =====================================================
echo "[8/8] bootstrap/, composer.json, composer.lock ..."
mkdir -p "$BACKUP_DIR/bootstrap"
scp -q "$SERVER:$REMOTE_PATH/bootstrap/app.php" "$BACKUP_DIR/bootstrap/app.php" 2>/dev/null || true
scp -q "$SERVER:$REMOTE_PATH/composer.json" "$BACKUP_DIR/composer.json" 2>/dev/null || true
scp -q "$SERVER:$REMOTE_PATH/composer.lock" "$BACKUP_DIR/composer.lock" 2>/dev/null || true
# Migration status
ssh $SERVER "cd $REMOTE_PATH && php artisan migrate:status 2>/dev/null" > "$BACKUP_DIR/database/migration_status.txt" 2>/dev/null || true
echo "  ✓ bootstrap + composer copied"
echo ""

# =====================================================
# ИТОГО
# =====================================================
TOTAL_SIZE=$(du -sh "$BACKUP_DIR" | awk '{print $1}')
FILE_COUNT=$(find "$BACKUP_DIR" -type f | wc -l | tr -d ' ')

echo "=============================================="
echo "  БЕКАП ЗАВЕРШЁН"
echo ""
echo "  Путь:    $BACKUP_DIR"
echo "  Размер:  $TOTAL_SIZE"
echo "  Файлов:  $FILE_COUNT"
echo ""
echo "  Содержит:"
echo "    - MySQL full dump + schema-only dump"
echo "    - Список таблиц с размерами"
echo "    - app/ (controllers, models, services)"
echo "    - config/"
echo "    - routes/"
echo "    - database/ (migrations, seeders)"
echo "    - views/ (blade templates)"
echo "    - public/assets/ (js, css, images)"
echo "    - .env.server"
echo "    - bootstrap/app.php"
echo "    - composer.json + lock"
echo "    - migration_status.txt"
echo ""
echo "  Для восстановления БД:"
echo "    mysql -u$DB_USER -p$DB_PASS $DB_NAME < $BACKUP_DIR/database/wincase_admin_full.sql"
echo "=============================================="
