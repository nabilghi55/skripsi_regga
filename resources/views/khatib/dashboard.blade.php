@extends('khatib.layouts.khatib')

@section('title', 'Khatib Dashboard')

@section('content')
<!-- Header Greeting -->
<div class="mobile-header">
    <div class="mobile-user-greeting">
        <span class="mobile-user-title">Halo,</span>
        <span class="mobile-user-name">{{ $khatib->nama }}</span>
    </div>
    <div class="mobile-header-actions">
        <div class="mobile-action-btn" id="notif-bell">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            @if($unreadNotification)
                <span class="mobile-notification-dot"></span>
            @endif
        </div>
        <div class="mobile-avatar">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor" style="color: var(--primary);"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        </div>
    </div>
</div>

<!-- Jadwal Berikutnya Box -->
<div class="next-schedule-card">
    <div class="next-schedule-header">Jadwal Berikutnya</div>
    @if($nextJadwal)
        <div class="next-schedule-body">
            <div class="schedule-date-badge">
                <span class="date-badge-day">{{ $nextJadwal->tanggal->format('d') }}</span>
                <span class="date-badge-month">{{ $nextJadwal->tanggal->translatedFormat('M') }}</span>
            </div>
            <div class="schedule-details">
                <span class="schedule-masjid">{{ $nextJadwal->masjid->nama }}</span>
                <span class="schedule-time">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    08.00 WIB
                </span>
            </div>
        </div>
        <div style="margin-top: 15px; text-align: right;">
            <a href="{{ route('khatib.detailJadwal', $nextJadwal->id) }}" class="btn btn-primary btn-detail-sm">DETAIL</a>
        </div>
    @else
        <div style="color: var(--text-muted); font-size: 13px; text-align: center; padding: 10px 0;">
            Tidak ada jadwal khotbah mendatang.
        </div>
    @endif
</div>

<!-- Quick Menu -->
<div class="mobile-menu-section">
    <span class="mobile-menu-title">Menu Utama</span>
    <ul class="mobile-menu-list">
        <li>
            <a href="{{ route('khatib.jadwalSaya') }}" class="mobile-menu-item-link">
                <div class="mobile-menu-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Jadwal Saya</span>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </li>
        <li>
            @if($nextJadwal)
                <a href="{{ route('khatib.detailJadwal', $nextJadwal->id) }}#informasi-masjid" class="mobile-menu-item-link">
                    <div class="mobile-menu-item-left">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-10 9h3v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h3L12 3z"/></svg>
                        <span>Informasi Masjid</span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <div class="mobile-menu-item-link" style="opacity: 0.6; cursor: not-allowed;">
                    <div class="mobile-menu-item-left">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-10 9h3v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h3L12 3z"/></svg>
                        <span>Informasi Masjid</span>
                    </div>
                </div>
            @endif
        </li>
        <li>
            @if($nextJadwal)
                <a href="{{ route('khatib.formPerubahan', $nextJadwal->id) }}" class="mobile-menu-item-link">
                    <div class="mobile-menu-item-left">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <span>Ajukan Perubahan Jadwal</span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <div class="mobile-menu-item-link" style="opacity: 0.6; cursor: not-allowed;">
                    <div class="mobile-menu-item-left">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <span>Ajukan Perubahan Jadwal</span>
                    </div>
                </div>
            @endif
        </li>
    </ul>
</div>

<!-- Modal Overlay for Screen 15 (Notifikasi Khatib) -->
@if($unreadNotification)
    <div class="modal-overlay" id="notif-modal">
        <div class="modal-card">
            <button class="modal-close" id="modal-close-btn">&times;</button>
            <div class="modal-icon-wrapper">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <h3 class="modal-title">PENGINGAT JADWAL</h3>
            <p class="modal-message">
                {{ $unreadNotification->pesan }}
            </p>
            <button class="btn btn-primary btn-modal-ok" id="modal-ok-btn">OK</button>
        </div>
    </div>
@endif
@endsection

@section('scripts')
@if($unreadNotification)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const notifModal = document.getElementById('notif-modal');
    const closeBtn = document.getElementById('modal-close-btn');
    const okBtn = document.getElementById('modal-ok-btn');
    const notifBell = document.getElementById('notif-bell');

    const markAsRead = () => {
        fetch("{{ route('khatib.markNotifRead', $unreadNotification->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notification red dot
                const dot = notifBell.querySelector('.mobile-notification-dot');
                if (dot) dot.remove();
            }
        })
        .catch(err => console.error(err));
    };

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            markAsRead();
            notifModal.style.display = 'none';
        });
    }

    if (okBtn) {
        okBtn.addEventListener('click', () => {
            markAsRead();
            notifModal.style.display = 'none';
        });
    }
});
</script>
@endif
@endsection
