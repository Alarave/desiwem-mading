@extends('layouts.app')

@section('title', 'Sign In — DeSiWeM')

@section('styles')
<style>
  .auth-wrapper {
    min-height: calc(85vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
  }

  .auth-card {
    width: 100%;
    max-width: 24rem;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }

  .auth-card-header {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    padding: 1.5rem 1.5rem 0.5rem;
    text-align: center;
  }

  .auth-card-title {
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: -0.025em;
    color: #0f172a;
    line-height: 1.2;
    margin: 0;
  }

  .auth-card-desc {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
  }

  .auth-card-content {
    padding: 1rem 1.5rem 1.5rem;
  }

  .auth-field-group {
    display: flex;
    flex-direction: column;
    gap: 1.15rem;
  }

  .auth-field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }

  .auth-field-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #0f172a;
    margin: 0;
  }

  .auth-input {
    display: flex;
    height: 2.35rem;
    width: 100%;
    border-radius: 0.375rem;
    border: 1px solid #cbd5e1;
    background-color: #ffffff;
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
    color: #0f172a;
    transition: all 0.15s ease-in-out;
  }

  .auth-input:focus {
    outline: none;
    border-color: #0f172a;
    box-shadow: 0 0 0 1px #0f172a;
  }

  .auth-password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .auth-password-wrapper .auth-input {
    padding-right: 2.5rem;
  }

  .auth-password-toggle {
    position: absolute;
    right: 0.5rem;
    background: none;
    border: none;
    color: #64748b;
    padding: 0.25rem 0.4rem;
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.25rem;
  }

  .auth-password-toggle:hover {
    color: #0f172a;
  }

  .auth-checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: -0.25rem;
  }

  .auth-checkbox {
    width: 1rem;
    height: 1rem;
    accent-color: #0f172a;
    border-radius: 0.25rem;
    cursor: pointer;
  }

  .auth-checkbox-label {
    font-size: 0.8125rem;
    color: #475569;
    cursor: pointer;
    user-select: none;
  }

  .auth-actions {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    margin-top: 1.25rem;
  }

  .auth-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 2.35rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    background-color: #0f172a;
    color: #ffffff;
    border: 1px solid #0f172a;
    cursor: pointer;
    transition: background-color 0.15s ease;
    text-decoration: none;
  }

  .auth-btn-primary:hover:not(:disabled) {
    background-color: #1e293b;
    color: #ffffff;
  }

  .auth-btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }

  .auth-btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 2.35rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    background-color: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: background-color 0.15s ease;
    text-decoration: none;
  }

  .auth-btn-outline:hover {
    background-color: #f8fafc;
    color: #0f172a;
  }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
  <div class="auth-card">
    <div class="auth-card-header">
      <h2 class="auth-card-title">Sign In</h2>
      <p class="auth-card-desc">Masuk untuk mengelola publikasi mading DeSiWeM.</p>
    </div>

    <div class="auth-card-content">
      @if($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3 border-0 rounded-2" style="font-size: 0.825rem; background-color: #fef2f2; color: #991b1b;">
          <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" id="auth-login-form">
        @csrf
        <div class="auth-field-group">
          <!-- Username -->
          <div class="auth-field">
            <label class="auth-field-label" for="login-username">Username</label>
            <input 
              id="login-username"
              name="username"
              type="text" 
              class="auth-input" 
              placeholder="Masukkan username"
              value="{{ old('username') }}" 
              autocomplete="username"
              required 
              autofocus 
            />
          </div>

          <!-- Password -->
          <div class="auth-field">
            <label class="auth-field-label" for="login-password">Password</label>
            <div class="auth-password-wrapper">
              <input 
                id="login-password"
                name="password" 
                type="password" 
                class="auth-input" 
                placeholder="Masukkan password"
                autocomplete="current-password"
                required 
              />
              <button type="button" class="auth-password-toggle" id="authPasswordToggle" aria-label="Lihat kata sandi">
                <i class="bi bi-eye" id="authPasswordEyeIcon"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="auth-checkbox-group">
            <input type="checkbox" name="remember" id="login-remember" class="auth-checkbox" value="1" {{ old('remember') ? 'checked' : '' }}>
            <label for="login-remember" class="auth-checkbox-label">Ingat saya pada perangkat ini</label>
          </div>

          <!-- Submit & Actions -->
          <div class="auth-actions">
            <button type="submit" class="auth-btn-primary" id="authSubmitBtn">
              <span id="authSubmitText">Sign In</span>
            </button>
            <a href="{{ route('mading.index') }}" class="auth-btn-outline">
              <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  (function () {
    // Password visibility toggle
    const toggleBtn = document.getElementById('authPasswordToggle');
    const pwdInput = document.getElementById('login-password');
    const eyeIcon = document.getElementById('authPasswordEyeIcon');

    if (toggleBtn && pwdInput && eyeIcon) {
      toggleBtn.addEventListener('click', function () {
        const isPassword = pwdInput.getAttribute('type') === 'password';
        pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
        eyeIcon.classList.toggle('bi-eye', !isPassword);
        eyeIcon.classList.toggle('bi-eye-slash', isPassword);
      });
    }

    // Double submit protection
    const form = document.getElementById('auth-login-form');
    const submitBtn = document.getElementById('authSubmitBtn');
    const submitText = document.getElementById('authSubmitText');

    if (form && submitBtn && submitText) {
      form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitText.textContent = 'Memproses...';
      });
    }
  })();
</script>
@endsection
