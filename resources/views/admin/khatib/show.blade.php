@extends('admin.layouts.admin')

@section('title', 'Detail Khatib - ' . $khatib->nama)
@section('page_header', 'Detail Khatib')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.khatib.index') }}" class="form-header-back" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: var(--text-dark); font-weight: 700;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Kembali ke Data Khatib</span>
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start; margin-bottom: 30px;">
    <!-- Profile Card (Left Column) -->
    <div class="content-card" style="margin-bottom: 0; padding: 24px; text-align: center;">
        <div class="profile-avatar-container">
            <div class="profile-avatar-wrapper">
                @if($khatib->foto_profile)
                    <img src="{{ asset('storage/' . $khatib->foto_profile) }}" class="profile-avatar-image" alt="Foto Profil">
                @else
                    <div class="profile-avatar-placeholder">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                @endif
            </div>
            <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">{{ $khatib->nama }}</h3>
            <span class="badge {{ $khatib->status === 'Normal' ? 'badge-active' : ($khatib->status === 'Off' ? 'badge-inactive' : 'badge-warning') }}" style="margin-bottom: 20px;">
                {{ $khatib->status }}
            </span>
        </div>

        <div style="text-align: left; border-top: 1px solid var(--border-color); padding-top: 20px; font-size: 13px; display: flex; flex-direction: column; gap: 12px;">
            <div>
                <strong style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">Kode NBM</strong>
                <span style="font-weight: 700; color: var(--text-dark);">{{ $khatib->nbm ?? '-' }}</span>
            </div>
            <div>
                <strong style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">Tanggal Lahir</strong>
                <span style="font-weight: 600; color: var(--text-dark);">{{ $khatib->tanggal_lahir ? $khatib->tanggal_lahir->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div>
                <strong style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">Jenjang Pendidikan</strong>
                <span style="font-weight: 600; color: var(--text-dark);">{{ $khatib->jenjang_pendidikan ?? '-' }}</span>
            </div>
            <div>
                <strong style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">Alamat Lengkap</strong>
                <span style="font-weight: 600; color: var(--text-dark);">{{ $khatib->alamat }}</span>
            </div>
            <div>
                <strong style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">No HP 1 (WhatsApp)</strong>
                <span style="font-weight: 600; color: var(--text-dark);">{{ $khatib->no_hp }}</span>
            </div>
            <div>
                <strong style="color: var(--text-muted); display: block; font-size: 11px; text-transform: uppercase;">No HP 2</strong>
                <span style="font-weight: 600; color: var(--text-dark);">{{ $khatib->no_hp_2 ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Attendance Stats & Sermon History (Right Column) -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Rekap Kehadiran -->
        <div class="content-card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;">Rekap Kehadiran</h3>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; text-align: center;">
                <div style="background-color: var(--success-light); padding: 12px; border-radius: var(--radius-md); border: 1px solid rgba(16, 185, 129, 0.2);">
                    <div style="font-size: 20px; font-weight: 800; color: var(--success);">{{ $rekap['Hadir'] }}</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-top: 4px;">Hadir</div>
                </div>
                <div style="background-color: var(--warning-light); padding: 12px; border-radius: var(--radius-md); border: 1px solid rgba(245, 158, 11, 0.2);">
                    <div style="font-size: 20px; font-weight: 800; color: var(--warning);">{{ $rekap['Izin'] }}</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-top: 4px;">Izin</div>
                </div>
                <div style="background-color: var(--warning-light); padding: 12px; border-radius: var(--radius-md); border: 1px solid rgba(245, 158, 11, 0.2);">
                    <div style="font-size: 20px; font-weight: 800; color: var(--warning);">{{ $rekap['Sakit'] }}</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-top: 4px;">Sakit</div>
                </div>
                <div style="background-color: var(--primary-light); padding: 12px; border-radius: var(--radius-md); border: 1px solid rgba(27, 82, 192, 0.2);">
                    <div style="font-size: 20px; font-weight: 800; color: var(--primary);">{{ $rekap['Badal'] }}</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-top: 4px;">Badal</div>
                </div>
                <div style="background-color: var(--danger-light); padding: 12px; border-radius: var(--radius-md); border: 1px solid rgba(239, 68, 68, 0.2);">
                    <div style="font-size: 20px; font-weight: 800; color: var(--danger);">{{ $rekap['Tidak Hadir'] }}</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-top: 4px;">Absen</div>
                </div>
            </div>
            <div style="margin-top: 15px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-dark);">
                Total Penugasan: {{ $rekap['Total'] }} kali
            </div>
        </div>

        <!-- Riwayat Khutbah -->
        <div class="content-card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;">Riwayat Tugas & Saran Takmir</h3>
            <div class="table-wrapper">
                <table class="custom-table" style="font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Tanggal</th>
                            <th>Masjid</th>
                            <th>Saran Takmir</th>
                            <th style="width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                            <tr>
                                <td style="font-weight: 600; color: var(--primary);">
                                    <div>{{ $r->tanggal->translatedFormat('d F Y') }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $r->hijri_date }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 700;">{{ $r->masjid->nama }}</div>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $r->masjid->alamat }}</div>
                                </td>
                                <td>
                                    @if($r->catatan_saran_takmir)
                                        <div style="font-style: italic; background-color: var(--bg-main); padding: 8px 12px; border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                                            "{{ $r->catatan_saran_takmir }}"
                                        </div>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 12px;">Tidak ada catatan.</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $r->status === 'Hadir' || $r->status === 'Aktif' ? 'badge-active' : ($r->status === 'Tidak Hadir' ? 'badge-inactive' : 'badge-warning') }}">
                                        {{ $r->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                    Belum ada riwayat tugas khutbah untuk Khatib ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
