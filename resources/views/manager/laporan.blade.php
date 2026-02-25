@extends('layouts.app')
@section('title', 'Riwayat Laporan')
@section('page-title', 'Riwayat Laporan Harian')
@section('breadcrumb', 'Laporan')
@section('content')
    <div class="card mb-20">
        <div class="card-body">
            <form method="GET">
                <div class="form-row" style="grid-template-columns:1fr 1fr 1fr auto;align-items:flex-end;gap:12px">
                    <div class="form-group" style="margin-bottom:0"><label class="form-label">Dari Tanggal</label><input
                            type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom:0"><label class="form-label">Sampai Tanggal</label><input
                            type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Grade</label>
                        <select name="grade" class="form-control">
                            <option value="">Semua</option>
                            @foreach(['A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang Baik'] as $g => $l)
                                <option value="{{ $g }}" {{ request('grade') === $g ? 'selected' : '' }}>{{ $g }} - {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;gap:6px"><button type="submit" class="btn btn-primary"><i
                                class="bi bi-search"></i></button><a href="{{ route('manager.laporan') }}"
                            class="btn btn-outline"><i class="bi bi-x-lg"></i></a></div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-journal-text" style="color:#3b82f6"></i> Daftar Laporan
                ({{ $laporan->total() }} data)</div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>ISP Aktif</th>
                        <th>Bandwidth</th>
                        <th>%</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Failover</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $l)
                        <tr>
                            <td style="font-weight:500">{{ $l->tanggal->format('d/m/Y') }}<br><span class="text-muted"
                                    style="font-size:0.7rem">{{ $l->tanggal->format('l') }}</span></td>
                            <td class="text-muted" style="font-size:0.82rem">{{ $l->isp_aktif ?? '-' }}</td>
                            <td>{{ $l->total_bandwidth_terpakai }}/{{ $l->total_bandwidth_tersedia }} <span class="text-muted"
                                    style="font-size:0.7rem">Mbps</span></td>
                            <td
                                style="font-weight:700;color:{{ $l->persentase_bandwidth >= 80 ? '#10b981' : ($l->persentase_bandwidth >= 60 ? '#06b6d4' : ($l->persentase_bandwidth >= 40 ? '#f59e0b' : '#ef4444')) }}">
                                {{ $l->persentase_bandwidth }}%</td>
                            <td><span class="grade-badge grade-{{ $l->grade }}"
                                    style="width:30px;height:30px;font-size:0.9rem">{{ $l->grade }}</span></td>
                            <td><span
                                    class="badge badge-{{ \App\Helpers\GradeHelper::badgeStatus($l->status_jaringan) }}">{{ $l->status_jaringan }}</span>
                            </td>
                            <td>{{ $l->failover->count() > 0 ? $l->failover->count() . ' event' : '-' }}</td>
                            <td><a href="{{ route('manager.laporan.show', $l->id) }}" class="btn btn-outline btn-sm"><i
                                        class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#64748b;padding:50px">Belum ada laporan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laporan->hasPages())
            <div style="padding:14px;border-top:1px solid var(--border)">{{ $laporan->links('vendor.pagination.custom') }}</div>
        @endif
    </div>
@endsection