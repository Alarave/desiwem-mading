<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Admin — DeSiWeM')</title>

  <!-- Google Fonts & Typography -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Master Stylesheets -->
  <link rel="stylesheet" href="{{ asset('css/public.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
  @yield('styles')
</head>
<body>

  <div class="acet-admin-shell">
    
    <!-- ====================================================================
         DESKTOP EXPANDABLE SIDEBAR
         Collapsed: 60px (Icon-only) | Hover / Focus: 300px (Smooth Expand)
         ==================================================================== -->
    <aside class="acet-desktop-sidebar" id="acetDesktopSidebar" aria-label="Navigasi Panel Admin">
      
      <!-- Top Section: Brand + Links -->
      <div class="acet-sidebar-top">
        
        <!-- Logo / Brand Link -->
        <a href="{{ route('admin.dashboard') }}" class="acet-brand-link" title="DeSiWeM Admin">
          <div class="acet-brand-icon">
            <span style="color: #fff; font-weight: 800; font-size: 0.75rem;">D</span>
          </div>
          <div class="acet-brand-text">
            <span class="acet-brand-title">DeSiWeM</span>
            <span class="acet-brand-sub">Panel Administrator</span>
          </div>
        </a>

        <!-- Main Navigation Links -->
        <ul class="acet-nav-list">
          
          <!-- Dashboard -->
          <li>
            <a href="{{ route('admin.dashboard') }}" 
               class="acet-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
               title="Dashboard">
              <div class="acet-nav-icon">
                <i class="bi bi-grid-1x2"></i>
              </div>
              <span class="acet-nav-label">Dashboard</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>

          <!-- Kelola Kategori -->
          <li>
            <a href="{{ route('admin.categories') }}" 
               class="acet-nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" 
               title="Kelola Kategori">
              <div class="acet-nav-icon">
                <i class="bi bi-tags"></i>
              </div>
              <span class="acet-nav-label">Kelola Kategori</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>

          <!-- Kelola Artikel -->
          <li>
            <a href="{{ route('admin.articles') }}" 
               class="acet-nav-link {{ request()->routeIs('admin.articles*') || request()->routeIs('articles.*') ? 'active' : '' }}" 
               title="Kelola Artikel">
              <div class="acet-nav-icon">
                <i class="bi bi-journal-richtext"></i>
              </div>
              <span class="acet-nav-label">Kelola Artikel</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>

          <!-- Cetak Laporan -->
          <li>
            <a href="{{ route('admin.report') }}" 
               class="acet-nav-link {{ request()->routeIs('admin.report*') ? 'active' : '' }}" 
               title="Cetak Laporan">
              <div class="acet-nav-icon">
                <i class="bi bi-printer"></i>
              </div>
              <span class="acet-nav-label">Cetak Laporan</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>

          <!-- Divider -->
          <li class="acet-nav-divider"></li>

          <!-- Mading Publik Link (Tab Baru) -->
          <li>
            <a href="{{ route('mading.index') }}" 
               target="_blank" 
               class="acet-nav-link" 
               title="Buka Papan Mading Publik">
              <div class="acet-nav-icon">
                <i class="bi bi-box-arrow-up-right"></i>
              </div>
              <span class="acet-nav-label">Mading Publik</span>
            </a>
          </li>

        </ul>
      </div>

      <!-- Bottom Dock: User Profile & Logout -->
      <div class="acet-sidebar-bottom">
        
        <!-- User Profile Card -->
        <div class="acet-profile-link" title="{{ auth()->user()->username ?? 'Administrator' }}">
          <div class="acet-avatar">
            {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
            <span class="acet-avatar-online"></span>
          </div>
          <div class="acet-profile-details">
            <span class="acet-profile-name">{{ auth()->user()->username ?? 'Administrator' }}</span>
            <span class="acet-profile-role">{{ auth()->user()->role ?? 'Admin Sesi' }}</span>
          </div>
        </div>

        <!-- Secure Logout Form -->
        <form method="POST" action="{{ route('logout') }}" id="acetLogoutForm" class="m-0 p-0">
          @csrf
          <button type="submit" class="acet-logout-button" title="Keluar Sesi">
            <div class="acet-nav-icon">
              <i class="bi bi-box-arrow-right"></i>
            </div>
            <span class="acet-nav-label">Keluar Sesi</span>
          </button>
        </form>

      </div>
    </aside>

    <!-- ====================================================================
         MOBILE TOPBAR & FULLSCREEN DRAWER
         ==================================================================== -->
    <div class="w-100 d-flex flex-column d-lg-none">
      <header class="acet-mobile-topbar">
        <a href="{{ route('admin.dashboard') }}" class="acet-mobile-brand">
          <div class="acet-brand-icon" style="width: 22px; height: 18px;">
            <span style="color: #fff; font-weight: 800; font-size: 0.7rem;">D</span>
          </div>
          <span>DeSiWeM</span>
        </a>
        <button type="button" class="acet-mobile-menu-trigger" id="acetMobileToggle" aria-label="Buka Menu Panel">
          <i class="bi bi-list fs-4"></i>
        </button>
      </header>
    </div>

    <!-- Fullscreen Mobile Drawer -->
    <div class="acet-mobile-drawer" id="acetMobileDrawer" aria-hidden="true">
      <button type="button" class="acet-mobile-close-btn" id="acetMobileClose" aria-label="Tutup Menu">
        <i class="bi bi-x-lg"></i>
      </button>

      <!-- Drawer Top -->
      <div>
        <a href="{{ route('admin.dashboard') }}" class="acet-brand-link mb-4">
          <div class="acet-brand-icon">
            <span style="color: #fff; font-weight: 800; font-size: 0.75rem;">D</span>
          </div>
          <div class="acet-brand-text" style="opacity: 1; max-width: 100%;">
            <span class="acet-brand-title">DeSiWeM</span>
            <span class="acet-brand-sub">Panel Administrator</span>
          </div>
        </a>

        <ul class="acet-nav-list">
          <li>
            <a href="{{ route('admin.dashboard') }}" class="acet-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <div class="acet-nav-icon"><i class="bi bi-grid-1x2"></i></div>
              <span class="acet-nav-label">Dashboard</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.categories') }}" class="acet-nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
              <div class="acet-nav-icon"><i class="bi bi-tags"></i></div>
              <span class="acet-nav-label">Kelola Kategori</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.articles') }}" class="acet-nav-link {{ request()->routeIs('admin.articles*') || request()->routeIs('articles.*') ? 'active' : '' }}">
              <div class="acet-nav-icon"><i class="bi bi-journal-richtext"></i></div>
              <span class="acet-nav-label">Kelola Artikel</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.report') }}" class="acet-nav-link {{ request()->routeIs('admin.report*') ? 'active' : '' }}">
              <div class="acet-nav-icon"><i class="bi bi-printer"></i></div>
              <span class="acet-nav-label">Cetak Laporan</span>
              <span class="acet-active-dot"></span>
            </a>
          </li>
          <li class="acet-nav-divider"></li>
          <li>
            <a href="{{ route('mading.index') }}" target="_blank" class="acet-nav-link">
              <div class="acet-nav-icon"><i class="bi bi-box-arrow-up-right"></i></div>
              <span class="acet-nav-label">Mading Publik</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Drawer Bottom -->
      <div class="acet-sidebar-bottom">
        <div class="acet-profile-link mb-2">
          <div class="acet-avatar">
            {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
            <span class="acet-avatar-online"></span>
          </div>
          <div class="acet-profile-details">
            <span class="acet-profile-name">{{ auth()->user()->username ?? 'Administrator' }}</span>
            <span class="acet-profile-role">Sesi Aktif</span>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
          @csrf
          <button type="submit" class="acet-logout-button">
            <div class="acet-nav-icon"><i class="bi bi-box-arrow-right"></i></div>
            <span class="acet-nav-label">Keluar Sesi</span>
          </button>
        </form>
      </div>
    </div>

    <!-- ====================================================================
         MAIN CONTENT AREA
         ==================================================================== -->
    <main class="acet-main-content">
      <div class="acet-content-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @yield('content')
      </div>
    </main>

  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/admin-sidebar.js') }}"></script>
  @yield('scripts')
</body>
</html>
