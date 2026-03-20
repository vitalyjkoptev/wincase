<?php

// =====================================================
// FILE: config/ads.php
// Advertising platforms configuration
// =====================================================

return [

    /*
    |--------------------------------------------------------------------------
    | Monthly Budget Plan (PLN)
    |--------------------------------------------------------------------------
    | Planned monthly spend per platform. Used in Budget Analysis.
    | Update these values as your ad strategy changes.
    */
    'budget_plan' => [
        'google_ads'    => (float) env('ADS_BUDGET_GOOGLE', 3000),
        'meta_ads'      => (float) env('ADS_BUDGET_META', 2000),
        'tiktok_ads'    => (float) env('ADS_BUDGET_TIKTOK', 1500),
        'pinterest_ads' => (float) env('ADS_BUDGET_PINTEREST', 500),
        'youtube_ads'   => (float) env('ADS_BUDGET_YOUTUBE', 1000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync Configuration
    |--------------------------------------------------------------------------
    */
    'sync' => [
        'default_lookback_days' => 2,
        'max_lookback_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | Offline Conversions
    |--------------------------------------------------------------------------
    */
    'google_conversion_action' => env('GOOGLE_ADS_CONVERSION_ACTION_ID', ''),
    'facebook_capi_test_code'  => env('META_CAPI_TEST_CODE', ''),
    'tiktok_event_set_id'      => env('TIKTOK_EVENT_SET_ID', ''),

];

// ---------------------------------------------------------------
// Аннотация (RU):
// Конфигурация рекламных платформ.
// budget_plan — плановый месячный бюджет по платформам (PLN).
// sync — настройки синхронизации (lookback 2 дня по умолчанию).
// Offline conversions IDs для Google, Facebook CAPI, TikTok Events.
// Файл: config/ads.php
// ---------------------------------------------------------------
