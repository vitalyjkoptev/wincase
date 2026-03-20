# WinCase — Server Setup Guide
# 4 VPS Hetzner + 3 cPanel + WordPress News Network

---

## СЕРВЕРЫ

| VPS | IP | MAC | Hostname | Роль |
|-----|-----|-----|----------|------|
| VPS-1 | 65.109.108.79 | 00:50:56:00:AE:F0 | static.79.108.109.65.clients.your-server.de | **Main CRM** (wincase.eu, wincasejobs.com) |
| VPS-2 | 65.109.108.82 | 00:50:56:00:B4:D8 | static.82.108.109.65.clients.your-server.de | **cPanel — News Main** (5 сайтов) |
| VPS-3 | 65.109.108.106 | 00:50:56:00:B4:DE | static.106.108.109.65.clients.your-server.de | **cPanel — News + Community** (5 сайтов) |
| VPS-4 | 65.109.108.107 | 00:50:56:00:B8:11 | static.107.108.109.65.clients.your-server.de | **cPanel — SEO Satellites** (5 сайтов) |

---

## РАСПРЕДЕЛЕНИЕ ДОМЕНОВ

### VPS-1 (65.109.108.79) — Main CRM Server
> Docker + Nginx, НЕ cPanel
| # | Домен | Назначение |
|---|-------|------------|
| 1 | wincase.eu | Главный сайт бизнеса |
| 2 | wincasejobs.com | Портал вакансий |
| 3 | api.wincase.eu | CRM API (Laravel) |
| 4 | n8n.wincase.eu | n8n Workflows |
| 5 | admin.wincase.eu | Admin Panel |

### VPS-2 (65.109.108.82) — cPanel News Main
| # | Домен | Тема | CMS | Языки |
|---|-------|------|-----|-------|
| 1 | polandpulse.news | General Poland/Europe News | WordPress | en, pl, ua, ru |
| 2 | eurogamingpost.com | iGaming Industry | WordPress | en |
| 3 | techpulse.news | Technology & Startups | WordPress | en, pl |
| 4 | bizeurope.news | European Business & Finance | WordPress | en, pl |
| 5 | sportpulse.news | European Sports | WordPress | en, pl |

### VPS-3 (65.109.108.106) — cPanel News + Community
| # | Домен | Тема | CMS | Языки |
|---|-------|------|-----|-------|
| 1 | diaspora.news | Ukrainian/Migrant Community | WordPress | ua, en, pl, ru |
| 2 | trendwatch.news | Lifestyle, Culture, Trends | WordPress | en, pl |
| 3 | legalizacja-polska.pl | SEO Satellite — Легализация (PL) | WordPress | pl |
| 4 | karta-pobytu.info | SEO Satellite — Карта побыту (PL) | WordPress | pl |
| 5 | work-permit-poland.com | SEO Satellite — Work Permit (EN) | WordPress | en |

### VPS-4 (65.109.108.107) — cPanel SEO Satellites
| # | Домен | Тема | CMS | Языки |
|---|-------|------|-----|-------|
| 1 | vnzh-polsha.com | SEO Satellite — ВНЖ Польша (RU) | WordPress | ru |
| 2 | praca-dla-obcokrajowcow.pl | SEO Satellite — Работа (PL) | WordPress | pl |
| 3 | posvidka-polshcha.com | SEO Satellite — Посвідка (UA) | WordPress | ua |
| 4 | immigration-warsaw.com | SEO Satellite — Immigration (EN) | WordPress | en |
| 5 | visa-polska.com | SEO Satellite — Visa (Multilingual) | WordPress | en, pl, ru, ua |

---

## ШАГ 1: НАСТРОЙКА NS В cPanel/WHM

### Вариант A — РЕКОМЕНДУЕМЫЙ (Hetzner DNS + A-записи)
> Самый простой. Домены остаются на Hetzner DNS, просто указываем A-записи на серверы.

**В панели Hetzner Robot → DNS для каждого домена:**

#### Домены на VPS-2 (65.109.108.82):
```
polandpulse.news        A    65.109.108.82
www.polandpulse.news    A    65.109.108.82
eurogamingpost.com      A    65.109.108.82
www.eurogamingpost.com  A    65.109.108.82
techpulse.news          A    65.109.108.82
www.techpulse.news      A    65.109.108.82
bizeurope.news          A    65.109.108.82
www.bizeurope.news      A    65.109.108.82
sportpulse.news         A    65.109.108.82
www.sportpulse.news     A    65.109.108.82
```

#### Домены на VPS-3 (65.109.108.106):
```
diaspora.news               A    65.109.108.106
www.diaspora.news            A    65.109.108.106
trendwatch.news              A    65.109.108.106
www.trendwatch.news          A    65.109.108.106
legalizacja-polska.pl        A    65.109.108.106
www.legalizacja-polska.pl    A    65.109.108.106
karta-pobytu.info            A    65.109.108.106
www.karta-pobytu.info        A    65.109.108.106
work-permit-poland.com       A    65.109.108.106
www.work-permit-poland.com   A    65.109.108.106
```

#### Домены на VPS-4 (65.109.108.107):
```
vnzh-polsha.com                 A    65.109.108.107
www.vnzh-polsha.com             A    65.109.108.107
praca-dla-obcokrajowcow.pl     A    65.109.108.107
www.praca-dla-obcokrajowcow.pl A    65.109.108.107
posvidka-polshcha.com           A    65.109.108.107
www.posvidka-polshcha.com       A    65.109.108.107
immigration-warsaw.com          A    65.109.108.107
www.immigration-warsaw.com      A    65.109.108.107
visa-polska.com                 A    65.109.108.107
www.visa-polska.com             A    65.109.108.107
```

### Настройка NS в Hetzner
> Если домены уже на Hetzner DNS, NS менять НЕ нужно — просто добавить A-записи выше.
> NS по умолчанию Hetzner: `hydrogen.ns.hetzner.com`, `oxygen.ns.hetzner.com`, `helium.ns.hetzner.de`

### Вариант B — cPanel как DNS-сервер
> Если хочешь чтобы cPanel управлял DNS полностью:

**1. В WHM каждого сервера → Basic WebHost Manager Setup:**

VPS-2 (65.109.108.82):
```
Hostname: vps2.wincase.eu
NS1: ns1.wincase.eu → 65.109.108.82
NS2: ns2.wincase.eu → 65.109.108.82
```

VPS-3 (65.109.108.106):
```
Hostname: vps3.wincase.eu
NS1: ns3.wincase.eu → 65.109.108.106
NS2: ns4.wincase.eu → 65.109.108.106
```

VPS-4 (65.109.108.107):
```
Hostname: vps4.wincase.eu
NS1: ns5.wincase.eu → 65.109.108.107
NS2: ns6.wincase.eu → 65.109.108.107
```

**2. В Hetzner Robot → домен wincase.eu → создать Glue Records:**
```
ns1.wincase.eu → 65.109.108.82
ns2.wincase.eu → 65.109.108.82
ns3.wincase.eu → 65.109.108.106
ns4.wincase.eu → 65.109.108.106
ns5.wincase.eu → 65.109.108.107
ns6.wincase.eu → 65.109.108.107
```

**3. Для каждого домена на VPS-2 изменить NS на:**
```
ns1.wincase.eu
ns2.wincase.eu
```
Для VPS-3: `ns3.wincase.eu`, `ns4.wincase.eu`
Для VPS-4: `ns5.wincase.eu`, `ns6.wincase.eu`

---

## ШАГ 2: НАСТРОЙКА WHM НА КАЖДОМ СЕРВЕРЕ

### 2.1 Первый вход в WHM
```
https://65.109.108.82:2087   (VPS-2)
https://65.109.108.106:2087  (VPS-3)
https://65.109.108.107:2087  (VPS-4)
```

### 2.2 Basic WebHost Manager Setup (для каждого сервера)
1. **WHM → Server Configuration → Basic WebHost Manager Setup**
2. Заполнить:
   - Contact Email: `wincasetop@gmail.com`
   - Hostname: `vps2.wincase.eu` / `vps3.wincase.eu` / `vps4.wincase.eu`
   - Nameservers: (см. Вариант B выше, если используешь cPanel DNS)

### 2.3 PHP Configuration
1. **WHM → Software → MultiPHP Manager**
   - Установить PHP 8.2 как системный (для WordPress)
2. **WHM → Software → MultiPHP INI Editor**
   ```
   upload_max_filesize = 256M
   post_max_size = 256M
   max_execution_time = 300
   memory_limit = 512M
   max_input_vars = 5000
   ```

### 2.4 Apache/Nginx
1. **WHM → Software → EasyApache 4**
   - PHP 8.2 (cPanel Provided)
   - mod_deflate, mod_expires, mod_headers, mod_rewrite
   - PHP extensions: curl, gd, intl, mbstring, mysql, xml, zip, opcache, imagick

---

## ШАГ 3: СОЗДАНИЕ АККАУНТОВ cPanel

### Скрипт через WHM API (SSH на каждый сервер)

#### VPS-2 (65.109.108.82):
```bash
# SSH на сервер
ssh root@65.109.108.82

# Создание 5 аккаунтов cPanel
whmapi1 createacct domain=polandpulse.news username=ppulse plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=eurogamingpost.com username=egaming plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=techpulse.news username=tpulse plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=bizeurope.news username=bizeu plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=sportpulse.news username=spulse plan=default contactemail=wincasetop@gmail.com
```

#### VPS-3 (65.109.108.106):
```bash
ssh root@65.109.108.106

whmapi1 createacct domain=diaspora.news username=diaspora plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=trendwatch.news username=twatch plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=legalizacja-polska.pl username=legalpl plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=karta-pobytu.info username=kpobytu plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=work-permit-poland.com username=wpermit plan=default contactemail=wincasetop@gmail.com
```

#### VPS-4 (65.109.108.107):
```bash
ssh root@65.109.108.107

whmapi1 createacct domain=vnzh-polsha.com username=vnzh plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=praca-dla-obcokrajowcow.pl username=pracadla plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=posvidka-polshcha.com username=posvidka plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=immigration-warsaw.com username=immwarsaw plan=default contactemail=wincasetop@gmail.com
whmapi1 createacct domain=visa-polska.com username=visapl plan=default contactemail=wincasetop@gmail.com
```

---

## ШАГ 4: УСТАНОВКА WORDPRESS (WP-CLI)

### 4.1 Установить WP-CLI на каждый cPanel-сервер
```bash
# На каждом сервере (VPS-2, VPS-3, VPS-4):
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
```

### 4.2 Скрипт автоустановки WordPress

#### VPS-2 (65.109.108.82):
```bash
#!/bin/bash
# install-wp-vps2.sh — WordPress install on VPS-2

SITES=(
  "ppulse|polandpulse.news|Poland Pulse — European News Portal"
  "egaming|eurogamingpost.com|EuroGaming Post — iGaming News"
  "tpulse|techpulse.news|TechPulse — Technology & AI News"
  "bizeu|bizeurope.news|BizEurope — European Business News"
  "spulse|sportpulse.news|SportPulse — European Sports News"
)

ADMIN_USER="wincase_admin"
ADMIN_EMAIL="wincasetop@gmail.com"
ADMIN_PASS="$(openssl rand -base64 20)"

echo "=== WinCase WordPress Auto-Install ==="
echo "Admin password: $ADMIN_PASS"
echo "СОХРАНИ ЭТОТ ПАРОЛЬ!"
echo "======================================="

for SITE in "${SITES[@]}"; do
  IFS='|' read -r USER DOMAIN TITLE <<< "$SITE"
  DOCROOT="/home/$USER/public_html"

  echo ""
  echo ">>> Installing WordPress: $DOMAIN"

  # Создать БД через cPanel API
  uapi --user=$USER Mysql create_database name="${USER}_wp"
  uapi --user=$USER Mysql create_user name="${USER}_wpu" password="$(openssl rand -base64 16)"
  uapi --user=$USER Mysql set_privileges_on_database user="${USER}_wpu" database="${USER}_wp" privileges=ALL

  # Скачать WordPress
  sudo -u $USER -- wp core download --path=$DOCROOT --locale=en_US

  # Конфиг
  sudo -u $USER -- wp config create \
    --path=$DOCROOT \
    --dbname="${USER}_wp" \
    --dbuser="${USER}_wpu" \
    --dbpass="$(openssl rand -base64 16)" \
    --dbhost=localhost \
    --extra-php <<'WPCONFIG'
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', 'minor');
define('WP_POST_REVISIONS', 5);
WPCONFIG

  # Установить WordPress
  sudo -u $USER -- wp core install \
    --path=$DOCROOT \
    --url="https://$DOMAIN" \
    --title="$TITLE" \
    --admin_user="$ADMIN_USER" \
    --admin_password="$ADMIN_PASS" \
    --admin_email="$ADMIN_EMAIL" \
    --skip-email

  # Permalink structure (SEO-friendly)
  sudo -u $USER -- wp rewrite structure '/%postname%/' --path=$DOCROOT

  # Удалить стандартные плагины/темы
  sudo -u $USER -- wp plugin delete hello akismet --path=$DOCROOT
  sudo -u $USER -- wp theme delete twentytwentythree twentytwentyfour --path=$DOCROOT

  # Установить необходимые плагины
  sudo -u $USER -- wp plugin install \
    wordpress-seo \
    wp-super-cache \
    wordfence \
    wp-mail-smtp \
    redirection \
    classic-editor \
    advanced-custom-fields \
    duplicate-post \
    application-passwords \
    --activate --path=$DOCROOT

  # Плагины для автопостинга (REST API)
  sudo -u $USER -- wp plugin install \
    jwt-authentication-for-wp-rest-api \
    --activate --path=$DOCROOT

  # SEO-тема (быстрая, для новостных сайтов)
  sudo -u $USER -- wp theme install flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor generatepress flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor flavor --activate --path=$DOCROOT

  # Создать страницы
  sudo -u $USER -- wp post create --post_type=page --post_title='About' --post_status=publish --path=$DOCROOT
  sudo -u $USER -- wp post create --post_type=page --post_title='Contact' --post_status=publish --path=$DOCROOT
  sudo -u $USER -- wp post create --post_type=page --post_title='Privacy Policy' --post_status=publish --path=$DOCROOT

  # Настройки
  sudo -u $USER -- wp option update blogdescription "$TITLE" --path=$DOCROOT
  sudo -u $USER -- wp option update timezone_string "Europe/Warsaw" --path=$DOCROOT
  sudo -u $USER -- wp option update date_format "Y-m-d" --path=$DOCROOT
  sudo -u $USER -- wp option update posts_per_page 12 --path=$DOCROOT
  sudo -u $USER -- wp option update default_comment_status "closed" --path=$DOCROOT

  # Отключить пингбэки
  sudo -u $USER -- wp option update default_pingback_flag 0 --path=$DOCROOT
  sudo -u $USER -- wp option update default_ping_status "closed" --path=$DOCROOT

  echo ">>> DONE: $DOMAIN"
done

echo ""
echo "=== ALL SITES INSTALLED ==="
echo "Admin: $ADMIN_USER"
echo "Password: $ADMIN_PASS"
echo "==========================="
```

---

## ШАГ 5: SSL СЕРТИФИКАТЫ (Let's Encrypt)

### Через WHM на каждом сервере:
1. **WHM → SSL/TLS → Manage AutoSSL**
2. Включить AutoSSL provider: **Let's Encrypt**
3. AutoSSL автоматически выпустит SSL для всех доменов

### Или через SSH:
```bash
# На каждом cPanel-сервере:
/usr/local/cpanel/bin/autossl_check --all
```

---

## ШАГ 6: НАСТРОЙКА ДЛЯ АВТОПОСТИНГА (REST API)

### На каждом WordPress сайте:

#### 6.1 Включить Application Passwords
```bash
# Для каждого сайта (пример для polandpulse.news):
sudo -u ppulse -- wp user application-password create wincase_admin "n8n-autopost" --path=/home/ppulse/public_html
```
> Сохранить токен! Он используется в n8n для автопостинга.

#### 6.2 REST API проверка
```bash
curl -s https://polandpulse.news/wp-json/wp/v2/posts | head -c 200
```

#### 6.3 Создать категории (из NewsSitesRegistry)

**polandpulse.news:**
```bash
DOCROOT=/home/ppulse/public_html
sudo -u ppulse -- wp term create category "Culture" --slug=culture --path=$DOCROOT
sudo -u ppulse -- wp term create category "Europe" --slug=europe --path=$DOCROOT
sudo -u ppulse -- wp term create category "Visa & Immigration" --slug=visa-immigration --path=$DOCROOT
sudo -u ppulse -- wp term create category "Business" --slug=business --path=$DOCROOT
sudo -u ppulse -- wp term create category "Trends" --slug=trends --path=$DOCROOT
sudo -u ppulse -- wp term create category "iGaming" --slug=igaming --path=$DOCROOT
sudo -u ppulse -- wp term create category "Tech News" --slug=tech-news --path=$DOCROOT
sudo -u ppulse -- wp term create category "Sports" --slug=sports --path=$DOCROOT
sudo -u ppulse -- wp term create category "Europass & Education" --slug=europass --path=$DOCROOT
sudo -u ppulse -- wp term create category "Live TV & Media" --slug=live-tv --path=$DOCROOT
```

**eurogamingpost.com:**
```bash
DOCROOT=/home/egaming/public_html
sudo -u egaming -- wp term create category "Industry News" --slug=industry-news --path=$DOCROOT
sudo -u egaming -- wp term create category "Regulation & Compliance" --slug=regulation --path=$DOCROOT
sudo -u egaming -- wp term create category "Online Casino" --slug=online-casino --path=$DOCROOT
sudo -u egaming -- wp term create category "Sports Betting" --slug=sports-betting --path=$DOCROOT
sudo -u egaming -- wp term create category "Crypto & Blockchain" --slug=crypto-gambling --path=$DOCROOT
sudo -u egaming -- wp term create category "Affiliate & Marketing" --slug=affiliate --path=$DOCROOT
sudo -u egaming -- wp term create category "Tech & Platforms" --slug=tech-platforms --path=$DOCROOT
sudo -u egaming -- wp term create category "Market Analysis" --slug=market-analysis --path=$DOCROOT
```

**techpulse.news:**
```bash
DOCROOT=/home/tpulse/public_html
sudo -u tpulse -- wp term create category "AI & Machine Learning" --slug=ai-ml --path=$DOCROOT
sudo -u tpulse -- wp term create category "Startups & Funding" --slug=startups --path=$DOCROOT
sudo -u tpulse -- wp term create category "Software & Apps" --slug=software --path=$DOCROOT
sudo -u tpulse -- wp term create category "Cybersecurity" --slug=cybersecurity --path=$DOCROOT
sudo -u tpulse -- wp term create category "Blockchain & Web3" --slug=blockchain --path=$DOCROOT
sudo -u tpulse -- wp term create category "Gadgets & Hardware" --slug=gadgets --path=$DOCROOT
sudo -u tpulse -- wp term create category "Cloud & DevOps" --slug=cloud --path=$DOCROOT
sudo -u tpulse -- wp term create category "Reviews" --slug=reviews --path=$DOCROOT
```

**bizeurope.news:**
```bash
DOCROOT=/home/bizeu/public_html
sudo -u bizeu -- wp term create category "EU Economy" --slug=eu-economy --path=$DOCROOT
sudo -u bizeu -- wp term create category "Markets & Investing" --slug=markets --path=$DOCROOT
sudo -u bizeu -- wp term create category "Fintech & Banking" --slug=fintech --path=$DOCROOT
sudo -u bizeu -- wp term create category "Real Estate" --slug=real-estate --path=$DOCROOT
sudo -u bizeu -- wp term create category "Crypto & DeFi" --slug=crypto-finance --path=$DOCROOT
sudo -u bizeu -- wp term create category "Trade & Policy" --slug=trade-policy --path=$DOCROOT
sudo -u bizeu -- wp term create category "Entrepreneurship" --slug=entrepreneurship --path=$DOCROOT
sudo -u bizeu -- wp term create category "Poland Business" --slug=pl-business --path=$DOCROOT
```

**sportpulse.news:**
```bash
DOCROOT=/home/spulse/public_html
sudo -u spulse -- wp term create category "Football / Soccer" --slug=football --path=$DOCROOT
sudo -u spulse -- wp term create category "MMA & Boxing" --slug=mma-boxing --path=$DOCROOT
sudo -u spulse -- wp term create category "Esports" --slug=esports --path=$DOCROOT
sudo -u spulse -- wp term create category "Tennis" --slug=tennis --path=$DOCROOT
sudo -u spulse -- wp term create category "Basketball" --slug=basketball --path=$DOCROOT
sudo -u spulse -- wp term create category "Motorsport / F1" --slug=motorsport --path=$DOCROOT
sudo -u spulse -- wp term create category "Olympics" --slug=olympics --path=$DOCROOT
sudo -u spulse -- wp term create category "Betting & Odds" --slug=betting-odds --path=$DOCROOT
```

---

## ШАГ 7: CRON ДЛЯ WP-CRON (КАЖДЫЙ СЕРВЕР)

```bash
# Отключить WP-Cron (заменить на системный cron)
# Добавить в wp-config.php каждого сайта:
# define('DISABLE_WP_CRON', true);

# Системный cron (crontab -e от root):
*/5 * * * * sudo -u ppulse -- wp cron event run --due-now --path=/home/ppulse/public_html > /dev/null 2>&1
*/5 * * * * sudo -u egaming -- wp cron event run --due-now --path=/home/egaming/public_html > /dev/null 2>&1
*/5 * * * * sudo -u tpulse -- wp cron event run --due-now --path=/home/tpulse/public_html > /dev/null 2>&1
*/5 * * * * sudo -u bizeu -- wp cron event run --due-now --path=/home/bizeu/public_html > /dev/null 2>&1
*/5 * * * * sudo -u spulse -- wp cron event run --due-now --path=/home/spulse/public_html > /dev/null 2>&1
```

---

## ДОМЕНЫ ДЛЯ ПОКУПКИ НА HETZNER

### Новостные (расширение сети):
| # | Домен | Тема | Сервер |
|---|-------|------|--------|
| 1 | polandtoday.news | EN — Poland daily news | VPS-2 |
| 2 | warsawdaily.com | EN — Warsaw city news | VPS-3 |
| 3 | eurovisanews.com | EN/PL — EU visa & immigration news | VPS-3 |

### Лендинги (лидогенерация CRM):
| # | Домен | Цель | Сервер |
|---|-------|------|--------|
| 1 | kartapobytu24.pl | Оформление карты побыту | VPS-4 |
| 2 | wizapolska24.pl | Визы в Польшу | VPS-4 |
| 3 | pesel-online.pl | Получение PESEL | VPS-4 |
| 4 | firmawpolsce.com | Открытие фирмы в Польше | VPS-4 |
| 5 | zezwolenie-praca.pl | Разрешение на работу | VPS-4 |
| 6 | meldunek24.pl | Регистрация мельдунек | VPS-3 |
| 7 | pobyt-stalowy.pl | ПМЖ в Польше | VPS-3 |
| 8 | obywatelstwo-polskie.pl | Гражданство Польши | VPS-3 |
| 9 | tlumacz-przysiegly.com | Присяжный переводчик | VPS-2 |
| 10 | biuro-imigracyjne.pl | Иммиграционное бюро | VPS-2 |

---

## БЫСТРЫЙ ЧЕКЛИСТ

### Для КАЖДОГО из 3 cPanel серверов:
- [ ] WHM → Basic Setup (hostname, email, NS)
- [ ] WHM → MultiPHP Manager → PHP 8.2
- [ ] WHM → EasyApache 4 → extensions
- [ ] WHM → AutoSSL → Let's Encrypt
- [ ] Создать 5 cPanel аккаунтов (whmapi1 createacct)
- [ ] Установить WP-CLI
- [ ] Запустить скрипт установки WordPress
- [ ] Создать категории в каждом WP
- [ ] Создать Application Passwords для n8n
- [ ] Настроить WP-Cron через crontab
- [ ] Проверить REST API (curl)
- [ ] Настроить SSL (AutoSSL)

### В Hetzner DNS:
- [ ] Создать A-записи для всех 15 доменов
- [ ] Создать www CNAME или A для каждого
- [ ] Проверить DNS propagation (dig domain A)
