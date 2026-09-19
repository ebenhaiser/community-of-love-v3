<?php

namespace Tests\Feature;

use App\Livewire\Master\AppUserManager;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class MasterAppUserManagerFeatureTest extends TestCase
{
    use RefreshDatabase;

    private Role $masterRole;

    private Role $memberRole;

    private User $masterUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->masterRole = Role::create([
            'name' => 'MASTER',
            'description' => 'Master Administrator',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->memberRole = Role::create([
            'name' => 'MEMBER',
            'description' => 'Anggota Jemaat',
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->masterUser = User::create([
            'username' => 'master',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'Master Administrator',
            'email' => 'master@church.org',
            'role_id' => $this->masterRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);
    }

    public function test_master_user_can_access_user_manager_page(): void
    {
        $this->actingAs($this->masterUser);

        $response = $this->get(route('master.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna Aplikasi');
    }

    public function test_non_master_user_cannot_access_user_manager_page(): void
    {
        $memberUser = User::create([
            'username' => 'member1',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'Member Biasa',
            'email' => 'member@church.org',
            'role_id' => $this->memberRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->actingAs($memberUser);

        $response = $this->get(route('master.users.index'));

        $response->assertStatus(403);
    }

    public function test_master_can_create_new_user_with_default_password(): void
    {
        $this->actingAs($this->masterUser);

        Livewire::test(AppUserManager::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->set('full_name', 'Budi Hartono')
            ->set('username', 'budi.hartono')
            ->set('email', 'budi@example.com')
            ->set('phone', '081299998888')
            ->set('role_id', $this->memberRole->role_id)
            ->set('is_active', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false)
            ->assertSee('Pengguna baru');

        $created = User::where('username', 'budi.hartono')->first();
        $this->assertNotNull($created);
        $this->assertEquals('Budi Hartono', $created->full_name);
        $this->assertEquals('budi.hartono', $created->username);
        $this->assertEquals('budi@example.com', $created->email);
        $this->assertTrue(Hash::check('password123', $created->password_hash));
    }

    public function test_master_can_edit_existing_user(): void
    {
        $this->actingAs($this->masterUser);

        $targetUser = User::create([
            'username' => 'targetuser',
            'password_hash' => Hash::make('oldpassword'),
            'full_name' => 'Nama Awal',
            'email' => 'target@example.com',
            'role_id' => $this->memberRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        Livewire::test(AppUserManager::class)
            ->call('openEditModal', $targetUser->user_id)
            ->assertSet('showModal', true)
            ->assertSet('full_name', 'Nama Awal')
            ->set('full_name', 'Nama Baru Diubah')
            ->set('phone', '081122334455')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $targetUser->refresh();
        $this->assertEquals('Nama Baru Diubah', $targetUser->full_name);
        $this->assertEquals('081122334455', $targetUser->phone);
    }

    public function test_master_can_reset_user_password_to_default(): void
    {
        $this->actingAs($this->masterUser);

        $targetUser = User::create([
            'username' => 'userreset',
            'password_hash' => Hash::make('somesecretpassword'),
            'full_name' => 'User Ingin Reset',
            'email' => 'reset@example.com',
            'role_id' => $this->memberRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->assertFalse(Hash::check('password123', $targetUser->password_hash));

        Livewire::test(AppUserManager::class)
            ->call('resetPassword', $targetUser->user_id)
            ->assertSee('berhasil direset ke default');

        $targetUser->refresh();
        $this->assertTrue(Hash::check('password123', $targetUser->password_hash));
    }

    public function test_master_can_toggle_active_status(): void
    {
        $this->actingAs($this->masterUser);

        $targetUser = User::create([
            'username' => 'toggleuser',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'User Toggle',
            'email' => 'toggle@example.com',
            'role_id' => $this->memberRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        Livewire::test(AppUserManager::class)
            ->call('toggleActive', $targetUser->user_id);

        $targetUser->refresh();
        $this->assertFalse($targetUser->is_active);

        Livewire::test(AppUserManager::class)
            ->call('toggleActive', $targetUser->user_id);

        $targetUser->refresh();
        $this->assertTrue($targetUser->is_active);
    }

    public function test_master_can_soft_delete_user(): void
    {
        $this->actingAs($this->masterUser);

        $targetUser = User::create([
            'username' => 'deleteuser',
            'password_hash' => Hash::make('password123'),
            'full_name' => 'User Delete',
            'email' => 'delete@example.com',
            'role_id' => $this->memberRole->role_id,
            'is_active' => true,
            'is_deleted' => false,
        ]);

        Livewire::test(AppUserManager::class)
            ->call('deleteUser', $targetUser->user_id);

        $targetUser->refresh();
        $this->assertTrue($targetUser->is_deleted);
    }
}
