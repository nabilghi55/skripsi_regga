@extends('admin.layouts.admin')

@section('title', 'Isi Jadwal Badal')
@section('page_header', 'Isi Jadwal Badal')

@section('content')
<div class="content-card form-card">
    <!-- Back Button Link -->
    <a href="{{ route('admin.badal.index', ['tanggal' => $date]) }}" class="form-header-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Kembali ke Halaman Badal</span>
    </a>

    <div style="background-color: var(--primary-light); padding: 15px 20px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 13px;">
        <div style="margin-bottom: 5px;"><strong>Masjid Tujuan:</strong> {{ $masjid->nama }} ({{ $masjid->kecamatan }})</div>
        <div><strong>Hari / Tanggal:</strong> {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.badal.tambahJadwalStore') }}" method="POST">
        @csrf
        
        <input type="hidden" name="masjid_id" value="{{ $masjid->id }}">
        <input type="hidden" name="tanggal" value="{{ $date }}">

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label class="form-label" for="jumat_ke">Jumat Ke</label>
                @php
                    // Pre-calculate Friday cycle
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $dayOfMonth = $carbonDate->day;
                    $jumatIndex = ceil($dayOfMonth / 7);
                @endphp
                <select name="jumat_ke" id="jumat_ke" class="form-control" required>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $jumatIndex == $i ? 'selected' : '' }}>Ke-{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label" for="waktu_khutbah">Waktu Khutbah</label>
                <input type="text" name="waktu_khutbah" id="waktu_khutbah" class="form-control" value="12:00" placeholder="12:00" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="khatib_id">Pilih Khatib Pengganti (Tersedia)</label>
            <select name="khatib_id" id="khatib_id" class="form-control @error('khatib_id') is-invalid @enderror" required>
                <option value="">-- Pilih Ustad yang tersedia --</option>
                @foreach($khatibs as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }} (NBM: {{ $k->nbm ?? '-' }})</option>
                @endforeach
            </select>
            @error('khatib_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
            <p style="font-size: 11px; color: var(--text-muted); margin-top: 5px;">Hanya menampilkan Khatib yang sedang tidak bertugas dan berstatus Normal.</p>
        </div>

        <div class="form-group">
            <label class="form-label" for="keterangan">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Masukkan catatan penggantian, contoh: Penggantian mendadak karena Ustad utama sakit">Penugasan Khatib Badal</textarea>
        </div>

        <div class="form-actions-row">
            <button type="submit" class="btn btn-primary" style="flex: 1;">SIMPAN & KIRIM WHATSAPP</button>
            <a href="{{ route('admin.badal.index', ['tanggal' => $date]) }}" class="btn btn-secondary" style="flex: 1;">BATAL</a>
        </div>
    </form>
</div>
@endsection
