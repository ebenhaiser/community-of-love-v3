<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Event Gereja Lintas COOL</h1>
      <p class="page-subtitle">Koordinasi kegiatan gabungan, ibadah raya COOL, retreat, dan seminar GBI Salemba</p>
    </div>
    @if (!$isShepherd)
      <button type="button" class="btn btn-success" wire:click="openCreateModal">
        <i class="bi bi-calendar2-plus-fill me-1"></i> Buat Event Baru
      </button>
    @endif
  </div>

  <!-- Filter Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-7">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari event gereja berdasarkan judul, kode, atau lokasi...">
          </div>
        </div>
        <div class="col-12 col-md-5">
          <select class="form-select" wire:model.live="statusFilter">
            <option value="">Semua Status Pelaksanaan</option>
            <option value="SCHEDULED">SCHEDULED (Terjadwal)</option>
            <option value="ONGOING">ONGOING (Berlangsung)</option>
            <option value="COMPLETED">COMPLETED (Selesai)</option>
            <option value="CANCELLED">CANCELLED (Dibatalkan)</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Events Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Kode & Nama Event</th>
              <th>Jadwal Pelaksanaan</th>
              <th>Lokasi</th>
              <th>Kelompok COOL Terdaftar</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($events as $evt)
              <tr wire:key="evt-row-{{ $evt->event_id }}">
                <td>
                  <span class="badge bg-light text-dark border font-monospace me-1">{{ $evt->event_code }}</span>
                  <div class="fw-bold text-dark mt-1">{{ $evt->name }}</div>
                  @if ($evt->description)
                    <small class="text-muted text-truncate d-inline-block" style="max-width: 280px;">
                      {{ $evt->description }}
                    </small>
                  @endif
                </td>
                <td>
                  <div class="fw-medium text-dark">
                    <i class="bi bi-calendar3 me-1 text-success"></i>
                    {{ $evt->event_date ? $evt->event_date->format('d M Y') : '-' }}
                  </div>
                  <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    {{ $evt->start_time ? substr((string) $evt->start_time, 0, 5) : '09:00' }}
                    @if ($evt->end_time)
                      - {{ substr((string) $evt->end_time, 0, 5) }} WIB
                    @endif
                  </small>
                </td>
                <td>
                  <div class="text-truncate" style="max-width: 220px;">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $evt->location ?: 'GBI Salemba Main Hall' }}
                  </div>
                </td>
                <td>
                  @if ($evt->cools->isNotEmpty())
                    <div class="d-flex flex-wrap gap-1" style="max-width: 250px;">
                      @foreach ($evt->cools as $cool)
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem;">
                          {{ $cool->name }}
                        </span>
                      @endforeach
                    </div>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary">Semua COOL Terbuka</span>
                  @endif
                </td>
                <td>
                  @if ($evt->status === 'COMPLETED')
                    <span class="badge bg-success">Selesai</span>
                  @elseif ($evt->status === 'ONGOING')
                    <span class="badge bg-primary">Berlangsung</span>
                  @elseif ($evt->status === 'CANCELLED')
                    <span class="badge bg-danger">Dibatalkan</span>
                  @else
                    <span class="badge bg-info text-dark">Terjadwal</span>
                  @endif
                </td>
                <td class="text-end text-nowrap">
                  @if (!$isShepherd)
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="openEditModal({{ $evt->event_id }})" title="Ubah Event">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                      onclick="if(confirm('Hapus event \'{{ addslashes($evt->name) }}\'?')) { @this.call('deleteEvent', {{ $evt->event_id }}) }"
                      title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  @else
                    <span class="badge bg-light text-muted border">Informasi Event</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-calendar-event fs-1 d-block mb-2 text-secondary"></i>
                  Tidak ada event gereja lintas COOL yang ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($events->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $events->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Tambah / Edit Event -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="bi bi-buildings-fill text-lime me-2"></i>
              {{ $editingEventId ? 'Ubah Data Event Gereja' : 'Buat Event Gereja Lintas COOL Baru' }}
            </h5>
            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body">
              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label for="event_code" class="form-label fw-semibold">Kode Event <span class="text-danger">*</span></label>
                  <input type="text" id="event_code" wire:model="event_code" class="form-control @error('event_code') is-invalid @enderror" placeholder="EVT-2026-001">
                  @error('event_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                  <label for="event_name" class="form-label fw-semibold">Nama Event <span class="text-danger">*</span></label>
                  <input type="text" id="event_name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Kebaktian Padang & Fellowship Gabungan COOL">
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label for="event_date" class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                  <input type="date" id="event_date" wire:model="event_date" class="form-control @error('event_date') is-invalid @enderror">
                  @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="event_start_time" class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                  <input type="time" id="event_start_time" wire:model="start_time" class="form-control @error('start_time') is-invalid @enderror">
                  @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="event_end_time" class="form-label fw-semibold">Waktu Selesai</label>
                  <input type="time" id="event_end_time" wire:model="end_time" class="form-control">
                </div>
              </div>

              <div class="mb-3">
                <label for="event_location" class="form-label fw-semibold">Lokasi Pelaksanaan</label>
                <input type="text" id="event_location" wire:model="location" class="form-control" placeholder="Contoh: Sanctuary GBI Salemba / Wisma Kinasih, Bogor">
              </div>

              <div class="mb-3">
                <label for="event_description" class="form-label fw-semibold">Deskripsi & Rundown Acara</label>
                <textarea id="event_description" wire:model="description" class="form-control" rows="3" placeholder="Rincian acara, dresscode, konsumsi, dan target kehadiran..."></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Kelompok COOL yang Diundang / Berpartisipasi</label>
                <div class="p-3 border rounded bg-light" style="max-height: 150px; overflow-y: auto;">
                  <div class="row g-2">
                    @foreach ($allCools as $c)
                      <div class="col-md-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="{{ $c->cool_id }}" id="cool_chk_{{ $c->cool_id }}" wire:model="selectedCoolIds">
                          <label class="form-check-label small" for="cool_chk_{{ $c->cool_id }}">
                            {{ $c->name }}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="event_status" class="form-label fw-semibold">Status Event</label>
                <select id="event_status" wire:model="status" class="form-select">
                  <option value="SCHEDULED">SCHEDULED (Terjadwal)</option>
                  <option value="ONGOING">ONGOING (Sedang Berlangsung)</option>
                  <option value="COMPLETED">COMPLETED (Selesai)</option>
                  <option value="CANCELLED">CANCELLED (Dibatalkan)</option>
                </select>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
              <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                <span wire:loading.remove><i class="bi bi-check-lg me-1"></i> Simpan Event</span>
                <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Menyimpan...</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
