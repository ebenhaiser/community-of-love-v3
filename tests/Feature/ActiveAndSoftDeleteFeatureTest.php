<?php

namespace Tests\Feature;

use App\Livewire\Activities\ActivityIndex;
use App\Livewire\Attendances\AttendanceManager;
use App\Livewire\Auth\Login;
use App\Livewire\Cools\CoolIndex;
use App\Livewire\Events\EventIndex;
use App\Livewire\Materials\MaterialManager;
use App\Livewire\Members\MemberIndex;
use App\Livewire\Messages\MessageInbox;
use App\Livewire\Shepherds\ShepherdIndex;
use App\Models\Activity;
use App\Models\ActivityMaterial;
use App\Models\ActivityType;
use App\Models\Attendance;
use App\Models\ChurchEvent;
use App\Models\Cool;
use App\Models\CoolMember;
use App\Models\Member;
use App\Models\MemberMessage;
use App\Models\QrAccess;
use App\Models\Role;
use App\Models\Shepherd;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ActiveAndSoftDeleteFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $masterUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->masterUser = User::where('username', 'master')->firstOrFail();
    }

    /**
     * Test QC: Inactive or soft-deleted users cannot log into the system.
     */
    public function test_inactive_or_deleted_user_cannot_login(): void
    {
        $role = Role::where('name', 'MEMBER')->firstOrFail();

        // 1. Inactive user with correct password
        $inactiveUser = User::create([
            'username' => 'inactive.user',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'User Non-Aktif',
            'email' => 'inactive@salemba.org',
            'role_id' => $role->role_id,
            'is_active' => false,
            'is_deleted' => false,
        ]);

        Livewire::test(Login::class)
            ->set('account', 'inactive.user')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['account']);

        $this->assertGuest();

        // 2. Soft-deleted user with correct password
        $deletedUser = User::create([
            'username' => 'deleted.user',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'User Dihapus',
            'email' => 'deleted@salemba.org',
            'role_id' => $role->role_id,
            'is_active' => true,
            'is_deleted' => true,
        ]);

        Livewire::test(Login::class)
            ->set('account', 'deleted.user')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['account']);

        $this->assertGuest();
    }

    /**
     * Test QC: Soft-deleted Shepherd is excluded from Shepherd Index.
     */
    public function test_soft_deleted_shepherd_is_excluded_from_index(): void
    {
        $this->actingAs($this->masterUser);

        $shepherd = Shepherd::create([
            'name' => 'Ps. Gembala Baru Soft Delete',
            'phone' => '081233445566',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        Livewire::test(ShepherdIndex::class)
            ->assertSee('Ps. Gembala Baru Soft Delete')
            ->call('deleteShepherd', $shepherd->shepherd_id);

        $shepherd->refresh();
        $this->assertTrue($shepherd->is_deleted);

        Livewire::test(ShepherdIndex::class)
            ->assertDontSee('Ps. Gembala Baru Soft Delete');
    }

    /**
     * Test QC: Soft-deleted COOL is excluded from Cool Index.
     */
    public function test_soft_deleted_cool_is_excluded_from_index(): void
    {
        $this->actingAs($this->masterUser);

        $shepherd = Shepherd::firstOrFail();
        $cool = Cool::create([
            'cool_code' => 'COOL-DEL-001',
            'name' => 'COOL Yang Akan Dihapus',
            'shepherd_id' => $shepherd->shepherd_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        Livewire::test(CoolIndex::class)
            ->assertSee('COOL Yang Akan Dihapus')
            ->call('deleteCool', $cool->cool_id);

        $cool->refresh();
        $this->assertTrue($cool->is_deleted);

        Livewire::test(CoolIndex::class)
            ->assertDontSee('COOL Yang Akan Dihapus');
    }

    /**
     * Test QC: Member status filter (ACTIVE vs INACTIVE) and soft-deleted member exclusion.
     */
    public function test_member_status_filtering_and_soft_delete(): void
    {
        $this->actingAs($this->masterUser);

        $cool = Cool::firstOrFail();

        // Active member
        $activeMember = Member::create([
            'member_code' => 'MBR-QC-ACT',
            'name' => 'Anggota Aktif QC',
            'status' => 'ACTIVE',
            'is_active' => true,
            'is_deleted' => false,
            'join_date' => now()->toDateString(),
        ]);

        CoolMember::create([
            'cool_id' => $cool->cool_id,
            'member_id' => $activeMember->member_id,
            'start_date' => now()->toDateString(),
            'status' => 'ACTIVE',
            'is_deleted' => false,
        ]);

        // Inactive member
        $inactiveMember = Member::create([
            'member_code' => 'MBR-QC-INACT',
            'name' => 'Anggota Nonaktif QC',
            'status' => 'INACTIVE',
            'is_active' => false,
            'is_deleted' => false,
            'join_date' => now()->toDateString(),
        ]);

        CoolMember::create([
            'cool_id' => $cool->cool_id,
            'member_id' => $inactiveMember->member_id,
            'start_date' => now()->toDateString(),
            'status' => 'INACTIVE',
            'is_deleted' => false,
        ]);

        // Soft-deleted member
        $deletedMember = Member::create([
            'member_code' => 'MBR-QC-DEL',
            'name' => 'Anggota Dihapus QC',
            'status' => 'ACTIVE',
            'is_active' => true,
            'is_deleted' => true,
            'join_date' => now()->toDateString(),
        ]);

        // 1. Soft-deleted member is NEVER seen
        Livewire::test(MemberIndex::class)
            ->assertDontSee('Anggota Dihapus QC')
            ->assertSee('Anggota Aktif QC')
            ->assertSee('Anggota Nonaktif QC');

        // 2. Filter by ACTIVE
        Livewire::test(MemberIndex::class)
            ->set('statusFilter', 'ACTIVE')
            ->assertSee('Anggota Aktif QC')
            ->assertDontSee('Anggota Nonaktif QC');

        // 3. Filter by INACTIVE
        Livewire::test(MemberIndex::class)
            ->set('statusFilter', 'INACTIVE')
            ->assertSee('Anggota Nonaktif QC')
            ->assertDontSee('Anggota Aktif QC');

        // 4. Soft delete the active member
        Livewire::test(MemberIndex::class)
            ->call('deleteMember', $activeMember->member_id);

        $activeMember->refresh();
        $this->assertTrue($activeMember->is_deleted);

        // cool_members pivot must also be marked is_deleted = true
        $pivot = CoolMember::where('member_id', $activeMember->member_id)->first();
        $this->assertTrue($pivot->is_deleted);
    }

    /**
     * Test QC: markAllPresent in AttendanceManager only marks active and non-deleted members.
     */
    public function test_mark_all_present_ignores_soft_deleted_or_unassigned_members(): void
    {
        $this->actingAs($this->masterUser);

        $cool = Cool::firstOrFail();
        $actType = ActivityType::firstOrFail();

        $activity = Activity::create([
            'cool_id' => $cool->cool_id,
            'activity_type_id' => $actType->activity_type_id,
            'name' => 'Ibadah QC Mark All',
            'activity_date' => now()->toDateString(),
            'start_time' => '19:00',
            'status' => 'SCHEDULED',
            'is_deleted' => false,
        ]);

        // Active member in cool
        $validMember = Member::create([
            'member_code' => 'MBR-VALID-01',
            'name' => 'Member Valid COOL',
            'status' => 'ACTIVE',
            'is_active' => true,
            'is_deleted' => false,
            'join_date' => now()->toDateString(),
        ]);

        CoolMember::create([
            'cool_id' => $cool->cool_id,
            'member_id' => $validMember->member_id,
            'start_date' => now()->toDateString(),
            'status' => 'ACTIVE',
            'is_deleted' => false,
        ]);

        // Soft-deleted member that was previously in cool
        $deletedMember = Member::create([
            'member_code' => 'MBR-DELETED-01',
            'name' => 'Member Terhapus Dari COOL',
            'status' => 'ACTIVE',
            'is_active' => true,
            'is_deleted' => true,
            'join_date' => now()->toDateString(),
        ]);

        CoolMember::create([
            'cool_id' => $cool->cool_id,
            'member_id' => $deletedMember->member_id,
            'start_date' => now()->toDateString(),
            'status' => 'ACTIVE',
            'is_deleted' => true,
        ]);

        // Execute markAllPresent
        Livewire::test(AttendanceManager::class, ['activityId' => $activity->activity_id])
            ->call('markAllPresent')
            ->assertSee('Semua anggota COOL berhasil ditandai Hadir!');

        // Valid member must have attendance
        $this->assertDatabaseHas('attendances', [
            'activity_id' => $activity->activity_id,
            'member_id' => $validMember->member_id,
            'is_deleted' => false,
        ]);

        // Deleted member must NOT have attendance
        $this->assertDatabaseMissing('attendances', [
            'activity_id' => $activity->activity_id,
            'member_id' => $deletedMember->member_id,
        ]);
    }

    /**
     * Test QC: Soft-deleted activity is excluded from ActivityIndex.
     */
    public function test_soft_deleted_activity_is_excluded(): void
    {
        $this->actingAs($this->masterUser);

        $cool = Cool::firstOrFail();
        $actType = ActivityType::firstOrFail();

        $activity = Activity::create([
            'cool_id' => $cool->cool_id,
            'activity_type_id' => $actType->activity_type_id,
            'name' => 'Kegiatan Dihapus QC',
            'activity_date' => now()->toDateString(),
            'start_time' => '19:00',
            'status' => 'SCHEDULED',
            'is_deleted' => false,
        ]);

        Livewire::test(ActivityIndex::class)
            ->assertSee('Kegiatan Dihapus QC')
            ->call('deleteActivity', $activity->activity_id);

        $activity->refresh();
        $this->assertTrue($activity->is_deleted);

        Livewire::test(ActivityIndex::class)
            ->assertDontSee('Kegiatan Dihapus QC');
    }

    /**
     * Test QC: Soft-deleted Material is excluded from MaterialManager.
     */
    public function test_soft_deleted_material_is_excluded(): void
    {
        $this->actingAs($this->masterUser);

        $activity = Activity::where('is_deleted', false)->firstOrFail();

        $material = ActivityMaterial::create([
            'activity_id' => $activity->activity_id,
            'material_type' => 'LINK',
            'file_name' => 'Materi Soft Delete QC',
            'external_url' => 'https://youtube.com/watch?v=12345',
            'is_deleted' => false,
            'uploaded_by' => $this->masterUser->user_id,
            'date_uploaded' => now(),
        ]);

        Livewire::test(MaterialManager::class)
            ->assertSee('Materi Soft Delete QC')
            ->call('deleteMaterial', $material->material_id);

        $material->refresh();
        $this->assertTrue($material->is_deleted);

        Livewire::test(MaterialManager::class)
            ->assertDontSee('Materi Soft Delete QC');
    }

    /**
     * Test QC: Soft-deleted Message is excluded from MessageInbox.
     */
    public function test_soft_deleted_message_is_excluded(): void
    {
        $this->actingAs($this->masterUser);

        $cool = Cool::firstOrFail();

        $msg = MemberMessage::create([
            'cool_id' => $cool->cool_id,
            'shepherd_id' => $cool->shepherd_id,
            'message' => 'Pesan untuk diuji soft delete',
            'status' => 'SENT',
            'is_deleted' => false,
            'date_created' => now(),
        ]);

        Livewire::test(MessageInbox::class)
            ->assertSee('Pesan untuk diuji soft delete')
            ->call('deleteMessage', $msg->message_id);

        $msg->refresh();
        $this->assertTrue($msg->is_deleted);

        Livewire::test(MessageInbox::class)
            ->assertDontSee('Pesan untuk diuji soft delete');
    }

    /**
     * Test QC: Soft-deleted ChurchEvent is excluded from EventIndex.
     */
    public function test_soft_deleted_church_event_is_excluded(): void
    {
        $this->actingAs($this->masterUser);

        $event = ChurchEvent::create([
            'event_code' => 'EVT-DEL-001',
            'name' => 'Event Dihapus QC',
            'event_date' => now()->addDays(7)->toDateString(),
            'start_time' => '10:00',
            'status' => 'SCHEDULED',
            'is_deleted' => false,
        ]);

        Livewire::test(EventIndex::class)
            ->assertSee('Event Dihapus QC')
            ->call('deleteEvent', $event->event_id);

        $event->refresh();
        $this->assertTrue($event->is_deleted);

        Livewire::test(EventIndex::class)
            ->assertDontSee('Event Dihapus QC');
    }

    /**
     * Test QC: Inactive or soft-deleted QrAccess cannot access public portal (returns 404).
     */
    public function test_inactive_or_deleted_qr_access_returns_404(): void
    {
        $cool = Cool::firstOrFail();

        // Inactive QR
        $inactiveQr = QrAccess::create([
            'cool_id' => $cool->cool_id,
            'access_code' => 'ACC-INACT',
            'qr_token' => 'qr-inactive-test',
            'pin_hash' => Hash::make('123456'),
            'is_active' => false,
            'is_deleted' => false,
        ]);

        $this->get('/c/'.$inactiveQr->qr_token)->assertStatus(404);

        // Soft-deleted QR
        $deletedQr = QrAccess::create([
            'cool_id' => $cool->cool_id,
            'access_code' => 'ACC-DEL',
            'qr_token' => 'qr-deleted-test',
            'pin_hash' => Hash::make('123456'),
            'is_active' => true,
            'is_deleted' => true,
        ]);

        $this->get('/c/'.$deletedQr->qr_token)->assertStatus(404);
    }
}
