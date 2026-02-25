@extends('layouts.app')
@section('title', 'Tambah Log Failover')
@section('page-title', 'Tambah Log Failover ISP')
@section('breadcrumb', 'Failover → Tambah')
@section('content')
    <div style="max-width:640px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-plus-circle" style="color:#f59e0b"></i> Form Log Perpindahan ISP
                </div>
                <a href="{{ route('admin.failover.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.failover.store') }}">
                    @csrf
                    <div class="qos-section">
                        <h6><i class="bi bi-info-circle"></i> Informasi Failover</h6>
                        <div class="form-row cols-2">
                            <div class="form-group">
                                <label class="form-label">Tanggal <span style="color:#ef4444">*</span></label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Laporan Harian Terkait</label>
                                <select name="id_laporan" class="form-control">
                                    <option value="">-- Opsional --</option>
                                    @foreach($laporanHarian as $l)
                                        <option value="{{ $l->id }}" {{ old('id_laporan') == $l->id ? 'selected' : '' }}>
                                            {{ $l->tanggal->format('d/m/Y') }} ({{ $l->status_jaringan }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row cols-2">
                            <div class="form-group">
                                <label class="form-label">Waktu Mulai <span style="color:#ef4444">*</span></label>
                                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="qos-section">
                        <h6><i class="bi bi-arrow-left-right"></i> Detail Perpindahan</h6>
                        <div class="form-row cols-2">
                            <div class="form-group">
                                <label class="form-label">Provider Asal (Dari) <span style="color:#ef4444">*</span></label>
                                <input type="text" name="provider_dari" value="{{ old('provider_dari') }}"
                                    class="form-control" placeholder="cth: Telkom IndiHome">
                                @error('provider_dari')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Provider Tujuan (Ke) <span style="color:#ef4444">*</span></label>
                                <input type="text" name="provider_ke" value="{{ old('provider_ke') }}" class="form-control"
                                    placeholder="cth: Biznet Fiber">
                                @error('provider_ke')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row cols-3">
                            <div class="form-group">
                                <label class="form-label">Penyebab <span style="color:#ef4444">*</span></label>
                                <select name="penyebab" class="form-control">
                                    @foreach(['Gangguan ISP', 'Maintenance', 'Manual', 'Lainnya'] as $p)
                                        <option value="{{ $p }}" {{ old('penyebab') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Durasi (menit)</label>
                                <input type="number" name="durasi_menit" value="{{ old('durasi_menit') }}"
                                    class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                                <select name="status" class="form-control">
                                    <option value="Selesai" {{ old('status') !== 'Berjalan' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="Berjalan" {{ old('status') === 'Berjalan' ? 'selected' : '' }}>Masih
                                        Berjalan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                placeholder="Detail kejadian, tindakan yang dilakukan, dampak pada user...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Log
                            Failover</button>
                        <a href="{{ route('admin.failover.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection