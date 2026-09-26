<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Profil & Pengaturan Akun</h1>
      <p class="page-subtitle">Kelola informasi profil dan keamanan akun Anda</p>
    </div>
  </div>



  <div class="row g-4">

    <!-- Left: Profile Card -->
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center py-5 px-4">
          <!-- Avatar -->
          <div class="position-relative d-inline-block mb-3">
            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center mx-auto"
              style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 700; color: #fff; letter-spacing: 2px;">
              {{ strtoupper(substr($user->full_name ?? 'U', 0, 2)) }}
            </div>
            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-3 border-white"
              style="font-size: 0.7rem; padding: 5px 8px;">
              <i class="bi bi-check-lg"></i> Aktif
            </span>
          </div>

          <h4 class="fw-bold text-dark mb-1">{{ $user->full_name }}</h4>
          <p class="text-muted mb-1 small">{{ $user->username }}</p>

          <div class="d-flex justify-content-center mb-3">
            @if ($user->role)
              @if ($user->role->name === 'MASTER')
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                  <i class="bi bi-shield-fill-check me-1"></i>Master Administrator
                </span>
              @elseif ($user->role->name === 'SHEPHERD')
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                  <i class="bi bi-person-badge-fill me-1"></i>Gembala COOL
                </span>
              @else
                <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">
                  <i class="bi bi-person me-1"></i>{{ $user->role->name }}
                </span>
              @endif
            @endif
          </div>

          <hr>

          <!-- Info Summary -->
          <div class="text-start px-2">
            @if ($user->email)
              <div class="d-flex align-items-center mb-3 gap-3">
                <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                  <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="overflow-hidden">
                  <div class="small text-muted">Email</div>
                  <div class="fw-medium text-dark text-truncate" style="max-width: 200px;">{{ $user->email }}</div>
                </div>
              </div>
            @endif

            @if ($user->phone)
              <div class="d-flex align-items-center mb-3 gap-3">
                <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <div>
                  <div class="small text-muted">Telepon</div>
                  <div class="fw-medium text-dark">{{ $user->phone }}</div>
                </div>
              </div>
            @endif

            @if ($user->shepherd)
              <div class="d-flex align-items-center mb-3 gap-3">
                <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                  <i class="bi bi-people-fill"></i>
                </div>
                <div>
                  <div class="small text-muted">Gembala</div>
                  <div class="fw-medium text-dark">{{ $user->shepherd->name }}</div>
                </div>
              </div>
            @endif

            <div class="d-flex align-items-center gap-3">
              <div class="flex-shrink-0 bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                <i class="bi bi-calendar3"></i>
              </div>
              <div>
                <div class="small text-muted">Bergabung Sejak</div>
                <div class="fw-medium text-dark">
                  {{ $user->date_created ? $user->date_created->format('d M Y') : '-' }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Tabs -->
    <div class="col-12 col-lg-8">
      <!-- Tab Nav -->
      <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
          <button class="nav-link {{ $activeTab === 'profile' ? 'active fw-bold text-success' : 'text-muted' }}"
            type="button" wire:click="$set('activeTab', 'profile')">
            <i class="bi bi-person-fill me-1"></i> Informasi Profil
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link {{ $activeTab === 'security' ? 'active fw-bold text-success' : 'text-muted' }}"
            type="button" wire:click="$set('activeTab', 'security')">
            <i class="bi bi-shield-lock-fill me-1"></i> Keamanan & Kata Sandi
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link {{ $activeTab === 'account' ? 'active fw-bold text-success' : 'text-muted' }}"
            type="button" wire:click="$set('activeTab', 'account')">
            <i class="bi bi-info-circle-fill me-1"></i> Info Akun
          </button>
        </li>
      </ul>

      <!-- Tab: Profile Edit -->
      @if ($activeTab === 'profile')
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0 fw-bold text-dark">
              <i class="bi bi-person-lines-fill text-success me-2"></i>Ubah Informasi Profil
            </h6>
            <small class="text-muted">Perbarui nama, email, dan nomor telepon Anda.</small>
          </div>
          <div class="card-body p-4">
            <form wire:submit="updateProfile">
              <div class="mb-4">
                <label for="username_display" class="form-label fw-semibold">Username</label>
                <input type="text" id="username_display" class="form-control bg-light text-muted" value="{{ $username }}" readonly disabled>
                <div class="form-text"><i class="bi bi-info-circle me-1"></i>Username tidak dapat diubah.</div>
              </div>

              <div class="mb-4">
                <label for="full_name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="full_name" wire:model="full_name"
                  class="form-control @error('full_name') is-invalid @enderror"
                  placeholder="Nama lengkap Anda">
                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label for="profile_email" class="form-label fw-semibold">Alamat Email</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" id="profile_email" wire:model="email"
                      class="form-control @error('email') is-invalid @enderror"
                      placeholder="nama@domain.com">
                  </div>
                  @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="profile_phone" class="form-label fw-semibold">Nomor Telepon / WA</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-phone text-muted"></i></span>
                    <input type="text" id="profile_phone" wire:model="phone"
                      class="form-control @error('phone') is-invalid @enderror"
                      placeholder="08xxxxxxxxxx">
                  </div>
                  @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                  <span wire:loading.remove><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</span>
                  <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i>Menyimpan...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      @endif

      <!-- Tab: Security -->
      @if ($activeTab === 'security')
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0 fw-bold text-dark">
              <i class="bi bi-shield-lock-fill text-success me-2"></i>Ubah Kata Sandi
            </h6>
            <small class="text-muted">Gunakan kombinasi huruf, angka, dan simbol untuk kata sandi yang kuat.</small>
          </div>
          <div class="card-body p-4">
            <form wire:submit="updatePassword">
              <div class="mb-4">
                <label for="current_password" class="form-label fw-semibold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                  <input type="password" id="current_password" wire:model="current_password"
                    class="form-control @error('current_password') is-invalid @enderror"
                    placeholder="Masukkan kata sandi saat ini">
                </div>
                @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="mb-4">
                <label for="new_password" class="form-label fw-semibold">Kata Sandi Baru <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-key text-muted"></i></span>
                  <input type="password" id="new_password" wire:model="new_password"
                    class="form-control @error('new_password') is-invalid @enderror"
                    placeholder="Min. 8 karakter (huruf + angka)">
                </div>
                @error('new_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <div class="form-text"><i class="bi bi-info-circle me-1"></i>Minimal 8 karakter dengan kombinasi huruf dan angka.</div>
              </div>

              <div class="mb-4">
                <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-key-fill text-muted"></i></span>
                  <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                    class="form-control"
                    placeholder="Ulangi kata sandi baru">
                </div>
              </div>

              <!-- Password strength tips -->
              <div class="alert alert-info border-0 bg-info-subtle py-3 mb-4" role="alert">
                <h6 class="alert-heading fw-bold mb-2 small"><i class="bi bi-lightbulb-fill me-1"></i>Tips Kata Sandi Kuat:</h6>
                <ul class="mb-0 small ps-3">
                  <li>Minimal 8 karakter</li>
                  <li>Kombinasi huruf besar, huruf kecil, dan angka</li>
                  <li>Hindari menggunakan tanggal lahir atau nama</li>
                  <li>Jangan gunakan kata sandi yang sama di banyak tempat</li>
                </ul>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" wire:click="$set('activeTab', 'profile')">
                  <i class="bi bi-arrow-left me-1"></i>Batal
                </button>
                <button type="submit" class="btn btn-warning text-dark fw-bold" wire:loading.attr="disabled">
                  <span wire:loading.remove><i class="bi bi-shield-check me-1"></i>Perbarui Kata Sandi</span>
                  <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i>Memperbarui...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      @endif

      <!-- Tab: Account Info -->
      @if ($activeTab === 'account')
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <h6 class="card-title mb-0 fw-bold text-dark">
              <i class="bi bi-info-circle-fill text-success me-2"></i>Informasi Akun
            </h6>
            <small class="text-muted">Detail teknis dan metadata akun Anda di sistem.</small>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                  <div class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">User ID</div>
                  <div class="fw-bold text-dark font-monospace">#{{ str_pad($user->user_id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                  <div class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Username</div>
                  <div class="fw-bold text-dark font-monospace">{{ $user->username }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                  <div class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Peran / Role</div>
                  <div class="fw-bold text-dark">{{ $user->role->name ?? '-' }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                  <div class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Status Akun</div>
                  <div class="fw-bold">
                    @if ($user->is_active)
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle-fill me-1"></i>Aktif
                      </span>
                    @else
                      <span class="badge bg-secondary-subtle text-secondary border">
                        <i class="bi bi-dash-circle me-1"></i>Non-Aktif
                      </span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                  <div class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Tanggal Bergabung</div>
                  <div class="fw-bold text-dark">
                    {{ $user->date_created ? $user->date_created->format('d F Y, H:i') : '-' }}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                  <div class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Terakhir Diperbarui</div>
                  <div class="fw-bold text-dark">
                    {{ $user->date_modified ? $user->date_modified->format('d F Y, H:i') : '-' }}
                  </div>
                </div>
              </div>

              @if ($user->shepherd)
                <div class="col-12">
                  <div class="p-3 bg-success-subtle border border-success-subtle rounded-3">
                    <div class="text-muted small fw-semibold mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                      <i class="bi bi-people-fill text-success me-1"></i>Profil Gembala COOL Terhubung
                    </div>
                    <div class="fw-bold text-dark">{{ $user->shepherd->name }}</div>
                    @if ($user->shepherd->phone)
                      <div class="text-muted small">{{ $user->shepherd->phone }}</div>
                    @endif
                  </div>
                </div>
              @endif
            </div>

            <!-- Danger Zone -->
            <hr class="mt-4 mb-3">
            <div class="border border-danger-subtle rounded-3 p-3 bg-danger-subtle">
              <h6 class="text-danger fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Zona Sensitif</h6>
              <p class="text-muted small mb-3">Tindakan berikut bersifat permanen dan memerlukan konfirmasi administrator.</p>
              <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-danger"
                  onclick="alert('Untuk menonaktifkan akun, silakan hubungi Master Administrator.')">
                  <i class="bi bi-person-dash me-1"></i>Nonaktifkan Akun
                </button>
              </div>
            </div>
          </div>
        </div>
      @endif

    </div>
  </div>
</div>
