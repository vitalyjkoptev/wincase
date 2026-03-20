# WINCASE CRM v4.0 — PROJECT TREE STRUCTURE

## Full Path: ~/Projects/wincase-crm/

---

```
wincase-crm/
│
├── backend/                                    # Laravel 12 Backend (PHP 8.4, API + Admin Panel)
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/
│   │   │       ├── SyncGoogleAdsCommand.php
│   │   │       ├── SyncMetaAdsCommand.php
│   │   │       ├── SyncTikTokAdsCommand.php
│   │   │       ├── SyncPinterestAdsCommand.php
│   │   │       ├── SyncYouTubeAdsCommand.php
│   │   │       ├── SyncGSCDataCommand.php
│   │   │       ├── SyncGA4DataCommand.php
│   │   │       ├── SyncSEODataCommand.php
│   │   │       ├── CheckNAPConsistencyCommand.php
│   │   │       ├── SyncReviewsCommand.php
│   │   │       └── GenerateMonthlyReportCommand.php
│   │   │
│   │   ├── Enums/
│   │   │   ├── LeadSourceEnum.php              # google_ads, facebook_ads, tiktok_ads, pinterest_ads, youtube_ads, threads, organic, telegram, whatsapp, referral, walk_in, phone
│   │   │   ├── LeadStatusEnum.php              # new, contacted, consultation, contract, paid, rejected, spam
│   │   │   ├── ServiceTypeEnum.php             # karta_pobytu, citizenship, work_permit, temporary_protection, business, job_centre, other
│   │   │   ├── AdsPlatformEnum.php             # google_ads, meta_ads, tiktok_ads, pinterest_ads, youtube_ads
│   │   │   ├── SocialPlatformEnum.php          # facebook, instagram, threads, tiktok, youtube, telegram, pinterest, linkedin
│   │   │   ├── ReviewPlatformEnum.php          # google, trustpilot, facebook, gowork, clutch, provenexpert
│   │   │   ├── PriorityEnum.php                # low, medium, high, urgent
│   │   │   ├── CaseStatusEnum.php              # new, in_progress, pending, under_review, completed
│   │   │   └── BrandListingStatusEnum.php      # listed, pending, not_listed, error
│   │   │
│   │   ├── Events/
│   │   │   ├── LeadCreatedEvent.php
│   │   │   ├── LeadConvertedEvent.php
│   │   │   ├── LeadAssignedEvent.php
│   │   │   ├── CaseStatusChangedEvent.php
│   │   │   ├── PaymentReceivedEvent.php
│   │   │   └── ReviewReceivedEvent.php
│   │   │
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/
│   │   │   │   │   └── V1/
│   │   │   │   │       ├── LeadController.php              # 8 endpoints: index, store, show, update, destroy, convert, funnel, stats
│   │   │   │   │       ├── AdsController.php               # 4 endpoints: overview, byPlatform, campaigns, budget
│   │   │   │   │       ├── SEOController.php               # 6 endpoints: overview, keywords, network, backlinks, reviews, brand
│   │   │   │   │       ├── DashboardController.php         # 8 endpoints: kpi, cases, leads, finance, ads, social, seo, notifications
│   │   │   │   │       ├── SocialController.php            # 6 endpoints: accounts, posts, threadsPost, analytics, threadsAnalytics, inbox
│   │   │   │   │       ├── ClientController.php            # CRUD + search, segment, verify
│   │   │   │   │       ├── CaseController.php              # CRUD + kanban, hearings, documents
│   │   │   │   │       ├── CalendarController.php          # events, hearings, google sync
│   │   │   │   │       ├── DocumentController.php          # upload, OCR, templates, e-sign
│   │   │   │   │       ├── FinanceController.php           # invoices, payments, stripe, p24
│   │   │   │   │       ├── ContentController.php           # media library, templates, content plan
│   │   │   │   │       ├── CommunicationController.php     # chat, email, whatsapp, telegram, threads DM
│   │   │   │   │       ├── AnalyticsController.php         # reports: cases, finance, marketing, seo, social
│   │   │   │   │       ├── LandingController.php           # 14+ landings, forms, A/B tests, conversions
│   │   │   │   │       ├── BrandController.php             # trademark, listings, wikipedia, knowledge panel
│   │   │   │   │       ├── NotificationController.php      # push, email, telegram alerts
│   │   │   │   │       └── SettingsController.php          # api keys, users, roles, domains
│   │   │   │   │
│   │   │   │   └── Webhook/
│   │   │   │       ├── LeadWebhookController.php           # Public POST (forms from 4 domains)
│   │   │   │       ├── MetaWebhookController.php           # Facebook/Instagram/Threads leads
│   │   │   │       ├── TikTokWebhookController.php         # TikTok lead forms
│   │   │   │       ├── GoogleAdsWebhookController.php      # Google Ads lead forms
│   │   │   │       ├── WhatsAppWebhookController.php       # WhatsApp Cloud API incoming
│   │   │   │       ├── TelegramWebhookController.php       # Telegram Bot incoming
│   │   │   │       └── StripeWebhookController.php         # Stripe payment events
│   │   │   │
│   │   │   ├── Middleware/
│   │   │   │   ├── LeadRateLimitMiddleware.php              # 10/min/IP for public lead POST
│   │   │   │   ├── HoneypotMiddleware.php                   # Anti-spam honeypot field
│   │   │   │   ├── RecaptchaMiddleware.php                  # reCAPTCHA v3 validation
│   │   │   │   ├── CorsDomainsMiddleware.php                # CORS: 4 wincase domains
│   │   │   │   └── AdminMiddleware.php                      # Admin-only access
│   │   │   │
│   │   │   ├── Requests/
│   │   │   │   ├── Lead/
│   │   │   │   │   ├── StoreLeadRequest.php
│   │   │   │   │   ├── UpdateLeadRequest.php
│   │   │   │   │   └── ConvertLeadRequest.php
│   │   │   │   ├── Ads/
│   │   │   │   │   └── AdsFilterRequest.php
│   │   │   │   ├── Social/
│   │   │   │   │   ├── CreatePostRequest.php
│   │   │   │   │   └── ThreadsPostRequest.php
│   │   │   │   ├── Client/
│   │   │   │   │   ├── StoreClientRequest.php
│   │   │   │   │   └── UpdateClientRequest.php
│   │   │   │   ├── Case/
│   │   │   │   │   ├── StoreCaseRequest.php
│   │   │   │   │   └── UpdateCaseRequest.php
│   │   │   │   └── Finance/
│   │   │   │       ├── StoreInvoiceRequest.php
│   │   │   │       └── StorePaymentRequest.php
│   │   │   │
│   │   │   └── Resources/
│   │   │       ├── LeadResource.php
│   │   │       ├── LeadCollection.php
│   │   │       ├── AdsPerformanceResource.php
│   │   │       ├── SEODataResource.php
│   │   │       ├── ReviewResource.php
│   │   │       ├── SocialAccountResource.php
│   │   │       ├── SocialPostResource.php
│   │   │       ├── BrandListingResource.php
│   │   │       ├── DashboardKPIResource.php
│   │   │       ├── ClientResource.php
│   │   │       ├── CaseResource.php
│   │   │       ├── InvoiceResource.php
│   │   │       └── NotificationResource.php
│   │   │
│   │   ├── Listeners/
│   │   │   ├── SendLeadWelcomeWhatsApp.php
│   │   │   ├── SendLeadAlertTelegram.php
│   │   │   ├── TrackOfflineConversion.php          # Google Ads gclid
│   │   │   ├── SendFacebookCAPIEvent.php            # Meta fbclid
│   │   │   ├── SendTikTokEvent.php                  # TikTok ttclid
│   │   │   ├── AddLeadToGoogleSheets.php
│   │   │   ├── StartBrevoEmailDrip.php
│   │   │   ├── SendReviewRequestChain.php
│   │   │   └── UpdateDashboardCache.php
│   │   │
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Client.php                          # Existing
│   │   │   ├── ClientCase.php                      # Existing (renamed from Case)
│   │   │   ├── Hearing.php                         # Existing
│   │   │   ├── Task.php                            # Existing
│   │   │   ├── Document.php                        # Existing
│   │   │   ├── Invoice.php                         # Existing
│   │   │   ├── Payment.php                         # Existing
│   │   │   ├── Notification.php                    # Existing
│   │   │   ├── CalendarEvent.php                   # Existing
│   │   │   ├── SocialAccount.php                   # Existing (updated: +threads, +linkedin)
│   │   │   ├── SocialPost.php                      # Existing (updated: +threads_id)
│   │   │   ├── SocialAnalytics.php                 # Existing (updated: +threads, +linkedin)
│   │   │   ├── ContentCalendar.php                 # Existing (updated: +platforms JSON)
│   │   │   ├── Lead.php                            # NEW — 31 columns
│   │   │   ├── AdsPerformance.php                  # NEW — daily ads metrics
│   │   │   ├── SEOData.php                         # NEW — GSC/GA4/Ahrefs per domain
│   │   │   ├── Review.php                          # NEW — all review platforms
│   │   │   ├── SEONetworkSite.php                  # NEW — 8 satellite sites
│   │   │   ├── BrandListing.php                    # NEW — 50+ catalogs NAP
│   │   │   └── Landing.php                         # NEW — 14+ landing pages
│   │   │
│   │   ├── Observers/
│   │   │   ├── LeadObserver.php                    # Auto-routing, notifications
│   │   │   ├── CaseObserver.php
│   │   │   └── PaymentObserver.php
│   │   │
│   │   ├── Policies/
│   │   │   ├── LeadPolicy.php
│   │   │   ├── ClientPolicy.php
│   │   │   ├── CasePolicy.php
│   │   │   └── FinancePolicy.php
│   │   │
│   │   ├── Providers/
│   │   │   ├── AppServiceProvider.php
│   │   │   ├── EventServiceProvider.php
│   │   │   ├── RouteServiceProvider.php
│   │   │   └── AuthServiceProvider.php
│   │   │
│   │   └── Services/
│   │       ├── Lead/
│   │       │   ├── LeadRoutingService.php          # Auto-assignment by language/service/priority
│   │       │   ├── LeadConversionService.php       # Lead -> Client + Case
│   │       │   ├── LeadFunnelService.php           # Funnel analytics
│   │       │   └── LeadImportService.php           # CSV/Excel import
│   │       │
│   │       ├── Ads/
│   │       │   ├── GoogleAdsService.php            # Google Ads API sync (9 campaigns)
│   │       │   ├── MetaAdsService.php              # Meta Ads API sync (4 campaigns)
│   │       │   ├── TikTokAdsService.php            # TikTok Ads API sync (4 campaigns)
│   │       │   ├── PinterestAdsService.php         # Pinterest Ads API sync (3 campaigns)
│   │       │   ├── YouTubeAdsService.php           # YouTube Ads API sync (5 campaigns)
│   │       │   ├── AdsAggregatorService.php        # Unified ads overview
│   │       │   └── BudgetPlannerService.php        # Budget allocation + ROI forecast
│   │       │
│   │       ├── SEO/
│   │       │   ├── GSCService.php                  # Google Search Console (4 domains)
│   │       │   ├── GA4Service.php                  # Google Analytics 4 (4 properties)
│   │       │   ├── AhrefsService.php               # Domain Authority, backlinks
│   │       │   ├── KeywordsTrackerService.php      # Top-50 keywords daily
│   │       │   └── SEONetworkService.php           # 8 satellite sites management
│   │       │
│   │       ├── Social/
│   │       │   ├── FacebookService.php             # Graph API v19.0
│   │       │   ├── InstagramService.php            # Instagram Graph API
│   │       │   ├── ThreadsService.php              # Threads API (Meta) — posts, carousel, replies, insights
│   │       │   ├── TikTokService.php               # TikTok Business API
│   │       │   ├── YouTubeService.php              # YouTube Data API v3
│   │       │   ├── TelegramService.php             # Telegram Bot API
│   │       │   ├── PinterestService.php            # Pinterest API v5
│   │       │   ├── LinkedInService.php             # LinkedIn API
│   │       │   ├── UnifiedPostingService.php       # Cross-posting to all 8 platforms
│   │       │   └── UnifiedInboxService.php         # All DMs/comments in one inbox
│   │       │
│   │       ├── Brand/
│   │       │   ├── TrademarkService.php            # UPRP + EUIPO status tracking
│   │       │   ├── BusinessListingsService.php     # 50+ catalogs NAP check (4 domains)
│   │       │   ├── ReviewsHubService.php           # Google, Trustpilot, Facebook, GoWork
│   │       │   ├── ReviewRequestService.php        # Auto review request chain
│   │       │   ├── WikipediaService.php            # Wikipedia article status
│   │       │   └── KnowledgePanelService.php       # Wikidata, Crunchbase, Schema
│   │       │
│   │       ├── Dashboard/
│   │       │   ├── KPIService.php                  # 10 KPI cards aggregation
│   │       │   ├── CasesDashboardService.php       # Kanban + tasks + hearings
│   │       │   ├── LeadsDashboardService.php       # Funnel + channels + latest
│   │       │   ├── FinanceDashboardService.php     # Revenue + unpaid
│   │       │   ├── AdsDashboardService.php         # All platforms summary
│   │       │   ├── SocialDashboardService.php      # 8 platforms + scheduled posts
│   │       │   ├── SEODashboardService.php         # 4 domains + DA
│   │       │   └── NotificationDashboardService.php
│   │       │
│   │       ├── Communication/
│   │       │   ├── WhatsAppCloudService.php        # WhatsApp Cloud API
│   │       │   ├── TelegramBotService.php          # Telegram Bot (@WinCasePro)
│   │       │   ├── BrevoEmailService.php           # Brevo (Sendinblue) email/drip
│   │       │   └── SMSService.php
│   │       │
│   │       ├── Integration/
│   │       │   ├── GoogleMapsService.php           # Maps + Places API
│   │       │   ├── StripeService.php               # Stripe payments
│   │       │   ├── Przelewy24Service.php           # P24 payments
│   │       │   ├── OpenAIService.php               # AI content generation
│   │       │   └── GoogleCalendarService.php       # Google Calendar sync
│   │       │
│   │       └── Report/
│   │           ├── WeeklyReportService.php
│   │           └── MonthlyReportService.php
│   │
│   ├── config/
│   │   ├── app.php
│   │   ├── auth.php
│   │   ├── database.php
│   │   ├── services.php                            # All 21 API keys config
│   │   ├── ads.php                                 # Ads platforms config
│   │   ├── seo.php                                 # SEO services config (4 domains)
│   │   ├── social.php                              # 8 social platforms config
│   │   ├── leads.php                               # Lead routing rules
│   │   ├── brand.php                               # Brand/listings config
│   │   └── cors.php                                # CORS: 4 wincase domains
│   │
│   ├── database/
│   │   ├── factories/
│   │   │   ├── LeadFactory.php
│   │   │   ├── ClientFactory.php
│   │   │   ├── CaseFactory.php
│   │   │   └── AdsPerformanceFactory.php
│   │   │
│   │   ├── migrations/
│   │   │   ├── 2026_01_01_000001_create_users_table.php
│   │   │   ├── 2026_01_01_000002_create_clients_table.php
│   │   │   ├── 2026_01_01_000003_create_cases_table.php
│   │   │   ├── 2026_01_01_000004_create_hearings_table.php
│   │   │   ├── 2026_01_01_000005_create_tasks_table.php
│   │   │   ├── 2026_01_01_000006_create_documents_table.php
│   │   │   ├── 2026_01_01_000007_create_invoices_table.php
│   │   │   ├── 2026_01_01_000008_create_payments_table.php
│   │   │   ├── 2026_01_01_000009_create_notifications_table.php
│   │   │   ├── 2026_01_01_000010_create_calendar_events_table.php
│   │   │   ├── 2026_01_01_000011_create_social_accounts_table.php
│   │   │   ├── 2026_01_01_000012_create_social_posts_table.php
│   │   │   ├── 2026_01_01_000013_create_social_analytics_table.php
│   │   │   ├── 2026_01_01_000014_create_content_calendar_table.php
│   │   │   │
│   │   │   │  # === NEW v4.0 TABLES ===
│   │   │   ├── 2026_02_15_000001_create_leads_table.php              # 31 columns
│   │   │   ├── 2026_02_15_000002_create_ads_performance_table.php     # Daily ads metrics
│   │   │   ├── 2026_02_15_000003_create_seo_data_table.php            # GSC/GA4/Ahrefs per domain
│   │   │   ├── 2026_02_15_000004_create_reviews_table.php             # All review platforms
│   │   │   ├── 2026_02_15_000005_create_seo_network_sites_table.php   # 8 satellite sites
│   │   │   ├── 2026_02_15_000006_create_brand_listings_table.php      # 50+ catalogs NAP
│   │   │   ├── 2026_02_15_000007_create_landings_table.php            # 14+ landing pages
│   │   │   │
│   │   │   │  # === v4.0 UPDATES TO EXISTING ===
│   │   │   ├── 2026_02_15_000010_add_threads_linkedin_to_social_accounts.php
│   │   │   ├── 2026_02_15_000011_add_threads_linkedin_to_social_posts.php
│   │   │   ├── 2026_02_15_000012_add_threads_linkedin_to_social_analytics.php
│   │   │   ├── 2026_02_15_000013_add_threads_id_to_social_posts.php
│   │   │   └── 2026_02_15_000014_add_platforms_json_to_content_calendar.php
│   │   │
│   │   └── seeders/
│   │       ├── DatabaseSeeder.php
│   │       ├── UserSeeder.php
│   │       ├── LeadRoutingRulesSeeder.php
│   │       ├── BrandListingsSeeder.php             # 50+ catalogs initial
│   │       └── SEONetworkSitesSeeder.php           # 8 satellite sites
│   │
│   ├── resources/
│   │   └── views/
│   │       └── emails/
│   │           ├── lead-welcome.blade.php          # Multi-language welcome
│   │           ├── lead-followup.blade.php
│   │           ├── review-request.blade.php
│   │           └── monthly-report.blade.php
│   │
│   ├── routes/
│   │   ├── api.php                                 # /api/v1/* — all 30+ endpoints
│   │   ├── web.php                                 # Admin panel routes
│   │   ├── webhooks.php                            # Public webhooks (leads, meta, tiktok, stripe)
│   │   └── channels.php                            # WebSocket channels (Laravel Reverb)
│   │
│   ├── storage/
│   │   ├── app/
│   │   │   ├── documents/                          # Client documents
│   │   │   ├── media/                              # Social media assets
│   │   │   └── reports/                            # Generated reports
│   │   └── logs/
│   │
│   ├── tests/
│   │   ├── Feature/
│   │   │   ├── LeadApiTest.php
│   │   │   ├── AdsApiTest.php
│   │   │   ├── SEOApiTest.php
│   │   │   ├── DashboardApiTest.php
│   │   │   ├── SocialApiTest.php
│   │   │   └── WebhookTest.php
│   │   └── Unit/
│   │       ├── LeadRoutingServiceTest.php
│   │       ├── LeadConversionServiceTest.php
│   │       └── AdsAggregatorServiceTest.php
│   │
│   ├── .env.example                                # 21+ API keys template
│   ├── composer.json
│   ├── artisan
│   └── README.md
│
├── frontend/                                       # Vue.js 3.5 Web Panel (SPA, Vite 7, Pinia 3, TypeScript)
│   ├── src/
│   │   ├── api/
│   │   │   ├── axios.js                            # Axios instance + interceptors
│   │   │   ├── leads.js                            # Leads API calls
│   │   │   ├── ads.js                              # Ads API calls
│   │   │   ├── seo.js                              # SEO API calls
│   │   │   ├── dashboard.js                        # Dashboard API calls
│   │   │   ├── social.js                           # Social API calls
│   │   │   ├── clients.js
│   │   │   ├── cases.js
│   │   │   ├── finance.js
│   │   │   ├── brand.js
│   │   │   └── auth.js
│   │   │
│   │   ├── components/
│   │   │   ├── dashboard/
│   │   │   │   ├── DashboardMain.vue               # Main consolidated dashboard
│   │   │   │   ├── KPIBar.vue                      # 10 KPI cards header
│   │   │   │   ├── CasesWidget.vue                 # Mini kanban + tasks + hearings
│   │   │   │   ├── LeadsWidget.vue                 # Funnel + channels pie + latest
│   │   │   │   ├── FinanceWidget.vue               # Revenue + unpaid + bar chart
│   │   │   │   ├── AdsWidget.vue                   # All platforms table + graphs
│   │   │   │   ├── SocialWidget.vue                # 8 platforms + scheduled posts
│   │   │   │   ├── SEOWidget.vue                   # 4 domains + DA + keywords
│   │   │   │   └── NotificationsWidget.vue         # Tasks + calendar sidebar
│   │   │   │
│   │   │   ├── leads/
│   │   │   │   ├── LeadsList.vue                   # Table with filters, search, pagination
│   │   │   │   ├── LeadForm.vue                    # Create/edit lead
│   │   │   │   ├── LeadDetail.vue                  # Full lead info + timeline
│   │   │   │   ├── LeadFunnel.vue                  # Visual funnel chart
│   │   │   │   ├── LeadChannels.vue                # Pie chart by channels
│   │   │   │   └── LeadRouting.vue                 # Routing rules config
│   │   │   │
│   │   │   ├── ads/
│   │   │   │   ├── AdsOverview.vue                 # Unified table + graphs
│   │   │   │   ├── AdsCampaigns.vue                # Campaigns per platform
│   │   │   │   ├── AdsBudget.vue                   # Budget planner
│   │   │   │   └── AdsReports.vue                  # Weekly/monthly export
│   │   │   │
│   │   │   ├── seo/
│   │   │   │   ├── SEOOverview.vue                 # 4 domains KPI
│   │   │   │   ├── GSCDashboard.vue                # Search Console data
│   │   │   │   ├── GA4Dashboard.vue                # Analytics data
│   │   │   │   ├── KeywordsTracker.vue             # Top-50 positions daily
│   │   │   │   ├── BacklinksMonitor.vue            # New/lost backlinks
│   │   │   │   ├── SEONetwork.vue                  # 8 satellite sites
│   │   │   │   └── CompetitorsView.vue             # DA + positions
│   │   │   │
│   │   │   ├── social/
│   │   │   │   ├── SocialDashboard.vue             # 8 platforms overview
│   │   │   │   ├── SocialPostCreator.vue           # AI + cross-posting
│   │   │   │   ├── ThreadsManager.vue              # Threads-specific features
│   │   │   │   ├── UnifiedInbox.vue                # All DMs/comments
│   │   │   │   ├── ContentCalendar.vue             # Visual calendar
│   │   │   │   └── SocialAnalytics.vue             # Per-platform analytics
│   │   │   │
│   │   │   ├── brand/
│   │   │   │   ├── TrademarkStatus.vue             # UPRP + EUIPO
│   │   │   │   ├── BusinessListings.vue            # 50+ catalogs + NAP check
│   │   │   │   ├── ReviewsHub.vue                  # All review platforms
│   │   │   │   ├── ReviewRequests.vue              # Auto chain stats
│   │   │   │   └── KnowledgePanel.vue              # Wikipedia, Wikidata
│   │   │   │
│   │   │   ├── clients/
│   │   │   │   ├── ClientsList.vue
│   │   │   │   ├── ClientProfile.vue
│   │   │   │   └── ClientSegmentation.vue
│   │   │   │
│   │   │   ├── cases/
│   │   │   │   ├── CasesKanban.vue
│   │   │   │   ├── CaseDetail.vue
│   │   │   │   └── HearingsCalendar.vue
│   │   │   │
│   │   │   ├── finance/
│   │   │   │   ├── InvoicesList.vue
│   │   │   │   ├── PaymentsList.vue
│   │   │   │   └── FinanceReports.vue
│   │   │   │
│   │   │   ├── landings/
│   │   │   │   ├── LandingsOverview.vue            # 14+ pages, 4 domains
│   │   │   │   ├── LandingForms.vue                # Form submissions
│   │   │   │   ├── ABTests.vue                     # A/B testing
│   │   │   │   └── PageSpeed.vue                   # Performance scores
│   │   │   │
│   │   │   ├── communications/
│   │   │   │   ├── ChatView.vue
│   │   │   │   ├── WhatsAppChat.vue
│   │   │   │   ├── TelegramChat.vue
│   │   │   │   └── EmailView.vue
│   │   │   │
│   │   │   ├── analytics/
│   │   │   │   ├── AnalyticsDashboard.vue
│   │   │   │   └── ReportBuilder.vue
│   │   │   │
│   │   │   ├── settings/
│   │   │   │   ├── APIKeysManager.vue
│   │   │   │   ├── UsersRoles.vue
│   │   │   │   ├── DomainsConfig.vue
│   │   │   │   └── N8NWorkflows.vue
│   │   │   │
│   │   │   └── ui/
│   │   │       ├── AppSidebar.vue                  # Main navigation
│   │   │       ├── AppHeader.vue
│   │   │       ├── AppFooter.vue
│   │   │       ├── DataTable.vue
│   │   │       ├── ChartWrapper.vue
│   │   │       ├── KPICard.vue
│   │   │       └── WidgetContainer.vue
│   │   │
│   │   ├── composables/
│   │   │   ├── useLeads.js
│   │   │   ├── useAds.js
│   │   │   ├── useSEO.js
│   │   │   ├── useDashboard.js
│   │   │   ├── useSocial.js
│   │   │   └── useWebSocket.js                     # Laravel Echo + Reverb (native WebSocket)
│   │   │
│   │   ├── router/
│   │   │   └── index.js                            # All routes
│   │   │
│   │   ├── stores/                                 # Pinia stores
│   │   │   ├── auth.js
│   │   │   ├── leads.js
│   │   │   ├── ads.js
│   │   │   ├── seo.js
│   │   │   ├── dashboard.js
│   │   │   ├── social.js
│   │   │   ├── clients.js
│   │   │   ├── cases.js
│   │   │   └── notifications.js
│   │   │
│   │   ├── utils/
│   │   │   ├── formatters.js                       # Date, currency, number formatters
│   │   │   ├── validators.js
│   │   │   └── constants.js                        # Enums, colors, labels
│   │   │
│   │   ├── App.vue
│   │   └── main.js
│   │
│   ├── public/
│   │   └── index.html
│   ├── package.json
│   ├── vite.config.js
│   ├── tailwind.config.js
│   └── README.md
│
├── mobile/                                         # Flutter 3.29+ Mobile App (Dart 3.7+)
│   ├── lib/
│   │   ├── main.dart
│   │   ├── app.dart
│   │   │
│   │   ├── config/
│   │   │   ├── app_config.dart
│   │   │   ├── api_config.dart
│   │   │   ├── routes.dart
│   │   │   └── theme.dart
│   │   │
│   │   ├── models/
│   │   │   ├── lead_model.dart
│   │   │   ├── client_model.dart
│   │   │   ├── case_model.dart
│   │   │   ├── kpi_model.dart
│   │   │   ├── ads_performance_model.dart
│   │   │   ├── social_account_model.dart
│   │   │   ├── notification_model.dart
│   │   │   └── user_model.dart
│   │   │
│   │   ├── services/
│   │   │   ├── api_service.dart                    # HTTP client + auth
│   │   │   ├── lead_service.dart
│   │   │   ├── dashboard_service.dart
│   │   │   ├── notification_service.dart
│   │   │   ├── push_notification_service.dart
│   │   │   └── websocket_service.dart
│   │   │
│   │   ├── providers/
│   │   │   ├── lead_provider.dart
│   │   │   ├── dashboard_provider.dart
│   │   │   ├── auth_provider.dart
│   │   │   └── notification_provider.dart
│   │   │
│   │   ├── screens/
│   │   │   ├── auth/
│   │   │   │   ├── login_screen.dart
│   │   │   │   └── pin_screen.dart
│   │   │   ├── dashboard/
│   │   │   │   ├── dashboard_screen.dart           # Mobile KPI + quick actions
│   │   │   │   └── kpi_cards_widget.dart
│   │   │   ├── leads/
│   │   │   │   ├── leads_list_screen.dart
│   │   │   │   ├── lead_detail_screen.dart
│   │   │   │   └── lead_create_screen.dart
│   │   │   ├── clients/
│   │   │   │   ├── clients_list_screen.dart
│   │   │   │   └── client_detail_screen.dart
│   │   │   ├── cases/
│   │   │   │   ├── cases_list_screen.dart
│   │   │   │   └── case_detail_screen.dart
│   │   │   ├── notifications/
│   │   │   │   └── notifications_screen.dart
│   │   │   └── settings/
│   │   │       └── settings_screen.dart
│   │   │
│   │   └── widgets/
│   │       ├── kpi_card.dart
│   │       ├── lead_card.dart
│   │       ├── case_card.dart
│   │       ├── notification_tile.dart
│   │       └── chart_widget.dart
│   │
│   ├── android/
│   ├── ios/
│   ├── pubspec.yaml
│   └── README.md
│
├── n8n-workflows/                                  # 22 n8n Workflows (JSON exports)
│   ├── W01_lead_processing.json                    # Webhook → Lead routing → all notifications
│   ├── W02_lead_followup.json                      # Cron 30min → no contact check → alert
│   ├── W03_lead_weekly_report.json                 # Cron Mon 9:00
│   ├── W04_google_ads_sync.json                    # Cron every 6h
│   ├── W05_meta_ads_sync.json                      # Cron every 6h
│   ├── W06_tiktok_ads_sync.json                    # Cron every 6h
│   ├── W07_pinterest_youtube_ads_sync.json         # Cron every 12h
│   ├── W08_gsc_ga4_daily_sync.json                 # Cron 6:00 (4 domains)
│   ├── W09_seo_weekly_report.json                  # Cron Mon 8:00
│   ├── W10_google_reviews_monitor.json             # Cron every 2h
│   ├── W11_social_autopost.json                    # Scheduled: content_calendar → 8 platforms
│   ├── W12_ai_content_generation.json              # HTTP Trigger → OpenAI
│   ├── W13_whatsapp_auto_reply.json                # Webhook → WhatsApp Cloud API
│   ├── W14_telegram_bot_handler.json               # Webhook → Telegram Bot API
│   ├── W15_review_request_chain.json               # Event: case completed → review request
│   ├── W16_monthly_report.json                     # Cron 1st of month 9:00
│   ├── W17_offline_conversion_google.json          # Event: lead.status=paid → Google Ads
│   ├── W18_facebook_capi_events.json               # Event: lead.created → Meta CAPI
│   ├── W19_tiktok_events_api.json                  # Event: lead.created → TikTok Events
│   ├── W20_seo_network_article_check.json          # Cron weekly
│   ├── W21_nap_consistency_check.json              # Cron monthly (4 domains)
│   └── W22_threads_autopost_analytics.json         # Cron: content_calendar + analytics 12h
│
├── landings/                                       # Landing Pages (4 domains)
│   ├── wincase-pro/                                # wincase.pro — Laravel Blade
│   │   ├── resources/views/landings/
│   │   │   ├── index.blade.php                     # PL main
│   │   │   ├── ru/karta-pobytu.blade.php           # RU landing
│   │   │   ├── ua/karta-pobytu.blade.php           # UA landing
│   │   │   ├── en/work-permit.blade.php            # EN landing
│   │   │   ├── hi/index.blade.php                  # Hindi landing
│   │   │   ├── tl/index.blade.php                  # Tagalog landing
│   │   │   ├── es/index.blade.php                  # Spanish landing
│   │   │   ├── tr/index.blade.php                  # Turkish landing
│   │   │   ├── consultation.blade.php              # Multi-lang booking
│   │   │   ├── checklist.blade.php                 # Documents checklist
│   │   │   ├── reviews.blade.php                   # Reviews page
│   │   │   └── blog/
│   │   │       └── index.blade.php                 # Blog listing
│   │   └── public/assets/landings/
│   │
│   ├── wincase-legalization/                       # wincase-legalization.com — A/B test mirror
│   │   └── resources/views/
│   │       ├── index.blade.php
│   │       └── variants/
│   │           ├── a.blade.php
│   │           └── b.blade.php
│   │
│   ├── wincase-job/                                # wincase-job.com — Vue.js SPA
│   │   └── src/
│   │       ├── views/
│   │       │   ├── JobSearch.vue
│   │       │   ├── JobDetail.vue
│   │       │   ├── CityMap.vue
│   │       │   └── EmployerProfile.vue
│   │       └── components/
│   │
│   └── wincase-org/                                # wincase.org — Corporate + future SaaS
│       └── resources/views/
│           ├── about.blade.php
│           ├── team.blade.php
│           ├── press.blade.php
│           └── investors.blade.php
│
├── docs/                                           # Documentation
│   ├── WINCASE_CRM_v4_FINAL_UA.docx               # Original spec (Ukrainian)
│   ├── API_REFERENCE.md                            # All 30+ endpoints documentation
│   ├── DATABASE_SCHEMA.md                          # 20+ tables (13 existing + 7 new)
│   ├── N8N_WORKFLOWS.md                            # 22 workflows documentation
│   ├── DEPLOYMENT.md                               # VPS Hostinger setup
│   ├── ENV_TEMPLATE.md                             # All 21 API keys guide
│   └── CHANGELOG.md                                # Version history
│
├── docker/
│   ├── docker-compose.yml                          # Laravel 12 + MySQL 8.4 + Redis 7.4 + n8n + Reverb
│   ├── nginx/
│   │   └── default.conf                            # php8.4-fpm.sock
│   ├── php/
│   │   └── Dockerfile                              # PHP 8.4-fpm + extensions
│   ├── reverb/
│   │   └── supervisord.conf                        # Laravel Reverb WebSocket process
│   └── n8n/
│       └── Dockerfile
│
├── .gitignore
├── .env.example
├── Makefile                                        # make install, make deploy, make test
└── README.md
```

---

## STATISTICS

| Metric                  | Count  |
|-------------------------|--------|
| **Total Files**         | ~250+  |
| **Backend (Laravel)**   | ~130   |
| **Frontend (Vue.js)**   | ~70    |
| **Mobile (Flutter)**    | ~35    |
| **n8n Workflows**       | 22     |
| **Landing Pages**       | 14+    |
| **DB Tables (existing)**| 14     |
| **DB Tables (new v4)**  | 7      |
| **DB Tables (total)**   | 21     |
| **API Endpoints**       | 30+    |
| **Services**            | 35+    |
| **Vue Components**      | 55+    |
| **Domains**             | 4      |
| **Social Platforms**    | 8      |
| **Languages (i18n)**    | 8 (PL, EN, RU, UA, HI, TL, ES, TR) |
| **API Keys/Tokens**     | 21     |

---

<!-- Аннотация (RU):
Этот файл содержит полную древовидную структуру проекта WINCASE CRM v4.0.
Проект состоит из 4 основных частей:
1. Backend — Laravel 12 + PHP 8.4 (API + Admin Panel) с 30+ endpoints, 35+ сервисов, 21 таблицей БД
2. Frontend — Vue.js 3.5 SPA (Vite 7, Pinia 3, TypeScript) с 55+ компонентами для всех модулей CRM
3. Mobile — Flutter приложение для iOS/Android с основными экранами управления
4. n8n Workflows — 22 автоматизации для лидов, рекламы, SEO, соцсетей, отчётов
Дополнительно: 4 домена с 14+ лендингами на 8 языках, Docker конфигурация.
Файл создан: 2026-02-16 | Версия: 4.0 FINAL
-->
