@extends('admin.layouts.admin')

@section('title', 'Riwayat Aktivitas Admin')
@section('page_header', 'Riwayat Aktivitas')

@section('content')
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Daftar Log Aktivitas Sistem (Admin)</h2>
    </div>

    <!-- Filters Form -->
    <form method="GET" action="{{ route('admin.riwayatAktivitas') }}" style="display: flex; gap: 12px; margin-bottom: 25px; align-items: center; flex-wrap: wrap;">
        <div style="flex-grow: 1; min-width: 250px; position: relative;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari pelaku atau aktivitas..." class="form-control" style="padding-left: 40px; font-size: 13px;">
            <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <button type="submit" class="btn btn-primary" style="width: auto; padding: 10px 18px; font-size: 13px;">Filter</button>
        @if($search)
            <a href="{{ route('admin.riwayatAktivitas') }}" class="btn btn-secondary" style="width: auto; padding: 10px 18px; font-size: 13px;">Reset</a>
        @endif
    </form>

    <!-- Table content -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Waktu Kejadian</th>
                    <th>Pelaku (User)</th>
                    <th>Aktivitas</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td style="font-weight: 600; color: var(--primary); font-size: 12px;">
                            {{ $log->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') }} WIB
                        </td>
                        <td>
                            @if($log->user)
                                <div style="font-weight: 700;">{{ $log->user->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">@ {{ $log->user->username }}</div>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">Sistem</span>
                            @endif
                        </td>
                        <td style="font-weight: 500; font-size: 13px; line-height: 1.4; max-width: 300px; white-space: normal;">
                            {{ $log->activity }}
                        </td>
                        <td style="font-family: monospace; font-size: 11px; color: var(--text-muted);">
                            {{ $log->ip_address }}
                        </td>
                        <td style="font-size: 11px; color: var(--text-muted); max-width: 180px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $log->user_agent }}">
                            {{ $log->user_agent }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada log aktivitas sistem yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
