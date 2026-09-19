<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Data Anggota COOL</h1>
      <p class="page-subtitle">Kelola anggota jemaat dan penempatan kelompok Community of Love (COOL)</p>
    </div>
    <button type="button" class="btn btn-success" wire:click="openCreateModal">
      <i class="bi bi-person-plus-fill me-1"></i> Tambah Anggota
    </button>
  </div>

  <!-- Filter & Search Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari nama, ID anggota, no HP, email...">
          </div>
        </div>
        <div class="col-6 col-md-3">
          <select class="form-select" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select class="form-select" wire:model.live="statusFilter">
            <option value="">Semua Status</option>
            <option value="ACTIVE">Aktif</option>
            <option value="NEW">Baru</option>
            <option value="MOVED">Pindah</option>
            <option value="INACTIVE">Non-Aktif</option>
          </select>
        </div>
        <div class="col-12 col-md-2 text-md-end text-start">
          <span class="badge bg-light text-secondary border px-3 py-2">
            <i class="bi bi-people me-1 text-success"></i> <strong>{{ $members->total() }}</strong> Anggota
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Members Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID & Nama Anggota</th>
              <th>Kontak (HP / WhatsApp)</th>
              <th>Kelompok COOL</th>
              <th>Tgl Bergabung</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($members as $member)
              <tr wire:key="mbr-row-{{ $member->member_id }}">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; font-weight: 600;">
                      {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div>
                      <div class="fw-semibold text-dark">{{ $member->name }}</div>
                      <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.75rem;">
                        {{ $member->member_code }}
                      </span>
                    </div>
                  </div>
                </td>
                <td>
                  <div>
                    @if ($member->phone)
                      @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $member->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                      @endphp
                      <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-decoration-none text-success fw-medium" title="Kirim Pesan WhatsApp">
                        <i class="bi bi-whatsapp me-1"></i>{{ $member->phone }}
                      </a>
                    @else
                      <span class="text-muted small">-</span>
                    @endif
                  </div>
                  @if ($member->email)
                    <div class="text-muted small">
                      <i class="bi bi-envelope me-1"></i>{{ $member->email }}
                    </div>
                  @endif
                </td>
                <td>
                  @if ($member->cools->isNotEmpty())
                    @foreach ($member->cools as $cool)
                      <a href="{{ url('/cools/' . $cool->cool_id) }}" class="badge bg-success-subtle text-success text-decoration-none py-1 px-2 mb-1 d-inline-block">
                        <i class="bi bi-people-fill me-1"></i>{{ $cool->name }}
                      </a>
                    @endforeach
                  @else
                    <span class="badge bg-secondary-subtle text-secondary">Belum Ditugaskan</span>
                  @endif
                </td>
                <td>
                  <span class="text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>{{ $member->join_date ? $member->join_date->format('d M Y') : '-' }}
                  </span>
                </td>
                <td>
                  @if ($member->status === 'ACTIVE')
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                      <i class="bi bi-check-circle-fill me-1"></i>Aktif
                    </span>
                  @elseif ($member->status === 'NEW')
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      <i class="bi bi-person-plus-fill me-1"></i>Baru
                    </span>
                  @elseif ($member->status === 'MOVED')
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                      <i class="bi bi-arrow-left-right me-1"></i>Pindah
                    </span>
                  @elseif ($member->status === 'INACTIVE' || ! $member->is_active)
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                      <i class="bi bi-dash-circle me-1"></i>Non-Aktif
                    </span>
                  @else
                    <span class="badge bg-light text-dark border">
                      {{ $member->status }}
                    </span>
                  @endif
                </td>
                <td class="text-end text-nowrap">
                  <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="openEditModal({{ $member->member_id }})" title="Ubah Data">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="if(confirm('Apakah Anda yakin ingin menghapus data anggota \'{{ addslashes($member->name) }}\'?')) { @this.call('deleteMember', {{ $member->member_id }}) }"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                  Tidak ada data anggota yang cocok dengan pencarian / filter.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($members->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $members->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Tambah / Edit Anggota -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="bi bi-person-fill text-lime me-2"></i>
              {{ $editingMemberId ? 'Ubah Data Anggota' : 'Tambah Anggota COOL Baru' }}
            </h5>
            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body">
              <div class="row g-2 mb-3">
                <div class="col-md-6">
                  <label for="member_code" class="form-label fw-semibold">No ID Anggota <span class="text-danger">*</span></label>
                  <input type="text" id="member_code" wire:model="member_code" class="form-control @error('member_code') is-invalid @enderror" placeholder="MBR-2026-0001">
                  @error('member_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="join_date" class="form-label fw-semibold">Tgl Bergabung <span class="text-danger">*</span></label>
                  <input type="date" id="join_date" wire:model="join_date" class="form-control @error('join_date') is-invalid @enderror">
                  @error('join_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Samuel Alexander">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="row g-2 mb-3">
                <div class="col-md-6">
                  <label for="phone" class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                  <input type="text" id="phone" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="081234567890">
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Alamat Email</label>
                  <input type="email" id="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@domain.com">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="mb-3">
                <label for="cool_id" class="form-label fw-semibold">Penempatan Kelompok COOL</label>
                <select id="cool_id" wire:model="cool_id" class="form-select @error('cool_id') is-invalid @enderror">
                  <option value="">-- Pilih Kelompok COOL --</option>
                  @foreach ($cools as $c)
                    <option value="{{ $c->cool_id }}">{{ $c->name }} (Gembala: {{ $c->shepherd->name ?? '-' }})</option>
                  @endforeach
                </select>
                @error('cool_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Anggota akan otomatis terdaftar pada presensi kegiatan kelompok COOL ini.</div>
              </div>

              <div class="row g-2 mb-2">
                <div class="col-md-6">
                  <label for="status" class="form-label fw-semibold">Status Keanggotaan</label>
                  <select id="status" wire:model.live="status" class="form-select">
                    <option value="ACTIVE">Aktif (ACTIVE)</option>
                    <option value="NEW">Baru (NEW)</option>
                    <option value="MOVED">Pindah (MOVED)</option>
                    <option value="INACTIVE">Non-Aktif (INACTIVE)</option>
                  </select>
                </div>
                <div class="col-md-6 d-flex align-items-center mt-4">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" wire:model.live="is_active">
                    <label class="form-check-label fw-semibold" for="is_active">Status Aktif Sistem</label>
                  </div>
                </div>
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
