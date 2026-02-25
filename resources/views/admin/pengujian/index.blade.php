@extends('layouts.app')

@section('title', 'Data Pengujian')
@section('page-title', 'Data Pengujian Jaringan')
@section('breadcrumb', 'Pengujian')

@section('content')
    {{-- Filter Bar --}}
    <div class="card mb-20">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pengujian.index') }}">
                <div class="form-row cols-3" style="align-items:flex-end;gap:12px;">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Periode</label>
                        <select name="periode" class="form-control">
                            <option value="">Semua Periode</option>
                            <option value="sebelum" {{ request('periode') === 'sebelum' ? 'selected' : '' }}>Sebelum</option>
                            <option value="sesudah" {{ request('periode') === 'sesudah' ? 'selected' : '' }}>Sesudah</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Cari Lokasi</label>
                        <input type="text" name="lokasi" value="{{ request('lokasi') }}" class="form-control"
                            placeholder="Nama lokasi...">
                    </div>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="btn btn-primary" style="flex:1"><i class="bi bi-search"></i>
                            Filter</button>
                        <a href="{{ route('admin.pengujian.index') }}" class="btn btn-outline"><i
                                class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clipboard-data" style="color:#3b82f6"></i> Daftar Pengujian
                ({{ $pengujian->total() }} data)</div>
            <a href="{{ route('admin.pengujian.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Pengujian
            </a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Periode</th>
                        <th>Throughput</th>
                        <th>Delay</th>
                        <th>Jitter</th>
                        <th>Packet Loss</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengujian as $i => $p)
                        <tr>
                            <td class="text-muted">{{ $pengujian->firstItem() + $i }}</td>
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <div style="font-weight:500">{{ $p->lokasi }}</div>
                                <div class="text-muted" style="font-size:0.72rem">oleh {{ $p->user?->nama }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $p->periode === 'sebelum' ? 'badge-warning' : 'badge-success' }}">
                                    {{ ucfirst($p->periode) }}
                                </span>
                            </td>
                            <td>{{ $p->parameterQos?->throughput ?? '-' }} <span class="text-muted"
                                    style="font-size:0.7rem">Kbps</span></td>
                            <td>{{ $p->parameterQos?->delay ?? '-' }} <span class="text-muted"
                                    style="font-size:0.7rem">ms</span></td>
                            <td>{{ $p->parameterQos?->jitter ?? '-' }} <span class="text-muted"
                                    style="font-size:0.7rem">ms</span></td>
                            <td>{{ $p->parameterQos?->packet_loss ?? '-' }}<span class="text-muted"
                                    style="font-size:0.7rem">%</span></td>
                            <td>
                                @if($p->parameterQos)
                                    @php $badge = \App\Helpers\QosHelper::badgeKategori($p->parameterQos->kategori); @endphp
                                    <span class="badge badge-{{ $badge }}">{{ $p->parameterQos->kategori }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:4px;">
                                    <a href="{{ route('admin.pengujian.show', $p->id_pengujian) }}"
                                        class="btn btn-outline btn-sm" title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.pengujian.edit', $p->id_pengujian) }}"
                                        class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.pengujian.destroy', $p->id_pengujian) }}"
                                        onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center;color:#64748b;padding:50px">
                                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                                Belum ada data pengujian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengujian->hasPages())
            <div style="padding:16px;border-top:1px solid var(--border)">
                {{ $pengujian->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
@endsection