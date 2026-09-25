<?php

namespace Tests\Feature;

use App\Livewire\Members\MemberIndex;
use App\Models\Cool;
use App\Models\Member;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JemaatManagementFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_jemaat_list_renders_with_demographic_and_cool_data(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        Livewire::test(MemberIndex::class)
            ->assertStatus(200)
            ->assertSee('Data Jemaat GBI Salemba')
            ->assertSee('Sudah Ber-COOL')
            ->assertSee('Belum Ber-COOL')
            ->assertSee('Mengikuti KOM')
            ->assertSee('Andi Pratama')
            ->assertSee('MBR-001');
    }

    public function test_filter_jemaat_by_cool_status(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        // Filter by already in COOL
        Livewire::test(MemberIndex::class)
            ->set('coolStatusFilter', 'in_cool')
            ->assertSee('Andi Pratama')
            ->assertDontSee('Kezia Priscilla');

        // Filter by not yet in COOL
        Livewire::test(MemberIndex::class)
            ->set('coolStatusFilter', 'not_in_cool')
            ->assertSee('Kezia Priscilla')
            ->assertDontSee('Andi Pratama');
    }

    public function test_filter_jemaat_by_kom_status(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        Livewire::test(MemberIndex::class)
            ->set('komFilter', 'KOM 400')
            ->assertSee('Grace Angelia')
            ->assertDontSee('Citra Lestari');
    }

    public function test_create_new_jemaat_without_cool(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        Livewire::test(MemberIndex::class)
            ->call('openCreateModal')
            ->set('member_code', 'JMT-2026-9991')
            ->set('name', 'Samuel Tambunan')
            ->set('gender', 'Laki-laki')
            ->set('phone', '081299990001')
            ->set('email', 'samuel.t@example.com')
            ->set('birthplace', 'Jakarta')
            ->set('birthdate', '1995-07-15')
            ->set('address', 'Jl. Salemba Tengah No. 20')
            ->set('social_media', '@samueltambunan')
            ->set('kom_status', 'KOM 100')
            ->set('marital_status', 'Belum Menikah')
            ->set('is_in_cool', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('members', [
            'member_code' => 'JMT-2026-9991',
            'name' => 'Samuel Tambunan',
            'gender' => 'Laki-laki',
            'birthplace' => 'Jakarta',
            'social_media' => '@samueltambunan',
            'kom_status' => 'KOM 100',
            'marital_status' => 'Belum Menikah',
            'is_in_cool' => false,
        ]);
    }

    public function test_create_new_jemaat_with_cool_assignment(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        $cool = Cool::where('is_deleted', false)->first();

        Livewire::test(MemberIndex::class)
            ->call('openCreateModal')
            ->set('member_code', 'JMT-2026-9992')
            ->set('name', 'Debora Maria')
            ->set('gender', 'Perempuan')
            ->set('phone', '081299990002')
            ->set('email', 'debora.m@example.com')
            ->set('birthplace', 'Bandung')
            ->set('birthdate', '1998-03-22')
            ->set('address', 'Jl. Cempaka Putih Raya No. 5')
            ->set('social_media', '@debora_maria')
            ->set('kom_status', 'KOM 200')
            ->set('marital_status', 'Menikah')
            ->set('is_in_cool', true)
            ->set('cool_id', $cool->cool_id)
            ->call('save')
            ->assertHasNoErrors();

        $member = Member::where('member_code', 'JMT-2026-9992')->first();
        $this->assertNotNull($member);
        $this->assertTrue($member->is_in_cool);
        $this->assertEquals('Debora Maria', $member->name);
        $this->assertEquals('Perempuan', $member->gender);
        $this->assertEquals('KOM 200', $member->kom_status);
        $this->assertEquals('Menikah', $member->marital_status);

        $this->assertDatabaseHas('cool_members', [
            'cool_id' => $cool->cool_id,
            'member_id' => $member->member_id,
            'is_deleted' => false,
        ]);
    }

    public function test_edit_existing_jemaat_data(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        $member = Member::where('member_code', 'MBR-001')->first();

        Livewire::test(MemberIndex::class)
            ->call('openEditModal', $member->member_id)
            ->set('kom_status', 'KOM 400')
            ->set('marital_status', 'Menikah')
            ->set('address', 'Jl. Salemba Baru No. 100')
            ->call('save')
            ->assertHasNoErrors();

        $member->refresh();
        $this->assertEquals('KOM 400', $member->kom_status);
        $this->assertEquals('Menikah', $member->marital_status);
        $this->assertEquals('Jl. Salemba Baru No. 100', $member->address);
    }

    public function test_open_and_close_detail_modal(): void
    {
        $admin = User::where('username', 'master')->first();
        $this->actingAs($admin);

        $member = Member::where('member_code', 'MBR-001')->first();

        Livewire::test(MemberIndex::class)
            ->call('openDetailModal', $member->member_id)
            ->assertSet('showDetailModal', true)
            ->assertSee('Detail Profil Jemaat GBI Salemba')
            ->assertSee($member->name)
            ->call('closeDetailModal')
            ->assertSet('showDetailModal', false);
    }
}
