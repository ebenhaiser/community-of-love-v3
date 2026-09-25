<div>
  <!-- Page Header -->
  <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h1 class="page-title mb-1">
        <i class="bi bi-people-fill text-success me-2"></i>Data Jemaat GBI Salemba
      </h1>
      <p class="page-subtitle text-muted mb-0">
        Pengelolaan basis data jemaat gereja dan integrasi kelompok Community of Love (COOL)
      </p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-success shadow-sm" wire:click="openCreateModal">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Jemaat
      </button>
    </div>
  </div>

  <!-- Quick Overview Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100 rounded-3">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
            <i class="bi bi-people fs-4"></i>
          </div>
          <div>
            <div class="text-muted small fw-medium">Total Jemaat</div>
            <div class="fs-4 fw-bold text-dark">{{ number_format($stats['total']) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100 rounded-3">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
            <i class="bi bi-heart-pulse-fill fs-4"></i>
          </div>
          <div>
            <div class="text-muted small fw-medium">Sudah Ber-COOL</div>
            <div class="fs-4 fw-bold text-primary">
              {{ number_format($stats['in_cool']) }}
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle small ms-1" style="font-size: 0.72rem;">
                {{ $stats['total'] > 0 ? round(($stats['in_cool'] / $stats['total']) * 100) : 0 }}%
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100 rounded-3">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-warning-subtle text-warning-emphasis p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
            <i class="bi bi-person-exclamation fs-4"></i>
          </div>
          <div>
            <div class="text-muted small fw-medium">Belum Ber-COOL</div>
            <div class="fs-4 fw-bold text-warning-emphasis">{{ number_format($stats['not_cool'] ?? $stats['not_in_cool']) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100 rounded-3">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
            <i class="bi bi-mortarboard-fill fs-4"></i>
          </div>
          <div>
            <div class="text-muted small fw-medium">Mengikuti KOM</div>
            <div class="fs-4 fw-bold text-info">{{ number_format($stats['ikut_kom']) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search Toolbar -->
  <div class="card shadow-sm border-0 mb-4 rounded-3">
    <div class="card-body">
      <!-- Search Input -->
      <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-lg-5">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0" wire:model.live.debounce.300ms="search"
              placeholder="Cari nama, ID jemaat, no HP, alamat, medsos...">
          </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
          <select class="form-select" wire:model.live="coolStatusFilter" title="Filter Keikutsertaan COOL">
            <option value="">Semua Status COOL</option>
            <option value="in_cool">Sudah Ber-COOL</option>
            <option value="not_in_cool">Belum Ber-COOL</option>
          </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
          <select class="form-select" wire:model.live="coolFilter" title="Filter Kelompok COOL">
            <option value="">Semua Kelompok</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
          <select class="form-select" wire:model.live="komFilter" title="Filter Status KOM">
            <option value="">Semua Status KOM</option>
            <option value="Belum KOM">Belum KOM</option>
            <option value="KOM 100">KOM 100</option>
            <option value="KOM 200">KOM 200</option>
            <option value="KOM 300">KOM 300</option>
            <option value="KOM 400">KOM 400</option>
          </select>
        </div>
      </div>

      <!-- Secondary Filters -->
      <div class="row g-2 align-items-center pt-2 border-top">
        <div class="col-6 col-md-3 col-lg-2">
          <select class="form-select form-select-sm" wire:model.live="maritalFilter" title="Filter Status Pernikahan">
            <option value="">Semua Status Nikah</option>
            <option value="Belum Menikah">Belum Menikah</option>
            <option value="Menikah">Menikah</option>
            <option value="Janda / Duda">Janda / Duda</option>
          </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
          <select class="form-select form-select-sm" wire:model.live="genderFilter" title="Filter Jenis Kelamin">
            <option value="">Semua Gender</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
          <select class="form-select form-select-sm" wire:model.live="statusFilter" title="Filter Status Keaktifan">
            <option value="">Semua Status Aktif</option>
            <option value="ACTIVE">Aktif</option>
            <option value="NEW">Jemaat Baru</option>
            <option value="MOVED">Pindah</option>
            <option value="INACTIVE">Non-Aktif</option>
          </select>
        </div>
        <div class="col-12 col-md-3 col-lg-6 text-md-end text-start">
          <span class="badge bg-light text-secondary border px-3 py-2">
            Menampilkan <strong>{{ $members->total() }}</strong> Data Jemaat
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Members Table -->
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="min-width: 200px;">Jemaat</th>
              <th>Gender & Tgl Lahir</th>
              <th style="min-width: 170px;">Kontak & Medsos</th>
              <th>Status KOM</th>
              <th>Status COOL</th>
              <th>Status Nikah</th>
              <th class="text-end" style="min-width: 130px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($members as $member)
              <tr wire:key="mbr-row-{{ $member->member_id }}">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold text-white shadow-sm"
                         style="width: 40px; height: 40px; background: {{ $member->gender === 'Perempuan' ? 'linear-gradient(135deg, #ec4899, #f43f5e)' : 'linear-gradient(135deg, #0d9488, #10b981)' }}; font-size: 0.85rem;">
                      {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div>
                      <div class="fw-semibold text-dark">{{ $member->name }}</div>
                      <div class="d-flex align-items-center gap-1 mt-1">
                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.72rem;">
                          {{ $member->member_code }}
                        </span>
                        @if ($member->status === 'NEW')
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.7rem;">Baru</span>
                        @elseif ($member->status === 'INACTIVE' || ! $member->is_active)
                          <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">Non-Aktif</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="small fw-medium text-dark">
                    <i class="bi {{ $member->gender === 'Perempuan' ? 'bi-gender-female text-danger' : 'bi-gender-male text-primary' }} me-1"></i>
                    {{ $member->gender ?? '-' }}
                  </div>
                  <div class="text-muted small">
                    @if ($member->birthdate)
                      <i class="bi bi-cake2 me-1"></i>{{ $member->birthdate->format('d M Y') }}
                      @php
                        $age = $member->birthdate->age;
                      @endphp
                      <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem;">{{ $age }} thn</span>
                    @elseif ($member->birthplace)
                      <i class="bi bi-geo-alt me-1"></i>{{ $member->birthplace }}
                    @else
                      -
                    @endif
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
                      <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-decoration-none text-success fw-medium small d-inline-flex align-items-center gap-1" title="Kirim WhatsApp">
                        <i class="bi bi-whatsapp text-success"></i>{{ $member->phone }}
                      </a>
                    @else
                      <span class="text-muted small">-</span>
                    @endif
                  </div>
                  @if ($member->social_media)
                    <div class="text-primary small text-truncate" style="max-width: 160px;" title="Media Sosial: {{ $member->social_media }}">
                      <i class="bi bi-instagram me-1"></i>{{ $member->social_media }}
                    </div>
                  @endif
                </td>
                <td>
                  @php
                    $komClass = match($member->kom_status) {
                      'KOM 400' => 'bg-danger-subtle text-danger-emphasis border border-danger-subtle',
                      'KOM 300' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                      'KOM 200' => 'bg-info-subtle text-info border border-info-subtle',
                      'KOM 100' => 'bg-primary-subtle text-primary border border-primary-subtle',
                      default => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                    };
                  @endphp
                  <span class="badge {{ $komClass }} px-2 py-1">
                    <i class="bi bi-mortarboard me-1"></i>{{ $member->kom_status ?? 'Belum KOM' }}
                  </span>
                </td>
                <td>
                  @if ($member->is_in_cool && $member->cools->isNotEmpty())
                    @foreach ($member->cools as $cool)
                      <a href="{{ url('/cools/' . $cool->cool_id) }}" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none py-1 px-2 d-inline-flex align-items-center gap-1" title="Lihat Kelompok COOL">
                        <i class="bi bi-heart-pulse-fill text-success"></i> {{ $cool->name }}
                      </a>
                    @endforeach
                  @elseif ($member->is_in_cool)
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                      <i class="bi bi-check-circle-fill me-1"></i>Sudah Ber-COOL
                    </span>
                  @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                      <i class="bi bi-dash-circle me-1"></i>Belum Ber-COOL
                    </span>
                  @endif
                </td>
                <td>
                  <div class="small fw-medium text-dark">
                    {{ $member->marital_status ?? 'Belum Menikah' }}
                  </div>
                  <div class="text-muted small" style="font-size: 0.72rem;">
                    Gabung: {{ $member->join_date ? $member->join_date->format('d/m/Y') : '-' }}
                  </div>
                </td>
                <td class="text-end text-nowrap">
                  <!-- Tombol Detail -->
                  <button type="button" class="btn btn-sm btn-outline-info me-1" wire:click="openDetailModal({{ $member->member_id }})" title="Lihat Profil Jemaat">
                    <i class="bi bi-eye"></i>
                  </button>
                  <!-- Tombol Edit -->
                  <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="openEditModal({{ $member->member_id }})" title="Ubah Data">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <!-- Tombol Hapus -->
                  <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="if(confirm('Apakah Anda yakin ingin menghapus data jemaat \'{{ addslashes($member->name) }}\'?')) { @this.call('deleteMember', {{ $member->member_id }}) }"
                    title="Hapus Jemaat">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-5">
                  <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                  Tidak ada data jemaat yang cocok dengan pencarian / filter yang dipilih.
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

  <!-- Modal Tambah / Edit Jemaat -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog" style="background-color: rgba(0,0,0,0.5);">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
          <div class="modal-header bg-light">
            <h5 class="modal-title fw-bold">
              <i class="bi bi-person-vcard text-success me-2"></i>
              {{ $editingMemberId ? 'Ubah Data Jemaat' : 'Tambah Data Jemaat GBI Salemba' }}
            </h5>
            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit="save">
            <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
              <!-- Section 1: Identitas Pribadi -->
              <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom text-dark">
                  <i class="bi bi-person-badge-fill text-success fs-5"></i>
                  <h6 class="fw-bold mb-0">1. Identitas Jemaat</h6>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="member_code" class="form-label fw-semibold small">No ID Jemaat <span class="text-danger">*</span></label>
                    <input type="text" id="member_code" wire:model="member_code" class="form-control @error('member_code') is-invalid @enderror" placeholder="JMT-2026-0001">
                    @error('member_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="join_date" class="form-label fw-semibold small">Tanggal Bergabung <span class="text-danger">*</span></label>
                    <input type="date" id="join_date" wire:model="join_date" class="form-control @error('join_date') is-invalid @enderror">
                    @error('join_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-12">
                    <label for="name" class="form-label fw-semibold small">Nama Lengkap Jemaat <span class="text-danger">*</span></label>
                    <input type="text" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Samuel Alexander">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="gender" class="form-label fw-semibold small">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select id="gender" wire:model="gender" class="form-select @error('gender') is-invalid @enderror">
                      <option value="Laki-laki">Laki-laki</option>
                      <option value="Perempuan">Perempuan</option>
                    </select>
                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="marital_status" class="form-label fw-semibold small">Status Pernikahan <span class="text-danger">*</span></label>
                    <select id="marital_status" wire:model="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                      <option value="Belum Menikah">Belum Menikah</option>
                      <option value="Menikah">Menikah</option>
                      <option value="Janda / Duda">Janda / Duda</option>
                    </select>
                    @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="birthplace" class="form-label fw-semibold small">Tempat Lahir</label>
                    <input type="text" id="birthplace" wire:model="birthplace" class="form-control @error('birthplace') is-invalid @enderror" placeholder="Contoh: Jakarta">
                    @error('birthplace') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="birthdate" class="form-label fw-semibold small">Tanggal Lahir</label>
                    <input type="date" id="birthdate" wire:model="birthdate" class="form-control @error('birthdate') is-invalid @enderror">
                    @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

              <!-- Section 2: Kontak & Domisili -->
              <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom text-dark">
                  <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                  <h6 class="fw-bold mb-0">2. Kontak & Domisili</h6>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="phone" class="form-label fw-semibold small">Nomor HP / WhatsApp</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                      <input type="text" id="phone" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="081234567890">
                    </div>
                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                      <input type="email" id="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="jemaat@email.com">
                    </div>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="social_media" class="form-label fw-semibold small">Media Sosial (Instagram / Lainnya)</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="bi bi-instagram text-danger"></i></span>
                      <input type="text" id="social_media" wire:model="social_media" class="form-control @error('social_media') is-invalid @enderror" placeholder="@username">
                    </div>
                    @error('social_media') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold small">Status Keanggotaan</label>
                    <select id="status" wire:model.live="status" class="form-select">
                      <option value="ACTIVE">Aktif (ACTIVE)</option>
                      <option value="NEW">Jemaat Baru (NEW)</option>
                      <option value="MOVED">Pindah (MOVED)</option>
                      <option value="INACTIVE">Non-Aktif (INACTIVE)</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <label for="address" class="form-label fw-semibold small">Alamat Tempat Tinggal</label>
                    <textarea id="address" wire:model="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Jl. Salemba No. ..."></textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>

              <!-- Section 3: Pembinaan Rohani (KOM) & Komunitas COOL -->
              <div class="mb-2">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom text-dark">
                  <i class="bi bi-heart-pulse-fill text-success fs-5"></i>
                  <h6 class="fw-bold mb-0">3. Pembinaan Rohani & Keanggotaan COOL</h6>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="kom_status" class="form-label fw-semibold small">Status Kelas KOM (GBI Salemba) <span class="text-danger">*</span></label>
                    <select id="kom_status" wire:model="kom_status" class="form-select @error('kom_status') is-invalid @enderror">
                      <option value="Belum KOM">Belum KOM</option>
                      <option value="KOM 100">KOM 100 (Dasar Keselamatan & Hidup Baru)</option>
                      <option value="KOM 200">KOM 200 (Doktrin & Pengajaran)</option>
                      <option value="KOM 300">KOM 300 (Kepemimpinan & Pelayanan)</option>
                      <option value="KOM 400">KOM 400 (Tugas Penggembalaan)</option>
                    </select>
                    @error('kom_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text small">Kelas Orientasi Melayani / Pembinaan jemaat GBI Salemba.</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold small d-block">Keikutsertaan Komunitas COOL</label>
                    <div class="card p-3 border rounded-3 bg-light">
                      <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="is_in_cool" wire:model.live="is_in_cool">
                        <label class="form-check-label fw-semibold" for="is_in_cool">
                          Sudah Bergabung dalam COOL
                        </label>
                      </div>
                      <div class="small text-muted">
                        {{ $is_in_cool ? 'Pilih kelompok COOL tempat jemaat ini bertumbuh.' : 'Jemaat ini belum tercatat dalam kelompok COOL manapun.' }}
                      </div>
                    </div>
                  </div>

                  @if ($is_in_cool)
                    <div class="col-12">
                      <label for="cool_id" class="form-label fw-semibold small">
                        Pilih Kelompok COOL <span class="text-danger">*</span>
                      </label>
                      <select id="cool_id" wire:model.live="cool_id" class="form-select @error('cool_id') is-invalid @enderror">
                        <option value="">-- Pilih Kelompok COOL --</option>
                        @foreach ($cools as $c)
                          <option value="{{ $c->cool_id }}">{{ $c->name }} (Gembala: {{ $c->shepherd->name ?? '-' }})</option>
                        @endforeach
                      </select>
                      @error('cool_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      <div class="form-text small text-success">
                        <i class="bi bi-info-circle me-1"></i>Jemaat akan otomatis terhubung ke kelompok COOL ini untuk presensi dan pastoral care.
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
              <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
              <button type="submit" class="btn btn-success px-4" wire:loading.attr="disabled">
                <span wire:loading.remove><i class="bi bi-check-lg me-1"></i> Simpan Data Jemaat</span>
                <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Menyimpan...</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal Detail Jemaat (Profile View) -->
  @if ($showDetailModal && $detailMember)
    <div class="modal fade show d-block" tabindex="-1" aria-modal="true" role="dialog" style="background-color: rgba(0,0,0,0.5);">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title fw-bold text-white">
              <i class="bi bi-person-bounding-box me-2"></i>Detail Profil Jemaat GBI Salemba
            </h5>
            <button type="button" class="btn-close btn-close-white" wire:click="closeDetailModal"></button>
          </div>
          <div class="modal-body p-4">
            <!-- Header Card Profil -->
            <div class="d-flex flex-wrap align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
              <div class="avatar rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm"
                   style="width: 64px; height: 64px; font-size: 1.4rem; background: {{ $detailMember->gender === 'Perempuan' ? 'linear-gradient(135deg, #ec4899, #f43f5e)' : 'linear-gradient(135deg, #0d9488, #10b981)' }};">
                {{ strtoupper(substr($detailMember->name, 0, 2)) }}
              </div>
              <div class="flex-grow-1">
                <h4 class="fw-bold mb-1 text-dark">{{ $detailMember->name }}</h4>
                <div class="d-flex flex-wrap align-items-center gap-2">
                  <span class="badge bg-secondary font-monospace">{{ $detailMember->member_code }}</span>
                  <span class="badge bg-light text-dark border">
                    <i class="bi {{ $detailMember->gender === 'Perempuan' ? 'bi-gender-female text-danger' : 'bi-gender-male text-primary' }} me-1"></i>
                    {{ $detailMember->gender ?? '-' }}
                  </span>
                  <span class="badge bg-light text-dark border">
                    <i class="bi bi-ring me-1"></i>{{ $detailMember->marital_status ?? 'Belum Menikah' }}
                  </span>
                </div>
              </div>
              <div>
                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="openEditModal({{ $detailMember->member_id }})">
                  <i class="bi bi-pencil me-1"></i>Edit Data
                </button>
              </div>
            </div>

            <!-- Detail Grid Info -->
            <div class="row g-4">
              <!-- Kolom Kiri: Kontak & Domisili -->
              <div class="col-md-6">
                <div class="card border h-100 rounded-3">
                  <div class="card-header bg-white fw-bold py-2 border-bottom small text-muted text-uppercase">
                    <i class="bi bi-telephone-fill text-success me-1"></i> Kontak & Domisili
                  </div>
                  <div class="card-body">
                    <ul class="list-unstyled mb-0">
                      <li class="mb-3">
                        <div class="text-muted small">No. HP / WhatsApp</div>
                        <div class="fw-semibold text-dark">
                          @if ($detailMember->phone)
                            @php
                              $cleanPhone = preg_replace('/[^0-9]/', '', $detailMember->phone);
                              if (str_starts_with($cleanPhone, '0')) {
                                  $cleanPhone = '62' . substr($cleanPhone, 1);
                              }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-success text-decoration-none">
                              <i class="bi bi-whatsapp me-1"></i>{{ $detailMember->phone }}
                            </a>
                          @else
                            -
                          @endif
                        </div>
                      </li>
                      <li class="mb-3">
                        <div class="text-muted small">Alamat Email</div>
                        <div class="fw-semibold text-dark">{{ $detailMember->email ?: '-' }}</div>
                      </li>
                      <li class="mb-3">
                        <div class="text-muted small">Media Sosial</div>
                        <div class="fw-semibold text-primary">
                          {{ $detailMember->social_media ? $detailMember->social_media : '-' }}
                        </div>
                      </li>
                      <li>
                        <div class="text-muted small">Alamat Lengkap</div>
                        <div class="fw-semibold text-dark">{{ $detailMember->address ?: '-' }}</div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Kolom Kanan: Rohani & Komunitas COOL -->
              <div class="col-md-6">
                <div class="card border h-100 rounded-3">
                  <div class="card-header bg-white fw-bold py-2 border-bottom small text-muted text-uppercase">
                    <i class="bi bi-heart-pulse-fill text-success me-1"></i> Pembinaan & Komunitas
                  </div>
                  <div class="card-body">
                    <ul class="list-unstyled mb-0">
                      <li class="mb-3">
                        <div class="text-muted small">Status Kelas KOM GBI Salemba</div>
                        <div class="mt-1">
                          <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-6">
                            <i class="bi bi-mortarboard me-1"></i>{{ $detailMember->kom_status ?? 'Belum KOM' }}
                          </span>
                        </div>
                      </li>
                      <li class="mb-3">
                        <div class="text-muted small">Status Komunitas COOL</div>
                        <div class="mt-1">
                          @if ($detailMember->is_in_cool)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                              <i class="bi bi-check-circle-fill me-1"></i>Sudah Terdaftar di COOL
                            </span>
                          @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                              <i class="bi bi-dash-circle me-1"></i>Belum Terdaftar di COOL
                            </span>
                          @endif
                        </div>
                      </li>
                      <li class="mb-3">
                        <div class="text-muted small">Kelompok COOL</div>
                        <div class="fw-semibold text-dark mt-1">
                          @if ($detailMember->cools->isNotEmpty())
                            @foreach ($detailMember->cools as $cool)
                              <a href="{{ url('/cools/' . $cool->cool_id) }}" class="badge bg-success text-white text-decoration-none py-2 px-3">
                                <i class="bi bi-people-fill me-1"></i>{{ $cool->name }}
                              </a>
                            @endforeach
                          @else
                            <span class="text-muted">- Belum ditempatkan -</span>
                          @endif
                        </div>
                      </li>
                      <li>
                        <div class="text-muted small">Kelahiran</div>
                        <div class="fw-semibold text-dark">
                          {{ $detailMember->birthplace ? $detailMember->birthplace . ', ' : '' }}
                          {{ $detailMember->birthdate ? $detailMember->birthdate->format('d F Y') . ' (' . $detailMember->birthdate->age . ' tahun)' : '-' }}
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light px-4 py-3">
            <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
