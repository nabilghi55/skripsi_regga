@extends('admin.layouts.admin')

@section('title', 'Kelola Notifikasi')
@section('page_header', 'Kelola Notifikasi')

@section('content')
<div class="content-card notification-form-card">
    <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;">Kirim Pengingat Jadwal</h2>
    
    <form action="{{ route('admin.notification.store') }}" method="POST" target="_blank">
        @csrf

        <div class="form-group">
            <label class="form-label" for="khatib_id">Pilih Khatib</label>
            <select name="khatib_id" id="khatib_id" class="form-control @error('khatib_id') is-invalid @enderror" required>
                <option value="">Pilih Khatib</option>
                @foreach($khatibs as $khatib)
                    <option value="{{ $khatib->id }}" data-phone="{{ $khatib->no_hp }}">
                        {{ $khatib->nama }} ({{ $khatib->no_hp }})
                    </option>
                @endforeach
            </select>
            @error('khatib_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="jadwal_id">Pilih Jadwal</label>
            <select name="jadwal_id" id="jadwal_id" class="form-control @error('jadwal_id') is-invalid @enderror">
                <option value="">Pilih Jadwal (Opsional)</option>
                @foreach($jadwals as $jadwal)
                    <option value="{{ $jadwal->id }}" data-details="{{ $jadwal->tanggal->translatedFormat('d F Y') }} - {{ $jadwal->masjid->nama }} - {{ $jadwal->khatib->nama }}">
                        {{ $jadwal->tanggal->translatedFormat('d F Y') }} - {{ $jadwal->masjid->nama }} (Khatib: {{ $jadwal->khatib->nama }})
                    </option>
                @endforeach
            </select>
            @error('jadwal_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="pesan">Pesan Notifikasi</label>
            <textarea name="pesan" id="pesan" rows="4" maxlength="160" data-counter="char-counter" class="form-control @error('pesan') is-invalid @enderror" placeholder="Tulis pesan notifikasi..." required></textarea>
            <div class="char-counter">
                <span id="char-counter">0/160</span>
            </div>
            @error('pesan')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">KIRIM NOTIFIKASI</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const khatibSelect = document.getElementById('khatib_id');
    const jadwalSelect = document.getElementById('jadwal_id');
    const pesanTextarea = document.getElementById('pesan');
    const counterSpan = document.getElementById('char-counter');

    const updateMessage = () => {
        const selectedKhatib = khatibSelect.options[khatibSelect.selectedIndex];
        const selectedJadwal = jadwalSelect.options[jadwalSelect.selectedIndex];

        if (selectedKhatib.value && selectedJadwal.value) {
            const details = selectedJadwal.getAttribute('data-details').split(' - ');
            const date = details[0];
            const masjid = details[1];
            const name = selectedKhatib.text.split(' (')[0];
            
            const autoMessage = `PENGINGAT JADWAL: Assalamualaikum ${name}, Anda dijadwalkan menjadi Khatib Jumat pada tanggal ${date} di ${masjid} pukul 08.00 WIB.`;
            pesanTextarea.value = autoMessage.substring(0, 160);
        } else if (selectedKhatib.value) {
            const name = selectedKhatib.text.split(' (')[0];
            const autoMessage = `Assalamualaikum ${name}, mohon periksa jadwal khotbah Jumat Anda di aplikasi SIKJ CMM. Terima kasih.`;
            pesanTextarea.value = autoMessage.substring(0, 160);
        }
        
        // Trigger input event to update character counter
        pesanTextarea.dispatchEvent(new Event('input'));
    };

    khatibSelect.addEventListener('change', updateMessage);
    jadwalSelect.addEventListener('change', updateMessage);
});
</script>
@endsection
