<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Presensi & Absensi Kegiatan COOL</h1>
      <p class="page-subtitle">Pencatatan kehadiran anggota secara real-time pada setiap ibadah atau pertemuan COOL</p>
    </div>
    @if ($currentActivity && $members->isNotEmpty())
      <button type="button" class="btn btn-outline-success"
        onclick="if(confirm('Apakah Anda yakin ingin menandai semua anggota terdaftar sebagai Hadir?')) { @this.call('markAllPresent') }">
        <i class="bi bi-check-all me-1"></i> Tandai Semua Hadir
      </button>
    @endif
  </div>



  <!-- Selection Bar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted small">Filter Berdasarkan COOL</label>
          <select class="form-select" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-8">
          <label class="form-label fw-semibold text-muted small">Pilih Kegiatan COOL <span class="text-danger">*</span></label>
          <select class="form-select fw-semibold" wire:model.live="activity_id">
            <option value="">-- Pilih Kegiatan COOL --</option>
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

  @if ($currentActivity)
    <!-- Activity Details & Summary Stat Cards -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
      <div class="card-body">
        <div class="row g-3 align-items-center">
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-1">
              <span class="badge bg-success me-2">{{ $currentActivity->cool->name ?? '-' }}</span>
              <span class="badge bg-light text-dark border">{{ $currentActivity->activityType->name ?? 'Kegiatan' }}</span>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ $currentActivity->name }}</h4>
            <div class="text-muted small">
              <i class="bi bi-calendar3 me-1"></i>{{ $currentActivity->activity_date ? $currentActivity->activity_date->format('l, d F Y') : '-' }} &bull;
              <i class="bi bi-clock ms-1 me-1"></i>{{ $currentActivity->start_time ? substr((string) $currentActivity->start_time, 0, 5) : '19:00' }} WIB &bull;
              <i class="bi bi-geo-alt ms-1 me-1"></i>{{ $currentActivity->location ?: 'Online' }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="row g-2 text-center">
              <div class="col-6 col-sm-3">
                <div class="p-2 bg-white rounded shadow-sm border">
                  <div class="text-muted small">Anggota</div>
                  <div class="fs-5 fw-bold text-dark">{{ $stats['total'] }}</div>
                </div>
              </div>
              <div class="col-6 col-sm-3">
                <div class="p-2 bg-white rounded shadow-sm border border-success">
                  <div class="text-success small fw-semibold">Hadir</div>
                  <div class="fs-5 fw-bold text-success">{{ $stats['present'] }}</div>
                </div>
              </div>
              <div class="col-6 col-sm-3">
                <div class="p-2 bg-white rounded shadow-sm border">
                  <div class="text-warning small fw-semibold">Izin/Sakit</div>
                  <div class="fs-5 fw-bold text-warning">{{ $stats['excused'] + $stats['sick'] }}</div>
                </div>
              </div>
              <div class="col-6 col-sm-3">
                <div class="p-2 bg-white rounded shadow-sm border">
                  <div class="text-muted small">Tingkat</div>
                  <div class="fs-5 fw-bold text-primary">{{ $stats['percentage'] }}%</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Attendance Sheet Table -->
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 50px;">#</th>
                <th>Nama Anggota</th>
                <th class="text-center text-nowrap" style="min-width: 320px;">Status Kehadiran</th>
                <th>Catatan / Keterangan</th>
                <th class="text-center text-nowrap">Waktu Presensi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($members as $index => $member)
                @php
                  $att = $existingAttendances->get($member->member_id);
                  $currentStatusId = $att ? $att->attendance_status_id : null;
                @endphp
                <tr wire:key="att-mbr-{{ $member->member_id }}">
                  <td class="text-muted small">{{ $index + 1 }}</td>
                  <td>
                    <div class="fw-semibold text-dark">{{ $member->name }}</div>
                    <small class="text-muted font-monospace">{{ $member->member_code }}</small>
                    @if ($member->phone)
                      @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $member->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                      @endphp
                      <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-decoration-none text-success small ms-2" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                      </a>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      @foreach ($statuses as $st)
                        @php
                          $isActiveStatus = ($currentStatusId === $st->attendance_status_id);
                          $btnClass = match($st->code) {
                            'PRESENT' => $isActiveStatus ? 'btn-success text-white' : 'btn-outline-success',
                            'ABSENT' => $isActiveStatus ? 'btn-danger text-white' : 'btn-outline-danger',
                            'EXCUSED' => $isActiveStatus ? 'btn-warning text-dark' : 'btn-outline-warning',
                            'SICK' => $isActiveStatus ? 'btn-info text-white' : 'btn-outline-info',
                            default => $isActiveStatus ? 'btn-secondary text-white' : 'btn-outline-secondary',
                          };
                        @endphp
                        <button type="button"
                          class="btn {{ $btnClass }} fw-semibold px-2 py-1"
                          wire:click="setStatus({{ $member->member_id }}, {{ $st->attendance_status_id }})">
                          @if ($isActiveStatus)
                            <i class="bi bi-check2"></i>
                          @endif
                          {{ $st->name }}
                        </button>
                      @endforeach
                    </div>
                  </td>
                  <td>
                    <div class="input-group input-group-sm">
                      <input type="text"
                        class="form-control"
                        wire:model="notes.{{ $member->member_id }}"
                        placeholder="Contoh: Sakit tipes, izin dinas..."
                        wire:keydown.enter="saveNote({{ $member->member_id }})">
                      <button class="btn btn-outline-secondary" type="button" wire:click="saveNote({{ $member->member_id }})" title="Simpan Catatan">
                        <i class="bi bi-save"></i>
                      </button>
                    </div>

                  </td>
                  <td class="text-center text-muted small">
                    @if ($att && $att->attendance_time)
                      {{ $att->attendance_time->format('H:i') }} WIB
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="bi bi-people fs-2 d-block mb-2 text-secondary"></i>
                    Belum ada anggota yang terdaftar pada kelompok COOL ini. Silakan tambahkan anggota terlebih dahulu.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @else
    <div class="card shadow-sm border-0 text-center py-5">
      <div class="card-body">
        <i class="bi bi-calendar2-check text-muted" style="font-size: 3rem;"></i>
        <h5 class="fw-bold mt-3 text-dark">Pilih Kegiatan untuk Membuka Presensi</h5>
        <p class="text-muted mb-0">Silakan pilih kegiatan COOL dari dropdown di atas untuk memulai pencatatan presensi anggota.</p>
      </div>
    </div>
  @endif
</div>
