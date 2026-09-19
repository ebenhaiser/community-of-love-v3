<?php

namespace App\Livewire\Public;

use App\Models\Activity;
use App\Models\ActivityMaterial;
use App\Models\Cool;
use App\Models\Member;
use App\Models\MemberMessage;
use App\Models\MemberSession;
use App\Models\Notification;
use App\Models\QrAccess;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Portal Jemaat COOL GBI Salemba')]
class CoolPortal extends Component
{
    public string $qrToken = '';

    public bool $isVerified = false;

    public string $pin = '';

    public ?int $coolId = null;

    public string $senderName = '';

    public string $senderPhone = '';

    public string $messageType = 'Pokok Doa';

    public string $messageContent = '';

    public string $activeTab = 'activities';

    public function mount(string $qrToken): void
    {
        $this->qrToken = $qrToken;

        $qr = QrAccess::where('qr_token', $this->qrToken)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->firstOrFail();

        $this->coolId = $qr->cool_id;

        if (session()->get('cool_verified_'.$this->qrToken)) {
            $this->isVerified = true;
        }
    }

    public function verifyPin(): void
    {
        $this->validate([
            'pin' => 'required|digits:6',
        ], [
            'pin.required' => 'Masukkan 6 digit PIN kelompok COOL Anda.',
            'pin.digits' => 'PIN harus terdiri dari 6 angka.',
        ]);

        $qr = QrAccess::where('qr_token', $this->qrToken)->firstOrFail();

        // Check PIN: supports Hash check or plain fallback
        $isValid = Hash::check($this->pin, $qr->pin_hash) || ($qr->pin_hash === $this->pin);

        if (! $isValid) {
            $this->addError('pin', 'PIN yang Anda masukkan salah. Silakan tanyakan kepada Gembala COOL Anda.');

            return;
        }

        // Record member session audit
        $session = MemberSession::create([
            'qr_access_id' => $qr->qr_access_id,
            'session_token_hash' => hash('sha256', bin2hex(random_bytes(16))),
            'created_at' => now(),
            'expires_at' => now()->addHours(24),
            'last_activity_at' => now(),
            'is_revoked' => false,
        ]);

        $qr->update(['last_used_at' => now()]);

        session()->put('cool_verified_'.$this->qrToken, true);
        session()->put('cool_session_id_'.$this->qrToken, $session->session_id);

        $this->isVerified = true;
        $this->pin = '';
    }

    public function exitPortal(): void
    {
        session()->forget('cool_verified_'.$this->qrToken);
        session()->forget('cool_session_id_'.$this->qrToken);
        $this->isVerified = false;
    }

    public function sendMessage(): void
    {
        $this->validate([
            'senderName' => 'required|string|max:100',
            'senderPhone' => 'nullable|string|max:20',
            'messageType' => 'required|string',
            'messageContent' => 'required|string|max:2000',
        ], [
            'senderName.required' => 'Nama lengkap wajib diisi.',
            'messageContent.required' => 'Isi pesan atau pokok doa wajib dituliskan.',
        ]);

        $cool = Cool::with('shepherd')->findOrFail($this->coolId);

        // Try to locate member by name or phone
        $memberId = null;
        if ($this->senderPhone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $this->senderPhone);
            $existingMember = Member::where('phone', 'like', "%{$cleanPhone}%")
                ->where('is_deleted', false)
                ->first();
            if ($existingMember) {
                $memberId = $existingMember->member_id;
            }
        }

        $sessionId = session()->get('cool_session_id_'.$this->qrToken);

        $formattedMessage = "[{$this->messageType}] dari {$this->senderName}".($this->senderPhone ? " ({$this->senderPhone})" : '').":\n\n".$this->messageContent;

        $msg = MemberMessage::create([
            'cool_id' => $cool->cool_id,
            'member_id' => $memberId,
            'shepherd_id' => $cool->shepherd_id,
            'member_session_id' => $sessionId,
            'message' => $formattedMessage,
            'status' => 'SENT',
            'is_deleted' => false,
            'date_created' => now(),
        ]);

        // Send notification to Shepherd user
        $shepherdUser = User::where('shepherd_id', $cool->shepherd_id)->first();
        if ($shepherdUser) {
            Notification::create([
                'user_id' => $shepherdUser->user_id,
                'notification_type' => 'MEMBER_MESSAGE',
                'title' => "Pesan Baru: {$this->messageType} dari {$this->senderName}",
                'message' => "Kelompok COOL {$cool->name}: {$this->messageContent}",
                'reference_type' => 'member_messages',
                'reference_id' => $msg->message_id,
                'is_deleted' => false,
                'date_created' => now(),
            ]);
        }

        session()->flash('message_sent', 'Puji Tuhan! Pesan / pokok doa Anda telah berhasil terkirim ke Gembala COOL.');
        $this->reset(['senderName', 'senderPhone', 'messageContent']);
        $this->messageType = 'Pokok Doa';
    }

    public function render()
    {
        $cool = Cool::with(['shepherd', 'members' => fn ($q) => $q->wherePivot('is_deleted', false)->where('members.is_deleted', false)])->findOrFail($this->coolId);

        $upcomingActivities = Activity::where('cool_id', $this->coolId)
            ->where('is_deleted', false)
            ->where('activity_date', '>=', now()->subDays(1)->toDateString())
            ->with('activityType')
            ->orderBy('activity_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $materials = ActivityMaterial::where('is_deleted', false)
            ->whereHas('activity', fn ($q) => $q->where('cool_id', $this->coolId))
            ->with('activity')
            ->orderByDesc('date_uploaded')
            ->take(8)
            ->get();

        return view('livewire.public.cool-portal', compact('cool', 'upcomingActivities', 'materials'));
    }
}
