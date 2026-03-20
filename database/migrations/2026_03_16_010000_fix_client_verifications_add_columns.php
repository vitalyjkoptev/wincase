<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Original migration only created id + timestamps.
        // Add the actual columns that the model and AuthologicService need.
        if (!Schema::hasColumn('client_verifications', 'client_id')) {
            Schema::table('client_verifications', function (Blueprint $table) {
                $table->foreignId('client_id')->after('id')->constrained('clients')->cascadeOnDelete();
                $table->string('type', 50)->default('identity')->after('client_id');
                $table->string('status', 30)->default('pending')->after('type');
                $table->foreignId('document_id')->nullable()->after('status');
                $table->foreignId('verified_by')->nullable()->after('document_id');
                $table->timestamp('verified_at')->nullable()->after('verified_by');
                $table->date('expires_at')->nullable()->after('verified_at');
                $table->text('notes')->nullable()->after('expires_at');
                $table->json('result_data')->nullable()->after('notes');

                $table->index(['client_id', 'status']);
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('client_verifications', function (Blueprint $table) {
            $table->dropColumn([
                'client_id', 'type', 'status', 'document_id',
                'verified_by', 'verified_at', 'expires_at', 'notes', 'result_data',
            ]);
        });
    }
};
