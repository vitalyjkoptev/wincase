<?php
/**
 * Telegram Channel Posts API for wincase.eu
 * Scrapes public channel t.me/s/wincasetop and returns JSON
 *
 * Endpoints:
 *   GET /api/telegram-posts.php              — latest posts (paginated)
 *   GET /api/telegram-posts.php?page=2       — page 2
 *   GET /api/telegram-posts.php?id=632       — single post by ID
 *   GET /api/telegram-posts.php?refresh=1    — force cache refresh (cron)
 *   GET /api/telegram-posts.php?q=keyword    — search
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=300');

define('CHANNEL', 'wincasetop');
define('CACHE_DIR', __DIR__ . '/../cache');
define('CACHE_FILE', CACHE_DIR . '/telegram-posts.json');
define('CACHE_TTL', 1800); // 30 minutes
define('PER_PAGE', 9);
define('MAX_PAGES_FETCH', 20); // fetch up to 20 pages (400 posts)

// Themed images based on post keywords (all 870x550, verified)
define('THEME_IMAGES', [
    'immigration' => [
        'keywords' => ['імміграц', 'иммиграц', 'immigration', 'переїзд', 'переезд', 'еміграц', 'эмиграц'],
        'images' => ['immigration-1.jpg', 'immigration-2.jpg', 'immigration-3.jpg', 'immigration-4.jpg', 'immigration-5.jpg'],
    ],
    'visa' => [
        'keywords' => ['віза', 'виза', 'visa', 'шенген', 'schengen', 'консульств'],
        'images' => ['visa-1.jpg', 'visa-2.jpg', 'visa-3.jpg'],
    ],
    'residence' => [
        'keywords' => ['карта побуту', 'karta pobytu', 'карта побыту', 'побутова', 'residence', 'ВНЖ', 'внж', 'посвідка', 'вид на жительство'],
        'images' => ['residence-1.jpg', 'residence-2.jpg', 'residence-3.jpg', 'residence-4.jpg'],
    ],
    'work' => [
        'keywords' => ['робот', 'работ', 'work', 'praca', 'job', 'зарплат', 'працевлаштув', 'трудоустр', 'вакансі'],
        'images' => ['work-1.jpg', 'work-2.jpg', 'work-3.jpg', 'work-4.jpg', 'work-5.jpg', 'work-6.jpg'],
    ],
    'documents' => [
        'keywords' => ['документ', 'document', 'довідк', 'справк', 'дозвіл', 'разрешени', 'permit', 'pesel', 'nip', 'zameldowanie'],
        'images' => ['documents-1.jpg', 'documents-2.jpg', 'documents-3.jpg', 'documents-4.jpg', 'documents-5.jpg'],
    ],
    'poland' => [
        'keywords' => ['польщ', 'польш', 'poland', 'polska', 'варшав', 'warszaw', 'краків', 'краков', 'wrocław'],
        'images' => ['poland-1.jpg', 'poland-2.jpg', 'poland-3.jpg', 'poland-4.jpg', 'poland-5.jpg'],
    ],
    'law' => [
        'keywords' => ['закон', 'закін', 'law', 'правов', 'суд', 'штраф', 'легаліз', 'легализ', 'legal'],
        'images' => ['law-1.jpg', 'law-2.jpg', 'law-3.jpg', 'law-4.jpg'],
    ],
    'citizenship' => [
        'keywords' => ['громадянств', 'гражданств', 'citizenship', 'obywatelstw', 'паспорт', 'passport', 'натураліз'],
        'images' => ['citizenship-1.jpg', 'citizenship-2.jpg', 'citizenship-3.jpg', 'citizenship-4.jpg'],
    ],
]);

// Fallback images for posts that don't match any theme (all 870x550)
define('FALLBACK_IMAGES', [
    'immigration-1.jpg', 'immigration-2.jpg', 'immigration-3.jpg',
    'work-1.jpg', 'work-2.jpg', 'work-3.jpg',
    'documents-1.jpg', 'documents-2.jpg',
    'visa-1.jpg', 'visa-2.jpg',
    'poland-1.jpg', 'poland-2.jpg',
    'law-1.jpg', 'law-2.jpg',
    'citizenship-1.jpg', 'citizenship-2.jpg',
    'residence-1.jpg', 'residence-2.jpg',
    'general-1.jpg', 'general-2.jpg', 'general-3.jpg', 'general-4.jpg',
]);

// Ensure cache dir exists
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0755, true);
}

// Router
$postId = $_GET['id'] ?? null;
$page = max(1, intval($_GET['page'] ?? 1));
$forceRefresh = isset($_GET['refresh']);
$search = trim($_GET['q'] ?? '');

// Force refresh via cron
if ($forceRefresh) {
    $posts = fetchAndCacheAll();
    echo json_encode(['ok' => true, 'cached' => count($posts), 'time' => date('c')]);
    exit;
}

// Load cached posts
$posts = loadCache();

// If no cache or cache expired, fetch fresh
if (empty($posts)) {
    $posts = fetchAndCacheAll();
}

// Single post
if ($postId) {
    $post = null;
    $idx = null;
    foreach ($posts as $i => $p) {
        if ($p['id'] == $postId) {
            $post = $p;
            $idx = $i;
            break;
        }
    }
    if ($post) {
        $prev = ($idx < count($posts) - 1) ? $posts[$idx + 1]['id'] : null;
        $next = ($idx > 0) ? $posts[$idx - 1]['id'] : null;

        // Translate full text on-demand for single post view
        if (!empty($post['text']) && !isset($post['text_translated'])) {
            $origText = $post['text'];
            $lines = explode("\n", $origText);
            $chunks = [];
            $current = '';
            foreach ($lines as $line) {
                if (mb_strlen($current . "\n" . $line) > 1000) {
                    $chunks[] = $current;
                    $current = $line;
                } else {
                    $current .= ($current ? "\n" : '') . $line;
                }
            }
            if ($current) $chunks[] = $current;
            $translated = [];
            foreach ($chunks as $chunk) {
                $translated[] = translateText($chunk);
                usleep(100000);
            }
            $post['text_original'] = $origText;
            $post['text'] = implode("\n", $translated);
        }

        // Get 3 recent posts for sidebar (excluding current)
        $recent = [];
        $count = 0;
        foreach ($posts as $p) {
            if ($p['id'] != $postId && $count < 3) {
                $recent[] = ['id' => $p['id'], 'title' => $p['title'], 'date_formatted' => $p['date_formatted'], 'image' => $p['image']];
                $count++;
            }
        }

        echo json_encode(['ok' => true, 'post' => $post, 'prev' => $prev, 'next' => $next, 'recent' => $recent]);
    } else {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Post not found']);
    }
    exit;
}

// Search
if ($search) {
    $searchLower = mb_strtolower($search);
    $posts = array_values(array_filter($posts, function($p) use ($searchLower) {
        return mb_strpos(mb_strtolower($p['title']), $searchLower) !== false
            || mb_strpos(mb_strtolower($p['text']), $searchLower) !== false;
    }));
}

// Pagination
$total = count($posts);
$totalPages = max(1, ceil($total / PER_PAGE));
$page = min($page, $totalPages);
$offset = ($page - 1) * PER_PAGE;
$items = array_slice($posts, $offset, PER_PAGE);

echo json_encode([
    'ok' => true,
    'posts' => $items,
    'pagination' => [
        'page' => $page,
        'per_page' => PER_PAGE,
        'total' => $total,
        'total_pages' => $totalPages,
    ]
], JSON_UNESCAPED_UNICODE);
exit;

// ============================================================
// Functions
// ============================================================

function loadCache(): array {
    if (!file_exists(CACHE_FILE)) return [];
    $mtime = filemtime(CACHE_FILE);
    if (time() - $mtime > CACHE_TTL) return [];
    $data = json_decode(file_get_contents(CACHE_FILE), true);
    return is_array($data) ? $data : [];
}

function fetchAndCacheAll(): array {
    $allPosts = [];
    $before = null;

    for ($i = 0; $i < MAX_PAGES_FETCH; $i++) {
        $url = 'https://t.me/s/' . CHANNEL;
        if ($before) $url .= '?before=' . $before;

        $html = fetchUrl($url);
        if (!$html) break;

        $posts = parseMessages($html);
        if (empty($posts)) break;

        $allPosts = array_merge($allPosts, $posts);

        $ids = array_column($posts, 'id');
        $minId = min($ids);
        if ($before && $before <= $minId) break;
        $before = $minId;

        usleep(300000); // 300ms delay
    }

    // Sort newest first
    usort($allPosts, fn($a, $b) => $b['id'] - $a['id']);

    // Remove duplicates by ID
    $seen = [];
    $unique = [];
    foreach ($allPosts as $post) {
        if (!isset($seen[$post['id']])) {
            $seen[$post['id']] = true;
            $unique[] = $post;
        }
    }

    // Remove duplicates by title (keep newest = first occurrence since sorted desc)
    $seenTitles = [];
    $deduped = [];
    foreach ($unique as $post) {
        $titleKey = mb_strtolower(trim(preg_replace('/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27FF}\x{FE00}-\x{FEFF}]/u', '', $post['title'])));
        $titleKey = preg_replace('/\s+/', ' ', $titleKey);
        if (!isset($seenTitles[$titleKey])) {
            $seenTitles[$titleKey] = true;
            $deduped[] = $post;
        }
    }
    $unique = $deduped;

    // Translate titles and excerpts from Ukrainian to English
    foreach ($unique as &$post) {
        translatePost($post, false); // title + excerpt only
        usleep(150000); // 150ms delay to avoid rate limiting
    }

    // Assign themed images (use original Ukrainian text for keyword matching)
    foreach ($unique as &$post) {
        $searchTitle = $post['title_original'] ?? $post['title'];
        $searchText = $post['text_original'] ?? $post['text'];
        $tempPost = $post;
        $tempPost['title'] = $searchTitle;
        $tempPost['text'] = $searchText;
        $post['image'] = assignImage($tempPost);
    }

    file_put_contents(CACHE_FILE, json_encode($unique, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    return $unique;
}

function fetchUrl(string $url): ?string {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        CURLOPT_HTTPHEADER => [
            'Accept-Language: uk-UA,uk;q=0.9,en;q=0.8',
            'Accept: text/html,application/xhtml+xml',
        ],
    ]);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($html && $code === 200) ? $html : null;
}

function parseMessages(string $html): array {
    $posts = [];
    $chunks = preg_split('/(?=<div class="tgme_widget_message_wrap)/', $html);

    foreach ($chunks as $chunk) {
        if (!preg_match('/data-post="' . preg_quote(CHANNEL) . '\/(\d+)"/', $chunk, $m)) continue;
        $postId = (int)$m[1];

        // Extract raw HTML text
        $text = '';
        if (preg_match('/<div class="tgme_widget_message_text[^"]*"[^>]*>(.*?)<\/div>/s', $chunk, $m)) {
            $rawHtml = $m[1];
            $text = preg_replace('/<br\s*\/?>/i', "\n", $rawHtml);
            $text = preg_replace('/<i class="emoji"[^>]*><b>([^<]*)<\/b><\/i>/', '$1', $text);
            $text = preg_replace('/<b>(.*?)<\/b>/s', '$1', $text);
            $text = preg_replace('/<i>(.*?)<\/i>/s', '$1', $text);
            $text = preg_replace('/<a[^>]*>(.*?)<\/a>/s', '$1', $text);
            $text = strip_tags($text);
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text = trim($text);
        }
        if (empty($text)) continue;

        // Date
        $date = '';
        if (preg_match('/datetime="([^"]+)"/', $chunk, $m)) $date = $m[1];

        // Views
        $views = '0';
        if (preg_match('/tgme_widget_message_views">([^<]+)</', $chunk, $m)) $views = trim($m[1]);

        // Photo
        $photo = '';
        if (preg_match('/tgme_widget_message_photo_wrap.*?background-image:url\(\'([^\']+)\'\)/s', $chunk, $m)) {
            $photo = $m[1];
            if (strpos($photo, '//') === 0) $photo = 'https:' . $photo;
        }

        // Title = first line
        $lines = array_filter(explode("\n", $text), fn($l) => trim($l) !== '');
        $lines = array_values($lines);
        $title = trim($lines[0] ?? '');
        $body = trim(implode("\n", array_slice($lines, 1)));

        // If title too long, try to cut at first sentence
        if (mb_strlen($title) > 120) {
            $cutPos = mb_strpos($title, '. ');
            if ($cutPos && $cutPos < 100) {
                $body = mb_substr($title, $cutPos + 2) . "\n" . $body;
                $title = mb_substr($title, 0, $cutPos + 1);
            }
        }

        // Excerpt
        $excerpt = mb_substr(preg_replace('/\s+/', ' ', $body), 0, 200);
        if (mb_strlen($body) > 200) $excerpt .= '...';

        $posts[] = [
            'id' => $postId,
            'title' => $title,
            'text' => $body,
            'excerpt' => $excerpt,
            'date' => $date,
            'date_formatted' => $date ? formatDate($date) : '',
            'views' => $views,
            'photo' => $photo,
            'image' => '', // will be assigned later
            'url' => 'https://t.me/' . CHANNEL . '/' . $postId,
        ];
    }
    return $posts;
}

function assignImage(array $post): string {
    $searchText = mb_strtolower($post['title'] . ' ' . $post['text']);

    // If post has a Telegram photo, use it
    if (!empty($post['photo'])) return $post['photo'];

    // Match by theme keywords
    foreach (THEME_IMAGES as $theme => $config) {
        foreach ($config['keywords'] as $kw) {
            if (mb_strpos($searchText, mb_strtolower($kw)) !== false) {
                // Pick image based on post ID for consistency
                $imgIdx = $post['id'] % count($config['images']);
                return 'assets/img/blog/' . $config['images'][$imgIdx];
            }
        }
    }

    // Fallback: rotate through fallback images based on post ID
    $fallbacks = FALLBACK_IMAGES;
    $imgIdx = $post['id'] % count($fallbacks);
    return 'assets/img/blog/' . $fallbacks[$imgIdx];
}

function translateText(string $text, string $from = 'uk', string $to = 'en'): string {
    if (empty(trim($text))) return $text;
    // Remove emoji before translation for cleaner results
    $clean = preg_replace('/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27FF}\x{FE00}-\x{FEFF}\x{1F900}-\x{1F9FF}]/u', '', $text);
    $clean = trim(preg_replace('/\s+/', ' ', $clean));
    if (empty($clean)) return $text;

    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' . $from . '&tl=' . $to . '&dt=t&q=' . urlencode($clean);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$resp || $code !== 200) return $text;
    $data = json_decode($resp, true);
    if (!is_array($data) || !isset($data[0])) return $text;

    $translated = '';
    foreach ($data[0] as $segment) {
        if (isset($segment[0])) $translated .= $segment[0];
    }
    return trim($translated) ?: $text;
}

function translatePost(array &$post, bool $fullText = false): void {
    // Translate title
    $post['title_original'] = $post['title'];
    $post['title'] = translateText($post['title']);

    // Translate excerpt
    if (!empty($post['excerpt'])) {
        $post['excerpt_original'] = $post['excerpt'];
        $post['excerpt'] = translateText($post['excerpt']);
    }

    // Only translate full text on demand (single post view)
    if ($fullText && !empty($post['text'])) {
        $post['text_original'] = $post['text'];
        $lines = explode("\n", $post['text']);
        $chunks = [];
        $current = '';
        foreach ($lines as $line) {
            if (mb_strlen($current . "\n" . $line) > 1000) {
                $chunks[] = $current;
                $current = $line;
            } else {
                $current .= ($current ? "\n" : '') . $line;
            }
        }
        if ($current) $chunks[] = $current;

        $translated = [];
        foreach ($chunks as $chunk) {
            $translated[] = translateText($chunk);
            usleep(100000);
        }
        $post['text'] = implode("\n", $translated);
    }
}

function formatDate(string $iso): string {
    $ts = strtotime($iso);
    if (!$ts) return '';
    $months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
    return date('d', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
