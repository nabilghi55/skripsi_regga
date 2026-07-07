@extends('admin.layouts.admin')

@section('title', 'Riwayat Takmir (Masjid)')
@section('page_header', 'Riwayat Takmir (Masjid)')

@section('content')
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Daftar Riwayat Aktivitas & Masukan Takmir</h2>
    </div>

    <!-- Filters Form -->
    <form method="GET" action="{{ route('admin.riwayatTakmir') }}" style="display: flex; gap: 12px; margin-bottom: 25px; align-items: center; flex-wrap: wrap;">
        <div style="flex-grow: 1; min-width: 250px; position: relative;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama masjid, khatib, atau catatan..." class="form-control" style="padding-left: 40px; font-size: 13px;">
            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div style="width: 200px;">
            <select name="masjid_id" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                <option value="">Semua Masjid</option>
                @foreach($masjids as $masjid)
                    <option value="{{ $masjid->id }}" {{ $masjidId == $masjid->id ? 'selected' : '' }}>{{ $masjid->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 18px; font-size: 13px;">Filter</button>
        @if($search || $masjidId)
            <a href="{{ route('admin.riwayatTakmir') }}" class="btn btn-secondary" style="width: auto; padding: 10px 18px; font-size: 13px;">Reset</a>
        @endif
    </form>

    <!-- Table content -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Masjid</th>
                    <th>Pengurus / Takmir</th>
                    <th>Jadwal Terkait</th>
                    <th>Khatib Bertugas</th>
                    <th>Catatan & Saran Takmir</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $index => $jadwal)
                    <tr>
                        <td>{{ $jadwals->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 700; color: var(--primary);">{{ $jadwal->masjid->nama }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $jadwal->masjid->alamat }}</div>
                        </td>
                        <td>
                            @if($jadwal->masjid->user)
                                <div style="font-weight: 600;">{{ $jadwal->masjid->user->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $jadwal->masjid->no_hp_1 ?: ($jadwal->masjid->no_hp_2 ?: '-') }}</div>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px; font-style: italic;">Belum ada Takmir</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $jadwal->tanggal->translatedFormat('d F Y') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Jumat ke-{{ $jadwal->jumat_ke ?? '-' }}</div>
                        </td>
                        <td style="font-weight: 600;">{{ $jadwal->khatib->nama }}</td>
                        <td style="max-width: 250px; white-space: normal; line-height: 1.4;">
                            @if($jadwal->catatan_saran_takmir)
                                <div style="background-color: var(--primary-light); color: var(--primary); padding: 8px 12px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500;">
                                    "{{ $jadwal->catatan_saran_takmir }}"
                                </div>
                            @else
                                <span style="color: var(--text-muted); font-style: italic; font-size: 12px;">Tidak ada catatan/saran</span>
                            @endif
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
                            Tidak ada riwayat takmir/masukan yang ditemukan.
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
