<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title & SEO Description -->
    <title>@yield('title', 'DeSiWeM — Digital School Information & Wall Magazine')</title>
    <meta name="description" content="@yield('meta_description', 'Portal majalah dinding digital resmi DeSiWeM. Menyajikan artikel ilmiah, karya literasi, agenda, dan inovasi civitas akademika.')">
    <meta name="keywords" content="@yield('meta_keywords', 'desiwem, mading digital, majalah dinding digital, karya mahasiswa, artikel ilmiah, berita kampus')">
    <meta name="author" content="@yield('meta_author', 'DeSiWeM')">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Search Engine Robots -->
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">

    <!-- Theme Color for Mobile Browsers -->
    <meta name="theme-color" content="#faf8f5">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:title" content="@yield('title', 'DeSiWeM — Digital School Information & Wall Magazine')">
    <meta property="og:description" content="@yield('meta_description', 'Portal majalah dinding digital resmi DeSiWeM. Menyajikan artikel ilmiah, karya literasi, agenda, dan inovasi civitas akademika.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-banner.jpg'))">
    <meta property="og:image:alt" content="@yield('title', 'DeSiWeM — Digital School Information & Wall Magazine')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="DeSiWeM Portal Mading Digital">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('canonical_url', url()->current())">
    <meta name="twitter:title" content="@yield('title', 'DeSiWeM — Digital School Information & Wall Magazine')">
    <meta name="twitter:description" content="@yield('meta_description', 'Portal majalah dinding digital resmi DeSiWeM. Menyajikan artikel ilmiah, karya literasi, agenda, dan inovasi civitas akademika.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-banner.jpg'))">
    <meta name="twitter:site" content="@desiwem">
    <meta name="twitter:creator" content="@desiwem">

    <!-- Google Fonts & Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Serif:opsz@12..24&family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Favicons -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Master Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    @yield('meta')
</head>
<body>

    <!-- Full-Screen Mobile Nav Overlay (Anti-UI-Slop) -->
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
            <a href="{{ route('mading.index') }}" class="mobile-nav-link {{ request()->routeIs('mading.index') && !request('category_id') && !request()->route('tag') ? 'active' : '' }}">
                <span>Beranda Mading</span>
                @if(request()->routeIs('mading.index') && !request('category_id') && !request()->route('tag'))
                    <span class="mobile-nav-link-badge">Aktif</span>
                @endif
            </a>
            @php
                $allNavCategories = \App\Models\Category::all();
            @endphp
            @foreach($allNavCategories as $navC)
                <a href="{{ route('mading.tag', strtolower($navC->name)) }}" 
                   class="mobile-nav-link {{ (isset($activeCategory) && $activeCategory->id == $navC->id) || request()->route('tag') == strtolower($navC->name) || request('category_id') == $navC->id ? 'active' : '' }}">
                    <span>{{ $navC->name }}</span>
                    @if((isset($activeCategory) && $activeCategory->id == $navC->id) || request()->route('tag') == strtolower($navC->name) || request('category_id') == $navC->id)
                        <span class="mobile-nav-link-badge">Aktif</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mobile-nav-footer">
            @auth
                <a class="mobile-nav-auth-btn" href="{{ route('admin.dashboard') }}">
                    Panel Admin
                </a>
            @else
                <a class="mobile-nav-auth-btn" href="{{ route('login') }}">
                    Login Admin
                </a>
            @endauth
            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} DeSiWeM. Editorial Edition.
            </div>
        </div>
    </div>

    <!-- Lexington Carbon Editorial Navigation Header -->
    <header class="carbon-navbar sticky-top">
        <div class="carbon-container d-flex align-items-center justify-content-between h-100">
            <!-- Brand Wordmark -->
            <a class="carbon-brand" href="{{ route('mading.index') }}">
                <span class="carbon-brand-name">DeSiWeM</span>
            </a>
            
            <!-- Desktop Minimalist Nav Links -->
            <nav class="carbon-nav-links d-none d-md-flex align-items-center">
                <a href="{{ route('mading.index') }}" 
                   class="carbon-nav-link {{ request()->routeIs('mading.index') && !request('category_id') && !request()->route('tag') ? 'active' : '' }}">
                    Beranda
                </a>
                @foreach($allNavCategories->take(4) as $hCat)
                    <a href="{{ route('mading.tag', strtolower($hCat->name)) }}" 
                       class="carbon-nav-link {{ (isset($activeCategory) && $activeCategory->id == $hCat->id) || request()->route('tag') == strtolower($hCat->name) || request('category_id') == $hCat->id ? 'active' : '' }}">
                        {{ $hCat->name }}
                    </a>
                @endforeach
            </nav>

            <!-- Actions: Admin / Login & Mobile Hamburger -->
            <div class="d-flex align-items-center gap-2">
                @auth
                    <a class="carbon-btn-ghost-sm" href="{{ route('admin.dashboard') }}">
                        Panel Admin
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                @else
                    <a class="carbon-btn-ghost-sm" href="{{ route('login') }}">
                        Login
                        <i class="bi bi-arrow-right"></i>
                    </a>
                @endauth

                <!-- Mobile Hamburger Button -->
                <button class="carbon-hamburger-btn d-md-none" id="mobileNavToggle" type="button" aria-label="Buka Menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-4 py-md-5">
        @yield('content')
    </main>

    <!-- Footer (Lexington Carbon Editorial) -->
    <footer class="carbon-footer">
        <div class="carbon-container">
            <div class="carbon-footer-content">
                <div>
                    <h3 class="carbon-newsletter-title">
                        Tetap terhubung dengan kabar terbaru ASESOR
                    </h3>
                    <form class="carbon-newsletter-form" onsubmit="return false;">
                        <input
                            type="email"
                            placeholder="Alamat email..."
                            class="carbon-newsletter-input"
                            aria-label="Email subscription"
                        >
                        <button
                            type="submit"
                            class="carbon-newsletter-btn"
                        >
                            Langganan
                        </button>
                    </form>
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                    <div class="carbon-footer-links">
                        <a href="{{ route('mading.index') }}" class="carbon-footer-link">Beranda</a>
                        <a href="#articles-section" class="carbon-footer-link">Artikel</a>
                        <a href="{{ route('login') }}" class="carbon-footer-link">Admin Portal</a>
                    </div>
                    <span class="text-muted small d-none d-sm-inline">·</span>
                    <span class="text-muted small">&copy; {{ date('Y') }} DeSiWeM. All Rights Reserved.</span>
                </div>
            </div>
        </div>
    </footer>

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
