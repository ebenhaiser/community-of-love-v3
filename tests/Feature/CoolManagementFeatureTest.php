<?php

namespace Tests\Feature;

use App\Livewire\Activities\ActivityIndex;
use App\Livewire\Attendances\AttendanceManager;
use App\Livewire\Auth\Login;
use App\Livewire\Cools\CoolIndex;
use App\Livewire\Members\MemberIndex;
use App\Livewire\Public\CoolPortal;
use App\Livewire\QrAccess\QrAccessManager;
use App\Livewire\Statistics\AttendanceStatistics;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Cool;
use App\Models\Member;
use App\Models\QrAccess;
use App\Models\Shepherd;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CoolManagementFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test authentication flows: login, invalid credentials, and logout.
     */
    public function test_user_authentication_flow(): void
    {
        // Unauthenticated redirect
        $this->get('/')->assertRedirect('/login');

        // Login component with invalid credentials
        Livewire::test(Login::class)
            ->set('account', 'master')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors(['account']);

        $this->assertGuest();

        // Login with valid credentials
        Livewire::test(Login::class)
            ->set('account', 'master')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/');

        $master = User::where('username', 'master')->first();
        $this->actingAs($master);
        $this->assertAuthenticatedAs($master);

        // Logout
        $this->get('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test COOL management: list, create, edit, and soft delete.
     */
    public function test_cool_management_crud(): void
    {
        $master = User::where('username', 'master')->first();
        $this->actingAs($master);

        $shepherd = Shepherd::first();

        // List & Create
        Livewire::test(CoolIndex::class)
            ->assertSee('Data Kelompok COOL')
            ->call('openCreateModal')
            ->set('cool_code', 'COOL-TEST-999')
            ->set('name', 'COOL Testing Grace')
            ->set('shepherd_id', $shepherd->shepherd_id)
            ->set('description', 'Kelompok testing')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('cools', [
            'cool_code' => 'COOL-TEST-999',
            'name' => 'COOL Testing Grace',
            'is_deleted' => false,
        ]);

        $created = Cool::where('cool_code', 'COOL-TEST-999')->first();

        // Soft delete
        Livewire::test(CoolIndex::class)
            ->call('deleteCool', $created->cool_id);

        $this->assertDatabaseHas('cools', [
            'cool_id' => $created->cool_id,
            'is_deleted' => true,
        ]);
    }

    /**
     * Test Member management: list, add member with COOL assignment, and soft delete.
     */
    public function test_member_management(): void
    {
        $master = User::where('username', 'master')->first();
        $this->actingAs($master);

        $cool = Cool::first();

        Livewire::test(MemberIndex::class)
            ->assertSee('Data Anggota COOL')
            ->call('openCreateModal')
            ->set('member_code', 'MBR-2026-9999')
            ->set('name', 'Yohanes Pembaptis')
            ->set('phone', '081299998888')
            ->set('email', 'yohanes@salemba.org')
            ->set('join_date', '2026-09-19')
            ->set('cool_id', $cool->cool_id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('members', [
            'member_code' => 'MBR-2026-9999',
            'name' => 'Yohanes Pembaptis',
            'is_deleted' => false,
        ]);

        $member = Member::where('member_code', 'MBR-2026-9999')->first();

        // Check cool_members pivot created
        $this->assertDatabaseHas('cool_members', [
            'member_id' => $member->member_id,
            'cool_id' => $cool->cool_id,
            'status' => 'ACTIVE',
            'is_deleted' => false,
        ]);

        // Soft delete
        Livewire::test(MemberIndex::class)
            ->call('deleteMember', $member->member_id);

        $this->assertDatabaseHas('members', [
            'member_id' => $member->member_id,
            'is_deleted' => true,
        ]);
    }

    /**
     * Test Activity scheduling and management.
     */
    public function test_activity_scheduling(): void
    {
        $master = User::where('username', 'master')->first();
        $this->actingAs($master);

        $cool = Cool::first();

        Livewire::test(ActivityIndex::class)
            ->assertSee('Jadwal COOL')
            ->call('openCreateModal')
            ->set('cool_id', $cool->cool_id)
            ->set('activity_type_id', 1)
            ->set('name', 'Ibadah Syukur Ulang Tahun COOL')
            ->set('activity_date', '2026-09-25')
            ->set('start_time', '19:30')
            ->set('location', 'Gereja GBI Salemba Lt. 3')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('activities', [
            'name' => 'Ibadah Syukur Ulang Tahun COOL',
            'cool_id' => $cool->cool_id,
            'is_deleted' => false,
        ]);
    }

    /**
     * Test Attendance sheet: marking statuses, note update, and mark all present.
     */
    public function test_attendance_management(): void
    {
        $master = User::where('username', 'master')->first();
        $this->actingAs($master);

        $activity = Activity::where('is_deleted', false)->first();
        $member = Member::where('is_deleted', false)->first();

        // Set attendance status to PRESENT (ID 1)
        Livewire::test(AttendanceManager::class, ['activityId' => $activity->activity_id])
            ->assertSee($activity->name)
            ->call('setStatus', $member->member_id, 1);

        $this->assertDatabaseHas('attendances', [
            'activity_id' => $activity->activity_id,
            'member_id' => $member->member_id,
            'attendance_status_id' => 1,
            'is_deleted' => false,
        ]);

        // Mark All Present
        Livewire::test(AttendanceManager::class, ['activityId' => $activity->activity_id])
            ->call('markAllPresent')
            ->assertSee('Semua anggota COOL berhasil ditandai Hadir!');
    }

    /**
     * Test Attendance Statistics and consecutive absence detection.
     */
    public function test_attendance_statistics_and_pastoral_care(): void
    {
        $master = User::where('username', 'master')->first();
        $this->actingAs($master);

        Livewire::test(AttendanceStatistics::class)
            ->assertSee('Statistik')
            ->assertSee('Laporan Kehadiran')
            ->assertSee('Perhatian Pastoral Care')
            ->assertSee('Rincian Kehadiran Per Kegiatan');
    }

    /**
     * Test Public Member Portal via QR token and 6-digit PIN verification.
     */
    public function test_public_member_portal_qr_and_pin_flow(): void
    {
        $qr = QrAccess::where('is_active', true)->where('is_deleted', false)->first();
        $this->assertNotNull($qr);

        // Access public URL
        $response = $this->get('/c/'.$qr->qr_token);
        $response->assertStatus(200);
        $response->assertSee('Masukkan PIN Akses COOL');

        // Test invalid PIN
        Livewire::test(CoolPortal::class, ['qrToken' => $qr->qr_token])
            ->set('pin', '000000')
            ->call('verifyPin')
            ->assertHasErrors(['pin']);

        // Test valid PIN (Seeded PIN is '123456')
        $testComponent = Livewire::test(CoolPortal::class, ['qrToken' => $qr->qr_token])
            ->set('pin', '123456')
            ->call('verifyPin')
            ->assertHasNoErrors()
            ->assertSet('isVerified', true)
            ->assertSee('Jadwal Pertemuan Terdekat');

        // Test sending prayer request / message to shepherd
        $testComponent
            ->set('activeTab', 'message')
            ->set('senderName', 'Stefanus Martir')
            ->set('senderPhone', '081211112222')
            ->set('messageType', 'Pokok Doa')
            ->set('messageContent', 'Mohon dukungan doa untuk kesembuhan ibu yang sedang dirawat di RS.')
            ->call('sendMessage')
            ->assertHasNoErrors()
            ->assertSee('Puji Tuhan! Pesan / pokok doa Anda telah berhasil terkirim');

        $this->assertDatabaseHas('member_messages', [
            'cool_id' => $qr->cool_id,
            'status' => 'SENT',
        ]);
    }

    /**
     * Test QR Access Manager page renders properly.
     */
    public function test_qr_access_page_renders_successfully(): void
    {
        $master = User::where('username', 'master')->first();
        $this->actingAs($master);

        $response = $this->get('/qr-access');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Akses QR');

        Livewire::test(QrAccessManager::class)
            ->assertSee('Akses QR')
            ->assertSee('Tautan Portal Jemaat');
    }
}
