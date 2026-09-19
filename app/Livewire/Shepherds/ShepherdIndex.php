<?php

namespace App\Livewire\Shepherds;

use App\Models\Shepherd;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen Gembala COOL')]
class ShepherdIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingShepherdId = null;

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public bool $is_active = true;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $shepherdId): void
    {
        $shepherd = Shepherd::findOrFail($shepherdId);
        $this->editingShepherdId = $shepherd->shepherd_id;
        $this->name = $shepherd->name;
        $this->phone = $shepherd->phone ?? '';
        $this->email = $shepherd->email ?? '';
        $this->is_active = (bool) $shepherd->is_active;

        $this->showModal = true;
    }

    public function resetForm(): void
    {
        $this->reset(['editingShepherdId', 'name', 'phone', 'email', 'is_active']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama Gembala wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingShepherdId) {
            $shepherd = Shepherd::findOrFail($this->editingShepherdId);
            $shepherd->update(array_merge($data, [
                'modified_by' => Auth::id() ?? 1,
                'date_modified' => now(),
            ]));
            session()->flash('success', "Data Gembala '{$shepherd->name}' berhasil diperbarui.");
        } else {
            $shepherd = Shepherd::create(array_merge($data, [
                'is_deleted' => false,
                'created_by' => Auth::id() ?? 1,
                'date_created' => now(),
            ]));
            session()->flash('success', "Gembala baru '{$shepherd->name}' berhasil ditambahkan.");
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteShepherd(int $shepherdId): void
    {
        $shepherd = Shepherd::findOrFail($shepherdId);
        $shepherd->update([
            'is_deleted' => true,
            'deleted_by' => Auth::id() ?? 1,
            'date_deleted' => now(),
        ]);

        session()->flash('success', "Data Gembala '{$shepherd->name}' berhasil dihapus (soft delete).");
    }

    public function render()
    {
        $shepherds = Shepherd::where('is_deleted', false)
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->with(['cools' => fn ($q) => $q->where('is_deleted', false)])
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.shepherds.shepherd-index', compact('shepherds'));
    }
}
