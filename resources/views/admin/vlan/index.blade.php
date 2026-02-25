@extends('layouts.app')
@section('title', 'Manajemen VLAN')
@section('page-title', 'Manajemen VLAN')
@section('breadcrumb', 'VLAN')
@section('content')

    {{-- Modal Hapus --}}
    <div id="deleteModal"
        style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);align-items:center;justify-content:center;">
        <div
            style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px;max-width:380px;width:90%;text-align:center">
            <div
                style="width:52px;height:52px;background:rgba(239,68,68,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                <i class="bi bi-trash" style="font-size:1.4rem;color:#ef4444"></i>
            </div>
            <div style="font-size:1rem;font-weight:700;margin-bottom:8px">Hapus VLAN?</div>
            <div style="color:#94a3b8;font-size:0.84rem;margin-bottom:22px">VLAN yang dihapus tidak dapat dikembalikan.
            </div>
            <div style="display:flex;gap:10px;justify-content:center">
                <button onclick="closeDeleteModal()" class="btn btn-outline" style="min-width:90px">Batal</button>
                <button onclick="submitDelete()" class="btn btn-danger" style="min-width:90px"><i class="bi bi-trash"></i>
                    Hapus</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-diagram-3" style="color:#06b6d4"></i> Daftar VLAN ({{ $vlans->total() }}
                terdaftar)</div>
            <a href="{{ route('admin.vlan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah
                VLAN</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>VLAN ID</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Alokasi BW</th>
                        <th>Subnet</th>
                        <th>Gateway</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vlans as $v)
                        <tr>
                            <td><span
                                    style="background:rgba(59,130,246,0.15);color:#60a5fa;padding:3px 10px;border-radius:6px;font-weight:700">{{ $v->vlan_id }}</span>
                            </td>
                            <td style="font-weight:600">{{ $v->nama }}</td>
                            <td class="text-muted">{{ $v->departemen ?? '-' }}</td>
                            <td style="font-weight:600;color:#10b981">{{ $v->bandwidth_allocated }} Mbps</td>
                            <td style="font-size:0.78rem;font-family:monospace">{{ $v->subnet ?? '-' }}</td>
                            <td style="font-size:0.78rem;font-family:monospace">{{ $v->gateway ?? '-' }}</td>
                            <td><span
                                    class="badge {{ $v->aktif ? 'badge-success' : 'badge-secondary' }}">{{ $v->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <a href="{{ route('admin.vlan.edit', $v->id) }}" class="btn btn-warning btn-sm"><i
                                            class="bi bi-pencil"></i></a>
                                    <form id="del-{{ $v->id }}" method="POST" action="{{ route('admin.vlan.destroy', $v->id) }}"
                                        style="display:none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="openDeleteModal({{ $v->id }})"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#64748b;padding:50px">Belum ada VLAN. <a
                                    href="{{ route('admin.vlan.create') }}" style="color:#60a5fa">Tambah sekarang</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vlans->hasPages())
            <div style="padding:14px;border-top:1px solid var(--border)">{{ $vlans->links('vendor.pagination.custom') }}</div>
        @endif
    </div>
    @push('scripts')
        <script>
            let currentDeleteId = null;
            function openDeleteModal(id) { currentDeleteId = id; document.getElementById('deleteModal').style.display = 'flex'; }
            function closeDeleteModal() { currentDeleteId = null; document.getElementById('deleteModal').style.display = 'none'; }
            function submitDelete() { if (currentDeleteId) document.getElementById('del-' + currentDeleteId).submit(); }
            document.getElementById('deleteModal').addEventListener('click', function (e) { if (e.target === this) closeDeleteModal(); });
        </script>
    @endpush
@endsection