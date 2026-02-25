@extends('layouts.app')
@section('title', 'Edit Log Failover')
@section('page-title', 'Edit Log Failover')
@section('breadcrumb', 'Failover → Edit')
@section('content')
    <div style="max-width:640px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-pencil" style="color:#f59e0b"></i> Edit Log Failover</div>
                <a href="{{ route('admin.failover.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.failover.update', $failover->id) }}">
                    @csrf @method('PUT')
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal"
                                value="{{ old('tanggal', $failover->tanggal->format('Y-m-d')) }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Laporan Terkait</label>
                            <select name="id_laporan" class="form-control">
                                <option value="">-- Opsional --</option>
                                @foreach($laporanHarian as $l)
                                    <option value="{{ $l->id }}" {{ old('id_laporan', $failover->id_laporan) == $l->id ? 'selected' : '' }}>{{ $l->tanggal->format('d/m/Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group"><label class="form-label">Waktu Mulai</label><input type="time"
                                name="waktu_mulai" value="{{ old('waktu_mulai', $failover->waktu_mulai) }}"
                                class="form-control"></div>
                        <div class="form-group"><label class="form-label">Waktu Selesai</label><input type="time"
                                name="waktu_selesai" value="{{ old('waktu_selesai', $failover->waktu_selesai) }}"
                                class="form-control"></div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group"><label class="form-label">Dari Provider</label><input type="text"
                                name="provider_dari" value="{{ old('provider_dari', $failover->provider_dari) }}"
                                class="form-control"></div>
                        <div class="form-group"><label class="form-label">Ke Provider</label><input type="text"
                                name="provider_ke" value="{{ old('provider_ke', $failover->provider_ke) }}"
                                class="form-control"></div>
                    </div>
                    <div class="form-row cols-3">
                        <div class="form-group">
                            <label class="form-label">Penyebab</label>
                            <select name="penyebab" class="form-control">
                                @foreach(['Gangguan ISP', 'Maintenance', 'Manual', 'Lainnya'] as $p)
                                    <option value="{{ $p }}" {{ old('penyebab', $failover->penyebab) === $p ? 'selected' : '' }}>
                                        {{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Durasi (mnt)</label><input type="number"
                                name="durasi_menit" value="{{ old('durasi_menit', $failover->durasi_menit) }}"
                                class="form-control" min="0"></div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="Selesai" {{ old('status', $failover->status) === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Berjalan" {{ old('status', $failover->status) === 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group"><label class="form-label">Keterangan</label><textarea name="keterangan"
                            class="form-control" rows="3">{{ old('keterangan', $failover->keterangan) }}</textarea></div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        <a href="{{ route('admin.failover.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection