@extends('admin.layouts.admin')

@section('title', 'Data Masjid')
@section('page_header', 'Data Masjid')

@section('content')
<div class="content-card">
    <div class="card-header-actions">
        <!-- Search bar -->
        <form action="{{ route('admin.masjid.index') }}" method="GET" class="search-box">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari data masjid..." class="search-input">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </form>

        <!-- Tambah button -->
        <a href="{{ route('admin.masjid.create') }}" class="btn btn-primary btn-add">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Data
        </a>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Masjid</th>
                    <th>Kecamatan</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($masjids as $index => $m)
                    <tr>
                        <td>{{ $masjids->firstItem() + $index }}</td>
                        <td style="font-weight: 700;">{{ $m->nama }}</td>
                        <td>{{ $m->kecamatan }}</td>
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
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
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
