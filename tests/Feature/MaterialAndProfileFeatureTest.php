<?php

namespace Tests\Feature;

use App\Livewire\Materials\MaterialManager;
use App\Livewire\Profile\UserProfile;
use App\Models\Activity;
use App\Models\ActivityMaterial;
use App\Models\Cool;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MaterialAndProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $masterUser;

    private User $shepherdUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->masterUser = User::where('username', 'master')->firstOrFail();
        $this->shepherdUser = User::where('username', 'gembala.budi')->firstOrFail();
    }

    /**
     * Test QC: Material list and upload of document file.
     */
    public function test_material_manager_document_upload(): void
    {
        Storage::fake('public');

        $this->actingAs($this->masterUser);

        $activity = Activity::where('is_deleted', false)->firstOrFail();
        $fakeFile = UploadedFile::fake()->create('renungan_mingguan.pdf', 500, 'application/pdf');

        Livewire::test(MaterialManager::class)
            ->assertSee('Materi & Dokumen', false)
            ->call('openUploadModal')
            ->set('modal_activity_id', $activity->activity_id)
            ->set('material_type', 'DOCUMENT')
            ->set('file_name', 'Materi Kotbah Tematik')
            ->set('file_upload', $fakeFile)
            ->set('description', 'Materi PDF kotbah')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Materi Kotbah Tematik');

        $this->assertDatabaseHas('activity_materials', [
            'activity_id' => $activity->activity_id,
            'file_name' => 'Materi Kotbah Tematik',
            'material_type' => 'DOCUMENT',
            'is_deleted' => false,
        ]);
    }

    /**
     * Test QC: Material upload of external link.
     */
    public function test_material_manager_link_upload(): void
    {
        $this->actingAs($this->masterUser);

        $activity = Activity::where('is_deleted', false)->firstOrFail();

        Livewire::test(MaterialManager::class)
            ->call('openUploadModal')
            ->set('modal_activity_id', $activity->activity_id)
            ->set('material_type', 'LINK')
            ->set('file_name', 'Video Renungan YouTube')
            ->set('external_url', 'https://www.youtube.com/watch?v=sample123')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Video Renungan YouTube');

        $this->assertDatabaseHas('activity_materials', [
            'activity_id' => $activity->activity_id,
            'file_name' => 'Video Renungan YouTube',
            'material_type' => 'LINK',
            'external_url' => 'https://www.youtube.com/watch?v=sample123',
            'is_deleted' => false,
        ]);
    }

    /**
     * Test QC: Shepherd data isolation on materials.
     */
    public function test_shepherd_material_data_isolation(): void
    {
        $this->actingAs($this->shepherdUser);

        // Cool 1 belongs to shepherd Budi (shepherd_id = 1)
        // Cool 2 belongs to shepherd Sari (shepherd_id = 2)
        $actCool1 = Activity::where('cool_id', 1)->where('is_deleted', false)->firstOrFail();
        $actCool2 = Activity::where('cool_id', 2)->where('is_deleted', false)->firstOrFail();

        // 1. Shepherd Budi uploading to Cool 2's activity should fail validation
        Livewire::test(MaterialManager::class)
            ->call('openUploadModal')
            ->set('modal_activity_id', $actCool2->activity_id)
            ->set('material_type', 'LINK')
            ->set('file_name', 'Materi Terlarang')
            ->set('external_url', 'https://youtube.com/watch?v=forbidden')
            ->call('save')
            ->assertHasErrors(['modal_activity_id']);

        // 2. Shepherd Budi cannot delete material belonging to Cool 2
        $matCool2 = ActivityMaterial::create([
            'activity_id' => $actCool2->activity_id,
            'material_type' => 'LINK',
            'file_name' => 'Materi Milik Gembala Lain',
            'external_url' => 'https://youtube.com/watch?v=other',
            'is_deleted' => false,
            'uploaded_by' => 1,
            'date_uploaded' => now(),
        ]);

        Livewire::test(MaterialManager::class)
            ->call('deleteMaterial', $matCool2->material_id)
            ->assertForbidden();
    }

    /**
     * Test QC: UserProfile updates profile info and validates email uniqueness.
     */
    public function test_user_profile_update(): void
    {
        $this->actingAs($this->masterUser);

        // Update profile details
        Livewire::test(UserProfile::class)
            ->assertSet('full_name', $this->masterUser->full_name)
            ->assertSet('email', $this->masterUser->email)
            ->set('full_name', 'Master Administrator Diperbarui')
            ->set('phone', '081299990000')
            ->call('updateProfile')
            ->assertHasNoErrors()
            ->assertSee('Profil berhasil diperbarui');

        $this->masterUser->refresh();
        $this->assertEquals('Master Administrator Diperbarui', $this->masterUser->full_name);
        $this->assertEquals('081299990000', $this->masterUser->phone);

        // Attempt duplicate email with another user's email
        $otherUser = User::where('username', 'gembala.budi')->firstOrFail();

        Livewire::test(UserProfile::class)
            ->set('email', $otherUser->email)
            ->call('updateProfile')
            ->assertHasErrors(['email']);
    }

    /**
     * Test QC: UserProfile password update with correct vs incorrect credentials.
     */
    public function test_user_profile_password_update(): void
    {
        $this->actingAs($this->masterUser);

        // 1. Wrong current password fails
        Livewire::test(UserProfile::class)
            ->set('current_password', 'wrongcurrentpass')
            ->set('new_password', 'newsecret123')
            ->set('new_password_confirmation', 'newsecret123')
            ->call('updatePassword')
            ->assertHasErrors(['current_password']);

        // 2. Mismatched confirmation fails
        Livewire::test(UserProfile::class)
            ->set('current_password', 'password123')
            ->set('new_password', 'newsecret123')
            ->set('new_password_confirmation', 'differentpass123')
            ->call('updatePassword')
            ->assertHasErrors(['new_password']);

        // 3. Valid password change succeeds
        Livewire::test(UserProfile::class)
            ->set('current_password', 'password123')
            ->set('new_password', 'newsecret123')
            ->set('new_password_confirmation', 'newsecret123')
            ->call('updatePassword')
            ->assertHasNoErrors()
            ->assertSee('Kata sandi berhasil diperbarui');

        $this->masterUser->refresh();
        $this->assertTrue(Hash::check('newsecret123', $this->masterUser->password_hash));
    }
}
