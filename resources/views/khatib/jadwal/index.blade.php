@extends('khatib.layouts.khatib')

@section('title', 'Jadwal Saya')

@section('content')
<!-- Back Header -->
<a href="{{ route('khatib.dashboard') }}" class="form-header-back" style="margin-bottom: 20px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    <span>Jadwal Saya</span>
</a>

<!-- Schedules list -->
<div class="schedule-list-container">
    @forelse($jadwals as $jadwal)
        <div class="schedule-list-item">
            <div class="schedule-list-left">
                <!-- Date Badge -->
                <div class="schedule-date-badge" style="width: 50px; height: 50px;">
                    <span class="date-badge-day" style="font-size: 16px;">{{ $jadwal->tanggal->format('d') }}</span>
                    <span class="date-badge-month" style="font-size: 8px;">{{ $jadwal->tanggal->translatedFormat('M') }}</span>
                </div>
                <!-- Details -->
                <div style="display: flex; flex-direction: column; gap: 2px;">
                    <span style="font-size: 14px; font-weight: 700; color: var(--text-dark);">{{ $jadwal->masjid->nama }}</span>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 500;">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 2px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        08.00 WIB
                    </span>
                </div>
            </div>
            
            <a href="{{ route('khatib.detailJadwal', $jadwal->id) }}" class="btn btn-secondary btn-detail-sm">DETAIL</a>
        </div>
    @empty
        <div style="color: var(--text-muted); text-align: center; padding: 40px 0; font-size: 13px;">
            Anda belum memiliki jadwal saat ini.
        </div>
    @endforelse
</div>
@endsection
