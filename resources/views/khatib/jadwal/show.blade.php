@extends('khatib.layouts.khatib')

@section('title', 'Detail Jadwal')

@section('content')
<!-- Back Header -->
<a href="{{ route('khatib.jadwalSaya') }}" class="form-header-back" style="margin-bottom: 20px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    <span>Detail Jadwal</span>
</a>

<div id="detail-jadwal-section" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px;">
    <!-- Masjid Info Card -->
    <div class="content-card" style="padding: 20px; border-radius: var(--radius-md); margin-bottom: 0;">
        <div class="detail-item">
            <span class="detail-label">Masjid</span>
            <span class="detail-value" style="font-size: 16px; color: var(--primary); font-weight: 700;">{{ $jadwal->masjid->nama }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Alamat</span>
            <span class="detail-value" style="font-size: 13px; color: var(--text-muted);">{{ $jadwal->masjid->alamat }}</span>
        </div>
        <div class="detail-item" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div>
                <span class="detail-label">Tanggal</span>
                <span class="detail-value">{{ $jadwal->tanggal->translatedFormat('d F Y') }}</span>
            </div>
            <div>
                <span class="detail-label">Jam</span>
                <span class="detail-value">08.00 WIB</span>
            </div>
        </div>
        <div class="detail-item" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div>
                <span class="detail-label">Khatib</span>
                <span class="detail-value">{{ $jadwal->khatib->nama }}</span>
            </div>
            <div>
                <span class="detail-label">Status</span>
                <div>
                    <span class="badge {{ $jadwal->status === 'Aktif' ? 'badge-active' : 'badge-warning' }}">
                        {{ $jadwal->status }}
                    </span>
                </div>
            </div>
        </div>

        <a href="{{ route('khatib.formPerubahan', $jadwal->id) }}" class="btn btn-primary" style="margin-top: 15px; font-size: 12px; padding: 12px;">AJUKAN PERUBAHAN JADWAL</a>
    </div>
</div>

<!-- Informasi Masjid & Peta Lokasi (Screen 14) -->
<div id="informasi-masjid" class="content-card" style="padding: 20px; border-radius: var(--radius-md); margin-bottom: 0;">
    <h3 style="font-size: 14px; font-weight: 800; color: var(--text-dark); margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Informasi Masjid</h3>
    
    <div class="detail-item">
        <span class="detail-label">Nama Masjid</span>
        <span class="detail-value">{{ $jadwal->masjid->nama }}</span>
    </div>
    <div class="detail-item" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
        <div>
            <span class="detail-label">Kecamatan</span>
            <span class="detail-value">{{ $jadwal->masjid->kecamatan }}</span>
        </div>
        <div>
            <span class="detail-label">Lokasi</span>
            <span class="detail-value">Malang</span>
        </div>
    </div>

    <div class="detail-item">
        <span class="detail-label">Peta Lokasi</span>
        <div class="map-placeholder">
            @if($jadwal->masjid->google_maps_link)
                <iframe src="https://maps.google.com/maps?q={{ urlencode($jadwal->masjid->nama . ' ' . $jadwal->masjid->alamat) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen></iframe>
            @else
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span style="font-size: 11px;">Peta lokasi tidak tersedia</span>
            @endif
        </div>
    </div>

    @if($jadwal->masjid->google_maps_link)
        <a href="{{ $jadwal->masjid->google_maps_link }}" target="_blank" class="btn btn-secondary" style="margin-top: 15px; font-size: 12px; padding: 12px;">LIHAT GOOGLE MAPS</a>
    @endif
</div>
@endsection
