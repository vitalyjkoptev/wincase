<?php

// =====================================================
// FILE: tests/Feature/AuthTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    public function test_login_success(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123'), 'status' => 'active']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['success', 'data' => ['token', 'user']]);
    }

    public function test_login_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(401);
    }

    public function test_login_brute_force_protection(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'test@test.com',
                'password' => 'wrong',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@test.com',
            'password' => 'anything',
        ]);

        $response->assertStatus(429);
    }

    public function test_get_current_user(): void
    {
        $response = $this->actingAs($this->admin)->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('data.role', 'admin');
    }

    public function test_logout(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/v1/auth/logout');
        $response->assertOk();
    }

    public function test_unauthenticated_access(): void
    {
        $response = $this->getJson('/api/v1/auth/me');
        $response->assertStatus(401);
    }
}

// =====================================================
// FILE: tests/Feature/LeadsTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadsTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = User::factory()->create(['role' => 'manager', 'status' => 'active']);
    }

    public function test_list_leads(): void
    {
        Lead::factory()->count(5)->create();

        $response = $this->actingAs($this->manager)->getJson('/api/v1/leads');

        $response->assertOk()
            ->assertJsonStructure(['success', 'data' => ['data', 'meta']]);
    }

    public function test_create_lead(): void
    {
        $response = $this->actingAs($this->manager)->postJson('/api/v1/leads', [
            'name' => 'Test Lead',
            'phone' => '+48579266493',
            'source' => 'website',
            'service_type' => 'work_permit',
            'language' => 'pl',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Lead')
            ->assertJsonPath('data.status', 'new');

        $this->assertDatabaseHas('leads', ['name' => 'Test Lead', 'phone' => '+48579266493']);
    }

    public function test_create_lead_validation(): void
    {
        $response = $this->actingAs($this->manager)->postJson('/api/v1/leads', [
            'name' => '', // required
        ]);

        $response->assertStatus(422);
    }

    public function test_update_lead_status(): void
    {
        $lead = Lead::factory()->create(['status' => 'new']);

        $response = $this->actingAs($this->manager)->patchJson("/api/v1/leads/{$lead->id}", [
            'status' => 'contacted',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'contacted');
    }

    public function test_convert_lead_to_client(): void
    {
        $lead = Lead::factory()->create(['status' => 'paid']);

        $response = $this->actingAs($this->manager)->postJson("/api/v1/leads/{$lead->id}/convert");

        $response->assertOk()
            ->assertJsonPath('data.status', 'converted');
    }

    public function test_lead_sources_list(): void
    {
        $response = $this->actingAs($this->manager)->getJson('/api/v1/leads/sources');

        $response->assertOk()
            ->assertJsonCount(14, 'data'); // 14 acquisition sources
    }

    public function test_lead_webhook(): void
    {
        $response = $this->postJson('/api/v1/leads/webhook/facebook', [
            'name' => 'Facebook Lead',
            'phone' => '+48111222333',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('leads', ['source' => 'facebook']);
    }

    public function test_operator_cannot_delete_leads(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);
        $lead = Lead::factory()->create();

        $response = $this->actingAs($operator)->deleteJson("/api/v1/leads/{$lead->id}");
        $response->assertStatus(403);
    }
}

// =====================================================
// FILE: tests/Feature/CasesTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\{User, Client, CrmCase};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CasesTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->client = Client::factory()->create();
    }

    public function test_create_case(): void
    {
        $response = $this->actingAs($this->manager)->postJson('/api/v1/cases', [
            'client_id' => $this->client->id,
            'service_type' => 'work_permit',
            'description' => 'Work permit application',
            'deadline' => now()->addMonths(3)->toDateString(),
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'active');
    }

    public function test_case_status_workflow(): void
    {
        $case = CrmCase::factory()->create(['status' => 'active']);

        // active → pending_docs
        $response = $this->actingAs($this->manager)
            ->postJson("/api/v1/cases/{$case->id}/status", ['status' => 'pending_docs']);
        $response->assertOk();

        // pending_docs → submitted
        $response = $this->actingAs($this->manager)
            ->postJson("/api/v1/cases/{$case->id}/status", ['status' => 'submitted']);
        $response->assertOk();
    }

    public function test_invalid_status_transition(): void
    {
        $case = CrmCase::factory()->create(['status' => 'active']);

        // active → completed (not allowed directly)
        $response = $this->actingAs($this->manager)
            ->postJson("/api/v1/cases/{$case->id}/status", ['status' => 'completed']);
        $response->assertStatus(422);
    }

    public function test_assign_case(): void
    {
        $case = CrmCase::factory()->create();

        $response = $this->actingAs($this->manager)
            ->postJson("/api/v1/cases/{$case->id}/assign", ['user_id' => $this->manager->id]);

        $response->assertOk()
            ->assertJsonPath('data.assigned_to', $this->manager->id);
    }
}

// =====================================================
// FILE: tests/Feature/POSTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class POSTest extends TestCase
{
    use RefreshDatabase;

    protected User $operator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->operator = User::factory()->create(['role' => 'operator']);
    }

    public function test_receive_payment(): void
    {
        $response = $this->actingAs($this->operator)->postJson('/api/v1/pos/receive', [
            'amount' => 1500.00,
            'client_name' => 'Jan Kowalski',
            'phone' => '+48111222333',
            'payment_method' => 'card',
            'service_type' => 'work_permit',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'pending');
    }

    public function test_approve_payment(): void
    {
        $tx = \App\Models\Transaction::factory()->create(['status' => 'pending']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson("/api/v1/pos/{$tx->id}/approve");

        $response->assertOk()
            ->assertJsonPath('data.status', 'approved');
    }

    public function test_vat_calculation(): void
    {
        $response = $this->actingAs($this->operator)->postJson('/api/v1/pos/receive', [
            'amount' => 1230.00,
            'client_name' => 'Test',
            'phone' => '+48111222333',
            'payment_method' => 'blik',
            'service_type' => 'consultation',
        ]);

        $response->assertStatus(201);
        // VAT 23%: 1230 / 1.23 = 1000 net, 230 VAT
        $this->assertEquals(230.00, round($response->json('data.vat_amount'), 2));
    }
}

// =====================================================
// FILE: tests/Feature/RBACTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->getJson('/api/v1/users');
        $response->assertOk();
    }

    public function test_manager_cannot_access_users(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->getJson('/api/v1/users');
        $response->assertStatus(403);
    }

    public function test_viewer_read_only(): void
    {
        $viewer = User::factory()->create(['role' => 'viewer']);

        $this->actingAs($viewer)->getJson('/api/v1/dashboard')->assertOk();
        $this->actingAs($viewer)->getJson('/api/v1/leads')->assertOk();
        $this->actingAs($viewer)->postJson('/api/v1/leads', ['name' => 'Test'])->assertStatus(403);
    }

    public function test_accountant_can_access_accounting(): void
    {
        $accountant = User::factory()->create(['role' => 'accountant']);

        $this->actingAs($accountant)->getJson('/api/v1/accounting/invoices')->assertOk();
        $this->actingAs($accountant)->getJson('/api/v1/accounting/tax/vat')->assertOk();
    }

    public function test_accountant_cannot_manage_leads(): void
    {
        $accountant = User::factory()->create(['role' => 'accountant']);

        $this->actingAs($accountant)->postJson('/api/v1/leads', ['name' => 'Test'])->assertStatus(403);
    }

    public function test_admin_can_change_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($admin)
            ->postJson("/api/v1/users/{$user->id}/role", ['role' => 'manager']);

        $response->assertOk();
        $this->assertEquals('manager', $user->fresh()->role);
    }
}

// =====================================================
// FILE: tests/Feature/NewsTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\User;
use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_list_news_sources(): void
    {
        $response = $this->actingAs($this->admin)->getJson('/api/v1/news/sources');

        $response->assertOk()
            ->assertJsonPath('data.stats.total_sources', 27);
    }

    public function test_list_articles(): void
    {
        NewsArticle::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/news/articles');

        $response->assertOk()
            ->assertJsonStructure(['success', 'data' => ['data', 'meta']]);
    }

    public function test_approve_article(): void
    {
        $article = NewsArticle::factory()->create(['status' => 'needs_review']);

        $response = $this->actingAs($this->admin)
            ->postJson("/api/v1/news/articles/{$article->id}/approve");

        $response->assertOk()
            ->assertJsonPath('data.status', 'rewritten');
    }

    public function test_reject_article(): void
    {
        $article = NewsArticle::factory()->create(['status' => 'parsed']);

        $response = $this->actingAs($this->admin)
            ->postJson("/api/v1/news/articles/{$article->id}/reject");

        $response->assertOk()
            ->assertJsonPath('data.status', 'rejected');
    }

    public function test_news_statistics(): void
    {
        $response = $this->actingAs($this->admin)->getJson('/api/v1/news/statistics');
        $response->assertOk()
            ->assertJsonStructure(['success', 'data' => ['total_parsed', 'by_status']]);
    }
}

// =====================================================
// FILE: tests/Feature/NotificationsTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\{User, Notification};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_notifications(): void
    {
        $user = User::factory()->create();
        Notification::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/v1/notifications');

        $response->assertOk()
            ->assertJsonStructure(['success', 'data' => ['notifications', 'unread_count']]);
    }

    public function test_mark_all_read(): void
    {
        $user = User::factory()->create();
        Notification::factory()->count(3)->create(['user_id' => $user->id, 'read_at' => null]);

        $response = $this->actingAs($user)->postJson('/api/v1/notifications/read');

        $response->assertOk();
        $this->assertEquals(0, Notification::where('user_id', $user->id)->whereNull('read_at')->count());
    }

    public function test_unread_count(): void
    {
        $user = User::factory()->create();
        Notification::factory()->count(7)->create(['user_id' => $user->id, 'read_at' => null]);

        $response = $this->actingAs($user)->getJson('/api/v1/notifications/unread-count');

        $response->assertOk()
            ->assertJsonPath('data.count', 7);
    }
}

// =====================================================
// FILE: tests/Feature/SystemTest.php
// =====================================================

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_check(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->getJson('/api/v1/system/health');

        $response->assertOk()
            ->assertJsonStructure(['success', 'data' => ['services', 'server', 'application']]);
    }

    public function test_non_admin_cannot_access_health(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)->getJson('/api/v1/system/health');
        $response->assertStatus(403);
    }

    public function test_cache_clear(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson('/api/v1/system/cache/clear', ['type' => 'all']);

        $response->assertOk()
            ->assertJsonPath('data.cleared', ['config', 'route', 'view', 'cache', 'event']);
    }

    public function test_audit_logs(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->getJson('/api/v1/audit/logs');
        $response->assertOk();
    }

    public function test_reports_types(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->getJson('/api/v1/reports/types');

        $response->assertOk()
            ->assertJsonCount(8, 'data'); // 8 report types
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// 7 тестовых файлов, 40+ тестов:
// AuthTest (6): login, invalid credentials, brute force, get me, logout, unauthenticated.
// LeadsTest (8): list, create, validation, update status, convert, sources, webhook, RBAC.
// CasesTest (4): create, status workflow, invalid transition, assign.
// POSTest (3): receive payment, approve, VAT calculation.
// RBACTest (6): admin access, manager denied, viewer read-only, accountant scope, role change.
// NewsTest (5): sources (27), articles list, approve, reject, statistics.
// NotificationsTest (3): list, mark all read, unread count.
// SystemTest (5): health check, non-admin denied, cache clear, audit, report types.
// Файл: tests/Feature/*.php
// ---------------------------------------------------------------
