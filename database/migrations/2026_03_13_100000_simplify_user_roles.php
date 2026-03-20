<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL: convert enum → varchar
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'staff'");
        }

        // Map old roles → new 3-role system
        DB::table('users')->where('role', 'admin')->update(['role' => 'boss']);
        DB::table('users')->where('role', 'director')->update(['role' => 'boss']);
        DB::table('users')->whereIn('role', ['manager', 'operator', 'worker', 'accountant', 'viewer'])->update(['role' => 'staff']);
        // 'boss' stays boss, 'user' stays user
    }

    public function down(): void
    {
        // No-op: role simplification is permanent
    }
};
