@extends('khatib.layouts.khatib')

@section('title', 'Notifikasi Saya')

@section('content')
<!-- Back Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
    <a href="{{ route('khatib.dashboard') }}" class="form-header-back" style="margin-bottom: 0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Notifikasi Saya</span>
    </a>
</div>

<!-- Notification Inbox Container -->
<div class="content-card" style="padding: 24px;">
    <h3 style="font-size: 15px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: var(--primary);"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        Kotak Masuk Notifikasi
    </h3>

    <div class="notification-inbox-list">
        @forelse($notifications as $notif)
            <div class="notification-inbox-item {{ $notif->read_at ? '' : 'unread' }}" id="notif-item-{{ $notif->id }}">
                <div class="notification-inbox-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="notification-inbox-content">
                    <p class="notification-inbox-message">{{ $notif->pesan }}</p>
                    <span class="notification-inbox-time">
                        {{ $notif->created_at->locale('id')->diffForHumans() }}
                        @if($notif->read_at)
                            <span style="color: var(--success); margin-left: 8px;">• Dibaca pada {{ $notif->read_at->timezone('Asia/Jakarta')->format('d M, H:i') }} WIB</span>
                        @endif
                    </span>
                </div>
                @if(!$notif->read_at)
                    <div class="notification-inbox-action">
                        <button onclick="markAsRead({{ $notif->id }})" class="btn btn-primary" style="padding: 6px 12px; font-size: 11px; font-weight: bold; width: auto; height: auto;">
                            Tandai Dibaca
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <div style="color: var(--text-muted); text-align: center; padding: 40px 0; font-size: 13px;">
                Belum ada notifikasi masuk untuk Anda.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div style="margin-top: 25px; display: flex; justify-content: center;">
            {{ $notifications->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function markAsRead(notifId) {
    const url = "{{ route('khatib.markNotifRead', ':id') }}".replace(':id', notifId);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI class
            const item = document.getElementById(`notif-item-${notifId}`);
            if (item) {
                item.classList.remove('unread');
                // Remove the action button
                const actionDiv = item.querySelector('.notification-inbox-action');
                if (actionDiv) actionDiv.remove();
                // Add read timestamp helper text
                const timeSpan = item.querySelector('.notification-inbox-time');
                if (timeSpan) {
                    const now = new Date();
                    const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                    timeSpan.innerHTML += `<span style="color: var(--success); margin-left: 8px;">• Dibaca baru saja</span>`;
                }
            }
            
            // Decement badge count if exists
            const badge = document.querySelector('.badge-notif-count');
            if (badge) {
                let count = parseInt(badge.textContent);
                count = isNaN(count) ? 0 : count - 1;
                if (count <= 0) {
                    badge.remove();
                    const dot = document.querySelector('.mobile-notification-dot');
                    if (dot) dot.remove();
                } else {
                    badge.textContent = count;
                }
            }
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection
