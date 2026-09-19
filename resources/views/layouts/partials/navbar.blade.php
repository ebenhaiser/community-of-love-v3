<header class="navbar-custom">
  <div class="navbar-left">
    <!-- Desktop sidebar toggle (visible on large screens only) -->
    <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
      id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
      <i class="bi bi-chevron-bar-left"></i>
    </button>
    <!-- Mobile sidebar toggle -->
    <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
      <i class="bi bi-list"></i>
    </button>

    <!-- Quick Actions Dropdown -->
    <div class="dropdown ms-2">
      <button class="btn-quick-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
        id="quick-actions-dropdown">
        <i class="bi bi-plus-lg"></i>
        <span>Aksi Cepat</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-quick-action" aria-labelledby="quick-actions-dropdown">
        <li class="dropdown-header">Pintasan Cepat</li>
        <li><a class="dropdown-item" href="{{ url('/activities') }}"><i class="bi bi-calendar-plus"></i> Jadwal Kegiatan</a></li>
        <li><a class="dropdown-item" href="{{ url('/members') }}"><i class="bi bi-person-plus"></i> Data Anggota</a></li>
        <li><a class="dropdown-item" href="{{ url('/cools') }}"><i class="bi bi-people"></i> Kelompok COOL</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="{{ url('/attendances') }}"><i class="bi bi-check2-circle"></i> Input Presensi</a></li>
      </ul>
    </div>
  </div>

  <!-- Mid navbar: search pill -->
  <div class="navbar-search-wrapper">
    <input type="text" class="navbar-search-input" placeholder="Cari data anggota, COOL, kegiatan..." id="main-search">
    <button class="navbar-search-btn" aria-label="Search">
      <i class="bi bi-search"></i>
    </button>
  </div>

  <!-- Right actions -->
  <div class="navbar-actions">
    <!-- Fullscreen Toggle -->
    <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
      <i class="bi bi-arrows-fullscreen"></i>
    </button>

    <!-- Notification Dropdown -->
    <div class="dropdown">
      <button class="navbar-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false" id="btn-notifications" data-bs-auto-close="outside">
        <i class="bi bi-bell"></i>
        <span class="navbar-action-badge"></span>
      </button>
      <div class="dropdown-menu dropdown-menu-end dropdown-menu-notification p-0"
        aria-labelledby="btn-notifications">
        <div class="notification-header">
          <h6 class="notification-title">Notifikasi</h6>
          <button class="btn-clear-all" type="button">Tandai semua dibaca</button>
        </div>
        <div class="notification-list">
          <a href="#" class="notification-item">
            <div class="notification-icon bg-primary text-white">
              <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div class="notification-content">
              <p class="notification-text">Pesan baru dari <strong>Andi Pratama</strong></p>
              <span class="notification-time">2 menit lalu</span>
            </div>
            <span class="notification-unread-dot"></span>
          </a>
          <a href="#" class="notification-item">
            <div class="notification-icon bg-warning text-dark">
              <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div class="notification-content">
              <p class="notification-text">Jadwal kegiatan COOL minggu ini siap diisi</p>
              <span class="notification-time">1 jam lalu</span>
            </div>
          </a>
        </div>
        <a href="{{ url('/messages') }}" class="notification-footer">Lihat Semua Pesan & Notifikasi</a>
      </div>
    </div>

    <!-- Profile Dropdown -->
    <div class="dropdown ms-2">
      <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false" id="profile-dropdown">
        <img src="{{ asset('spark-admin-1.0.0/assets/images/avatar.png') }}" alt="Profile Image" class="navbar-profile-img"
          onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
        <span class="navbar-profile-name d-none d-md-inline">{{ auth()->user()->full_name ?? 'Master Admin' }}</span>
        <i class="bi bi-chevron-down navbar-profile-caret"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
        <li class="dropdown-header">Selamat Datang!</li>
        <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person"></i> Profil Saya</a></li>
        <li><a class="dropdown-item" href="{{ url('/settings') }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <form action="{{ url('/logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item text-danger border-0 bg-transparent">
              <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</header>
