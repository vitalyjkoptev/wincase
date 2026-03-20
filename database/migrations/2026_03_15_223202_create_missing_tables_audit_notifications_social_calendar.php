<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── AUDIT LOG ──────────────────────────────────────
        if (!Schema::hasTable('audit_log')) {
            Schema::create('audit_log', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('action');              // login, create, update, delete, etc.
                $table->string('entity_type')->nullable(); // lead, client, case, etc.
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->text('description')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();

                $table->index(['entity_type', 'entity_id']);
                $table->index('action');
                $table->index('user_id');
                $table->index('created_at');
            });
        }

        // ─── NOTIFICATIONS ──────────────────────────────────
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->string('type')->nullable();         // lead_new, case_update, payment, etc.
                $table->string('title')->nullable();
                $table->text('body')->nullable();
                $table->json('data')->nullable();
                $table->json('channels')->nullable();       // ['email','telegram','push']
                $table->string('priority')->default('normal'); // low, normal, high, urgent
                $table->string('status')->default('pending');  // pending, sent, failed
                $table->json('channel_results')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('type');
                $table->index('status');
                $table->index('read_at');
            });
        }

        // ─── SOCIAL ANALYTICS ───────────────────────────────
        if (!Schema::hasTable('social_analytics')) {
            Schema::create('social_analytics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('social_post_id')->constrained('social_posts')->cascadeOnDelete();
                $table->date('date');
                $table->unsignedInteger('likes')->default(0);
                $table->unsignedInteger('comments')->default(0);
                $table->unsignedInteger('shares')->default(0);
                $table->unsignedInteger('reach')->default(0);
                $table->unsignedInteger('impressions')->default(0);
                $table->unsignedInteger('clicks')->default(0);
                $table->unsignedInteger('saves')->default(0);
                $table->decimal('engagement_rate', 8, 4)->default(0);
                $table->timestamps();

                $table->index(['social_post_id', 'date']);
            });
        }

        // ─── CALENDAR EVENTS ────────────────────────────────
        if (!Schema::hasTable('calendar_events')) {
            Schema::create('calendar_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('case_id')->nullable();
                $table->string('type')->default('meeting'); // meeting, hearing, deadline, reminder
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('start_at');
                $table->timestamp('end_at')->nullable();
                $table->boolean('all_day')->default(false);
                $table->string('location')->nullable();
                $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
                $table->timestamps();

                $table->index('user_id');
                $table->index('start_at');
                $table->index('status');
            });
        }

        // ─── NEWS SITE CONFIGS ──────────────────────────────
        if (!Schema::hasTable('news_site_configs')) {
            Schema::create('news_site_configs', function (Blueprint $table) {
                $table->id();
                $table->string('site_key')->unique();      // polandpulse, eurogamingpost, etc.
                $table->string('domain');
                $table->string('wp_url');
                $table->string('wp_user')->nullable();
                $table->text('wp_password')->nullable();
                $table->boolean('is_active')->default(true);
                $table->json('categories')->nullable();     // array of WP category IDs
                $table->json('rss_feeds')->nullable();      // array of RSS source URLs
                $table->string('language', 5)->default('en');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('news_site_configs');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('social_analytics');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('audit_log');
    }
};
