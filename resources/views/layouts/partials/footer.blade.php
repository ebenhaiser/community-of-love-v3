<footer class="footer-custom">
  <div class="footer-left">
    <span class="footer-logo">
      <i class="bi bi-heart-pulse-fill text-lime"></i> COOL GBI Salemba
    </span>
    <span class="footer-separator">|</span>
    <span class="footer-copy">&copy; {{ date('Y') }} Sistem Manajemen Komunitas COOL GBI Salemba.</span>
  </div>
  <div class="footer-right">
    <ul class="footer-links">
      <li><a href="{{ url('/dashboard') }}" class="footer-link">Dashboard</a></li>
      <li><a href="{{ url('/statistics') }}" class="footer-link">Statistik</a></li>
      <li><a href="{{ url('/qr-access') }}" class="footer-link">Akses QR</a></li>
      <li><a href="#" class="footer-link">Status <span class="status-dot"></span></a></li>
    </ul>
  </div>
</footer>
