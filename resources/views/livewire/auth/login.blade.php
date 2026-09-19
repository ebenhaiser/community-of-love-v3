<div>
  <p class="login-subtitle">Masuk untuk mengakses Sistem Manajemen COOL</p>

  <form wire:submit="login" class="needs-validation">
    @if ($errors->has('account'))
      <div class="alert alert-danger py-2 small mb-3">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first('account') }}
      </div>
    @endif

    <!-- Username / Email Input Group -->
    <div class="login-form-group">
      <label for="account" class="login-form-label">Username atau Email</label>
      <div class="login-input-group">
        <i class="bi bi-person input-icon"></i>
        <input type="text" id="account" wire:model="account" class="login-input @error('account') is-invalid @enderror"
          placeholder="master atau budi.santoso@gbisalemba.org" autofocus required>
      </div>
    </div>

    <!-- Password Input Group -->
    <div class="login-form-group">
      <label for="password" class="login-form-label">Kata Sandi</label>
      <div class="login-input-group">
        <i class="bi bi-shield-lock input-icon"></i>
        <input type="password" id="password" wire:model="password" class="login-input @error('password') is-invalid @enderror"
          placeholder="••••••••" required>
      </div>
      @error('password')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
      @enderror
    </div>

    <!-- Options (Remember me) -->
    <div class="login-options">
      <label class="custom-control-label">
        <input type="checkbox" wire:model="remember" class="custom-checkbox-input" id="rememberMe">
        <span>Ingat Saya</span>
      </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-login" id="btn-submit" wire:loading.attr="disabled">
      <span wire:loading.remove>Masuk ke Dashboard</span>
      <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i> Memproses...</span>
      <i class="bi bi-arrow-right" wire:loading.remove></i>
    </button>
  </form>

  <!-- Default Credentials Info for Testing -->
  <div class="mt-4 p-3 bg-light rounded text-start small border">
    <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle-fill text-primary me-1"></i> Akun Pengujian (Seeder):</div>
    <div class="text-muted">Master: <code>master</code> / <code>{{ $defaultPassword }}</code></div>
    <div class="text-muted">Gembala: <code>gembala.budi</code> / <code>{{ $defaultPassword }}</code></div>
  </div>
</div>
