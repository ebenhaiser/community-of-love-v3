<?php

namespace App\Livewire\Public;

use App\Models\Cool;
use App\Models\CoolMember;
use App\Models\Member;
use App\Models\MemberFollowUp;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Formulir Pendaftaran Jemaat Baru - GBI Salemba')]
class MemberRegistration extends Component
{
    /**
     * Stepper tracking (1: Data Diri, 2: Kontak & Alamat, 3: Komunitas & Doa, 4: Berhasil)
     */
    public int $currentStep = 1;

    public int $totalSteps = 3;

    public bool $isSuccess = false;

    // Optional query parameter to pre-select COOL group
    #[Url(as: 'cool')]
    public ?string $coolCode = null;

    #[Url(as: 'cool_id')]
    public ?int $preselectedCoolId = null;

    // Step 1: Data Pribadi
    public string $name = '';

    public string $gender = 'Laki-laki';

    public string $birthplace = '';

    public string $birthdate = '';

    public string $marital_status = 'Belum Menikah';

    // Step 2: Kontak & Domisili
    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $social_media = '';

    // Step 3: Kehidupan Rohani & Komunitas COOL
    public string $kom_status = 'Belum KOM';

    public string $wants_cool = 'yes'; // 'yes', 'recommend', 'no'

    public ?int $cool_id = null;

    public string $prayer_request = '';

    // Success details
    public ?string $registeredMemberCode = null;

    public ?string $registeredName = null;

    public ?string $assignedCoolName = null;

    /**
     * Mount component and handle optional pre-selection
     */
    public function mount(?int $cool_id = null, ?string $cool = null): void
    {
        if ($cool_id || $this->preselectedCoolId) {
            $targetId = $cool_id ?: $this->preselectedCoolId;
            $coolExists = Cool::where('cool_id', $targetId)
                ->where('is_deleted', false)
                ->where('is_active', true)
                ->exists();

            if ($coolExists) {
                $this->cool_id = (int) $targetId;
                $this->wants_cool = 'yes';
            }
        } elseif ($cool || $this->coolCode) {
            $targetCode = $cool ?: $this->coolCode;
            $foundCool = Cool::where('cool_code', $targetCode)
                ->where('is_deleted', false)
                ->where('is_active', true)
                ->first();

            if ($foundCool) {
                $this->cool_id = $foundCool->cool_id;
                $this->wants_cool = 'yes';
            }
        }
    }

    /**
     * Realtime reactivity for COOL selection preference
     */
    public function updatedWantsCool(string $value): void
    {
        if ($value !== 'yes') {
            $this->cool_id = null;
        }
    }

    /**
     * Step navigation: proceed to next step after validating current step
     */
    public function nextStep(): void
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->dispatch('scroll-to-top');
        }
    }

    /**
     * Step navigation: return to previous step
     */
    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('scroll-to-top');
        }
    }

    /**
     * Go to a specific step if prior steps are valid
     */
    public function goToStep(int $step): void
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
            $this->dispatch('scroll-to-top');

            return;
        }

        if ($step > $this->currentStep) {
            $this->validateCurrentStep();
            $this->currentStep = $step;
            $this->dispatch('scroll-to-top');
        }
    }

    /**
     * Validation rules for each step
     */
    public function validateCurrentStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'gender' => ['required', 'string', Rule::in(['Laki-laki', 'Perempuan'])],
                'birthplace' => ['nullable', 'string', 'max:100'],
                'birthdate' => ['nullable', 'date', 'before:today'],
                'marital_status' => ['required', 'string', Rule::in(['Belum Menikah', 'Menikah', 'Janda/Duda'])],
            ], [
                'name.required' => 'Nama lengkap jemaat wajib diisi.',
                'name.min' => 'Nama lengkap minimal 3 karakter.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'birthdate.before' => 'Tanggal lahir harus sebelum hari ini.',
                'marital_status.required' => 'Status pernikahan wajib dipilih.',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'phone' => ['required', 'string', 'min:8', 'max:25'],
                'email' => ['nullable', 'email', 'max:255'],
                'address' => ['nullable', 'string', 'max:1000'],
                'social_media' => ['nullable', 'string', 'max:255'],
            ], [
                'phone.required' => 'Nomor WhatsApp / HP aktif wajib diisi untuk koordinasi pastoral.',
                'phone.min' => 'Nomor telepon minimal 8 digit.',
                'email.email' => 'Format alamat email tidak valid.',
            ]);
        }
    }

    /**
     * Generate unique member code (e.g. JMT-2026-0001)
     */
    protected function generateMemberCode(): string
    {
        $year = date('Y');
        $maxId = Member::max('member_id') ?? 0;
        $counter = $maxId + 1;
        $code = 'JMT-'.$year.'-'.str_pad((string) $counter, 4, '0', STR_PAD_LEFT);

        while (Member::where('member_code', $code)->exists()) {
            $counter++;
            $code = 'JMT-'.$year.'-'.str_pad((string) $counter, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /**
     * Submit and save new member registration
     */
    public function register(): void
    {
        // Validate all steps before writing to database
        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'gender' => ['required', 'string', Rule::in(['Laki-laki', 'Perempuan'])],
            'birthplace' => ['nullable', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'marital_status' => ['required', 'string', Rule::in(['Belum Menikah', 'Menikah', 'Janda/Duda'])],
            'phone' => ['required', 'string', 'min:8', 'max:25'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'social_media' => ['nullable', 'string', 'max:255'],
            'kom_status' => ['required', 'string', Rule::in(['Belum KOM', 'KOM 100', 'KOM 200', 'KOM 300', 'KOM 400'])],
            'wants_cool' => ['required', 'string', Rule::in(['yes', 'recommend', 'no'])],
            'cool_id' => ['nullable', 'exists:cools,cool_id'],
            'prayer_request' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama lengkap jemaat wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'phone.required' => 'Nomor WhatsApp / HP aktif wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'marital_status.required' => 'Status pernikahan wajib dipilih.',
            'kom_status.required' => 'Status KOM wajib dipilih.',
        ]);

        if ($this->wants_cool === 'yes' && empty($this->cool_id)) {
            $this->addError('cool_id', 'Silakan pilih kelompok COOL yang ingin Anda ikuti, atau pilih opsi "Rekomendasikan untuk Saya".');

            return;
        }

        DB::transaction(function (): void {
            $memberCode = $this->generateMemberCode();
            $effectiveIsInCool = ($this->wants_cool === 'yes' && ! empty($this->cool_id));

            $member = Member::create([
                'member_code' => $memberCode,
                'name' => trim($this->name),
                'gender' => $this->gender,
                'birthplace' => $this->birthplace ?: null,
                'birthdate' => $this->birthdate ?: null,
                'address' => $this->address ?: null,
                'social_media' => $this->social_media ?: null,
                'kom_status' => $this->kom_status,
                'marital_status' => $this->marital_status,
                'is_in_cool' => $effectiveIsInCool,
                'phone' => $this->phone,
                'email' => $this->email ?: null,
                'join_date' => now()->toDateString(),
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => null, // Public self-registration
                'date_created' => now(),
            ]);

            $selectedCool = null;

            // Handle COOL membership if specific group chosen
            if ($effectiveIsInCool && $this->cool_id) {
                $selectedCool = Cool::with('shepherd')->find($this->cool_id);

                CoolMember::create([
                    'cool_id' => $this->cool_id,
                    'member_id' => $member->member_id,
                    'start_date' => now()->toDateString(),
                    'status' => 'ACTIVE',
                    'is_deleted' => false,
                    'created_by' => null,
                    'date_created' => now(),
                ]);
            }

            // Record initial pastoral note / follow-up if prayer request exists or COOL recommendation requested
            $needsFollowUp = ! empty($this->prayer_request) || $this->wants_cool === 'recommend';

            if ($needsFollowUp) {
                $followUpNotes = [];
                if ($this->wants_cool === 'recommend') {
                    $followUpNotes[] = '[Permintaan Penempatan COOL] Jemaat baru ingin direkomendasikan kelompok COOL terdekat.';
                }
                if (! empty($this->prayer_request)) {
                    $followUpNotes[] = "[Pokok Doa Awal]:\n".$this->prayer_request;
                }

                MemberFollowUp::create([
                    'member_id' => $member->member_id,
                    'cool_id' => $this->cool_id,
                    'status' => 'PENDING',
                    'consecutive_absent_count' => 0,
                    'action_taken' => 'Pendaftaran Jemaat Baru',
                    'notes' => implode("\n\n", $followUpNotes),
                    'is_deleted' => false,
                    'date_created' => now(),
                ]);
            }

            // Create notification for active church administrators
            $adminUsers = User::where('is_deleted', false)
                ->where('is_active', true)
                ->get();

            $coolInfoText = $selectedCool ? "Kelompok: {$selectedCool->name}" : ($this->wants_cool === 'recommend' ? 'Perlu Rekomendasi COOL' : 'Belum Ber-COOL');

            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->user_id,
                    'notification_type' => 'NEW_MEMBER',
                    'title' => "Pendaftaran Jemaat Baru: {$member->name}",
                    'message' => "Jemaat baru {$member->name} ({$member->member_code}) telah mendaftar mandiri. {$coolInfoText}. No HP: {$member->phone}",
                    'reference_type' => 'members',
                    'reference_id' => $member->member_id,
                    'is_deleted' => false,
                    'date_created' => now(),
                ]);
            }

            // If a specific COOL was chosen, also notify the Shepherd
            if ($selectedCool && $selectedCool->shepherd_id) {
                $shepherdUser = User::where('member_id', $selectedCool->shepherd_id)
                    ->where('is_deleted', false)
                    ->first();

                if ($shepherdUser && ! $adminUsers->contains('user_id', $shepherdUser->user_id)) {
                    Notification::create([
                        'user_id' => $shepherdUser->user_id,
                        'notification_type' => 'NEW_MEMBER',
                        'title' => "Anggota Baru di COOL {$selectedCool->name}: {$member->name}",
                        'message' => "Jemaat baru {$member->name} ({$member->member_code}) mendaftar dan memilih kelompok COOL Anda. No HP: {$member->phone}",
                        'reference_type' => 'members',
                        'reference_id' => $member->member_id,
                        'is_deleted' => false,
                        'date_created' => now(),
                    ]);
                }
            }

            $this->registeredMemberCode = $member->member_code;
            $this->registeredName = $member->name;
            $this->assignedCoolName = $selectedCool ? $selectedCool->name : ($this->wants_cool === 'recommend' ? 'Menunggu Rekomendasi Tim Pastoral' : null);
            $this->isSuccess = true;
        });

        $this->dispatch('notify', message: 'Puji Tuhan! Formulir pendaftaran jemaat baru berhasil dikirimkan.', type: 'success');
        $this->dispatch('scroll-to-top');
    }

    /**
     * Reset form to allow registering another member
     */
    public function resetForm(): void
    {
        $this->reset([
            'name',
            'gender',
            'birthplace',
            'birthdate',
            'phone',
            'email',
            'address',
            'social_media',
            'cool_id',
            'prayer_request',
            'registeredMemberCode',
            'registeredName',
            'assignedCoolName',
        ]);

        $this->gender = 'Laki-laki';
        $this->marital_status = 'Belum Menikah';
        $this->kom_status = 'Belum KOM';
        $this->wants_cool = 'yes';
        $this->currentStep = 1;
        $this->isSuccess = false;
        $this->resetValidation();
        $this->dispatch('scroll-to-top');
    }

    public function render()
    {
        $cools = Cool::with('shepherd')
            ->where('is_deleted', false)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.public.member-registration', [
            'cools' => $cools,
        ]);
    }
}
