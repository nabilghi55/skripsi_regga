@extends('khatib.layouts.khatib')

@section('title', 'Profil Saya')

@section('content')
<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr; gap: 24px; max-width: 600px; margin: 0 auto; padding-bottom: 40px;">
    <!-- Profile Info and Edit Form Card -->
    <div class="content-card" style="margin-bottom: 0;">
        <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            PROFIL SAYA
        </h3>

        <form action="{{ route('khatib.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group" style="text-align: center; margin-bottom: 24px;">
                <div class="profile-avatar-container">
                    <div class="profile-avatar-wrapper" style="margin: 0 auto 12px;">
                        @if($khatib->foto_profile)
                            <img src="{{ asset('storage/' . $khatib->foto_profile) }}" id="khatib-preview-image" class="profile-avatar-image" alt="Foto Profil">
                        @else
                            <div id="khatib-avatar-placeholder" class="profile-avatar-placeholder">
                                <svg width="50" height="50" viewBox="0 0 24 24" fill="currentColor" style="color: var(--primary);"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                            </div>
                            <img src="" id="khatib-preview-image" class="profile-avatar-image" style="display: none;" alt="Foto Profil">
                        @endif
                    </div>
                </div>
                <label class="form-label" style="cursor: pointer; color: var(--primary); text-transform: none; font-size: 13px; font-weight: bold;">
                    <span>Ganti Foto Profil</span>
                    <input type="file" name="foto_profile" id="khatib_foto_profile" accept="image/*" onchange="previewKhatibPhoto()" style="display: none;">
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Baku Muhammadiyah (NBM)</label>
                <input type="text" class="form-control" value="{{ $khatib->nbm ?? 'Belum terdaftar' }}" disabled style="background-color: var(--bg-main); font-weight: bold; color: var(--text-muted);">
                <span style="font-size: 11px; color: var(--text-muted);">NBM hanya dapat diubah oleh Admin Pengurus CMM.</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $khatib->nama) }}" required>
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label class="form-label" for="no_hp">No HP 1 (WhatsApp)</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $khatib->no_hp) }}" required>
                </div>
                <div>
                    <label class="form-label" for="no_hp_2">No HP 2</label>
                    <input type="text" name="no_hp_2" id="no_hp_2" class="form-control" value="{{ old('no_hp_2', $khatib->no_hp_2) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" required>{{ old('alamat', $khatib->alamat) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">SIMPAN PROFIL</button>
        </form>
    </div>

    <!-- Change Password Card -->
    <div class="content-card" style="margin-bottom: 0;">
        <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            GANTI PASSWORD
        </h3>

        <form action="{{ route('khatib.profile.password') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="current_password">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password">Password Baru</label>
                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Masukkan password baru (minimal 6 karakter)" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
            </div>

            <button type="submit" class="btn btn-primary" style="background-color: var(--danger);">GANTI PASSWORD</button>
        </form>
    </div>
</div>

<script>
function previewKhatibPhoto() {
    const preview = document.getElementById('khatib-preview-image');
    const file = document.getElementById('khatib_foto_profile').files[0];
    const placeholder = document.getElementById('khatib-avatar-placeholder');
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
