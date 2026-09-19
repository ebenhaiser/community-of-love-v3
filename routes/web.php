<?php

use App\Livewire\Activities\ActivityIndex;
use App\Livewire\Attendances\AttendanceManager;
use App\Livewire\Auth\Login;
use App\Livewire\Cools\CoolDetail;
use App\Livewire\Cools\CoolIndex;
use App\Livewire\Dashboard;
use App\Livewire\Events\EventIndex;
use App\Livewire\Materials\MaterialManager;
use App\Livewire\Members\MemberIndex;
use App\Livewire\Messages\MessageInbox;
use App\Livewire\Public\CoolPortal;
use App\Livewire\QrAccess\QrAccessManager;
use App\Livewire\Shepherds\ShepherdIndex;
use App\Livewire\Statistics\AttendanceStatistics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/c/{qrToken}', CoolPortal::class)->name('cool.portal');

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
    Route::get('/shepherds', ShepherdIndex::class)->name('shepherds.index');
    Route::get('/members', MemberIndex::class)->name('members.index');

    // Kegiatan & Presensi
    Route::get('/activities', ActivityIndex::class)->name('activities.index');
    Route::get('/attendances', AttendanceManager::class)->name('attendances.index');
    Route::get('/materials', MaterialManager::class)->name('materials.index');

    // Monitoring & Statistik
    Route::get('/statistics', AttendanceStatistics::class)->name('statistics.index');

    // Komunikasi & Akses
    Route::get('/messages', MessageInbox::class)->name('messages.index');
    Route::get('/qr-access', QrAccessManager::class)->name('qr-access.index');

    // Event Gereja (Phase 2)
    Route::get('/church-events', EventIndex::class)->name('church-events.index');
});
