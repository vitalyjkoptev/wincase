<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Align server schema with local development.
 *
 * Server reality (32 tables, MariaDB 10.11.16):
 *   users(23 cols), leads(15), cases(17), clients(20), invoices(18),
 *   tasks(14), documents(15), payments(11), messages(8 — old wincasejobs format)
 *   + applications, employers, seekers, vacancies, conversations, tickets,
 *     ticket_messages, parsed_jobs, landing_templates, client_registrations,
 *     calendar_events, activity_log, settings, notifications, sessions, etc.
 *
 * This migration:
 *   1. Adds missing columns to existing tables (safe: hasColumn checks)
 *   2. Syncs data from old column names to new ones
 *   3. Creates 20 new tables (safe: hasTable checks)
 *   4. Does NOT touch/drop existing server-only tables
 *   5. Does NOT alter messages table (different structure, will handle separately)
 *
 * Safe to run multiple times.
 */
return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // 1. LEADS — add missing columns
        // Server has: id, first_name, last_name, email, phone, source,
        //   status, service_type, nationality, notes, assigned_to,
        //   estimated_value, created_at, updated_at, deleted_at
        // =====================================================
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('leads', 'message')) {
                    $table->text('message')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('leads', 'client_id')) {
                    $table->unsignedBigInteger('client_id')->nullable()->after('assigned_to');
                }
                if (!Schema::hasColumn('leads', 'case_id')) {
                    $table->unsignedBigInteger('case_id')->nullable()->after('client_id');
                }
                if (!Schema::hasColumn('leads', 'first_contact_at')) {
                    $table->timestamp('first_contact_at')->nullable();
                }
                if (!Schema::hasColumn('leads', 'priority')) {
                    $table->string('priority', 20)->default('medium')->after('status');
                }
                if (!Schema::hasColumn('leads', 'utm_source')) {
                    $table->string('utm_source')->nullable();
                }
                if (!Schema::hasColumn('leads', 'utm_medium')) {
                    $table->string('utm_medium')->nullable();
                }
                if (!Schema::hasColumn('leads', 'utm_campaign')) {
                    $table->string('utm_campaign')->nullable();
                }
                if (!Schema::hasColumn('leads', 'utm_content')) {
                    $table->string('utm_content')->nullable();
                }
                if (!Schema::hasColumn('leads', 'utm_term')) {
                    $table->string('utm_term')->nullable();
                }
                if (!Schema::hasColumn('leads', 'gclid')) {
                    $table->string('gclid')->nullable();
                }
                if (!Schema::hasColumn('leads', 'fbclid')) {
                    $table->string('fbclid')->nullable();
                }
                if (!Schema::hasColumn('leads', 'landing_page')) {
                    $table->string('landing_page')->nullable();
                }
                if (!Schema::hasColumn('leads', 'referrer')) {
                    $table->string('referrer')->nullable();
                }
                if (!Schema::hasColumn('leads', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable();
                }
                if (!Schema::hasColumn('leads', 'user_agent')) {
                    $table->text('user_agent')->nullable();
                }
            });

            // Sync: fill 'name' from first_name + last_name where name is null
            if (Schema::hasColumn('leads', 'first_name') && Schema::hasColumn('leads', 'name')) {
                if (DB::getDriverName() === 'sqlite') {
                    DB::statement("UPDATE leads SET name = TRIM(COALESCE(first_name,'') || ' ' || COALESCE(last_name,'')) WHERE name IS NULL");
                } else {
                    DB::statement("UPDATE leads SET name = TRIM(CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,''))) WHERE name IS NULL");
                }
            }
        }

        // =====================================================
        // 2. CASES — add missing columns
        // Server has: id, case_number, client_id, assigned_to, type,
        //   status, priority, description, deadline, submission_date,
        //   decision_date, fee, paid, notes, created_at, updated_at, deleted_at
        // =====================================================
        if (Schema::hasTable('cases')) {
            Schema::table('cases', function (Blueprint $table) {
                if (!Schema::hasColumn('cases', 'service_type')) {
                    $table->string('service_type')->nullable()->after('type');
                }
                if (!Schema::hasColumn('cases', 'amount')) {
                    $table->decimal('amount', 12, 2)->nullable()->after('fee');
                }
                if (!Schema::hasColumn('cases', 'currency')) {
                    $table->string('currency', 3)->default('PLN')->after('amount');
                }
                if (!Schema::hasColumn('cases', 'is_paid')) {
                    $table->boolean('is_paid')->default(false)->after('paid');
                }
                if (!Schema::hasColumn('cases', 'voivodeship')) {
                    $table->string('voivodeship')->nullable();
                }
                if (!Schema::hasColumn('cases', 'office_name')) {
                    $table->string('office_name')->nullable();
                }
                if (!Schema::hasColumn('cases', 'appeal_deadline')) {
                    $table->date('appeal_deadline')->nullable();
                }
                if (!Schema::hasColumn('cases', 'documents_required')) {
                    $table->unsignedInteger('documents_required')->default(0);
                }
                if (!Schema::hasColumn('cases', 'documents_collected')) {
                    $table->unsignedInteger('documents_collected')->default(0);
                }
                if (!Schema::hasColumn('cases', 'progress_percentage')) {
                    $table->unsignedTinyInteger('progress_percentage')->default(0);
                }
                if (!Schema::hasColumn('cases', 'lead_id')) {
                    $table->unsignedBigInteger('lead_id')->nullable();
                }
                if (!Schema::hasColumn('cases', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable();
                }
                if (!Schema::hasColumn('cases', 'closed_at')) {
                    $table->timestamp('closed_at')->nullable();
                }
            });

            // Sync: service_type from type, amount from fee, is_paid from paid
            if (Schema::hasColumn('cases', 'type') && Schema::hasColumn('cases', 'service_type')) {
                DB::statement("UPDATE cases SET service_type = type WHERE service_type IS NULL AND type IS NOT NULL");
            }
            if (Schema::hasColumn('cases', 'fee') && Schema::hasColumn('cases', 'amount')) {
                DB::statement("UPDATE cases SET amount = fee WHERE amount IS NULL AND fee IS NOT NULL");
            }
            if (Schema::hasColumn('cases', 'paid') && Schema::hasColumn('cases', 'is_paid')) {
                // paid on server is tinyint/boolean
                DB::statement("UPDATE cases SET is_paid = paid WHERE is_paid = 0 AND paid IS NOT NULL AND paid != 0");
            }
        }

        // =====================================================
        // 3. CLIENTS — add missing columns
        // Server has: id, lead_id, first_name, last_name, email, phone,
        //   pesel, passport_number, passport_expiry, nationality,
        //   date_of_birth, address, city, postal_code, country,
        //   notes, status, created_at, updated_at, deleted_at
        // =====================================================
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (!Schema::hasColumn('clients', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }
                if (!Schema::hasColumn('clients', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable()->after('status');
                }
                if (!Schema::hasColumn('clients', 'voivodeship')) {
                    $table->string('voivodeship')->nullable()->after('country');
                }
                if (!Schema::hasColumn('clients', 'preferred_language')) {
                    $table->string('preferred_language', 5)->default('pl');
                }
                if (!Schema::hasColumn('clients', 'company_name')) {
                    $table->string('company_name')->nullable();
                }
                if (!Schema::hasColumn('clients', 'nip')) {
                    $table->string('nip', 20)->nullable();
                }
                if (!Schema::hasColumn('clients', 'gdpr_consent')) {
                    $table->boolean('gdpr_consent')->default(false);
                }
                if (!Schema::hasColumn('clients', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable();
                }
            });

            // Sync: fill 'name' from first_name + last_name
            if (Schema::hasColumn('clients', 'first_name') && Schema::hasColumn('clients', 'name')) {
                if (DB::getDriverName() === 'sqlite') {
                    DB::statement("UPDATE clients SET name = TRIM(COALESCE(first_name,'') || ' ' || COALESCE(last_name,'')) WHERE name IS NULL");
                } else {
                    DB::statement("UPDATE clients SET name = TRIM(CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,''))) WHERE name IS NULL");
                }
            }
        }

        // =====================================================
        // 4. INVOICES — add aliases / missing columns
        // Server has: id, invoice_number, client_id, case_id, amount,
        //   tax_rate, tax_amount, total, currency, status, issue_date,
        //   due_date, paid_date, payment_method, notes, timestamps, deleted_at
        // =====================================================
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'total_amount')) {
                    $table->decimal('total_amount', 12, 2)->nullable()->after('total');
                }
                if (!Schema::hasColumn('invoices', 'net_amount')) {
                    $table->decimal('net_amount', 12, 2)->nullable()->after('amount');
                }
                if (!Schema::hasColumn('invoices', 'vat_rate')) {
                    $table->decimal('vat_rate', 5, 2)->default(23)->after('tax_rate');
                }
                if (!Schema::hasColumn('invoices', 'vat_amount')) {
                    $table->decimal('vat_amount', 12, 2)->nullable()->after('tax_amount');
                }
                if (!Schema::hasColumn('invoices', 'gross_amount')) {
                    $table->decimal('gross_amount', 12, 2)->nullable()->after('total_amount');
                }
                if (!Schema::hasColumn('invoices', 'bank_account')) {
                    $table->string('bank_account')->nullable();
                }
                if (!Schema::hasColumn('invoices', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable();
                }
            });

            // Sync: total_amount from total, net_amount from amount, vat_rate from tax_rate, vat_amount from tax_amount
            if (Schema::hasColumn('invoices', 'total') && Schema::hasColumn('invoices', 'total_amount')) {
                DB::statement("UPDATE invoices SET total_amount = total WHERE total_amount IS NULL AND total IS NOT NULL");
            }
            if (Schema::hasColumn('invoices', 'amount') && Schema::hasColumn('invoices', 'net_amount')) {
                DB::statement("UPDATE invoices SET net_amount = amount WHERE net_amount IS NULL AND amount IS NOT NULL");
            }
            if (Schema::hasColumn('invoices', 'tax_rate') && Schema::hasColumn('invoices', 'vat_rate')) {
                DB::statement("UPDATE invoices SET vat_rate = tax_rate WHERE vat_rate = 23 AND tax_rate IS NOT NULL AND tax_rate != 23");
            }
            if (Schema::hasColumn('invoices', 'tax_amount') && Schema::hasColumn('invoices', 'vat_amount')) {
                DB::statement("UPDATE invoices SET vat_amount = tax_amount WHERE vat_amount IS NULL AND tax_amount IS NOT NULL");
            }
            // gross_amount = total
            if (Schema::hasColumn('invoices', 'total') && Schema::hasColumn('invoices', 'gross_amount')) {
                DB::statement("UPDATE invoices SET gross_amount = total WHERE gross_amount IS NULL AND total IS NOT NULL");
            }
        }

        // =====================================================
        // 5. TASKS — add missing columns
        // Server has: id, title, description, assigned_to, created_by,
        //   taskable_type, taskable_id, status, priority, due_date,
        //   completed_at, timestamps, deleted_at
        // Note: server uses polymorphic (taskable_type/id), we add direct FKs too
        // =====================================================
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('tasks', 'type')) {
                    $table->string('type', 50)->default('task')->after('id');
                }
                if (!Schema::hasColumn('tasks', 'case_id')) {
                    $table->unsignedBigInteger('case_id')->nullable()->after('taskable_id');
                }
                if (!Schema::hasColumn('tasks', 'client_id')) {
                    $table->unsignedBigInteger('client_id')->nullable()->after('case_id');
                }
                if (!Schema::hasColumn('tasks', 'lead_id')) {
                    $table->unsignedBigInteger('lead_id')->nullable()->after('client_id');
                }
                if (!Schema::hasColumn('tasks', 'reminder_at')) {
                    $table->timestamp('reminder_at')->nullable();
                }
            });

            // Sync: populate case_id/client_id from polymorphic where possible
            if (Schema::hasColumn('tasks', 'taskable_type') && Schema::hasColumn('tasks', 'case_id')) {
                DB::statement("UPDATE tasks SET case_id = taskable_id WHERE taskable_type LIKE '%Case%' AND case_id IS NULL AND taskable_id IS NOT NULL");
                DB::statement("UPDATE tasks SET client_id = taskable_id WHERE taskable_type LIKE '%Client%' AND client_id IS NULL AND taskable_id IS NOT NULL");
            }
        }

        // =====================================================
        // 6. USERS — add employee columns
        // Server has: id, name, email, phone, avatar, avatar_url, role(varchar),
        //   status, position, department, is_active, permissions,
        //   email_verified_at, password, remember_token, timestamps,
        //   deleted_at, last_login_at, last_login_ip,
        //   two_factor_enabled, two_factor_secret, two_factor_recovery_codes
        //
        // role is already VARCHAR — good!
        // Roles on server: admin(1), boss(3), staff(3), user(2)
        // =====================================================
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'hire_date')) {
                    $table->date('hire_date')->nullable();
                }
                if (!Schema::hasColumn('users', 'salary')) {
                    $table->decimal('salary', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('users', 'manager_id')) {
                    $table->unsignedBigInteger('manager_id')->nullable();
                }
                if (!Schema::hasColumn('users', 'bio')) {
                    $table->text('bio')->nullable();
                }
            });

            // Fix remaining non-standard roles → 3-role system
            DB::table('users')->where('role', 'admin')->update(['role' => 'boss']);
            DB::table('users')->where('role', 'director')->update(['role' => 'boss']);
            DB::table('users')->whereNotIn('role', ['boss', 'staff', 'user'])->update(['role' => 'staff']);
        }

        // =====================================================
        // 7. DOCUMENTS — add missing columns if needed
        // Server has polymorphic: documentable_type, documentable_id,
        //   name, type, file_path, original_name, file_size, mime_type,
        //   expiry_date, status, uploaded_by, timestamps, deleted_at
        // We need: client_id, case_id, verified_by as direct FKs
        // =====================================================
        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table) {
                if (!Schema::hasColumn('documents', 'client_id')) {
                    $table->unsignedBigInteger('client_id')->nullable();
                }
                if (!Schema::hasColumn('documents', 'case_id')) {
                    $table->unsignedBigInteger('case_id')->nullable();
                }
                if (!Schema::hasColumn('documents', 'verified_by')) {
                    $table->unsignedBigInteger('verified_by')->nullable();
                }
            });

            // Sync from polymorphic
            if (Schema::hasColumn('documents', 'documentable_type') && Schema::hasColumn('documents', 'client_id')) {
                DB::statement("UPDATE documents SET client_id = documentable_id WHERE documentable_type LIKE '%Client%' AND client_id IS NULL AND documentable_id IS NOT NULL");
                DB::statement("UPDATE documents SET case_id = documentable_id WHERE documentable_type LIKE '%Case%' AND case_id IS NULL AND documentable_id IS NOT NULL");
            }
        }

        // =====================================================
        // 8. PAYMENTS — add missing columns
        // Server has: id, invoice_id, client_id, amount, currency,
        //   method, status, reference, notes, timestamps
        // We need: case_id, payment_method (alias for method), paid_date
        // =====================================================
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'case_id')) {
                    $table->unsignedBigInteger('case_id')->nullable()->after('client_id');
                }
                if (!Schema::hasColumn('payments', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('method');
                }
                if (!Schema::hasColumn('payments', 'paid_date')) {
                    $table->date('paid_date')->nullable();
                }
            });

            // Sync payment_method from method
            if (Schema::hasColumn('payments', 'method') && Schema::hasColumn('payments', 'payment_method')) {
                DB::statement("UPDATE payments SET payment_method = method WHERE payment_method IS NULL AND method IS NOT NULL");
            }
        }

        // =====================================================
        // NOTE: messages table is NOT touched!
        // Server uses old wincasejobs structure:
        //   conversation_id, sender_type(enum), sender_id, message, attachment, is_read
        // Our CRM expects: from_user_id, to_user_id, client_id, case_id, channel
        // We'll use staff_messages (new table) for internal CRM messaging
        // and keep messages table as-is for legacy job platform
        // =====================================================

        // =====================================================
        // 9. CALENDAR_EVENTS — add missing columns if needed
        // Server has this table already
        // =====================================================
        if (Schema::hasTable('calendar_events')) {
            Schema::table('calendar_events', function (Blueprint $table) {
                if (!Schema::hasColumn('calendar_events', 'case_id')) {
                    $table->unsignedBigInteger('case_id')->nullable();
                }
                if (!Schema::hasColumn('calendar_events', 'client_id')) {
                    $table->unsignedBigInteger('client_id')->nullable();
                }
                if (!Schema::hasColumn('calendar_events', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable();
                }
                if (!Schema::hasColumn('calendar_events', 'event_type')) {
                    $table->string('event_type', 30)->default('meeting');
                }
                if (!Schema::hasColumn('calendar_events', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('calendar_events', 'is_all_day')) {
                    $table->boolean('is_all_day')->default(false);
                }
                if (!Schema::hasColumn('calendar_events', 'reminder_minutes')) {
                    $table->integer('reminder_minutes')->nullable();
                }
            });
        }

        // =====================================================
        // 10. CREATE MISSING TABLES (20 new)
        // All with hasTable guard — safe to re-run
        // =====================================================

        if (!Schema::hasTable('ads_performance')) {
            Schema::create('ads_performance', function (Blueprint $table) {
                $table->id();
                $table->string('platform', 30);
                $table->string('campaign_id')->nullable();
                $table->string('campaign_name')->nullable();
                $table->date('date');
                $table->decimal('cost', 12, 2)->default(0);
                $table->integer('impressions')->default(0);
                $table->integer('clicks')->default(0);
                $table->integer('leads_count')->default(0);
                $table->decimal('cpc', 8, 2)->default(0);
                $table->decimal('cpl', 8, 2)->default(0);
                $table->timestamps();
                $table->index(['platform', 'date']);
            });
        }

        if (!Schema::hasTable('seo_data')) {
            Schema::create('seo_data', function (Blueprint $table) {
                $table->id();
                $table->string('source', 20);
                $table->string('domain')->nullable();
                $table->date('date');
                $table->integer('clicks')->default(0);
                $table->integer('impressions')->default(0);
                $table->decimal('avg_position', 6, 2)->nullable();
                $table->integer('users')->default(0);
                $table->integer('sessions')->default(0);
                $table->integer('domain_authority')->nullable();
                $table->integer('backlinks')->default(0);
                $table->integer('referring_domains')->default(0);
                $table->timestamps();
                $table->index(['source', 'date']);
            });
        }

        if (!Schema::hasTable('seo_network_sites')) {
            Schema::create('seo_network_sites', function (Blueprint $table) {
                $table->id();
                $table->string('domain');
                $table->string('platform', 20)->default('wordpress');
                $table->string('status', 20)->default('active');
                $table->integer('domain_authority')->default(0);
                $table->integer('articles_total')->default(0);
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('social_accounts')) {
            Schema::create('social_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('platform', 30);
                $table->string('account_id')->nullable();
                $table->string('account_name')->nullable();
                $table->integer('followers')->default(0);
                $table->integer('posts_count')->default(0);
                $table->string('access_token', 1000)->nullable();
                $table->timestamp('token_expires_at')->nullable();
                $table->timestamp('last_synced_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('social_posts')) {
            Schema::create('social_posts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('social_account_id')->nullable();
                $table->string('platform', 30);
                $table->string('post_id')->nullable();
                $table->text('text')->nullable();
                $table->string('media_url')->nullable();
                $table->string('status', 20)->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pos_transactions')) {
            Schema::create('pos_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('invoice_id')->nullable();
                $table->unsignedBigInteger('received_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('PLN');
                $table->string('payment_method', 20)->default('cash');
                $table->string('status', 20)->default('received');
                $table->text('notes')->nullable();
                $table->string('receipt_number')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tax_reports')) {
            Schema::create('tax_reports', function (Blueprint $table) {
                $table->id();
                $table->string('report_type', 30);
                $table->date('period_start');
                $table->date('period_end');
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->string('status', 20)->default('calculated');
                $table->date('due_date')->nullable();
                $table->date('filed_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('category', 50);
                $table->string('description')->nullable();
                $table->decimal('net_amount', 12, 2);
                $table->decimal('vat_amount', 12, 2)->default(0);
                $table->decimal('gross_amount', 12, 2);
                $table->date('date');
                $table->string('vendor')->nullable();
                $table->string('invoice_number')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_kpis')) {
            Schema::create('employee_kpis', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->date('period_date');
                $table->integer('cases_completed')->default(0);
                $table->integer('leads_converted')->default(0);
                $table->integer('tasks_completed')->default(0);
                $table->decimal('revenue_generated', 12, 2)->default(0);
                $table->decimal('rating', 3, 2)->nullable();
                $table->timestamps();
                $table->index(['user_id', 'period_date']);
            });
        }

        if (!Schema::hasTable('employee_time_tracking')) {
            Schema::create('employee_time_tracking', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->date('date');
                $table->time('clock_in')->nullable();
                $table->time('clock_out')->nullable();
                $table->decimal('hours_worked', 5, 2)->default(0);
                $table->string('status', 20)->default('present');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'date']);
            });
        }

        if (!Schema::hasTable('staff_messages')) {
            Schema::create('staff_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('from_user_id');
                $table->unsignedBigInteger('to_user_id');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
                $table->index(['to_user_id', 'is_read']);
            });
        }

        if (!Schema::hasTable('n8n_workflows')) {
            Schema::create('n8n_workflows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('n8n_workflow_id')->nullable();
                $table->string('status', 20)->default('active');
                $table->string('trigger_type', 30)->nullable();
                $table->string('category', 30)->nullable();
                $table->timestamp('last_executed_at')->nullable();
                $table->integer('execution_count')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hearings')) {
            Schema::create('hearings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('case_id');
                $table->dateTime('hearing_date');
                $table->string('location')->nullable();
                $table->string('type', 50)->nullable();
                $table->string('status', 20)->default('scheduled');
                $table->text('notes')->nullable();
                $table->text('result')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('news_articles')) {
            Schema::create('news_articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content')->nullable();
                $table->text('original_content')->nullable();
                $table->string('source_url')->nullable();
                $table->string('source_name')->nullable();
                $table->string('site_domain')->nullable();
                $table->string('category')->nullable();
                $table->string('language', 5)->default('en');
                $table->string('status', 20)->default('draft');
                $table->integer('wp_post_id')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->index(['site_domain', 'status']);
            });
        }

        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->string('platform', 30);
                $table->string('external_id')->nullable();
                $table->tinyInteger('rating')->nullable();
                $table->text('text')->nullable();
                $table->string('author_name')->nullable();
                $table->string('status', 20)->default('pending');
                $table->text('reply')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('landing_pages')) {
            Schema::create('landing_pages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('domain')->nullable();
                $table->string('status', 20)->default('draft');
                $table->integer('visits')->default(0);
                $table->integer('conversions')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('accounting_periods')) {
            Schema::create('accounting_periods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status', 20)->default('open');
                $table->decimal('total_revenue', 14, 2)->default(0);
                $table->decimal('total_expenses', 14, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('client_verifications')) {
            Schema::create('client_verifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->string('type', 30);
                $table->string('status', 20)->default('pending');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ai_generations')) {
            Schema::create('ai_generations', function (Blueprint $table) {
                $table->id();
                $table->string('type', 30);
                $table->text('prompt')->nullable();
                $table->text('result')->nullable();
                $table->string('model', 50)->nullable();
                $table->integer('tokens_used')->default(0);
                $table->decimal('cost', 8, 4)->default(0);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('brand_listings')) {
            Schema::create('brand_listings', function (Blueprint $table) {
                $table->id();
                $table->string('platform', 50);
                $table->string('listing_url')->nullable();
                $table->string('status', 20)->default('active');
                $table->decimal('rating', 3, 2)->nullable();
                $table->integer('reviews_count')->default(0);
                $table->boolean('is_claimed')->default(false);
                $table->timestamp('last_checked_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop only tables WE created (not existing server tables)
        $newTables = [
            'brand_listings', 'ai_generations', 'client_verifications',
            'accounting_periods', 'landing_pages', 'reviews', 'news_articles',
            'hearings', 'n8n_workflows', 'staff_messages', 'employee_time_tracking',
            'employee_kpis', 'expenses', 'tax_reports', 'pos_transactions',
            'social_posts', 'social_accounts', 'seo_network_sites', 'seo_data',
            'ads_performance',
        ];

        foreach ($newTables as $table) {
            Schema::dropIfExists($table);
        }

        // Note: added columns on existing tables are NOT dropped in down()
        // to prevent data loss on server.
    }
};
