@extends('layouts.app')
@section('title', 'Log Failover ISP')
@section('page-title', 'Log Failover ISP')
@section('breadcrumb', 'Failover')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-arrow-left-right" style="color:#f59e0b"></i> Riwayat Failover ISP
                ({{ $failovers->total() }} event)</div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Penyebab</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failovers as $f)
                        <tr>
                            <td style="font-weight:500">{{ $f->tanggal->format('d/m/Y') }}</td>
                            <td style="font-family:monospace;font-size:0.8rem">{{ $f->waktu_mulai }}<br><span
                                    class="text-muted">→ {{ $f->waktu_selesai ?? 'berjalan' }}</span></td>
                            <td style="color:#f87171">{{ $f->provider_dari }}</td>
                            <td style="color:#34d399">{{ $f->provider_ke }}</td>
                            <td><span class="badge badge-warning">{{ $f->penyebab }}</span></td>
                            <td>{{ $f->durasi_menit ? $f->durasi_menit . ' mnt' : '-' }}</td>
                            <td><span
                                    class="badge {{ $f->status === 'Selesai' ? 'badge-success' : 'badge-warning' }}">{{ $f->status }}</span>
                            </td>
                            <td style="font-size:0.78rem;color:#94a3b8">{{ Str::limit($f->keterangan, 60) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#64748b;padding:50px">Belum ada log failover</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($failovers->hasPages())
            <div style="padding:14px;border-top:1px solid var(--border)">{{ $failovers->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
@endsection