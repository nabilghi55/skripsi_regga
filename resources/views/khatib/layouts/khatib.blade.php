<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Khatib SIKJ') - CMM SIKJ</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body style="background-color: var(--bg-main);">

    <!-- Desktop/Tablet Top Navbar -->
    <header class="khatib-navbar">
        <div class="khatib-navbar-container">
            <a href="{{ route('khatib.dashboard') }}" class="khatib-brand">
                <svg class="khatib-logo" viewBox="0 0 100 100" fill="none" style="width: 32px; height: 32px;">
                    <circle cx="50" cy="50" r="45" stroke="var(--primary)" stroke-width="4" stroke-dasharray="6 6"/>
                    <path d="M50 20 C60 20 65 30 65 45 C65 60 55 70 50 80 C45 70 35 60 35 45 C35 30 40 20 50 20 Z" fill="var(--primary)" opacity="0.1"/>
                    <path d="M50 25 L53 34 L62 34 L55 40 L57 49 L50 43 L43 49 L45 40 L38 34 L47 34 Z" fill="var(--primary)"/>
                </svg>
                <span class="khatib-brand-name">SIKJ Khatib</span>
            </a>
            
            <nav class="khatib-nav-links">
                <a href="{{ route('khatib.dashboard') }}" class="khatib-nav-link {{ Request::routeIs('khatib.dashboard') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('khatib.jadwalSaya') }}" class="khatib-nav-link {{ Request::routeIs('khatib.jadwalSaya') || Request::routeIs('khatib.detailJadwal') || Request::routeIs('khatib.formPerubahan') ? 'active' : '' }}">Jadwal Saya</a>
                <a href="{{ route('khatib.profile') }}" class="khatib-nav-link {{ Request::routeIs('khatib.profile') ? 'active' : '' }}">Profil Saya</a>
            </nav>

            <div class="khatib-user-menu">
                <span class="khatib-user-name">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" id="khatib-logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="btn-logout-desktop" onclick="event.preventDefault(); document.getElementById('khatib-logout-form').submit();">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="khatib-main-content">
        <div class="khatib-container">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Bottom Navigation Bar (Visible on mobile screens) -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('khatib.dashboard') }}" class="mobile-nav-item {{ Request::routeIs('khatib.dashboard') ? 'active' : '' }}">
            <span class="mobile-nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </span>
            <span>Beranda</span>
        </a>
        <a href="{{ route('khatib.jadwalSaya') }}" class="mobile-nav-item {{ Request::routeIs('khatib.jadwalSaya') || Request::routeIs('khatib.detailJadwal') || Request::routeIs('khatib.formPerubahan') ? 'active' : '' }}">
            <span class="mobile-nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </span>
            <span>Jadwal Saya</span>
        </a>
        <a href="{{ route('khatib.profile') }}" class="mobile-nav-item {{ Request::routeIs('khatib.profile') ? 'active' : '' }}">
            <span class="mobile-nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <span>Profil</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" id="mobile-logout-form" style="display: none;">
            @csrf
        </form>
        <a href="#" class="mobile-nav-item" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
            <span class="mobile-nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </span>
            <span>Logout</span>
        </a>
    </nav>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
