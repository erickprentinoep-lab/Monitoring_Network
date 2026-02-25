@extends('layouts.app')
@section('title', 'Tambah VLAN')
@section('page-title', 'Tambah VLAN Baru')
@section('breadcrumb', 'VLAN → Tambah')
@section('content')
    <div style="max-width:640px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-plus-circle" style="color:#06b6d4"></i> Form VLAN Baru</div>
                <a href="{{ route('admin.vlan.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vlan.store') }}">
                    @csrf
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">VLAN ID <span style="color:#ef4444">*</span></label>
                            <input type="number" name="vlan_id" value="{{ old('vlan_id') }}"
                                class="form-control {{ $errors->has('vlan_id') ? 'is-invalid' : '' }}"
                                placeholder="cth: 10, 20, 30">
                            @error('vlan_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama VLAN <span style="color:#ef4444">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                placeholder="cth: Staff, Server, Tamu">
                            @error('nama')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Departemen</label>
                            <input type="text" name="departemen" value="{{ old('departemen') }}" class="form-control"
                                placeholder="cth: IT, HRD, Keuangan">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alokasi Bandwidth (Mbps) <span style="color:#ef4444">*</span></label>
                            <input type="number" name="bandwidth_allocated" step="0.1" min="0.1"
                                value="{{ old('bandwidth_allocated') }}"
                                class="form-control {{ $errors->has('bandwidth_allocated') ? 'is-invalid' : '' }}">
                            @error('bandwidth_allocated')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Subnet</label>
                            <input type="text" name="subnet" value="{{ old('subnet') }}" class="form-control"
                                placeholder="cth: 192.168.10.0/24">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gateway</label>
                            <input type="text" name="gateway" value="{{ old('gateway') }}" class="form-control"
                                placeholder="cth: 192.168.10.1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                            placeholder="Kegunaan VLAN ini...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:10px">
                        <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', '1') ? 'checked' : '' }}
                            style="width:16px;height:16px;accent-color:#3b82f6">
                        <label for="aktif" style="font-size:0.85rem;cursor:pointer">VLAN ini aktif digunakan</label>
                    </div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan VLAN</button>
                        <a href="{{ route('admin.vlan.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection