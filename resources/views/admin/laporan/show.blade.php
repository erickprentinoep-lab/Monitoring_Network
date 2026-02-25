@extends('layouts.app')
@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Harian')
@section('breadcrumb', 'Laporan → Detail')
@section('content')

    {{-- Header --}}
    <div class="card mb-20"
        style="border-left:4px solid {{ $laporan->status_jaringan === 'Normal' ? '#10b981' : ($laporan->status_jaringan === 'Degraded' ? '#f59e0b' : '#ef4444') }}">
        <div class="card-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
            <div class="grade-badge grade-{{ $laporan->grade }}">{{ $laporan->grade }}</div>
            <div>
                <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase">Laporan Harian —
                    {{ $laporan->tanggal->format('l, d F Y') }}</div>
                <div style="font-size:1.1rem;font-weight:700;margin-top:2px">
                    {{ $laporan->label_grade }}
                    <span class="badge badge-{{ \App\Helpers\GradeHelper::badgeStatus($laporan->status_jaringan) }}"
                        style="margin-left:8px">{{ $laporan->status_jaringan }}</span>
                </div>
                <div style="font-size:0.8rem;color:#94a3b8;margin-top:2px">ISP Aktif: {{ $laporan->isp_aktif ?? '-' }} ·
                    Dibuat oleh: {{ $laporan->user?->nama }}</div>
            </div>
            <div style="margin-left:auto;text-align:right">
                <div style="font-size:0.7rem;color:#94a3b8">UTILISASI BANDWIDTH</div>
                <div style="font-size:2rem;font-weight:900;color:#3b82f6">{{ $laporan->persentase_bandwidth }}%</div>
                <div style="font-size:0.8rem;color:#94a3b8">
                    {{ $laporan->total_bandwidth_terpakai }}/{{ $laporan->total_bandwidth_tersedia }} Mbps</div>
            </div>
            <div style="display:flex;gap:6px">
                <a href="{{ route('admin.laporan.edit', $laporan->id) }}" class="btn btn-warning btn-sm"><i
                        class="bi bi-pencil"></i> Edit</a>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
        </div>
    </div>

    <div class="grid grid-2 mb-20">
        {{-- Detail VLAN --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-diagram-3" style="color:#06b6d4"></i> Kondisi per VLAN</div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>VLAN</th>
                            <th>Terpakai</th>
                            <th>%</th>
                            <th>PKT Loss</th>
                            <th>Delay</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan->detailVlan as $dv)
                            <tr>
                                <td>
                                    <span
                                        style="background:rgba(59,130,246,0.15);color:#60a5fa;padding:1px 7px;border-radius:5px;font-size:0.7rem;font-weight:700">{{ $dv->vlan?->vlan_id }}</span>
                                    <span style="margin-left:4px;font-weight:500">{{ $dv->vlan?->nama }}</span>
                                </td>
                                <td>{{ $dv->bandwidth_terpakai }} Mbps</td>
                                <td
                                    style="font-weight:600;color:{{ $dv->persentase >= 80 ? '#10b981' : ($dv->persentase >= 60 ? '#06b6d4' : ($dv->persentase >= 40 ? '#f59e0b' : '#ef4444')) }}">
                                    {{ $dv->persentase }}%</td>
                                <td>{{ $dv->packet_loss }}%</td>
                                <td>{{ $dv->delay }}ms</td>
                                <td><span
                                        class="badge badge-{{ \App\Helpers\GradeHelper::badgeStatus($dv->status) }}">{{ $dv->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#64748b;padding:20px">Tidak ada data VLAN</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Catatan Operasional --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clipboard-text" style="color:#f59e0b"></i> Catatan Operasional</div>
            </div>
            <div class="card-body">
                @if($laporan->insiden)
                    <div style="margin-bottom:14px">
                        <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Insiden</div>
                        <div
                            style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:10px 12px;font-size:0.84rem">
                            {{ $laporan->insiden }}</div>
                    </div>
                @endif
                @if($laporan->tindakan)
                    <div style="margin-bottom:14px">
                        <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Tindakan</div>
                        <div
                            style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:8px;padding:10px 12px;font-size:0.84rem">
                            {{ $laporan->tindakan }}</div>
                    </div>
                @endif
                @if($laporan->catatan)
                    <div>
                        <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;margin-bottom:4px">Catatan</div>
                        <div style="font-size:0.84rem;color:#94a3b8">{{ $laporan->catatan }}</div>
                    </div>
                @endif
                @if(!$laporan->insiden && !$laporan->tindakan && !$laporan->catatan)
                    <p class="text-muted" style="font-size:0.84rem">Tidak ada catatan operasional.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Failover terkait --}}
    @if($laporan->failover->count() > 0)
        <div class="card mb-20">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-arrow-left-right" style="color:#f59e0b"></i> Log Failover pada Hari Ini
                </div>
                <a href="{{ route('admin.failover.create') }}" class="btn btn-outline btn-sm"><i class="bi bi-plus"></i>
                    Tambah</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Perpindahan</th>
                            <th>Penyebab</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan->failover as $f)
                            <tr>
                                <td style="font-size:0.8rem">{{ $f->waktu_mulai }} → {{ $f->waktu_selesai ?? '...' }}</td>
                                <td><span style="color:#f59e0b">{{ $f->provider_dari }}</span> <i class="bi bi-arrow-right"></i>
                                    <span style="color:#10b981">{{ $f->provider_ke }}</span></td>
                                <td><span class="badge badge-warning">{{ $f->penyebab }}</span></td>
                                <td>{{ $f->durasi_menit ? $f->durasi_menit . ' mnt' : '-' }}</td>
                                <td><span
                                        class="badge {{ $f->status === 'Selesai' ? 'badge-success' : 'badge-warning' }}">{{ $f->status }}</span>
                                </td>
                                <td style="font-size:0.78rem;color:#94a3b8">{{ Str::limit($f->keterangan, 60) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div style="display:flex;gap:10px">
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="{{ route('admin.laporan.edit', $laporan->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i>
            Edit</a>
        <a href="{{ route('admin.failover.create') }}" class="btn btn-outline"><i class="bi bi-plus"></i> Tambah Log
            Failover</a>
        <form method="POST" action="{{ route('admin.laporan.destroy', $laporan->id) }}"
            onsubmit="return confirm('Hapus laporan ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
        </form>
    </div>
@endsection