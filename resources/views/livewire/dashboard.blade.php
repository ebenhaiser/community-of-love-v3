<div>
  <!-- Page Header -->
  <div class="page-header">
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

  <!-- Quick Info Stat Cards Row -->
  <div class="row g-3 g-md-4 mb-4">
    <!-- Stat Card 1: Green Alert Banner -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card alert-green-card h-100">
        <div class="position-relative z-index-2">
          <span class="alert-green-badge">Aktif</span>
          <div class="alert-green-date">{{ now()->translatedFormat('d F Y') }}</div>
          <div class="alert-green-text">{{ $totalCools }} Kelompok COOL Beroperasi</div>
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

    <!-- Stat Card 2: Gembala -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card card-stat h-100 p-3">
        <div class="card-header border-0 p-0 mb-2">
          <span class="stat-label">Gembala COOL</span>
        </div>
        <div class="stat-value">{{ $totalShepherds }}</div>
        <div class="trend-badge trend-up mt-2">
          <i class="bi bi-person-check-fill"></i>
          <span>Gembala aktif membina</span>
        </div>
      </div>
    </div>

    <!-- Stat Card 3: Anggota -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card card-stat h-100 p-3">
        <div class="card-header border-0 p-0 mb-2">
          <span class="stat-label">Anggota Jemaat</span>
        </div>
        <div class="stat-value">{{ $totalMembers }}</div>
        <div class="trend-badge trend-up mt-2">
          <i class="bi bi-people-fill"></i>
          <span>Terdaftar di kelompok</span>
        </div>
      </div>
    </div>

    <!-- Stat Card 4: Persentase Kehadiran -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card card-stat h-100 p-3">
        <div class="card-header border-0 p-0 mb-2">
          <span class="stat-label">Rata-Rata Kehadiran</span>
        </div>
        <div class="stat-value text-success">{{ $attendanceRate }}%</div>
        <div class="trend-badge trend-up mt-2">
          <i class="bi bi-bar-chart-fill"></i>
          <span>{{ $presentCount }} hadir dari total {{ $presentCount + $absentCount + $excusedCount + $sickCount }} record</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Content Grid -->
  <div class="row g-4">
    <!-- Left Column: Daftar Kelompok COOL -->
    <div class="col-12 col-lg-7">
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill text-success me-2"></i>Kelompok COOL</h5>
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
                  <tr wire:key="cool-{{ $cool->cool_id }}">
                    <td><span class="badge bg-light text-dark font-monospace border">{{ $cool->cool_code }}</span></td>
                    <td class="fw-semibold">{{ $cool->name }}</td>
                    <td>{{ $cool->shepherd->name ?? '-' }}</td>
                    <td class="text-center">
                      <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">
                        {{ $cool->members->count() }} Orang
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
                    <td colspan="5" class="text-center text-muted py-4">Belum ada kelompok COOL.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Jadwal Kegiatan Terkini -->
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
                  <th class="text-end">Presensi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($recentActivities as $act)
                  <tr wire:key="act-{{ $act->activity_id }}">
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
                    <td class="text-end">
                      <a href="{{ url('/attendances?activity_id=' . $act->activity_id) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check2-square me-1"></i> Buka
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada kegiatan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Alert Ketidakhadiran & Akses Cepat -->
    <div class="col-12 col-lg-5">
      <!-- Card Peringatan Ketidakhadiran (PRD FR-11 & FR-12) -->
      <div class="card border-warning shadow-sm mb-4">
        <div class="card-header bg-warning-subtle text-warning-emphasis fw-bold py-3 d-flex justify-content-between align-items-center">
          <span><i class="bi bi-exclamation-triangle-fill me-2"></i>Perlu Perhatian & Follow-up</span>
          <div class="d-flex align-items-center gap-1">
            <a href="{{ url('/follow-ups') }}" class="btn btn-xs btn-outline-warning text-dark py-0 px-2 fw-semibold" style="font-size: 11px;" title="Kelola Master Pastoral Follow-up">
              Kelola Master <i class="bi bi-arrow-right"></i>
            </a>
            <span class="badge bg-warning text-dark rounded-pill">Sistem Alert</span>
          </div>
        </div>
        <div class="card-body">
          <p class="small text-muted mb-3">
            Anggota berikut terdeteksi memiliki ketidakhadiran berturut-turut pada pertemuan COOL terakhir:
          </p>
          <div class="list-group list-group-flush">
            @forelse ($flaggedMembers as $flagged)
              <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center" wire:key="flag-{{ $flagged->member_id }}">
                <div>
                  <div class="fw-semibold text-dark">{{ $flagged->name }}</div>
                  <small class="text-danger"><i class="bi bi-x-circle me-1"></i>Tidak hadir berturut-turut ({{ $flagged->phone ?? 'Tanpa kontak' }})</small>
                </div>
                <div class="d-flex gap-1">
                  @if (!empty($flagged->phone))
                    @php
                      $cleanPhone = preg_replace('/[^0-9]/', '', $flagged->phone);
                      if (str_starts_with($cleanPhone, '0')) {
                          $cleanPhone = '62' . substr($cleanPhone, 1);
                      }
                      $msg = "Shalom Sdr/i {$flagged->name}, kami dari COOL " . ($flagged->cool_name ?? 'COOL') . " GBI Salemba merindukan kehadiran Anda. Semoga Sdr/i dalam keadaan sehat & diberkati. Apakah ada pokok doa yang bisa kami doakan bersama?";
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg) }}" target="_blank" class="btn btn-sm btn-success" title="Sapa via WhatsApp">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                  @else
                    <span class="badge bg-secondary-subtle text-muted align-self-center">No HP Kosong</span>
                  @endif
                  <a href="{{ url('/follow-ups') }}" class="btn btn-sm btn-outline-danger" title="Buka di Master Follow-up">
                    <i class="bi bi-clipboard2-check"></i>
                  </a>
                </div>
              </div>
            @empty
              <div class="text-muted small py-2">
                <i class="bi bi-check-circle-fill text-success me-1"></i> Tidak ada anggota yang memerlukan tindak lanjut khusus saat ini.
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Card Akses Cepat QR Code (PRD FR-14) -->
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
            Anggota dapat mengakses halaman informasi COOL dan mengirim pesan melalui pemindaian QR Code dan verifikasi PIN aman.
          </p>
          <a href="{{ url('/qr-access') }}" class="btn btn-outline-success btn-sm px-4">
            <i class="bi bi-gear-fill me-1"></i> Kelola Token QR & PIN
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
