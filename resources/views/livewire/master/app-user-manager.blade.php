<div>
  <!-- Page Header -->
  <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h1 class="page-title mb-0">Manajemen Pengguna Aplikasi</h1>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
          <i class="bi bi-shield-lock me-1"></i>Akses MASTER
        </span>
      </div>
      <p class="page-subtitle text-muted mb-0">Kelola akun pengguna, tambah pengguna baru, ubah data profil, atau reset kata sandi akun.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ url('/profile') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
      <button type="button" class="btn btn-success" wire:click="openCreateModal">
        <i class="bi bi-person-plus-fill me-1"></i>Tambah Pengguna
      </button>
    </div>
  </div>



  <div class="card shadow-sm border-0">
    <div class="card-body">
      <!-- Filter Bar -->
      <div class="row g-2 mb-3 align-items-center">
        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Cari nama, username, email, telepon..."
                   wire:model.live.debounce.300ms="search">
          </div>
        </div>
        <div class="col-6 col-md-3">
          <select class="form-select" wire:model.live="roleFilter">
            <option value="">Semua Peran (Role)</option>
            @foreach ($roles as $r)
              <option value="{{ $r->role_id }}">{{ $r->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3">
          <select class="form-select" wire:model.live="statusFilter">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Non-Aktif</option>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select class="form-select" wire:model.live="perPage">
            <option value="10">10 / halaman</option>
            <option value="15">15 / halaman</option>
            <option value="25">25 / halaman</option>
            <option value="50">50 / halaman</option>
          </select>
        </div>
      </div>

      <!-- Users Table -->
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Pengguna</th>
              <th>Kontak</th>
              <th>Peran</th>
              <th>Profil Terkait</th>
              <th>Status</th>
              <th class="text-end" style="min-width: 220px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center"
                         style="width: 38px; height: 38px; font-size: 14px; flex-shrink: 0;">
                      {{ strtoupper(substr($user->full_name, 0, 1)) }}
                    </div>
                    <div>
                      <div class="fw-semibold text-dark">{{ $user->full_name }}</div>
                      <div class="text-muted small">@<span>{{ $user->username }}</span></div>
                    </div>
                  </div>
                </td>
                <td>
                  <div><i class="bi bi-envelope me-1 text-muted small"></i>{{ $user->email ?? '-' }}</div>
                  @if ($user->phone)
                    <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</div>
                  @endif
                </td>
                <td>
                  @if ($user->role)
                    @php
                      $badgeClass = match($user->role->name) {
                        'MASTER' => 'bg-danger-subtle text-danger border-danger-subtle',
                        'SHEPHERD' => 'bg-info-subtle text-info border-info-subtle',
                        default => 'bg-secondary-subtle text-secondary border-secondary-subtle'
                      };
                    @endphp
                    <span class="badge {{ $badgeClass }} border">
                      {{ $user->role->name }}
                    </span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary border">-</span>
                  @endif
                </td>
                <td>
                  @if ($user->member)
                    <span class="small text-dark fw-medium">
                      <i class="bi bi-person-badge text-primary me-1"></i>{{ $user->member->name }}
                    </span>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>
                <td>
                  @if ($user->is_active)
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                      <i class="bi bi-check-circle me-1"></i>Aktif
                    </span>
                  @else
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                      <i class="bi bi-x-circle me-1"></i>Non‑Aktif
                    </span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm" role="group">
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-outline-primary"
                            wire:click="openEditModal({{ $user->user_id }})"
                            title="Ubah Data Pengguna">
                      <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- Reset Password Button -->
                    <button type="button" class="btn btn-outline-warning"
                            wire:click="resetPassword({{ $user->user_id }})"
                            wire:confirm="Apakah Anda yakin ingin mereset password akun '{{ $user->username }}' ke default ({{ $defaultPassword }})?"
                            title="Reset Password ke default ({{ $defaultPassword }})">
                      <i class="bi bi-key-fill"></i>
                    </button>

                    <!-- Toggle Active Button -->
                    <button type="button" class="btn {{ $user->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                            wire:click="toggleActive({{ $user->user_id }})"
                            title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                      <i class="bi {{ $user->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                    </button>

                    <!-- Delete Button -->
                    <button type="button" class="btn btn-outline-danger"
                            wire:click="deleteUser({{ $user->user_id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus akun pengguna '{{ $user->full_name }}'?"
                            title="Hapus Akun Pengguna">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="bi bi-people fs-1 d-block text-secondary mb-2"></i>
                  Tidak ada data pengguna yang ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $users->links() }}
      </div>
    </div>
  </div>

  <!-- Modal Tambah / Edit Pengguna -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog" style="background-color: rgba(0, 0, 0, 0.55);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header bg-light">
            <h5 class="modal-title fw-bold">
              <i class="bi {{ $editingUserId ? 'bi-pencil-square text-primary' : 'bi-person-plus-fill text-success' }} me-2"></i>
              {{ $editingUserId ? 'Ubah Data Pengguna' : 'Tambah Pengguna Baru' }}
            </h5>
            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body p-4">
              @if (! $editingUserId)
                <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center gap-2">
                  <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                  <div>
                    Password default akun baru otomatis disetel: <strong class="badge bg-light text-dark border">{{ $defaultPassword }}</strong>
                  </div>
                </div>
              @endif

              <div class="mb-3">
                <label for="full_name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="full_name" wire:model="full_name"
                       class="form-control @error('full_name') is-invalid @enderror"
                       placeholder="Contoh: Yohanes Santoso">
                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                  <input type="text" id="username" wire:model="username"
                         class="form-control @error('username') is-invalid @enderror"
                         placeholder="Contoh: yohanes.santoso">
                  @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="role_id" class="form-label fw-semibold">Peran (Role) <span class="text-danger">*</span></label>
                  <select id="role_id" wire:model.live="role_id" class="form-select @error('role_id') is-invalid @enderror">
                    <option value="">-- Pilih Peran --</option>
                    @foreach ($roles as $role)
                      <option value="{{ $role->role_id }}">{{ $role->name }} ({{ $role->description ?? '-' }})</option>
                    @endforeach
                  </select>
                  @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Alamat Email</label>
                  <input type="email" id="email" wire:model="email"
                         class="form-control @error('email') is-invalid @enderror"
                         placeholder="nama@contoh.org">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="phone" class="form-label fw-semibold">Nomor Telepon / WA</label>
                  <input type="text" id="phone" wire:model="phone"
                         class="form-control @error('phone') is-invalid @enderror"
                         placeholder="08123456789">
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <!-- Optional Shepherd Link if Role is SHEPHERD -->
              <div class="mb-3">
                <label for="shepherd_id" class="form-label fw-semibold">Profil Gembala Terkait <span class="text-muted small fw-normal">(Khusus Gembala COOL)</span></label>
                <select id="shepherd_id" wire:model="shepherd_id" class="form-select @error('shepherd_id') is-invalid @enderror">
                  <option value="">-- Tidak Terkait Profil Gembala --</option>
                  @foreach ($shepherds as $sh)
                    <option value="{{ $sh->member_id }}">{{ $sh->name }} ({{ $sh->email ?? $sh->phone ?? '-' }})</option>
                  @endforeach
                </select>
                @error('shepherd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" id="is_active" wire:model="is_active">
                <label class="form-check-label fw-semibold" for="is_active">Status Akun Aktif</label>
                <div class="form-text small">Jika dinonaktifkan, pengguna tidak dapat masuk ke aplikasi.</div>
              </div>
            </div>

            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
              <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                <span wire:loading.remove><i class="bi bi-check-lg me-1"></i> Simpan</span>
                <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Menyimpan...</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
