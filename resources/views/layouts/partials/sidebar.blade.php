<div class="sidebar-wrapper" id="sidebar">
  <!-- Brand Logo & Mobile Close Button -->
  <div class="sidebar-header-row">
    <a href="{{ url('/') }}" class="sidebar-brand text-decoration-none mb-0">
      <i class="bi bi-heart-pulse-fill text-lime"></i>
      <span>COOL Salemba</span>
    </a>
    <button type="button" class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Tutup Menu">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <!-- Navigation Menu -->
  <div class="flex-grow-1 overflow-y-auto">
    <!-- Group: Menu -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Utama</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/') }}" class="sidebar-menu-link {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}" id="menu-overview" title="Dashboard">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Jemaat & Komunitas COOL -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Jemaat & Komunitas COOL</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/members') }}" class="sidebar-menu-link {{ request()->is('members*') ? 'active' : '' }}" id="menu-members" title="Data Jemaat GBI Salemba">
            <i class="bi bi-person-vcard-fill"></i>
            <span>Data Jemaat</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ url('/cools') }}" class="sidebar-menu-link {{ request()->is('cools*') ? 'active' : '' }}" id="menu-cools" title="Kelompok COOL">
            <i class="bi bi-people-fill"></i>
            <span>Data COOL</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ url('/shepherds') }}" class="sidebar-menu-link {{ request()->is('shepherds*') ? 'active' : '' }}" id="menu-shepherds" title="Gembala COOL">
            <i class="bi bi-person-badge-fill"></i>
            <span>Gembala COOL</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Aktivitas & Presensi -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Kegiatan & Presensi</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/activities') }}" class="sidebar-menu-link {{ request()->is('activities*') ? 'active' : '' }}" id="menu-activities" title="Kegiatan COOL">
            <i class="bi bi-calendar-event-fill"></i>
            <span>Kegiatan COOL</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ url('/attendances') }}" class="sidebar-menu-link {{ request()->is('attendances*') ? 'active' : '' }}" id="menu-attendances" title="Presensi Kegiatan">
            <i class="bi bi-check2-square"></i>
            <span>Presensi / Absensi</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ url('/materials') }}" class="sidebar-menu-link {{ request()->is('materials*') ? 'active' : '' }}" id="menu-materials" title="Materi & Dokumen">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Materi & Dokumen</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Monitoring & Statistik -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Monitoring & Laporan</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/statistics') }}" class="sidebar-menu-link {{ request()->is('statistics*') ? 'active' : '' }}" id="menu-statistics" title="Statistik Kehadiran">
            <i class="bi bi-bar-chart-line-fill"></i>
            <span>Statistik Kehadiran</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ url('/follow-ups') }}" class="sidebar-menu-link {{ request()->is('follow-ups*') ? 'active' : '' }}" id="menu-follow-ups" title="Perhatian & Follow-up">
            <i class="bi bi-heart-pulse-fill"></i>
            <span>Pastoral & Follow-up</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Komunikasi -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Komunikasi & Akses</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/messages') }}" class="sidebar-menu-link {{ request()->is('messages*') ? 'active' : '' }}" id="menu-messages" title="Pesan Anggota">
            <i class="bi bi-chat-dots-fill"></i>
            <span>Pesan Anggota</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ url('/qr-access') }}" class="sidebar-menu-link {{ request()->is('qr-access*') ? 'active' : '' }}" id="menu-qr-access" title="Akses QR & PIN">
            <i class="bi bi-qr-code-scan"></i>
            <span>Akses QR & PIN</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Event Gereja (Phase 2) -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Event Gereja</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/church-events') }}" class="sidebar-menu-link {{ request()->is('church-events*') ? 'active' : '' }}" id="menu-events" title="Event Gereja">
            <i class="bi bi-buildings-fill"></i>
            <span>Event Lintas COOL</span>
          </a>
        </li>
      </ul>
    </div>

    @if(Auth::check() && Auth::user()->role->name === 'MASTER')
    <!-- Group: Admin -->
    <div class="sidebar-menu-section">  
      <div class="sidebar-menu-title">Admin</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/master/users') }}" class="sidebar-menu-link {{ request()->is('master/users*') ? 'active' : '' }}" id="menu-master-users" title="Manajemen Pengguna">
            <i class="bi bi-people-fill"></i>
            <span>Manajemen Pengguna</span>
          </a>
        </li>
      </ul>
    </div>
    @endif

    <!-- Group: Tentang Kami -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Tentang Kami</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ url('/about-us') }}" class="sidebar-menu-link {{ request()->is('about-us*') ? 'active' : '' }}" id="menu-about-us" title="Tentang Kami">
            <i class="bi bi-info-circle-fill"></i>
            <span>Tentang Kami</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Sidebar Profile Card (Dynamic Footer) -->
  <!-- <div class="sidebar-profile">
    <img src="{{ asset('spark-admin-1.0.0/assets/images/avatar.png') }}" alt="Profile" class="sidebar-profile-img"
      onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">{{ auth()->user()->full_name ?? auth()->user()->username ?? 'Pengguna' }}</div>
      <div class="sidebar-profile-email">{{ (auth()->user()->role && auth()->user()->role->name === 'SHEPHERD') ? 'Gembala COOL' : 'Master Administrator' }}</div>
    </div>
  </div> -->
</div>
