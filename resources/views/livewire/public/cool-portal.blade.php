<div class="container py-4" style="max-width: 720px;">
  @if (!$isVerified)
    <!-- PIN Verification Screen -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
      <div class="card-header bg-success text-white text-center py-4 border-0" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;">
        <div class="d-inline-flex p-3 bg-white bg-opacity-25 rounded-circle mb-2">
          <i class="bi bi-heart-pulse-fill fs-1 text-white"></i>
        </div>
        <h3 class="fw-bold mb-1">COOL GBI Salemba</h3>
        <p class="mb-0 text-white-50">Community of Love - Portal Jemaat Mandiri</p>
      </div>
      <div class="card-body p-4 text-center">
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill mb-3">
          {{ $cool->name }}
        </span>
        <h5 class="fw-bold text-dark mb-2">Masukkan PIN Akses COOL</h5>
        <p class="text-muted small mb-4">Silakan masukkan 6 digit nomor PIN kelompok COOL Anda untuk melihat jadwal kegiatan, materi firman, dan menghubungi gembala.</p>

        <form wire:submit="verifyPin" style="max-width: 320px; margin: 0 auto;">
          <div class="mb-3">
            <input type="password" wire:model="pin" maxlength="6" autofocus
              class="form-control form-control-lg text-center font-monospace tracking-widest fs-3 fw-bold @error('pin') is-invalid @enderror"
              placeholder="&bull;&bull;&bull;&bull;&bull;&bull;"
              style="letter-spacing: 0.5em;">
            @error('pin')
              <div class="invalid-feedback text-start">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" wire:loading.attr="disabled">
            <span wire:loading.remove><i class="bi bi-unlock-fill me-1"></i> Buka Portal COOL</span>
            <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Memverifikasi...</span>
          </button>
        </form>

        <div class="mt-4 pt-3 border-top text-muted small">
          <i class="bi bi-info-circle me-1"></i>
          Belum tahu nomor PIN kelompok ini? Tanyakan langsung kepada <strong>{{ $cool->shepherd->name ?? 'Gembala COOL' }}</strong>.
        </div>
      </div>
    </div>
  @else
    <!-- Verified Member Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center">
        <i class="bi bi-heart-pulse-fill text-success fs-4 me-2"></i>
        <span class="fw-bold text-dark fs-5">GBI Salemba</span>
      </div>
      <button type="button" wire:click="exitPortal" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-box-arrow-right me-1"></i> Keluar
      </button>
    </div>

    <!-- COOL Header Hero -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden text-white" style="background: linear-gradient(135deg, #059669 0%, #064e3b 100%);">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <span class="badge bg-white text-success fw-bold px-2 py-1 mb-2 font-monospace">{{ $cool->cool_code }}</span>
            <h2 class="fw-bold mb-1">{{ $cool->name }}</h2>
            <p class="text-white-50 mb-3">{{ $cool->description ?: 'Komunitas sel Community of Love GBI Salemba' }}</p>
          </div>
        </div>

        <div class="d-flex align-items-center bg-white bg-opacity-10 p-3 rounded-3 mt-2">
          <div class="avatar bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-weight: 700;">
            {{ strtoupper(substr($cool->shepherd->name ?? 'G', 0, 2)) }}
          </div>
          <div class="flex-grow-1">
            <small class="text-white-50 d-block">Gembala Pembina COOL:</small>
            <strong class="fs-6">{{ $cool->shepherd->name ?? '-' }}</strong>
          </div>
          @if ($cool->shepherd && $cool->shepherd->phone)
            @php
              $cleanShepPhone = preg_replace('/[^0-9]/', '', $cool->shepherd->phone);
              if (str_starts_with($cleanShepPhone, '0')) {
                  $cleanShepPhone = '62' . substr($cleanShepPhone, 1);
              }
            @endphp
            <a href="https://wa.me/{{ $cleanShepPhone }}?text={{ urlencode('Shalom Gembala ' . $cool->shepherd->name . ', saya dari kelompok COOL ' . $cool->name) }}"
              target="_blank" class="btn btn-light btn-sm text-success fw-bold px-3">
              <i class="bi bi-whatsapp me-1"></i> WhatsApp
            </a>
          @endif
        </div>
      </div>
    </div>

    <!-- Navigation Pills -->
    <ul class="nav nav-pills nav-fill bg-white p-1 rounded-3 shadow-sm border mb-4">
      <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'activities' ? 'active bg-success text-white fw-bold' : 'text-dark' }}"
          wire:click="$set('activeTab', 'activities')">
          <i class="bi bi-calendar-event me-1"></i> Jadwal Kegiatan
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'materials' ? 'active bg-success text-white fw-bold' : 'text-dark' }}"
          wire:click="$set('activeTab', 'materials')">
          <i class="bi bi-file-earmark-text me-1"></i> Materi & Bahan
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link {{ $activeTab === 'message' ? 'active bg-success text-white fw-bold' : 'text-dark' }}"
          wire:click="$set('activeTab', 'message')">
          <i class="bi bi-chat-heart me-1"></i> Hubungi Gembala
        </button>
      </li>
    </ul>

    <!-- Tab 1: Activities -->
    @if ($activeTab === 'activities')
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0">
          <h5 class="fw-bold mb-0 text-dark">Jadwal Pertemuan Terdekat</h5>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            @forelse ($upcomingActivities as $act)
              <div class="list-group-item p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="fw-bold text-dark mb-0">{{ $act->name }}</h6>
                  <span class="badge bg-success-subtle text-success">{{ $act->activityType->name ?? 'Kegiatan' }}</span>
                </div>
                <div class="text-muted small mb-2">
                  <div>
                    <i class="bi bi-calendar3 me-1 text-success"></i>
                    <strong>{{ $act->activity_date ? $act->activity_date->format('l, d F Y') : '-' }}</strong> &bull;
                    <i class="bi bi-clock ms-1 me-1 text-success"></i>
                    {{ $act->start_time ? substr((string) $act->start_time, 0, 5) : '19:00' }} WIB
                  </div>
                  @if ($act->location)
                    <div class="mt-1">
                      <i class="bi bi-geo-alt me-1 text-danger"></i> {{ $act->location }}
                    </div>
                  @endif
                </div>
                @if ($act->description)
                  <p class="text-secondary small mb-0 bg-light p-2 rounded">
                    {{ $act->description }}
                  </p>
                @endif
              </div>
            @empty
              <div class="p-4 text-center text-muted">
                <i class="bi bi-calendar-check fs-2 d-block mb-2 text-secondary"></i>
                Belum ada jadwal pertemuan mendatang yang diumumkan.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    @endif

    <!-- Tab 2: Materials -->
    @if ($activeTab === 'materials')
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0">
          <h5 class="fw-bold mb-0 text-dark">Bahan Renungan & Materi Pertemuan</h5>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            @forelse ($materials as $mat)
              <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="p-2 rounded me-3
                    @if($mat->material_type === 'LINK') bg-primary-subtle text-primary
                    @else bg-danger-subtle text-danger @endif">
                    @if($mat->material_type === 'LINK')
                      <i class="bi bi-link-45deg fs-4"></i>
                    @else
                      <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                    @endif
                  </div>
                  <div>
                    <div class="fw-bold text-dark">{{ $mat->file_name }}</div>
                    <small class="text-muted">{{ $mat->activity->name ?? '-' }} &bull; {{ $mat->date_uploaded ? $mat->date_uploaded->format('d M Y') : '' }}</small>
                  </div>
                </div>
                <div>
                  @if ($mat->material_type === 'LINK' && $mat->external_url)
                    <a href="{{ $mat->external_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                      Buka Tautan <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                  @elseif ($mat->file_path)
                    <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="btn btn-sm btn-success">
                      Unduh <i class="bi bi-download ms-1"></i>
                    </a>
                  @endif
                </div>
              </div>
            @empty
              <div class="p-4 text-center text-muted">
                <i class="bi bi-file-earmark-x fs-2 d-block mb-2 text-secondary"></i>
                Belum ada materi pertemuan yang diunggah untuk kelompok ini.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    @endif

    <!-- Tab 3: Contact Shepherd / Prayer Request Form -->
    @if ($activeTab === 'message')
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0">
          <h5 class="fw-bold mb-0 text-dark">Kirim Pesan / Pokok Doa ke Gembala</h5>
          <p class="text-muted small mb-0 mt-1">Pesan Anda akan diterima secara privat oleh Gembala COOL.</p>
        </div>
        <div class="card-body">
          @if (session()->has('message_sent'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
              <i class="bi bi-check-circle-fill fs-4 me-2"></i>
              <div>{{ session('message_sent') }}</div>
            </div>
          @endif

          <form wire:submit="sendMessage">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="senderName" class="form-label fw-semibold">Nama Lengkap Anda <span class="text-danger">*</span></label>
                <input type="text" id="senderName" wire:model="senderName" class="form-control @error('senderName') is-invalid @enderror" placeholder="Contoh: Maria Magdalena">
                @error('senderName') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label for="senderPhone" class="form-label fw-semibold">Nomor WhatsApp (Opsional)</label>
                <input type="text" id="senderPhone" wire:model="senderPhone" class="form-control" placeholder="081234567890">
                <div class="form-text small">Agar Gembala dapat menghubungi Anda kembali.</div>
              </div>
            </div>

            <div class="mb-3">
              <label for="messageType" class="form-label fw-semibold">Kategori Pesan</label>
              <select id="messageType" wire:model="messageType" class="form-select">
                <option value="Pokok Doa">Pokok Doa Pribadi / Keluarga</option>
                <option value="Konseling Pastoral">Permohonan Konseling Pastoral</option>
                <option value="Pemberitahuan Izin">Izin Tidak Bisa Hadir Pertemuan</option>
                <option value="Kesaksian">Kesaksian Pujian / Berkat</option>
                <option value="Pertanyaan">Pertanyaan Umum</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="messageContent" class="form-label fw-semibold">Isi Pesan / Pokok Doa <span class="text-danger">*</span></label>
              <textarea id="messageContent" wire:model="messageContent" class="form-control @error('messageContent') is-invalid @enderror" rows="4" placeholder="Tuliskan pokok doa, pergumulan, atau pesan Anda di sini..."></textarea>
              @error('messageContent') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-success fw-semibold shadow-sm" wire:loading.attr="disabled">
              <span wire:loading.remove><i class="bi bi-send-fill me-1"></i> Kirim Pesan Sekarang</span>
              <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Mengirim...</span>
            </button>
          </form>
        </div>
      </div>
    @endif

    <div class="text-center text-muted small py-3">
      Community of Love &bull; Gereja Bethel Indonesia (GBI) Salemba &bull; Terhubung dalam Kasih Kristus
    </div>
  @endif
</div>
