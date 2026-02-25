@extends('layouts.app')

@section('title', 'Tambah Pengujian')
@section('page-title', 'Tambah Data Pengujian')
@section('breadcrumb', 'Pengujian → Tambah')

@section('content')
    <div style="max-width:800px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-plus-circle" style="color:#3b82f6"></i> Form Input Pengujian
                    Jaringan</div>
                <a href="{{ route('admin.pengujian.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pengujian.store') }}">
                    @csrf

                    {{-- Info Umum --}}
                    <div class="qos-section">
                        <h6><i class="bi bi-info-circle"></i> Informasi Pengujian</h6>
                        <div class="form-row cols-2">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Tanggal Pengujian <span style="color:#ef4444">*</span></label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}">
                                @error('tanggal')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Lokasi Pengujian <span style="color:#ef4444">*</span></label>
                                <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                                    class="form-control {{ $errors->has('lokasi') ? 'is-invalid' : '' }}"
                                    placeholder="cth: Gedung A - Lantai 2">
                                @error('lokasi')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row cols-2" style="margin-top:16px">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Periode <span style="color:#ef4444">*</span></label>
                                <select name="periode"
                                    class="form-control {{ $errors->has('periode') ? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih Periode --</option>
                                    <option value="sebelum" {{ old('periode') === 'sebelum' ? 'selected' : '' }}>Sebelum
                                        Manajemen Jaringan</option>
                                    <option value="sesudah" {{ old('periode') === 'sesudah' ? 'selected' : '' }}>Sesudah
                                        Manajemen Jaringan</option>
                                </select>
                                @error('periode')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">Keterangan <span class="text-muted">(opsional)</span></label>
                                <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control"
                                    placeholder="Catatan tambahan...">
                            </div>
                        </div>
                    </div>

                    {{-- Parameter QoS --}}
                    <div class="qos-section">
                        <h6><i class="bi bi-diagram-3"></i> Parameter QoS (Quality of Service)</h6>
                        <div class="form-row cols-2">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">
                                    Throughput <span style="color:#ef4444">*</span>
                                    <span class="text-muted" style="font-weight:400">(Kbps)</span>
                                </label>
                                <input type="number" name="throughput" value="{{ old('throughput') }}" step="0.01" min="0"
                                    class="form-control {{ $errors->has('throughput') ? 'is-invalid' : '' }}"
                                    placeholder="cth: 1024.00">
                                @error('throughput')<div class="error-msg">{{ $message }}</div>@enderror
                                <div class="text-muted" style="font-size:0.7rem;margin-top:4px">Sangat Baik ≥ 1000 Kbps ·
                                    Buruk &lt; 256 Kbps</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">
                                    Delay <span style="color:#ef4444">*</span>
                                    <span class="text-muted" style="font-weight:400">(ms)</span>
                                </label>
                                <input type="number" name="delay" value="{{ old('delay') }}" step="0.01" min="0"
                                    class="form-control {{ $errors->has('delay') ? 'is-invalid' : '' }}"
                                    placeholder="cth: 50.00">
                                @error('delay')<div class="error-msg">{{ $message }}</div>@enderror
                                <div class="text-muted" style="font-size:0.7rem;margin-top:4px">Sangat Baik &lt; 150ms ·
                                    Buruk &gt; 450ms</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">
                                    Jitter <span style="color:#ef4444">*</span>
                                    <span class="text-muted" style="font-weight:400">(ms)</span>
                                </label>
                                <input type="number" name="jitter" value="{{ old('jitter') }}" step="0.01" min="0"
                                    class="form-control {{ $errors->has('jitter') ? 'is-invalid' : '' }}"
                                    placeholder="cth: 10.00">
                                @error('jitter')<div class="error-msg">{{ $message }}</div>@enderror
                                <div class="text-muted" style="font-size:0.7rem;margin-top:4px">Sangat Baik &lt; 20ms ·
                                    Buruk &gt; 75ms</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label">
                                    Packet Loss <span style="color:#ef4444">*</span>
                                    <span class="text-muted" style="font-weight:400">(%)</span>
                                </label>
                                <input type="number" name="packet_loss" value="{{ old('packet_loss') }}" step="0.01" min="0"
                                    max="100" class="form-control {{ $errors->has('packet_loss') ? 'is-invalid' : '' }}"
                                    placeholder="cth: 0.50">
                                @error('packet_loss')<div class="error-msg">{{ $message }}</div>@enderror
                                <div class="text-muted" style="font-size:0.7rem;margin-top:4px">Sangat Baik 0–1% · Buruk
                                    &gt; 25%</div>
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan Standar QoS --}}
                    <div class="info-box mb-20">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                            <i class="bi bi-info-circle" style="color:#06b6d4"></i>
                            <span style="font-size:0.8rem;font-weight:600;color:#06b6d4">Standar Kategori TIPHON</span>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;font-size:0.75rem;color:#94a3b8">
                            <span><span class="badge badge-success">Sangat Baik</span> Skor ≥ 3.5</span>
                            <span><span class="badge badge-info">Baik</span> Skor 2.5–3.5</span>
                            <span><span class="badge badge-warning">Sedang</span> Skor 1.5–2.5</span>
                            <span><span class="badge badge-danger">Buruk</span> Skor &lt; 1.5</span>
                        </div>
                        <div style="margin-top:8px;font-size:0.72rem;color:#64748b">
                            Kategori dihitung otomatis oleh sistem berdasarkan nilai QoS yang diinput.
                        </div>
                    </div>

                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Data Pengujian
                        </button>
                        <a href="{{ route('admin.pengujian.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection