@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page_header', 'Dashboard')

@section('content')
<!-- Last Updated Timestamp -->
<div style="background-color: var(--primary-light); color: var(--primary); padding: 12px 20px; border-radius: var(--radius-md); font-weight: 700; font-size: 13px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span>Update Terakhir Data SIKJ: {{ $tanggalUpdate }}</span>
</div>

<!-- Statistics Grid -->
<div class="stats-grid">
    <a href="{{ route('admin.khatib.index') }}" class="stat-card" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-info">
            <span class="stat-value">{{ $totalKhatib }}</span>
            <span class="stat-label">Total Khatib</span>
        </div>
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </a>

    <a href="{{ route('admin.masjid.index') }}" class="stat-card" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-info">
            <span class="stat-value">{{ $totalMasjid }}</span>
            <span class="stat-label">Total Masjid</span>
        </div>
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-10 9h3v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h3L12 3z"/></svg>
        </div>
    </a>

    <a href="{{ route('admin.jadwal.index') }}" class="stat-card" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-info">
            <span class="stat-value">{{ $totalJadwal }}</span>
            <span class="stat-label">Total Jadwal</span>
        </div>
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
    </a>

    <a href="{{ route('admin.jadwal.index') }}" class="stat-card" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-info">
            <span class="stat-value">{{ $jadwalMingguIni }}</span>
            <span class="stat-label">Jadwal Minggu Ini</span>
        </div>
        <div class="stat-icon" style="background-color: var(--warning-light); color: var(--warning);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
    </a>
</div>

<!-- Schedules Table -->
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 15px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Jadwal Terdekat (Unik Per Masjid)</h2>
    </div>

    <!-- Filters Form -->
    <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 12px; margin-bottom: 25px; align-items: center; flex-wrap: wrap;">
        <div style="flex-grow: 1; min-width: 200px; position: relative;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama masjid atau khatib..." class="form-control" style="padding-left: 40px; font-size: 13px;">
            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div style="width: 180px;">
            <select name="periode" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                <option value="">Pilih Periode Waktu</option>
                <option value="1_minggu" {{ $periode === '1_minggu' ? 'selected' : '' }}>1 Minggu Terdekat</option>
                <option value="1_bulan" {{ $periode === '1_bulan' ? 'selected' : '' }}>1 Bulan Terdekat</option>
                <option value="4_bulan" {{ $periode === '4_bulan' ? 'selected' : '' }}>4 Bulan Terdekat</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 18px; font-size: 13px;">Filter</button>
        @if($search || $periode)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="width: auto; padding: 10px 18px; font-size: 13px;">Reset</a>
        @endif
    </form>

    <!-- Table content -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumat Ke / Waktu</th>
                    <th>Masjid</th>
                    <th>Khatib</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalTerdekat as $jadwal)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">
                            <div>{{ $jadwal->tanggal->translatedFormat('d F Y') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $jadwal->hijri_date }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Jumat ke-{{ $jadwal->jumat_ke ?? '-' }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ \Carbon\Carbon::parse($jadwal->waktu_khutbah)->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            <div style="font-weight: 700;">{{ $jadwal->masjid->nama }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $jadwal->masjid->kecamatan }}</div>
                        </td>
                        <td style="font-weight: 600;">{{ $jadwal->khatib->nama }}</td>
                        <td>
                            <span class="badge {{ $jadwal->status === 'Aktif' || $jadwal->status === 'Hadir' ? 'badge-active' : ($jadwal->status === 'Perubahan Diajukan' ? 'badge-warning' : 'badge-inactive') }}">
                                {{ $jadwal->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada jadwal terdekat saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Lihat Selengkapnya Link at the Bottom -->
    <div style="margin-top: 20px; text-align: center; border-top: 1px solid var(--border-color); padding-top: 20px;">
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary" style="width: auto; display: inline-flex; gap: 8px; font-size: 13px; padding: 10px 24px;">
            <span>Lihat Selengkapnya</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>
</div>

<!-- Two-Column Layout for Badal Request and Activity Logs -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-top: 24px;">
    <!-- Column Left: Badal Pending -->
    <div class="content-card" style="margin-bottom: 0;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 15px;">Permintaan Badal Menunggu Verifikasi</h2>
        <div class="table-wrapper">
            <table class="custom-table" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Masjid & Tanggal</th>
                        <th>Khatib Asal</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingBadals as $badal)
                        <tr>
                            <td>
                                <div style="font-weight: 700;">{{ $badal->masjid->nama }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $badal->jadwal ? $badal->jadwal->tanggal->translatedFormat('d M Y') : '-' }}</div>
                            </td>
                            <td style="font-weight: 600;">{{ $badal->khatib->nama }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.riwayatBadal.edit', $badal->id) }}" class="btn btn-primary" style="width: auto; padding: 6px 12px; font-size: 11px;">Verifikasi</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Tidak ada permintaan badal menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Column Right: Recent Activity Logs -->
    <div class="content-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 15px;">Aktivitas Terbaru Admin</h2>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px;">
                @forelse($recentActivities as $activity)
                    <li style="border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-start; gap: 15px;">
                        <div>
                            <div style="font-weight: 700; font-size: 13px; color: var(--text-dark);">
                                {{ $activity->user ? $activity->user->name : 'Sistem' }}
                            </div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px; line-height: 1.4;">
                                {{ $activity->activity }}
                            </div>
                        </div>
                        <div style="font-size: 11px; color: var(--text-muted); white-space: nowrap;">
                            {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </li>
                @empty
                    <li style="text-align: center; color: var(--text-muted); padding: 30px 0;">
                        Belum ada log aktivitas.
                    </li>
                @endforelse
            </ul>
        </div>
        
        @if($recentActivities->count() > 0)
            <div style="margin-top: 20px; text-align: center; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <a href="{{ route('admin.riwayatAktivitas') }}" style="font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <span>Lihat Semua Aktivitas</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
