@extends('layouts.app')

@section('title', 'Data Pengujian')
@section('page-title', 'Data Pengujian Jaringan')
@section('breadcrumb', 'Data Pengujian')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-table" style="color:#3b82f6"></i> Daftar Data Pengujian
                ({{ $pengujian->total() }} data)</div>
            <span class="badge badge-info"><i class="bi bi-eye"></i> Read Only</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Periode</th>
                        <th>Throughput (Kbps)</th>
                        <th>Delay (ms)</th>
                        <th>Jitter (ms)</th>
                        <th>Packet Loss (%)</th>
                        <th>Kategori</th>
                        <th>Kesimpulan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengujian as $i => $p)
                        <tr>
                            <td class="text-muted">{{ $pengujian->firstItem() + $i }}</td>
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <div style="font-weight:500">{{ $p->lokasi }}</div>
                                <div class="text-muted" style="font-size:0.72rem">{{ $p->user?->nama }}</div>
                            </td>
                            <td><span
                                    class="badge {{ $p->periode === 'sebelum' ? 'badge-warning' : 'badge-success' }}">{{ ucfirst($p->periode) }}</span>
                            </td>
                            <td>{{ $p->parameterQos?->throughput ?? '-' }}</td>
                            <td>{{ $p->parameterQos?->delay ?? '-' }}</td>
                            <td>{{ $p->parameterQos?->jitter ?? '-' }}</td>
                            <td>{{ $p->parameterQos?->packet_loss ?? '-' }}</td>
                            <td>
                                @if($p->parameterQos)
                                    @php $badge = \App\Helpers\QosHelper::badgeKategori($p->parameterQos->kategori); @endphp
                                    <span class="badge badge-{{ $badge }}">{{ $p->parameterQos->kategori }}</span>
                                @endif
                            </td>
                            <td style="max-width:200px;font-size:0.75rem;color:#94a3b8">
                                {{ Str::limit($p->parameterQos?->kesimpulan ?? '-', 60) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center;color:#64748b;padding:50px">Belum ada data pengujian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengujian->hasPages())
            <div style="padding:16px;border-top:1px solid var(--border)">
                {{ $pengujian->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
@endsection