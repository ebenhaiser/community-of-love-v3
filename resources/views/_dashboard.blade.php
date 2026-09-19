@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('page-header')
  <div class="page-header px-4">
    <div>
      <h1 class="page-title">Dashboard Manajemen COOL</h1>
      <p class="page-subtitle">Sistem Informasi & Manajemen Komunitas Community of Love (COOL) GBI Salemba</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-success px-3 py-2 fs-6 rounded-pill">
        <i class="bi bi-shield-check me-1"></i> Mode: {{ auth()->user()->role->name ?? 'MASTER ADMIN' }}
      </span>
    </div>
  </div>
@endsection

@section('content')
  <!-- TOP AREA: Quick Stats Row -->
  <div class="row g-4 mb-4">
    <!-- Stat Card 1: Green Banner -->
    <div class="col-xl-3 col-md-6">
      <div class="card alert-green-card h-100">
        <div class="position-relative z-index-2">
          <span class="alert-green-badge">Aktif</span>
          <div class="alert-green-date">{{ now()->translatedFormat('d F Y') }}</div>
          <div class="alert-green-text">{{ $totalCools ?? 3 }} Kelompok COOL Beroperasi</div>
        </div>
        <a href="{{ url('/cools') }}" class="alert-green-link z-index-2">
          <span>Kelola COOL</span>
          <i class="bi bi-arrow-right"></i>
        </a>
        <svg class="alert-green-bg-shape" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g transform="translate(50,50)">
            <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" />
            <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" transform="rotate(60)" />
            <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" transform="rotate(120)" />
          </g>
        </svg>
      </div>
    </div>

    <!-- Stat Card 2: Total Gembala -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-stat h-100 p-3">
        <div class="card-header border-0 p-0 mb-2">
          <span class="stat-label">Gembala COOL</span>
          <div class="dropdown">
            <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
              <li><a class="dropdown-item" href="{{ url('/shepherds') }}"><i class="bi bi-eye"></i> Lihat Semua</a></li>
            </ul>
          </div>
        </div>
        <div class="stat-value">{{ $totalShepherds ?? 3 }}</div>
        <div class="trend-badge trend-up mt-2">
          <i class="bi bi-person-check-fill"></i>
          <span>Gembala aktif membina</span>
        </div>
      </div>
    </div>

    <!-- Stat Card 3: Total Anggota -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-stat h-100 p-3">
        <div class="card-header border-0 p-0 mb-2">
          <span class="stat-label">Anggota Jemaat</span>
          <div class="dropdown">
            <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
              <li><a class="dropdown-item" href="{{ url('/members') }}"><i class="bi bi-eye"></i> Lihat Semua</a></li>
            </ul>
          </div>
        </div>
        <div class="stat-value">{{ $totalMembers ?? 10 }}</div>
        <div class="trend-badge trend-up mt-2">
          <i class="bi bi-people-fill"></i>
          <span>Terdaftar di kelompok</span>
        </div>
      </div>
    </div>

    <!-- Stat Card 4: Total Kegiatan -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-stat h-100 p-3">
        <div class="card-header border-0 p-0 mb-2">
          <span class="stat-label">Total Pertemuan / Kegiatan</span>
          <div class="dropdown">
            <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
              <li><a class="dropdown-item" href="{{ url('/activities') }}"><i class="bi bi-eye"></i> Lihat Semua</a></li>
            </ul>
          </div>
        </div>
        <div class="stat-value">{{ $totalActivities ?? 11 }}</div>
        <div class="trend-badge trend-up mt-2">
          <i class="bi bi-calendar2-check-fill"></i>
          <span>Jadwal tercatat</span>
        </div>
      </div>
    </div>
  </div>

  <!-- MIDDLE AREA: Content Grid -->
  <div class="row g-4">
    <!-- Left Column: Daftar Kelompok COOL -->
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill text-success me-2"></i>Kelompok COOL Aktif</h5>
          <a href="{{ url('/cools') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Kode</th>
                  <th>Nama Kelompok</th>
                  <th>Gembala</th>
                  <th class="text-center">Anggota</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($cools as $cool)
                  <tr>
                    <td><span class="badge bg-light text-dark font-monospace border">{{ $cool->cool_code }}</span></td>
                    <td class="fw-semibold">{{ $cool->name }}</td>
                    <td>{{ $cool->shepherd->name ?? '-' }}</td>
                    <td class="text-center">
                      <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">
                        {{ $cool->members_count ?? $cool->members->count() }} Orang
                      </span>
                    </td>
                    <td class="text-end">
                      <a href="{{ url('/cools/' . $cool->cool_id) }}" class="btn btn-sm btn-light border" title="Detail">
                        <i class="bi bi-chevron-right"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada data COOL.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kegiatan Terbaru / Mendatang -->
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-event-fill text-primary me-2"></i>Jadwal Kegiatan Terkini</h5>
          <a href="{{ url('/activities') }}" class="btn btn-sm btn-outline-primary">Semua Jadwal</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Kegiatan</th>
                  <th>Kelompok</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($recentActivities as $act)
                  <tr>
                    <td>
                      <div class="fw-semibold">{{ $act->name }}</div>
                      <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $act->location ?? 'Online / Rumah Jemaat' }}</small>
                    </td>
                    <td><span class="badge bg-secondary-subtle text-dark">{{ $act->cool->name ?? '-' }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($act->activity_date)->format('d M Y') }}</td>
                    <td>
                      @if ($act->status === 'COMPLETED')
                        <span class="badge bg-success-subtle text-success">Selesai</span>
                      @elseif ($act->status === 'SCHEDULED')
                        <span class="badge bg-primary-subtle text-primary">Terjadwal</span>
                      @else
                        <span class="badge bg-light text-muted">{{ $act->status }}</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada kegiatan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Status & Follow-up Alert Card -->
    <div class="col-lg-5">
      <!-- Card Peringatan Ketidakhadiran (Feature PRD FR-11 & FR-12) -->
      <div class="card border-warning shadow-sm mb-4">
        <div class="card-header bg-warning-subtle text-warning-emphasis fw-bold py-3 d-flex justify-content-between align-items-center">
          <span><i class="bi bi-exclamation-triangle-fill me-2"></i>Perlu Perhatian & Follow-up</span>
          <span class="badge bg-warning text-dark rounded-pill">Sistem Alert</span>
        </div>
        <div class="card-body">
          <p class="small text-muted mb-3">
            Berikut adalah anggota yang terdeteksi memiliki ketidakhadiran berturut-turut pada pertemuan COOL terakhir:
          </p>
          <div class="list-group list-group-flush">
            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold text-dark">Budi Gunawan</div>
                <small class="text-danger"><i class="bi bi-x-circle me-1"></i>Tidak hadir 4x berturut-turut</small>
              </div>
              <span class="badge bg-danger text-white rounded-pill px-3 py-1">Follow-up</span>
            </div>
            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold text-dark">Daniel Christian</div>
                <small class="text-warning-emphasis"><i class="bi bi-clock-history me-1"></i>Izin lembur 2x pertemuan</small>
              </div>
              <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1">Pantau</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Akses Cepat QR Code (Feature PRD FR-14) -->
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="mb-0 fw-bold"><i class="bi bi-qr-code-scan text-success me-2"></i>Akses Halaman Anggota</h5>
        </div>
        <div class="card-body text-center py-4">
          <div class="mb-3">
            <div class="d-inline-block p-3 border rounded-3 bg-light">
              <i class="bi bi-qr-code display-4 text-success"></i>
            </div>
          </div>
          <h6 class="fw-bold mb-1">Akses QR Code & PIN Kelompok</h6>
          <p class="small text-muted mb-3">
            Anggota dapat mengakses halaman informasi COOL dan mengirim pesan melalui pemindaian QR Code dan input PIN aman.
          </p>
          <a href="{{ url('/qr-access') }}" class="btn btn-outline-success btn-sm px-4">
            <i class="bi bi-gear-fill me-1"></i> Kelola Token QR & PIN
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
