@extends('layouts.app')
@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan Harian')
@section('breadcrumb', 'Laporan → Edit')
@section('content')
    <div style="max-width:640px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-pencil-square" style="color:#f59e0b"></i> Edit Laporan
                    {{ $laporan->tanggal->format('d/m/Y') }}</div>
                <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.laporan.update', $laporan->id) }}">
                    @csrf @method('PUT')
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Bandwidth Tersedia (Mbps)</label>
                            <input type="number" name="total_bandwidth_tersedia" step="0.01"
                                value="{{ old('total_bandwidth_tersedia', $laporan->total_bandwidth_tersedia) }}"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bandwidth Terpakai (Mbps)</label>
                            <input type="number" name="total_bandwidth_terpakai" step="0.01"
                                value="{{ old('total_bandwidth_terpakai', $laporan->total_bandwidth_terpakai) }}"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Status Jaringan</label>
                            <select name="status_jaringan" class="form-control">
                                @foreach(['Normal', 'Degraded', 'Down'] as $s)
                                    <option value="{{ $s }}" {{ old('status_jaringan', $laporan->status_jaringan) === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">ISP Aktif</label>
                            <input type="text" name="isp_aktif" value="{{ old('isp_aktif', $laporan->isp_aktif) }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Insiden</label>
                        <textarea name="insiden" class="form-control"
                            rows="3">{{ old('insiden', $laporan->insiden) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tindakan</label>
                        <textarea name="tindakan" class="form-control"
                            rows="3">{{ old('tindakan', $laporan->tindakan) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control"
                            rows="2">{{ old('catatan', $laporan->catatan) }}</textarea>
                    </div>
                    <div class="info-box mb-20" style="font-size:0.8rem;color:#94a3b8">
                        <i class="bi bi-info-circle" style="color:#06b6d4"></i> Grade akan dihitung ulang otomatis saat
                        disimpan. Untuk edit data per VLAN, hapus laporan dan buat ulang.
                    </div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection