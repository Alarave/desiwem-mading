@extends('layouts.app')

@section('title', 'Sign in - Mading ASESOR')

@section('styles')
<style>
  .shadcn-wrapper {
    min-height: calc(85vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
  }

  .shadcn-card {
    width: 100%;
    max-width: 24rem; /* max-w-sm */
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }

  .shadcn-card-header {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    padding: 1.5rem 1.5rem 0.5rem;
    text-align: center;
  }

  .shadcn-card-title {
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: -0.025em;
    color: #0f172a;
    line-height: 1.2;
    margin: 0;
  }

  .shadcn-card-desc {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
  }

  .shadcn-card-content {
    padding: 1rem 1.5rem;
  }

  .shadcn-field-group {
    display: flex;
    flex-direction: column;
    gap: 1.15rem;
  }

  .shadcn-field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }

  .shadcn-field-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #0f172a;
    margin: 0;
  }

  .shadcn-input {
    display: flex;
    height: 2.35rem;
    width: 100%;
    border-radius: 0.375rem;
    border: 1px solid #cbd5e1;
    background-color: transparent;
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
    color: #0f172a;
    transition: all 0.15s ease-in-out;
  }

  .shadcn-input:focus {
    outline: none;
    border-color: #0f172a;
    box-shadow: 0 0 0 1px #0f172a;
  }

  .shadcn-field-desc {
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 0.15rem;
  }

  .shadcn-card-footer {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 0.5rem 1.5rem 1.5rem;
  }

  .shadcn-btn-primary {
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

  .shadcn-btn-primary:hover {
    background-color: #1e293b;
    color: #ffffff;
  }

  .shadcn-btn-outline {
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

  .shadcn-btn-outline:hover {
    background-color: #f8fafc;
    color: #0f172a;
  }

  .shadcn-btn-link {
    background: none;
    border: none;
    padding: 0;
    font-size: 0.825rem;
    color: #64748b;
    text-decoration: none;
    cursor: pointer;
  }

  .shadcn-btn-link:hover {
    color: #0f172a;
    text-decoration: underline;
  }
</style>
@endsection

@section('content')
<div class="shadcn-wrapper">
  <!-- Card06 Adaptation -->
  <div class="shadcn-card">
    <div class="shadcn-card-header">
      <h2 class="shadcn-card-title">Sign in</h2>
      <p class="shadcn-card-desc">Enter your credentials to access your account.</p>
    </div>

    <div class="shadcn-card-content">
      @if($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3 border-0 rounded-2" style="font-size: 0.825rem; background-color: #fef2f2; color: #991b1b;">
          <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" id="login-form">
        @csrf
        <div class="shadcn-field-group">
          <!-- Username / Email Field -->
          <div class="shadcn-field">
            <label class="shadcn-field-label" for="card-06-username">Username</label>
            <input 
              id="card-06-username"
              name="username"
              type="text" 
              class="shadcn-input" 
              placeholder="admin"
              value="{{ old('username') }}" 
              required 
              autofocus 
            />
          </div>

          <!-- Password Field -->
          <div class="shadcn-field">
            <label class="shadcn-field-label" for="card-06-password">Password</label>
            <input 
              id="card-06-password"
              name="password" 
              type="password" 
              class="shadcn-input" 
              required 
            />
            <div class="shadcn-field-desc">
              Must be at least 8 characters.
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="shadcn-card-footer">
      <button type="submit" form="login-form" class="shadcn-btn-primary">
        Sign in
      </button>
      <a href="{{ route('mading.index') }}" class="shadcn-btn-outline">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</div>
@endsection
