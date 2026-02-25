@extends('layouts.app')

@section('title', 'Edit Pengujian')
@section('page-title', 'Edit Data Pengujian')
@section('breadcrumb', 'Pengujian → Edit')

@section('content')
    <div style="max-width:800px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-pencil-square" style="color:#f59e0b"></i> Edit Pengujian
                    #{{ $pengujian->id_pengujian }}</div>
                <a href="{{ route('admin.pengujian.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pengujian.update', $pengujian->id_pengujian) }}">
                    @csrf @method('PUT')

                    <div class="qos-section">
                        <h6><i class="bi bi-info-circle"></i> Informasi Pengujian</h6>
                        <div class="form-row cols-2">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Tanggal Pengujian <span style="color:#ef4444">*</span></label>
                                <input type="date" name="tanggal"
                                    value="{{ old('tanggal', $pengujian->tanggal->format('Y-m-d')) }}"
                                    class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}">
                                @error('tanggal')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Lokasi Pengujian <span style="color:#ef4444">*</span></label>
                                <input type="text" name="lokasi" value="{{ old('lokasi', $pengujian->lokasi) }}"
                                    class="form-control {{ $errors->has('lokasi') ? 'is-invalid' : '' }}">
                                @error('lokasi')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row cols-2" style="margin-top:16px">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Periode <span style="color:#ef4444">*</span></label>
                                <select name="periode"
                                    class="form-control {{ $errors->has('periode') ? 'is-invalid' : '' }}">
                                    <option value="sebelum" {{ old('periode', $pengujian->periode) === 'sebelum' ? 'selected' : '' }}>Sebelum Manajemen Jaringan</option>
                                    <option value="sesudah" {{ old('periode', $pengujian->periode) === 'sesudah' ? 'selected' : '' }}>Sesudah Manajemen Jaringan</option>
                                </select>
                                @error('periode')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" value="{{ old('keterangan', $pengujian->keterangan) }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="qos-section">
                        <h6><i class="bi bi-diagram-3"></i> Parameter QoS</h6>
                        <div class="form-row cols-2">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Throughput (Kbps) <span style="color:#ef4444">*</span></label>
                                <input type="number" name="throughput" step="0.01" min="0"
                                    value="{{ old('throughput', $pengujian->parameterQos?->throughput) }}"
                                    class="form-control {{ $errors->has('throughput') ? 'is-invalid' : '' }}">
                                @error('throughput')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Delay (ms) <span style="color:#ef4444">*</span></label>
                                <input type="number" name="delay" step="0.01" min="0"
                                    value="{{ old('delay', $pengujian->parameterQos?->delay) }}"
                                    class="form-control {{ $errors->has('delay') ? 'is-invalid' : '' }}">
                                @error('delay')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Jitter (ms) <span style="color:#ef4444">*</span></label>
                                <input type="number" name="jitter" step="0.01" min="0"
                                    value="{{ old('jitter', $pengujian->parameterQos?->jitter) }}"
                                    class="form-control {{ $errors->has('jitter') ? 'is-invalid' : '' }}">
                                @error('jitter')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Packet Loss (%) <span style="color:#ef4444">*</span></label>
                                <input type="number" name="packet_loss" step="0.01" min="0" max="100"
                                    value="{{ old('packet_loss', $pengujian->parameterQos?->packet_loss) }}"
                                    class="form-control {{ $errors->has('packet_loss') ? 'is-invalid' : '' }}">
                                @error('packet_loss')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    @if($pengujian->parameterQos)
                        <div class="info-box mb-20">
                            <div style="font-size:0.8rem;color:#06b6d4;font-weight:600;margin-bottom:6px">Kategori Saat Ini
                            </div>
                            @php $badge = \App\Helpers\QosHelper::badgeKategori($pengujian->parameterQos->kategori); @endphp
                            <span class="badge badge-{{ $badge }}">{{ $pengujian->parameterQos->kategori }}</span>
                            <span class="text-muted" style="font-size:0.75rem;margin-left:8px">Akan diperbarui otomatis setelah
                                disimpan</span>
                        </div>
                    @endif

                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                        <a href="{{ route('admin.pengujian.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection