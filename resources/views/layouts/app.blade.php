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

    <!-- Global Toast Notifications -->
    <div x-data="{
        toasts: [],
        addToast(payload, defaultType = 'success') {
            let msg = '';
            let typ = defaultType;
            if (typeof payload === 'object' && payload !== null) {
                if (Array.isArray(payload)) {
                    if (payload.length > 0 && typeof payload[0] === 'object' && payload[0] !== null) {
                        msg = payload[0].message || payload[0].msg || '';
                        typ = payload[0].type || payload[0].status || typ;
                    } else if (payload.length > 0) {
                        msg = payload[0];
                        typ = payload[1] || typ;
                    }
                } else {
                    msg = payload.message || payload.msg || '';
                    typ = payload.type || payload.status || typ;
                }
            } else {
                msg = String(payload || '');
            }
            if (!msg || msg.trim() === '') return;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message: msg, type: typ, show: true });
            setTimeout(() => {
                this.removeToast(id);
            }, 4000);
        },
        removeToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) toast.show = false;
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 400);
        }
    }" 
    @notify.window="addToast($event.detail)"
    x-init="
        window.showToast = (msg, typ = 'success') => addToast(msg, typ);
        @if (session('success')) addToast('{{ addslashes(session('success')) }}', 'success'); @endif
        @if (session('error')) addToast('{{ addslashes(session('error')) }}', 'error'); @endif
        @if (session('warning')) addToast('{{ addslashes(session('warning')) }}', 'warning'); @endif
        @if (session('info')) addToast('{{ addslashes(session('info')) }}', 'info'); @endif
    "
    class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" 
                 x-transition.opacity.duration.300ms 
                 class="toast align-items-center border-0 show mb-2 shadow-lg" 
                 :class="{
                     'text-bg-danger': toast.type === 'error' || toast.type === 'danger',
                     'text-bg-warning text-dark': toast.type === 'warning',
                     'text-bg-info text-dark': toast.type === 'info',
                     'text-bg-success': toast.type === 'success' || !toast.type
                 }" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center fw-medium py-2 px-3">
                        <i class="bi me-2 fs-5" :class="{
                            'bi-exclamation-triangle-fill': toast.type === 'error' || toast.type === 'danger',
                            'bi-exclamation-circle-fill': toast.type === 'warning',
                            'bi-info-circle-fill': toast.type === 'info',
                            'bi-check-circle-fill': toast.type === 'success' || !toast.type
                        }"></i>
                        <div x-text="toast.message" style="font-size: 13.5px; line-height: 1.4;"></div>
                    </div>
                    <button type="button" @click="removeToast(toast.id)" class="btn-close me-2 m-auto" :class="toast.type === 'warning' || toast.type === 'info' ? '' : 'btn-close-white'" aria-label="Close"></button>
                </div>
            </div>
        </template>
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
      /**
       * Mobile sidebar helper.
       * dashboard.js already handles: open/close toggle, overlay click-to-close.
       * We only add: close-on-nav-link-click (mobile) + re-bind after Livewire navigation.
       */
      function initMobileNav() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const closeBtn = document.getElementById('sidebar-close-btn');

        // Reuse the overlay dashboard.js created, or create one if missing
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

        // Attach toggle (use addEventListener to avoid overwriting dashboard.js handler)
        if (toggleBtn) {
          // Remove previous listener by cloning the node, then re-attach
          const newToggle = toggleBtn.cloneNode(true);
          toggleBtn.parentNode.replaceChild(newToggle, toggleBtn);
          newToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar && sidebar.classList.contains('show')) {
              closeNav();
            } else {
              openNav();
            }
          });
        }

        // Sidebar close button (X)
        if (closeBtn) {
          const newClose = closeBtn.cloneNode(true);
          closeBtn.parentNode.replaceChild(newClose, closeBtn);
          newClose.addEventListener('click', function (e) {
            e.preventDefault();
            closeNav();
          });
        }

        // Overlay click closes sidebar
        overlay.onclick = function () {
          closeNav();
        };

        // Close sidebar when a nav link is clicked on mobile
        if (sidebar) {
          sidebar.addEventListener('click', function (e) {
            const link = e.target.closest('.sidebar-menu-link');
            if (link && window.innerWidth < 1200) {
              closeNav();
            }
          });
        }
      }

      // Run after DOM is ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileNav);
      } else {
        initMobileNav();
      }

      // Re-run after every Livewire navigation (SPA-style page change)
      document.addEventListener('livewire:navigated', initMobileNav);
    })();
  </script>

  @livewireScripts
  @stack('scripts')
</body>

</html>
