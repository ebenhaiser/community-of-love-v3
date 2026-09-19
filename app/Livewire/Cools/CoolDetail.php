<?php

namespace App\Livewire\Cools;

use App\Models\Cool;
use App\Models\QrAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Detail Kelompok COOL')]
class CoolDetail extends Component
{
    public int $coolId;

    public string $activeTab = 'members';

    public string $newPin = '';

    public function mount(int $coolId = 0, ?int $id = null): void
    {
        $this->coolId = $coolId ?: ($id ?? 0);

        $cool = Cool::findOrFail($this->coolId);
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            if ($cool->shepherd_id !== $user->shepherd_id) {
                abort(403, 'Anda hanya dapat mengakses kelompok COOL yang Anda gembalakan.');
            }
        }
    }

    public function updatePin(): void
    {
        $cool = Cool::findOrFail($this->coolId);
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD' && $cool->shepherd_id !== $user->shepherd_id) {
            abort(403, 'Akses ditolak.');
        }

        $this->validate([
            'newPin' => 'required|digits:6',
        ], [
            'newPin.required' => 'PIN baru wajib diisi.',
            'newPin.digits' => 'PIN harus terdiri dari 6 angka.',
        ]);

        $userId = Auth::id() ?? 1;
        $qr = QrAccess::where('cool_id', $this->coolId)->where('is_deleted', false)->first();

        if ($qr) {
            $qr->update([
                'pin_hash' => Hash::make($this->newPin),
                'modified_by' => $userId,
                'date_modified' => now(),
            ]);
        } else {
            QrAccess::create([
                'cool_id' => $this->coolId,
                'access_code' => 'COOL-'.$this->coolId.'-QR',
                'qr_token' => 'qr_'.Str::random(24),
                'pin_hash' => Hash::make($this->newPin),
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => $userId,
                'date_created' => now(),
            ]);
        }

        $this->newPin = '';
        session()->flash('success', 'PIN keamanan akses QR berhasil diperbarui.');
    }

    public function regenerateQrToken(): void
    {
        $cool = Cool::findOrFail($this->coolId);
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD' && $cool->shepherd_id !== $user->shepherd_id) {
            abort(403, 'Akses ditolak.');
        }

        $qr = QrAccess::where('cool_id', $this->coolId)->where('is_deleted', false)->first();
        if ($qr) {
            $qr->update([
                'qr_token' => 'qr_'.Str::random(24),
                'modified_by' => Auth::id() ?? 1,
                'date_modified' => now(),
            ]);
            session()->flash('success', 'Token QR Code berhasil diperbarui. Token lama otomatis tidak berlaku.');
        }
    }

    public function render()
    {
        $cool = Cool::with([
            'shepherd',
            'coolMembers' => fn ($q) => $q->where('cool_members.is_deleted', false)->where('status', 'ACTIVE')->with('member'),
            'activities' => fn ($q) => $q->where('activities.is_deleted', false)->with(['activityType', 'attendances'])->orderByDesc('activity_date'),
            'qrAccesses' => fn ($q) => $q->where('qr_accesses.is_deleted', false)->latest('qr_access_id'),
        ])->findOrFail($this->coolId);

        $activeQr = $cool->qrAccesses->first();

        return view('livewire.cools.cool-detail', compact('cool', 'activeQr'));
    }
}
