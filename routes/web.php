<?php

use App\Livewire\AboutUs\AboutUs;
use App\Livewire\Activities\ActivityIndex;
use App\Livewire\Attendances\AttendanceManager;
use App\Livewire\Auth\Login;
use App\Livewire\Bible\Reader;
use App\Livewire\Cools\CoolDetail;
use App\Livewire\Cools\CoolIndex;
use App\Livewire\Dashboard;
use App\Livewire\Events\EventIndex;
use App\Livewire\FollowUps\FollowUpIndex;
use App\Livewire\Master\AppUserManager;
use App\Livewire\Materials\MaterialManager;
use App\Livewire\Members\MemberIndex;
use App\Livewire\Messages\MessageInbox;
use App\Livewire\Profile\UserProfile;
use App\Livewire\Public\CoolPortal;
use App\Livewire\Public\MemberRegistration;
use App\Livewire\QrAccess\QrAccessManager;
use App\Livewire\Statistics\AttendanceStatistics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/daftar-jemaat', MemberRegistration::class)->name('jemaat.register');
Route::get('/register-jemaat', function () {
    return redirect()->route('jemaat.register');
});
Route::get('/c/{qrToken}', CoolPortal::class)->name('cool.portal');

Route::get('/bible', Reader::class)->name('bible.read'); // on dev

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    })->name('logout');

    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    });

    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', function () {
        return redirect('/');
    });

    // Komunitas COOL
    Route::get('/cools', CoolIndex::class)->name('cools.index');
    Route::get('/cools/{coolId}', CoolDetail::class)->name('cools.show');
    Route::get('/members', MemberIndex::class)->name('members.index');

    // Kegiatan & Presensi
    Route::get('/activities', ActivityIndex::class)->name('activities.index');
    Route::get('/attendances', AttendanceManager::class)->name('attendances.index');
    Route::get('/materials', MaterialManager::class)->name('materials.index');

    // Monitoring & Statistik
    Route::get('/statistics', AttendanceStatistics::class)->name('statistics.index');
    Route::get('/follow-ups', FollowUpIndex::class)->name('follow-ups.index');

    // Komunikasi & Akses
    Route::get('/messages', MessageInbox::class)->name('messages.index');
    Route::get('/qr-access', QrAccessManager::class)->name('qr-access.index');

    // Event Gereja (Phase 2)
    Route::get('/church-events', EventIndex::class)->name('church-events.index');

    // Profil & Pengaturan Akun
    Route::get('/profile', UserProfile::class)->name('profile.show');
    Route::get('/settings', UserProfile::class)->name('settings.show');
    Route::get('/master/users', AppUserManager::class)->name('master.users.index');

    // Tentang Kami
    Route::get('/about-us', AboutUs::class)->name('about-us.index');
});
