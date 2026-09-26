<div class="registration-container"
     x-data="{
       scrollToTop() {
         window.scrollTo({ top: 0, behavior: 'smooth' });
       }
     }"
     @scroll-to-top.window="scrollToTop()">

  @if (!$isSuccess)
    <!-- ========================================== -->
    <!-- Form Card Header                           -->
    <!-- ========================================== -->
    <div class="portal-card shadow-lg border-0 mb-4 overflow-hidden">
      <!-- Header Church Banner -->
      <div class="p-4 text-center text-white position-relative overflow-hidden"
           style="background: linear-gradient(145deg, #064E3B 0%, #047857 55%, #059669 100%);">
        <div class="position-absolute" style="top: -25px; right: -25px; width: 140px; height: 140px; background: radial-gradient(circle, rgba(180, 241, 5, 0.25) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>
        <div class="position-absolute" style="bottom: -35px; left: -25px; width: 130px; height: 130px; background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>

        <div class="d-inline-flex p-3 bg-white bg-opacity-20 rounded-circle mb-2 shadow-sm" style="backdrop-filter: blur(8px);">
          <i class="bi bi-heart-pulse-fill fs-2 text-white"></i>
        </div>
        <div class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.06em;">
          GBI SALEMBA &bull; COMMUNITY OF LOVE
        </div>
        <h1 class="fw-bold mb-1 fs-4 text-white">Pendaftaran Jemaat Baru</h1>
        <p class="mb-0 text-white-50 small" style="font-size: 13px; max-width: 380px; margin: 0 auto; line-height: 1.4;">
          Selamat datang di rumah Tuhan! Silakan lengkapi data diri Anda agar kami dapat menyapa dan melayani Anda lebih dekat.
        </p>

        <!-- Stepper Indicator -->
        <div class="mt-4 pt-2">
          <div class="d-flex justify-content-between align-items-center position-relative px-2">
            <!-- Background connecting line -->
            <div class="position-absolute start-0 end-0" style="top: 18px; height: 3px; background: rgba(255, 255, 255, 0.2); z-index: 1;"></div>
            <!-- Active progress connecting line -->
            <div class="position-absolute start-0"
                 style="top: 18px; height: 3px; background: #B4F105; z-index: 2; transition: width 0.3s ease; width: {{ $currentStep === 1 ? '16%' : ($currentStep === 2 ? '50%' : '100%') }};"></div>

            <!-- Step 1 Button -->
            <button type="button"
                    wire:click="goToStep(1)"
                    class="btn p-0 border-0 text-white text-center position-relative d-flex flex-column align-items-center"
                    style="z-index: 3; cursor: pointer;">
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold {{ $currentStep >= 1 ? 'bg-white text-success' : 'bg-white bg-opacity-25 text-white' }}"
                   style="width: 36px; height: 36px; font-size: 14px; {{ $currentStep === 1 ? 'box-shadow: 0 0 0 4px rgba(180, 241, 5, 0.5) !important;' : '' }}">
                @if ($currentStep > 1)
                  <i class="bi bi-check-lg fw-bold text-success"></i>
                @else
                  1
                @endif
              </div>
              <span class="small mt-1 fw-semibold" style="font-size: 11px; {{ $currentStep === 1 ? 'color: #B4F105;' : '' }}">Data Diri</span>
            </button>

            <!-- Step 2 Button -->
            <button type="button"
                    wire:click="goToStep(2)"
                    class="btn p-0 border-0 text-white text-center position-relative d-flex flex-column align-items-center"
                    style="z-index: 3; cursor: pointer;">
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold {{ $currentStep >= 2 ? 'bg-white text-success' : 'bg-white bg-opacity-25 text-white' }}"
                   style="width: 36px; height: 36px; font-size: 14px; {{ $currentStep === 2 ? 'box-shadow: 0 0 0 4px rgba(180, 241, 5, 0.5) !important;' : '' }}">
                @if ($currentStep > 2)
                  <i class="bi bi-check-lg fw-bold text-success"></i>
                @else
                  2
                @endif
              </div>
              <span class="small mt-1 fw-semibold" style="font-size: 11px; {{ $currentStep === 2 ? 'color: #B4F105;' : '' }}">Kontak</span>
            </button>

            <!-- Step 3 Button -->
            <button type="button"
                    wire:click="goToStep(3)"
                    class="btn p-0 border-0 text-white text-center position-relative d-flex flex-column align-items-center"
                    style="z-index: 3; cursor: pointer;">
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold {{ $currentStep >= 3 ? 'bg-white text-success' : 'bg-white bg-opacity-25 text-white' }}"
                   style="width: 36px; height: 36px; font-size: 14px; {{ $currentStep === 3 ? 'box-shadow: 0 0 0 4px rgba(180, 241, 5, 0.5) !important;' : '' }}">
                3
              </div>
              <span class="small mt-1 fw-semibold" style="font-size: 11px; {{ $currentStep === 3 ? 'color: #B4F105;' : '' }}">Komunitas</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Card Body with Step Forms -->
      <div class="p-3 p-sm-4 bg-white">

        <!-- ========================================== -->
        <!-- STEP 1: DATA PRIBADI                       -->
        <!-- ========================================== -->
        @if ($currentStep === 1)
          <div>
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
              <span class="d-inline-flex p-2 bg-success-subtle text-success rounded-circle" style="width: 32px; height: 32px; align-items: center; justify-content: center;">
                <i class="bi bi-person-badge-fill" style="font-size: 14px;"></i>
              </span>
              <div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 15px;">Langkah 1: Identitas Pribadi</h5>
                <small class="text-muted" style="font-size: 11px;">Informasi dasar jemaat baru</small>
              </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-3">
              <label for="name" class="form-label fw-bold text-dark small mb-1">
                Nama Lengkap <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                  <i class="bi bi-person"></i>
                </span>
                <input type="text"
                       id="name"
                       wire:model="name"
                       class="form-control border-start-0 @error('name') is-invalid @enderror"
                       style="border-radius: 0 12px 12px 0; font-size: 15px; padding: 0.75rem 0.85rem;"
                       placeholder="Contoh: Stefanus Wijaya"
                       autocomplete="name"
                       autofocus>
              </div>
              @error('name')
                <div class="text-danger small mt-1" style="font-size: 12px;">
                  <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-3">
              <label class="form-label fw-bold text-dark small mb-2 d-block">
                Jenis Kelamin <span class="text-danger">*</span>
              </label>
              <div class="row g-2">
                <div class="col-6">
                  <button type="button"
                          class="btn w-100 py-3 d-flex flex-column align-items-center justify-content-center btn-mobile-touch border"
                          style="border-radius: 14px; {{ $gender === 'Laki-laki' ? 'background: #ECFDF5; border-color: #059669 !important; color: #064E3B; font-weight: 700;' : 'background: #F8FAFC; border-color: #E2E8F0; color: #64748B;' }}"
                          wire:click="$set('gender', 'Laki-laki')">
                    <span class="fs-4 mb-1">👨</span>
                    <span style="font-size: 14px;">Laki-laki</span>
                  </button>
                </div>
                <div class="col-6">
                  <button type="button"
                          class="btn w-100 py-3 d-flex flex-column align-items-center justify-content-center btn-mobile-touch border"
                          style="border-radius: 14px; {{ $gender === 'Perempuan' ? 'background: #ECFDF5; border-color: #059669 !important; color: #064E3B; font-weight: 700;' : 'background: #F8FAFC; border-color: #E2E8F0; color: #64748B;' }}"
                          wire:click="$set('gender', 'Perempuan')">
                    <span class="fs-4 mb-1">👩</span>
                    <span style="font-size: 14px;">Perempuan</span>
                  </button>
                </div>
              </div>
              @error('gender')
                <div class="text-danger small mt-1" style="font-size: 12px;">
                  <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="row g-2 mb-3">
              <div class="col-12 col-sm-6">
                <label for="birthplace" class="form-label fw-bold text-dark small mb-1">Tempat Lahir</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                    <i class="bi bi-geo-alt"></i>
                  </span>
                  <input type="text"
                         id="birthplace"
                         wire:model="birthplace"
                         class="form-control border-start-0"
                         style="border-radius: 0 12px 12px 0; font-size: 14px; padding: 0.7rem 0.85rem;"
                         placeholder="Contoh: Jakarta">
                </div>
              </div>

              <div class="col-12 col-sm-6">
                <label for="birthdate" class="form-label fw-bold text-dark small mb-1">Tanggal Lahir</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                    <i class="bi bi-calendar-date"></i>
                  </span>
                  <input type="date"
                         id="birthdate"
                         wire:model="birthdate"
                         max="{{ date('Y-m-d') }}"
                         class="form-control border-start-0 @error('birthdate') is-invalid @enderror"
                         style="border-radius: 0 12px 12px 0; font-size: 14px; padding: 0.7rem 0.85rem;">
                </div>
                @error('birthdate')
                  <div class="text-danger small mt-1" style="font-size: 12px;">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                  </div>
                @enderror
              </div>
            </div>

            <!-- Status Pernikahan -->
            <div class="mb-4">
              <label class="form-label fw-bold text-dark small mb-2 d-block">
                Status Pernikahan <span class="text-danger">*</span>
              </label>
              <div class="d-flex flex-wrap gap-2">
                @foreach (['Belum Menikah' => 'Belum Menikah', 'Menikah' => 'Menikah', 'Janda/Duda' => 'Janda / Duda'] as $val => $lbl)
                  <button type="button"
                          class="btn btn-mobile-touch py-2 px-3 rounded-pill border fw-semibold flex-grow-1"
                          style="font-size: 13px; {{ $marital_status === $val ? 'background: #059669; border-color: #059669 !important; color: #ffffff;' : 'background: #F8FAFC; border-color: #E2E8F0; color: #475569;' }}"
                          wire:click="$set('marital_status', '{{ $val }}')">
                    {{ $lbl }}
                  </button>
                @endforeach
              </div>
              @error('marital_status')
                <div class="text-danger small mt-1" style="font-size: 12px;">
                  <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <!-- Action Button: Next Step -->
            <button type="button"
                    wire:click="nextStep"
                    class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3 btn-mobile-touch d-flex align-items-center justify-content-center gap-2"
                    style="border-radius: 16px; background: linear-gradient(135deg, #059669 0%, #047857 100%);">
              <span>Lanjut ke Kontak & Alamat</span>
              <i class="bi bi-arrow-right"></i>
            </button>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- STEP 2: KONTAK & DOMISILI                  -->
        <!-- ========================================== -->
        @if ($currentStep === 2)
          <div>
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
              <span class="d-inline-flex p-2 bg-success-subtle text-success rounded-circle" style="width: 32px; height: 32px; align-items: center; justify-content: center;">
                <i class="bi bi-telephone-inbound-fill" style="font-size: 14px;"></i>
              </span>
              <div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 15px;">Langkah 2: Kontak & Domisili</h5>
                <small class="text-muted" style="font-size: 11px;">Agar tim penggembalaan dapat berkomunikasi</small>
              </div>
            </div>

            <!-- Nomor WhatsApp / HP -->
            <div class="mb-3">
              <label for="phone" class="form-label fw-bold text-dark small mb-1">
                Nomor WhatsApp / HP Aktif <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-success-subtle text-success border-end-0 fw-bold" style="border-radius: 12px 0 0 12px;">
                  <i class="bi bi-whatsapp"></i>
                </span>
                <input type="tel"
                       id="phone"
                       wire:model="phone"
                       class="form-control border-start-0 @error('phone') is-invalid @enderror"
                       style="border-radius: 0 12px 12px 0; font-size: 15px; padding: 0.75rem 0.85rem;"
                       placeholder="Contoh: 081234567890"
                       inputmode="tel"
                       autocomplete="tel"
                       autofocus>
              </div>
              <div class="form-text small text-muted" style="font-size: 11px;">
                Nomor ini akan digunakan untuk menyapa dan menyampaikan informasi persekutuan.
              </div>
              @error('phone')
                <div class="text-danger small mt-1" style="font-size: 12px;">
                  <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label fw-bold text-dark small mb-1">
                Alamat Email (Opsional)
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                  <i class="bi bi-envelope"></i>
                </span>
                <input type="email"
                       id="email"
                       wire:model="email"
                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                       style="border-radius: 0 12px 12px 0; font-size: 14px; padding: 0.7rem 0.85rem;"
                       placeholder="nama@email.com"
                       inputmode="email"
                       autocomplete="email">
              </div>
              @error('email')
                <div class="text-danger small mt-1" style="font-size: 12px;">
                  <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <!-- Alamat Domisili -->
            <div class="mb-3">
              <label for="address" class="form-label fw-bold text-dark small mb-1">
                Alamat Tempat Tinggal Saat Ini
              </label>
              <textarea id="address"
                        wire:model="address"
                        rows="3"
                        class="form-control"
                        style="border-radius: 12px; font-size: 14px;"
                        placeholder="Tuliskan nama jalan, RT/RW, kelurahan, kecamatan, atau kota..."></textarea>
              <div class="form-text small text-muted" style="font-size: 11px;">
                Membantu kami merekomendasikan kelompok COOL terdekat dengan tempat tinggal Anda.
              </div>
            </div>

            <!-- Akun Media Sosial -->
            <div class="mb-4">
              <label for="social_media" class="form-label fw-bold text-dark small mb-1">
                Akun Media Sosial / Instagram (Opsional)
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
                  <i class="bi bi-instagram"></i>
                </span>
                <input type="text"
                       id="social_media"
                       wire:model="social_media"
                       class="form-control border-start-0"
                       style="border-radius: 0 12px 12px 0; font-size: 14px; padding: 0.7rem 0.85rem;"
                       placeholder="@username">
              </div>
            </div>

            <!-- Action Buttons: Back & Next -->
            <div class="d-flex gap-2">
              <button type="button"
                      wire:click="previousStep"
                      class="btn btn-light py-3 px-3 fw-bold border text-secondary btn-mobile-touch"
                      style="border-radius: 14px; min-width: 90px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </button>

              <button type="button"
                      wire:click="nextStep"
                      class="btn btn-success btn-lg flex-grow-1 fw-bold shadow-sm py-3 btn-mobile-touch d-flex align-items-center justify-content-center gap-2"
                      style="border-radius: 14px; background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                <span>Lanjut ke Komunitas</span>
                <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>
        @endif

        <!-- ========================================== -->
        <!-- STEP 3: KEHIDUPAN ROHANI & KOMUNITAS COOL  -->
        <!-- ========================================== -->
        @if ($currentStep === 3)
          <form wire:submit="register">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
              <span class="d-inline-flex p-2 bg-success-subtle text-success rounded-circle" style="width: 32px; height: 32px; align-items: center; justify-content: center;">
                <i class="bi bi-people-fill" style="font-size: 14px;"></i>
              </span>
              <div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 15px;">Langkah 3: Komunitas & Rohani</h5>
                <small class="text-muted" style="font-size: 11px;">Bertumbuh bersama dalam kasih Kristus</small>
              </div>
            </div>

            <!-- Status KOM -->
            <div class="mb-3">
              <label class="form-label fw-bold text-dark small mb-1 d-block">
                Status Kelas Pengajaran KOM (Kehidupan Berorientasi Melayani) <span class="text-danger">*</span>
              </label>
              <div class="d-flex flex-wrap gap-1 mb-2">
                @foreach (['Belum KOM' => 'Belum Pernah KOM', 'KOM 100' => 'KOM 100', 'KOM 200' => 'KOM 200', 'KOM 300' => 'KOM 300', 'KOM 400' => 'KOM 400'] as $val => $lbl)
                  <button type="button"
                          class="btn btn-sm btn-mobile-touch py-1 px-3 rounded-pill border fw-semibold"
                          style="font-size: 12px; {{ $kom_status === $val ? 'background: #059669; border-color: #059669 !important; color: #ffffff;' : 'background: #F8FAFC; border-color: #E2E8F0; color: #475569;' }}"
                          wire:click="$set('kom_status', '{{ $val }}')">
                    {{ $lbl }}
                  </button>
                @endforeach
              </div>
              <small class="text-muted" style="font-size: 11px;">
                Jika Anda baru pertama kali beribadah, pilih "Belum Pernah KOM".
              </small>
              @error('kom_status')
                <div class="text-danger small mt-1" style="font-size: 12px;">
                  <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
              @enderror
            </div>

            <!-- Minat Bergabung Komunitas COOL -->
            <div class="mb-3">
              <label class="form-label fw-bold text-dark small mb-2 d-block">
                Bergabung Komunitas Sel COOL (Community of Love)
              </label>

              <!-- Option Cards -->
              <div class="d-flex flex-column gap-2 mb-3">
                <!-- Card 1: Yes, choose specific COOL -->
                <div class="p-3 border rounded-3 cursor-pointer btn-mobile-touch transition-all"
                     style="{{ $wants_cool === 'yes' ? 'background: #ECFDF5; border-color: #059669 !important; box-shadow: 0 2px 8px rgba(5,150,105,0.15);' : 'background: #FAFAFA; border-color: #E2E8F0;' }}"
                     wire:click="$set('wants_cool', 'yes')">
                  <div class="d-flex align-items-center gap-3">
                    <div class="form-check m-0">
                      <input class="form-check-input" type="radio" name="wants_cool" value="yes"
                             wire:model.live="wants_cool" id="cool_opt_yes">
                    </div>
                    <div class="flex-grow-1">
                      <label for="cool_opt_yes" class="fw-bold text-dark mb-0 d-block cursor-pointer" style="font-size: 13px;">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> Ya, saya sudah memiliki kelompok COOL pilihan
                      </label>
                      <small class="text-muted" style="font-size: 11px;">Pilih kelompok COOL dari daftar di bawah.</small>
                    </div>
                  </div>

                  <!-- Dropdown appears if option 1 is selected -->
                  @if ($wants_cool === 'yes')
                    <div class="mt-3 pt-2 border-top">
                      <label for="cool_id" class="form-label fw-semibold small text-dark mb-1">
                        Pilih Kelompok COOL <span class="text-danger">*</span>:
                      </label>
                      <select id="cool_id" wire:model="cool_id"
                              class="form-select @error('cool_id') is-invalid @enderror"
                              style="border-radius: 12px; font-size: 13px;">
                        <option value="">-- Pilih Kelompok COOL --</option>
                        @foreach ($cools as $c)
                          <option value="{{ $c->cool_id }}">
                            {{ $c->name }} (Gembala: {{ $c->shepherd->name ?? 'Belum Ditugaskan' }})
                          </option>
                        @endforeach
                      </select>
                      @error('cool_id')
                        <div class="text-danger small mt-1" style="font-size: 12px;">
                          <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                      @enderror
                    </div>
                  @endif
                </div>

                <!-- Card 2: Recommend for me -->
                <div class="p-3 border rounded-3 cursor-pointer btn-mobile-touch transition-all"
                     style="{{ $wants_cool === 'recommend' ? 'background: #EFF6FF; border-color: #3B82F6 !important; box-shadow: 0 2px 8px rgba(59,130,246,0.15);' : 'background: #FAFAFA; border-color: #E2E8F0;' }}"
                     wire:click="$set('wants_cool', 'recommend')">
                  <div class="d-flex align-items-center gap-3">
                    <div class="form-check m-0">
                      <input class="form-check-input" type="radio" name="wants_cool" value="recommend"
                             wire:model.live="wants_cool" id="cool_opt_recommend">
                    </div>
                    <div class="flex-grow-1">
                      <label for="cool_opt_recommend" class="fw-bold text-dark mb-0 d-block cursor-pointer" style="font-size: 13px;">
                        <i class="bi bi-compass-fill text-primary me-1"></i> Saya ingin dicarikan kelompok COOL terdekat
                      </label>
                      <small class="text-muted" style="font-size: 11px;">Tim pastoral akan merekomendasikan kelompok yang cocok sesuai domisili Anda.</small>
                    </div>
                  </div>
                </div>

                <!-- Card 3: Not yet -->
                <div class="p-3 border rounded-3 cursor-pointer btn-mobile-touch transition-all"
                     style="{{ $wants_cool === 'no' ? 'background: #F8FAFC; border-color: #94A3B8 !important;' : 'background: #FAFAFA; border-color: #E2E8F0;' }}"
                     wire:click="$set('wants_cool', 'no')">
                  <div class="d-flex align-items-center gap-3">
                    <div class="form-check m-0">
                      <input class="form-check-input" type="radio" name="wants_cool" value="no"
                             wire:model.live="wants_cool" id="cool_opt_no">
                    </div>
                    <div class="flex-grow-1">
                      <label for="cool_opt_no" class="fw-bold text-secondary mb-0 d-block cursor-pointer" style="font-size: 13px;">
                        <i class="bi bi-hourglass-split me-1"></i> Belum ingin bergabung saat ini
                      </label>
                      <small class="text-muted" style="font-size: 11px;">Hanya mendaftar sebagai jemaat umum GBI Salemba.</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pokok Doa Awal / Catatan Pertama -->
            <div class="mb-4">
              <label for="prayer_request" class="form-label fw-bold text-dark small mb-1">
                Pokok Doa / Permohonan Konseling Pastoral (Opsional)
              </label>
              <textarea id="prayer_request"
                        wire:model="prayer_request"
                        rows="3"
                        class="form-control"
                        style="border-radius: 12px; font-size: 14px;"
                        placeholder="Ada hal atau permohonan yang ingin didukung dalam doa oleh hamba Tuhan? Tuliskan di sini..."></textarea>
              <div class="form-text small text-muted" style="font-size: 11px;">
                <i class="bi bi-shield-lock me-1 text-success"></i>
                Permohonan doa ini bersifat rahasia dan diteruskan khusus kepada tim pastoral.
              </div>
            </div>

            <!-- Summary Review Checklist -->
            <div class="p-3 bg-light rounded-3 mb-4 border border-light-subtle">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-clipboard2-check-fill text-success"></i>
                <span class="fw-bold text-dark" style="font-size: 12px;">Ringkasan Data Pendaftaran:</span>
              </div>
              <div class="row g-1 small text-secondary" style="font-size: 12px;">
                <div class="col-5 text-muted">Nama Lengkap:</div>
                <div class="col-7 fw-semibold text-dark text-truncate">{{ $name ?: '-' }}</div>

                <div class="col-5 text-muted">Jenis Kelamin:</div>
                <div class="col-7 fw-semibold text-dark">{{ $gender }}</div>

                <div class="col-5 text-muted">Nomor WhatsApp:</div>
                <div class="col-7 fw-semibold text-dark font-monospace">{{ $phone ?: '-' }}</div>

                <div class="col-5 text-muted">Status Pernikahan:</div>
                <div class="col-7 fw-semibold text-dark">{{ $marital_status }}</div>

                <div class="col-5 text-muted">Status KOM:</div>
                <div class="col-7 fw-semibold text-dark">{{ $kom_status }}</div>
              </div>
            </div>

            <!-- Action Buttons: Back & Submit -->
            <div class="d-flex gap-2">
              <button type="button"
                      wire:click="previousStep"
                      class="btn btn-light py-3 px-3 fw-bold border text-secondary btn-mobile-touch"
                      style="border-radius: 14px; min-width: 90px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </button>

              <button type="submit"
                      class="btn btn-success btn-lg flex-grow-1 fw-bold shadow-sm py-3 btn-mobile-touch d-flex align-items-center justify-content-center gap-2"
                      wire:loading.attr="disabled"
                      style="border-radius: 14px; background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                <span wire:loading.remove>
                  <i class="bi bi-send-check-fill"></i> Kirim Pendaftaran Sekarang
                </span>
                <span wire:loading>
                  <i class="bi bi-arrow-repeat spin"></i> Menyimpan Data...
                </span>
              </button>
            </div>
          </form>
        @endif

      </div>
    </div>
  @else
    <!-- ========================================== -->
    <!-- SCREEN: PENDAFTARAN BERHASIL (SUCCESS)     -->
    <!-- ========================================== -->
    <div class="portal-card shadow-lg border-0 mb-4 overflow-hidden">
      <!-- Success Header Banner -->
      <div class="p-4 text-center text-white position-relative overflow-hidden"
           style="background: linear-gradient(145deg, #064E3B 0%, #047857 60%, #059669 100%);">
        <div class="position-absolute" style="top: -20px; right: -20px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(180, 241, 5, 0.3) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>

        <div class="d-inline-flex p-3 bg-white text-success rounded-circle mb-3 shadow-lg"
             style="width: 72px; height: 72px; align-items: center; justify-content: center; font-size: 36px;">
          <i class="bi bi-check2-circle"></i>
        </div>

        <div class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 11px;">
          PUJI TUHAN!
        </div>
        <h2 class="fw-bold mb-1 fs-4 text-white">Selamat Datang di GBI Salemba!</h2>
        <p class="text-white-50 small mb-0" style="font-size: 13px;">
          Data jemaat Anda telah berhasil tersimpan dalam sistem gereja kami.
        </p>
      </div>

      <!-- Card Content -->
      <div class="p-3 p-sm-4 bg-white">
        <!-- Digital Member Pass Card Preview -->
        <div class="card border border-success-subtle shadow-sm rounded-4 mb-4 overflow-hidden"
             style="background: linear-gradient(180deg, #F0FDF4 0%, #FFFFFF 100%);">
          <div class="p-3 border-bottom border-success-subtle d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-heart-pulse-fill text-success"></i>
              <span class="fw-bold text-dark" style="font-size: 12px; letter-spacing: 0.05em;">KARTU JEMAAT DIGITAL</span>
            </div>
            <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 10px;">
              STATUS: AKTIF
            </span>
          </div>

          <div class="p-3">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                   style="width: 52px; height: 52px; font-size: 18px;">
                {{ strtoupper(substr($registeredName ?? 'J', 0, 2)) }}
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="text-muted small" style="font-size: 11px;">Nama Jemaat:</div>
                <h5 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 16px;">
                  {{ $registeredName }}
                </h5>
                <div class="d-inline-flex align-items-center gap-1 badge bg-success-subtle text-success border border-success-subtle px-2 py-0 rounded-pill font-monospace mt-1" style="font-size: 11px;">
                  <i class="bi bi-upc-scan"></i> {{ $registeredMemberCode }}
                </div>
              </div>
            </div>

            <div class="bg-white p-2 px-3 rounded-3 border border-light-subtle small mb-2">
              <div class="row g-1" style="font-size: 12px;">
                <div class="col-5 text-muted">Tanggal Bergabung:</div>
                <div class="col-7 fw-semibold text-dark">{{ date('d F Y') }}</div>

                <div class="col-5 text-muted">Status COOL:</div>
                <div class="col-7 fw-semibold text-success">
                  {{ $assignedCoolName ?: 'Belum Tergabung COOL' }}
                </div>
              </div>
            </div>

            <div class="text-center text-muted small" style="font-size: 11px;">
              <i class="bi bi-info-circle me-1"></i> Simpan nomor ID Anda untuk keperluan presensi ibadah & kegiatan gereja.
            </div>
          </div>
        </div>

        <!-- Scripture Verse -->
        <div class="card border-0 bg-light p-3 rounded-4 text-center mb-4">
          <small class="text-secondary fst-italic" style="font-size: 12px; line-height: 1.5;">
            "Sebab di mana dua atau tiga orang berkumpul dalam Nama-Ku, di situ Aku ada di tengah-tengah mereka."
          </small>
          <div class="fw-bold text-success mt-1" style="font-size: 11px;">
            &mdash; MATIUS 18:20
          </div>
        </div>

        <!-- Quick Pastoral Actions -->
        @php
          $greetingWa = "Shalom Tim Sekretariat GBI Salemba, saya baru saja mendaftar sebagai jemaat baru dengan nama {$registeredName} (ID: {$registeredMemberCode}). Salam kenal, Tuhan memberkati.";
        @endphp
        <div class="d-flex flex-column gap-2 mb-3">
          <a href="https://api.whatsapp.com/send?text={{ urlencode($greetingWa) }}"
             target="_blank"
             class="btn btn-success fw-bold py-3 btn-mobile-touch shadow-sm d-flex align-items-center justify-content-center gap-2"
             style="border-radius: 14px; background: #25D366; border: none; font-size: 14px;">
            <i class="bi bi-whatsapp fs-5"></i> Sapa Tim Sekretariat via WhatsApp
          </a>

          <button type="button"
                  wire:click="resetForm"
                  class="btn btn-outline-secondary py-3 fw-bold btn-mobile-touch d-flex align-items-center justify-content-center gap-2"
                  style="border-radius: 14px; font-size: 14px;">
            <i class="bi bi-person-plus-fill text-success"></i> Daftarkan Anggota Keluarga / Jemaat Lain
          </button>
        </div>

      </div>
    </div>
  @endif

  <!-- Extra CSS Tweaks for Mobile Optimization -->
  <style>
    .cursor-pointer {
      cursor: pointer;
    }
    .transition-all {
      transition: all 0.2s ease;
    }
    .spin {
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    /* Touch optimization */
    input, select, textarea, button {
      touch-action: manipulation;
    }
    /* iOS input shadow fix */
    input[type="text"], input[type="tel"], input[type="email"], input[type="date"], select, textarea {
      -webkit-appearance: none;
    }
  </style>

</div>
