@extends('layouts.app')
@section('title', 'Edit VLAN')
@section('page-title', 'Edit VLAN')
@section('breadcrumb', 'VLAN → Edit')
@section('content')
    <div style="max-width:640px">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-pencil" style="color:#f59e0b"></i> Edit VLAN {{ $vlan->vlan_id }} -
                    {{ $vlan->nama }}</div>
                <a href="{{ route('admin.vlan.index') }}" class="btn btn-outline btn-sm"><i
                        class="bi bi-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vlan.update', $vlan->id) }}">
                    @csrf @method('PUT')
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">VLAN ID</label>
                            <input type="number" name="vlan_id" value="{{ old('vlan_id', $vlan->vlan_id) }}"
                                class="form-control {{ $errors->has('vlan_id') ? 'is-invalid' : '' }}">
                            @error('vlan_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama VLAN</label>
                            <input type="text" name="nama" value="{{ old('nama', $vlan->nama) }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Departemen</label>
                            <input type="text" name="departemen" value="{{ old('departemen', $vlan->departemen) }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alokasi Bandwidth (Mbps)</label>
                            <input type="number" name="bandwidth_allocated" step="0.1"
                                value="{{ old('bandwidth_allocated', $vlan->bandwidth_allocated) }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-row cols-2">
                        <div class="form-group">
                            <label class="form-label">Subnet</label>
                            <input type="text" name="subnet" value="{{ old('subnet', $vlan->subnet) }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gateway</label>
                            <input type="text" name="gateway" value="{{ old('gateway', $vlan->gateway) }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control"
                            rows="3">{{ old('deskripsi', $vlan->deskripsi) }}</textarea>
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:10px">
                        <input type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', $vlan->aktif) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#3b82f6">
                        <label for="aktif" style="font-size:0.85rem;cursor:pointer">VLAN ini aktif</label>
                    </div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        <a href="{{ route('admin.vlan.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection