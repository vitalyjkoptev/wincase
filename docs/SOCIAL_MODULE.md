# WINCASE CRM v4.0 — Social + Brand Module (Phase 7)
## Laravel 12 + PHP 8.4

---

## Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                 SocialOrchestrationService                        │
│  (unified posting, inbox, calendar, sync, dashboard stats)        │
└──┬──────┬──────┬───────┬──────┬───────┬──────┬───────┬──────────┘
   │      │      │       │      │       │      │       │
   ▼      ▼      ▼       ▼      ▼       ▼      ▼       ▼
┌────┐┌─────┐┌───────┐┌──────┐┌─────┐┌────┐┌──────┐┌────────┐
│ FB ││ IG  ││Threads││TikTok││ YT  ││ TG ││Pinter││LinkedIn│
│Svc ││ Svc ││ Svc   ││ Svc  ││ Svc ││Svc ││Svc   ││  Svc   │
└──┬─┘└──┬──┘└───┬───┘└──┬───┘└──┬──┘└──┬─┘└──┬───┘└───┬────┘
   │     │       │       │       │      │      │        │
   ▼     ▼       ▼       ▼       ▼      ▼      ▼        ▼
 Graph  Graph  Threads  Content  Data   Bot   Pinter  LinkedIn
 API    API    API v1   Post.API API v3 API   API v5  Mktg API
 v19.0  v19.0  .0       v2                            v2
```

---

## 8 Platforms Support Matrix

| Platform | Publish | Analytics | Inbox | Account Stats | API |
|----------|---------|-----------|-------|---------------|-----|
| **Facebook** | ✅ text/photo/link | ✅ impressions, clicks | ✅ Conversations | ✅ followers | Graph API v19.0 |
| **Instagram** | ✅ image/reels (2-step) | ✅ reach, likes, saves | ✅ DM | ✅ followers, media | Graph API v19.0 |
| **Threads** | ✅ text/image (2-step) | ✅ views, likes, replies | ❌ | ✅ followers | Threads API v1.0 |
| **TikTok** | ✅ video only (URL pull) | ✅ views, likes, shares | ❌ | ✅ followers, videos | Content Posting v2 |
| **YouTube** | ⚡ metadata (upload via n8n) | ✅ views, likes, comments | ✅ comments | ✅ subscribers | Data API v3 |
| **Telegram** | ✅ text/photo/video (HTML) | ❌ (use TGStat) | ✅ bot messages | ✅ member count | Bot API |
| **Pinterest** | ✅ pin with image + link | ✅ impressions, saves, clicks | ❌ | ✅ followers, pins | API v5 |
| **LinkedIn** | ✅ text/article (org page) | ✅ impressions, engagement | ❌ | ✅ followers | Marketing API v2 |

**Legend:** ✅ Full support | ⚡ Partial | ❌ Not available

---

## Key Features

### Unified Posting
Publish same content to 2-8 platforms in one API call:
```
POST /api/v1/social/publish
{
  "text": "New article about...",
  "media_url": "https://...",
  "link": "https://wincase.pro/blog/...",
  "platforms": ["facebook", "instagram", "threads", "telegram", "linkedin"]
}
```

### Unified Inbox
Aggregated messages from 4 platforms that support messaging:
Facebook (Conversations), Instagram (DM), YouTube (Comments), Telegram (Bot messages)

### Content Calendar
View scheduled and published posts grouped by date:
```
GET /api/v1/social/calendar?date_from=2026-02-01&date_to=2026-02-28
```

---

## Files Created (Phase 7)

```
social-module/
├── services/
│   ├── AbstractSocialService.php        # Base: publish, savePost, saveAnalytics
│   ├── FacebookService.php              # Graph API v19.0
│   ├── InstagramService.php             # IG Business (2-step publish)
│   ├── ThreadsService.php               # Threads API v1.0 (2-step publish)
│   ├── TikTokService.php                # Content Posting API v2
│   ├── YouTubeService.php               # Data API v3
│   ├── TelegramService.php              # Bot API (HTML parse_mode)
│   ├── PinterestService.php             # Pinterest API v5
│   ├── LinkedInService.php              # Marketing API v2 (ugcPosts)
│   └── SocialOrchestrationService.php   # Unified: posting, inbox, calendar
├── controllers/
│   └── SocialController.php             # 7 API endpoints
├── routes/
│   └── api_social_routes.php            # 7 routes (auth:sanctum)
└── SOCIAL_MODULE.md                     # This documentation
```

---

## API Endpoints (7)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/social/accounts` | All 8 platform accounts + stats |
| `POST` | `/api/v1/social/publish` | Unified posting to N platforms |
| `GET` | `/api/v1/social/inbox` | Unified Inbox (FB, IG, YT, TG) |
| `GET` | `/api/v1/social/posts` | Recent posts (?platform= filter) |
| `GET` | `/api/v1/social/posts/{id}/analytics` | Single post metrics |
| `GET` | `/api/v1/social/calendar` | Content calendar (?date_from=&date_to=) |
| `POST` | `/api/v1/social/sync` | Sync all account stats (manual trigger) |

---

## n8n Workflows (Social)

| Workflow | Task | Frequency |
|----------|------|-----------|
| W11 | Sync all account stats (followers) | Daily |
| W12 | Sync post analytics (last 7 days) | Daily |
| W13 | Auto-publish scheduled posts | Every 30 min |
| W14 | Unified inbox polling | Every 15 min |

---

## Installation

```bash
# Copy files
cp services/*.php backend/app/Services/Social/
cp controllers/SocialController.php backend/app/Http/Controllers/Api/V1/

# Append routes
cat routes/api_social_routes.php >> backend/routes/api.php

# Add to .env
FACEBOOK_PAGE_ID=your_page_id
FACEBOOK_PAGE_TOKEN=your_page_token
INSTAGRAM_USER_ID=your_ig_user_id
INSTAGRAM_TOKEN=your_ig_token
THREADS_USER_ID=your_threads_user_id
THREADS_TOKEN=your_threads_token
TIKTOK_CREATOR_TOKEN=your_tiktok_token
YOUTUBE_CHANNEL_ID=your_channel_id
YOUTUBE_REFRESH_TOKEN=your_refresh_token
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHANNEL_ID=@WinCasePro
PINTEREST_ACCESS_TOKEN=your_pinterest_token
PINTEREST_BOARD_ID=your_board_id
LINKEDIN_ACCESS_TOKEN=your_linkedin_token
LINKEDIN_ORGANIZATION_ID=your_org_id

php artisan route:list --path=social
```

<!--
Аннотация (RU):
Модуль Social + Brand WINCASE CRM v4.0.
8 платформ: Facebook, Instagram, Threads, TikTok, YouTube, Telegram, Pinterest, LinkedIn.
Unified Posting (массовый постинг), Unified Inbox (4 платформы с DM),
Content Calendar, Account Stats Sync.
12 файлов: 10 сервисов (abstract + 8 платформ + orchestration), 1 контроллер, 1 routes.
Файл: docs/SOCIAL_MODULE.md
-->
