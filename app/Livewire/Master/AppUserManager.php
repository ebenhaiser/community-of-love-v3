<?php

namespace App\Livewire\Master;

use App\Models\Role;
use App\Models\Shepherd;
use App\Models\User;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen Pengguna Aplikasi')]
class AppUserManager extends Component
{
    use WithPagination;

    public string $defaultPassword = '';

    public string $search = '';

    public string $roleFilter = '';

    public string $statusFilter = '';

    public int $perPage = 15;

    protected string $paginationTheme = 'bootstrap';

    // Modal state & form properties
    public bool $showModal = false;

    public ?int $editingUserId = null;

    public string $full_name = '';

    public string $username = '';

    public string $email = '';

    public string $phone = '';

    public ?int $role_id = null;

    public ?int $shepherd_id = null;

    public bool $is_active = true;

    public function mount(): void
    {
        $this->authorizeMaster();
        $this->defaultPassword = AppHelper::getSettings('default_user_password') ?? '';
    }

    /**
     * Ensure the authenticated user has the MASTER role.
     */
    protected function authorizeMaster(): void
    {
        $user = Auth::user();
        if (! $user || ! $user->role || $user->role->name !== 'MASTER') {
            abort(403, 'Akses ditolak. Hanya pengguna dengan peran MASTER yang dapat mengakses halaman ini.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->authorizeMaster();
        $this->resetForm();

        $defaultRole = Role::where('name', 'MEMBER')->where('is_deleted', false)->first()
            ?? Role::where('is_deleted', false)->first();
        if ($defaultRole) {
            $this->role_id = $defaultRole->role_id;
        }

        $this->showModal = true;
    }

    public function openEditModal(int $userId): void
    {
        $this->authorizeMaster();
        $this->resetForm();

        $user = User::findOrFail($userId);
        $this->editingUserId = $user->user_id;
        $this->full_name = $user->full_name;
        $this->username = $user->username;
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->role_id = $user->role_id;
        $this->shepherd_id = $user->shepherd_id;
        $this->is_active = (bool) $user->is_active;

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function resetForm(): void
    {
        $this->editingUserId = null;
        $this->full_name = '';
        $this->username = '';
        $this->email = '';
        $this->phone = '';
        $this->role_id = null;
        $this->shepherd_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->authorizeMaster();

        $rules = [
            'full_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9._-]+$/', 'max:100', 'unique:app_users,username,'.($this->editingUserId ?: 'NULL').',user_id'],
            'email' => 'nullable|email|max:255|unique:app_users,email,'.($this->editingUserId ?: 'NULL').',user_id',
            'phone' => 'nullable|string|max:50',
            'role_id' => 'required|exists:roles,role_id',
            'shepherd_id' => 'nullable|exists:shepherds,shepherd_id',
            'is_active' => 'boolean',
        ];

        $messages = [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), garis bawah (_), dan titik (.).',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'role_id.required' => 'Peran (Role) wajib dipilih.',
            'role_id.exists' => 'Peran yang dipilih tidak valid.',
            'shepherd_id.exists' => 'Data Gembala tidak valid.',
        ];

        $this->validate($rules, $messages);

        if ($this->editingUserId) {
            $target = User::findOrFail($this->editingUserId);
            $target->update([
                'full_name' => $this->full_name,
                'username' => strtolower(trim($this->username)),
                'email' => $this->email ? strtolower(trim($this->email)) : null,
                'phone' => $this->phone ?: null,
                'role_id' => $this->role_id,
                'shepherd_id' => $this->shepherd_id ?: null,
                'is_active' => $this->is_active,
                'modified_by' => Auth::id(),
                'date_modified' => now(),
            ]);

            session()->flash('success', "Data pengguna '{$target->full_name}' berhasil diperbarui.");
        } else {
            $newUser = User::create([
                'full_name' => $this->full_name,
                'username' => strtolower(trim($this->username)),
                'email' => $this->email ? strtolower(trim($this->email)) : null,
                'phone' => $this->phone ?: null,
                'role_id' => $this->role_id,
                'shepherd_id' => $this->shepherd_id ?: null,
                'password_hash' => Hash::make($this->defaultPassword),
                'is_active' => $this->is_active,
                'is_deleted' => false,
                'created_by' => Auth::id(),
                'date_created' => now(),
            ]);

            session()->flash('success', "Pengguna baru '{$newUser->full_name}' berhasil ditambahkan dengan password default ($this->defaultPassword).");
        }

        $this->closeModal();
    }

    public function resetPassword(int $userId): void
    {
        $this->authorizeMaster();
        $target = User::findOrFail($userId);
        $target->password_hash = Hash::make($this->defaultPassword);
        $target->modified_by = Auth::id();
        $target->date_modified = now();
        $target->save();

        session()->flash('success', "Password akun '{$target->full_name}' ({$target->username}) berhasil direset ke default: $this->defaultPassword");
    }

    public function toggleActive(int $userId): void
    {
        $this->authorizeMaster();
        $target = User::findOrFail($userId);

        if ($target->user_id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif.');

            return;
        }

        $target->is_active = ! $target->is_active;
        $target->modified_by = Auth::id();
        $target->date_modified = now();
        $target->save();

        $status = $target->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Status akun '{$target->full_name}' berhasil {$status}.");
    }

    public function deleteUser(int $userId): void
    {
        $this->authorizeMaster();
        $target = User::findOrFail($userId);

        if ($target->user_id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

            return;
        }

        $target->is_deleted = true;
        $target->deleted_by = Auth::id();
        $target->date_deleted = now();
        $target->save();

        session()->flash('success', "Pengguna '{$target->full_name}' berhasil dihapus (soft delete).");
    }

    public function render()
    {
        $this->authorizeMaster();

        $query = User::with(['role', 'shepherd'])
            ->where('is_deleted', false)
            ->when($this->search, function ($q) {
                $search = trim($this->search);
                $q->where(function ($sub) use ($search) {
                    $sub->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($this->roleFilter, function ($q) {
                $q->where('role_id', $this->roleFilter);
            })
            ->when($this->statusFilter !== '', function ($q) {
                $q->where('is_active', $this->statusFilter === '1');
            });

        $users = $query->orderBy('user_id', 'desc')->paginate($this->perPage);

        $roles = Role::where('is_deleted', false)->where('is_active', true)->orderBy('role_id')->get();
        $shepherds = Shepherd::where('is_deleted', false)->where('is_active', true)->orderBy('name')->get();

        return view('livewire.master.app-user-manager', compact('users', 'roles', 'shepherds'));
    }
}
