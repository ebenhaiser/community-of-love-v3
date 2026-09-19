<div>
  <!-- Header & Navigation -->
  <div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
      <a href="{{ url('/cools') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar COOL
      </a>
      <h1 class="page-title mb-1">{{ $cool->name }}</h1>
      <span class="badge bg-light text-dark border font-monospace me-2">{{ $cool->cool_code }}</span>
      @if ($cool->is_active)
        <span class="badge bg-success">Aktif</span>
      @else
        <span class="badge bg-secondary">Non-Aktif</span>
      @endif
    </div>
    <div>
      <a href="{{ url('/activities?coolFilter=' . $cool->cool_id) }}" class="btn btn-outline-primary me-1">
        <i class="bi bi-calendar-plus me-1"></i> Buat Kegiatan
      </a>
    </div>
  </div>

  <!-- Profile Card -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-4 align-items-center">
        <div class="col-md-6 border-end">
          <h6 class="text-muted text-uppercase small fw-bold mb-2">Gembala Pembina</h6>
          <div class="d-flex align-items-center">
            <div class="bg-success-subtle text-success p-3 rounded-circle me-3">
              <i class="bi bi-person-badge fs-3"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-0">{{ $cool->shepherd->name ?? 'Belum Ditentukan' }}</h5>
              <p class="text-muted mb-0 small">
                <i class="bi bi-telephone me-1"></i> {{ $cool->shepherd->phone ?? '-' }} •
                <i class="bi bi-envelope me-1"></i> {{ $cool->shepherd->email ?? '-' }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h6 class="text-muted text-uppercase small fw-bold mb-2">Profil & Keterangan</h6>
          <p class="mb-0 text-secondary">
            {{ $cool->description ?: 'Belum ada deskripsi profil untuk kelompok COOL ini.' }}
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabs Navigation -->
  <ul class="nav nav-tabs mb-4">
    <li class="nav-item">
      <button class="nav-link {{ $activeTab === 'members' ? 'active fw-bold text-success' : 'text-muted' }}"
        type="button" wire:click="$set('activeTab', 'members')">
        <i class="bi bi-people-fill me-1"></i> Anggota Aktif ({{ $cool->coolMembers->count() }})
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link {{ $activeTab === 'activities' ? 'active fw-bold text-success' : 'text-muted' }}"
        type="button" wire:click="$set('activeTab', 'activities')">
        <i class="bi bi-calendar-event-fill me-1"></i> Jadwal & Riwayat Kegiatan ({{ $cool->activities->count() }})
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link {{ $activeTab === 'qr' ? 'active fw-bold text-success' : 'text-muted' }}"
        type="button" wire:click="$set('activeTab', 'qr')">
        <i class="bi bi-qr-code-scan me-1"></i> Akses QR Code & PIN
      </button>
    </li>
  </ul>

  <!-- Tab 1: Anggota Aktif -->
  @if ($activeTab === 'members')
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Kode</th>
                <th>Nama Anggota</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Tgl Bergabung COOL</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($cool->coolMembers as $cm)
                <tr wire:key="cm-{{ $cm->cool_member_id }}">
                  <td><span class="badge bg-light text-dark border font-monospace">{{ $cm->member->member_code ?? '-' }}</span></td>
                  <td class="fw-semibold">{{ $cm->member->name ?? '-' }}</td>
                  <td>{{ $cm->member->phone ?? '-' }}</td>
                  <td>{{ $cm->member->email ?? '-' }}</td>
                  <td>{{ \Carbon\Carbon::parse($cm->start_date)->format('d M Y') }}</td>
                  <td><span class="badge bg-success">Aktif</span></td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">Belum ada anggota yang terdaftar pada kelompok COOL ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

  <!-- Tab 2: Jadwal & Kegiatan -->
  @if ($activeTab === 'activities')
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Nama Kegiatan</th>
                <th>Jenis</th>
                <th>Tanggal & Waktu</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th class="text-end">Presensi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($cool->activities as $act)
                <tr wire:key="cool-act-{{ $act->activity_id }}">
                  <td class="fw-semibold">{{ $act->name }}</td>
                  <td><span class="badge bg-secondary-subtle text-dark">{{ $act->activityType->name ?? '-' }}</span></td>
                  <td>
                    {{ \Carbon\Carbon::parse($act->activity_date)->format('d M Y') }}
                    <small class="text-muted d-block">{{ $act->start_time ? substr($act->start_time, 0, 5) : '-' }} WIB</small>
                  </td>
                  <td>{{ $act->location ?? '-' }}</td>
                  <td>
                    @if ($act->status === 'COMPLETED')
                      <span class="badge bg-success-subtle text-success">Selesai</span>
                    @elseif ($act->status === 'SCHEDULED')
                      <span class="badge bg-primary-subtle text-primary">Terjadwal</span>
                    @else
                      <span class="badge bg-light text-muted">{{ $act->status }}</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <a href="{{ url('/activities/' . $act->activity_id . '/attendance') }}" class="btn btn-sm btn-outline-success">
                      <i class="bi bi-check2-square me-1"></i> Buka Presensi
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">Belum ada kegiatan yang dibuat untuk kelompok ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

  <!-- Tab 3: Akses QR Code & PIN -->
  @if ($activeTab === 'qr')
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3 fw-bold">
            <i class="bi bi-qr-code me-1 text-success"></i> Tautan Akses Anggota
          </div>
          <div class="card-body text-center py-4">
            @if ($activeQr)
              <div class="p-3 border rounded bg-light d-inline-block mb-3">
                <i class="bi bi-qr-code display-1 text-success"></i>
              </div>
              <h6 class="fw-bold mb-1">{{ $activeQr->access_code }}</h6>
              <p class="small text-muted mb-3">Token: <code>{{ $activeQr->qr_token }}</code></p>

              <div class="input-group mb-3">
                <input type="text" class="form-control font-monospace small" readonly
                  value="{{ url('/c/' . $activeQr->qr_token) }}" id="qrUrlInput">
                <button class="btn btn-outline-secondary" type="button"
                  onclick="navigator.clipboard.writeText(document.getElementById('qrUrlInput').value); alert('Tautan portal anggota disalin!')">
                  <i class="bi bi-clipboard"></i> Salin
                </button>
              </div>

              <div class="d-flex justify-content-center gap-2">
                <a href="{{ url('/c/' . $activeQr->qr_token) }}" target="_blank" class="btn btn-success btn-sm">
                  <i class="bi bi-box-arrow-up-right me-1"></i> Buka Portal Anggota
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="regenerateQrToken"
                  onclick="return confirm('Regenerate token akan membuat link QR lama tidak berlaku. Lanjutkan?')">
                  <i class="bi bi-arrow-clockwise me-1"></i> Regenerate Token
                </button>
              </div>
            @else
              <p class="text-muted">Kelompok ini belum memiliki QR Code akses.</p>
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3 fw-bold">
            <i class="bi bi-key-fill me-1 text-success"></i> Keamanan PIN Kelompok
          </div>
          <div class="card-body">
            <p class="small text-muted mb-3">
              Anggota yang membuka tautan QR diwajibkan memasukkan PIN kelompok sebelum dapat melihat materi kegiatan dan mengirim pesan kepada Gembala.
            </p>

            <form wire:submit="updatePin">
              <div class="mb-3">
                <label for="newPin" class="form-label fw-semibold">Atur PIN Baru</label>
                <input type="password" id="newPin" wire:model="newPin"
                  class="form-control @error('newPin') is-invalid @enderror"
                  placeholder="Masukkan 4-10 karakter PIN (contoh: 123456)">
                @error('newPin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted d-block mt-1">PIN disimpan dalam format hash aman (Bcrypt).</small>
              </div>

              <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove><i class="bi bi-shield-lock me-1"></i> Simpan PIN Baru</span>
                <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Menyimpan...</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
