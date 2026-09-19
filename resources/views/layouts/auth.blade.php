<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Masuk') - Sistem Manajemen COOL GBI Salemba</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('spark-admin-1.0.0/assets/images/favicon.ico') }}">

  <!-- Local Third-Party Libraries (Spark Admin) -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('spark-admin-1.0.0/assets/css/main.css') }}">

  @livewireStyles
  @stack('styles')
</head>

<body>

  <!-- Authentication Container & Login Card -->
  <div class="login-wrapper">
    <!-- Glowing background shapes for modern visual appearance -->
    <div class="login-bg-shape login-bg-shape-1"></div>
    <div class="login-bg-shape login-bg-shape-2"></div>

    <!-- Main centered login card -->
    <div class="login-card">

      <!-- Brand Identity -->
      <a href="{{ url('/') }}" class="login-brand text-decoration-none">
        <i class="bi bi-heart-pulse-fill text-lime"></i>
        <span>COOL Salemba</span>
      </a>

      @yield('content')
      {{ $slot ?? '' }}

    </div>
  </div>

  <!-- Local Bootstrap bundle -->
  <script src="{{ asset('spark-admin-1.0.0/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Custom Authentication interactions script -->
  <script src="{{ asset('spark-admin-1.0.0/assets/js/auth.js') }}"></script>

  @livewireScripts
  @stack('scripts')
</body>

</html>
