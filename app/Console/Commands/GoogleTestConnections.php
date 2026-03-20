<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Test all Google API connections.
 *
 * Usage: php artisan google:test-connections
 */
class GoogleTestConnections extends Command
{
    protected $signature = 'google:test-connections';
    protected $description = 'Test all Google API connections (GA4, GSC, Ads, GBP, YouTube)';

    public function handle(): int
    {
        $this->info('=== Testing Google API Connections ===');
        $this->line('');

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $refreshToken = config('services.google.refresh_token');

        // Check credentials exist
        $this->line('1. Credentials check:');
        $this->line('   Client ID: ' . ($clientId ? substr($clientId, 0, 20) . '...' : 'NOT SET'));
        $this->line('   Client Secret: ' . ($clientSecret ? substr($clientSecret, 0, 8) . '...' : 'NOT SET'));
        $this->line('   Refresh Token: ' . ($refreshToken ? substr($refreshToken, 0, 15) . '...' : 'NOT SET'));
        $this->line('   Maps API Key: ' . (config('services.google.api_key') ? 'SET' : 'NOT SET'));
        $this->line('');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            $this->error('Missing credentials. Run: php artisan google:oauth-setup');
            return Command::FAILURE;
        }

        // Get access token
        $this->line('2. Getting access token...');
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if (!$tokenResponse->successful()) {
            $this->error('   FAILED: ' . $tokenResponse->body());
            return Command::FAILURE;
        }

        $accessToken = $tokenResponse->json('access_token');
        $this->info('   OK — token obtained');
        $this->line('');

        $results = [];

        // Test GA4
        $this->line('3. Google Analytics 4 (GA4):');
        $propertyId = config('services.google_analytics.property_id', '464011074');
        $ga4 = Http::withToken($accessToken)
            ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$propertyId}:runReport", [
                'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
                'metrics' => [['name' => 'totalUsers'], ['name' => 'sessions']],
            ]);
        if ($ga4->successful()) {
            $rows = $ga4->json('rows') ?? [];
            $users = $rows[0]['metricValues'][0]['value'] ?? 0;
            $sessions = $rows[0]['metricValues'][1]['value'] ?? 0;
            $this->info("   OK — Property {$propertyId}: {$users} users, {$sessions} sessions (7d)");
            $results['ga4'] = true;
        } else {
            $this->error("   FAIL [{$ga4->status()}]: " . substr($ga4->body(), 0, 200));
            $results['ga4'] = false;
        }

        // Test per-domain GA4 properties
        foreach (['wincase_pro', 'legalization', 'job', 'org'] as $key) {
            $pid = config("services.ga4.property_{$key}");
            if ($pid) {
                $this->line("   Domain property {$key}: {$pid}");
            }
        }
        $this->line('');

        // Test GSC
        $this->line('4. Google Search Console (GSC):');
        $gsc = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/webmasters/v3/sites');
        if ($gsc->successful()) {
            $sites = $gsc->json('siteEntry') ?? [];
            $this->info('   OK — ' . count($sites) . ' verified sites:');
            foreach ($sites as $site) {
                $this->line('     - ' . ($site['siteUrl'] ?? '?') . ' (' . ($site['permissionLevel'] ?? '?') . ')');
            }
            $results['gsc'] = true;
        } else {
            $this->error("   FAIL [{$gsc->status()}]: " . substr($gsc->body(), 0, 200));
            $results['gsc'] = false;
        }
        $this->line('');

        // Test Google Ads
        $this->line('5. Google Ads:');
        $adsCustomerId = config('services.google_ads.customer_id');
        $adsDevToken = config('services.google_ads.developer_token');
        $adsRefreshToken = config('services.google_ads.refresh_token') ?: $refreshToken;

        if (!$adsCustomerId || !$adsDevToken) {
            $this->warn('   SKIP — customer_id or developer_token not configured');
            $this->line('   Set: GOOGLE_ADS_CUSTOMER_ID, GOOGLE_ADS_DEVELOPER_TOKEN in .env');
            $results['google_ads'] = null;
        } else {
            // Get ads-specific access token if different refresh token
            $adsAccessToken = $accessToken;
            if ($adsRefreshToken !== $refreshToken) {
                $adsTokenResp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'refresh_token' => $adsRefreshToken,
                    'grant_type' => 'refresh_token',
                ]);
                $adsAccessToken = $adsTokenResp->json('access_token') ?? $accessToken;
            }

            $cleanCustomerId = str_replace('-', '', $adsCustomerId);
            $adsTest = Http::withHeaders([
                'Authorization' => "Bearer {$adsAccessToken}",
                'developer-token' => $adsDevToken,
            ])->post("https://googleads.googleapis.com/v17/customers/{$cleanCustomerId}/googleAds:searchStream", [
                'query' => "SELECT customer.descriptive_name, customer.id FROM customer LIMIT 1",
            ]);

            if ($adsTest->successful()) {
                $name = $adsTest->json('0.results.0.customer.descriptiveName') ?? 'Unknown';
                $this->info("   OK — Account: {$name} (ID: {$adsCustomerId})");
                $results['google_ads'] = true;
            } else {
                $this->error("   FAIL [{$adsTest->status()}]: " . substr($adsTest->body(), 0, 200));
                $results['google_ads'] = false;
            }
        }
        $this->line('');

        // Test Google Business Profile / Places
        $this->line('6. Google Business Profile (Places API):');
        $apiKey = config('services.google.api_key');
        $placeId = config('services.google.place_id');

        if (!$apiKey) {
            $this->warn('   SKIP — GOOGLE_MAPS_API_KEY not set');
            $results['gbp'] = null;
        } else {
            $places = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'key' => $apiKey,
                'fields' => 'name,rating,user_ratings_total,reviews',
            ]);

            if ($places->successful() && $places->json('status') === 'OK') {
                $result = $places->json('result');
                $name = $result['name'] ?? '?';
                $rating = $result['rating'] ?? 0;
                $total = $result['user_ratings_total'] ?? 0;
                $reviewCount = count($result['reviews'] ?? []);
                $this->info("   OK — {$name}: {$rating}/5 ({$total} reviews, {$reviewCount} loaded)");
                $results['gbp'] = true;
            } else {
                $status = $places->json('status') ?? $places->status();
                $this->error("   FAIL: {$status} — " . substr($places->body(), 0, 200));
                $results['gbp'] = false;
            }
        }
        $this->line('');

        // Test YouTube
        $this->line('7. YouTube:');
        $yt = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => 'snippet,statistics',
                'mine' => 'true',
            ]);
        if ($yt->successful()) {
            $channels = $yt->json('items') ?? [];
            if (count($channels) > 0) {
                $ch = $channels[0];
                $name = $ch['snippet']['title'] ?? '?';
                $subs = $ch['statistics']['subscriberCount'] ?? 0;
                $this->info("   OK — Channel: {$name} ({$subs} subscribers)");
            } else {
                $this->warn('   OK — No YouTube channel linked to this account');
            }
            $results['youtube'] = true;
        } else {
            $this->error("   FAIL [{$yt->status()}]: " . substr($yt->body(), 0, 200));
            $results['youtube'] = false;
        }
        $this->line('');

        // Summary
        $this->info('=== Summary ===');
        foreach ($results as $service => $ok) {
            $status = $ok === true ? 'OK' : ($ok === null ? 'SKIP' : 'FAIL');
            $icon = $ok === true ? '[+]' : ($ok === null ? '[~]' : '[-]');
            $this->line("  {$icon} {$service}: {$status}");
        }

        return Command::SUCCESS;
    }
}
