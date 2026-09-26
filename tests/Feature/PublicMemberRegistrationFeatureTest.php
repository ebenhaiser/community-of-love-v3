<?php

namespace Tests\Feature;

use App\Livewire\Public\MemberRegistration;
use App\Models\Cool;
use App\Models\Member;
use App\Models\MemberFollowUp;
use App\Models\Notification;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicMemberRegistrationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_registration_page_is_accessible_without_authentication(): void
    {
        $response = $this->get(route('jemaat.register'));
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Jemaat Baru');
        $response->assertSee('GBI SALEMBA');
    }

    public function test_register_alias_redirects_to_registration_page(): void
    {
        $response = $this->get('/register-jemaat');
        $response->assertRedirect(route('jemaat.register'));
    }

    public function test_step_1_requires_name_gender_and_marital_status(): void
    {
        Livewire::test(MemberRegistration::class)
            ->set('name', '')
            ->call('nextStep')
            ->assertHasErrors(['name'])
            ->assertSet('currentStep', 1);

        Livewire::test(MemberRegistration::class)
            ->set('name', 'Jo') // min 3 chars
            ->call('nextStep')
            ->assertHasErrors(['name'])
            ->assertSet('currentStep', 1);
    }

    public function test_step_2_requires_valid_phone_number(): void
    {
        Livewire::test(MemberRegistration::class)
            ->set('name', 'Yohanes Calvin')
            ->set('gender', 'Laki-laki')
            ->set('marital_status', 'Belum Menikah')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 2)
            ->set('phone', '')
            ->call('nextStep')
            ->assertHasErrors(['phone'])
            ->assertSet('currentStep', 2);
    }

    public function test_successful_public_registration_without_cool(): void
    {
        Livewire::test(MemberRegistration::class)
            ->set('name', 'Lukas Kristanto')
            ->set('gender', 'Laki-laki')
            ->set('birthplace', 'Surabaya')
            ->set('birthdate', '1996-05-10')
            ->set('marital_status', 'Belum Menikah')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('phone', '081234567891')
            ->set('email', 'lukas@example.com')
            ->set('address', 'Jl. Kramat Raya No. 12, Jakarta Pusat')
            ->set('social_media', '@lukaskris')
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->set('kom_status', 'Belum KOM')
            ->set('wants_cool', 'no')
            ->call('register')
            ->assertHasNoErrors()
            ->assertSet('isSuccess', true)
            ->assertSee('Selamat Datang di GBI Salemba!')
            ->assertSee('Lukas Kristanto');

        $member = Member::where('name', 'Lukas Kristanto')->first();
        $this->assertNotNull($member);
        $this->assertEquals('Laki-laki', $member->gender);
        $this->assertEquals('081234567891', $member->phone);
        $this->assertEquals('Belum KOM', $member->kom_status);
        $this->assertFalse($member->is_in_cool);
        $this->assertTrue($member->is_active);
        $this->assertFalse($member->is_deleted);
        $this->assertStringStartsWith('JMT-'.date('Y'), $member->member_code);

        // Verify admin notification created
        $this->assertDatabaseHas('notifications', [
            'notification_type' => 'NEW_MEMBER',
            'reference_type' => 'members',
            'reference_id' => $member->member_id,
        ]);
    }

    public function test_successful_public_registration_with_cool_assignment(): void
    {
        $cool = Cool::where('is_deleted', false)->where('is_active', true)->first();
        $this->assertNotNull($cool);

        Livewire::test(MemberRegistration::class)
            ->set('name', 'Priscilla Gunawan')
            ->set('gender', 'Perempuan')
            ->set('marital_status', 'Menikah')
            ->set('phone', '081298765432')
            ->set('email', 'priscilla@example.com')
            ->set('kom_status', 'KOM 100')
            ->set('wants_cool', 'yes')
            ->set('cool_id', $cool->cool_id)
            ->call('register')
            ->assertHasNoErrors()
            ->assertSet('isSuccess', true);

        $member = Member::where('name', 'Priscilla Gunawan')->first();
        $this->assertNotNull($member);
        $this->assertTrue($member->is_in_cool);

        $this->assertDatabaseHas('cool_members', [
            'cool_id' => $cool->cool_id,
            'member_id' => $member->member_id,
            'status' => 'ACTIVE',
            'is_deleted' => false,
        ]);

        // If shepherd user exists for this cool's shepherd_id, check notification
        $shepherdUser = User::where('member_id', $cool->shepherd_id)->first();
        if ($shepherdUser) {
            $this->assertDatabaseHas('notifications', [
                'user_id' => $shepherdUser->user_id,
                'notification_type' => 'NEW_MEMBER',
                'reference_type' => 'members',
                'reference_id' => $member->member_id,
            ]);
        }
    }

    public function test_prayer_request_creates_pastoral_follow_up_record(): void
    {
        Livewire::test(MemberRegistration::class)
            ->set('name', 'Barnabas Hadi')
            ->set('gender', 'Laki-laki')
            ->set('marital_status', 'Belum Menikah')
            ->set('phone', '081211112222')
            ->set('kom_status', 'Belum KOM')
            ->set('wants_cool', 'recommend')
            ->set('prayer_request', 'Mohon dukungan doa untuk pekerjaan baru dan pemulihan kesehatan keluarga.')
            ->call('register')
            ->assertHasNoErrors()
            ->assertSet('isSuccess', true);

        $member = Member::where('name', 'Barnabas Hadi')->first();
        $this->assertNotNull($member);

        $followUp = MemberFollowUp::where('member_id', $member->member_id)->first();
        $this->assertNotNull($followUp);
        $this->assertEquals('PENDING', $followUp->status);
        $this->assertStringContainsString('Mohon dukungan doa untuk pekerjaan baru', $followUp->notes);
    }

    public function test_preselection_of_cool_via_query_parameters(): void
    {
        $cool = Cool::where('is_deleted', false)->where('is_active', true)->first();

        Livewire::withQueryParams(['cool_id' => $cool->cool_id])
            ->test(MemberRegistration::class)
            ->assertSet('cool_id', $cool->cool_id)
            ->assertSet('wants_cool', 'yes');
    }

    public function test_reset_form_allows_registering_another_member(): void
    {
        $component = Livewire::test(MemberRegistration::class)
            ->set('name', 'Petrus Simon')
            ->set('gender', 'Laki-laki')
            ->set('marital_status', 'Menikah')
            ->set('phone', '081233334444')
            ->set('kom_status', 'KOM 200')
            ->set('wants_cool', 'no')
            ->call('register')
            ->assertSet('isSuccess', true)
            ->call('resetForm')
            ->assertSet('isSuccess', false)
            ->assertSet('currentStep', 1)
            ->assertSet('name', '')
            ->assertSet('phone', '');
    }
}
