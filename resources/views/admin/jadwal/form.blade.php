@extends('admin.layouts.admin')

@section('title', ($isEdit ? 'Edit' : 'Tambah') . ' Jadwal Khotbah')
@section('page_header', ($isEdit ? 'Edit' : 'Tambah') . ' Jadwal Khotbah')

@section('content')
<div class="content-card form-card">
    <!-- Back Button Link -->
    <a href="{{ route('admin.jadwal.index') }}" class="form-header-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Kembali ke Jadwal Khotbah</span>
    </a>

    <!-- Form -->
    <form action="{{ $isEdit ? route('admin.jadwal.update', $jadwal->id) : route('admin.jadwal.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-group" style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px;">
            <div>
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $jadwal->tanggal ? $jadwal->tanggal->format('Y-m-d') : '') }}" required>
                @error('tanggal')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="form-label" for="jumat_ke">Jumat Ke</label>
                <select name="jumat_ke" id="jumat_ke" class="form-control @error('jumat_ke') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('jumat_ke', $jadwal->jumat_ke) == $i ? 'selected' : '' }}>Ke-{{ $i }}</option>
                    @endfor
                </select>
                @error('jumat_ke')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="form-label" for="waktu_khutbah">Waktu Khutbah</label>
                <input type="text" name="waktu_khutbah" id="waktu_khutbah" class="form-control @error('waktu_khutbah') is-invalid @enderror" value="{{ old('waktu_khutbah', $jadwal->waktu_khutbah ?? '12:00') }}" placeholder="12:00" required>
                @error('waktu_khutbah')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="masjid_id">Masjid</label>
            <select name="masjid_id" id="masjid_id" class="form-control @error('masjid_id') is-invalid @enderror" required>
                <option value="">Pilih Masjid</option>
                @foreach($masjids as $m)
                    <option value="{{ $m->id }}" {{ old('masjid_id', $jadwal->masjid_id) == $m->id ? 'selected' : '' }}>{{ $m->nama }} ({{ $m->kecamatan }})</option>
                @endforeach
            </select>
            @error('masjid_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="khatib_id">Khatib / Ustad</label>
            <select name="khatib_id" id="khatib_id" class="form-control @error('khatib_id') is-invalid @enderror" required>
                <option value="">Pilih Khatib</option>
                @foreach($khatibs as $k)
                    <option value="{{ $k->id }}" {{ old('khatib_id', $jadwal->khatib_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->status }})</option>
                @endforeach
            </select>
            @error('khatib_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        @if($isEdit)
            <div class="form-group">
                <label class="form-label" for="status">Status Jadwal</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="Aktif" {{ old('status', $jadwal->status) === 'Aktif' ? 'selected' : '' }}>Aktif (Tugas Terjadwal)</option>
                    <option value="Hadir" {{ old('status', $jadwal->status) === 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Izin" {{ old('status', $jadwal->status) === 'Izin' ? 'selected' : '' }}>Izin (Udzur)</option>
                    <option value="Sakit" {{ old('status', $jadwal->status) === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Badal" {{ old('status', $jadwal->status) === 'Badal' ? 'selected' : '' }}>Badal (Khatib Pengganti)</option>
                    <option value="Tidak Hadir" {{ old('status', $jadwal->status) === 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir (Tanpa Keterangan)</option>
                    <option value="Perubahan Diajukan" {{ old('status', $jadwal->status) === 'Perubahan Diajukan' ? 'selected' : '' }}>Perubahan Diajukan</option>
                </select>
                @error('status')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        @else
            <input type="hidden" name="status" value="Aktif">
        @endif

        <div class="form-group">
            <label class="form-label" for="catatan_saran_takmir">Catatan Saran Takmir (Opsional)</label>
            <textarea name="catatan_saran_takmir" id="catatan_saran_takmir" rows="3" class="form-control @error('catatan_saran_takmir') is-invalid @enderror" placeholder="Masukkan masukan atau catatan dari Takmir">{{ old('catatan_saran_takmir', $jadwal->catatan_saran_takmir) }}</textarea>
            @error('catatan_saran_takmir')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="keterangan">Keterangan Tambahan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan tambahan jika ada">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
            @error('keterangan')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions-row">
            <button type="submit" class="btn btn-primary" style="flex: 1;">SIMPAN</button>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary" style="flex: 1;">BATAL</a>
        </div>
    </form>
</div>
@endsection
