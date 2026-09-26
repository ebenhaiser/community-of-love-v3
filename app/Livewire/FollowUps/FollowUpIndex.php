<?php

namespace App\Livewire\FollowUps;

use App\Models\Cool;
use App\Models\Member;
use App\Models\MemberFollowUp;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Master Perhatian & Follow-up Pastoral')]
class FollowUpIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusTab = 'PENDING'; // ALL, PENDING, RESOLVED

    public string $coolFilter = '';

    public int $consecutiveThreshold = 2;

    public int $perPage = 15;

    protected string $paginationTheme = 'bootstrap';

    // Modal properties
    public bool $showModal = false;

    public ?int $selectedFollowUpId = null;

    public string $selectedMemberName = '';

    public string $action_taken = 'WhatsApp / Telepon';

    public string $notes = '';

    public string $status = 'RESOLVED';

    public function mount(): void
    {
        $this->syncConsecutiveAbsences();

        $hasPending = MemberFollowUp::where('status', 'PENDING')->where('is_deleted', false)->exists();
        $this->statusTab = $hasPending ? 'PENDING' : 'ALL';
    }

    /**
     * Auto-detect members with consecutive absences and ensure they have a follow-up record.
     */
    public function syncConsecutiveAbsences(): void
    {
        $allMembers = Member::where('is_deleted', false)
            ->with([
                'cools' => fn ($q) => $q->wherePivot('is_deleted', false)->where('cools.is_deleted', false),
                'coolMembers.cool',
                'attendances' => function ($q) {
                    $q->where('attendances.is_deleted', false)
                        ->whereHas('activity', fn ($a) => $a->where('activities.is_deleted', false))
                        ->with(['activity', 'status']);
                },
                'latestFollowUp',
            ])
            ->get();

        foreach ($allMembers as $member) {
            $sortedAttendances = $member->attendances->sortByDesc(
                fn ($att) => $att->activity?->activity_date?->timestamp ?? ($att->attendance_time ? $att->attendance_time->timestamp : $att->attendance_id)
            );

            $consecutiveCount = 0;

            foreach ($sortedAttendances as $att) {
                $statusCode = $att->status->code ?? '';
                if ($statusCode === 'ABSENT') {
                    $consecutiveCount++;
                } elseif ($statusCode === 'PRESENT') {
                    break;
                }
            }

            $cool = $member->cools->first() ?? $member->coolMembers->first()?->cool;
            $coolId = $cool?->cool_id;

            if ($consecutiveCount >= $this->consecutiveThreshold) {
                // If member doesn't have an active follow-up, create one
                $existing = MemberFollowUp::where('member_id', $member->member_id)
                    ->where('is_deleted', false)
                    ->latest('follow_up_id')
                    ->first();

                if (! $existing) {
                    MemberFollowUp::create([
                        'member_id' => $member->member_id,
                        'cool_id' => $coolId,
                        'consecutive_absent_count' => $consecutiveCount,
                        'status' => 'PENDING',
                        'is_deleted' => false,
                        'created_by' => Auth::id(),
                        'date_created' => now(),
                    ]);
                } elseif ($existing->status === 'PENDING') {
                    // Update latest count and cool if changed
                    $existing->update([
                        'consecutive_absent_count' => $consecutiveCount,
                        'cool_id' => $coolId,
                        'date_modified' => now(),
                    ]);
                }
            }
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusTab(): void
    {
        $this->resetPage();
    }

    public function updatingCoolFilter(): void
    {
        $this->resetPage();
    }

    public function openResolveModal(int $followUpId): void
    {
        $followUp = MemberFollowUp::with('member')->findOrFail($followUpId);
        $this->selectedFollowUpId = $followUp->follow_up_id;
        $this->selectedMemberName = $followUp->member->name ?? 'Anggota';
        $this->action_taken = $followUp->action_taken ?: 'WhatsApp / Telepon';
        $this->notes = $followUp->notes ?: '';
        $this->status = 'RESOLVED';
        $this->showModal = true;
    }

    public function openEditModal(int $followUpId): void
    {
        $this->openResolveModal($followUpId);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedFollowUpId = null;
        $this->selectedMemberName = '';
        $this->action_taken = 'WhatsApp / Telepon';
        $this->notes = '';
        $this->resetValidation();
    }

    public function saveFollowUp(): void
    {
        $this->validate([
            'action_taken' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ], [
            'action_taken.required' => 'Jenis tindakan pastoral wajib dipilih.',
        ]);

        if (! $this->selectedFollowUpId) {
            return;
        }

        $followUp = MemberFollowUp::findOrFail($this->selectedFollowUpId);
        $followUp->update([
            'status' => 'RESOLVED',
            'action_taken' => $this->action_taken,
            'notes' => $this->notes ?: null,
            'handled_by' => Auth::id(),
            'handled_at' => now(),
            'modified_by' => Auth::id(),
            'date_modified' => now(),
        ]);

        session()->flash('success', "Tindak lanjut pastoral untuk '{$followUp->member->name}' berhasil ditandai SUDAH DITANGANI.");
        $this->dispatch('notify', message: "Tindak lanjut pastoral untuk '{$followUp->member->name}' berhasil ditandai SUDAH DITANGANI.", type: 'success');
        $this->closeModal();
    }

    public function reopenFollowUp(int $followUpId): void
    {
        $followUp = MemberFollowUp::with('member')->findOrFail($followUpId);
        $followUp->update([
            'status' => 'PENDING',
            'modified_by' => Auth::id(),
            'date_modified' => now(),
        ]);

        session()->flash('success', "Status follow-up untuk '{$followUp->member->name}' berhasil dikembalikan ke BELUM DITANGANI.");
        $this->dispatch('notify', message: "Status follow-up untuk '{$followUp->member->name}' berhasil dikembalikan ke BELUM DITANGANI.", type: 'success');
    }

    public function deleteFollowUp(int $followUpId): void
    {
        $followUp = MemberFollowUp::findOrFail($followUpId);
        $followUp->update([
            'is_deleted' => true,
            'deleted_by' => Auth::id(),
            'date_deleted' => now(),
        ]);

        session()->flash('success', 'Catatan follow-up berhasil dihapus.');
        $this->dispatch('notify', message: 'Catatan follow-up berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->member_id;

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->member_id))
            ->orderBy('name')
            ->get();

        $shepherdCoolIds = $cools->pluck('cool_id')->toArray();

        // Base Query
        $baseQuery = MemberFollowUp::where('member_follow_ups.is_deleted', false)
            ->whereHas('member', fn ($m) => $m->where('is_deleted', false))
            ->when($isShepherd, function ($q) use ($shepherdCoolIds) {
                $q->where(function ($sub) use ($shepherdCoolIds) {
                    $sub->whereIn('cool_id', $shepherdCoolIds)
                        ->orWhereHas('member.coolMembers', fn ($cm) => $cm->whereIn('cool_id', $shepherdCoolIds)->where('is_deleted', false));
                });
            });

        // Statistics counts
        $pendingCount = (clone $baseQuery)->where('status', 'PENDING')->count();
        $resolvedCount = (clone $baseQuery)->where('status', 'RESOLVED')->count();
        $totalCount = (clone $baseQuery)->count();

        // Filtered Query
        $query = (clone $baseQuery)
            ->with([
                'member' => function ($m) {
                    $m->with([
                        'attendances' => function ($q) {
                            $q->where('attendances.is_deleted', false)
                                ->whereHas('activity', fn ($a) => $a->where('activities.is_deleted', false))
                                ->with(['activity', 'status']);
                        },
                    ]);
                },
                'cool.shepherd',
                'handler',
            ])
            ->when($this->statusTab !== 'ALL', function ($q) {
                $q->where('status', $this->statusTab);
            })
            ->when($this->coolFilter, function ($q) {
                $q->where('cool_id', $this->coolFilter);
            })
            ->when($this->search, function ($q) {
                $search = trim($this->search);
                $q->whereHas('member', function ($m) use ($search) {
                    $m->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('member_code', 'like', "%{$search}%");
                });
            });

        $followUps = $query->orderByRaw("CASE WHEN status = 'PENDING' THEN 1 ELSE 2 END")
            ->orderByDesc('follow_up_id')
            ->paginate($this->perPage);

        return view('livewire.follow-ups.follow-up-index', compact(
            'followUps',
            'cools',
            'pendingCount',
            'resolvedCount',
            'totalCount'
        ));
    }
}
