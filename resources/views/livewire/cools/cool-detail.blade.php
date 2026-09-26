<div>
  <!-- Header & Navigation -->
  <div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
      <a href="{{ url('/cools') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar COOL
      </a>
      <h1 class="page-title mb-1">{{ $cool->name }}</h1>
      <div class="d-flex align-items-center gap-2 mt-1">
        <span class="badge bg-light text-dark border font-monospace">{{ $cool->cool_code }}</span>
        @if ($cool->is_active)
          <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
        @else
          <span class="badge bg-secondary-subtle text-secondary">Non-Aktif</span>
        @endif
      </div>
    </div>
    <div class="w-100 w-sm-auto text-start text-sm-end">
      <a href="{{ url('/activities?coolFilter=' . $cool->cool_id) }}" class="btn btn-success">
        <i class="bi bi-calendar-plus me-1"></i> Buat Kegiatan
      </a>
    </div>
  </div>



  <!-- Profile Card -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-4 align-items-center">
        <div class="col-12 col-md-6 border-bottom border-bottom-md-0 pb-3 pb-md-0">
          <h6 class="text-muted text-uppercase small fw-bold mb-2">Gembala Pembina</h6>
          <div class="d-flex align-items-center">
            <div class="bg-success-subtle text-success p-3 rounded-circle me-3">
              <i class="bi bi-person-badge fs-3"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-0">{{ $cool->shepherd->name ?? 'Belum Ditentukan' }}</h5>
              <p class="text-muted mb-0 small">
                @if ($cool->shepherd && $cool->shepherd->phone)
                  @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $cool->shepherd->phone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                  @endphp
                  <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-decoration-none text-success fw-medium me-2">
                    <i class="bi bi-whatsapp me-1"></i>{{ $cool->shepherd->phone }}
                  </a>
                @endif
                @if ($cool->shepherd && $cool->shepherd->email)
                  <span class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $cool->shepherd->email }}</span>
                @endif
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
  <ul class="nav nav-tabs mb-4 flex-nowrap overflow-x-auto pb-1">
    <li class="nav-item text-nowrap">
      <button class="nav-link {{ $activeTab === 'members' ? 'active fw-bold text-success' : 'text-muted' }}"
        type="button" wire:click="$set('activeTab', 'members')">
        <i class="bi bi-people-fill me-1"></i> Anggota Aktif ({{ $cool->coolMembers->count() }})
      </button>
    </li>
    <li class="nav-item text-nowrap">
      <button class="nav-link {{ $activeTab === 'activities' ? 'active fw-bold text-success' : 'text-muted' }}"
        type="button" wire:click="$set('activeTab', 'activities')">
        <i class="bi bi-calendar-event-fill me-1"></i> Jadwal & Riwayat ({{ $cool->activities->count() }})
      </button>
    </li>
    <li class="nav-item text-nowrap">
      <button class="nav-link {{ $activeTab === 'qr' ? 'active fw-bold text-success' : 'text-muted' }}"
        type="button" wire:click="$set('activeTab', 'qr')">
        <i class="bi bi-qr-code-scan me-1"></i> Akses QR & PIN
      </button>
    </li>
  </ul>

  <!-- Tab 1: Anggota Aktif -->
  @if ($activeTab === 'members')
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h6 class="card-title mb-0 fw-bold text-dark">
            <i class="bi bi-people-fill text-success me-2"></i>Daftar Anggota Aktif Kelompok COOL
          </h6>
          <small class="text-muted">Total {{ $cool->coolMembers->count() }} anggota terdaftar dalam kelompok ini</small>
        </div>
        <a href="{{ url('/members') }}" class="btn btn-sm btn-success">
          <i class="bi bi-person-plus-fill me-1"></i> Kelola / Tambah Anggota
        </a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="min-width: 120px;">No. ID</th>
                <th style="min-width: 200px;">Nama Anggota</th>
                <th style="min-width: 180px;">No. Telepon / WhatsApp</th>
                <th style="min-width: 180px;">Email</th>
                <th style="min-width: 160px;">Tgl Bergabung COOL</th>
                <th style="min-width: 130px;">Status</th>
                <th class="text-end" style="min-width: 100px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($cool->coolMembers as $cm)
                <tr wire:key="cm-{{ $cm->cool_member_id }}">
                  <td>
                    <span class="badge bg-light text-secondary border font-monospace">
                      {{ $cm->member->member_code ?? '-' }}
                    </span>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 36px; height: 36px; font-weight: 600;">
                        {{ strtoupper(substr($cm->member->name ?? 'A', 0, 2)) }}
                      </div>
                      <span class="fw-semibold text-dark">{{ $cm->member->name ?? '-' }}</span>
                    </div>
                  </td>
                  <td>
                    @if ($cm->member && $cm->member->phone)
                      @php
                        $cleanMbrPhone = preg_replace('/[^0-9]/', '', $cm->member->phone);
                        if (str_starts_with($cleanMbrPhone, '0')) {
                            $cleanMbrPhone = '62' . substr($cleanMbrPhone, 1);
                        }
                      @endphp
                      <a href="https://wa.me/{{ $cleanMbrPhone }}" target="_blank" class="text-decoration-none text-success fw-medium" title="Kirim Pesan WhatsApp">
                        <i class="bi bi-whatsapp me-1"></i>{{ $cm->member->phone }}
                      </a>
                    @else
                      <span class="text-muted small">-</span>
                    @endif
                  </td>
                  <td>
                    @if ($cm->member && $cm->member->email)
                      <span class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $cm->member->email }}</span>
                    @else
                      <span class="text-muted small">-</span>
                    @endif
                  </td>
                  <td>
                    <span class="text-muted small">
                      <i class="bi bi-calendar3 me-1"></i>
                      {{ $cm->start_date ? \Carbon\Carbon::parse($cm->start_date)->format('d M Y') : '-' }}
                    </span>
                  </td>
                  <td>
                    @php $mbrStatus = $cm->member->status ?? $cm->status; @endphp
                    @if ($mbrStatus === 'ACTIVE')
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle-fill me-1"></i>Aktif
                      </span>
                    @elseif ($mbrStatus === 'NEW')
                      <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        <i class="bi bi-person-plus-fill me-1"></i>Baru
                      </span>
                    @elseif ($mbrStatus === 'MOVED')
                      <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                        <i class="bi bi-arrow-left-right me-1"></i>Pindah
                      </span>
                    @elseif ($mbrStatus === 'INACTIVE' || (isset($cm->member) && ! $cm->member->is_active))
                      <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                        <i class="bi bi-dash-circle me-1"></i>Non-Aktif
                      </span>
                    @else
                      <span class="badge bg-light text-dark border">
                        {{ $mbrStatus }}
                      </span>
                    @endif
                  </td>
                  <td class="text-end text-nowrap">
                    @if ($cm->member)
                      <a href="{{ url('/members?search=' . urlencode($cm->member->member_code ?: $cm->member->name)) }}" class="btn btn-sm btn-outline-secondary" title="Kelola Anggota di Master Data">
                        <i class="bi bi-pencil me-1"></i> Ubah
                      </a>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-5">
                    <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada anggota terdaftar pada kelompok COOL ini.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

  <!-- Tab 2: Jadwal & Riwayat Kegiatan -->
  @if ($activeTab === 'activities')
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h6 class="card-title mb-0 fw-bold text-dark">
            <i class="bi bi-calendar-event-fill text-success me-2"></i>Jadwal & Riwayat Kegiatan COOL
          </h6>
          <small class="text-muted">Total {{ $cool->activities->count() }} kegiatan tercatat</small>
        </div>
        <a href="{{ url('/activities?coolFilter=' . $cool->cool_id) }}" class="btn btn-sm btn-success">
          <i class="bi bi-calendar-plus me-1"></i> Buat Kegiatan Baru
        </a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="min-width: 180px;">Nama Kegiatan</th>
                <th style="min-width: 140px;">Jenis Pertemuan</th>
                <th style="min-width: 150px;">Tanggal & Waktu</th>
                <th style="min-width: 130px;">Lokasi</th>
                <th style="min-width: 120px;">Kehadiran</th>
                <th style="min-width: 110px;">Status</th>
                <th class="text-end" style="min-width: 110px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($cool->activities as $act)
                <tr wire:key="act-{{ $act->activity_id }}">
                  <td class="fw-semibold text-dark">{{ $act->name }}</td>
                  <td><span class="badge bg-light text-dark border">{{ $act->activityType->name ?? 'Kegiatan' }}</span></td>
                  <td>
                    <div class="fw-medium text-dark"><i class="bi bi-calendar-event me-1 text-muted"></i>{{ $act->activity_date ? $act->activity_date->format('d M Y') : '-' }}</div>
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $act->start_time ? substr((string) $act->start_time, 0, 5) : '19:00' }} WIB</small>
                  </td>
                  <td><span class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $act->location ?: 'Online' }}</span></td>
                  <td>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                      <i class="bi bi-person-check-fill me-1"></i>{{ $act->attendances->where('status.code', 'PRESENT')->count() }} Hadir
                    </span>
                  </td>
                  <td>
                    @if ($act->status === 'COMPLETED')
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle-fill me-1"></i>Selesai
                      </span>
                    @elseif ($act->status === 'CANCELLED')
                      <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                        <i class="bi bi-x-circle-fill me-1"></i>Dibatalkan
                      </span>
                    @else
                      <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        <i class="bi bi-clock-history me-1"></i>Terjadwal
                      </span>
                    @endif
                  </td>
                  <td class="text-end text-nowrap">
                    <a href="{{ url('/attendances?activity_id=' . $act->activity_id) }}" class="btn btn-sm btn-outline-success">
                      <i class="bi bi-check2-square me-1"></i> Presensi
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada kegiatan yang dijadwalkan untuk kelompok ini.
                  </td>
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
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white py-3 fw-bold">
            <i class="bi bi-qr-code me-1 text-success"></i> Tautan Akses Anggota
          </div>
          <div class="card-body text-center py-4">
            @if ($activeQr)
              @php
                $portalUrl = url('/c/' . $activeQr->qr_token);
              @endphp
              <div class="p-3 border rounded bg-light d-inline-block mb-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($portalUrl) }}"
                  alt="QR Code {{ $cool->name }}" class="img-fluid rounded" style="width: 150px; height: 150px;">
              </div>
              <h6 class="fw-bold mb-1">{{ $activeQr->access_code }}</h6>
              <p class="small text-muted mb-3">Token: <code>{{ $activeQr->qr_token }}</code></p>

              <div class="input-group mb-3">
                <input type="text" class="form-control font-monospace small" readonly
                  value="{{ $portalUrl }}" id="qrUrlInput">
                <button class="btn btn-outline-secondary" type="button"
                  onclick="navigator.clipboard.writeText(document.getElementById('qrUrlInput').value); alert('Tautan portal anggota disalin!')">
                  <i class="bi bi-clipboard"></i> Salin
                </button>
              </div>

              <div class="d-flex justify-content-center gap-2">
                <a href="{{ $portalUrl }}" target="_blank" class="btn btn-success btn-sm">
                  <i class="bi bi-box-arrow-up-right me-1"></i> Buka Portal Anggota
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="regenerateQrToken"
                  onclick="return confirm('Regenerate token akan membuat link QR lama tidak berlaku. Lanjutkan?')">
                  <i class="bi bi-arrow-clockwise me-1"></i> Regenerate Token
                </button>
              </div>
            @else
              <div class="py-4 text-muted">
                <i class="bi bi-qr-code text-secondary fs-1 d-block mb-2"></i>
                <p class="small mb-3">Kelompok ini belum memiliki QR Code akses mandiri.</p>
                <button type="button" class="btn btn-sm btn-success" wire:click="generateMissingQr">
                  <i class="bi bi-plus-circle me-1"></i> Buat Akses QR & PIN
                </button>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white py-3 fw-bold">
            <i class="bi bi-key-fill me-1 text-success"></i> Keamanan PIN Kelompok
          </div>
          <div class="card-body">
            <p class="small text-muted mb-3">
              Anggota yang membuka tautan QR diwajibkan memasukkan PIN kelompok sebelum dapat melihat materi kegiatan dan mengirim pesan kepada Gembala.
            </p>

            <form wire:submit="updatePin">
              <div class="mb-3">
                <label for="newPin" class="form-label fw-semibold">Atur 6-Digit PIN Baru</label>
                <input type="password" id="newPin" wire:model="newPin" maxlength="6"
                  class="form-control form-control-lg text-center font-monospace tracking-widest @error('newPin') is-invalid @enderror"
                  placeholder="&bull;&bull;&bull;&bull;&bull;&bull;">
                @error('newPin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted d-block mt-1">PIN disimpan dalam format hash aman (Bcrypt).</small>
              </div>

              <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
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
