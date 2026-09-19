<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#064E3B">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Portal Jemaat COOL') - GBI Salemba</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('spark-admin-1.0.0/assets/images/favicon.ico') }}">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Local Third-Party Libraries (Spark Admin) -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-enhanced.css') }}">

  <style>
    :root {
      --portal-primary: #059669;
      --portal-primary-dark: #064E3B;
      --portal-primary-light: #10B981;
      --portal-accent: #B4F105;
      --portal-bg: #F8FAFC;
    }

    * {
      -webkit-tap-highlight-color: transparent;
      box-sizing: border-box;
    }

    body {
      background: radial-gradient(120% 70% at 50% 0%, #E8F5E9 0%, #F1F5F2 40%, #E2E8F0 100%);
      min-height: 100vh;
      min-height: -webkit-fill-available;
      display: flex;
      flex-direction: column;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      color: #0F172A;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
    }

    /* Mobile-first app wrapper */
    .portal-main-area {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      width: 100%;
      padding: 0.75rem 0.5rem 6rem;
    }

    @media (min-width: 576px) {
      .portal-main-area {
        padding: 1.5rem 1rem 6rem;
      }
    }

    .portal-mobile-frame {
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
    }

    /* Ambient background shapes */
    .portal-bg-decor {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 380px;
      background: linear-gradient(180deg, rgba(6, 78, 59, 0.08) 0%, rgba(5, 150, 105, 0.04) 70%, transparent 100%);
      pointer-events: none;
      z-index: 0;
    }

    /* Card Styling */
    .portal-card {
      background: #FFFFFF;
      border: 1px solid rgba(226, 232, 240, 0.85);
      border-radius: 20px;
      box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.06), 0 4px 10px -2px rgba(0, 0, 0, 0.03);
      overflow: hidden;
      position: relative;
    }

    /* Touch Button active effects */
    .btn-mobile-touch {
      transition: transform 0.12s ease, box-shadow 0.12s ease;
      touch-action: manipulation;
    }
    .btn-mobile-touch:active {
      transform: scale(0.97);
    }

    /* Segmented Navigation Tab Pill */
    .portal-nav-pill {
      font-weight: 600;
      font-size: 0.82rem;
      border-radius: 12px;
      padding: 0.55rem 0.5rem;
      color: #64748B;
      transition: all 0.2s ease;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.35rem;
      white-space: nowrap;
    }

    .portal-nav-pill.active {
      background-color: #059669 !important;
      color: #FFFFFF !important;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    /* Mobile Bottom Navigation Bar */
    .portal-bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-top: 1px solid rgba(226, 232, 240, 0.9);
      padding: 0.45rem 1rem calc(0.45rem + env(safe-area-inset-bottom, 0px));
      z-index: 1040;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
    }

    .portal-bottom-nav-inner {
      max-width: 500px;
      margin: 0 auto;
      display: flex;
      justify-content: space-around;
      align-items: center;
    }

    .portal-bottom-tab {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: none;
      border: none;
      padding: 0.25rem 0.6rem;
      color: #94A3B8;
      font-size: 10px;
      font-weight: 600;
      border-radius: 12px;
      transition: all 0.15s ease;
      position: relative;
      touch-action: manipulation;
    }

    .portal-bottom-tab i {
      font-size: 1.25rem;
      line-height: 1.2;
      margin-bottom: 2px;
      transition: transform 0.15s ease;
    }

    .portal-bottom-tab.active {
      color: #059669;
    }

    .portal-bottom-tab.active i {
      transform: scale(1.15);
      color: #059669;
    }

    .portal-bottom-tab:active {
      transform: scale(0.92);
    }

    .portal-badge-count {
      position: absolute;
      top: 0px;
      right: 12px;
      background-color: #EF4444;
      color: white;
      font-size: 9px;
      font-weight: 700;
      border-radius: 10px;
      padding: 1px 5px;
      line-height: 1.2;
    }

    /* Keypad styling for mobile PIN */
    .pin-box {
      width: 44px;
      height: 52px;
      border: 2px solid #E2E8F0;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: 700;
      font-family: monospace;
      color: #064E3B;
      background: #F8FAFC;
      transition: all 0.15s ease;
    }
    .pin-box.filled {
      border-color: #059669;
      background: #ECFDF5;
      color: #064E3B;
    }
    .pin-box.active-cursor {
      border-color: #10B981;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
      background: #FFFFFF;
    }

    .keypad-btn {
      width: 100%;
      height: 56px;
      border: 1px solid #E2E8F0;
      background: #FFFFFF;
      border-radius: 14px;
      font-size: 20px;
      font-weight: 700;
      color: #1E293B;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
      transition: all 0.1s ease;
      touch-action: manipulation;
    }
    .keypad-btn:active {
      background: #F1F5F9;
      transform: scale(0.95);
      box-shadow: none;
    }
    .keypad-btn-sub {
      font-size: 9px;
      letter-spacing: 0.1em;
      color: #94A3B8;
      margin-top: -3px;
      font-weight: 600;
    }
  </style>

  @livewireStyles
  @stack('styles')
</head>

<body>
  <div class="portal-bg-decor"></div>

  <main class="portal-main-area">
    <div class="portal-mobile-frame">
      @yield('content')
      {{ $slot ?? '' }}
    </div>
  </main>

  <footer class="py-3 text-center text-muted small mt-auto" style="padding-bottom: 5rem !important;">
    <div class="portal-mobile-frame px-3">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <i class="bi bi-heart-pulse-fill text-success"></i>
        <span class="fw-bold text-dark" style="font-size: 13px;">Community of Love (COOL)</span>
        <span>&bull;</span>
        <span style="font-size: 13px;">GBI Salemba</span>
      </div>
      <p class="mb-0 text-secondary" style="font-size: 11px;">
        &copy; {{ date('Y') }} Gereja Bethel Indonesia Salemba &bull; Bertumbuh bersama dalam kasih Kristus.
      </p>
    </div>
  </footer>

  <!-- Local Bootstrap bundle -->
  <script src="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  @livewireScripts
  @stack('scripts')
</body>

</html>
