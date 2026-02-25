@extends('layouts.app')
@section('title', 'Riwayat Laporan')
@section('page-title', 'Riwayat Laporan Harian')
@section('breadcrumb', 'Laporan')
@section('content')

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal"
        style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);align-items:center;justify-content:center;">
        <div
            style="background:#1e293b;border:1px solid #334155;border-radius:16px;padding:28px;max-width:380px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.5)">
            <div
                style="width:56px;height:56px;background:rgba(239,68,68,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <i class="bi bi-trash" style="font-size:1.5rem;color:#ef4444"></i>
            </div>
            <div style="font-size:1rem;font-weight:700;margin-bottom:8px">Hapus Laporan?</div>
            <div style="color:#94a3b8;font-size:0.84rem;margin-bottom:24px">Laporan yang dihapus tidak dapat dikembalikan.
                Semua data VLAN dan failover terkait juga akan terhapus.</div>
            <div style="display:flex;gap:10px;justify-content:center">
                <button onclick="closeDeleteModal()" class="btn btn-outline" style="min-width:100px">Batal</button>
                <button onclick="submitDeleteForm()" class="btn btn-danger" style="min-width:100px"><i
                        class="bi bi-trash"></i> Ya, Hapus</button>
            </div>
        </div>
    </div>

    <div class="card mb-20">
        <div class="card-body">
            <form method="GET">
                <div class="form-row" style="grid-template-columns:1fr 1fr 1fr 1fr auto;align-items:flex-end;gap:12px">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Grade</label>
                        <select name="grade" class="form-control">
                            <option value="">Semua</option>
                            @foreach(['A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang Baik'] as $g => $l)
                                <option value="{{ $g }}" {{ request('grade') === $g ? 'selected' : '' }}>{{ $g }} - {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="Normal" {{ request('status') === 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Degraded" {{ request('status') === 'Degraded' ? 'selected' : '' }}>Degraded
                            </option>
                            <option value="Down" {{ request('status') === 'Down' ? 'selected' : '' }}>Down</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:6px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-journal-text" style="color:#3b82f6"></i> Daftar Laporan
                ({{ $laporan->total() }} data)</div>
            <a href="{{ route('admin.laporan.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Buat
                Laporan</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>ISP Aktif</th>
                        <th>Bandwidth</th>
                        <th>%</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Failover</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $l)
                        <tr>
                            <td style="font-weight:500">{{ $l->tanggal->format('d/m/Y') }}<br><span class="text-muted"
                                    style="font-size:0.7rem">{{ $l->tanggal->format('l') }}</span></td>
                            <td style="font-size:0.82rem">{{ $l->isp_aktif ?? '-' }}</td>
                            <td>{{ $l->total_bandwidth_terpakai }}/{{ $l->total_bandwidth_tersedia }} <span class="text-muted"
                                    style="font-size:0.7rem">Mbps</span></td>
                            <td
                                style="font-weight:700;color:{{ $l->persentase_bandwidth >= 80 ? '#10b981' : ($l->persentase_bandwidth >= 60 ? '#06b6d4' : ($l->persentase_bandwidth >= 40 ? '#f59e0b' : '#ef4444')) }}">
                                {{ $l->persentase_bandwidth }}%</td>
                            <td><span class="grade-badge grade-{{ $l->grade }}"
                                    style="width:30px;height:30px;font-size:0.9rem">{{ $l->grade }}</span></td>
                            <td><span
                                    class="badge badge-{{ \App\Helpers\GradeHelper::badgeStatus($l->status_jaringan) }}">{{ $l->status_jaringan }}</span>
                            </td>
                            <td>{{ $l->failover->count() > 0 ? $l->failover->count() . ' event' : '-' }}</td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <a href="{{ route('admin.laporan.show', $l->id) }}" class="btn btn-outline btn-sm"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.laporan.edit', $l->id) }}" class="btn btn-warning btn-sm"><i
                                            class="bi bi-pencil"></i></a>
                                    {{-- Form hapus — dipanggil via modal --}}
                                    <form id="delete-form-{{ $l->id }}" method="POST"
                                        action="{{ route('admin.laporan.destroy', $l->id) }}" style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal({{ $l->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#64748b;padding:50px">Belum ada laporan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laporan->hasPages())
            <div style="padding:14px;border-top:1px solid var(--border)">{{ $laporan->links('vendor.pagination.custom') }}</div>
        @endif
    </div>

    @push('scripts')
        <script>
            let currentDeleteId = null;

            function openDeleteModal(id) {
                currentDeleteId = id;
                document.getElementById('deleteModal').style.display = 'flex';
            }

            function closeDeleteModal() {
                currentDeleteId = null;
                document.getElementById('deleteModal').style.display = 'none';
            }

            function submitDeleteForm() {
                if (currentDeleteId) {
                    document.getElementById('delete-form-' + currentDeleteId).submit();
                }
            }

            // Tutup modal kalau klik di luar kotak
            document.getElementById('deleteModal').addEventListener('click', function (e) {
                if (e.target === this) closeDeleteModal();
            });
        </script>
    @endpush
@endsection