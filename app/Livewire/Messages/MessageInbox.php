<?php

namespace App\Livewire\Messages;

use App\Models\Cool;
use App\Models\MemberMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pesan & Pokok Doa Jemaat')]
class MessageInbox extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $coolFilter = '';

    public ?int $viewingMessageId = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCoolFilter(): void
    {
        $this->resetPage();
    }

    protected function authorizeMessage(MemberMessage $msg): void
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;
        if ($isShepherd) {
            $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)
                ->where('is_deleted', false)
                ->pluck('cool_id')
                ->toArray();
            if (! in_array($msg->cool_id, $shepherdCoolIds)) {
                abort(403, 'Anda tidak memiliki akses ke pesan kelompok COOL ini.');
            }
        }
    }

    public function markAsRead(int $messageId): void
    {
        $msg = MemberMessage::findOrFail($messageId);
        $this->authorizeMessage($msg);

        $msg->update([
            'status' => 'READ',
            'read_at' => now(),
        ]);

        session()->flash('success', 'Pesan ditandai sebagai Sudah Dibaca.');
    }

    public function markAsResponded(int $messageId): void
    {
        $msg = MemberMessage::findOrFail($messageId);
        $this->authorizeMessage($msg);

        $msg->update([
            'status' => 'RESPONDED',
            'read_at' => $msg->read_at ?? now(),
        ]);

        session()->flash('success', 'Pesan ditandai Sudah Direspon.');
    }

    public function deleteMessage(int $messageId): void
    {
        $msg = MemberMessage::findOrFail($messageId);
        $this->authorizeMessage($msg);

        $msg->update([
            'is_deleted' => true,
            'deleted_by' => Auth::id() ?? 1,
            'date_deleted' => now(),
        ]);

        session()->flash('success', 'Pesan berhasil dihapus.');
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        $shepherdCoolIds = [];
        if ($isShepherd) {
            $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)
                ->where('is_deleted', false)
                ->pluck('cool_id')
                ->toArray();
        }

        $messages = MemberMessage::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->whereIn('cool_id', $shepherdCoolIds))
            ->when($this->coolFilter, fn ($q) => $q->where('cool_id', $this->coolFilter))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->where('message', 'like', "%{$this->search}%");
            })
            ->with(['cool', 'member', 'shepherd'])
            ->orderByDesc('date_created')
            ->paginate(10);

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        $unreadCount = MemberMessage::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->whereIn('cool_id', $shepherdCoolIds))
            ->where('status', 'SENT')
            ->count();

        return view('livewire.messages.message-inbox', compact('messages', 'cools', 'unreadCount', 'isShepherd'));
    }
}
