<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==================== CLIENTS ====================
        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable()->after('id');
            $table->string('last_name', 100)->nullable()->after('first_name');
            $table->string('name', 200)->nullable()->after('last_name');
            $table->string('email', 191)->nullable()->after('name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('nationality', 3)->nullable()->after('phone');
            $table->string('passport_number', 50)->nullable()->after('nationality');
            $table->string('pesel', 11)->nullable()->after('passport_number');
            $table->date('date_of_birth')->nullable()->after('pesel');
            $table->string('address', 255)->nullable()->after('date_of_birth');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('postal_code', 10)->nullable()->after('city');
            $table->string('voivodeship', 50)->nullable()->after('postal_code');
            $table->string('preferred_language', 5)->default('pl')->after('voivodeship');
            $table->enum('status', ['active', 'archived', 'blacklisted'])->default('active')->after('preferred_language');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete()->after('status');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('lead_id');
            $table->string('company_name', 200)->nullable()->after('assigned_to');
            $table->string('nip', 15)->nullable()->after('company_name');
            $table->boolean('gdpr_consent')->default(false)->after('nip');
            $table->text('notes')->nullable()->after('gdpr_consent');
            $table->timestamp('archived_at')->nullable()->after('notes');
            $table->index('status');
            $table->index('nationality');
            $table->index('assigned_to');
        });

        // ==================== CASES ====================
        Schema::table('cases', function (Blueprint $table) {
            $table->string('case_number', 20)->unique()->after('id');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('case_number');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete()->after('client_id');
            $table->string('service_type', 50)->after('lead_id');
            $table->string('status', 50)->default('new')->after('service_type');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('assigned_to');
            $table->string('voivodeship', 50)->nullable()->after('priority');
            $table->string('office_name', 200)->nullable()->after('voivodeship');
            $table->date('submission_date')->nullable()->after('office_name');
            $table->date('decision_date')->nullable()->after('submission_date');
            $table->date('deadline')->nullable()->after('decision_date');
            $table->date('appeal_deadline')->nullable()->after('deadline');
            $table->json('documents_required')->nullable()->after('appeal_deadline');
            $table->json('documents_collected')->nullable()->after('documents_required');
            $table->unsignedTinyInteger('progress_percentage')->default(0)->after('documents_collected');
            $table->decimal('amount', 10, 2)->nullable()->after('progress_percentage');
            $table->string('currency', 3)->default('PLN')->after('amount');
            $table->boolean('is_paid')->default(false)->after('currency');
            $table->text('notes')->nullable()->after('is_paid');
            $table->timestamp('completed_at')->nullable()->after('notes');
            $table->timestamp('closed_at')->nullable()->after('completed_at');
            $table->index('status');
            $table->index('service_type');
            $table->index('assigned_to');
            $table->index('deadline');
        });

        // ==================== DOCUMENTS ====================
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('id');
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete()->after('client_id');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete()->after('case_id');
            $table->string('type', 50)->after('uploaded_by');
            $table->string('name', 255)->after('type');
            $table->string('original_filename', 255)->nullable()->after('name');
            $table->string('file_path', 500)->after('original_filename');
            $table->unsignedInteger('file_size')->default(0)->after('file_path');
            $table->string('mime_type', 100)->nullable()->after('file_size');
            $table->enum('status', ['pending', 'verified', 'rejected', 'expired'])->default('pending')->after('mime_type');
            $table->date('expires_at')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('expires_at');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
            $table->string('rejection_reason', 500)->nullable()->after('verified_by');
            $table->text('notes')->nullable()->after('rejection_reason');
            $table->index(['client_id', 'type']);
            $table->index('expires_at');
        });

        // ==================== TASKS ====================
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title', 255)->after('id');
            $table->text('description')->nullable()->after('title');
            $table->enum('type', ['call', 'meeting', 'document', 'deadline', 'follow_up', 'other'])->default('other')->after('description');
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete()->after('type');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('case_id');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete()->after('client_id');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()->after('lead_id');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('assigned_to');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('created_by');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('priority');
            $table->date('due_date')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('due_date');
            $table->timestamp('reminder_at')->nullable()->after('completed_at');
            $table->index('status');
            $table->index('assigned_to');
            $table->index('due_date');
        });

        // ==================== INVOICES ====================
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number', 30)->unique()->after('id');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('invoice_number');
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete()->after('client_id');
            $table->date('issue_date')->after('case_id');
            $table->date('due_date')->after('issue_date');
            $table->date('paid_date')->nullable()->after('due_date');
            $table->decimal('net_amount', 10, 2)->default(0)->after('paid_date');
            $table->decimal('vat_rate', 5, 2)->default(23.00)->after('net_amount');
            $table->decimal('vat_amount', 10, 2)->default(0)->after('vat_rate');
            $table->decimal('gross_amount', 10, 2)->default(0)->after('vat_amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('gross_amount');
            $table->string('currency', 3)->default('PLN')->after('total_amount');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft')->after('currency');
            $table->string('payment_method', 50)->nullable()->after('status');
            $table->string('bank_account', 50)->nullable()->after('payment_method');
            $table->text('notes')->nullable()->after('bank_account');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
            $table->index('status');
            $table->index('due_date');
        });

        // ==================== PAYMENTS ====================
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete()->after('id');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('invoice_id');
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete()->after('client_id');
            $table->unsignedBigInteger('pos_transaction_id')->nullable()->after('case_id');
            $table->decimal('amount', 10, 2)->after('pos_transaction_id');
            $table->string('currency', 3)->default('PLN')->after('amount');
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'online'])->after('currency');
            $table->date('payment_date')->after('payment_method');
            $table->string('reference_number', 100)->nullable()->after('payment_date');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->after('reference_number');
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->text('notes')->nullable()->after('received_by');
            $table->index('status');
            $table->index('payment_date');
        });

        // ==================== HEARINGS ====================
        Schema::table('hearings', function (Blueprint $table) {
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete()->after('id');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('case_id');
            $table->date('hearing_date')->after('client_id');
            $table->time('hearing_time')->nullable()->after('hearing_date');
            $table->string('office_name', 200)->after('hearing_time');
            $table->string('room_number', 50)->nullable()->after('office_name');
            $table->enum('type', ['interview', 'appeal_hearing', 'document_submission', 'oath', 'other'])->default('interview')->after('room_number');
            $table->enum('status', ['scheduled', 'completed', 'postponed', 'cancelled'])->default('scheduled')->after('type');
            $table->boolean('interpreter_needed')->default(false)->after('status');
            $table->string('interpreter_language', 5)->nullable()->after('interpreter_needed');
            $table->text('result')->nullable()->after('interpreter_language');
            $table->text('notes')->nullable()->after('result');
            $table->boolean('reminder_sent')->default(false)->after('notes');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('reminder_sent');
            $table->index('hearing_date');
            $table->index('status');
        });

        // ==================== MESSAGES ====================
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->after('id');
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete()->after('client_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('case_id');
            $table->enum('channel', ['email', 'sms', 'whatsapp', 'telegram', 'internal'])->after('user_id');
            $table->enum('direction', ['inbound', 'outbound'])->after('channel');
            $table->string('subject', 255)->nullable()->after('direction');
            $table->text('body')->after('subject');
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent')->after('body');
            $table->string('type', 50)->nullable()->after('status');
            $table->timestamp('sent_at')->nullable()->after('type');
            $table->timestamp('read_at')->nullable()->after('sent_at');
            $table->index(['client_id', 'channel']);
        });

        // ==================== SOCIAL ACCOUNTS ====================
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->string('platform', 30)->after('id');
            $table->string('account_name', 200)->after('platform');
            $table->string('account_id', 200)->nullable()->after('account_name');
            $table->text('access_token')->nullable()->after('account_id');
            $table->text('refresh_token')->nullable()->after('access_token');
            $table->timestamp('token_expires_at')->nullable()->after('refresh_token');
            $table->unsignedInteger('followers_count')->default(0)->after('token_expires_at');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active')->after('followers_count');
            $table->json('meta')->nullable()->after('status');
            $table->index('platform');
        });

        // ==================== SOCIAL POSTS ====================
        Schema::table('social_posts', function (Blueprint $table) {
            $table->foreignId('social_account_id')->nullable()->constrained('social_accounts')->nullOnDelete()->after('id');
            $table->string('platform', 30)->after('social_account_id');
            $table->string('external_id', 200)->nullable()->after('platform');
            $table->text('content')->nullable()->after('external_id');
            $table->json('media_urls')->nullable()->after('content');
            $table->string('post_type', 30)->default('text')->after('media_urls');
            $table->enum('status', ['draft', 'scheduled', 'published', 'failed'])->default('draft')->after('post_type');
            $table->timestamp('scheduled_at')->nullable()->after('status');
            $table->timestamp('published_at')->nullable()->after('scheduled_at');
            $table->unsignedInteger('likes')->default(0)->after('published_at');
            $table->unsignedInteger('comments')->default(0)->after('likes');
            $table->unsignedInteger('shares')->default(0)->after('comments');
            $table->unsignedInteger('reach')->default(0)->after('shares');
            $table->unsignedInteger('impressions')->default(0)->after('reach');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('impressions');
            $table->index('platform');
            $table->index('status');
        });

        // ==================== CLIENT VERIFICATIONS ====================
        Schema::table('client_verifications', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete()->after('id');
            $table->string('type', 50)->after('client_id');
            $table->enum('status', ['pending', 'verified', 'rejected', 'expired'])->default('pending')->after('type');
            $table->foreignId('document_id')->nullable()->constrained('documents')->nullOnDelete()->after('status');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('document_id');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->date('expires_at')->nullable()->after('verified_at');
            $table->text('notes')->nullable()->after('expires_at');
            $table->json('result_data')->nullable()->after('notes');
            $table->index(['client_id', 'type']);
        });

        // ==================== N8N WORKFLOWS ====================
        Schema::table('n8n_workflows', function (Blueprint $table) {
            $table->string('name', 200)->after('id');
            $table->string('n8n_workflow_id', 50)->nullable()->after('name');
            $table->text('description')->nullable()->after('n8n_workflow_id');
            $table->string('trigger_type', 50)->nullable()->after('description');
            $table->string('trigger_event', 100)->nullable()->after('trigger_type');
            $table->boolean('is_active')->default(true)->after('trigger_event');
            $table->timestamp('last_executed_at')->nullable()->after('is_active');
            $table->unsignedInteger('execution_count')->default(0)->after('last_executed_at');
            $table->string('last_status', 30)->nullable()->after('execution_count');
            $table->json('config')->nullable()->after('last_status');
        });

        // ==================== AI GENERATIONS ====================
        Schema::table('ai_generations', function (Blueprint $table) {
            $table->string('type', 50)->after('id');
            $table->string('model', 50)->after('type');
            $table->text('prompt')->after('model');
            $table->longText('result')->nullable()->after('prompt');
            $table->unsignedInteger('tokens_used')->default(0)->after('result');
            $table->decimal('cost', 8, 4)->default(0)->after('tokens_used');
            $table->string('source_type', 50)->nullable()->after('cost');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->after('source_id');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        // Reversing all changes...
        $tables = [
            'clients' => ['first_name','last_name','name','email','phone','nationality','passport_number','pesel','date_of_birth','address','city','postal_code','voivodeship','preferred_language','status','lead_id','assigned_to','company_name','nip','gdpr_consent','notes','archived_at'],
            'cases' => ['case_number','client_id','lead_id','service_type','status','assigned_to','priority','voivodeship','office_name','submission_date','decision_date','deadline','appeal_deadline','documents_required','documents_collected','progress_percentage','amount','currency','is_paid','notes','completed_at','closed_at'],
            'documents' => ['client_id','case_id','uploaded_by','type','name','original_filename','file_path','file_size','mime_type','status','expires_at','verified_at','verified_by','rejection_reason','notes'],
            'tasks' => ['title','description','type','case_id','client_id','lead_id','assigned_to','created_by','priority','status','due_date','completed_at','reminder_at'],
            'invoices' => ['invoice_number','client_id','case_id','issue_date','due_date','paid_date','net_amount','vat_rate','vat_amount','gross_amount','total_amount','currency','status','payment_method','bank_account','notes','created_by'],
            'payments' => ['invoice_id','client_id','case_id','pos_transaction_id','amount','currency','payment_method','payment_date','reference_number','status','received_by','notes'],
            'hearings' => ['case_id','client_id','hearing_date','hearing_time','office_name','room_number','type','status','interpreter_needed','interpreter_language','result','notes','reminder_sent','created_by'],
            'messages' => ['client_id','case_id','user_id','channel','direction','subject','body','status','type','sent_at','read_at'],
            'social_accounts' => ['platform','account_name','account_id','access_token','refresh_token','token_expires_at','followers_count','status','meta'],
            'social_posts' => ['social_account_id','platform','external_id','content','media_urls','post_type','status','scheduled_at','published_at','likes','comments','shares','reach','impressions','created_by'],
            'client_verifications' => ['client_id','type','status','document_id','verified_by','verified_at','expires_at','notes','result_data'],
            'n8n_workflows' => ['name','n8n_workflow_id','description','trigger_type','trigger_event','is_active','last_executed_at','execution_count','last_status','config'],
            'ai_generations' => ['type','model','prompt','result','tokens_used','cost','source_type','source_id','status','created_by'],
        ];

        foreach ($tables as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($columns) {
                $t->dropColumn($columns);
            });
        }
    }
};
