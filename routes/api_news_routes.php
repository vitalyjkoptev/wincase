<?php

// =====================================================
// FILE: routes/api_news_routes.php — 14 endpoints
// =====================================================

use App\Http\Controllers\Api\V1\NewsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/news')->middleware(['auth:sanctum'])->group(function () {
    // Sources & Config
    Route::get('/sources', [NewsController::class, 'sources'])->name('news.sources');
    Route::get('/schedule', [NewsController::class, 'schedule'])->name('news.schedule');
    Route::get('/statistics', [NewsController::class, 'statistics'])->name('news.statistics');

    // Articles
    Route::get('/articles', [NewsController::class, 'articles'])->name('news.articles');
    Route::get('/articles/{id}', [NewsController::class, 'showArticle'])->name('news.articles.show');
    Route::post('/articles/{id}/approve', [NewsController::class, 'approveArticle'])->name('news.articles.approve');
    Route::post('/articles/{id}/reject', [NewsController::class, 'rejectArticle'])->name('news.articles.reject');

    // Parse
    Route::post('/parse', [NewsController::class, 'triggerParse'])->name('news.parse');
    Route::post('/parse/{sourceKey}', [NewsController::class, 'parseSource'])->name('news.parse.source');

    // Rewrite
    Route::post('/rewrite/{id}', [NewsController::class, 'rewriteArticle'])->name('news.rewrite');
    Route::post('/rewrite-batch', [NewsController::class, 'rewriteBatch'])->name('news.rewrite.batch');
    Route::post('/translate/{id}', [NewsController::class, 'translateArticle'])->name('news.translate');

    // Publish
    Route::post('/publish', [NewsController::class, 'publishReady'])->name('news.publish');

    // Live Feed
    Route::get('/feed', [NewsController::class, 'feedHistory'])->name('news.feed');
});

// =====================================================
// FILE: app/Models/NewsArticle.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsArticle extends Model
{
    use SoftDeletes;

    protected $table = 'news_articles';

    protected $fillable = [
        'source_key', 'source_name', 'source_url',
        'original_title', 'original_content', 'original_description', 'original_language',
        'category', 'priority', 'image_url', 'published_at',
        'rewritten_title', 'rewritten_content', 'rewritten_description', 'rewritten_language',
        'seo_meta_title', 'seo_meta_description', 'seo_keywords', 'seo_slug',
        'plagiarism_score', 'status', 'skip_reason',
        'rewritten_at', 'last_published_at',
        'published_to', 'published_urls',
        'parent_article_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'rewritten_at' => 'datetime',
        'last_published_at' => 'datetime',
        'published_to' => 'array',
        'published_urls' => 'array',
        'plagiarism_score' => 'float',
    ];

    // Relations
    public function publishLogs() { return $this->hasMany(NewsPublishLog::class); }
    public function translations() { return $this->hasMany(self::class, 'parent_article_id'); }
    public function parent() { return $this->belongsTo(self::class, 'parent_article_id'); }
}

// =====================================================
// FILE: app/Models/NewsPublishLog.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsPublishLog extends Model
{
    protected $fillable = ['news_article_id', 'target_site', 'status', 'error_message', 'published_url'];
    public function article() { return $this->belongsTo(NewsArticle::class, 'news_article_id'); }
}

// =====================================================
// FILE: app/Models/NewsFeedLog.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeedLog extends Model
{
    protected $fillable = ['event_type', 'article_id', 'source', 'target_site', 'title', 'language', 'message'];
}

// ---------------------------------------------------------------
// Аннотация (RU):
// 14 API routes (auth:sanctum): sources, articles CRUD, parse, rewrite, translate, publish, feed.
// NewsArticle model: original + rewritten fields, plagiarism_score, published_to (array), SEO fields.
// NewsPublishLog: лог публикаций (target_site, status, url).
// NewsFeedLog: лог live feed событий (для истории).
// Файл: routes/api_news_routes.php + app/Models/News*.php
// ---------------------------------------------------------------
