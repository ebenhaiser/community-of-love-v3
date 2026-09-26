<div class="portal-container"
     x-data="{
       showPin: false,
       pinVal: @entangle('pin'),
       addDigit(d) {
         if ((this.pinVal || '').length < 6) {
           this.pinVal = (this.pinVal || '') + d;
         }
       },
       backspace() {
         if ((this.pinVal || '').length > 0) {
           this.pinVal = this.pinVal.slice(0, -1);
         }
       },
       clear() {
         this.pinVal = '';
       }
     }">

  @if (!$isVerified)
    <!-- ========================================== -->
    <!-- Screen 1: Mobile-Optimized PIN Verification -->
    <!-- ========================================== -->
    <div class="portal-card shadow-lg border-0 mb-4">
      <!-- Header Banner with Church Branding -->
      <div class="p-4 text-center text-white position-relative overflow-hidden"
           style="background: linear-gradient(145deg, #064E3B 0%, #047857 55%, #059669 100%);">
        <div class="position-absolute" style="top: -25px; right: -25px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(180, 241, 5, 0.2) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>
        <div class="position-absolute" style="bottom: -35px; left: -25px; width: 130px; height: 130px; background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>

        <div class="d-inline-flex p-3 bg-white bg-opacity-20 rounded-circle mb-2 shadow-sm" style="backdrop-filter: blur(8px);">
          <i class="bi bi-heart-pulse-fill fs-2 text-white"></i>
        </div>
        <div class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.06em;">
          GBI SALEMBA &bull; COMMUNITY OF LOVE
        </div>
        <h2 class="fw-bold mb-1 fs-4 text-white">{{ $cool->name }}</h2>
        <p class="mb-0 text-white-50 small">Portal Mandiri Jemaat Kelompok COOL</p>
      </div>

      <!-- Card Body: PIN Entry -->
      <div class="p-3 p-sm-4">
        <div class="text-center mb-3">
          <div class="d-inline-flex align-items-center gap-1 badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill mb-2 fw-bold font-monospace">
            <i class="bi bi-shield-lock-fill"></i> KODE: {{ $cool->cool_code }}
          </div>
          <h4 class="fw-bold text-dark mb-1">Masukkan PIN Akses COOL</h4>
          <p class="text-muted small mb-0" style="font-size: 12px;">
            Akses jadwal kegiatan, materi renungan firman, dan sapa Gembala pembina kelompok Anda.
          </p>
        </div>

        <form wire:submit="verifyPin">
          <!-- Hidden Real Input synced to Alpine / Livewire for Physical & Mobile Keyboard -->
          <input type="tel"
                 id="pinInput"
                 wire:model="pin"
                 x-model="pinVal"
                 maxlength="6"
                 inputmode="numeric"
                 pattern="[0-9]*"
                 autocomplete="one-time-code"
                 class="visually-hidden"
                 autofocus>

          <!-- 6-Digit Visual Boxes -->
          <div class="d-flex justify-content-center align-items-center gap-2 mb-2 cursor-pointer"
               @click="$el.closest('form').querySelector('#pinInput').focus()">
            <template x-for="i in [0, 1, 2, 3, 4, 5]" :key="i">
              <div class="pin-box"
                   :class="{
                     'filled': (pinVal || '').length > i,
                     'active-cursor': (pinVal || '').length === i
                   }">
                <span x-show="(pinVal || '').length > i"
                      x-text="showPin ? (pinVal || '')[i] : '&bull;'"
                      style="font-size: 26px; line-height: 1;"></span>
                <span x-show="(pinVal || '').length <= i" class="text-muted opacity-25" style="font-size: 14px;">-</span>
              </div>
            </template>
          </div>

          <!-- Toggle Eye Visibility -->
          <div class="text-center mb-3">
            <button type="button"
                    class="btn btn-sm btn-link text-decoration-none text-muted py-0"
                    @click="showPin = !showPin"
                    style="font-size: 12px;">
              <i class="bi" :class="showPin ? 'bi-eye-slash text-danger' : 'bi-eye text-success'"></i>
              <span x-text="showPin ? 'Sembunyikan Angka' : 'Tampilkan Angka'"></span>
            </button>
          </div>

          @error('pin')
            <div class="alert alert-danger py-2 px-3 small text-center mb-3 rounded-3 fw-medium" role="alert">
              <i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}
            </div>
          @enderror

          <!-- Mobile Tactile Numeric Keypad -->
          <div class="bg-light p-3 rounded-4 mb-3 border border-light-subtle">
            <div class="row g-2 mb-2 text-center">
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('1')">1</button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('2')">2<span class="keypad-btn-sub">ABC</span></button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('3')">3<span class="keypad-btn-sub">DEF</span></button></div>
            </div>
            <div class="row g-2 mb-2 text-center">
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('4')">4<span class="keypad-btn-sub">GHI</span></button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('5')">5<span class="keypad-btn-sub">JKL</span></button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('6')">6<span class="keypad-btn-sub">MNO</span></button></div>
            </div>
            <div class="row g-2 mb-2 text-center">
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('7')">7<span class="keypad-btn-sub">PQRS</span></button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('8')">8<span class="keypad-btn-sub">TUV</span></button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('9')">9<span class="keypad-btn-sub">WXYZ</span></button></div>
            </div>
            <div class="row g-2 text-center">
              <div class="col-4"><button type="button" class="keypad-btn text-muted fs-6" @click="clear()" title="Hapus Semua">C</button></div>
              <div class="col-4"><button type="button" class="keypad-btn" @click="addDigit('0')">0</button></div>
              <div class="col-4"><button type="button" class="keypad-btn text-danger fs-5" @click="backspace()" title="Hapus Satu"><i class="bi bi-backspace-fill"></i></button></div>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit"
                  class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3 btn-mobile-touch d-flex align-items-center justify-content-center gap-2"
                  wire:loading.attr="disabled"
                  style="border-radius: 16px; background: linear-gradient(135deg, #059669 0%, #047857 100%);">
            <span wire:loading.remove><i class="bi bi-unlock-fill"></i> Buka Portal COOL</span>
            <span wire:loading><i class="bi bi-arrow-repeat spin"></i> Memverifikasi PIN...</span>
          </button>
        </form>

        <!-- Help Box: WhatsApp Direct to Shepherd -->
        <div class="mt-4 pt-3 border-top">
          <div class="bg-light p-3 rounded-4 border border-light-subtle d-flex align-items-center gap-3">
            <div class="avatar rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center shadow-sm"
                 style="width: 44px; height: 44px; flex-shrink: 0; font-size: 14px;">
              {{ strtoupper(substr($cool->shepherd->name ?? 'G', 0, 2)) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
              <div class="text-muted" style="font-size: 11px;">Belum tahu nomor PIN?</div>
              <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">
                {{ $cool->shepherd->name ?? 'Gembala COOL' }}
              </div>
            </div>
            @if ($cool->shepherd && $cool->shepherd->phone)
              @php
                $cleanShepPhone = preg_replace('/[^0-9]/', '', $cool->shepherd->phone);
                if (str_starts_with($cleanShepPhone, '0')) {
                    $cleanShepPhone = '62' . substr($cleanShepPhone, 1);
                }
                $waMsg = "Shalom Gembala " . ($cool->shepherd->name ?? '') . ", saya jemaat dari kelompok " . $cool->name . ". Mohon info PIN untuk membuka portal jemaat COOL. Terima kasih.";
              @endphp
              <a href="https://wa.me/{{ $cleanShepPhone }}?text={{ urlencode($waMsg) }}" target="_blank"
                 class="btn btn-sm btn-outline-success fw-bold px-3 py-2 btn-mobile-touch" style="border-radius: 12px; font-size: 12px; white-space: nowrap;">
                <i class="bi bi-whatsapp me-1 text-success"></i> Tanya PIN
              </a>
            @endif
          </div>
        </div>

        <!-- Link to New Member Public Registration -->
        <div class="mt-3 text-center">
          <a href="{{ route('jemaat.register', ['cool_id' => $cool->cool_id]) }}" class="text-decoration-none small text-success fw-semibold d-inline-flex align-items-center gap-1">
            <i class="bi bi-person-plus-fill"></i> Jemaat Baru? Daftarkan Diri di Sini
          </a>
        </div>
      </div>
    </div>
  @else
    <!-- ========================================== -->
    <!-- Screen 2: Verified Member Mobile Portal    -->
    <!-- ========================================== -->

    <!-- Mobile Top App Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
      <div class="d-flex align-items-center gap-2">
        <span class="d-inline-flex p-1 bg-success text-white rounded-circle shadow-sm" style="width: 28px; height: 28px; align-items: center; justify-content: center;">
          <i class="bi bi-heart-pulse-fill" style="font-size: 13px;"></i>
        </span>
        <div>
          <span class="fw-bold text-dark" style="font-size: 14px;">GBI Salemba</span>
          <span class="badge bg-success-subtle text-success border border-success-subtle py-0 px-2 rounded-pill ms-1" style="font-size: 10px;">
            <i class="bi bi-shield-check me-1"></i>Terverifikasi
          </span>
        </div>
      </div>
      <button type="button"
              wire:click="exitPortal"
              class="btn btn-sm btn-outline-danger py-1 px-3 btn-mobile-touch"
              style="border-radius: 20px; font-size: 12px;">
        <i class="bi bi-box-arrow-right me-1"></i> Keluar
      </button>
    </div>

    <!-- COOL Hero Banner Card -->
    <div class="portal-card mb-3 text-white position-relative overflow-hidden"
         style="background: linear-gradient(145deg, #064E3B 0%, #047857 60%, #059669 100%);">
      <!-- Ambient Decorative Lights -->
      <div class="position-absolute" style="top: -30px; right: -30px; width: 170px; height: 170px; background: radial-gradient(circle, rgba(180, 241, 5, 0.25) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>
      <div class="position-absolute" style="bottom: -40px; left: -20px; width: 140px; height: 140px; background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>

      <div class="p-3 p-sm-4 position-relative">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
          <span class="badge bg-white text-dark fw-bold px-2 py-1 font-monospace" style="font-size: 11px;">
            {{ $cool->cool_code }}
          </span>
          <span class="badge bg-white bg-opacity-20 text-white fw-normal px-2 py-1" style="font-size: 11px;">
            <i class="bi bi-people-fill me-1"></i>Komunitas Sel COOL
          </span>
        </div>

        <h2 class="fw-bold mb-1 fs-4 text-white">{{ $cool->name }}</h2>
        <p class="text-white-50 mb-3 small" style="font-size: 12px; line-height: 1.4;">
          {{ $cool->description ?: 'Komunitas sel Community of Love GBI Salemba &bull; Bertumbuh dalam firman dan kasih Kristus.' }}
        </p>

        <!-- Quick Meeting Info Bar -->
        <div class="d-flex flex-column gap-2 pt-2 border-top border-white border-opacity-15">
          <div class="d-flex align-items-center gap-2 text-white small" style="font-size: 12px;">
            <i class="bi bi-calendar2-check-fill text-warning"></i>
            <span>Rutin: <strong>{{ $cool->regular_schedule_day ?? 'Jumat' }}</strong> &bull; {{ $cool->regular_schedule_time ? substr((string) $cool->regular_schedule_time, 0, 5) : '19:30' }} WIB</span>
          </div>
          @if ($cool->meeting_place)
            <div class="d-flex align-items-start gap-2 text-white-50 small" style="font-size: 11px;">
              <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
              <span class="text-truncate">{{ $cool->meeting_place }}</span>
            </div>
          @endif
        </div>

        <!-- Shepherd Highlight Bar -->
        <div class="bg-white bg-opacity-10 rounded-3 p-2 px-3 mt-3 border border-white border-opacity-15 d-flex align-items-center gap-2"
             style="backdrop-filter: blur(8px);">
          <div class="avatar bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold"
               style="width: 36px; height: 36px; flex-shrink: 0; font-size: 13px;">
            {{ strtoupper(substr($cool->shepherd->name ?? 'G', 0, 2)) }}
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="text-white-50" style="font-size: 10px;">Gembala Kelompok:</div>
            <div class="fw-bold text-white text-truncate" style="font-size: 12px;">
              {{ $cool->shepherd->name ?? 'Gembala COOL' }}
            </div>
          </div>
          @if ($cool->shepherd && $cool->shepherd->phone)
            @php
              $cleanShepPhone = preg_replace('/[^0-9]/', '', $cool->shepherd->phone);
              if (str_starts_with($cleanShepPhone, '0')) {
                  $cleanShepPhone = '62' . substr($cleanShepPhone, 1);
              }
              $shepGreeting = "Shalom Gembala " . ($cool->shepherd->name ?? '') . ", saya jemaat dari kelompok COOL " . $cool->name;
            @endphp
            <a href="https://wa.me/{{ $cleanShepPhone }}?text={{ urlencode($shepGreeting) }}"
               target="_blank" class="btn btn-light btn-sm text-success fw-bold px-3 py-1 btn-mobile-touch shadow-sm"
               style="border-radius: 10px; font-size: 11px; white-space: nowrap;">
              <i class="bi bi-whatsapp me-1 text-success"></i> WhatsApp
            </a>
          @endif
        </div>
      </div>
    </div>

    <!-- Segmented Navigation Pills (Top) -->
    <div class="portal-card p-1 mb-3">
      <div class="nav nav-pills nav-fill gap-1" role="tablist">
        <button type="button"
                class="nav-link portal-nav-pill {{ $activeTab === 'activities' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'activities')">
          <i class="bi bi-calendar-event"></i> Jadwal
          @if ($upcomingActivities->count() > 0)
            <span class="badge bg-white text-dark py-0 px-1 rounded-pill" style="font-size: 10px;">{{ $upcomingActivities->count() }}</span>
          @endif
        </button>
        <button type="button"
                class="nav-link portal-nav-pill {{ $activeTab === 'materials' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'materials')">
          <i class="bi bi-file-earmark-text"></i> Materi
          @if ($materials->count() > 0)
            <span class="badge bg-white text-dark py-0 px-1 rounded-pill" style="font-size: 10px;">{{ $materials->count() }}</span>
          @endif
        </button>
        <button type="button"
                class="nav-link portal-nav-pill {{ $activeTab === 'message' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'message')">
          <i class="bi bi-chat-heart"></i> Titip Doa
        </button>
        <button type="button"
                class="nav-link portal-nav-pill {{ $activeTab === 'shepherd' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'shepherd')">
          <i class="bi bi-person-heart"></i> Gembala
        </button>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- Tab 1: Jadwal Pertemuan                    -->
    <!-- ========================================== -->
    @if ($activeTab === 'activities')
      <div class="portal-card mb-4">
        <div class="p-3 px-4 border-bottom bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
              <i class="bi bi-calendar3 text-success me-2"></i>Jadwal Pertemuan Terdekat
            </h5>
            <small class="text-muted" style="font-size: 11px;">Kegiatan ibadah dan persekutuan kelompok COOL</small>
          </div>
        </div>

        <div class="p-3">
          @forelse ($upcomingActivities as $act)
            <div class="card border border-light-subtle shadow-sm mb-3 rounded-4 overflow-hidden">
              <div class="card-body p-3">
                <div class="d-flex align-items-start gap-3">
                  <!-- Date Badge Box -->
                  <div class="rounded-3 text-center p-2 text-white shadow-sm flex-shrink-0"
                       style="background: linear-gradient(135deg, #059669 0%, #047857 100%); width: 62px;">
                    <div class="fw-bold fs-3 lh-1 mb-0">
                      {{ $act->activity_date ? $act->activity_date->format('d') : '?' }}
                    </div>
                    <div class="text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.05em;">
                      {{ $act->activity_date ? $act->activity_date->format('M Y') : 'N/A' }}
                    </div>
                  </div>

                  <!-- Details -->
                  <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex flex-wrap align-items-center gap-1 mb-1">
                      <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10px;">
                        {{ $act->activityType->name ?? 'Pertemuan COOL' }}
                      </span>
                      <span class="text-muted small" style="font-size: 11px;">
                        <i class="bi bi-clock me-1 text-success"></i>
                        {{ $act->start_time ? substr((string) $act->start_time, 0, 5) : '19:00' }} WIB
                      </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $act->name }}</h5>

                    @if ($act->location)
                      <div class="text-muted small mb-1 d-flex align-items-start gap-1" style="font-size: 12px;">
                        <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                        <span>{{ $act->location }}</span>
                      </div>
                    @endif

                    @if ($act->description)
                      <div class="bg-light p-2 rounded-2 small text-secondary mt-1" style="font-size: 11px;">
                        {{ $act->description }}
                      </div>
                    @endif
                  </div>
                </div>

                <!-- Quick Action WhatsApp Share -->
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                  @if ($act->location)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($act->location) }}"
                       target="_blank" class="btn btn-sm btn-link text-decoration-none text-muted p-0" style="font-size: 11px;">
                      <i class="bi bi-map me-1 text-primary"></i> Buka Peta
                    </a>
                  @else
                    <div></div>
                  @endif

                  @php
                    $actDateStr = $act->activity_date ? $act->activity_date->format('l, d F Y') : '-';
                    $shareMsg = "Shalom jemaat COOL {$cool->name}!\nJangan lupa hadir di pertemuan:\n\n*{$act->name}*\n📅 Hari/Tgl: {$actDateStr}\n⏰ Pukul: " . ($act->start_time ? substr((string) $act->start_time, 0, 5) : '19:00') . " WIB\n📍 Lokasi: " . ($act->location ?: 'Sesuai info') . "\n\nSampai jumpa, Tuhan memberkati!";
                  @endphp
                  <a href="https://api.whatsapp.com/send?text={{ urlencode($shareMsg) }}" target="_blank"
                     class="btn btn-sm btn-outline-success fw-semibold px-3 py-1 btn-mobile-touch" style="border-radius: 10px; font-size: 11px;">
                    <i class="bi bi-whatsapp me-1 text-success"></i> Bagikan Jadwal
                  </a>
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-5 text-muted">
              <div class="p-3 bg-light rounded-circle d-inline-flex mb-2">
                <i class="bi bi-calendar-check fs-2 text-secondary opacity-50"></i>
              </div>
              <h6 class="fw-bold text-dark mb-1">Belum Ada Jadwal Mendatang</h6>
              <p class="small text-muted mb-0">Jadwal pertemuan berikutnya akan diumumkan oleh Gembala COOL.</p>
            </div>
          @endforelse
        </div>
      </div>
    @endif

    <!-- ========================================== -->
    <!-- Tab 2: Materi & Dokumen                    -->
    <!-- ========================================== -->
    @if ($activeTab === 'materials')
      <div class="portal-card mb-4">
        <div class="p-3 px-4 border-bottom bg-light bg-opacity-50">
          <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
            <i class="bi bi-journal-bookmark-fill text-success me-2"></i>Bahan Renungan & Dokumen
          </h5>
          <small class="text-muted" style="font-size: 11px;">Bahan firman penunjang pertemuan kelompok</small>
        </div>

        <div class="p-3">
          @forelse ($materials as $mat)
            <div class="card border border-light-subtle shadow-sm mb-3 rounded-4 p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0
                  @if($mat->material_type === 'LINK') bg-primary-subtle text-primary @else bg-danger-subtle text-danger @endif"
                  style="width: 44px; height: 44px;">
                  @if($mat->material_type === 'LINK')
                    <i class="bi bi-link-45deg fs-4"></i>
                  @else
                    <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                  @endif
                </div>

                <div class="flex-grow-1 overflow-hidden">
                  <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 13px;">{{ $mat->file_name }}</h6>
                  <div class="text-muted small" style="font-size: 11px;">
                    <span class="badge bg-light text-dark border me-1">{{ $mat->material_type }}</span>
                    <span>{{ $mat->date_uploaded ? $mat->date_uploaded->format('d M Y') : '' }}</span>
                  </div>
                </div>

                <div class="flex-shrink-0">
                  @if ($mat->material_type === 'LINK' && $mat->external_url)
                    <a href="{{ $mat->external_url }}" target="_blank"
                       class="btn btn-sm btn-outline-primary px-3 fw-semibold btn-mobile-touch" style="border-radius: 10px; font-size: 11px;">
                      Buka <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                  @elseif ($mat->file_path)
                    <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank"
                       class="btn btn-sm btn-success px-3 fw-semibold btn-mobile-touch" style="border-radius: 10px; font-size: 11px;">
                      Unduh <i class="bi bi-download ms-1"></i>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-5 text-muted">
              <div class="p-3 bg-light rounded-circle d-inline-flex mb-2">
                <i class="bi bi-file-earmark-x fs-2 text-secondary opacity-50"></i>
              </div>
              <h6 class="fw-bold text-dark mb-1">Belum Ada Materi yang Diunggah</h6>
              <p class="small text-muted mb-0">Materi firman akan diunggah oleh Gembala kelompok COOL.</p>
            </div>
          @endforelse
        </div>
      </div>
    @endif

    <!-- ========================================== -->
    <!-- Tab 3: Kirim Doa / Pesan ke Gembala        -->
    <!-- ========================================== -->
    @if ($activeTab === 'message')
      <div class="portal-card mb-4">
        <div class="p-3 px-4 border-bottom bg-light bg-opacity-50">
          <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
            <i class="bi bi-chat-heart-fill text-danger me-2"></i>Kirim Pesan / Pokok Doa ke Gembala
          </h5>
          <small class="text-muted" style="font-size: 11px;">Pesan Anda bersifat rahasia untuk {{ $cool->shepherd->name ?? 'Gembala COOL' }}.</small>
        </div>

        <div class="p-3 p-sm-4">


          <form wire:submit="sendMessage">
            <!-- Category Chips Selector -->
            <div class="mb-3">
              <label class="form-label fw-semibold small text-muted mb-2 d-block">Pilih Kategori Permohonan:</label>
              <div class="d-flex flex-wrap gap-1">
                @foreach (['Pokok Doa' => '🙏 Pokok Doa', 'Konseling Pastoral' => '🤝 Konseling', 'Pemberitahuan Izin' => '✉️ Izin Tidak Hadir', 'Kesaksian' => '✨ Kesaksian', 'Pertanyaan' => '❓ Tanya Gembala'] as $key => $label)
                  <button type="button"
                          class="btn btn-sm btn-mobile-touch py-1 px-2 rounded-pill fw-semibold"
                          style="font-size: 11px;"
                          :class="'{{ $messageType }}' === '{{ $key }}' ? 'btn-success text-white' : 'btn-outline-secondary border-light-subtle bg-light text-dark'"
                          wire:click="$set('messageType', '{{ $key }}')">
                    {{ $label }}
                  </button>
                @endforeach
              </div>
            </div>

            <div class="mb-3">
              <label for="senderName" class="form-label fw-semibold small">Nama Lengkap Anda <span class="text-danger">*</span></label>
              <input type="text" id="senderName" wire:model="senderName"
                     class="form-control @error('senderName') is-invalid @enderror"
                     style="border-radius: 12px; font-size: 14px;"
                     placeholder="Contoh: Maria Magdalena">
              @error('senderName') <div class="invalid-feedback small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label for="senderPhone" class="form-label fw-semibold small">Nomor WhatsApp (Opsional)</label>
              <input type="tel" id="senderPhone" wire:model="senderPhone"
                     class="form-control"
                     style="border-radius: 12px; font-size: 14px;"
                     placeholder="Contoh: 081234567890">
              <div class="form-text small" style="font-size: 11px;">Agar Gembala dapat mendoakan dan membalas pesan Anda.</div>
            </div>

            <div class="mb-3">
              <label for="messageContent" class="form-label fw-semibold small">Isi Pesan / Pokok Doa <span class="text-danger">*</span></label>
              <textarea id="messageContent" wire:model="messageContent"
                        class="form-control @error('messageContent') is-invalid @enderror"
                        rows="4"
                        style="border-radius: 12px; font-size: 14px;"
                        placeholder="Tuliskan isi pokok doa, pergumulan, atau pesan Anda di sini secara terbuka..."></textarea>
              @error('messageContent') <div class="invalid-feedback small">{{ $message }}</div> @enderror
            </div>

            <button type="submit"
                    class="btn btn-success fw-bold shadow-sm w-100 py-3 btn-mobile-touch d-flex align-items-center justify-content-center gap-2"
                    wire:loading.attr="disabled"
                    style="border-radius: 14px; background: linear-gradient(135deg, #059669 0%, #047857 100%);">
              <span wire:loading.remove><i class="bi bi-send-fill"></i> Kirim Pesan Sekarang</span>
              <span wire:loading><i class="bi bi-arrow-repeat spin"></i> Mengirim Pesan...</span>
            </button>
          </form>
        </div>
      </div>
    @endif

    <!-- ========================================== -->
    <!-- Tab 4: Profil Gembala                      -->
    <!-- ========================================== -->
    @if ($activeTab === 'shepherd')
      <div class="portal-card mb-4">
        <div class="p-3 px-4 border-bottom bg-light bg-opacity-50">
          <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
            <i class="bi bi-person-badge-fill text-success me-2"></i>Gembala Pembina Kelompok
          </h5>
          <small class="text-muted" style="font-size: 11px;">Informasi dan kontak pastoral Gembala COOL Anda</small>
        </div>

        <div class="p-3 p-sm-4 text-center">
          <div class="avatar bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm fw-bold mb-2 position-relative"
               style="width: 72px; height: 72px; font-size: 24px;">
            {{ strtoupper(substr($cool->shepherd->name ?? 'G', 0, 2)) }}
            <span class="position-absolute bottom-0 end-0 bg-warning text-dark rounded-circle p-1 d-flex align-items-center justify-content-center"
                  style="width: 22px; height: 22px; font-size: 11px;" title="Gembala Terverifikasi">
              <i class="bi bi-star-fill"></i>
            </span>
          </div>

          <h5 class="fw-bold text-dark mb-0 fs-6">{{ $cool->shepherd->name ?? 'Gembala COOL' }}</h5>
          <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 mb-3" style="font-size: 11px;">
            Gembala Pembina Kelompok COOL
          </span>

          @if ($cool->shepherd && $cool->shepherd->phone)
            @php
              $cleanShepPhone = preg_replace('/[^0-9]/', '', $cool->shepherd->phone);
              if (str_starts_with($cleanShepPhone, '0')) {
                  $cleanShepPhone = '62' . substr($cleanShepPhone, 1);
              }
              $shepGreeting = "Shalom Gembala " . ($cool->shepherd->name ?? '') . ", saya jemaat kelompok COOL " . $cool->name;
            @endphp

            <div class="d-flex flex-column gap-2 mb-3">
              <a href="https://wa.me/{{ $cleanShepPhone }}?text={{ urlencode($shepGreeting) }}"
                 target="_blank" class="btn btn-success fw-bold py-2 btn-mobile-touch shadow-sm"
                 style="border-radius: 12px; background: #25D366; border: none; font-size: 13px;">
                <i class="bi bi-whatsapp me-2"></i> Chat Langsung via WhatsApp
              </a>

              <a href="tel:{{ $cleanShepPhone }}"
                 class="btn btn-outline-secondary py-2 btn-mobile-touch"
                 style="border-radius: 12px; font-size: 13px;">
                <i class="bi bi-telephone-fill me-2 text-primary"></i> Hubungi Telepon ({{ $cool->shepherd->phone }})
              </a>
            </div>
          @endif
        </div>
      </div>
    @endif

    <!-- Pastoral Blessing Scripture Card -->
    <div class="card border-0 bg-white bg-opacity-75 p-3 rounded-4 text-center shadow-sm mb-4">
      <small class="text-muted fst-italic" style="font-size: 12px;">
        "Dan marilah kita saling memperhatikan supaya kita saling mendorong dalam kasih dan dalam pekerjaan yang baik. Janganlah kita menjauhkan diri dari pertemuan-pertemuan ibadah kita..."
      </small>
      <div class="fw-bold text-success mt-1" style="font-size: 11px;">
        &mdash; IBRANI 10:24-25
      </div>
    </div>

    <!-- ========================================== -->
    <!-- Mobile Fixed Bottom Navigation Bar Dock    -->
    <!-- ========================================== -->
    <nav class="portal-bottom-nav">
      <div class="portal-bottom-nav-inner">
        <button type="button"
                class="portal-bottom-tab {{ $activeTab === 'activities' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'activities')">
          <i class="bi bi-calendar-event{{ $activeTab === 'activities' ? '-fill' : '' }}"></i>
          <span>Jadwal</span>
          @if ($upcomingActivities->count() > 0)
            <span class="portal-badge-count">{{ $upcomingActivities->count() }}</span>
          @endif
        </button>

        <button type="button"
                class="portal-bottom-tab {{ $activeTab === 'materials' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'materials')">
          <i class="bi bi-journal-text"></i>
          <span>Materi</span>
          @if ($materials->count() > 0)
            <span class="portal-badge-count">{{ $materials->count() }}</span>
          @endif
        </button>

        <button type="button"
                class="portal-bottom-tab {{ $activeTab === 'message' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'message')">
          <i class="bi bi-chat-heart{{ $activeTab === 'message' ? '-fill' : '' }}"></i>
          <span>Doa</span>
        </button>

        <button type="button"
                class="portal-bottom-tab {{ $activeTab === 'shepherd' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'shepherd')">
          <i class="bi bi-person-badge{{ $activeTab === 'shepherd' ? '-fill' : '' }}"></i>
          <span>Gembala</span>
        </button>
      </div>
    </nav>
  @endif
</div>
