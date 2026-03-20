<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('n8n_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('W01-W35');
            $table->string('name');
            $table->string('n8n_workflow_id', 50)->nullable()->comment('ID in n8n instance');
            $table->string('module', 30)->index()->comment('leads,ads,seo,social,brand,accounting,core,system,news');
            $table->string('trigger_type', 30)->default('cron')->comment('cron,webhook,event');
            $table->string('frequency', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('last_status', 20)->nullable()->comment('success,failed,running,unknown');
            $table->dateTime('last_executed_at')->nullable();
            $table->unsignedInteger('execution_count')->default(0);
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('n8n_workflows');
    }
};
