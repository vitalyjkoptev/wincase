<?php
/**
 * Generate n8n-importable JSON for all 35 WinCase CRM workflows.
 * Run: php n8n/generate_workflows.php
 * Then import each JSON via n8n UI → Workflows → Import from File.
 */

$API_URL = 'https://crm.wincase.eu';
$TELEGRAM_BOT_TOKEN = '8708170857:AAHhlDNDp2lqzcki3PNN_aVJTfTd6HK1Eew';
$TELEGRAM_ADMIN_CHAT = '7627104580';
$BOSS_EMAIL = 'wincasetop@gmail.com';

$outputDir = __DIR__ . '/workflows';
if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

// Helper: create n8n workflow JSON structure
function makeWorkflow(string $name, array $nodes, array $connections = [], array $settings = []): array
{
    return [
        'name' => $name,
        'nodes' => $nodes,
        'connections' => $connections,
        'active' => false,
        'settings' => array_merge(['executionOrder' => 'v1'], $settings),
        'tags' => [],
    ];
}

function cronNode(string $cron, int $x = 250, int $y = 300): array
{
    return [
        'parameters' => ['rule' => ['interval' => [['field' => 'cronExpression', 'expression' => $cron]]]],
        'name' => 'Schedule Trigger',
        'type' => 'n8n-nodes-base.scheduleTrigger',
        'typeVersion' => 1.2,
        'position' => [$x, $y],
    ];
}

function httpNode(string $name, string $method, string $url, array $body = [], int $x = 500, int $y = 300, array $headers = []): array
{
    $params = [
        'method' => $method,
        'url' => $url,
        'options' => [],
        'sendHeaders' => true,
        'headerParameters' => ['parameters' => array_merge(
            [['name' => 'Accept', 'value' => 'application/json']],
            $headers
        )],
    ];
    if ($body) {
        $params['sendBody'] = true;
        $params['bodyParameters'] = ['parameters' => array_map(fn($k, $v) => ['name' => $k, 'value' => $v], array_keys($body), $body)];
    }
    return [
        'parameters' => $params,
        'name' => $name,
        'type' => 'n8n-nodes-base.httpRequest',
        'typeVersion' => 4.2,
        'position' => [$x, $y],
    ];
}

function ifNode(string $name, string $expr, int $x = 750, int $y = 300): array
{
    return [
        'parameters' => ['conditions' => ['string' => [['value1' => $expr, 'operation' => 'isNotEmpty']]]],
        'name' => $name,
        'type' => 'n8n-nodes-base.if',
        'typeVersion' => 1,
        'position' => [$x, $y],
    ];
}

function telegramNode(string $name, string $text, int $x = 1000, int $y = 300): array
{
    global $TELEGRAM_BOT_TOKEN, $TELEGRAM_ADMIN_CHAT;
    return [
        'parameters' => [
            'operation' => 'sendMessage',
            'chatId' => $TELEGRAM_ADMIN_CHAT,
            'text' => $text,
            'additionalFields' => ['parse_mode' => 'HTML'],
        ],
        'name' => $name,
        'type' => 'n8n-nodes-base.telegram',
        'typeVersion' => 1.2,
        'position' => [$x, $y],
        'credentials' => ['telegramApi' => ['name' => 'WinCase Bot', 'id' => 'telegram_cred']],
    ];
}

function webhookNode(string $path, int $x = 250, int $y = 300): array
{
    return [
        'parameters' => ['path' => $path, 'httpMethod' => 'POST', 'responseMode' => 'responseNode'],
        'name' => 'Webhook',
        'type' => 'n8n-nodes-base.webhook',
        'typeVersion' => 2,
        'position' => [$x, $y],
    ];
}

function connect(string $from, string $to): array
{
    return [$from => ['main' => [[['node' => $to, 'type' => 'main', 'index' => 0]]]]];
}

function chain(array $nodeNames): array
{
    $conns = [];
    for ($i = 0; $i < count($nodeNames) - 1; $i++) {
        $conns[$nodeNames[$i]] = ['main' => [[['node' => $nodeNames[$i + 1], 'type' => 'main', 'index' => 0]]]];
    }
    return $conns;
}

// ========================================================
// GENERATE ALL 35 WORKFLOWS
// ========================================================

$workflows = [];

// --- W01: Lead Capture ---
$workflows['W01'] = makeWorkflow('W01: Lead Capture — Multi-Source Ingestion', [
    webhookNode('leads/capture'),
    httpNode('Validate & Store', 'POST', "$API_URL/api/v1/leads/webhook/n8n", [], 500, 300),
    ifNode('Has Lead?', '={{\$json.data.id}}', 750, 300),
    httpNode('Assign Manager', 'POST', "$API_URL/api/v1/leads/{{\$json.data.id}}/assign", ['method' => 'round-robin'], 1000, 200),
    telegramNode('Notify Admin', "🆕 New lead: <b>{{\$json.data.name}}</b>\nSource: {{\$json.data.source}}\nPhone: {{\$json.data.phone}}", 1000, 400),
], chain(['Webhook', 'Validate & Store', 'Has Lead?', 'Assign Manager']));

// --- W02: Lead Auto-Response ---
$workflows['W02'] = makeWorkflow('W02: Lead Auto-Response', [
    webhookNode('leads/auto-response'),
    httpNode('Get Lead', 'GET', "$API_URL/api/v1/leads/{{\$json.lead_id}}", [], 500, 300),
    httpNode('Send Response', 'POST', "$API_URL/api/v1/notifications/send", ['type' => 'lead_welcome', 'lead_id' => '={{\$json.data.id}}'], 750, 300),
], chain(['Webhook', 'Get Lead', 'Send Response']));

// --- W03: Lead Nurturing ---
$workflows['W03'] = makeWorkflow('W03: Lead Nurturing — Follow-up Sequence', [
    cronNode('0 */4 * * *'),
    httpNode('Get Stale Leads', 'GET', "$API_URL/api/v1/leads?status=new&older_than=24h", [], 500, 300),
    ifNode('Any Stale?', '={{\$json.data.length}}', 750, 300),
    httpNode('Send Reminders', 'POST', "$API_URL/api/v1/leads/nurture", [], 1000, 300),
    telegramNode('Alert', "⏰ {{\$json.data.reminded}} leads reminded, {{\$json.data.escalated}} escalated", 1250, 300),
], chain(['Schedule Trigger', 'Get Stale Leads', 'Any Stale?', 'Send Reminders', 'Alert']));

// --- W04: Google Ads Sync ---
$workflows['W04'] = makeWorkflow('W04: Google Ads Sync', [
    cronNode('0 */6 * * *'),
    httpNode('Fetch Ads Data', 'POST', "$API_URL/api/v1/ads/sync/google", [], 500, 300),
    telegramNode('Report', "📊 Google Ads synced: {{\$json.data.campaigns}} campaigns, spend: {{\$json.data.total_cost}} PLN", 750, 300),
], chain(['Schedule Trigger', 'Fetch Ads Data', 'Report']));

// --- W05: Meta Ads Sync ---
$workflows['W05'] = makeWorkflow('W05: Meta Ads Sync', [
    cronNode('0 */6 * * *'),
    httpNode('Fetch Meta Data', 'POST', "$API_URL/api/v1/ads/sync/meta", [], 500, 300),
    telegramNode('Report', "📊 Meta Ads synced: {{\$json.data.campaigns}} campaigns", 750, 300),
], chain(['Schedule Trigger', 'Fetch Meta Data', 'Report']));

// --- W06: TikTok Ads Sync ---
$workflows['W06'] = makeWorkflow('W06: TikTok Ads Sync', [
    cronNode('0 */6 * * *'),
    httpNode('Fetch TikTok Data', 'POST', "$API_URL/api/v1/ads/sync/tiktok", [], 500, 300),
    telegramNode('Report', "📊 TikTok Ads synced", 750, 300),
], chain(['Schedule Trigger', 'Fetch TikTok Data', 'Report']));

// --- W07: Pinterest + YouTube Ads ---
$workflows['W07'] = makeWorkflow('W07: Pinterest + YouTube Ads Sync', [
    cronNode('0 6 * * *'),
    httpNode('Pinterest Sync', 'POST', "$API_URL/api/v1/ads/sync/pinterest", [], 500, 200),
    httpNode('YouTube Sync', 'POST', "$API_URL/api/v1/ads/sync/youtube", [], 500, 400),
    telegramNode('Report', "📊 Pinterest + YouTube Ads synced", 750, 300),
], array_merge(
    ['Schedule Trigger' => ['main' => [[['node' => 'Pinterest Sync', 'type' => 'main', 'index' => 0], ['node' => 'YouTube Sync', 'type' => 'main', 'index' => 0]]]]],
    chain(['Pinterest Sync', 'Report'])
));

// --- W08: SEO GSC + GA4 ---
$workflows['W08'] = makeWorkflow('W08: SEO Data Sync — GSC + GA4', [
    cronNode('0 7 * * *'),
    httpNode('Sync GSC', 'POST', "$API_URL/api/v1/seo/sync/gsc", [], 500, 200),
    httpNode('Sync GA4', 'POST', "$API_URL/api/v1/seo/sync/ga4", [], 500, 400),
    telegramNode('Report', "📈 SEO synced: GSC + GA4 data updated", 750, 300),
], array_merge(
    ['Schedule Trigger' => ['main' => [[['node' => 'Sync GSC', 'type' => 'main', 'index' => 0], ['node' => 'Sync GA4', 'type' => 'main', 'index' => 0]]]]],
    chain(['Sync GA4', 'Report'])
));

// --- W09: SEO Ahrefs ---
$workflows['W09'] = makeWorkflow('W09: SEO Data Sync — Ahrefs + Network', [
    cronNode('0 8 * * 1'),
    httpNode('Sync Ahrefs', 'POST', "$API_URL/api/v1/seo/sync/ahrefs", [], 500, 300),
    httpNode('Check Network', 'POST', "$API_URL/api/v1/seo/network/check", [], 750, 300),
    telegramNode('Report', "📈 Weekly SEO: Ahrefs + {{\$json.data.sites}} network sites checked", 1000, 300),
], chain(['Schedule Trigger', 'Sync Ahrefs', 'Check Network', 'Report']));

// --- W10: Reviews Sync ---
$workflows['W10'] = makeWorkflow('W10: Reviews Sync (4 platforms)', [
    cronNode('0 */2 * * *'),
    httpNode('Sync Reviews', 'POST', "$API_URL/api/v1/brand/reviews/sync", [], 500, 300),
    ifNode('New Reviews?', '={{\$json.data.new_count}}', 750, 300),
    telegramNode('Alert', "⭐ {{\$json.data.new_count}} new reviews found (avg: {{\$json.data.avg_rating}})", 1000, 300),
], chain(['Schedule Trigger', 'Sync Reviews', 'New Reviews?', 'Alert']));

// --- W11: Social Accounts Sync ---
$workflows['W11'] = makeWorkflow('W11: Social Accounts Sync', [
    cronNode('0 9 * * *'),
    httpNode('Sync Accounts', 'POST', "$API_URL/api/v1/social/accounts/sync", [], 500, 300),
    telegramNode('Report', "📱 Social accounts synced: {{\$json.data.total}} accounts updated", 750, 300),
], chain(['Schedule Trigger', 'Sync Accounts', 'Report']));

// --- W12: Social Post Analytics ---
$workflows['W12'] = makeWorkflow('W12: Social Post Analytics', [
    cronNode('0 */4 * * *'),
    httpNode('Sync Analytics', 'POST', "$API_URL/api/v1/social/analytics/sync", [], 500, 300),
], chain(['Schedule Trigger', 'Sync Analytics']));

// --- W13: Scheduled Post Publisher ---
$workflows['W13'] = makeWorkflow('W13: Scheduled Post Publisher', [
    cronNode('*/5 * * * *'),
    httpNode('Publish Due', 'POST', "$API_URL/api/v1/social/posts/publish-scheduled", [], 500, 300),
    ifNode('Published?', '={{\$json.data.published}}', 750, 300),
    telegramNode('Notify', "📤 Published {{\$json.data.published}} scheduled posts", 1000, 300),
], chain(['Schedule Trigger', 'Publish Due', 'Published?', 'Notify']));

// --- W14: Unified Inbox Poll ---
$workflows['W14'] = makeWorkflow('W14: Unified Inbox Poll', [
    cronNode('*/10 * * * *'),
    httpNode('Poll Inbox', 'GET', "$API_URL/api/v1/social/inbox/poll", [], 500, 300),
    ifNode('New Messages?', '={{\$json.data.new_count}}', 750, 300),
    httpNode('Push Notification', 'POST', "$API_URL/api/v1/notifications/push", ['type' => 'inbox_new', 'count' => '={{\$json.data.new_count}}'], 1000, 300),
], chain(['Schedule Trigger', 'Poll Inbox', 'New Messages?', 'Push Notification']));

// --- W15: Brand Mentions ---
$workflows['W15'] = makeWorkflow('W15: Brand Mentions Monitor', [
    cronNode('0 */3 * * *'),
    httpNode('Check Mentions', 'POST', "$API_URL/api/v1/brand/mentions/check", [], 500, 300),
    ifNode('Found?', '={{\$json.data.new_mentions}}', 750, 300),
    telegramNode('Alert', "🔍 {{\$json.data.new_mentions}} new brand mentions found", 1000, 300),
], chain(['Schedule Trigger', 'Check Mentions', 'Found?', 'Alert']));

// --- W16: NAP Consistency ---
$workflows['W16'] = makeWorkflow('W16: NAP Consistency Check', [
    cronNode('0 10 * * 5'),
    httpNode('Check NAP', 'POST', "$API_URL/api/v1/brand/nap/check", [], 500, 300),
    ifNode('Mismatches?', '={{\$json.data.mismatches}}', 750, 300),
    telegramNode('Alert', "⚠️ NAP mismatches: {{\$json.data.mismatches}} directories need update", 1000, 300),
], chain(['Schedule Trigger', 'Check NAP', 'Mismatches?', 'Alert']));

// --- W17: Bank Statement Import ---
$workflows['W17'] = makeWorkflow('W17: Bank Statement Import', [
    cronNode('0 22 * * *'),
    httpNode('Import Statements', 'POST', "$API_URL/api/v1/accounting/bank/import", [], 500, 300),
    telegramNode('Report', "🏦 Bank imported: {{\$json.data.transactions}} txns, {{\$json.data.matched}} matched", 750, 300),
], chain(['Schedule Trigger', 'Import Statements', 'Report']));

// --- W18: Invoice Generator ---
$workflows['W18'] = makeWorkflow('W18: Invoice Generator (POS Approved)', [
    webhookNode('invoices/generate'),
    httpNode('Generate Invoice', 'POST', "$API_URL/api/v1/accounting/invoices/generate", ['pos_id' => '={{\$json.pos_id}}'], 500, 300),
    httpNode('Send PDF', 'POST', "$API_URL/api/v1/accounting/invoices/{{\$json.data.id}}/send", [], 750, 300),
    telegramNode('Notify', "🧾 Invoice #{{\$json.data.number}} generated — {{\$json.data.total}} PLN", 1000, 300),
], chain(['Webhook', 'Generate Invoice', 'Send PDF', 'Notify']));

// --- W19: Tax Report ---
$workflows['W19'] = makeWorkflow('W19: Tax Report Generator', [
    cronNode('0 6 1 * *'),
    httpNode('Generate Report', 'POST', "$API_URL/api/v1/accounting/tax/report", [], 500, 300),
    telegramNode('Report', "📋 Monthly tax report generated: PIT {{\$json.data.pit}} PLN, VAT {{\$json.data.vat}} PLN", 750, 300),
], chain(['Schedule Trigger', 'Generate Report', 'Report']));

// --- W20: Document Expiry Alert ---
$workflows['W20'] = makeWorkflow('W20: Document Expiry Alert', [
    cronNode('0 8 * * *'),
    httpNode('Check Expiring', 'GET', "$API_URL/api/v1/documents/expiring", [], 500, 300),
    ifNode('Any Expiring?', '={{\$json.data.count}}', 750, 300),
    httpNode('Send Alerts', 'POST', "$API_URL/api/v1/documents/expiry-alerts", [], 1000, 200),
    telegramNode('Notify', "📄 {{\$json.data.count}} documents expiring soon — alerts sent", 1000, 400),
], chain(['Schedule Trigger', 'Check Expiring', 'Any Expiring?', 'Send Alerts', 'Notify']));

// --- W21: Case Deadline Alert ---
$workflows['W21'] = makeWorkflow('W21: Case Deadline Alert', [
    cronNode('0 8 * * *'),
    httpNode('Check Deadlines', 'GET', "$API_URL/api/v1/cases/deadlines", [], 500, 300),
    ifNode('Any Due?', '={{\$json.data.count}}', 750, 300),
    httpNode('Send Alerts', 'POST', "$API_URL/api/v1/cases/deadline-alerts", [], 1000, 200),
    telegramNode('Notify', "⚖️ {{\$json.data.count}} case deadlines approaching — alerts sent", 1000, 400),
], chain(['Schedule Trigger', 'Check Deadlines', 'Any Due?', 'Send Alerts', 'Notify']));

// --- W22: System Health Monitor ---
$workflows['W22'] = makeWorkflow('W22: System Health Monitor', [
    cronNode('*/15 * * * *'),
    httpNode('Health Check', 'GET', "$API_URL/api/v1/system/health", [], 500, 300),
    ifNode('Degraded?', '={{\$json.status === "degraded"}}', 750, 300),
    telegramNode('ALERT', "🔴 SYSTEM DEGRADED\n{{JSON.stringify(\$json.checks)}}", 1000, 300),
], chain(['Schedule Trigger', 'Health Check', 'Degraded?', 'ALERT']));

// --- W23-W27: News V1 (replaced by V2, but keeping for completeness) ---
// --- W28: Critical News Parser ---
$workflows['W28'] = makeWorkflow('W28: Critical News Parser (5min)', [
    cronNode('*/5 * * * *'),
    httpNode('Parse Critical', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'critical'], 500, 300),
    ifNode('New Articles?', '={{\$json.data.new_articles}}', 750, 300),
    httpNode('AI Rewrite', 'POST', "$API_URL/api/v1/news/rewrite-batch", ['priority' => 'critical'], 1000, 200),
    httpNode('Publish', 'POST', "$API_URL/api/v1/news/publish", [], 1250, 200),
    telegramNode('Alert', "⚡ BREAKING: {{\$json.data.published}} critical news published", 1500, 200),
], chain(['Schedule Trigger', 'Parse Critical', 'New Articles?', 'AI Rewrite', 'Publish', 'Alert']));

// --- W29: High Priority Parser ---
$workflows['W29'] = makeWorkflow('W29: High Priority Parser (10min)', [
    cronNode('*/10 * * * *'),
    httpNode('Parse High', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'high'], 500, 300),
    ifNode('New?', '={{\$json.data.new_articles}}', 750, 300),
    telegramNode('Info', "📰 {{\$json.data.new_articles}} high-priority articles parsed", 1000, 300),
], chain(['Schedule Trigger', 'Parse High', 'New?', 'Info']));

// --- W30: Medium Priority Parser ---
$workflows['W30'] = makeWorkflow('W30: Medium Priority Parser (30min)', [
    cronNode('*/30 * * * *'),
    httpNode('Parse Medium', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'medium'], 500, 300),
], chain(['Schedule Trigger', 'Parse Medium']));

// --- W31: Low Priority Parser ---
$workflows['W31'] = makeWorkflow('W31: Low Priority Parser (60min)', [
    cronNode('0 * * * *'),
    httpNode('Parse Low', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'low'], 500, 300),
], chain(['Schedule Trigger', 'Parse Low']));

// --- W32: AI Rewrite Engine ---
$workflows['W32'] = makeWorkflow('W32: AI Rewrite Engine (10min)', [
    cronNode('*/10 * * * *'),
    httpNode('Rewrite Batch', 'POST', "$API_URL/api/v1/news/rewrite-batch", ['limit' => '10'], 500, 300),
    ifNode('Needs Review?', '={{\$json.data.needs_review}}', 750, 300),
    telegramNode('Review Alert', "⚠️ {{\$json.data.needs_review}} articles need manual review (plagiarism > 15%)", 1000, 300),
], chain(['Schedule Trigger', 'Rewrite Batch', 'Needs Review?', 'Review Alert']));

// --- W33: Auto Publisher ---
$workflows['W33'] = makeWorkflow('W33: Auto Publisher (5min)', [
    cronNode('*/5 * * * *'),
    httpNode('Publish Ready', 'POST', "$API_URL/api/v1/news/publish", [], 500, 300),
    ifNode('Published?', '={{\$json.data.published}}', 750, 300),
    telegramNode('Summary', "📤 Published {{\$json.data.published}} articles to target sites", 1000, 300),
], chain(['Schedule Trigger', 'Publish Ready', 'Published?', 'Summary']));

// --- W34: Site Health Monitor ---
$sites = ['polandpulse.news','wincase.pro','eurogamingpost.com','techpulse.news','bizeurope.news','sportpulse.news','diaspora.news','trendwatch.news'];
$siteNodes = [cronNode('*/15 * * * *')];
$x = 500;
foreach ($sites as $i => $site) {
    $siteNodes[] = httpNode("Check $site", 'GET', "https://$site", [], $x, 200 + ($i * 80));
}
$siteNodes[] = telegramNode('Report', "🏥 Site health check completed — all 8 sites checked", $x + 500, 300);
$workflows['W34'] = makeWorkflow('W34: Site Health Monitor (15min)', $siteNodes,
    ['Schedule Trigger' => ['main' => [[['node' => 'Check ' . $sites[0], 'type' => 'main', 'index' => 0]]]]]);

// --- W35: Daily Digest ---
$workflows['W35'] = makeWorkflow('W35: Daily News Digest (20:00)', [
    cronNode('0 20 * * *'),
    httpNode('Get Stats', 'GET', "$API_URL/api/v1/news/statistics", [], 500, 300),
    telegramNode('Digest', "📰 <b>Daily News Digest</b>\n\n📥 Parsed: {{\$json.data.today_parsed}}\n📤 Published: {{\$json.data.today_published}}\n📊 Total: {{\$json.data.total_articles}}", 750, 300),
], chain(['Schedule Trigger', 'Get Stats', 'Digest']));

// ========================================================
// W23-W27: Aliases for V1 (same as W28-W32 but simpler)
// ========================================================
$workflows['W23'] = makeWorkflow('W23: News Parser — Critical (PAP, UDSC, Gov.pl)', [
    cronNode('*/5 * * * *'),
    httpNode('Parse Critical', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'critical'], 500, 300),
    httpNode('Rewrite', 'POST', "$API_URL/api/v1/news/rewrite-batch", [], 750, 300),
    httpNode('Publish', 'POST', "$API_URL/api/v1/news/publish", [], 1000, 300),
    telegramNode('Notify', "🔴 CRITICAL news processed: parsed → rewritten → published", 1250, 300),
], chain(['Schedule Trigger', 'Parse Critical', 'Rewrite', 'Publish', 'Notify']));

$workflows['W24'] = makeWorkflow('W24: News Parser — High & Medium', [
    cronNode('*/15 * * * *'),
    httpNode('Parse High', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'high'], 500, 200),
    httpNode('Parse Medium', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'medium'], 500, 400),
], ['Schedule Trigger' => ['main' => [[['node' => 'Parse High', 'type' => 'main', 'index' => 0], ['node' => 'Parse Medium', 'type' => 'main', 'index' => 0]]]]]);

$workflows['W25'] = makeWorkflow('W25: AI Rewriter — Batch Processing', [
    cronNode('*/10 * * * *'),
    httpNode('Rewrite Batch', 'POST', "$API_URL/api/v1/news/rewrite-batch", [], 500, 300),
    httpNode('Publish Ready', 'POST', "$API_URL/api/v1/news/publish", [], 750, 300),
], chain(['Schedule Trigger', 'Rewrite Batch', 'Publish Ready']));

$workflows['W26'] = makeWorkflow('W26: Auto Publisher — Rewritten → Sites', [
    cronNode('*/5 * * * *'),
    httpNode('Publish', 'POST', "$API_URL/api/v1/news/publish", [], 500, 300),
    ifNode('Any?', '={{\$json.data.published}}', 750, 300),
    telegramNode('Summary', "📰 Published {{\$json.data.published}} articles", 1000, 300),
], chain(['Schedule Trigger', 'Publish', 'Any?', 'Summary']));

$workflows['W27'] = makeWorkflow('W27: News Parser — Low Priority (Sport, Tech)', [
    cronNode('*/30 * * * *'),
    httpNode('Parse Low', 'POST', "$API_URL/api/v1/news/parse", ['priority' => 'low'], 500, 300),
], chain(['Schedule Trigger', 'Parse Low']));

// ========================================================
// SAVE ALL 35 JSONS
// ========================================================
$count = 0;
ksort($workflows);
foreach ($workflows as $code => $wf) {
    $filename = $outputDir . '/' . strtolower($code) . '_' . preg_replace('/[^a-z0-9]+/', '_', strtolower(explode(':', $wf['name'])[1] ?? $wf['name'])) . '.json';
    file_put_contents($filename, json_encode($wf, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $count++;
    echo "✅ $code: {$wf['name']} → " . basename($filename) . "\n";
}

echo "\n🎉 Generated $count workflow JSONs in $outputDir/\n";
echo "Import each via n8n UI → Workflows → Import from File\n";
