@extends('khatib.layouts.khatib')

@section('title', 'Ajukan Perubahan Jadwal')

@section('content')
<!-- Back Header -->
<a href="{{ route('khatib.detailJadwal', $jadwal->id) }}" class="form-header-back" style="margin-bottom: 20px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    <span>Ajukan Perubahan Jadwal</span>
</a>

<div class="content-card" style="padding: 20px; border-radius: var(--radius-md);">
    <form action="{{ route('khatib.kirimPerubahan', $jadwal->id) }}" method="POST" target="_blank" id="change-request-form">
        @csrf

        <div class="form-group">
            <label class="form-label">Jadwal</label>
            <input type="text" class="form-control" value="{{ $jadwal->tanggal->translatedFormat('d F Y') }} - {{ $jadwal->masjid->nama }}" style="background-color: var(--bg-main); color: var(--text-muted); cursor: not-allowed;" readonly>
        </div>

        <div class="form-group">
            <label class="form-label" for="alasan">Alasan Perubahan</label>
            <textarea name="alasan" id="alasan" rows="5" maxlength="200" data-counter="alasan-counter" class="form-control @error('alasan') is-invalid @enderror" placeholder="Jelaskan alasan perubahan jadwal..." required></textarea>
            <div class="char-counter">
                <span id="alasan-counter">0/200</span>
            </div>
            @error('alasan')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="font-size: 11px; text-align: center; color: var(--text-muted); margin-bottom: 20px; font-weight: 500; line-height: 1.4;">
            Permintaan akan dikirim ke WhatsApp Pengurus CMM
        </div>

        <button type="submit" class="btn btn-whatsapp">KIRIM VIA WHATSAPP</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('change-request-form');
    if (form) {
        form.addEventListener('submit', () => {
            // After submitting and redirecting to WhatsApp in a new tab, redirect the current mobile view back to schedule list
            setTimeout(() => {
                window.location.href = "{{ route('khatib.jadwalSaya') }}";
            }, 1000);
        });
    }
});
</script>
@endsection
