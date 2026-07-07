@extends('admin.layouts.admin')

@section('title', 'Kelola Notifikasi')
@section('page_header', 'Kelola Notifikasi')

@section('content')
<div class="content-card">
    <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;">Kirim Pengingat Jadwal (WhatsApp)</h2>
    
    <!-- Tab Navigation -->
    <div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
        <button type="button" id="tab-khatib" class="btn" style="background-color: var(--primary); color: white; width: auto; font-size: 13px; padding: 8px 16px;">Kirim ke Khatib</button>
        <button type="button" id="tab-masjid" class="btn btn-secondary" style="width: auto; font-size: 13px; padding: 8px 16px;">Kirim ke Takmir Masjid</button>
    </div>

    <form action="{{ route('admin.notification.store') }}" method="POST" target="_blank" id="notif-form">
        @csrf
        
        <!-- Hidden target type input -->
        <input type="hidden" name="target_type" id="target_type" value="khatib">

        <!-- Khatib Select Form Group -->
        <div class="form-group" id="group-khatib">
            <label class="form-label" for="khatib_id">Pilih Khatib</label>
            <select name="khatib_id" id="khatib_id" class="form-control @error('khatib_id') is-invalid @enderror">
                <option value="">Pilih Khatib</option>
                @foreach($khatibs as $khatib)
                    <option value="{{ $khatib->id }}" data-nama="{{ $khatib->nama }}">
                        {{ $khatib->nama }} ({{ $khatib->no_hp }})
                    </option>
                @endforeach
            </select>
            @error('khatib_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <!-- Masjid Select Form Group -->
        <div class="form-group" id="group-masjid" style="display: none;">
            <label class="form-label" for="masjid_id">Pilih Masjid</label>
            <select name="masjid_id" id="masjid_id" class="form-control @error('masjid_id') is-invalid @enderror">
                <option value="">Pilih Masjid</option>
                @foreach($masjids as $masjid)
                    @php
                        $hp = $masjid->no_hp_1 ?: ($masjid->no_hp_2 ?: '-');
                    @endphp
                    <option value="{{ $masjid->id }}" data-nama="{{ $masjid->nama }}">
                        {{ $masjid->nama }} (Takmir: {{ $hp }})
                    </option>
                @endforeach
            </select>
            @error('masjid_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <!-- Schedule Select Form Group -->
        <div class="form-group">
            <label class="form-label" for="jadwal_id">Pilih Jadwal Terkait</label>
            <select name="jadwal_id" id="jadwal_id" class="form-control @error('jadwal_id') is-invalid @enderror" required>
                <option value="">Pilih Khatib/Masjid terlebih dahulu</option>
            </select>
            @error('jadwal_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <!-- Message TextArea -->
        <div class="form-group">
            <label class="form-label" for="pesan">Pesan Notifikasi (Maks. 200 karakter)</label>
            <textarea name="pesan" id="pesan" rows="4" maxlength="200" data-counter="char-counter" class="form-control @error('pesan') is-invalid @enderror" placeholder="Pesan notifikasi otomatis terisi..." required></textarea>
            <div class="char-counter" style="text-align: right; font-size: 11px; margin-top: 5px; color: var(--text-muted);">
                <span id="char-counter">0/200</span>
            </div>
            @error('pesan')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 24px;">KIRIM VIA WHATSAPP</button>
    </form>
</div>

<!-- Riwayat Notifikasi Table -->
<div class="content-card" style="margin-top: 30px;">
    <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;">Riwayat Notifikasi</h2>
    
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Penerima</th>
                    <th>Tipe</th>
                    <th>Jadwal Terkait</th>
                    <th>Isi Pesan</th>
                    <th>Waktu Kirim</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $index => $notif)
                    <tr>
                        <td>{{ $notifications->firstItem() + $index }}</td>
                        <td>
                            @if($notif->khatib)
                                <div style="font-weight: 700;">{{ $notif->khatib->nama }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $notif->khatib->no_hp }}</div>
                            @elseif($notif->masjid)
                                <div style="font-weight: 700;">Takmir {{ $notif->masjid->nama }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $notif->masjid->no_hp_1 ?: $notif->masjid->no_hp_2 }}</div>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $notif->khatib ? 'badge-active' : 'badge-inactive' }}" style="background-color: {{ $notif->khatib ? 'var(--primary-light)' : 'rgba(245, 158, 11, 0.1)' }}; color: {{ $notif->khatib ? 'var(--primary)' : 'var(--warning)' }};">
                                {{ $notif->khatib ? 'Khatib' : 'Takmir Masjid' }}
                            </span>
                        </td>
                        <td>
                            @if($notif->jadwal)
                                <div style="font-weight: 600;">{{ $notif->jadwal->tanggal->translatedFormat('d M Y') }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $notif->jadwal->masjid->nama }}</div>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; max-width: 250px; white-space: normal; line-height: 1.4;">
                            {{ $notif->pesan }}
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">
                            {{ $notif->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                        </td>
                        <td>
                            @if($notif->khatib)
                                @if($notif->read_at)
                                    <span class="badge badge-active" style="font-size: 10px;">Dibaca {{ $notif->read_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
                                @else
                                    <span class="badge badge-inactive" style="font-size: 10px;">Belum Dibaca</span>
                                @endif
                            @else
                                <span class="badge badge-active" style="background-color: var(--border-color); color: var(--text-dark); font-size: 10px;">Terkirim</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Belum ada riwayat pengiriman notifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const jadwals = @json($jadwals);
    
    const tabKhatib = document.getElementById('tab-khatib');
    const tabMasjid = document.getElementById('tab-masjid');
    const targetTypeInput = document.getElementById('target_type');
    
    const groupKhatib = document.getElementById('group-khatib');
    const groupMasjid = document.getElementById('group-masjid');
    
    const khatibSelect = document.getElementById('khatib_id');
    const masjidSelect = document.getElementById('masjid_id');
    const jadwalSelect = document.getElementById('jadwal_id');
    const pesanTextarea = document.getElementById('pesan');
    const counterSpan = document.getElementById('char-counter');

    // Switch to Khatib Tab
    tabKhatib.addEventListener('click', () => {
        tabKhatib.style.backgroundColor = 'var(--primary)';
        tabKhatib.style.color = 'white';
        tabMasjid.style.backgroundColor = 'transparent';
        tabMasjid.style.color = 'var(--text-muted)';
        
        targetTypeInput.value = 'khatib';
        groupKhatib.style.display = 'block';
        groupMasjid.style.display = 'none';
        
        khatibSelect.required = true;
        masjidSelect.required = false;
        masjidSelect.value = '';
        
        resetScheduleAndMessage();
    });

    // Switch to Masjid Tab
    tabMasjid.addEventListener('click', () => {
        tabMasjid.style.backgroundColor = 'var(--primary)';
        tabMasjid.style.color = 'white';
        tabKhatib.style.backgroundColor = 'transparent';
        tabKhatib.style.color = 'var(--text-muted)';
        
        targetTypeInput.value = 'masjid';
        groupKhatib.style.display = 'none';
        groupMasjid.style.display = 'block';
        
        khatibSelect.required = false;
        khatibSelect.value = '';
        masjidSelect.required = true;
        
        resetScheduleAndMessage();
    });

    const resetScheduleAndMessage = () => {
        jadwalSelect.innerHTML = '<option value="">Pilih Khatib/Masjid terlebih dahulu</option>';
        pesanTextarea.value = '';
        counterSpan.textContent = '0/200';
    };

    // Helper to format date in JS (similar to Carbon translatedFormat)
    const formatIndoDate = (dateStr) => {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const d = new Date(dateStr);
        const day = d.getDate();
        const month = months[d.getMonth()];
        const year = d.getFullYear();
        return `${day} ${month} ${year}`;
    };

    const populateSchedules = () => {
        const targetType = targetTypeInput.value;
        let filteredJadwals = [];
        
        if (targetType === 'khatib' && khatibSelect.value) {
            filteredJadwals = jadwals.filter(j => j.khatib_id == khatibSelect.value);
        } else if (targetType === 'masjid' && masjidSelect.value) {
            filteredJadwals = jadwals.filter(j => j.masjid_id == masjidSelect.value);
        }
        
        jadwalSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
        
        filteredJadwals.forEach(j => {
            const dateFormatted = formatIndoDate(j.tanggal);
            const text = `${dateFormatted} - ${j.masjid.nama} (Khatib: ${j.khatib.nama})`;
            const option = document.createElement('option');
            option.value = j.id;
            option.textContent = text;
            option.setAttribute('data-tanggal', dateFormatted);
            option.setAttribute('data-masjid', j.masjid.nama);
            option.setAttribute('data-khatib', j.khatib.nama);
            option.setAttribute('data-waktu', j.waktu_khutbah.substring(0, 5));
            jadwalSelect.appendChild(option);
        });

        if (filteredJadwals.length === 0) {
            jadwalSelect.innerHTML = '<option value="">Tidak ada jadwal yang tersedia</option>';
        }
        
        pesanTextarea.value = '';
        counterSpan.textContent = '0/200';
    };

    khatibSelect.addEventListener('change', populateSchedules);
    masjidSelect.addEventListener('change', populateSchedules);

    // Auto-generate message when schedule is selected
    jadwalSelect.addEventListener('change', () => {
        const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            pesanTextarea.value = '';
            counterSpan.textContent = '0/200';
            return;
        }

        const date = selectedOption.getAttribute('data-tanggal');
        const masjid = selectedOption.getAttribute('data-masjid');
        const khatib = selectedOption.getAttribute('data-khatib');
        const waktu = selectedOption.getAttribute('data-waktu');
        const targetType = targetTypeInput.value;

        let autoMessage = '';
        if (targetType === 'khatib') {
            autoMessage = `PENGINGAT JADWAL: Assalamualaikum ${khatib}, Anda dijadwalkan menjadi Khatib Jumat pada tanggal ${date} di ${masjid} pukul ${waktu} WIB. Mohon konfirmasinya. Terima kasih.`;
        } else {
            autoMessage = `PENGINGAT JADWAL: Assalamualaikum Takmir ${masjid}, berikut adalah jadwal Khatib Jumat terdekat di masjid Anda: ${khatib} pada tanggal ${date} pukul ${waktu} WIB. Mohon konfirmasi kesiapannya. Terima kasih.`;
        }

        pesanTextarea.value = autoMessage.substring(0, 200);
        pesanTextarea.dispatchEvent(new Event('input'));
    });
});
</script>
@endsection
