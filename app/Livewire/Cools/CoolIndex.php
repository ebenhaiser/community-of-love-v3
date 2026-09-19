<?php

namespace App\Livewire\Cools;

use App\Models\Cool;
use App\Models\Shepherd;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen Kelompok COOL')]
class CoolIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $shepherdFilter = '';

    public bool $showModal = false;

    public ?int $editingCoolId = null;

    public string $cool_code = '';

    public string $name = '';

    public ?int $shepherd_id = null;

    public string $description = '';

    public bool $is_active = true;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingShepherdFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Gembala COOL tidak memiliki izin menambahkan kelompok COOL baru.');
        }

        $this->resetForm();
        $this->cool_code = 'COOL-SLM-'.str_pad((string) (Cool::count() + 1), 3, '0', STR_PAD_LEFT);
        $this->showModal = true;
    }

    public function openEditModal(int $coolId): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Hanya Master Administrator yang dapat mengedit data kelompok COOL.');
        }

        $cool = Cool::findOrFail($coolId);
        $this->editingCoolId = $cool->cool_id;
        $this->cool_code = $cool->cool_code;
        $this->name = $cool->name;
        $this->shepherd_id = $cool->shepherd_id;
        $this->description = $cool->description ?? '';
        $this->is_active = (bool) $cool->is_active;

        $this->showModal = true;
    }

    public function resetForm(): void
    {
        $this->reset(['editingCoolId', 'cool_code', 'name', 'shepherd_id', 'description', 'is_active']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Gembala COOL tidak dapat menambah atau mengubah kelompok COOL.');
        }

        $rules = [
            'cool_code' => 'required|string|max:50|unique:cools,cool_code,'.($this->editingCoolId ?? 'NULL').',cool_id',
            'name' => 'required|string|max:255',
            'shepherd_id' => 'required|exists:shepherds,shepherd_id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        $this->validate($rules, [
            'cool_code.required' => 'Kode COOL wajib diisi.',
            'cool_code.unique' => 'Kode COOL sudah terdaftar.',
            'name.required' => 'Nama kelompok COOL wajib diisi.',
            'shepherd_id.required' => 'Gembala COOL wajib dipilih.',
        ]);

        $data = [
            'cool_code' => $this->cool_code,
            'name' => $this->name,
            'shepherd_id' => $this->shepherd_id,
            'description' => $this->description ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingCoolId) {
            $cool = Cool::findOrFail($this->editingCoolId);
            $cool->update(array_merge($data, [
                'modified_by' => Auth::id() ?? 1,
                'date_modified' => now(),
            ]));
            session()->flash('success', "Kelompok COOL '{$cool->name}' berhasil diperbarui.");
        } else {
            $cool = Cool::create(array_merge($data, [
                'is_deleted' => false,
                'created_by' => Auth::id() ?? 1,
                'date_created' => now(),
            ]));
            session()->flash('success', "Kelompok COOL '{$cool->name}' berhasil ditambahkan.");
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteCool(int $coolId): void
    {
        $user = Auth::user();
        if ($user && $user->role && $user->role->name === 'SHEPHERD') {
            abort(403, 'Hanya Master Administrator yang dapat menghapus kelompok COOL.');
        }

        $cool = Cool::findOrFail($coolId);
        $cool->update([
            'is_deleted' => true,
            'deleted_by' => Auth::id() ?? 1,
            'date_deleted' => now(),
        ]);

        session()->flash('success', "Kelompok COOL '{$cool->name}' berhasil dihapus (soft delete).");
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->when($this->shepherdFilter, fn ($q) => $q->where('shepherd_id', $this->shepherdFilter))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('cool_code', 'like', "%{$this->search}%");
                });
            })
            ->with(['shepherd', 'members'])
            ->orderBy('name')
            ->paginate(10);

        $shepherds = Shepherd::where('is_deleted', false)->orderBy('name')->get();

        return view('livewire.cools.cool-index', compact('cools', 'shepherds', 'isShepherd'));
    }
}
