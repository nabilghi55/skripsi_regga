<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIKJ - CMM</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body style="background-color: var(--bg-main);">

    <!-- Splash Screen Overlay (Screen 1) -->
    <div id="splash-screen">
        <div class="splash-content">
            <!-- Logo placeholder (SVG crescent and star / halal mosque dome motif) -->
            <svg class="splash-logo" viewBox="0 0 100 100" fill="none">
                <circle cx="50" cy="50" r="45" stroke="var(--primary)" stroke-width="4" stroke-dasharray="6 6"/>
                <path d="M50 20 C60 20 65 30 65 45 C65 60 55 70 50 80 C45 70 35 60 35 45 C35 30 40 20 50 20 Z" fill="var(--primary)" opacity="0.1"/>
                <path d="M50 25 L53 34 L62 34 L55 40 L57 49 L50 43 L43 49 L45 40 L38 34 L47 34 Z" fill="var(--primary)"/>
                <text x="50" y="68" font-size="12" font-weight="800" fill="var(--primary)" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif">CMM</text>
            </svg>
            <h1 class="splash-title">SIKJ</h1>
            <h2 class="splash-subtitle">Sistem Informasi<br>Jadwal Khatib Jumat</h2>
            <p class="splash-desc">Mudah • Cepat • Terintegrasi</p>
            
            <div class="spinner"></div>
            <span class="splash-loading-text">Loading...</span>
        </div>
        <div class="splash-footer">
            &copy; 2026 CMM. All rights reserved.
        </div>
    </div>

    <!-- Login Screen Container (Screen 2) -->
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo -->
            <svg class="auth-logo" viewBox="0 0 100 100" fill="none" style="margin-bottom: 15px;">
                <circle cx="50" cy="50" r="45" stroke="var(--primary)" stroke-width="4" stroke-dasharray="6 6"/>
                <path d="M50 20 C60 20 65 30 65 45 C65 60 55 70 50 80 C45 70 35 60 35 45 C35 30 40 20 50 20 Z" fill="var(--primary)" opacity="0.1"/>
                <path d="M50 25 L53 34 L62 34 L55 40 L57 49 L50 43 L43 49 L45 40 L38 34 L47 34 Z" fill="var(--primary)"/>
                <text x="50" y="68" font-size="12" font-weight="800" fill="var(--primary)" text-anchor="middle" font-family="'Plus Jakarta Sans', sans-serif">CMM</text>
            </svg>
            
            <h1 class="auth-title">CMM</h1>
            <h2 class="auth-subtitle">LOGIN SIKJ</h2>

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary">LOGIN</button>
            </form>

            <div style="margin-top: 30px; font-size: 11px; color: var(--text-muted);">
                &copy; 2026 CMM. All rights reserved.
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
