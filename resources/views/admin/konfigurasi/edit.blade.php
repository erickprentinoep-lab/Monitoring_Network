@extends('layouts.app')
@section('title', 'Edit Konfigurasi')
@section('page-title', 'Edit Konfigurasi Jaringan')
@section('breadcrumb', 'Konfigurasi → Edit')
@section('content')
    <div style="max-width:800px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-gear-wide-connected" style="color:#3b82f6"></i> Edit Konfigurasi
                    Jaringan & Mikrotik</div>
                <a href="{{ route('admin.konfigurasi') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.konfigurasi.update') }}">
                    @csrf @method('PUT')
                    <div class="qos-section">
                        <h6><i class="bi bi-building"></i> Informasi Perusahaan & ISP</h6>
                        <div class="form-group">
                            <label class="form-label">Nama Perusahaan <span style="color:#ef4444">*</span></label>
                            <input type="text" name="nama_perusahaan"
                                value="{{ old('nama_perusahaan', $config->nama_perusahaan) }}" class="form-control"
                                placeholder="cth: PT. Maju Bersama Indonesia">
                            @error('nama_perusahaan')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row cols-2">
                            <div class="form-group">
                                <label class="form-label">ISP Utama</label>
                                <input type="text" name="isp_utama" value="{{ old('isp_utama', $config->isp_utama) }}"
                                    class="form-control" placeholder="cth: Telkom IndiHome">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bandwidth Utama (Mbps)</label>
                                <input type="number" name="bandwidth_utama" step="0.1"
                                    value="{{ old('bandwidth_utama', $config->bandwidth_utama) }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">ISP Backup</label>
                                <input type="text" name="isp_backup" value="{{ old('isp_backup', $config->isp_backup) }}"
                                    class="form-control" placeholder="cth: Biznet Fiber">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bandwidth Backup (Mbps)</label>
                                <input type="number" name="bandwidth_backup" step="0.1"
                                    value="{{ old('bandwidth_backup', $config->bandwidth_backup) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Perangkat Utama</label>
                            <input type="text" name="perangkat_utama"
                                value="{{ old('perangkat_utama', $config->perangkat_utama) }}" class="form-control"
                                placeholder="cth: Mikrotik CCR1036-12G-4S">
                        </div>
                    </div>
                    <div class="qos-section">
                        <h6><i class="bi bi-funnel"></i> Dokumentasi Konfigurasi</h6>
                        <div class="form-group">
                            <label class="form-label">Konfigurasi QoS (Queue, Bandwidth Management)</label>
                            <textarea name="konfigurasi_qos" class="form-control" rows="5"
                                placeholder="Dokumentasikan aturan QoS, Queue Tree, PCQ, dll...">{{ old('konfigurasi_qos', $config->konfigurasi_qos) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfigurasi Failover</label>
                            <textarea name="konfigurasi_failover" class="form-control" rows="5"
                                placeholder="Dokumentasikan setup dual WAN, rekursif routing, kondisi failover...">{{ old('konfigurasi_failover', $config->konfigurasi_failover) }}</textarea>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Konfigurasi VLAN</label>
                            <textarea name="konfigurasi_vlan" class="form-control" rows="5"
                                placeholder="Daftar VLAN, segmentasi jaringan, kebijakan inter-VLAN routing...">{{ old('konfigurasi_vlan', $config->konfigurasi_vlan) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control"
                            rows="3">{{ old('catatan', $config->catatan) }}</textarea>
                    </div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Konfigurasi</button>
                        <a href="{{ route('admin.konfigurasi') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection