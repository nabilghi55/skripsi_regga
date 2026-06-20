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
    <form action="{{ $isEdit ? route('admin.khatib.update', $khatib->id) : route('admin.khatib.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-group" style="text-align: center; margin-bottom: 25px;">
            <label class="form-label" style="display: block; text-align: center;">Foto Profil</label>
            <div class="profile-avatar-container" style="margin-bottom: 12px;">
                <div class="profile-avatar-wrapper" style="margin: 0 auto 12px;">
                    @if($khatib->foto_profile)
                        <img src="{{ asset('storage/' . $khatib->foto_profile) }}" id="preview-image" class="profile-avatar-image" alt="Preview Foto">
                    @else
                        <div id="avatar-placeholder" class="profile-avatar-placeholder">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <img src="" id="preview-image" class="profile-avatar-image" style="display: none;" alt="Preview Foto">
                    @endif
                </div>
            </div>
            <input type="file" name="foto_profile" id="foto_profile" class="form-control" accept="image/*" onchange="previewFile()" style="max-width: 300px; margin: 0 auto;">
            @error('foto_profile')
                <span class="error-text" style="display: block; text-align: center;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="nbm">Nomor Baku Muhammadiyah (NBM)</label>
            <input type="text" name="nbm" id="nbm" class="form-control @error('nbm') is-invalid @enderror" value="{{ old('nbm', $khatib->nbm) }}" placeholder="Masukkan NBM (jika ada)">
            @error('nbm')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $khatib->nama) }}" placeholder="Masukkan nama khatib" required>
            @error('nama')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label class="form-label" for="no_hp">No HP 1 (WhatsApp)</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $khatib->no_hp) }}" placeholder="Masukkan nomor HP utama" required>
                @error('no_hp')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="form-label" for="no_hp_2">No HP 2 (Opsional)</label>
                <input type="text" name="no_hp_2" id="no_hp_2" class="form-control @error('no_hp_2') is-invalid @enderror" value="{{ old('no_hp_2', $khatib->no_hp_2) }}" placeholder="Masukkan nomor HP kedua">
                @error('no_hp_2')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $khatib->tanggal_lahir ? $khatib->tanggal_lahir->format('Y-m-d') : '') }}">
            @error('tanggal_lahir')
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
            <div class="radio-group" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px;">
                <label class="radio-label">
                    <input type="radio" name="status" value="Normal" {{ old('status', $khatib->status ?? 'Normal') === 'Normal' ? 'checked' : '' }}>
                    <span>Normal</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="status" value="Udzur / Sakit" {{ old('status', $khatib->status) === 'Udzur / Sakit' ? 'checked' : '' }}>
                    <span>Udzur / Sakit</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="status" value="Off" {{ old('status', $khatib->status) === 'Off' ? 'checked' : '' }}>
                    <span>Off</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="status" value="Tugas / Izin" {{ old('status', $khatib->status) === 'Tugas / Izin' ? 'checked' : '' }}>
                    <span>Tugas / Izin</span>
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

<script>
function previewFile() {
    const preview = document.getElementById('preview-image');
    const file = document.getElementById('foto_profile').files[0];
    const placeholder = document.getElementById('avatar-placeholder');
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
        preview.style.display = "block";
        if (placeholder) {
            placeholder.style.display = "none";
        }
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
