@extends('admin.layouts.admin')

@section('title', 'Edit Riwayat Badal')
@section('page_header', 'Edit Riwayat Badal')

@section('content')
<div class="content-card form-card">
    <!-- Back Button Link -->
    <a href="{{ route('admin.riwayatBadal.index') }}" class="form-header-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        <span>Kembali ke Riwayat Badal</span>
    </a>

    <div style="background-color: var(--primary-light); padding: 15px 20px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 13px;">
        <div style="margin-bottom: 5px;"><strong>Masjid:</strong> {{ $riwayat->masjid->nama }}</div>
        <div style="margin-bottom: 5px;"><strong>Tanggal Pengajuan:</strong> {{ $riwayat->tanggal_pengajuan->translatedFormat('d F Y') }}</div>
        <div><strong>Khatib Utama:</strong> {{ $riwayat->khatib->nama }}</div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.riwayatBadal.update', $riwayat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="pengganti_id">Pilih Khatib Pengganti (Tersedia)</label>
            <select name="pengganti_id" id="pengganti_id" class="form-control @error('pengganti_id') is-invalid @enderror">
                <option value="">-- Belum ada pengganti --</option>
                @foreach($khatibs as $k)
                    <option value="{{ $k->id }}" {{ old('pengganti_id', $riwayat->pengganti_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }} (NBM: {{ $k->nbm ?? '-' }})
                    </option>
                @endforeach
            </select>
            @error('pengganti_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="status">Status Verifikasi</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Belum Terverifikasi" {{ old('status', $riwayat->status) === 'Belum Terverifikasi' ? 'selected' : '' }}>Belum Terverifikasi</option>
                <option value="Sudah Terverifikasi" {{ old('status', $riwayat->status) === 'Sudah Terverifikasi' ? 'selected' : '' }}>Sudah Terverifikasi</option>
            </select>
        </div>

        <div class="form-actions-row">
            <button type="submit" class="btn btn-primary" style="flex: 1;">SIMPAN PERUBAHAN</button>
            <a href="{{ route('admin.riwayatBadal.index') }}" class="btn btn-secondary" style="flex: 1;">BATAL</a>
        </div>
    </form>
</div>
@endsection
