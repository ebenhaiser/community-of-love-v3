<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Materi & Dokumen Pertemuan</h1>
      <p class="page-subtitle">Distribusi bahan khotbah, slide sharing, panduan renungan, dan materi ibadah COOL</p>
    </div>
    <button type="button" class="btn btn-success" wire:click="openUploadModal">
      <i class="bi bi-cloud-upload-fill me-1"></i> Tambah Materi Baru
    </button>
  </div>

  <!-- Filter Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari materi atau topik...">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <select class="form-select" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <select class="form-select" wire:model.live="activity_id">
            <option value="">Semua Pertemuan / Kegiatan</option>
            @foreach ($activities as $act)
              <option value="{{ $act->activity_id }}">
                {{ $act->activity_date ? $act->activity_date->format('d/m/Y') : '' }} - {{ $act->name }} ({{ $act->cool->name ?? '-' }})
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Materials Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Materi & Tipe</th>
              <th>Kegiatan & Kelompok</th>
              <th>Deskripsi</th>
              <th>Ukuran / Sumber</th>
              <th>Diunggah Oleh</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($materials as $mat)
              <tr wire:key="mat-row-{{ $mat->material_id }}">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="p-2 rounded me-3
                      @if($mat->material_type === 'LINK') bg-primary-subtle text-primary
                      @elseif($mat->material_type === 'IMAGE') bg-info-subtle text-info
                      @else bg-danger-subtle text-danger @endif">
                      @if($mat->material_type === 'LINK')
                        <i class="bi bi-link-45deg fs-4"></i>
                      @elseif($mat->material_type === 'IMAGE')
                        <i class="bi bi-image fs-4"></i>
                      @elseif($mat->material_type === 'VIDEO')
                        <i class="bi bi-camera-video fs-4"></i>
                      @else
                        <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                      @endif
                    </div>
                    <div>
                      <div class="fw-bold text-dark">{{ $mat->file_name }}</div>
                      <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">
                        {{ $mat->material_type }}
                      </span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="fw-semibold text-dark">{{ $mat->activity->name ?? '-' }}</div>
                  <small class="text-success">{{ $mat->activity->cool->name ?? '-' }}</small>
                </td>
                <td>
                  <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                    {{ $mat->description ?: '-' }}
                  </small>
                </td>
                <td>
                  @if ($mat->material_type === 'LINK')
                    <span class="badge bg-light text-primary border">External URL</span>
                  @elseif ($mat->file_size)
                    <span class="text-muted small">{{ round($mat->file_size / 1024, 1) }} KB</span>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>
                <td>
                  <div class="small fw-medium">{{ $mat->uploader->full_name ?? 'Admin' }}</div>
                  <div class="text-muted small">{{ $mat->date_uploaded ? $mat->date_uploaded->format('d M Y H:i') : '-' }}</div>
                </td>
                <td class="text-end text-nowrap">
                  @if ($mat->material_type === 'LINK' && $mat->external_url)
                    <a href="{{ $mat->external_url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Buka Tautan">
                      <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                    </a>
                  @elseif ($mat->file_path)
                    <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success me-1" title="Unduh / Lihat File">
                      <i class="bi bi-download me-1"></i> Unduh
                    </a>
                  @endif
                  <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="if(confirm('Apakah Anda yakin ingin menghapus materi \'{{ addslashes($mat->file_name) }}\'?')) { @this.call('deleteMaterial', {{ $mat->material_id }}) }"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                  Tidak ada materi atau dokumen yang cocok dengan filter saat ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($materials->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $materials->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Tambah Materi -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="bi bi-file-earmark-text-fill text-lime me-2"></i>
              Unggah Materi Pertemuan COOL
            </h5>
            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body">
              <div class="mb-3">
                <label for="modal_activity_id" class="form-label fw-semibold">Kegiatan COOL <span class="text-danger">*</span></label>
                <select id="modal_activity_id" wire:model="modal_activity_id" class="form-select @error('modal_activity_id') is-invalid @enderror">
                  <option value="">-- Pilih Kegiatan COOL --</option>
                  @foreach ($activities as $act)
                    <option value="{{ $act->activity_id }}">
                      {{ $act->activity_date ? $act->activity_date->format('d/m/Y') : '' }} - {{ $act->name }} ({{ $act->cool->name ?? '-' }})
                    </option>
                  @endforeach
                </select>
                @error('modal_activity_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Materi <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" id="type_doc" value="DOCUMENT" wire:model.live="material_type">
                    <label class="form-check-label" for="type_doc">File Dokumen (PDF, PPTX, Doc)</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" id="type_link" value="LINK" wire:model.live="material_type">
                    <label class="form-check-label" for="type_link">Tautan Web (URL)</label>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="file_name" class="form-label fw-semibold">Judul / Nama Materi <span class="text-danger">*</span></label>
                <input type="text" id="file_name" wire:model="file_name" class="form-control @error('file_name') is-invalid @enderror" placeholder="Contoh: Renungan Kasih Karunia Minggu ke-3">
                @error('file_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              @if ($material_type === 'LINK')
                <div class="mb-3">
                  <label for="external_url" class="form-label fw-semibold">Link URL Eksternal <span class="text-danger">*</span></label>
                  <input type="url" id="external_url" wire:model="external_url" class="form-control @error('external_url') is-invalid @enderror" placeholder="https://drive.google.com/... atau https://youtube.com/...">
                  @error('external_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              @else
                <div class="mb-3">
                  <label for="file_upload" class="form-label fw-semibold">Pilih File <span class="text-danger">*</span></label>
                  <input type="file" id="file_upload" wire:model="file_upload" class="form-control @error('file_upload') is-invalid @enderror">
                  <div class="form-text small">Maksimal ukuran file: 10 MB (PDF, Docx, PPTX, gambar).</div>
                  @error('file_upload') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div wire:loading wire:target="file_upload" class="text-success small mt-1">
                    <i class="bi bi-arrow-repeat spin"></i> Mengunggah file ke server...
                  </div>
                </div>
              @endif

              <div class="mb-3">
                <label for="description" class="form-label fw-semibold">Deskripsi / Catatan Tambahan</label>
                <textarea id="description" wire:model="description" class="form-control" rows="2" placeholder="Catatan panduan bagi fasilitator atau jemaat..."></textarea>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
              <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                <span wire:loading.remove><i class="bi bi-cloud-arrow-up me-1"></i> Simpan Materi</span>
                <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Menyimpan...</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
