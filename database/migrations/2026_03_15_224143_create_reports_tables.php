<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('scheduled_reports')) {
            Schema::create('scheduled_reports', function (Blueprint $table) {
                $table->id();
                $table->string('report_type');
                $table->string('format', 10)->default('pdf');
                $table->string('frequency', 20)->default('weekly'); // daily, weekly, monthly
                $table->json('recipients')->nullable();
                $table->json('parameters')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamp('last_run_at')->nullable();
                $table->timestamp('next_run_at')->nullable();
                $table->timestamps();

                $table->index('active');
                $table->index('next_run_at');
            });
        }

        if (!Schema::hasTable('generated_reports')) {
            Schema::create('generated_reports', function (Blueprint $table) {
                $table->id();
                $table->string('report_type');
                $table->string('format', 10);
                $table->string('filename');
                $table->string('path');
                $table->json('parameters')->nullable();
                $table->unsignedBigInteger('generated_by')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->timestamps();

                $table->index('report_type');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_reports');
        Schema::dropIfExists('scheduled_reports');
    }
};
