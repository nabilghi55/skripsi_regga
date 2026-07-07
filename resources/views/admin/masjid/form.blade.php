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
    <form action="{{ $isEdit ? route('admin.masjid.update', $masjid->id) : route('admin.masjid.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-group" style="text-align: center; margin-bottom: 25px;">
            <label class="form-label" style="display: block; text-align: center;">Foto Masjid</label>
            <div class="profile-avatar-container" style="margin-bottom: 12px;">
                <div class="profile-avatar-wrapper" style="margin: 0 auto 12px;">
                    @if($masjid->foto_profile)
                        <img src="{{ asset('storage/' . $masjid->foto_profile) }}" id="preview-image" class="profile-avatar-image" alt="Preview Foto">
                    @else
                        <div id="avatar-placeholder" class="profile-avatar-placeholder">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
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

@if($isEdit)
<div class="content-card form-card" style="margin-top: 30px;">
    <h3 style="margin-bottom: 20px; font-weight: 800; color: var(--text-dark); display: flex; align-items: center; gap: 8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        <span>Akun Takmir Masjid</span>
    </h3>

    @if($masjid->user)
        <!-- If Takmir account exists, show edit form -->
        <form action="{{ route('admin.masjid.takmir.store', $masjid->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="takmir_name">Nama Lengkap Takmir</label>
                <input type="text" name="name" id="takmir_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $masjid->user->name) }}" required>
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label class="form-label" for="takmir_username">Username</label>
                    <input type="text" name="username" id="takmir_username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $masjid->user->username) }}" required>
                    @error('username')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="takmir_email">Email</label>
                    <input type="email" name="email" id="takmir_email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $masjid->user->email) }}" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="takmir_password">Password Baru <span style="font-weight: normal; font-size: 12px; color: var(--text-muted);">(Kosongkan jika tidak ingin mengubah password)</span></label>
                <input type="password" name="password" id="takmir_password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru minimal 8 karakter">
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primary" style="flex: 1;">PERBARUI AKUN TAKMIR</button>
            </div>
        </form>

        <form action="{{ route('admin.masjid.takmir.destroy', $masjid->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun takmir ini?')" style="margin-top: 15px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary" style="width: 100%; border-color: var(--danger); color: var(--danger);">HAPUS AKUN TAKMIR</button>
        </form>
    @else
        <!-- If Takmir account doesn't exist, show register form -->
        <form action="{{ route('admin.masjid.takmir.store', $masjid->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="takmir_name">Nama Lengkap Takmir</label>
                <input type="text" name="name" id="takmir_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fathoni" required>
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label class="form-label" for="takmir_username">Username</label>
                    <input type="text" name="username" id="takmir_username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Contoh: takmir_annur" required>
                    @error('username')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="takmir_email">Email</label>
                    <input type="email" name="email" id="takmir_email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Contoh: takmir@annur.or.id" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="takmir_password">Password</label>
                <input type="password" name="password" id="takmir_password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password minimal 8 karakter" required>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primary" style="flex: 1;">BUAT AKUN TAKMIR</button>
            </div>
        </form>
    @endif
</div>
@endif
@endsection

@section('scripts')
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
