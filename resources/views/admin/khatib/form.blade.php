@extends('admin.layouts.admin')

@section('title', ($isEdit ? 'Edit' : 'Tambah') . ' Data Khatib')
@section('page_header', ($isEdit ? 'Edit' : 'Tambah') . ' Data Khatib')

@section('content')
<div class="content-card form-card">
    <!-- Back Button Link -->
    <a href="{{ route('admin.khatib.index') }}" class="form-header-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Kembali ke Data Khatib</span>
    </a>

    <!-- Form -->
    <form action="{{ $isEdit ? route('admin.khatib.update', $khatib->id) : route('admin.khatib.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $khatib->nama) }}" placeholder="Masukkan nama khatib" required>
            @error('nama')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="no_hp">No HP</label>
            <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $khatib->no_hp) }}" placeholder="Masukkan nomor HP (WhatsApp)" required>
            @error('no_hp')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $khatib->alamat) }}</textarea>
            @error('alamat')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="status" value="Aktif" {{ old('status', $khatib->status ?? 'Aktif') === 'Aktif' ? 'checked' : '' }}>
                    <span>Aktif</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="status" value="Tidak Aktif" {{ old('status', $khatib->status) === 'Tidak Aktif' ? 'checked' : '' }}>
                    <span>Tidak Aktif</span>
                </label>
            </div>
            @error('status')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions-row">
            <button type="submit" class="btn btn-primary" style="flex: 1;">SIMPAN</button>
            <a href="{{ route('admin.khatib.index') }}" class="btn btn-secondary" style="flex: 1;">BATAL</a>
        </div>
    </form>
</div>
@endsection
