<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SIKJ CMM</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                </svg>
                <span class="sidebar-brand-name">CMM SIKJ</span>
            </a>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.khatib.index') }}" class="sidebar-item-link {{ Request::routeIs('admin.khatib.*') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Data Khatib</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.masjid.index') }}" class="sidebar-item-link {{ Request::routeIs('admin.masjid.*') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-10 9h3v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h3L12 3z"/></svg>
                        <span>Data Masjid</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.jadwal.index') }}" class="sidebar-item-link {{ Request::routeIs('admin.jadwal.index') || Request::routeIs('admin.jadwal.edit') || Request::routeIs('admin.jadwal.create') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>Jadwal Khotbah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.badal.index') }}" class="sidebar-item-link {{ Request::routeIs('admin.badal.*') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M9 22V12h10v10M9 12l5-5 5 5"/></svg>
                        <span>Manajemen Badal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.riwayatBadal.index') }}" class="sidebar-item-link {{ Request::routeIs('admin.riwayatBadal.*') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span>Riwayat Badal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.notification.index') }}" class="sidebar-item-link {{ Request::routeIs('admin.notification.*') ? 'active' : '' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span>Notifikasi</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" class="sidebar-item-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        <span>Keluar</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-profile">
                <div class="sidebar-avatar">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="sidebar-profile-info">
                    <span class="sidebar-profile-name">{{ Auth::user()->name }}</span>
                    <span class="sidebar-profile-role">Pengurus CMM</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header-container">
                <h1 class="page-title">@yield('page_header')</h1>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="font-size: 13px; font-weight: 600; color: var(--text-muted);">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
