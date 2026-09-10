<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Admin — DeSiWeM')</title>

  <!-- Google Fonts & Typography (Lexington Carbon Style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://rsms.me/">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Master Stylesheets -->
  <link rel="stylesheet" href="{{ asset('css/Public.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @yield('styles')
</head>
<body>

  <!-- Full-Screen Mobile Nav Overlay (Lexington Carbon) -->
  <div class="mobile-nav-overlay" id="mobileNavOverlay" aria-hidden="true">
    <div class="mobile-nav-header">
      <a class="carbon-brand" href="{{ route('mading.index') }}">
        <span class="carbon-brand-name">DeSiWeM</span>
      </a>
      <button type="button" class="carbon-hamburger-btn" id="mobileNavClose" aria-label="Tutup Menu">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div class="mobile-nav-body">
      <a href="{{ route('mading.index') }}" class="mobile-nav-link" target="_blank">
        <span>Lihat Mading Publik</span>
        <i class="bi bi-box-arrow-up-right small text-muted"></i>
      </a>
      <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span>Dashboard</span>
        @if(request()->routeIs('admin.dashboard'))
          <span class="mobile-nav-link-badge">Aktif</span>
        @endif
      </a>
      <a href="{{ route('admin.categories') }}" class="mobile-nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <span>Kelola Kategori</span>
        @if(request()->routeIs('admin.categories*'))
          <span class="mobile-nav-link-badge">Aktif</span>
        @endif
      </a>
      <a href="{{ route('admin.articles') }}" class="mobile-nav-link {{ request()->routeIs('admin.articles*') || request()->routeIs('articles.*') ? 'active' : '' }}">
        <span>Kelola Artikel</span>
        @if(request()->routeIs('admin.articles*') || request()->routeIs('articles.*'))
          <span class="mobile-nav-link-badge">Aktif</span>
        @endif
      </a>
      <a href="{{ route('admin.report') }}" class="mobile-nav-link {{ request()->routeIs('admin.report*') ? 'active' : '' }}">
        <span>Cetak Laporan</span>
        @if(request()->routeIs('admin.report*'))
          <span class="mobile-nav-link-badge">Aktif</span>
        @endif
      </a>
    </div>

    <div class="mobile-nav-footer">
      @auth
        <form method="POST" action="{{ route('logout') }}" class="w-100 mb-0">
          @csrf
          <button type="submit" class="mobile-nav-auth-btn w-100 text-center text-danger border-0">
            Keluar Sesi
          </button>
        </form>
      @endauth
      <div class="text-center text-muted small mt-2">
        &copy; {{ date('Y') }} DeSiWeM. Panel Admin.
      </div>
    </div>
  </div>

  <!-- Lexington Carbon Editorial Navigation Header (Matched with Public) -->
  <header class="carbon-navbar sticky-top">
    <div class="carbon-container d-flex align-items-center justify-content-between h-100">
      <!-- Brand Wordmark -->
      <a class="carbon-brand" href="{{ route('mading.index') }}">
        <span class="carbon-brand-name">DeSiWeM</span>
      </a>

      <!-- Desktop Minimalist Nav Links -->
      <nav class="carbon-nav-links d-none d-md-flex align-items-center">
        <a href="{{ route('mading.index') }}" class="carbon-nav-link" target="_blank" title="Buka Mading Publik">
          <span>Mading Publik</span>
          <i class="bi bi-box-arrow-up-right ms-1 small text-muted"></i>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="carbon-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          Dashboard
        </a>
        <a href="{{ route('admin.categories') }}" class="carbon-nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
          Kategori
        </a>
        <a href="{{ route('admin.articles') }}" class="carbon-nav-link {{ request()->routeIs('admin.articles*') || request()->routeIs('articles.*') ? 'active' : '' }}">
          Artikel
        </a>
        <a href="{{ route('admin.report') }}" class="carbon-nav-link {{ request()->routeIs('admin.report*') ? 'active' : '' }}">
          Laporan
        </a>
      </nav>

      <!-- Actions: Logout & Mobile Hamburger -->
      <div class="d-flex align-items-center gap-2">
        @auth
          <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
            @csrf
            <button type="submit" class="carbon-btn-ghost-sm" style="cursor: pointer;" title="Keluar">
              <span>Keluar</span>
              <i class="bi bi-box-arrow-right"></i>
            </button>
          </form>
        @endauth

        <!-- Mobile Hamburger Button -->
        <button class="carbon-hamburger-btn d-md-none" id="mobileNavToggle" type="button" aria-label="Buka Menu">
          <i class="bi bi-list fs-5"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Main Content Container -->
  <main class="container" style="padding-top: 2.5rem; padding-bottom: 4rem;">
    <div class="admin-layout">
      
      <!-- Admin Sidebar Navigation (WhatsApp Web-Themed Animated) -->
      <aside class="admin-sidebar">
        <div class="sidebar-title">
          <i class="bi bi-grid-fill"></i> Menu Panel
        </div>
        
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="wa-icon-box">
            <i class="bi bi-speedometer2"></i>
          </span>
          <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
          <span class="wa-icon-box">
            <i class="bi bi-folder2-open"></i>
          </span>
          <span>Kelola Kategori</span>
        </a>
        
        <a href="{{ route('admin.articles') }}" class="sidebar-link {{ request()->routeIs('admin.articles') || request()->routeIs('articles.*') ? 'active' : '' }}">
          <span class="wa-icon-box">
            <i class="bi bi-journal-richtext"></i>
          </span>
          <span>Kelola Artikel</span>
        </a>
        
        <a href="{{ route('admin.report') }}" class="sidebar-link {{ request()->routeIs('admin.report') ? 'active' : '' }}">
          <span class="wa-icon-box">
            <i class="bi bi-printer"></i>
          </span>
          <span>Cetak Laporan</span>
        </a>
        
        <div class="sidebar-title" style="margin-top: 1rem;">
          <i class="bi bi-shield-lock-fill"></i> Sesi Anda
        </div>
        
        <form action="{{ route('logout') }}" method="POST" id="logoutForm" class="d-none">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" id="logout-btn" class="sidebar-link sidebar-link-logout">
          <span class="wa-icon-box">
            <i class="bi bi-box-arrow-right"></i>
          </span>
          <span>Logout</span>
        </a>

        <!-- WhatsApp Web User Profile Panel at Bottom -->
        <div class="wa-user-panel">
          <div class="wa-user-avatar">
            {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
            <span class="wa-online-dot" title="Online"></span>
          </div>
          <div class="wa-user-info">
            <span class="wa-user-name">{{ auth()->user()->username ?? 'Administrator' }}</span>
            <span class="wa-user-role">Sesi Aktif</span>
          </div>
        </div>
      </aside>

      <!-- Admin Main Panel Content -->
      <section class="admin-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
      </section>
    </div>
  </main>

  <!-- Animation & Script Dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('mobileNavToggle');
        const closeBtn = document.getElementById('mobileNavClose');
        const overlay = document.getElementById('mobileNavOverlay');

        function openMenu() {
            if (!overlay) return;
            overlay.classList.add('is-open');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.classList.add('mobile-nav-locked');
        }

        function closeMenu() {
            if (!overlay) return;
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('mobile-nav-locked');
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay && overlay.classList.contains('is-open')) {
                closeMenu();
            }
        });
    });
  </script>
  @yield('scripts')
</body>
</html>
