@extends('layouts.app')
@section('title', 'Log Failover ISP')
@section('page-title', 'Log Failover ISP')
@section('breadcrumb', 'Failover')
@section('content')

    {{-- Modal Hapus --}}
    <div id="deleteModal"
        style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);align-items:center;justify-content:center;">
        <div
            style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px;max-width:380px;width:90%;text-align:center">
            <div
                style="width:52px;height:52px;background:rgba(239,68,68,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                <i class="bi bi-trash" style="font-size:1.4rem;color:#ef4444"></i></div>
            <div style="font-size:1rem;font-weight:700;margin-bottom:8px">Hapus Log Failover?</div>
            <div style="color:#94a3b8;font-size:0.84rem;margin-bottom:22px">Data yang dihapus tidak dapat dikembalikan.
            </div>
            <div style="display:flex;gap:10px;justify-content:center">
                <button onclick="closeModal()" class="btn btn-outline" style="min-width:90px">Batal</button>
                <button onclick="submitDel()" class="btn btn-danger" style="min-width:90px"><i class="bi bi-trash"></i>
                    Hapus</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-arrow-left-right" style="color:#f59e0b"></i> Riwayat Failover ISP
                ({{ $failovers->total() }} event)</div>
            <a href="{{ route('admin.failover.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i>
                Tambah Log</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Penyebab</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failovers as $f)
                        <tr>
                            <td style="font-weight:500">{{ $f->tanggal->format('d/m/Y') }}</td>
                            <td style="font-family:monospace;font-size:0.8rem">{{ $f->waktu_mulai }}<br><span
                                    class="text-muted">→ {{ $f->waktu_selesai ?? 'berjalan' }}</span></td>
                            <td style="color:#f87171">{{ $f->provider_dari }}</td>
                            <td style="color:#34d399">{{ $f->provider_ke }}</td>
                            <td><span class="badge badge-warning">{{ $f->penyebab }}</span></td>
                            <td>{{ $f->durasi_menit ? $f->durasi_menit . ' mnt' : '-' }}</td>
                            <td><span
                                    class="badge {{ $f->status === 'Selesai' ? 'badge-success' : 'badge-warning' }}">{{ $f->status }}</span>
                            </td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <a href="{{ route('admin.failover.edit', $f->id) }}" class="btn btn-warning btn-sm"><i
                                            class="bi bi-pencil"></i></a>
                                    <form id="del-{{ $f->id }}" method="POST"
                                        action="{{ route('admin.failover.destroy', $f->id) }}" style="display:none">@csrf
                                        @method('DELETE')</form>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="openModal({{ $f->id }})"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#64748b;padding:50px">Belum ada log failover</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($failovers->hasPages())
            <div style="padding:14px;border-top:1px solid var(--border)">{{ $failovers->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
    @push('scripts')
        <script>
            let _did = null;
            function openModal(id) { _did = id; document.getElementById('deleteModal').style.display = 'flex'; }
            function closeModal() { _did = null; document.getElementById('deleteModal').style.display = 'none'; }
            function submitDel() { if (_did) document.getElementById('del-' + _did).submit(); }
            document.getElementById('deleteModal').addEventListener('click', function (e) { if (e.target === this) closeModal(); });
        </script>
    @endpush
@endsection