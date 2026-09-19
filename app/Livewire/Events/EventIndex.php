<?php

namespace App\Livewire\Events;

use App\Models\ChurchEvent;
use App\Models\Cool;
use App\Models\EventCool;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Event Gereja Lintas COOL')]
class EventIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public bool $showModal = false;

    public ?int $editingEventId = null;

    public string $event_code = '';

    public string $name = '';

    public string $event_date = '';

    public string $start_time = '09:00';

    public string $end_time = '12:00';

    public string $location = '';

    public string $description = '';

    public string $status = 'SCHEDULED';

    public array $selectedCoolIds = [];

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->event_date = date('Y-m-d');
    }

    public function openCreateModal(): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Hanya Master Administrator yang dapat membuat event gereja baru.');
        }

        $this->resetForm();
        $this->event_code = 'EVT-'.date('Y').'-'.str_pad((string) (ChurchEvent::count() + 1), 3, '0', STR_PAD_LEFT);
        $this->event_date = date('Y-m-d');
        $this->start_time = '09:00';
        $this->end_time = '12:00';
        $this->status = 'SCHEDULED';
        $this->showModal = true;
    }

    public function openEditModal(int $eventId): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Hanya Master Administrator yang dapat mengedit event gereja.');
        }

        $event = ChurchEvent::with(['cools' => fn ($q) => $q->wherePivot('is_deleted', false)])->findOrFail($eventId);
        $this->editingEventId = $event->event_id;
        $this->event_code = $event->event_code;
        $this->name = $event->name;
        $this->event_date = $event->event_date ? $event->event_date->format('Y-m-d') : date('Y-m-d');
        $this->start_time = $event->start_time ? substr((string) $event->start_time, 0, 5) : '09:00';
        $this->end_time = $event->end_time ? substr((string) $event->end_time, 0, 5) : '12:00';
        $this->location = $event->location ?? '';
        $this->description = $event->description ?? '';
        $this->status = $event->status ?? 'SCHEDULED';
        $this->selectedCoolIds = $event->cools->pluck('cool_id')->map(fn ($id) => (string) $id)->toArray();

        $this->showModal = true;
    }

    public function resetForm(): void
    {
        $this->reset(['editingEventId', 'event_code', 'name', 'location', 'description', 'selectedCoolIds']);
        $this->event_date = date('Y-m-d');
        $this->start_time = '09:00';
        $this->end_time = '12:00';
        $this->status = 'SCHEDULED';
        $this->resetValidation();
    }

    public function save(): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Akses ditolak.');
        }

        $rules = [
            'event_code' => 'required|string|max:50|unique:church_events,event_code,'.($this->editingEventId ?? 'NULL').',event_id',
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
        ];

        $this->validate($rules, [
            'event_code.required' => 'Kode event wajib diisi.',
            'event_code.unique' => 'Kode event sudah digunakan.',
            'name.required' => 'Nama event wajib diisi.',
            'event_date.required' => 'Tanggal pelaksanaan wajib ditentukan.',
        ]);

        $userId = Auth::id() ?? 1;

        $data = [
            'event_code' => $this->event_code,
            'name' => $this->name,
            'event_date' => $this->event_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time ?: null,
            'location' => $this->location ?: null,
            'description' => $this->description ?: null,
            'status' => $this->status,
        ];

        if ($this->editingEventId) {
            $event = ChurchEvent::findOrFail($this->editingEventId);
            $event->update(array_merge($data, [
                'modified_by' => $userId,
                'date_modified' => now(),
            ]));

            // Sync participating COOLs
            EventCool::where('event_id', $event->event_id)->update([
                'is_deleted' => true,
                'deleted_by' => $userId,
                'date_deleted' => now(),
            ]);

            foreach ($this->selectedCoolIds as $cId) {
                EventCool::updateOrCreate(
                    ['event_id' => $event->event_id, 'cool_id' => (int) $cId],
                    [
                        'is_deleted' => false,
                        'created_by' => $userId,
                        'date_created' => now(),
                        'deleted_by' => null,
                        'date_deleted' => null,
                    ]
                );
            }

            session()->flash('success', "Event '{$event->name}' berhasil diperbarui.");
        } else {
            $event = ChurchEvent::create(array_merge($data, [
                'is_deleted' => false,
                'created_by' => $userId,
                'date_created' => now(),
            ]));

            foreach ($this->selectedCoolIds as $cId) {
                EventCool::create([
                    'event_id' => $event->event_id,
                    'cool_id' => (int) $cId,
                    'is_deleted' => false,
                    'created_by' => $userId,
                    'date_created' => now(),
                ]);
            }

            session()->flash('success', "Event baru '{$event->name}' berhasil dibuat.");
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteEvent(int $eventId): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Hanya Master Administrator yang dapat menghapus event gereja.');
        }

        $event = ChurchEvent::findOrFail($eventId);
        $userId = Auth::id() ?? 1;

        $event->update([
            'is_deleted' => true,
            'deleted_by' => $userId,
            'date_deleted' => now(),
        ]);

        EventCool::where('event_id', $eventId)->update([
            'is_deleted' => true,
            'deleted_by' => $userId,
            'date_deleted' => now(),
        ]);

        session()->flash('success', "Event '{$event->name}' berhasil dihapus.");
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

        $events = ChurchEvent::where('is_deleted', false)
            ->when($isShepherd, function ($q) use ($shepherdCoolIds) {
                $q->where(function ($sub) use ($shepherdCoolIds) {
                    // Open to all COOLs (no specific cools attached) OR attached to this shepherd's COOL
                    $sub->whereDoesntHave('cools', fn ($c) => $c->where('event_cools.is_deleted', false))
                        ->orWhereHas('cools', function ($c) use ($shepherdCoolIds) {
                            $c->where('event_cools.is_deleted', false)
                                ->whereIn('cools.cool_id', $shepherdCoolIds);
                        });
                });
            })
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('event_code', 'like', "%{$this->search}%")
                        ->orWhere('location', 'like', "%{$this->search}%");
                });
            })
            ->with(['cools' => fn ($q) => $q->where('event_cools.is_deleted', false)])
            ->withCount([
                'cools as participating_cools_count' => fn ($q) => $q->where('event_cools.is_deleted', false),
            ])
            ->orderByDesc('event_date')
            ->paginate(10);

        $allCools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        return view('livewire.events.event-index', compact('events', 'allCools', 'isShepherd'));
    }
}
