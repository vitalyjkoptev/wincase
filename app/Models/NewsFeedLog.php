<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsFeedLog extends Model
{
    protected $table = 'news_feed_logs';

    protected $fillable = [
        'feed_url', 'site_key', 'status', 'articles_found',
        'articles_published', 'error_message',
    ];
}
