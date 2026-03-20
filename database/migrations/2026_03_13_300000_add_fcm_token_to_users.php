<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'fcm_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('fcm_token', 512)->nullable()->after('avatar_url');
                $table->string('device_platform', 20)->nullable()->after('fcm_token'); // android, ios, web
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fcm_token', 'device_platform']);
        });
    }
};
