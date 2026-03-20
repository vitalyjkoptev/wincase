#!/bin/bash
# =====================================================
# WINCASE CRM — Server Schema Checker
# Сравнивает реальную схему MySQL на сервере с ожидаемой
# Usage: bash scripts/check-server-schema.sh
# =====================================================

set -e

SERVER="wincase-root"
DB_NAME="wincase_admin"
DB_USER="wincase_admin"
DB_PASS="c5134cece8e441c585c679f0"
REMOTE_PATH="/home/wincase/admin-app"

MYSQL_CMD="mysql -u$DB_USER -p$DB_PASS $DB_NAME -N -B"

echo "=============================================="
echo "  WINCASE — Server Schema Check"
echo "  Server: 65.109.108.79"
echo "  DB: $DB_NAME"
echo "=============================================="
echo ""

# =====================================================
# 1. Получить список таблиц на сервере
# =====================================================
echo "=== [1] ТАБЛИЦЫ НА СЕРВЕРЕ ==="
SERVER_TABLES=$(ssh $SERVER "$MYSQL_CMD -e \"SHOW TABLES;\"" 2>/dev/null)
echo "$SERVER_TABLES" | while read t; do echo "  ✓ $t"; done
SERVER_TABLE_COUNT=$(echo "$SERVER_TABLES" | wc -l | tr -d ' ')
echo ""
echo "  Всего таблиц: $SERVER_TABLE_COUNT"
echo ""

# =====================================================
# 2. Проверить таблицы которые ДОЛЖНЫ быть (align_server_schema создаёт)
# =====================================================
echo "=== [2] ПРОВЕРКА НОВЫХ ТАБЛИЦ (17 шт) ==="
NEW_TABLES=(
    ads_performance
    seo_data
    seo_network_sites
    social_accounts
    social_posts
    pos_transactions
    tax_reports
    expenses
    employee_kpis
    employee_time_tracking
    staff_messages
    n8n_workflows
    hearings
    news_articles
    reviews
    landing_pages
    accounting_periods
    client_verifications
    ai_generations
    brand_listings
)

MISSING_TABLES=()
EXISTING_NEW=()
for t in "${NEW_TABLES[@]}"; do
    if echo "$SERVER_TABLES" | grep -qw "$t"; then
        EXISTING_NEW+=("$t")
        echo "  ✓ $t — уже есть"
    else
        MISSING_TABLES+=("$t")
        echo "  ✗ $t — ОТСУТСТВУЕТ"
    fi
done
echo ""
echo "  Есть: ${#EXISTING_NEW[@]}, Нужно создать: ${#MISSING_TABLES[@]}"
echo ""

# =====================================================
# 3. Проверить колонки основных таблиц
# =====================================================
echo "=== [3] ПРОВЕРКА КОЛОНОК СУЩЕСТВУЮЩИХ ТАБЛИЦ ==="

check_columns() {
    local table=$1
    shift
    local expected_cols=("$@")

    if ! echo "$SERVER_TABLES" | grep -qw "$table"; then
        echo "  ⚠ Таблица '$table' не существует на сервере!"
        return
    fi

    echo ""
    echo "  --- $table ---"
    SERVER_COLS=$(ssh $SERVER "$MYSQL_CMD -e \"SHOW COLUMNS FROM $table;\"" 2>/dev/null | awk '{print \$1}')

    for col in "${expected_cols[@]}"; do
        if echo "$SERVER_COLS" | grep -qw "$col"; then
            echo "    ✓ $col"
        else
            echo "    ✗ $col — ОТСУТСТВУЕТ"
        fi
    done
}

# LEADS
check_columns "leads" \
    name message client_id case_id first_contact_at priority \
    utm_source utm_medium utm_campaign utm_content utm_term \
    gclid fbclid landing_page referrer ip_address user_agent

# CASES
check_columns "cases" \
    service_type amount currency is_paid voivodeship office_name \
    appeal_deadline documents_required documents_collected \
    progress_percentage lead_id completed_at closed_at

# CLIENTS
check_columns "clients" \
    name assigned_to voivodeship preferred_language \
    company_name nip gdpr_consent archived_at

# INVOICES
check_columns "invoices" \
    total_amount net_amount vat_rate vat_amount gross_amount \
    bank_account created_by

# TASKS
check_columns "tasks" \
    type case_id client_id lead_id reminder_at

# USERS
check_columns "users" \
    role hire_date salary manager_id bio \
    department position phone avatar

echo ""

# =====================================================
# 4. Проверить текущие роли пользователей
# =====================================================
echo "=== [4] РОЛИ ПОЛЬЗОВАТЕЛЕЙ ==="
ssh $SERVER "$MYSQL_CMD -e \"SELECT role, COUNT(*) as cnt FROM users GROUP BY role ORDER BY cnt DESC;\"" 2>/dev/null | while read role cnt; do
    echo "  $role: $cnt"
done
echo ""

# =====================================================
# 5. Проверить тип колонки role (enum vs varchar)
# =====================================================
echo "=== [5] ТИП КОЛОНКИ users.role ==="
ROLE_TYPE=$(ssh $SERVER "$MYSQL_CMD -e \"SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$DB_NAME' AND TABLE_NAME='users' AND COLUMN_NAME='role';\"" 2>/dev/null)
echo "  Тип: $ROLE_TYPE"
if echo "$ROLE_TYPE" | grep -qi "enum"; then
    echo "  ⚠ ENUM — нужно конвертировать в VARCHAR перед миграцией!"
else
    echo "  ✓ OK (varchar)"
fi
echo ""

# =====================================================
# 6. Проверить статус миграций
# =====================================================
echo "=== [6] МИГРАЦИИ НА СЕРВЕРЕ ==="
LAST_MIGRATIONS=$(ssh $SERVER "cd $REMOTE_PATH && php artisan migrate:status 2>/dev/null | tail -20" 2>/dev/null || echo "  ⚠ Не удалось получить статус миграций")
echo "$LAST_MIGRATIONS"
echo ""

# =====================================================
# 7. Проверить documents таблицу (особые поля)
# =====================================================
echo "=== [7] ДОПОЛНИТЕЛЬНЫЕ ТАБЛИЦЫ ==="

check_columns "documents" \
    client_id case_id type file_path original_name \
    status uploaded_by verified_by

check_columns "payments" \
    client_id case_id invoice_id amount currency \
    payment_method status paid_date

check_columns "messages" \
    from_user_id to_user_id client_id case_id \
    channel message is_read

echo ""

# =====================================================
# 8. Размеры таблиц (данные на сервере)
# =====================================================
echo "=== [8] РАЗМЕРЫ ТАБЛИЦ (кол-во строк) ==="
CORE_TABLES=(users clients cases leads tasks invoices documents payments messages hearings)
for t in "${CORE_TABLES[@]}"; do
    if echo "$SERVER_TABLES" | grep -qw "$t"; then
        CNT=$(ssh $SERVER "$MYSQL_CMD -e \"SELECT COUNT(*) FROM $t;\"" 2>/dev/null)
        echo "  $t: $CNT rows"
    fi
done
echo ""

# =====================================================
# ИТОГО
# =====================================================
echo "=============================================="
echo "  ИТОГО:"
echo "  Таблиц на сервере: $SERVER_TABLE_COUNT"
echo "  Новых таблиц нужно создать: ${#MISSING_TABLES[@]}"
if [ ${#MISSING_TABLES[@]} -gt 0 ]; then
    echo "  Отсутствующие: ${MISSING_TABLES[*]}"
fi
echo ""
echo "  Следующий шаг:"
echo "    1. bash deploy-to-server.sh"
echo "    2. ssh $SERVER"
echo "    3. cd $REMOTE_PATH"
echo "    4. php artisan migrate --force"
echo "=============================================="
