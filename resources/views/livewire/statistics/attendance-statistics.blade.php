<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Statistik & Laporan Kehadiran</h1>
      <p class="page-subtitle">Analisis tingkat partisipasi jemaat dan pemantauan pastoral care COOL GBI Salemba</p>
    </div>
  </div>

  <!-- Filter Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label fw-semibold text-muted small">Kelompok COOL</label>
          <select class="form-select" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold text-muted small">Dari Tanggal</label>
          <input type="date" class="form-control" wire:model.live="startDate">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold text-muted small">Sampai Tanggal</label>
          <input type="date" class="form-control" wire:model.live="endDate">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold text-muted small">Ambang Absen Beruntun</label>
          <select class="form-select" wire:model.live="consecutiveThreshold">
            <option value="2">&ge; 2 Kali Berturut-turut</option>
            <option value="3">&ge; 3 Kali Berturut-turut</option>
            <option value="4">&ge; 4 Kali Berturut-turut</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex align-items-center">
          <div class="p-3 bg-primary-subtle text-primary rounded-circle me-3">
            <i class="bi bi-calendar2-check-fill fs-3"></i>
          </div>
          <div>
            <div class="text-muted small">Total Kegiatan</div>
            <div class="fs-4 fw-bold text-dark">{{ $totalActivities }}</div>
            <small class="text-muted">Pada periode terpilih</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex align-items-center">
          <div class="p-3 bg-success-subtle text-success rounded-circle me-3">
            <i class="bi bi-people-fill fs-3"></i>
          </div>
          <div>
            <div class="text-muted small">Total Hadir</div>
            <div class="fs-4 fw-bold text-success">{{ $totalPresent }}</div>
            <small class="text-muted">Jemaat hadir</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex align-items-center">
          <div class="p-3 bg-danger-subtle text-danger rounded-circle me-3">
            <i class="bi bi-person-x-fill fs-3"></i>
          </div>
          <div>
            <div class="text-muted small">Tidak Hadir (Absen)</div>
            <div class="fs-4 fw-bold text-danger">{{ $totalAbsent }}</div>
            <small class="text-muted">Izin/Sakit: {{ $totalExcused + $totalSick }}</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body d-flex align-items-center">
          <div class="p-3 bg-info-subtle text-info rounded-circle me-3">
            <i class="bi bi-pie-chart-fill fs-3"></i>
          </div>
          <div class="w-100">
            <div class="text-muted small">Tingkat Kehadiran</div>
            <div class="fs-4 fw-bold text-primary">{{ $overallRate }}%</div>
            <div class="progress mt-1" style="height: 6px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $overallRate }}%;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Section: Pastoral Care / Consecutive Absence Alert -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-danger-subtle py-3 border-0">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="card-title fw-bold text-danger mb-0">
            <i class="bi bi-heart-pulse-fill me-2"></i> Perhatian Pastoral Care: Anggota Tidak Hadir Beruntun
          </h5>
          <p class="text-muted small mb-0 mt-1">Anggota yang tidak hadir &ge; {{ $consecutiveThreshold }} kali berturut-turut pada pertemuan COOL dan membutuhkan kunjungan / sapaan gembala.</p>
        </div>
        <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">
          {{ count($absentAlerts) }} Perlu Perhatian
        </span>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nama Anggota</th>
              <th>Kelompok COOL</th>
              <th>Gembala Pembina</th>
              <th class="text-center">Absen Beruntun</th>
              <th>Terakhir Hadir</th>
              <th class="text-end">Aksi Pastoral</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($absentAlerts as $alert)
              <tr>
                <td>
                  <div class="fw-bold text-dark">{{ $alert['name'] }}</div>
                  <small class="text-muted">{{ $alert['phone'] ?? 'Tanpa Nomor HP' }}</small>
                </td>
                <td>
                  <span class="badge bg-light text-dark border">{{ $alert['cool_name'] }}</span>
                </td>
                <td>{{ $alert['shepherd_name'] }}</td>
                <td class="text-center">
                  <span class="badge bg-danger text-white px-2 py-1">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $alert['consecutive_absent'] }}x Berturut-turut
                  </span>
                </td>
                <td>
                  <span class="text-muted small">
                    <i class="bi bi-clock-history me-1"></i> {{ $alert['last_attended'] }}
                  </span>
                </td>
                <td class="text-end">
                  @if (!empty($alert['phone']))
                    @php
                      $cleanPhone = preg_replace('/[^0-9]/', '', $alert['phone']);
                      if (str_starts_with($cleanPhone, '0')) {
                          $cleanPhone = '62' . substr($cleanPhone, 1);
                      }
                      $msg = "Shalom Sdr/i {$alert['name']}, kami dari COOL {$alert['cool_name']} GBI Salemba merindukan kehadiran Anda. Semoga Sdr/i dalam keadaan sehat & diberkati. Apakah ada pokok doa yang bisa kami doakan bersama?";
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg) }}" target="_blank" class="btn btn-sm btn-success">
                      <i class="bi bi-whatsapp me-1"></i> Sapa via WhatsApp
                    </a>
                  @else
                    <span class="badge bg-secondary-subtle text-muted">No HP Kosong</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-success py-4">
                  <i class="bi bi-check-circle-fill fs-2 d-block mb-2 text-success"></i>
                  Puji Tuhan! Tidak ada anggota yang absen lebih dari {{ $consecutiveThreshold }} kali berturut-turut.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Section: Rincian Kegiatan -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-0">
      <h5 class="card-title fw-bold text-dark mb-0">Rincian Kehadiran Per Kegiatan</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Kegiatan COOL</th>
              <th>Tanggal</th>
              <th>Kelompok</th>
              <th class="text-center">Hadir</th>
              <th class="text-center">Tidak Hadir</th>
              <th class="text-center">Izin / Sakit</th>
              <th style="width: 200px;">Tingkat Kehadiran</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($activityBreakdown as $row)
              <tr>
                <td>
                  <div class="fw-semibold text-dark">{{ $row['name'] }}</div>
                </td>
                <td>
                  <span class="text-muted small">{{ $row['date'] }}</span>
                </td>
                <td>
                  <span class="badge bg-light text-dark border">{{ $row['cool_name'] }}</span>
                </td>
                <td class="text-center fw-bold text-success">{{ $row['present'] }}</td>
                <td class="text-center fw-bold text-danger">{{ $row['absent'] }}</td>
                <td class="text-center fw-bold text-warning">{{ $row['excused'] + $row['sick'] }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <span class="small fw-bold me-2">{{ $row['rate'] }}%</span>
                    <div class="progress flex-grow-1" style="height: 6px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: {{ $row['rate'] }}%;"></div>
                    </div>
                  </div>
                </td>
                <td class="text-end">
                  <a href="{{ url('/attendances?activity_id=' . $row['activity_id']) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-eye me-1"></i> Lihat Presensi
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="bi bi-bar-chart fs-2 d-block mb-2 text-secondary"></i>
                  Tidak ada kegiatan COOL pada rentang tanggal yang dipilih.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
