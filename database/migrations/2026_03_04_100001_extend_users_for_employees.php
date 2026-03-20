<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend users table with employee fields
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id', 20)->nullable()->unique()->after('avatar_url');
            $table->string('position', 100)->nullable()->after('employee_id');
            $table->decimal('salary', 10, 2)->nullable()->after('position');
            $table->string('contract_type', 30)->nullable()->after('salary'); // umowa_o_prace, umowa_zlecenie, b2b
            $table->date('hire_date')->nullable()->after('contract_type');
            $table->date('contract_end_date')->nullable()->after('hire_date');
            $table->json('work_schedule')->nullable()->after('contract_end_date'); // {"mon":"09:00-18:00","tue":"09:00-18:00",...}
            $table->string('emergency_contact', 200)->nullable()->after('work_schedule');
            $table->string('emergency_phone', 30)->nullable()->after('emergency_contact');
        });

        // Employee time tracking (clock in/out)
        Schema::create('employee_time_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->decimal('hours_worked', 5, 2)->nullable();
            $table->string('type', 20)->default('regular'); // regular, overtime, remote
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'clock_in']);
        });

        // Staff internal messages (boss-chat + team)
        Schema::create('staff_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->string('type', 20)->default('message'); // message, boss_chat, announcement
            $table->boolean('is_encrypted')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->timestamps();

            $table->index(['receiver_id', 'read_at']);
            $table->index(['sender_id', 'created_at']);
        });

        // Employee KPIs / performance
        Schema::create('employee_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('period', 7); // 2026-03
            $table->integer('cases_completed')->default(0);
            $table->integer('cases_opened')->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_overdue')->default(0);
            $table->integer('clients_served')->default(0);
            $table->decimal('avg_case_duration_days', 6, 1)->nullable();
            $table->decimal('client_satisfaction', 3, 2)->nullable(); // 0.00-5.00
            $table->decimal('hours_worked', 6, 1)->default(0);
            $table->text('manager_notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_kpis');
        Schema::dropIfExists('staff_messages');
        Schema::dropIfExists('employee_time_tracking');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id', 'position', 'salary', 'contract_type',
                'hire_date', 'contract_end_date', 'work_schedule',
                'emergency_contact', 'emergency_phone',
            ]);
        });
    }
};
