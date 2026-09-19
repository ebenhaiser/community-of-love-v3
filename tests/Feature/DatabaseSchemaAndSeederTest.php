<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ChurchEvent;
use App\Models\Cool;
use App\Models\QrAccess;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaAndSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all ERD tables exist in the database.
     */
    public function test_all_erd_tables_exist(): void
    {
        $expectedTables = [
            'roles',
            'shepherds',
            'app_users',
            'cools',
            'members',
            'cool_members',
            'activity_types',
            'activities',
            'attendance_statuses',
            'attendances',
            'activity_materials',
            'qr_accesses',
            'member_sessions',
            'member_messages',
            'notifications',
            'church_events',
            'event_cools',
            'event_members',
            'event_attendances',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table {$table} does not exist in schema.");
        }
    }

    /**
     * Test that seeders run properly and populate all models.
     */
    public function test_database_seeder_populates_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('roles', 3);
        $this->assertDatabaseCount('attendance_statuses', 4);
        $this->assertDatabaseCount('activity_types', 6);
        $this->assertDatabaseCount('shepherds', 3);
        $this->assertDatabaseCount('app_users', 3);
        $this->assertDatabaseCount('cools', 3);
        $this->assertDatabaseCount('members', 10);
        $this->assertDatabaseCount('cool_members', 10);
        $this->assertDatabaseCount('activities', 11);
        $this->assertDatabaseCount('attendances', 45);
        $this->assertDatabaseCount('activity_materials', 3);
        $this->assertDatabaseCount('qr_accesses', 2);
        $this->assertDatabaseCount('member_messages', 2);
        $this->assertDatabaseCount('notifications', 2);
        $this->assertDatabaseCount('church_events', 2);
        $this->assertDatabaseCount('event_cools', 4);
        $this->assertDatabaseCount('event_members', 5);
        $this->assertDatabaseCount('event_attendances', 5);
    }

    /**
     * Test core Eloquent relationships work seamlessly.
     */
    public function test_eloquent_relationships_work(): void
    {
        $this->seed(DatabaseSeeder::class);

        // User and Role
        $master = User::where('username', 'master')->first();
        $this->assertNotNull($master);
        $this->assertEquals('MASTER', $master->role->name);

        // Shepherd and User
        $budiUser = User::where('username', 'gembala.budi')->first();
        $this->assertNotNull($budiUser->shepherd);
        $this->assertEquals('Ps. Budi Santoso', $budiUser->shepherd->name);

        // Cool with Shepherd and Members
        $cool1 = Cool::where('cool_code', 'COOL-SLM-001')->first();
        $this->assertNotNull($cool1);
        $this->assertEquals('Ps. Budi Santoso', $cool1->shepherd->name);
        $this->assertCount(5, $cool1->members);
        $this->assertCount(7, $cool1->activities);

        // Activity and Attendance
        $firstActivity = Activity::find(1);
        $this->assertNotNull($firstActivity);
        $this->assertCount(5, $firstActivity->attendances);

        // QR Access
        $qr = QrAccess::where('cool_id', 1)->first();
        $this->assertNotNull($qr);
        $this->assertEquals('COOL-SLM-001-QR', $qr->access_code);

        // Church Event (Phase 2)
        $event = ChurchEvent::find(2);
        $this->assertNotNull($event);
        $this->assertCount(2, $event->cools);
        $this->assertCount(5, $event->members);
        $this->assertCount(5, $event->attendances);
    }

    /**
     * Test dashboard page renders with Spark Admin layout and seeded statistics.
     */
    public function test_dashboard_page_renders_with_spark_admin_layout(): void
    {
        $this->seed(DatabaseSeeder::class);

        $master = User::where('username', 'master')->first();
        $response = $this->actingAs($master)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Manajemen COOL');
        $response->assertSee('COOL Salemba');
        $response->assertSee('COOL-SLM-001');
        $response->assertSee('Ps. Budi Santoso');
        $response->assertSee('spark-admin-1.0.0/assets/css/main.css');
    }
}
