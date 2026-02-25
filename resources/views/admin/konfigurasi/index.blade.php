@extends('layouts.app')
@section('title', 'Konfigurasi Jaringan')
@section('page-title', 'Konfigurasi Jaringan')
@section('breadcrumb', 'Konfigurasi')
@section('content')

    @if($config)
        <div class="grid grid-2 mb-20">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-building" style="color:#3b82f6"></i> Informasi Perusahaan & ISP
                    </div>
                    <a href="{{ route('admin.konfigurasi.edit') }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i>
                        Edit</a>
                </div>
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;padding:7px 0;width:40%">Perusahaan</td>
                            <td style="font-weight:600">{{ $config->nama_perusahaan }}</td>
                        </tr>
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;padding:7px 0">ISP Utama</td>
                            <td>{{ $config->isp_utama ?? '-' }} <span class="text-muted"
                                    style="font-size:0.75rem">({{ $config->bandwidth_utama }} Mbps)</span></td>
                        </tr>
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;padding:7px 0">ISP Backup</td>
                            <td>{{ $config->isp_backup ?? '-' }} <span class="text-muted"
                                    style="font-size:0.75rem">({{ $config->bandwidth_backup }} Mbps)</span></td>
                        </tr>
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;padding:7px 0">Perangkat Utama</td>
                            <td>{{ $config->perangkat_utama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;padding:7px 0">Terakhir Diupdate</td>
                            <td style="font-size:0.8rem">{{ $config->updated_at->format('d/m/Y H:i') }} oleh
                                {{ $config->updatedBy?->nama }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-layers" style="color:#10b981"></i> Catatan Konfigurasi VLAN</div>
                </div>
                <div class="card-body">
                    <pre
                        style="font-size:0.8rem;color:#94a3b8;white-space:pre-wrap;line-height:1.6">{{ $config->konfigurasi_vlan ?? 'Belum diisi' }}</pre>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-funnel" style="color:#06b6d4"></i> Konfigurasi QoS</div>
                </div>
                <div class="card-body">
                    <pre
                        style="font-size:0.8rem;color:#94a3b8;white-space:pre-wrap;line-height:1.6">{{ $config->konfigurasi_qos ?? 'Belum diisi' }}</pre>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-arrow-left-right" style="color:#f59e0b"></i> Konfigurasi Failover
                    </div>
                </div>
                <div class="card-body">
                    <pre
                        style="font-size:0.8rem;color:#94a3b8;white-space:pre-wrap;line-height:1.6">{{ $config->konfigurasi_failover ?? 'Belum diisi' }}</pre>
                </div>
            </div>
        </div>
        @if($config->catatan)
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Catatan Tambahan</div>
                </div>
                <div class="card-body">
                    <p style="color:#94a3b8;font-size:0.85rem">{{ $config->catatan }}</p>
                </div>
            </div>
        @endif
    @else
        <div class="card">
            <div class="card-body" style="text-align:center;padding:60px">
                <i class="bi bi-gear-wide-connected" style="font-size:3rem;color:#334155;display:block;margin-bottom:12px"></i>
                <div style="font-size:1rem;font-weight:600;margin-bottom:8px">Belum ada konfigurasi jaringan</div>
                <a href="{{ route('admin.konfigurasi.edit') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Isi
                    Konfigurasi Sekarang</a>
            </div>
        </div>
    @endif
@endsection