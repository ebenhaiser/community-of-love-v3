<?php

namespace App\Livewire\Auth;

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Masuk ke Dashboard')]
class Login extends Component
{
    public string $account = '';

    public string $password = '';

    public bool $remember = false;

    public string $defaultPassword = '';

    public function login(): void
    {
        $this->validate([
            'account' => 'required|string',
            'password' => 'required|string',
        ], [
            'account.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($this->account).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('account', "Terlalu banyak percobaan masuk. Silakan coba lagi dalam {$seconds} detik.");

            return;
        }

        // Determine if account is email or username
        $isEmail = filter_var($this->account, FILTER_VALIDATE_EMAIL);
        $credentials = [
            $isEmail ? 'email' : 'username' => $this->account,
            'password' => $this->password,
            'is_deleted' => false,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            RateLimiter::clear($throttleKey);
            session()->regenerate();

            $this->redirectIntended(default: route('dashboard'));

            return;
        }

        RateLimiter::hit($throttleKey);

        $this->addError('account', 'Username/email atau kata sandi tidak cocok dengan data kami.');
    }

    public function render()
    {
        $this->defaultPassword = AppHelper::getSettings('default_user_password') ?? 'password123';

        return view('livewire.auth.login');
    }
}
