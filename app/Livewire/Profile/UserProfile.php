<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Profil & Pengaturan Akun')]
class UserProfile extends Component
{
    public string $activeTab = 'profile';

    // Profile fields
    public string $full_name = '';

    public string $email = '';

    public string $phone = '';

    public string $username = '';

    // Password fields
    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->full_name = $user->full_name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->username = $user->username ?? '';
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $this->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:app_users,email,'.$user->user_id.',user_id',
            'phone' => 'nullable|string|max:50',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->update([
            'full_name' => $this->full_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'modified_by' => $user->user_id,
            'date_modified' => now(),
        ]);

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $user = Auth::user();

        $this->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'new_password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        if (! Hash::check($this->current_password, $user->password_hash)) {
            $this->addError('current_password', 'Kata sandi saat ini tidak sesuai.');

            return;
        }

        $user->update([
            'password_hash' => Hash::make($this->new_password),
            'modified_by' => $user->user_id,
            'date_modified' => now(),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->resetValidation();

        session()->flash('success', 'Kata sandi berhasil diperbarui.');
    }

    public function render()
    {
        $user = Auth::user()->load('role', 'shepherd');

        return view('livewire.profile.user-profile', compact('user'));
    }
}
