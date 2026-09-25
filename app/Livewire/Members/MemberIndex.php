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
#[Title('Data Jemaat & COOL')]
class MemberIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $coolFilter = '';

    public string $statusFilter = '';

    public string $coolStatusFilter = '';

    public string $komFilter = '';

    public string $maritalFilter = '';

    public string $genderFilter = '';

    public bool $showModal = false;

    public bool $showDetailModal = false;

    public ?int $editingMemberId = null;

    public ?int $viewingMemberId = null;

    // Form fields
    public string $member_code = '';

    public string $name = '';

    public string $gender = 'Laki-laki';

    public string $birthplace = '';

    public string $birthdate = '';

    public string $address = '';

    public string $social_media = '';

    public string $kom_status = 'Belum KOM';

    public string $marital_status = 'Belum Menikah';

    public bool $is_in_cool = false;

    public ?int $cool_id = null;

    public string $phone = '';

    public string $email = '';

    public string $join_date = '';

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

    public function updatingCoolStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingKomFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMaritalFilter(): void
    {
        $this->resetPage();
    }

    public function updatingGenderFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->join_date = date('Y-m-d');
    }

    public function updatedIsInCool(bool $value): void
    {
        if (! $value) {
            $this->cool_id = null;
        }
    }

    public function updatedCoolId($value): void
    {
        if (! empty($value)) {
            $this->is_in_cool = true;
        }
    }

    public function updatedStatus(string $value): void
    {
        if ($value === 'INACTIVE' || $value === 'MOVED') {
            $this->is_active = false;
        } else {
            $this->is_active = true;
        }
    }

    public function updatedIsActive(bool $value): void
    {
        if (! $value && ($this->status === 'ACTIVE' || $this->status === 'NEW')) {
            $this->status = 'INACTIVE';
        } elseif ($value && $this->status === 'INACTIVE') {
            $this->status = 'ACTIVE';
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->member_code = 'JMT-'.date('Y').'-'.str_pad((string) (Member::count() + 1), 4, '0', STR_PAD_LEFT);
        $this->join_date = date('Y-m-d');

        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id) {
            $firstCool = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->first();
            if ($firstCool) {
                $this->cool_id = $firstCool->cool_id;
                $this->is_in_cool = true;
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
                abort(403, 'Anda hanya dapat mengakses data jemaat kelompok COOL Anda sendiri.');
            }
        }

        $this->editingMemberId = $member->member_id;
        $this->member_code = $member->member_code;
        $this->name = $member->name;
        $this->gender = $member->gender ?? 'Laki-laki';
        $this->birthplace = $member->birthplace ?? '';
        $this->birthdate = $member->birthdate ? $member->birthdate->format('Y-m-d') : '';
        $this->address = $member->address ?? '';
        $this->social_media = $member->social_media ?? '';
        $this->kom_status = $member->kom_status ?? 'Belum KOM';
        $this->marital_status = $member->marital_status ?? 'Belum Menikah';
        $this->phone = $member->phone ?? '';
        $this->email = $member->email ?? '';
        $this->join_date = $member->join_date ? $member->join_date->format('Y-m-d') : date('Y-m-d');
        $this->status = $member->status ?? 'ACTIVE';
        $this->is_active = (bool) $member->is_active;

        $activeCoolMember = $member->coolMembers->first();
        $this->cool_id = $activeCoolMember ? $activeCoolMember->cool_id : null;
        $this->is_in_cool = (bool) ($member->is_in_cool || $this->cool_id !== null);

        $this->showModal = true;
    }

    public function openDetailModal(int $memberId): void
    {
        $this->viewingMemberId = $memberId;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->viewingMemberId = null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingMemberId',
            'member_code',
            'name',
            'birthplace',
            'birthdate',
            'address',
            'social_media',
            'phone',
            'email',
            'cool_id',
        ]);
        $this->gender = 'Laki-laki';
        $this->kom_status = 'Belum KOM';
        $this->marital_status = 'Belum Menikah';
        $this->is_in_cool = false;
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
            'gender' => 'required|string|max:20',
            'birthplace' => 'nullable|string|max:100',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'social_media' => 'nullable|string|max:255',
            'kom_status' => 'required|string|max:50',
            'marital_status' => 'required|string|max:50',
            'is_in_cool' => 'boolean',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'join_date' => 'required|date',
            'cool_id' => 'nullable|exists:cools,cool_id',
            'status' => 'required|string|max:20',
            'is_active' => 'boolean',
        ];

        $this->validate($rules, [
            'member_code.required' => 'Nomor ID Jemaat wajib diisi.',
            'member_code.unique' => 'Nomor ID Jemaat sudah terdaftar.',
            'name.required' => 'Nama lengkap jemaat wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'kom_status.required' => 'Status KOM wajib dipilih.',
            'marital_status.required' => 'Status pernikahan wajib dipilih.',
            'join_date.required' => 'Tanggal bergabung wajib diisi.',
        ]);

        if ($this->status === 'INACTIVE' || $this->status === 'MOVED') {
            $this->is_active = false;
        } elseif (! $this->is_active && $this->status === 'ACTIVE') {
            $this->status = 'INACTIVE';
        }

        $effectiveIsInCool = $this->is_in_cool && ! empty($this->cool_id);

        $data = [
            'member_code' => $this->member_code,
            'name' => $this->name,
            'gender' => $this->gender,
            'birthplace' => $this->birthplace ?: null,
            'birthdate' => $this->birthdate ?: null,
            'address' => $this->address ?: null,
            'social_media' => $this->social_media ?: null,
            'kom_status' => $this->kom_status,
            'marital_status' => $this->marital_status,
            'is_in_cool' => $effectiveIsInCool,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'join_date' => $this->join_date,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ];

        $userId = Auth::id() ?? 1;
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($isShepherd && $this->cool_id) {
            $shepherdCoolIds = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->pluck('cool_id')->toArray();
            if (! in_array($this->cool_id, $shepherdCoolIds)) {
                $this->addError('cool_id', 'Anda hanya dapat menugaskan jemaat ke kelompok COOL Anda sendiri.');

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
                    abort(403, 'Anda hanya dapat mengedit jemaat kelompok COOL Anda sendiri.');
                }
            }

            $member->update(array_merge($data, [
                'modified_by' => $userId,
                'date_modified' => now(),
            ]));

            // Update COOL assignment if changed or update status on existing assignment
            $existingActive = CoolMember::where('member_id', $member->member_id)
                ->where('is_deleted', false)
                ->first();

            if ($effectiveIsInCool && $this->cool_id) {
                if (! $existingActive || $existingActive->cool_id !== (int) $this->cool_id) {
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
                        'status' => $this->status,
                        'is_deleted' => false,
                        'created_by' => $userId,
                        'date_created' => now(),
                    ]);
                } else {
                    $existingActive->update([
                        'status' => $this->status,
                        'modified_by' => $userId,
                        'date_modified' => now(),
                    ]);
                }
            } elseif ($existingActive) {
                $existingActive->update([
                    'end_date' => now()->toDateString(),
                    'status' => 'REMOVED',
                    'is_deleted' => true,
                    'modified_by' => $userId,
                    'date_modified' => now(),
                ]);
            }

            session()->flash('success', "Data jemaat '{$member->name}' berhasil diperbarui.");
        } else {
            $member = Member::create(array_merge($data, [
                'is_deleted' => false,
                'created_by' => $userId,
                'date_created' => now(),
            ]));

            if ($effectiveIsInCool && $this->cool_id) {
                CoolMember::create([
                    'cool_id' => $this->cool_id,
                    'member_id' => $member->member_id,
                    'start_date' => $this->join_date,
                    'status' => $this->status,
                    'is_deleted' => false,
                    'created_by' => $userId,
                    'date_created' => now(),
                ]);
            }

            session()->flash('success', "Data jemaat baru '{$member->name}' berhasil ditambahkan.");
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

        session()->flash('success', "Data jemaat '{$member->name}' berhasil dihapus (soft delete).");
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

        $query = Member::where('is_deleted', false)
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
            ->when($this->coolStatusFilter, function ($q) {
                if ($this->coolStatusFilter === 'in_cool') {
                    $q->where('is_in_cool', true);
                } elseif ($this->coolStatusFilter === 'not_in_cool') {
                    $q->where('is_in_cool', false);
                }
            })
            ->when($this->komFilter, function ($q) {
                $q->where('kom_status', $this->komFilter);
            })
            ->when($this->maritalFilter, function ($q) {
                $q->where('marital_status', $this->maritalFilter);
            })
            ->when($this->genderFilter, function ($q) {
                $q->where('gender', $this->genderFilter);
            })
            ->when($this->statusFilter, function ($q) {
                if ($this->statusFilter === 'ACTIVE' || $this->statusFilter === 'active') {
                    $q->where(function ($sub) {
                        $sub->where('status', 'ACTIVE')->orWhere(function ($s) {
                            $s->where('is_active', true)->whereNotIn('status', ['INACTIVE', 'MOVED']);
                        });
                    });
                } elseif ($this->statusFilter === 'INACTIVE' || $this->statusFilter === 'inactive') {
                    $q->where(function ($sub) {
                        $sub->where('is_active', false)->orWhere('status', 'INACTIVE');
                    });
                } else {
                    $q->where('status', $this->statusFilter);
                }
            })
            ->when($this->search, function ($q) {
                $term = "%{$this->search}%";
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', $term)
                        ->orWhere('member_code', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('address', 'like', $term)
                        ->orWhere('birthplace', 'like', $term)
                        ->orWhere('social_media', 'like', $term);
                });
            })
            ->with(['cools' => fn ($q) => $q->wherePivot('is_deleted', false)->where('cools.is_deleted', false)])
            ->orderBy('name');

        $members = $query->paginate(12);

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        // Calculate summary counts for fast overview
        $statsBase = Member::where('is_deleted', false)
            ->when($isShepherd, function ($q) use ($shepherdCoolIds) {
                $q->whereHas('coolMembers', function ($sub) use ($shepherdCoolIds) {
                    $sub->whereIn('cool_id', $shepherdCoolIds)->where('is_deleted', false);
                });
            });

        $stats = [
            'total' => (clone $statsBase)->count(),
            'in_cool' => (clone $statsBase)->where('is_in_cool', true)->count(),
            'not_in_cool' => (clone $statsBase)->where('is_in_cool', false)->count(),
            'ikut_kom' => (clone $statsBase)->whereIn('kom_status', ['KOM 100', 'KOM 200', 'KOM 300', 'KOM 400'])->count(),
        ];

        $detailMember = null;
        if ($this->showDetailModal && $this->viewingMemberId) {
            $detailMember = Member::with(['cools' => fn ($q) => $q->wherePivot('is_deleted', false)])->find($this->viewingMemberId);
        }

        return view('livewire.members.member-index', compact('members', 'cools', 'isShepherd', 'stats', 'detailMember'));
    }
}
