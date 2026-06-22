@extends('takmir.layouts.takmir')

@section('title', 'Beranda Takmir')

@section('content')
<div class="mobile-header" style="margin-bottom: 30px;">
    <div class="mobile-user-greeting">
        <span class="mobile-user-title">Halo Takmir,</span>
        <span class="mobile-user-name" style="text-transform: uppercase;">{{ $masjid ? $masjid->nama : 'Masjid' }}</span>
    </div>
    <div class="mobile-header-actions">
        <div class="mobile-avatar">
            @if($masjid && $masjid->foto_profile)
                <img src="{{ asset('storage/' . $masjid->foto_profile) }}" alt="Foto Masjid" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--primary);">
            @else
                <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor" style="color: var(--primary);"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
            @endif
        </div>
    </div>
</div>

<div class="content-card" style="padding: 24px; border-radius: var(--radius-lg); margin-bottom: 30px;">
    <h3 style="font-size: 15px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: var(--primary);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Jadwal Terdekat Pekan Ini
    </h3>

    @if($nextJadwal)
        <div style="display: grid; grid-template-columns: 1fr; gap: 16px;">
            <div style="background-color: var(--primary-light); padding: 16px; border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
                <div style="font-size: 11px; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 4px;">TANGGAL DAN HARI</div>
                <div style="font-size: 16px; font-weight: 800; color: var(--text-dark);">
                    {{ $nextJadwal->tanggal->translatedFormat('l, d F Y') }}
                </div>
                <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 2px;">
                    {{ $nextJadwal->hijri_date }}
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="background-color: var(--bg-main); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">NAMA KHOTIB</div>
                    <div style="font-size: 14px; font-weight: 800; color: var(--text-dark);">{{ $nextJadwal->khatib->nama }}</div>
                </div>

                <div style="background-color: var(--bg-main); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">WAKTU DZUHUR / KHUTBAH</div>
                    <div style="font-size: 14px; font-weight: 800; color: var(--text-dark);">{{ $nextJadwal->waktu_khutbah }} WIB</div>
                </div>
            </div>

            <div style="background-color: var(--bg-main); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">NO HP KHOTIB</div>
                    <div style="font-size: 14px; font-weight: 800; color: var(--text-dark);">{{ $nextJadwal->khatib->no_hp }}</div>
                </div>
                <div>
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $nextJadwal->khatib->no_hp);
                        if (strpos($cleanPhone, '0') === 0) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-primary" style="padding: 8px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; width: auto; height: auto; text-decoration: none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Hubungi via WA
                    </a>
                </div>
            </div>
        </div>
    @else
        <div style="color: var(--text-muted); text-align: center; padding: 30px 0; font-size: 14px;">
            Tidak ada jadwal khotbah pekan ini.
        </div>
    @endif
</div>

<!-- Modal Pop Up Reminder -->
@if($showReminderPopup && $nextJadwal)
    <div class="modal-overlay" id="reminder-modal">
        <div class="modal-card" style="max-width: 380px; padding: 24px;">
            <button class="modal-close" id="modal-close-btn">&times;</button>
            <div class="modal-icon-wrapper" style="background-color: var(--primary-light); color: var(--primary);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <h3 class="modal-title" style="font-size: 16px; font-weight: 800; letter-spacing: 0.5px;">REMINDER JADWAL JUMAT</h3>
            
            <div style="text-align: left; background-color: var(--bg-main); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px; margin: 15px 0 20px;">
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Tanggal & Hari</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-dark);">
                        {{ $nextJadwal->tanggal->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nama Khotib</div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-dark);">{{ $nextJadwal->khatib->nama }}</div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <div style="font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Waktu Dzuhur</div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--text-dark);">{{ $nextJadwal->waktu_khutbah }}</div>
                    </div>
                    <div>
                        <div style="font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">No HP</div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--text-dark);">{{ $nextJadwal->khatib->no_hp }}</div>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" id="modal-ok-btn" style="width: 100%;">SAYA MENGERTI</button>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('reminder-modal');
    const closeBtn = document.getElementById('modal-close-btn');
    const okBtn = document.getElementById('modal-ok-btn');

    if (modal) {
        const dismissModal = () => {
            modal.style.display = 'none';
        };

        if (closeBtn) closeBtn.addEventListener('click', dismissModal);
        if (okBtn) okBtn.addEventListener('click', dismissModal);
    }
});
</script>
@endsection
