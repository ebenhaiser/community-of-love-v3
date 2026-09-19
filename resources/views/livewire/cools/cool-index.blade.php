<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Kelompok COOL</h1>
      <p class="page-subtitle">Kelola kelompok Community of Love (COOL) GBI Salemba</p>
    </div>
    @if (!$isShepherd)
      <button type="button" class="btn btn-success" wire:click="openCreateModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kelompok COOL
      </button>
    @endif
  </div>

  <!-- Filter & Search Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari berdasarkan nama COOL atau kode...">
          </div>
        </div>
        @if (!$isShepherd)
          <div class="col-md-4">
            <select class="form-select" wire:model.live="shepherdFilter">
              <option value="">Semua Gembala</option>
              @foreach ($shepherds as $shep)
                <option value="{{ $shep->shepherd_id }}">{{ $shep->name }}</option>
              @endforeach
            </select>
          </div>
        @endif
        <div class="col-md-2 text-end">
          <span class="text-muted small">Total: <strong>{{ $cools->total() }}</strong> kelompok</span>
        </div>
      </div>
    </div>
  </div>

  <!-- COOL List Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Kode COOL</th>
              <th>Nama Kelompok</th>
              <th>Gembala Pembina</th>
              <th class="text-center">Jumlah Anggota</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($cools as $cool)
              <tr wire:key="row-cool-{{ $cool->cool_id }}">
                <td>
                  <span class="badge bg-light text-dark border font-monospace">{{ $cool->cool_code }}</span>
                </td>
                <td>
                  <div class="fw-semibold text-dark">{{ $cool->name }}</div>
                  @if ($cool->description)
                    <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                      {{ $cool->description }}
                    </small>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge text-success me-2 fs-5"></i>
                    <div>
                      <div class="fw-medium">{{ $cool->shepherd->name ?? '-' }}</div>
                      <small class="text-muted">{{ $cool->shepherd->phone ?? '' }}</small>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">
                    {{ $cool->members->count() }} Anggota
                  </span>
                </td>
                <td>
                  @if ($cool->is_active)
                    <span class="badge bg-success">Aktif</span>
                  @else
                    <span class="badge bg-secondary">Non-Aktif</span>
                  @endif
                </td>
                <td class="text-end">
                  <a href="{{ url('/cools/' . $cool->cool_id) }}" class="btn btn-sm btn-outline-primary me-1" title="Lihat Detail">
                    <i class="bi bi-eye"></i> Detail
                  </a>
                  @if (!$isShepherd)
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="openEditModal({{ $cool->cool_id }})" title="Ubah">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                      onclick="if(confirm('Apakah Anda yakin ingin menghapus kelompok COOL ini? Histori data akan tetap aman tersimpan.')) { @this.call('deleteCool', {{ $cool->cool_id }}) }"
                      title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                  Tidak ada data kelompok COOL yang ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($cools->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $cools->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Tambah / Edit COOL -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">
              {{ $editingCoolId ? 'Ubah Data Kelompok COOL' : 'Tambah Kelompok COOL Baru' }}
            </h5>
            <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body">
              <div class="mb-3">
                <label for="cool_code" class="form-label fw-semibold">Kode COOL <span class="text-danger">*</span></label>
                <input type="text" id="cool_code" wire:model="cool_code" class="form-control @error('cool_code') is-invalid @enderror" placeholder="Contoh: COOL-SLM-004">
                @error('cool_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Kelompok COOL <span class="text-danger">*</span></label>
                <input type="text" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: COOL Salemba 04 - Harmony">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="shepherd_id" class="form-label fw-semibold">Gembala Pembina <span class="text-danger">*</span></label>
                <select id="shepherd_id" wire:model="shepherd_id" class="form-select @error('shepherd_id') is-invalid @enderror">
                  <option value="">-- Pilih Gembala COOL --</option>
                  @foreach ($shepherds as $shep)
                    <option value="{{ $shep->shepherd_id }}">{{ $shep->name }} ({{ $shep->phone ?? 'Tanpa HP' }})</option>
                  @endforeach
                </select>
                @error('shepherd_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="description" class="form-label fw-semibold">Deskripsi / Keterangan</label>
                <textarea id="description" wire:model="description" class="form-control" rows="3" placeholder="Informasi profil kelompok, target jemaat, dll."></textarea>
              </div>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="is_active" wire:model="is_active">
                <label class="form-check-label" for="is_active">Status Kelompok Aktif</label>
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
