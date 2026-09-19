<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Manajemen Akses QR & PIN Jemaat</h1>
      <p class="page-subtitle">Pintu gerbang mandiri jemaat COOL melalui scan QR Code dan PIN 6-digit tanpa perlu akun/login rumit</p>
    </div>
  </div>

  <!-- QR Cards Grid -->
  <div class="row g-4">
    @forelse ($cools as $cool)
      @php
        $qr = $cool->qrAccess;
        $portalUrl = $qr ? url('/c/' . $qr->qr_token) : null;
      @endphp
      <div class="col-12 col-md-6 col-lg-4" wire:key="qr-card-{{ $cool->cool_id }}">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
            <span class="badge bg-light text-dark border font-monospace">{{ $cool->cool_code }}</span>
            <span class="badge bg-success">Aktif</span>
          </div>
          <div class="card-body text-center pt-2">
            <h5 class="fw-bold text-dark mb-1">{{ $cool->name }}</h5>
            <p class="text-muted small mb-3">Gembala: {{ $cool->shepherd->name ?? '-' }}</p>

            @if ($qr)
              <div class="p-3 bg-light rounded-3 d-inline-block mb-3 border">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($portalUrl) }}"
                  alt="QR Code {{ $cool->name }}" class="img-fluid rounded" style="width: 160px; height: 160px;">
              </div>

              <div class="mb-3 text-start">
                <label class="form-label text-muted small fw-semibold mb-1">Tautan Portal Jemaat</label>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control font-monospace" value="{{ $portalUrl }}" readonly id="url-{{ $cool->cool_id }}">
                  <button class="btn btn-outline-secondary" type="button"
                    onclick="navigator.clipboard.writeText('{{ $portalUrl }}'); alert('Tautan berhasil disalin ke clipboard!');" title="Salin Tautan">
                    <i class="bi bi-clipboard"></i>
                  </button>
                  <a href="{{ $portalUrl }}" target="_blank" class="btn btn-outline-success" title="Buka Portal">
                    <i class="bi bi-box-arrow-up-right"></i>
                  </a>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center text-muted small mb-3 border-top pt-2">
                <span>Kode Akses: <strong class="text-dark">{{ $qr->access_code }}</strong></span>
                <span>PIN: <span class="badge bg-secondary-subtle text-dark">6 Digit Terproteksi</span></span>
              </div>

              <div class="d-grid gap-2 d-flex justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openPinModal({{ $qr->qr_access_id }})">
                  <i class="bi bi-key-fill me-1"></i> Ganti PIN
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary"
                  onclick="if(confirm('Apakah Anda yakin ingin memperbarui Token QR untuk {{ addslashes($cool->name) }}? Token lama tidak akan bisa diakses lagi.')) { @this.call('regenerateToken', {{ $qr->qr_access_id }}) }"
                  title="Regenerate Token">
                  <i class="bi bi-arrow-clockwise"></i> Reset QR
                </button>
              </div>
            @else
              <div class="py-4 text-muted">
                <i class="bi bi-qr-code text-secondary fs-1 d-block mb-2"></i>
                <p class="small mb-3">Kelompok ini belum memiliki QR Code akses mandiri.</p>
                <button type="button" class="btn btn-sm btn-success" wire:click="generateMissingQr({{ $cool->cool_id }})">
                  <i class="bi bi-plus-circle me-1"></i> Buat Akses QR & PIN
                </button>
              </div>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-5">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        Tidak ada data kelompok COOL.
      </div>
    @endforelse
  </div>

  <!-- Modal Ganti PIN -->
  @if ($showPinModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header">
            <h6 class="modal-title fw-bold">
              <i class="bi bi-key-fill text-lime me-2"></i>
              Ganti PIN Akses COOL
            </h6>
            <button type="button" class="btn-close" wire:click="$set('showPinModal', false)"></button>
          </div>
          <form wire:submit="updatePin">
            <div class="modal-body text-center">
              <p class="small text-muted mb-3">Kelompok: <strong>{{ $selectedCoolName }}</strong></p>
              <div class="mb-3">
                <label for="newPin" class="form-label fw-semibold">Masukkan 6 Digit PIN Baru</label>
                <input type="password" id="newPin" wire:model="newPin" maxlength="6"
                  class="form-control form-control-lg text-center font-monospace tracking-wider @error('newPin') is-invalid @enderror"
                  placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" autofocus>
                @error('newPin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text small">Bagikan PIN ini kepada anggota kelompok COOL Anda.</div>
              </div>
            </div>
            <div class="modal-footer bg-light p-2">
              <button type="button" class="btn btn-sm btn-secondary" wire:click="$set('showPinModal', false)">Batal</button>
              <button type="submit" class="btn btn-sm btn-success" wire:loading.attr="disabled">
                Simpan PIN Baru
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
