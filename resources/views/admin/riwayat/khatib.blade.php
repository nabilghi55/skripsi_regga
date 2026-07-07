@extends('admin.layouts.admin')

@section('title', 'Riwayat Khatib')
@section('page_header', 'Riwayat Khatib')

@section('content')
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Daftar Riwayat Jadwal Khatib</h2>
    </div>

    <!-- Filters Form -->
    <form method="GET" action="{{ route('admin.riwayatKhotib') }}" style="display: flex; gap: 12px; margin-bottom: 25px; align-items: center; flex-wrap: wrap;">
        <div style="flex-grow: 1; min-width: 250px; position: relative;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama khatib atau masjid..." class="form-control" style="padding-left: 40px; font-size: 13px;">
            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div style="width: 180px;">
            <select name="status" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                <option value="">Semua Status</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 18px; font-size: 13px;">Filter</button>
        @if($search || $status)
            <a href="{{ route('admin.riwayatKhotib') }}" class="btn btn-secondary" style="width: auto; padding: 10px 18px; font-size: 13px;">Reset</a>
        @endif
    </form>

    <!-- Table content -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Jumat Ke / Waktu</th>
                    <th>Nama Khatib</th>
                    <th>Masjid</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $index => $jadwal)
                    <tr>
                        <td>{{ $jadwals->firstItem() + $index }}</td>
                        <td style="font-weight: 600; color: var(--primary);">
                            <div>{{ $jadwal->tanggal->translatedFormat('d F Y') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $jadwal->hijri_date }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">Jumat ke-{{ $jadwal->jumat_ke ?? '-' }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ \Carbon\Carbon::parse($jadwal->waktu_khutbah)->format('H:i') }} WIB</div>
                        </td>
                        <td style="font-weight: 600;">{{ $jadwal->khatib->nama }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $jadwal->masjid->nama }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $jadwal->masjid->kecamatan }}</div>
                        </td>
                        <td style="font-size: 13px; line-height: 1.4; max-width: 200px; white-space: normal;">
                            {{ $jadwal->keterangan ?: '-' }}
                        </td>
                        <td>
                            <span class="badge {{ $jadwal->status === 'Hadir' || $jadwal->status === 'Aktif' ? 'badge-active' : ($jadwal->status === 'Perubahan Diajukan' ? 'badge-warning' : 'badge-inactive') }}">
                                {{ $jadwal->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada riwayat jadwal khatib yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $jadwals->links() }}
    </div>
</div>
@endsection
