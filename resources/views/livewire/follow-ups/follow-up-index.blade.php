<div>
  <!-- Page Header -->
  <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h1 class="page-title mb-0">Master Perhatian & Follow-up Pastoral</h1>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
          <i class="bi bi-heart-pulse me-1"></i>Pastoral Care
        </span>
      </div>
      <p class="page-subtitle text-muted mb-0">
        Kelola dan pantau anggota yang tidak hadir berturut-turut (&ge; {{ $consecutiveThreshold }}x). Anggota yang sudah ditandai selesai tidak akan muncul di alert Dashboard dan Statistik.
      </p>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-outline-secondary" wire:click="syncConsecutiveAbsences" wire:loading.attr="disabled" title="Deteksi Ulang Absensi Anggota">
        <i class="bi bi-arrow-clockwise me-1" wire:loading.class="spin"></i> Sinkronisasi Deteksi
      </button>
      <a href="{{ url('/statistics') }}" class="btn btn-outline-primary">
        <i class="bi bi-bar-chart-line me-1"></i>Lihat Statistik
      </a>
    </div>
  </div>

  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Metrics Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0 border-start border-danger border-4">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase">Belum Ditangani</div>
            <h3 class="fw-bold mb-0 text-danger">{{ $pendingCount }}</h3>
            <small class="text-muted">Memerlukan sapaan / kunjungan</small>
          </div>
          <div class="rounded-circle bg-danger-subtle p-3 text-danger fs-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0 border-start border-success border-4">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase">Sudah Ditangani</div>
            <h3 class="fw-bold mb-0 text-success">{{ $resolvedCount }}</h3>
            <small class="text-muted">Telah dikontak / didoakan</small>
          </div>
          <div class="rounded-circle bg-success-subtle p-3 text-success fs-3">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0 border-start border-primary border-4">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
          <div>
            <div class="text-muted small fw-semibold text-uppercase">Total Riwayat Kasus</div>
            <h3 class="fw-bold mb-0 text-primary">{{ $totalCount }}</h3>
            <small class="text-muted">Total catatan pastoral</small>
          </div>
          <div class="rounded-circle bg-primary-subtle p-3 text-primary fs-3">
            <i class="bi bi-journal-text"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom p-3">
      <!-- Status Tabs -->
      <ul class="nav nav-pills card-header-pills mb-3">
        <li class="nav-item">
          <button type="button" class="nav-link {{ $statusTab === 'PENDING' ? 'active bg-danger text-white' : 'text-dark' }}"
                  wire:click="$set('statusTab', 'PENDING')">
            <i class="bi bi-hourglass-split me-1"></i> Belum Ditangani
            <span class="badge {{ $statusTab === 'PENDING' ? 'bg-white text-danger' : 'bg-danger text-white' }} ms-1 rounded-pill">{{ $pendingCount }}</span>
          </button>
        </li>
        <li class="nav-item ms-2">
          <button type="button" class="nav-link {{ $statusTab === 'RESOLVED' ? 'active bg-success text-white' : 'text-dark' }}"
                  wire:click="$set('statusTab', 'RESOLVED')">
            <i class="bi bi-check-circle me-1"></i> Sudah Ditangani
            <span class="badge {{ $statusTab === 'RESOLVED' ? 'bg-white text-success' : 'bg-success text-white' }} ms-1 rounded-pill">{{ $resolvedCount }}</span>
          </button>
        </li>
        <li class="nav-item ms-2">
          <button type="button" class="nav-link {{ $statusTab === 'ALL' ? 'active bg-secondary text-white' : 'text-dark' }}"
                  wire:click="$set('statusTab', 'ALL')">
            Semua Data
            <span class="badge {{ $statusTab === 'ALL' ? 'bg-white text-secondary' : 'bg-secondary text-white' }} ms-1 rounded-pill">{{ $totalCount }}</span>
          </button>
        </li>
      </ul>

      <!-- Filter Row -->
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Cari nama anggota atau nomor telepon..."
                   wire:model.live.debounce.300ms="search">
          </div>
        </div>
        <div class="col-12 col-md-4">
          <select class="form-select form-select-sm" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-md-3 text-md-end">
          <span class="text-muted small">
            Menampilkan <strong>{{ $followUps->total() }}</strong> anggota
          </span>
        </div>
      </div>
    </div>

    <!-- Table List -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Nama Anggota</th>
              <th>Kelompok COOL</th>
              <th class="text-center">Absen Beruntun</th>
              <th>Status Tindak Lanjut</th>
              <th>Riwayat & Catatan Pastoral</th>
              <th class="text-end" style="min-width: 200px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($followUps as $item)
              <tr>
                <td class="text-muted small">#{{ $item->follow_up_id }}</td>
                <td>
                  <div class="fw-bold text-dark">{{ $item->member->name }}</div>
                  <div class="text-muted small">
                    <i class="bi bi-telephone me-1"></i>{{ $item->member->phone ?? 'Tanpa Nomor HP' }}
                  </div>
                </td>
                <td>
                  <div class="fw-medium text-dark">{{ $item->cool->name ?? ($item->member->coolMembers->first()?->cool->name ?? '-') }}</div>
                  <small class="text-muted">
                    Gembala: {{ $item->cool->shepherd->name ?? ($item->member->coolMembers->first()?->cool->shepherd->name ?? '-') }}
                  </small>
                </td>
                <td class="text-center">
                  <span class="badge bg-danger text-white px-2 py-1">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $item->consecutive_absent_count }}x Berturut-turut
                  </span>
                </td>
                <td>
                  @if ($item->status === 'RESOLVED')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                      <i class="bi bi-check-circle-fill me-1"></i>Sudah Ditangani
                    </span>
                  @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                      <i class="bi bi-clock-history me-1"></i>Belum Ditangani
                    </span>
                  @endif
                </td>
                <td>
                  @if ($item->status === 'RESOLVED')
                    <div>
                      <span class="badge bg-light text-dark border me-1">{{ $item->action_taken ?? 'Ditangani' }}</span>
                      <small class="text-muted">oleh {{ $item->handler->full_name ?? 'Petugas' }} ({{ $item->handled_at ? $item->handled_at->format('d M Y') : '-' }})</small>
                    </div>
                    @if ($item->notes)
                      <p class="small text-muted mb-0 mt-1 fst-italic">
                        "{{ $item->notes }}"
                      </p>
                    @endif
                  @else
                    <span class="text-muted small fst-italic">Belum ada tindakan tercatat</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-1">
                    <!-- WhatsApp Action -->
                    @if (!empty($item->member->phone))
                      @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $item->member->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $coolName = $item->cool->name ?? 'COOL';
                        $msg = "Shalom Sdr/i {$item->member->name}, kami dari {$coolName} merindukan kehadiran Anda. Semoga Sdr/i dalam keadaan sehat & diberkati. Apakah ada pokok doa yang bisa kami doakan bersama?";
                      @endphp
                      <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg) }}" target="_blank"
                         class="btn btn-sm btn-success" title="Sapa via WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                      </a>
                    @endif

                    @if ($item->status === 'PENDING')
                      <!-- Mark as Resolved Button -->
                      <button type="button" class="btn btn-sm btn-outline-success"
                              wire:click="openResolveModal({{ $item->follow_up_id }})">
                        <i class="bi bi-check2-circle me-1"></i>Tandai Selesai
                      </button>
                    @else
                      <!-- Edit Notes Button -->
                      <button type="button" class="btn btn-sm btn-outline-primary"
                              wire:click="openEditModal({{ $item->follow_up_id }})" title="Ubah Catatan Penanganan">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <!-- Reopen Button -->
                      <button type="button" class="btn btn-sm btn-outline-secondary"
                              wire:click="reopenFollowUp({{ $item->follow_up_id }})"
                              wire:confirm="Kembalikan status kasus ini ke BELUM DITANGANI? Anggota akan kembali muncul di peringatan Dashboard dan Statistik."
                              title="Kembalikan ke Belum Ditangani">
                        <i class="bi bi-arrow-counterclockwise"></i>
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-2"></i>
                  @if ($statusTab === 'PENDING')
                    Puji Tuhan! Tidak ada anggota yang memerlukan tindak lanjut pastoral saat ini.
                  @elseif ($statusTab === 'RESOLVED')
                    Belum ada riwayat anggota yang ditandai selesai ditangani.
                  @else
                    Tidak ada data tindak lanjut yang cocok dengan filter pencarian.
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="p-3 border-top">
        {{ $followUps->links() }}
      </div>
    </div>
  </div>

  <!-- Modal Pencatatan Tindak Lanjut Pastoral -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog" style="background-color: rgba(0, 0, 0, 0.55);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header bg-light">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-clipboard2-check-fill text-success me-2"></i>
              Tindak Lanjut Pastoral: {{ $selectedMemberName }}
            </h5>
            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
          </div>
          <form wire:submit="saveFollowUp">
            <div class="modal-body p-4">
              <div class="alert alert-info small py-2 px-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                <div>
                  Menandai anggota ini sebagai <strong>Sudah Ditangani</strong> akan menghapusnya dari daftar peringatan Dashboard dan Statistik.
                </div>
              </div>

              <div class="mb-3">
                <label for="action_taken" class="form-label fw-semibold">Tindakan Pastoral yang Dilakukan <span class="text-danger">*</span></label>
                <select id="action_taken" wire:model="action_taken" class="form-select @error('action_taken') is-invalid @enderror">
                  <option value="WhatsApp / Telepon">WhatsApp / Telepon</option>
                  <option value="Kunjungan Rumah (Home Visit)">Kunjungan Rumah (Home Visit)</option>
                  <option value="Konseling Pastoral">Konseling Pastoral</option>
                  <option value="Doa Bersama / Pelayanan">Doa Bersama / Pelayanan</option>
                  <option value="Pertemuan Pribadi">Pertemuan Pribadi</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
                @error('action_taken') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="notes" class="form-label fw-semibold">Catatan Pastoral / Respon Anggota</label>
                <textarea id="notes" wire:model="notes" rows="4"
                          class="form-control @error('notes') is-invalid @enderror"
                          placeholder="Contoh: Telah dikunjungi dan didoakan. Anggota sedang pemulihan pasca sakit dan berjanji akan hadir kembali minggu depan."></textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text small">Catatan ini tersimpan sebagai riwayat pembinaan pastoral anggota.</div>
              </div>
            </div>

            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
              <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                <span wire:loading.remove><i class="bi bi-check-lg me-1"></i> Simpan Penanganan</span>
                <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Menyimpan...</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
