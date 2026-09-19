<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Kegiatan & Jadwal COOL</h1>
      <p class="page-subtitle">Jadwal pertemuan ibadah, fellowship, dan pendalaman firman kelompok COOL</p>
    </div>
    <button type="button" class="btn btn-success" wire:click="openCreateModal">
      <i class="bi bi-calendar-plus-fill me-1"></i> Jadwalkan Kegiatan
    </button>
  </div>

  <!-- Filter & Search Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari kegiatan, lokasi, topik...">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <select class="form-select" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <select class="form-select" wire:model.live="typeFilter">
            <option value="">Semua Jenis Kegiatan</option>
            @foreach ($activityTypes as $type)
              <option value="{{ $type->activity_type_id }}">{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-md-2">
          <select class="form-select" wire:model.live="statusFilter">
            <option value="">Semua Status</option>
            <option value="SCHEDULED">SCHEDULED</option>
            <option value="COMPLETED">COMPLETED</option>
            <option value="CANCELLED">CANCELLED</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Activities Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Kegiatan & Kelompok</th>
              <th>Jadwal & Waktu</th>
              <th>Lokasi</th>
              <th class="text-center">Kehadiran</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($activities as $act)
              <tr wire:key="act-row-{{ $act->activity_id }}">
                <td>
                  <div class="fw-bold text-dark">{{ $act->name }}</div>
                  <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-light text-dark border">
                      <i class="bi bi-tag-fill me-1 text-success"></i>{{ $act->activityType->name ?? 'Kegiatan' }}
                    </span>
                    <a href="{{ url('/cools/' . $act->cool_id) }}" class="text-decoration-none small text-success fw-medium">
                      {{ $act->cool->name ?? '-' }}
                    </a>
                  </div>
                </td>
                <td>
                  <div class="fw-medium text-dark">
                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                    {{ $act->activity_date ? $act->activity_date->format('d M Y') : '-' }}
                  </div>
                  <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    {{ $act->start_time ? substr((string) $act->start_time, 0, 5) : '19:00' }}
                    @if ($act->end_time)
                      - {{ substr((string) $act->end_time, 0, 5) }} WIB
                    @endif
                  </small>
                </td>
                <td>
                  <div class="text-truncate" style="max-width: 200px;" title="{{ $act->location }}">
                    <i class="bi bi-geo-alt text-danger me-1"></i>{{ $act->location ?: 'Online / TBD' }}
                  </div>
                </td>
                <td class="text-center">
                  @if ($act->total_attendances > 0)
                    <span class="badge bg-success-subtle text-success px-2 py-1">
                      <i class="bi bi-check-circle-fill me-1"></i>{{ $act->present_count }} / {{ $act->total_attendances }}
                    </span>
                  @else
                    <span class="badge bg-secondary-subtle text-muted">Belum ada data</span>
                  @endif
                </td>
                <td>
                  @if ($act->status === 'COMPLETED')
                    <span class="badge bg-success">Selesai</span>
                  @elseif ($act->status === 'CANCELLED')
                    <span class="badge bg-danger">Dibatalkan</span>
                  @else
                    <span class="badge bg-info text-dark">Terjadwal</span>
                  @endif
                </td>
                <td class="text-end text-nowrap">
                  <a href="{{ url('/attendances?activity_id=' . $act->activity_id) }}" class="btn btn-sm btn-success me-1" title="Isi & Kelola Presensi">
                    <i class="bi bi-check2-square me-1"></i> Presensi
                  </a>
                  <a href="{{ url('/materials?activity_id=' . $act->activity_id) }}" class="btn btn-sm btn-outline-primary me-1" title="Materi Pertemuan">
                    <i class="bi bi-file-earmark-text"></i>
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="openEditModal({{ $act->activity_id }})" title="Ubah Kegiatan">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="if(confirm('Apakah Anda yakin ingin menghapus kegiatan \'{{ addslashes($act->name) }}\'?')) { @this.call('deleteActivity', {{ $act->activity_id }}) }"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                  Tidak ada kegiatan COOL yang ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($activities->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $activities->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Tambah / Edit Kegiatan -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="bi bi-calendar-event-fill text-lime me-2"></i>
              {{ $editingActivityId ? 'Ubah Data Kegiatan COOL' : 'Jadwalkan Kegiatan COOL Baru' }}
            </h5>
            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="cool_id" class="form-label fw-semibold">Kelompok COOL <span class="text-danger">*</span></label>
                  <select id="cool_id" wire:model="cool_id" class="form-select @error('cool_id') is-invalid @enderror">
                    <option value="">-- Pilih Kelompok COOL --</option>
                    @foreach ($cools as $c)
                      <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
                    @endforeach
                  </select>
                  @error('cool_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="activity_type_id" class="form-label fw-semibold">Jenis Pertemuan <span class="text-danger">*</span></label>
                  <select id="activity_type_id" wire:model="activity_type_id" class="form-select @error('activity_type_id') is-invalid @enderror">
                    <option value="">-- Pilih Jenis Pertemuan --</option>
                    @foreach ($activityTypes as $type)
                      <option value="{{ $type->activity_type_id }}">{{ $type->name }}</option>
                    @endforeach
                  </select>
                  @error('activity_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="mb-3">
                <label for="act_name" class="form-label fw-semibold">Nama / Tema Kegiatan <span class="text-danger">*</span></label>
                <input type="text" id="act_name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Ibadah COOL - Kasih Karunia yang Melimpah">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label for="activity_date" class="form-label fw-semibold">Tanggal Kegiatan <span class="text-danger">*</span></label>
                  <input type="date" id="activity_date" wire:model="activity_date" class="form-control @error('activity_date') is-invalid @enderror">
                  @error('activity_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="start_time" class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                  <input type="time" id="start_time" wire:model="start_time" class="form-control @error('start_time') is-invalid @enderror">
                  @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="end_time" class="form-label fw-semibold">Waktu Selesai</label>
                  <input type="time" id="end_time" wire:model="end_time" class="form-control">
                </div>
              </div>

              <div class="mb-3">
                <label for="location" class="form-label fw-semibold">Lokasi / Alamat Pertemuan</label>
                <input type="text" id="location" wire:model="location" class="form-control" placeholder="Contoh: Rumah Bp. Budi, Jl. Salemba Raya No. 10 / Zoom Link">
              </div>

              <div class="mb-3">
                <label for="description" class="form-label fw-semibold">Deskripsi / Pokok Doa / Catatan</label>
                <textarea id="description" wire:model="description" class="form-control" rows="3" placeholder="Rundown acara, pokok doa bersama, atau catatan persiapan..."></textarea>
              </div>

              <div class="mb-3">
                <label for="status" class="form-label fw-semibold">Status Pelaksanaan</label>
                <select id="status" wire:model="status" class="form-select">
                  <option value="SCHEDULED">SCHEDULED (Terjadwal)</option>
                  <option value="COMPLETED">COMPLETED (Selesai)</option>
                  <option value="CANCELLED">CANCELLED (Dibatalkan)</option>
                </select>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
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
