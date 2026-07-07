@extends('admin.layouts.admin')

@section('title', 'Data Masjid')
@section('page_header', 'Data Masjid')

@section('content')
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 25px;">
        <!-- Search and Filter Form -->
        <form action="{{ route('admin.masjid.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-grow: 1; max-width: 500px; flex-wrap: wrap;">
            <div style="flex-grow: 1; min-width: 180px; position: relative;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode masjid..." class="form-control" style="padding-left: 40px; font-size: 13px;">
                <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <div style="width: 160px;">
                <select name="kecamatan" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatans as $kec)
                        <option value="{{ $kec }}" {{ $kecamatan === $kec ? 'selected' : '' }}>{{ $kec }}</option>
                    @endforeach
                </select>
            </div>
            @if($search || $kecamatan)
                <a href="{{ route('admin.masjid.index') }}" class="btn btn-secondary" style="width: auto; padding: 10px 14px; font-size: 13px;">Reset</a>
            @endif
        </form>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Unduh Excel Button -->
            <a href="{{ route('admin.masjid.export', ['search' => $search, 'kecamatan' => $kecamatan]) }}" class="btn btn-secondary" style="width: auto; padding: 10px 16px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Unduh Excel</span>
            </a>

            <!-- Download PDF Button -->
            <a href="{{ route('admin.masjid.cetak', ['search' => $search, 'kecamatan' => $kecamatan]) }}" target="_blank" class="btn btn-secondary" style="width: auto; padding: 10px 16px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak PDF</span>
            </a>

            <!-- Tambah button -->
            <a href="{{ route('admin.masjid.create') }}" class="btn btn-primary btn-add">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Data
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Masjid</th>
                    <th>Nama Masjid</th>
                    <th>Alamat</th>
                    <th>No HP 1</th>
                    <th>No HP 2</th>
                    <th>Kecamatan</th>
                    <th>Akun Takmir</th>
                    <th>Kategori</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($masjids as $index => $m)
                    <tr>
                        <td>{{ $masjids->firstItem() + $index }}</td>
                        <td><code style="font-weight: bold; color: var(--text-dark);">{{ $m->kode_masjid ?? '-' }}</code></td>
                        <td style="font-weight: 700; white-space: nowrap;">
                            <!-- Image Avatar Preview -->
                            @if($m->foto_profile)
                                <img src="{{ asset('storage/' . $m->foto_profile) }}" class="table-profile-img" alt="Masjid">
                            @else
                                <div class="table-profile-img-placeholder">{{ substr($m->nama, 0, 2) }}</div>
                            @endif
                            <a href="{{ route('admin.masjid.edit', $m->id) }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">
                                {{ $m->nama }}
                            </a>
                        </td>
                        <td>{{ $m->alamat }}</td>
                        <td>{{ $m->no_hp_1 ?? '-' }}</td>
                        <td>{{ $m->no_hp_2 ?? '-' }}</td>
                        <td>{{ $m->kecamatan }}</td>
                        <td>
                            @if($m->user)
                                <span class="badge badge-active" title="Username: {{ $m->user->username }}">
                                    {{ $m->user->name }}
                                </span>
                            @else
                                <span class="badge badge-inactive">
                                    Belum Ada Akun
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $m->kategori === 'Masjid Muhammadiyah' ? 'badge-active' : 'badge-warning' }}">
                                {{ $m->kategori }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons" style="justify-content: center;">
                                <a href="{{ route('admin.masjid.edit', $m->id) }}" class="btn-action" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.masjid.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data masjid ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada data masjid ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($masjids->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $masjids->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
