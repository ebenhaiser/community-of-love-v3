<?php

namespace App\Livewire\Materials;

use App\Models\Activity;
use App\Models\ActivityMaterial;
use App\Models\Cool;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Materi & Dokumen Pertemuan COOL')]
class MaterialManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Url]
    public ?int $activity_id = null;

    public string $coolFilter = '';

    public string $search = '';

    public bool $showModal = false;

    public ?int $modal_activity_id = null;

    public string $material_type = 'DOCUMENT';

    public string $file_name = '';

    public string $external_url = '';

    public $file_upload;

    public string $description = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCoolFilter(): void
    {
        $this->resetPage();
    }

    public function updatingActivityId(): void
    {
        $this->resetPage();
    }

    public function openUploadModal(): void
    {
        $this->reset(['file_name', 'external_url', 'file_upload', 'description']);
        $this->material_type = 'DOCUMENT';
        $this->modal_activity_id = $this->activity_id;

        if (! $this->modal_activity_id) {
            $user = Auth::user();
            $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;
            $recent = Activity::where('is_deleted', false)
                ->when($isShepherd, fn ($q) => $q->whereHas('cool', fn ($c) => $c->where('shepherd_id', $user->shepherd_id)))
                ->orderByDesc('activity_date')
                ->first();
            if ($recent) {
                $this->modal_activity_id = $recent->activity_id;
            }
        }

        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'modal_activity_id' => 'required|exists:activities,activity_id',
            'material_type' => 'required|in:DOCUMENT,LINK,IMAGE,VIDEO',
            'file_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        if ($this->material_type === 'LINK') {
            $rules['external_url'] = 'required|url|max:2048';
        } else {
            $rules['file_upload'] = 'required|file|max:10240'; // max 10MB
        }

        $this->validate($rules, [
            'modal_activity_id.required' => 'Kegiatan COOL wajib dipilih.',
            'file_name.required' => 'Judul / nama materi wajib diisi.',
            'external_url.required' => 'URL link materi wajib diisi.',
            'file_upload.required' => 'File materi wajib diunggah.',
        ]);

        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($isShepherd) {
            $act = Activity::with('cool')->find($this->modal_activity_id);
            if (! $act || ! $act->cool || $act->cool->shepherd_id !== $user->shepherd_id) {
                $this->addError('modal_activity_id', 'Anda hanya dapat mengunggah materi untuk kegiatan kelompok COOL Anda sendiri.');

                return;
            }
        }

        $filePath = null;
        $fileSize = null;
        $mimeType = null;

        if ($this->material_type !== 'LINK' && $this->file_upload) {
            $filePath = $this->file_upload->store('materials', 'public');
            $fileSize = $this->file_upload->getSize();
            $mimeType = $this->file_upload->getMimeType();
        }

        ActivityMaterial::create([
            'activity_id' => $this->modal_activity_id,
            'material_type' => $this->material_type,
            'file_name' => $this->file_name,
            'file_path' => $filePath,
            'external_url' => $this->material_type === 'LINK' ? $this->external_url : null,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'description' => $this->description ?: null,
            'is_deleted' => false,
            'uploaded_by' => Auth::id() ?? 1,
            'date_uploaded' => now(),
        ]);

        session()->flash('success', "Materi '{$this->file_name}' berhasil ditambahkan.");
        $this->showModal = false;
        $this->reset(['file_name', 'external_url', 'file_upload', 'description']);
    }

    public function deleteMaterial(int $materialId): void
    {
        $material = ActivityMaterial::with('activity.cool')->findOrFail($materialId);
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        if ($isShepherd && $material->activity && $material->activity->cool && $material->activity->cool->shepherd_id !== $user->shepherd_id) {
            abort(403, 'Akses ditolak.');
        }

        $material->update([
            'is_deleted' => true,
            'deleted_by' => Auth::id() ?? 1,
            'date_deleted' => now(),
        ]);

        session()->flash('success', "Materi '{$material->file_name}' berhasil dihapus.");
    }

    public function render()
    {
        $user = Auth::user();
        $isShepherd = $user && $user->role && $user->role->name === 'SHEPHERD' && $user->shepherd_id;

        $cools = Cool::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->where('shepherd_id', $user->shepherd_id))
            ->orderBy('name')
            ->get();

        $shepherdCoolIds = $cools->pluck('cool_id')->toArray();

        $activities = Activity::where('is_deleted', false)
            ->when($isShepherd, fn ($q) => $q->whereIn('cool_id', $shepherdCoolIds))
            ->when($this->coolFilter, fn ($q) => $q->where('cool_id', $this->coolFilter))
            ->with('cool')
            ->orderByDesc('activity_date')
            ->get();

        $materials = ActivityMaterial::where('is_deleted', false)
            ->when($isShepherd, function ($q) use ($shepherdCoolIds) {
                $q->whereHas('activity', fn ($a) => $a->whereIn('cool_id', $shepherdCoolIds));
            })
            ->when($this->activity_id, fn ($q) => $q->where('activity_id', $this->activity_id))
            ->when($this->coolFilter, function ($q) {
                $q->whereHas('activity', fn ($a) => $a->where('cool_id', $this->coolFilter));
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('file_name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->with(['activity.cool', 'uploader'])
            ->orderByDesc('date_uploaded')
            ->paginate(10);

        return view('livewire.materials.material-manager', compact('cools', 'activities', 'materials', 'isShepherd'));
    }
}
