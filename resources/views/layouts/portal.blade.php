<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Portal Jemaat COOL') - GBI Salemba</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('spark-admin-1.0.0/assets/images/favicon.ico') }}">

  <!-- Local Third-Party Libraries (Spark Admin) -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-enhanced.css') }}">

  <style>
    body {
      background: radial-gradient(circle at 10% 20%, rgba(180, 241, 5, 0.05) 0%, rgba(244, 246, 245, 1) 90%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .portal-main-area {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem 1rem;
    }
  </style>

  @livewireStyles
  @stack('styles')
</head>

<body>

  <main class="portal-main-area">
    <div class="w-100" style="max-width: 760px;">
      @yield('content')
      {{ $slot ?? '' }}
    </div>
  </main>

  <footer class="py-3 text-center text-muted small">
    &copy; {{ date('Y') }} Community of Love (COOL) &bull; Gereja Bethel Indonesia Salemba
  </footer>

  <!-- Local Bootstrap bundle -->
  <script src="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  @livewireScripts
  @stack('scripts')
</body>

</html>
