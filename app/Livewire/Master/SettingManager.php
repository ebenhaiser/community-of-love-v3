<?php

namespace App\Livewire\Master;

use App\Helpers\AppHelper;
use App\Models\Role;
use App\Models\Shepherd;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pengaturan Aplikasi')]
class SettingManager extends Component
{
    use WithPagination;

    // Komponen ini akan menjadi halaman utama pengaturan
    // Tidak ada logika CRUD spesifik di sini, hanya routing ke sub-komponen

    public function render()
    {
        $this->authorizeMaster();
        

        return view('livewire.master.setting-manager');
    }

    public function mount(): void
    {
        $this->authorizeMaster();
    }

    protected function authorizeMaster(): void
    {
        $user = Auth::user();
        if (! $user || ! $user->role || $user->role->name !== 'MASTER') {
            abort(403, 'Akses ditolak. Hanya pengguna dengan peran MASTER yang dapat mengakses halaman ini.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}