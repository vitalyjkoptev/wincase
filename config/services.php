<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID', 'wincase-7206c'),
        'credentials_path' => env('FIREBASE_CREDENTIALS_PATH', storage_path('firebase-service-account.json')),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'admin_chat_id' => env('TELEGRAM_ADMIN_CHAT_ID'),
        'channel_id' => env('TELEGRAM_CHANNEL_ID'),
    ],

    'whatsapp' => [
        'token' => env('WHATSAPP_TOKEN'),
        'phone_id' => env('WHATSAPP_PHONE_ID'),
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v21.0'),
    ],

    'smsapi' => [
        'token' => env('SMSAPI_TOKEN'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],

    // =====================================================
    // GOOGLE — shared OAuth 2.0 credentials (GA4, GSC, Ads, YouTube)
    // =====================================================

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
        'api_key' => env('GOOGLE_MAPS_API_KEY'),         // Maps/Places API key
        'place_id' => env('GOOGLE_PLACE_ID', 'ChIJd6uSdZnNHkcRBIQUdlchCgM'),
    ],

    // GA4 Measurement Protocol (для отправки событий из бекенда)
    'google_analytics' => [
        'measurement_id' => env('GA_MEASUREMENT_ID', 'G-2839K9SY95'),
        'api_secret' => env('GA_API_SECRET', 'ctq-lC0ISXqYq35pIOxDRQ'),
        'property_id' => env('GA_PROPERTY_ID', '464011074'),
        'stream_id' => env('GA_STREAM_ID', '13901640560'),
    ],

    // GA4 Data API — property IDs для каждого домена (для чтения аналитики)
    'ga4' => [
        'property_wincase_pro' => env('GA4_PROPERTY_WINCASE_PRO'),
        'property_legalization' => env('GA4_PROPERTY_LEGALIZATION'),
        'property_job' => env('GA4_PROPERTY_JOB'),
        'property_org' => env('GA4_PROPERTY_ORG'),
    ],

    // Google Ads API v17
    'google_ads' => [
        'customer_id' => env('GOOGLE_ADS_CUSTOMER_ID'),
        'developer_token' => env('GOOGLE_ADS_DEVELOPER_TOKEN'),
        'refresh_token' => env('GOOGLE_ADS_REFRESH_TOKEN'),  // может отличаться от общего
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    // =====================================================
    // SOCIAL MEDIA PLATFORMS
    // =====================================================

    'facebook' => [
        'page_id' => env('FACEBOOK_PAGE_ID'),
        'page_token' => env('FACEBOOK_ACCESS_TOKEN'),
    ],

    'instagram' => [
        'user_id' => env('INSTAGRAM_BUSINESS_ID'),
        'token' => env('FACEBOOK_ACCESS_TOKEN'),  // Instagram uses Facebook Graph API
    ],

    'youtube' => [
        'channel_id' => env('YOUTUBE_CHANNEL_ID'),
        'api_key' => env('YOUTUBE_API_KEY'),
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),  // shared Google OAuth
    ],

    'linkedin' => [
        'access_token' => env('LINKEDIN_ACCESS_TOKEN'),
        'organization_id' => env('LINKEDIN_COMPANY_ID'),
    ],

    'tiktok' => [
        'creator_token' => env('TIKTOK_ACCESS_TOKEN'),
        'access_token' => env('TIKTOK_ACCESS_TOKEN'),
        'advertiser_id' => env('TIKTOK_ADVERTISER_ID'),
        'pixel_id' => env('TIKTOK_PIXEL_ID'),
        'business_id' => env('TIKTOK_BUSINESS_ID'),
    ],

    'pinterest' => [
        'access_token' => env('PINTEREST_ACCESS_TOKEN'),
        'board_id' => env('PINTEREST_BOARD_ID'),
        'ad_account_id' => env('PINTEREST_AD_ACCOUNT_ID'),
    ],

    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'api_secret' => env('TWITTER_API_SECRET'),
        'access_token' => env('TWITTER_ACCESS_TOKEN'),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
    ],

    'threads' => [
        'token' => env('THREADS_TOKEN'),
        'user_id' => env('THREADS_USER_ID'),
        'api_url' => env('THREADS_API_URL', 'https://graph.threads.net/v1.0'),
    ],

    // =====================================================
    // ADVERTISING PLATFORMS
    // =====================================================

    'meta' => [
        'access_token' => env('META_ACCESS_TOKEN'),
        'ad_account_id' => env('META_AD_ACCOUNT_ID'),
        'system_user_token' => env('META_ACCESS_TOKEN'),
        'pixel_id' => env('META_PIXEL_ID'),
        'app_id' => env('META_APP_ID'),
        'app_secret' => env('META_APP_SECRET'),
    ],

    'meta_ads' => [
        'token' => env('META_ACCESS_TOKEN'),
        'ad_account_id' => env('META_AD_ACCOUNT_ID'),
        'pixel_id' => env('META_PIXEL_ID'),
        'api_url' => env('META_ADS_API_URL', 'https://graph.facebook.com/v21.0'),
    ],

    // =====================================================
    // SEO & REVIEWS
    // =====================================================

    'ahrefs' => [
        'api_key' => env('AHREFS_API_KEY'),
    ],

    'trustpilot' => [
        'business_id' => env('TRUSTPILOT_BUSINESS_ID'),
        'api_key' => env('TRUSTPILOT_API_KEY'),
    ],

    // google_places — алиас, ReviewsHubService использует config('services.google.place_id')
    'google_places' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
        'place_id' => env('GOOGLE_PLACE_ID', 'ChIJd6uSdZnNHkcRBIQUdlchCgM'),
    ],

    // =====================================================
    // AI REWRITER
    // =====================================================

    'ai_rewriter' => [
        'provider' => env('AI_REWRITER_PROVIDER', 'anthropic'),
        'model' => env('AI_REWRITER_MODEL', 'claude-sonnet-4-5-20250929'),
        'openai_model' => env('AI_REWRITER_OPENAI_MODEL', 'gpt-4o'),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('AI_REWRITER_OPENAI_MODEL', 'gpt-4o'),
    ],

    // =====================================================
    // IDENTITY VERIFICATION (mObywatel / Profil Zaufany via Authologic)
    // =====================================================

    'authologic' => [
        'api_key' => env('AUTHOLOGIC_API_KEY'),
        'api_secret' => env('AUTHOLOGIC_API_SECRET'),
        'sandbox' => env('AUTHOLOGIC_SANDBOX', true),
        'api_url' => env('AUTHOLOGIC_SANDBOX', true)
            ? 'https://sandbox.authologic.com/api'
            : 'https://api.authologic.com/api',
        'strategy' => env('AUTHOLOGIC_STRATEGY', 'public:sandbox'),
        'return_url' => env('AUTHOLOGIC_RETURN_URL', '/verification/complete'),
        'callback_url' => env('AUTHOLOGIC_CALLBACK_URL', '/api/v1/verification/callback'),
    ],

    // =====================================================
    // WORKFLOW & AUTOMATION
    // =====================================================

    'n8n' => [
        'url' => env('N8N_URL', 'https://n8n.wincase.eu'),
        'api_key' => env('N8N_API_KEY'),
        'user' => env('N8N_USER'),
        'password' => env('N8N_PASSWORD'),
        'public_url' => env('N8N_PUBLIC_URL'),
    ],

    // =====================================================
    // PAYMENT SYSTEMS
    // =====================================================

    'stripe' => [
        'key' => env('STRIPE_KEY'),               // pk_live_... (publishable key for frontend)
        'secret' => env('STRIPE_SECRET'),          // sk_live_... (secret key for backend)
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'), // whsec_... (for verifying webhooks)
        'currency' => env('STRIPE_CURRENCY', 'pln'),
    ],

    'przelewy24' => [
        'merchant_id' => env('P24_MERCHANT_ID'),   // ID sprzedawcy
        'pos_id' => env('P24_POS_ID'),             // ID punktu (usually same as merchant_id)
        'api_key' => env('P24_API_KEY'),           // Klucz API (from panel)
        'crc' => env('P24_CRC'),                   // CRC key (for signature verification)
        'sandbox' => env('P24_SANDBOX', false),    // true = sandbox.przelewy24.pl
        'api_url' => env('P24_API_URL', 'https://secure.przelewy24.pl/api/v1'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'sandbox' => env('PAYPAL_SANDBOX', false), // true = sandbox mode
        'api_url' => env('PAYPAL_SANDBOX', false)
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com',
        'currency' => env('PAYPAL_CURRENCY', 'PLN'),
    ],

];
