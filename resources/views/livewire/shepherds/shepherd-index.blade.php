<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Gembala COOL</h1>
      <p class="page-subtitle">Kelola data pembina dan penanggung jawab kelompok COOL GBI Salemba</p>
    </div>
    <button type="button" class="btn btn-success" wire:click="openCreateModal">
      <i class="bi bi-plus-lg me-1"></i> Tambah Gembala Baru
    </button>
  </div>

  <!-- Search Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari berdasarkan nama, nomor HP, atau email...">
          </div>
        </div>
        <div class="col-md-6 text-end">
          <span class="text-muted small">Total: <strong>{{ $shepherds->total() }}</strong> Gembala</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Shepherd List Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nama Gembala</th>
              <th>No. Telepon / WhatsApp</th>
              <th>Email</th>
              <th>Kelompok COOL yang Dibina</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($shepherds as $shep)
              <tr wire:key="row-shep-{{ $shep->shepherd_id }}">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-2 rounded-circle me-3">
                      <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div>
                      <span class="fw-semibold text-dark">{{ $shep->name }}</span>
                    </div>
                  </div>
                </td>
                <td>
                  @if ($shep->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shep->phone) }}" target="_blank" class="text-decoration-none text-success">
                      <i class="bi bi-whatsapp me-1"></i> {{ $shep->phone }}
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>{{ $shep->email ?? '-' }}</td>
                <td>
                  @if ($shep->cools->count() > 0)
                    @foreach ($shep->cools as $c)
                      <span class="badge bg-light text-dark border me-1">{{ $c->name }}</span>
                    @endforeach
                  @else
                    <span class="text-muted small">Belum ada kelompok</span>
                  @endif
                </td>
                <td>
                  @if ($shep->is_active)
                    <span class="badge bg-success">Aktif</span>
                  @else
                    <span class="badge bg-secondary">Non-Aktif</span>
                  @endif
                </td>
                <td class="text-end">
                  <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="openEditModal({{ $shep->shepherd_id }})" title="Ubah">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="if(confirm('Apakah Anda yakin ingin menghapus data Gembala ini? Histori kelompok akan tetap tersimpan.')) { @this.call('deleteShepherd', {{ $shep->shepherd_id }}) }"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                  Tidak ada data Gembala yang ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($shepherds->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $shepherds->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Tambah / Edit Gembala -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">
              {{ $editingShepherdId ? 'Ubah Data Gembala COOL' : 'Tambah Gembala Baru' }}
            </h5>
            <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body">
              <div class="mb-3">
                <label for="shep_name" class="form-label fw-semibold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                <input type="text" id="shep_name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Ps. Budi Santoso">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="shep_phone" class="form-label fw-semibold">No. Telepon / WhatsApp</label>
                <input type="text" id="shep_phone" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Contoh: 081234567890">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label for="shep_email" class="form-label fw-semibold">Alamat Email</label>
                <input type="email" id="shep_email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="Contoh: budi.santoso@gbisalemba.org">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="shep_is_active" wire:model="is_active">
                <label class="form-check-label" for="shep_is_active">Status Gembala Aktif</label>
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
