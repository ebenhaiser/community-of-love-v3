<?php

namespace App\Livewire\Activities;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Cool;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kegiatan & Jadwal COOL')]
class ActivityIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $coolFilter = '';

    public string $typeFilter = '';

    public string $statusFilter = '';

    public bool $showModal = false;

    public ?int $editingActivityId = null;

    public ?int $cool_id = null;

    public ?int $activity_type_id = null;

    public string $name = '';

    public string $activity_date = '';

    public string $start_time = '19:00';

    public string $end_time = '21:00';

    public string $location = '';

    public string $description = '';

    public string $status = 'SCHEDULED';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCoolFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->activity_date = date('Y-m-d');
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->activity_date = date('Y-m-d');
        $this->start_time = '19:00';
        $this->end_time = '21:00';
        $this->status = 'SCHEDULED';

        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id) {
            $firstCool = Cool::where('shepherd_id', $user->shepherd_id)->where('is_deleted', false)->first();
            if ($firstCool) {
                $this->cool_id = $firstCool->cool_id;
            }
        }

        $firstType = ActivityType::where('is_deleted', false)->first();
        if ($firstType) {
            $this->activity_type_id = $firstType->activity_type_id;
        }

        $this->showModal = true;
    }

    public function openEditModal(int $activityId): void
    {
        $activity = Activity::with('cool')->findOrFail($activityId);

        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;
        if ($isShepherd && $activity->cool && $activity->cool->shepherd_id !== $user->shepherd_id) {
            abort(403, 'Anda hanya dapat mengakses kegiatan kelompok COOL Anda sendiri.');
        }

        $this->editingActivityId = $activity->activity_id;
        $this->cool_id = $activity->cool_id;
        $this->activity_type_id = $activity->activity_type_id;
        $this->name = $activity->name;
        $this->activity_date = $activity->activity_date ? $activity->activity_date->format('Y-m-d') : date('Y-m-d');
        $this->start_time = $activity->start_time ? substr((string) $activity->start_time, 0, 5) : '19:00';
        $this->end_time = $activity->end_time ? substr((string) $activity->end_time, 0, 5) : '21:00';
        $this->location = $activity->location ?? '';
        $this->description = $activity->description ?? '';
        $this->status = $activity->status ?? 'SCHEDULED';

        $this->showModal = true;
    }

    public function resetForm(): void
    {
        $this->reset(['editingActivityId', 'cool_id', 'activity_type_id', 'name', 'location', 'description']);
        $this->activity_date = date('Y-m-d');
        $this->start_time = '19:00';
        $this->end_time = '21:00';
        $this->status = 'SCHEDULED';
        $this->resetValidation();
    }

    public function save(): void
    {
        $rules = [
            'cool_id' => 'required|exists:cools,cool_id',
            'activity_type_id' => 'required|exists:activity_types,activity_type_id',
            'name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
        ];

        $this->validate($rules, [
            'cool_id.required' => 'Kelompok COOL wajib dipilih.',
            'activity_type_id.required' => 'Jenis kegiatan wajib dipilih.',
            'name.required' => 'Nama kegiatan wajib diisi.',
            'activity_date.required' => 'Tanggal kegiatan wajib ditentukan.',
            'start_time.required' => 'Waktu mulai wajib ditentukan.',
        ]);

        $userId = Auth::id() ?? 1;
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($isShepherd) {
            $cool = Cool::find($this->cool_id);
            if (! $cool || $cool->shepherd_id !== $user->shepherd_id) {
                $this->addError('cool_id', 'Anda hanya dapat menjadwalkan kegiatan untuk kelompok COOL Anda sendiri.');

                return;
            }
        }

        $data = [
            'cool_id' => $this->cool_id,
            'activity_type_id' => $this->activity_type_id,
            'name' => $this->name,
            'activity_date' => $this->activity_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time ?: null,
            'location' => $this->location ?: null,
            'description' => $this->description ?: null,
            'status' => $this->status,
        ];

        if ($this->editingActivityId) {
            $activity = Activity::with('cool')->findOrFail($this->editingActivityId);
            if ($isShepherd && $activity->cool && $activity->cool->shepherd_id !== $user->shepherd_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengubah kegiatan kelompok COOL lain.');
            }

            $activity->update(array_merge($data, [
                'modified_by' => $userId,
                'date_modified' => now(),
            ]));
            session()->flash('success', "Kegiatan '{$activity->name}' berhasil diperbarui.");
        } else {
            $activity = Activity::create(array_merge($data, [
                'is_deleted' => false,
                'created_by' => $userId,
                'date_created' => now(),
            ]));
            session()->flash('success', "Kegiatan baru '{$activity->name}' berhasil dijadwalkan.");
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteActivity(int $activityId): void
    {
        $activity = Activity::with('cool')->findOrFail($activityId);
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($isShepherd && $activity->cool && $activity->cool->shepherd_id !== $user->shepherd_id) {
            abort(403, 'Akses ditolak.');
        }

        $userId = Auth::id() ?? 1;

        $activity->update([
            'is_deleted' => true,
            'deleted_by' => $userId,
            'date_deleted' => now(),
        ]);

        session()->flash('success', "Kegiatan '{$activity->name}' berhasil dihapus (soft delete).");
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

        $activities = Activity::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->whereIn('cool_id', $shepherdCoolIds))
            ->when($this->coolFilter, fn ($q) => $q->where('cool_id', $this->coolFilter))
            ->when($this->typeFilter, fn ($q) => $q->where('activity_type_id', $this->typeFilter))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('location', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->with(['cool', 'activityType'])
            ->withCount([
                'attendances as total_attendances' => fn ($q) => $q->where('is_deleted', false),
                'attendances as present_count' => fn ($q) => $q->where('is_deleted', false)
                    ->whereHas('status', fn ($s) => $s->where('code', 'PRESENT')),
            ])
            ->orderByDesc('activity_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        $activityTypes = ActivityType::where('is_deleted', false)->get();

        return view('livewire.activities.activity-index', compact('activities', 'cools', 'activityTypes', 'isShepherd'));
    }
}
