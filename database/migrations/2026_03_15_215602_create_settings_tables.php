<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->string('lang', 5)->default('pl');
                $table->string('subject');
                $table->text('body');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->unique(['key', 'lang']);
            });
        }

        if (!Schema::hasTable('notification_settings')) {
            Schema::create('notification_settings', function (Blueprint $table) {
                $table->id();
                $table->string('notification');
                $table->string('channel');
                $table->boolean('enabled')->default(true);
                $table->timestamps();
                $table->unique(['notification', 'channel']);
            });
        }

        if (!Schema::hasTable('user_notification_prefs')) {
            Schema::create('user_notification_prefs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('notification');
                $table->string('channel');
                $table->boolean('enabled')->default(true);
                $table->timestamps();
                $table->unique(['user_id', 'notification', 'channel']);
            });
        }

        if (!Schema::hasTable('user_preferences')) {
            Schema::create('user_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('key');
                $table->text('value')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('user_notification_prefs');
        Schema::dropIfExists('notification_settings');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('settings');
    }
};
