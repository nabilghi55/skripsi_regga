@extends('admin.layouts.admin')

@section('title', ($isEdit ? 'Edit' : 'Tambah') . ' Data Masjid')
@section('page_header', ($isEdit ? 'Edit' : 'Tambah') . ' Data Masjid')

@section('content')
<div class="content-card form-card">
    <!-- Back Button Link -->
    <a href="{{ route('admin.masjid.index') }}" class="form-header-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Kembali ke Data Masjid</span>
    </a>

    <!-- Form -->
    <form action="{{ $isEdit ? route('admin.masjid.update', $masjid->id) : route('admin.masjid.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="kode_masjid">Kode Masjid</label>
            <input type="text" name="kode_masjid" id="kode_masjid" class="form-control @error('kode_masjid') is-invalid @enderror" value="{{ old('kode_masjid', $masjid->kode_masjid) }}" placeholder="Contoh: M001">
            @error('kode_masjid')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="nama">Nama Masjid</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $masjid->nama) }}" placeholder="Masukkan nama masjid" required>
            @error('nama')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="alamat">Alamat Lengkap</label>
            <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap masjid" required>{{ old('alamat', $masjid->alamat) }}</textarea>
            @error('alamat')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label class="form-label" for="no_hp_1">No HP 1 (Takmir Utama)</label>
                <input type="text" name="no_hp_1" id="no_hp_1" class="form-control @error('no_hp_1') is-invalid @enderror" value="{{ old('no_hp_1', $masjid->no_hp_1) }}" placeholder="Masukkan nomor HP takmir 1">
                @error('no_hp_1')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="form-label" for="no_hp_2">No HP 2 (Takmir Kedua)</label>
                <input type="text" name="no_hp_2" id="no_hp_2" class="form-control @error('no_hp_2') is-invalid @enderror" value="{{ old('no_hp_2', $masjid->no_hp_2) }}" placeholder="Masukkan nomor HP takmir 2">
                @error('no_hp_2')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label class="form-label" for="kecamatan">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" required>
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatans as $kec)
                        <option value="{{ $kec }}" {{ old('kecamatan', $masjid->kecamatan) === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
                @error('kecamatan')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="form-label" for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="Masjid Muhammadiyah" {{ old('kategori', $masjid->kategori) === 'Masjid Muhammadiyah' ? 'selected' : '' }}>Masjid Muhammadiyah</option>
                    <option value="Masjid Independen" {{ old('kategori', $masjid->kategori) === 'Masjid Independen' ? 'selected' : '' }}>Masjid Independen</option>
                </select>
                @error('kategori')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="google_maps_link">Link Google Maps</label>
            <input type="url" name="google_maps_link" id="google_maps_link" class="form-control @error('google_maps_link') is-invalid @enderror" value="{{ old('google_maps_link', $masjid->google_maps_link) }}" placeholder="Masukkan link google maps">
            @error('google_maps_link')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions-row">
            <button type="submit" class="btn btn-primary" style="flex: 1;">SIMPAN</button>
            <a href="{{ route('admin.masjid.index') }}" class="btn btn-secondary" style="flex: 1;">BATAL</a>
        </div>
    </form>
</div>
@endsection
