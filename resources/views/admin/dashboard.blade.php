@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page_header', 'Dashboard')

@section('content')
<!-- Statistics Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-value">{{ $totalKhatib }}</span>
            <span class="stat-label">Total Khatib</span>
        </div>
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-value">{{ $totalMasjid }}</span>
            <span class="stat-label">Total Masjid</span>
        </div>
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-10 9h3v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h3L12 3z"/></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-value">{{ $totalJadwal }}</span>
            <span class="stat-label">Total Jadwal</span>
        </div>
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-value">{{ $jadwalMingguIni }}</span>
            <span class="stat-label">Jadwal Minggu Ini</span>
        </div>
        <div class="stat-icon" style="background-color: var(--warning-light); color: var(--warning);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
    </div>
</div>

<!-- Schedules Table -->
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 25px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Jadwal Terdekat</h2>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary" style="width: auto; padding: 8px 16px; font-size: 13px;">Lihat Semua</a>
    </div>

    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Masjid</th>
                    <th>Khatib</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalTerdekat as $jadwal)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">{{ $jadwal->tanggal->translatedFormat('d F Y') }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $jadwal->masjid->nama }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $jadwal->masjid->kecamatan }}</div>
                        </td>
                        <td style="font-weight: 600;">{{ $jadwal->khatib->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada jadwal terdekat saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
