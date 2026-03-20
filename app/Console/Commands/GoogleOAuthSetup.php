<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Artisan command to get Google OAuth 2.0 refresh token.
 *
 * Usage:
 *   1. First create OAuth credentials in Google Cloud Console
 *   2. Run: php artisan google:oauth-setup
 *   3. Open the URL in browser, authorize, copy the code
 *   4. Paste the code — command will exchange it for refresh_token
 *   5. Add refresh_token to .env
 *
 * Scopes included:
 *   - Google Analytics Data API (GA4)
 *   - Google Search Console
 *   - Google Ads API
 *   - YouTube Data + Analytics
 *   - Google Business Profile
 */
class GoogleOAuthSetup extends Command
{
    protected $signature = 'google:oauth-setup
                            {--client-id= : Google OAuth Client ID (or uses .env GOOGLE_CLIENT_ID)}
                            {--client-secret= : Google OAuth Client Secret (or uses .env GOOGLE_CLIENT_SECRET)}
                            {--exchange-code= : Authorization code to exchange for refresh token}';

    protected $description = 'Setup Google OAuth 2.0 — get refresh token for GA4, GSC, Ads, GBP, YouTube';

    // All scopes needed for WinCase CRM integrations
    protected array $scopes = [
        // Google Analytics 4 Data API
        'https://www.googleapis.com/auth/analytics.readonly',
        // Google Search Console
        'https://www.googleapis.com/auth/webmasters.readonly',
        // Google Ads API
        'https://www.googleapis.com/auth/adwords',
        // YouTube Data API + Analytics
        'https://www.googleapis.com/auth/youtube.readonly',
        'https://www.googleapis.com/auth/yt-analytics.readonly',
        // Google Business Profile (GBP)
        'https://www.googleapis.com/auth/business.manage',
    ];

    public function handle(): int
    {
        $clientId = $this->option('client-id') ?: config('services.google.client_id');
        $clientSecret = $this->option('client-secret') ?: config('services.google.client_secret');

        if (!$clientId || !$clientSecret) {
            $this->error('Google Client ID and Secret required.');
            $this->line('');
            $this->info('Set in .env:');
            $this->line('  GOOGLE_CLIENT_ID=your_client_id');
            $this->line('  GOOGLE_CLIENT_SECRET=your_client_secret');
            $this->line('');
            $this->info('Or pass as options:');
            $this->line('  php artisan google:oauth-setup --client-id=xxx --client-secret=yyy');
            return Command::FAILURE;
        }

        // If exchange code provided — go straight to token exchange
        if ($code = $this->option('exchange-code')) {
            return $this->exchangeCode($clientId, $clientSecret, $code);
        }

        // Step 1: Show instructions
        $this->info('');
        $this->info('=== Google OAuth 2.0 Setup for WinCase CRM ===');
        $this->info('');
        $this->line('Step 1: Open this URL in your browser:');
        $this->line('');

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => 'http://localhost',
            'response_type' => 'code',
            'scope' => implode(' ', $this->scopes),
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        $this->line($authUrl);
        $this->line('');
        $this->line('Step 2: Sign in with wincasepro@gmail.com');
        $this->line('Step 3: Allow all permissions');
        $this->line('Step 4: Copy the authorization code');
        $this->line('');

        $code = $this->ask('Paste the authorization code here');

        if (!$code) {
            $this->error('No code provided.');
            return Command::FAILURE;
        }

        return $this->exchangeCode($clientId, $clientSecret, $code);
    }

    protected function exchangeCode(string $clientId, string $clientSecret, string $code): int
    {
        $this->info('Exchanging code for refresh token...');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => 'http://localhost',
            'grant_type' => 'authorization_code',
        ]);

        if (!$response->successful()) {
            $this->error('Token exchange failed:');
            $this->line($response->body());
            return Command::FAILURE;
        }

        $data = $response->json();
        $refreshToken = $data['refresh_token'] ?? null;
        $accessToken = $data['access_token'] ?? null;

        if (!$refreshToken) {
            $this->error('No refresh_token in response. Try adding prompt=consent.');
            $this->line(json_encode($data, JSON_PRETTY_PRINT));
            return Command::FAILURE;
        }

        $this->info('');
        $this->info('=== SUCCESS ===');
        $this->line('');
        $this->line('Add these to your .env:');
        $this->line('');
        $this->line("GOOGLE_REFRESH_TOKEN={$refreshToken}");
        $this->line('');

        // Test: get user info
        if ($accessToken) {
            $this->testAccess($accessToken);
        }

        $this->info('');
        $this->info('=== Next steps ===');
        $this->line('1. Add GOOGLE_REFRESH_TOKEN to .env on server');
        $this->line('2. Enable APIs in Google Cloud Console:');
        $this->line('   - Google Analytics Data API');
        $this->line('   - Google Search Console API');
        $this->line('   - Google Ads API');
        $this->line('   - Google My Business API');
        $this->line('   - YouTube Data API v3');
        $this->line('   - Maps JavaScript API + Places API');
        $this->line('3. For Google Ads: apply for developer token at ads.google.com/aw/apicenter');
        $this->line('4. Run: php artisan google:test-connections');
        $this->line('');

        return Command::SUCCESS;
    }

    protected function testAccess(string $accessToken): void
    {
        $this->info('Testing access...');

        // Test GA4
        $propertyId = config('services.google_analytics.property_id', '464011074');
        $ga4 = Http::withToken($accessToken)
            ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$propertyId}:runReport", [
                'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
                'metrics' => [['name' => 'totalUsers']],
            ]);
        $this->line('  GA4 (property ' . $propertyId . '): ' . ($ga4->successful() ? 'OK' : 'FAIL (' . $ga4->status() . ')'));

        // Test GSC
        $gsc = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/webmasters/v3/sites');
        $this->line('  GSC (list sites): ' . ($gsc->successful() ? 'OK — ' . count($gsc->json('siteEntry') ?? []) . ' sites' : 'FAIL (' . $gsc->status() . ')'));

        // Test Google Ads (basic — needs developer token too)
        $this->line('  Google Ads: requires developer token (test separately)');

        // Test YouTube
        $yt = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => 'snippet',
                'mine' => 'true',
            ]);
        $this->line('  YouTube: ' . ($yt->successful() ? 'OK' : 'FAIL (' . $yt->status() . ')'));
    }
}
