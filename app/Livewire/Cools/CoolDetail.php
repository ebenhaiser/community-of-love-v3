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

    public function mount(int $id): void
    {
        $this->coolId = $id;
    }

    public function updatePin(): void
    {
        $this->validate([
            'newPin' => 'required|string|min:4|max:10',
        ], [
            'newPin.required' => 'PIN baru wajib diisi.',
            'newPin.min' => 'PIN minimal 4 karakter.',
        ]);

        $qr = QrAccess::firstOrCreate(
            ['cool_id' => $this->coolId, 'is_deleted' => false],
            [
                'access_code' => 'COOL-'.$this->coolId.'-QR',
                'qr_token' => 'qr_'.Str::random(24),
                'pin_hash' => Hash::make($this->newPin),
                'is_active' => true,
                'created_by' => Auth::id() ?? 1,
                'date_created' => now(),
            ]
        );

        $qr->update([
            'pin_hash' => Hash::make($this->newPin),
            'modified_by' => Auth::id() ?? 1,
            'date_modified' => now(),
        ]);

        $this->newPin = '';
        session()->flash('success', 'PIN keamanan akses QR berhasil diperbarui.');
    }

    public function regenerateQrToken(): void
    {
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
            'coolMembers' => fn ($q) => $q->where('is_deleted', false)->where('status', 'ACTIVE')->with('member'),
            'activities' => fn ($q) => $q->where('is_deleted', false)->with(['activityType', 'attendances'])->orderByDesc('activity_date'),
            'qrAccesses' => fn ($q) => $q->where('is_deleted', false)->latest('qr_access_id'),
        ])->findOrFail($this->coolId);

        $activeQr = $cool->qrAccesses->first();

        return view('livewire.cools.cool-detail', compact('cool', 'activeQr'));
    }
}
