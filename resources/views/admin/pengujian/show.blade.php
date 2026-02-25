@extends('layouts.app')

@section('title', 'Detail Pengujian')
@section('page-title', 'Detail Pengujian Jaringan')
@section('breadcrumb', 'Pengujian → Detail')

@section('content')
    <div class="grid grid-2">
        {{-- Info Pengujian --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clipboard" style="color:#3b82f6"></i> Informasi Pengujian</div>
                <div style="display:flex;gap:6px">
                    <a href="{{ route('admin.pengujian.edit', $pengujian->id_pengujian) }}"
                        class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                </div>
            </div>
            <div class="card-body">
                <table style="width:100%">
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem;width:40%">ID Pengujian</td>
                        <td style="font-weight:600">#{{ $pengujian->id_pengujian }}</td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem">Tanggal</td>
                        <td>{{ $pengujian->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem">Lokasi</td>
                        <td style="font-weight:500">{{ $pengujian->lokasi }}</td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem">Periode</td>
                        <td>
                            <span class="badge {{ $pengujian->periode === 'sebelum' ? 'badge-warning' : 'badge-success' }}">
                                {{ ucfirst($pengujian->periode) }} Manajemen
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem">Diinput oleh</td>
                        <td>{{ $pengujian->user?->nama }}</td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem">Keterangan</td>
                        <td>{{ $pengujian->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:8px 0;font-size:0.82rem">Dibuat</td>
                        <td style="font-size:0.8rem">{{ $pengujian->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Parameter QoS --}}
        @if($pengujian->parameterQos)
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-diagram-3" style="color:#10b981"></i> Parameter QoS</div>
                    @php $qos = $pengujian->parameterQos;
                    $badge = \App\Helpers\QosHelper::badgeKategori($qos->kategori); @endphp
                    <span class="badge badge-{{ $badge }}"
                        style="font-size:0.82rem;padding:5px 14px">{{ $qos->kategori }}</span>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div
                            style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);border-radius:10px;padding:16px;text-align:center">
                            <div
                                style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                Throughput</div>
                            <div style="font-size:1.5rem;font-weight:700;color:#3b82f6">{{ $qos->throughput }}</div>
                            <div style="font-size:0.72rem;color:#94a3b8">Kbps</div>
                            @php $s = \App\Helpers\QosHelper::skorThroughput($qos->throughput); @endphp
                            <div style="margin-top:6px">
                                @for($i = 1; $i <= 4; $i++) <span
                                style="font-size:0.8rem;color:{{ $i <= $s ? '#3b82f6' : '#334155' }}">●</span> @endfor
                            </div>
                        </div>
                        <div
                            style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:16px;text-align:center">
                            <div
                                style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                Delay</div>
                            <div style="font-size:1.5rem;font-weight:700;color:#f59e0b">{{ $qos->delay }}</div>
                            <div style="font-size:0.72rem;color:#94a3b8">ms</div>
                            @php $s = \App\Helpers\QosHelper::skorDelay($qos->delay); @endphp
                            <div style="margin-top:6px">
                                @for($i = 1; $i <= 4; $i++) <span
                                style="font-size:0.8rem;color:{{ $i <= $s ? '#f59e0b' : '#334155' }}">●</span> @endfor
                            </div>
                        </div>
                        <div
                            style="background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.2);border-radius:10px;padding:16px;text-align:center">
                            <div
                                style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                Jitter</div>
                            <div style="font-size:1.5rem;font-weight:700;color:#06b6d4">{{ $qos->jitter }}</div>
                            <div style="font-size:0.72rem;color:#94a3b8">ms</div>
                            @php $s = \App\Helpers\QosHelper::skorJitter($qos->jitter); @endphp
                            <div style="margin-top:6px">
                                @for($i = 1; $i <= 4; $i++) <span
                                style="font-size:0.8rem;color:{{ $i <= $s ? '#06b6d4' : '#334155' }}">●</span> @endfor
                            </div>
                        </div>
                        <div
                            style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:10px;padding:16px;text-align:center">
                            <div
                                style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                Packet Loss</div>
                            <div style="font-size:1.5rem;font-weight:700;color:#ef4444">{{ $qos->packet_loss }}</div>
                            <div style="font-size:0.72rem;color:#94a3b8">%</div>
                            @php $s = \App\Helpers\QosHelper::skorPacketLoss($qos->packet_loss); @endphp
                            <div style="margin-top:6px">
                                @for($i = 1; $i <= 4; $i++) <span
                                style="font-size:0.8rem;color:{{ $i <= $s ? '#ef4444' : '#334155' }}">●</span> @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Kesimpulan Analisis --}}
    @if($pengujian->parameterQos?->kesimpulan)
        <div class="card mt-20">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-lightbulb" style="color:#f59e0b"></i> Kesimpulan Analisis Otomatis</div>
            </div>
            <div class="card-body">
                <div class="info-box">
                    <p style="font-size:0.875rem;line-height:1.7;color:#e2e8f0">{{ $pengujian->parameterQos->kesimpulan }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-20" style="display:flex;gap:10px">
        <a href="{{ route('admin.pengujian.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="{{ route('admin.pengujian.edit', $pengujian->id_pengujian) }}" class="btn btn-warning"><i
                class="bi bi-pencil"></i> Edit Data</a>
        <form method="POST" action="{{ route('admin.pengujian.destroy', $pengujian->id_pengujian) }}"
            onsubmit="return confirm('Hapus data ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
        </form>
    </div>
@endsection