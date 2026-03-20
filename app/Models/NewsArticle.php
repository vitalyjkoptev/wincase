<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    protected $table = 'news_articles';

    protected $fillable = [
        'title', 'slug', 'content', 'original_content', 'source_url',
        'source_name', 'site_domain', 'category', 'language', 'status',
        'wp_post_id', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'wp_post_id' => 'integer',
        ];
    }

    public function scopePublished($q) { return $q->where('status', 'published'); }
    public function scopeDraft($q) { return $q->where('status', 'draft'); }
    public function scopeByCategory($q, string $cat) { return $q->where('category', $cat); }
    public function scopeByLanguage($q, string $lang) { return $q->where('language', $lang); }

    public static function getCategories(): array
    {
        return [
            'immigration' => 'Immigration',
            'work_permits' => 'Work Permits',
            'residence' => 'Residence',
            'tax' => 'Tax & Accounting',
            'company_reg' => 'Company Registration',
            'legal_updates' => 'Legal Updates',
            'guides' => 'Guides',
            'business' => 'Business',
            'eu_policy' => 'EU Policy',
            'tech_news' => 'Tech News',
        ];
    }
}
