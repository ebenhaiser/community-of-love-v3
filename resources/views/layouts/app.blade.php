<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Dashboard') - Sistem Manajemen COOL GBI Salemba</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('spark-admin-1.0.0/assets/images/favicon.ico') }}">

  <!-- Local Third-Party Libraries (Spark Admin) -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/apexcharts/apexcharts.css') }}">
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/flatpickr/flatpickr.min.css') }}">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-enhanced.css') }}">

  @livewireStyles
  @stack('styles')
</head>

<body>

  <!-- Sidebar Component -->
  @include('layouts.partials.sidebar')

  <!-- Main Content Area -->
  <div class="main-wrapper">

    <!-- Top Navbar Component -->
    @include('layouts.partials.navbar')

    <!-- Flash Messages / Alerts -->
    <div class="px-3 px-md-4 pt-3">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3 shadow-sm" role="alert">
          <i class="bi bi-check-circle-fill me-2 fs-5"></i>
          <div>{{ session('success') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3 shadow-sm" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
          <div>{{ session('error') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>

    <!-- Page Header (Optional per view) -->
    @hasSection('page-header')
      @yield('page-header')
    @endif

    <!-- Main Content Container -->
    <main class="page-content px-3 px-md-4 py-3">
      @yield('content')
      {{ $slot ?? '' }}
    </main>

    <!-- Footer Component -->
    @include('layouts.partials.footer')

  </div>

  <!-- Local Third-Party Libraries Script dependencies -->
  <script src="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('spark-admin-1.0.0/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('spark-admin-1.0.0/assets/libs/flatpickr/flatpickr.min.js') }}"></script>

  <!-- Local dashboard interactions controller -->
  <script src="{{ asset('spark-admin-1.0.0/assets/js/dashboard.js') }}"></script>

  <!-- Mobile Sidebar Interaction Script -->
  <script>
    (function () {
      function initMobileNav() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const closeBtn = document.getElementById('sidebar-close-btn');
        let overlay = document.querySelector('.sidebar-overlay');

        if (!overlay) {
          overlay = document.createElement('div');
          overlay.className = 'sidebar-overlay';
          document.body.appendChild(overlay);
        }

        function openNav() {
          if (sidebar) sidebar.classList.add('show');
          if (overlay) overlay.classList.add('show');
        }

        function closeNav() {
          if (sidebar) sidebar.classList.remove('show');
          if (overlay) overlay.classList.remove('show');
        }

        if (toggleBtn) {
          toggleBtn.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar && sidebar.classList.contains('show')) {
              closeNav();
            } else {
              openNav();
            }
          };
        }

        if (closeBtn) {
          closeBtn.onclick = function (e) {
            e.preventDefault();
            closeNav();
          };
        }

        if (overlay) {
          overlay.onclick = function () {
            closeNav();
          };
        }

        // Close when clicking nav links on mobile
        if (sidebar) {
          const links = sidebar.querySelectorAll('.sidebar-menu-link');
          links.forEach(function (link) {
            link.addEventListener('click', function () {
              if (window.innerWidth < 1200) {
                closeNav();
              }
            });
          });
        }
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileNav);
      } else {
        initMobileNav();
      }
      document.addEventListener('livewire:navigated', initMobileNav);
    })();
  </script>

  @livewireStyles
  @livewireScripts
  @stack('scripts')
</body>

</html>
