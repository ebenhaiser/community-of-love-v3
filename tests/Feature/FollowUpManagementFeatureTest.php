<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Livewire\FollowUps\FollowUpIndex;
use App\Livewire\Statistics\AttendanceStatistics;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Cool;
use App\Models\CoolMember;
use App\Models\Member;
use App\Models\MemberFollowUp;
use App\Models\Role;
use App\Models\Shepherd;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class FollowUpManagementFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $masterUser;

    private Member $member;

    private Cool $cool;

    protected function setUp(): void
    {
        parent::setUp();

        $masterRole = Role::create([
            'name' => 'MASTER',
            'description' => 'Master Administrator',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->masterUser = User::create([
            'username' => 'master',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'Master Administrator',
            'email' => 'master@church.org',
            'role_id' => $masterRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $shepherd = Shepherd::create([
            'name' => 'Ps. Budi Santoso',
            'phone' => '0812345678',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->cool = Cool::create([
            'cool_code' => 'COOL-01',
            'name' => 'COOL Salemba 01',
            'shepherd_id' => $shepherd->shepherd_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->member = Member::create([
            'member_code' => 'MBR-001',
            'name' => 'Budi Gunawan',
            'phone' => '081299998888',
            'status' => 'ACTIVE',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        CoolMember::create([
            'cool_id' => $this->cool->cool_id,
            'member_id' => $this->member->member_id,
            'status' => 'ACTIVE',
            'start_date' => now()->subMonths(3)->toDateString(),
            'is_deleted' => false,
        ]);

        $actType = ActivityType::create([
            'name' => 'Pertemuan Rutin',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $absentStatus = AttendanceStatus::create([
            'code' => 'ABSENT',
            'name' => 'Tidak Hadir',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        // Create 2 activities and mark member as ABSENT in both
        $act1 = Activity::create([
            'cool_id' => $this->cool->cool_id,
            'activity_type_id' => $actType->activity_type_id,
            'name' => 'COOL Minggu 1',
            'activity_date' => now()->subWeeks(2)->toDateString(),
            'is_deleted' => false,
        ]);

        $act2 = Activity::create([
            'cool_id' => $this->cool->cool_id,
            'activity_type_id' => $actType->activity_type_id,
            'name' => 'COOL Minggu 2',
            'activity_date' => now()->subWeek()->toDateString(),
            'is_deleted' => false,
        ]);

        Attendance::create([
            'activity_id' => $act1->activity_id,
            'member_id' => $this->member->member_id,
            'attendance_status_id' => $absentStatus->attendance_status_id,
            'is_deleted' => false,
        ]);

        Attendance::create([
            'activity_id' => $act2->activity_id,
            'member_id' => $this->member->member_id,
            'attendance_status_id' => $absentStatus->attendance_status_id,
            'is_deleted' => false,
        ]);
    }

    public function test_user_can_access_follow_up_page(): void
    {
        $this->actingAs($this->masterUser);

        $response = $this->get(route('follow-ups.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Perhatian', false);
        $response->assertSee('Budi Gunawan');
    }

    public function test_consecutive_absent_member_is_auto_synced_as_pending(): void
    {
        $this->actingAs($this->masterUser);

        Livewire::test(FollowUpIndex::class)
            ->assertSee('Budi Gunawan')
            ->assertSee('Belum Ditangani');

        $record = MemberFollowUp::where('member_id', $this->member->member_id)->first();
        $this->assertNotNull($record);
        $this->assertEquals('PENDING', $record->status);
        $this->assertEquals(2, $record->consecutive_absent_count);
    }

    public function test_member_can_be_marked_as_resolved(): void
    {
        $this->actingAs($this->masterUser);

        // First auto-sync
        $test = Livewire::test(FollowUpIndex::class);
        $record = MemberFollowUp::where('member_id', $this->member->member_id)->first();

        $test->call('openResolveModal', $record->follow_up_id)
            ->assertSet('showModal', true)
            ->set('action_taken', 'Kunjungan Rumah (Home Visit)')
            ->set('notes', 'Sudah dikunjungi oleh gembala dan didoakan.')
            ->call('saveFollowUp')
            ->assertHasNoErrors()
            ->assertSet('showModal', false)
            ->assertSee('SUDAH DITANGANI');

        $record->refresh();
        $this->assertEquals('RESOLVED', $record->status);
        $this->assertEquals('Kunjungan Rumah (Home Visit)', $record->action_taken);
        $this->assertEquals('Sudah dikunjungi oleh gembala dan didoakan.', $record->notes);
        $this->assertEquals($this->masterUser->user_id, $record->handled_by);
        $this->assertNotNull($record->handled_at);
    }

    public function test_resolved_member_does_not_appear_in_dashboard_or_statistics(): void
    {
        $this->actingAs($this->masterUser);

        // Before resolving: member appears in Dashboard and Statistics
        Livewire::test(Dashboard::class)
            ->assertSee('Budi Gunawan');

        Livewire::test(AttendanceStatistics::class)
            ->assertSee('Budi Gunawan');

        // Now resolve the follow-up
        MemberFollowUp::create([
            'member_id' => $this->member->member_id,
            'cool_id' => $this->cool->cool_id,
            'consecutive_absent_count' => 2,
            'status' => 'RESOLVED',
            'action_taken' => 'WhatsApp / Telepon',
            'notes' => 'Sudah ditangani',
            'handled_by' => $this->masterUser->user_id,
            'handled_at' => now(),
            'is_deleted' => false,
        ]);

        // After resolving: member must NOT appear in Dashboard or Statistics
        Livewire::test(Dashboard::class)
            ->assertDontSee('Budi Gunawan');

        Livewire::test(AttendanceStatistics::class)
            ->assertDontSee('Budi Gunawan');
    }

    public function test_resolved_follow_up_can_be_reopened(): void
    {
        $this->actingAs($this->masterUser);

        $record = MemberFollowUp::create([
            'member_id' => $this->member->member_id,
            'cool_id' => $this->cool->cool_id,
            'consecutive_absent_count' => 2,
            'status' => 'RESOLVED',
            'action_taken' => 'WhatsApp / Telepon',
            'notes' => 'Sudah ditangani',
            'handled_by' => $this->masterUser->user_id,
            'handled_at' => now(),
            'is_deleted' => false,
        ]);

        Livewire::test(FollowUpIndex::class)
            ->call('reopenFollowUp', $record->follow_up_id)
            ->assertSee('BELUM DITANGANI');

        $record->refresh();
        $this->assertEquals('PENDING', $record->status);

        // Reappears in Dashboard
        Livewire::test(Dashboard::class)
            ->assertSee('Budi Gunawan');
    }
}
