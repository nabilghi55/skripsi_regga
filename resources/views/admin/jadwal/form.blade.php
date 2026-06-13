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

        <div class="form-group">
            <label class="form-label" for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $jadwal->tanggal ? $jadwal->tanggal->format('Y-m-d') : '') }}" required>
            @error('tanggal')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="masjid_id">Masjid</label>
            <select name="masjid_id" id="masjid_id" class="form-control @error('masjid_id') is-invalid @enderror" required>
                <option value="">Pilih Masjid</option>
                @foreach($masjids as $masjid)
                    <option value="{{ $masjid->id }}" {{ old('masjid_id', $jadwal->masjid_id) == $masjid->id ? 'selected' : '' }}>
                        {{ $masjid->nama }} ({{ $masjid->kecamatan }})
                    </option>
                @endforeach
            </select>
            @error('masjid_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="khatib_id">Khatib</label>
            <select name="khatib_id" id="khatib_id" class="form-control @error('khatib_id') is-invalid @enderror" required>
                <option value="">Pilih Khatib</option>
                @foreach($khatibs as $khatib)
                    <option value="{{ $khatib->id }}" {{ old('khatib_id', $jadwal->khatib_id) == $khatib->id ? 'selected' : '' }}>
                        {{ $khatib->nama }}
                    </option>
                @endforeach
            </select>
            @error('khatib_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
            @error('keterangan')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        @if($isEdit)
            <div class="form-group">
                <label class="form-label" for="status">Status Jadwal</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Aktif" {{ old('status', $jadwal->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Perubahan Diajukan" {{ old('status', $jadwal->status) === 'Perubahan Diajukan' ? 'selected' : '' }}>Perubahan Diajukan</option>
                </select>
            </div>
        @endif

        <div class="form-actions-row">
            <button type="submit" class="btn btn-primary" style="flex: 1;">SIMPAN</button>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary" style="flex: 1;">BATAL</a>
        </div>
    </form>
</div>
@endsection
