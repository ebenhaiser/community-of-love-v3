<?php

namespace App\Livewire\QrAccess;

use App\Helpers\AppHelper;
use App\Models\Cool;
use App\Models\QrAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Manajemen Akses QR & PIN Jemaat')]
class QrAccessManager extends Component
{
    public bool $showPinModal = false;

    public ?int $selectedQrAccessId = null;

    public string $selectedCoolName = '';

    public string $newPin = '';

    public function openPinModal(int $qrAccessId): void
    {
        $qr = QrAccess::with('cool')->findOrFail($qrAccessId);
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        if ($isShepherd && $qr->cool && $qr->cool->shepherd_id !== $user->member_id) {
            abort(403, 'Anda hanya dapat mengelola akses kelompok COOL Anda sendiri.');
        }

        $this->selectedQrAccessId = $qr->qr_access_id;
        $this->selectedCoolName = $qr->cool->name ?? 'COOL';
        $this->newPin = '';
        $this->showPinModal = true;
    }

    public function updatePin(): void
    {
        $this->validate([
            'newPin' => 'required|digits:6',
        ], [
            'newPin.required' => 'PIN 6-digit wajib diisi.',
            'newPin.digits' => 'PIN harus terdiri dari 6 angka.',
        ]);

        $qr = QrAccess::with('cool')->findOrFail($this->selectedQrAccessId);
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        if ($isShepherd && $qr->cool && $qr->cool->shepherd_id !== $user->member_id) {
            abort(403, 'Akses ditolak.');
        }

        $qr->update([
            'pin_hash' => Hash::make($this->newPin),
            'modified_by' => Auth::id() ?? 1,
            'date_modified' => now(),
        ]);

        session()->flash('success', "PIN untuk {$this->selectedCoolName} berhasil diperbarui menjadi: {$this->newPin}");
        $this->dispatch('notify', message: "PIN untuk {$this->selectedCoolName} berhasil diperbarui menjadi: {$this->newPin}", type: 'success');
        $this->showPinModal = false;
        $this->newPin = '';
    }

    public function regenerateToken(int $qrAccessId): void
    {
        $qr = QrAccess::with('cool')->findOrFail($qrAccessId);
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        if ($isShepherd && $qr->cool && $qr->cool->shepherd_id !== $user->member_id) {
            abort(403, 'Akses ditolak.');
        }

        $newToken = 'qr-'.Str::slug($qr->cool->name ?? 'cool').'-'.Str::random(12);

        $qr->update([
            'qr_token' => $newToken,
            'modified_by' => Auth::id() ?? 1,
            'date_modified' => now(),
        ]);

        session()->flash('success', "Token QR untuk {$qr->cool->name} berhasil diperbarui.");
        $this->dispatch('notify', message: "Token QR untuk {$qr->cool->name} berhasil diperbarui.", type: 'success');
    }

    public function generateMissingQr(int $coolId): void
    {
        $cool = Cool::findOrFail($coolId);
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        if ($isShepherd && $cool->shepherd_id !== $user->member_id) {
            abort(403, 'Akses ditolak.');
        }

        $token = 'qr-'.Str::slug($cool->name).'-'.Str::random(12);
        $defaultPin = AppHelper::getSettings('default_qr_pin');

        QrAccess::create([
            'cool_id' => $cool->cool_id,
            'access_code' => 'ACC-'.strtoupper(Str::random(6)),
            'pin_hash' => Hash::make($defaultPin),
            'qr_token' => $token,
            'is_active' => true,
            'is_deleted' => false,
            'created_by' => Auth::id() ?? 1,
            'date_created' => now(),
        ]);

        session()->flash('success', "Akses QR berhasil dibuat untuk {$cool->name}. Default PIN: ".$defaultPin);
        $this->dispatch('notify', message: "Akses QR berhasil dibuat untuk {$cool->name}. Default PIN: ".$defaultPin, type: 'success');
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->member_id))
            ->with(['shepherd', 'qrAccess' => fn ($q) => $q->where('qr_accesses.is_deleted', false)])
            ->orderBy('name')
            ->get();

        return view('livewire.qr-access.qr-access-manager', compact('cools', 'isShepherd'));
    }
}
