<?php

namespace App\Livewire\Members;

use App\Models\Cool;
use App\Models\CoolMember;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen Anggota COOL')]
class MemberIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $coolFilter = '';

    public string $statusFilter = '';

    public bool $showModal = false;

    public ?int $editingMemberId = null;

    public string $member_code = '';

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $join_date = '';

    public ?int $cool_id = null;

    public string $status = 'ACTIVE';

    public bool $is_active = true;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCoolFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->join_date = date('Y-m-d');
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->member_code = 'MBR-'.date('Y').'-'.str_pad((string) (Member::count() + 1), 4, '0', STR_PAD_LEFT);
        $this->join_date = date('Y-m-d');

        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id) {
            $firstCool = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->first();
            if ($firstCool) {
                $this->cool_id = $firstCool->cool_id;
            }
        }

        $this->showModal = true;
    }

    public function openEditModal(int $memberId): void
    {
        $member = Member::with(['coolMembers' => fn ($q) => $q->where('is_deleted', false)])->findOrFail($memberId);

        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;
        if ($isShepherd) {
            $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->pluck('cool_id')->toArray();
            $belongsToShepherd = $member->coolMembers()->whereIn('cool_id', $shepherdCoolIds)->where('is_deleted', false)->exists();
            if (! $belongsToShepherd) {
                abort(403, 'Anda hanya dapat mengakses data anggota kelompok COOL Anda sendiri.');
            }
        }

        $this->editingMemberId = $member->member_id;
        $this->member_code = $member->member_code;
        $this->name = $member->name;
        $this->phone = $member->phone ?? '';
        $this->email = $member->email ?? '';
        $this->join_date = $member->join_date ? $member->join_date->format('Y-m-d') : date('Y-m-d');
        $this->status = $member->status ?? 'ACTIVE';
        $this->is_active = (bool) $member->is_active;

        $activeCoolMember = $member->coolMembers->first();
        $this->cool_id = $activeCoolMember ? $activeCoolMember->cool_id : null;

        $this->showModal = true;
    }

    public function resetForm(): void
    {
        $this->reset(['editingMemberId', 'member_code', 'name', 'phone', 'email', 'cool_id']);
        $this->status = 'ACTIVE';
        $this->is_active = true;
        $this->join_date = date('Y-m-d');
        $this->resetValidation();
    }

    public function save(): void
    {
        $rules = [
            'member_code' => 'required|string|max:50|unique:members,member_code,'.($this->editingMemberId ?? 'NULL').',member_id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'join_date' => 'required|date',
            'cool_id' => 'nullable|exists:cools,cool_id',
            'status' => 'required|string|max:20',
            'is_active' => 'boolean',
        ];

        $this->validate($rules, [
            'member_code.required' => 'Nomor ID Anggota wajib diisi.',
            'member_code.unique' => 'Nomor ID Anggota sudah terdaftar.',
            'name.required' => 'Nama lengkap anggota wajib diisi.',
            'join_date.required' => 'Tanggal bergabung wajib diisi.',
        ]);

        $data = [
            'member_code' => $this->member_code,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'join_date' => $this->join_date,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ];

        $userId = Auth::id() ?? 1;
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($isShepherd) {
            $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->pluck('cool_id')->toArray();
            if (! $this->cool_id || ! in_array($this->cool_id, $shepherdCoolIds)) {
                $this->addError('cool_id', 'Anda hanya dapat menugaskan anggota ke kelompok COOL Anda sendiri.');

                return;
            }
        }

        if ($this->editingMemberId) {
            $member = Member::findOrFail($this->editingMemberId);

            if ($isShepherd) {
                $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->pluck('cool_id')->toArray();
                $belongsToShepherd = CoolMember::where('member_id', $member->member_id)
                    ->whereIn('cool_id', $shepherdCoolIds)
                    ->where('is_deleted', false)
                    ->exists();

                if (! $belongsToShepherd) {
                    abort(403, 'Anda hanya dapat mengedit anggota kelompok COOL Anda sendiri.');
                }
            }

            $member->update(array_merge($data, [
                'modified_by' => $userId,
                'date_modified' => now(),
            ]));

            // Update COOL assignment if changed
            if ($this->cool_id) {
                $existingActive = CoolMember::where('member_id', $member->member_id)
                    ->where('is_deleted', false)
                    ->first();

                if (! $existingActive || $existingActive->cool_id !== $this->cool_id) {
                    if ($existingActive) {
                        $existingActive->update([
                            'end_date' => now()->toDateString(),
                            'status' => 'TRANSFERRED',
                            'is_deleted' => true,
                            'modified_by' => $userId,
                            'date_modified' => now(),
                        ]);
                    }

                    CoolMember::create([
                        'cool_id' => $this->cool_id,
                        'member_id' => $member->member_id,
                        'start_date' => now()->toDateString(),
                        'status' => 'ACTIVE',
                        'is_deleted' => false,
                        'created_by' => $userId,
                        'date_created' => now(),
                    ]);
                }
            }

            session()->flash('success', "Data anggota '{$member->name}' berhasil diperbarui.");
        } else {
            $member = Member::create(array_merge($data, [
                'is_deleted' => false,
                'created_by' => $userId,
                'date_created' => now(),
            ]));

            if ($this->cool_id) {
                CoolMember::create([
                    'cool_id' => $this->cool_id,
                    'member_id' => $member->member_id,
                    'start_date' => $this->join_date,
                    'status' => 'ACTIVE',
                    'is_deleted' => false,
                    'created_by' => $userId,
                    'date_created' => now(),
                ]);
            }

            session()->flash('success', "Anggota baru '{$member->name}' berhasil ditambahkan.");
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteMember(int $memberId): void
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;
        if ($isShepherd) {
            $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->pluck('cool_id')->toArray();
            $belongs = CoolMember::where('member_id', $memberId)->whereIn('cool_id', $shepherdCoolIds)->where('is_deleted', false)->exists();
            if (! $belongs) {
                abort(403, 'Akses ditolak.');
            }
        }

        $member = Member::findOrFail($memberId);
        $userId = Auth::id() ?? 1;

        $member->update([
            'is_deleted' => true,
            'deleted_by' => $userId,
            'date_deleted' => now(),
        ]);

        CoolMember::where('member_id', $memberId)->update([
            'is_deleted' => true,
            'deleted_by' => $userId,
            'date_deleted' => now(),
        ]);

        session()->flash('success', "Data anggota '{$member->name}' berhasil dihapus (soft delete).");
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

        $members = Member::where('is_deleted', false)
            ->when($isShepherd, function ($q) use ($shepherdCoolIds) {
                $q->whereHas('coolMembers', function ($sub) use ($shepherdCoolIds) {
                    $sub->whereIn('cool_id', $shepherdCoolIds)->where('is_deleted', false);
                });
            })
            ->when($this->coolFilter, function ($q) {
                $q->whereHas('coolMembers', function ($sub) {
                    $sub->where('cool_id', $this->coolFilter)->where('is_deleted', false);
                });
            })
            ->when($this->statusFilter, function ($q) {
                if ($this->statusFilter === 'active') {
                    $q->where('is_active', true);
                } elseif ($this->statusFilter === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('member_code', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->with(['cools' => fn ($q) => $q->wherePivot('is_deleted', false)->where('cools.is_deleted', false)])
            ->orderBy('name')
            ->paginate(12);

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        return view('livewire.members.member-index', compact('members', 'cools', 'isShepherd'));
    }
}
