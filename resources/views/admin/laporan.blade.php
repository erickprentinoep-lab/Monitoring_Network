@extends('layouts.app')

@section('title', 'Laporan & Analisis')
@section('page-title', 'Laporan & Analisis Performa Jaringan')
@section('breadcrumb', 'Laporan')

@section('content')

    {{-- Filter --}}
    <div class="card mb-20">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan') }}">
                <div class="form-row" style="grid-template-columns:1fr 1fr 1fr auto;align-items:flex-end;gap:12px">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Periode</label>
                        <select name="periode" class="form-control">
                            <option value="">Semua</option>
                            <option value="sebelum" {{ request('periode') === 'sebelum' ? 'selected' : '' }}>Sebelum</option>
                            <option value="sesudah" {{ request('periode') === 'sesudah' ? 'selected' : '' }}>Sesudah</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="form-control">
                    </div>
                    <div style="display:flex;gap:6px">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                        <a href="{{ route('admin.laporan') }}" class="btn btn-outline"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delta Summary --}}
    @if($delta)
        <div class="card mb-20" style="border-color:rgba(16,185,129,0.3)">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-graph-up-arrow" style="color:#10b981"></i> Ringkasan Peningkatan
                    Performa</div>
            </div>
            <div class="card-body">
                <div class="grid grid-4">
                    <div style="text-align:center">
                        <div class="text-muted" style="font-size:0.75rem;margin-bottom:6px">Throughput</div>
                        <span class="delta-badge {{ $delta['throughput'] >= 0 ? 'delta-positive' : 'delta-negative' }}">
                            <i class="bi bi-arrow-{{ $delta['throughput'] >= 0 ? 'up' : 'down' }}-right"></i>
                            {{ abs($delta['throughput']) }}%
                        </span>
                        <div class="text-muted" style="font-size:0.7rem;margin-top:4px">
                            {{ $delta['throughput'] >= 0 ? 'Meningkat' : 'Menurun' }}</div>
                    </div>
                    <div style="text-align:center">
                        <div class="text-muted" style="font-size:0.75rem;margin-bottom:6px">Delay</div>
                        <span class="delta-badge {{ $delta['delay'] >= 0 ? 'delta-positive' : 'delta-negative' }}">
                            <i class="bi bi-arrow-{{ $delta['delay'] >= 0 ? 'down' : 'up' }}-right"></i>
                            {{ abs($delta['delay']) }}%
                        </span>
                        <div class="text-muted" style="font-size:0.7rem;margin-top:4px">
                            {{ $delta['delay'] >= 0 ? 'Berkurang ✓' : 'Bertambah' }}</div>
                    </div>
                    <div style="text-align:center">
                        <div class="text-muted" style="font-size:0.75rem;margin-bottom:6px">Jitter</div>
                        <span class="delta-badge {{ $delta['jitter'] >= 0 ? 'delta-positive' : 'delta-negative' }}">
                            <i class="bi bi-arrow-{{ $delta['jitter'] >= 0 ? 'down' : 'up' }}-right"></i>
                            {{ abs($delta['jitter']) }}%
                        </span>
                        <div class="text-muted" style="font-size:0.7rem;margin-top:4px">
                            {{ $delta['jitter'] >= 0 ? 'Berkurang ✓' : 'Bertambah' }}</div>
                    </div>
                    <div style="text-align:center">
                        <div class="text-muted" style="font-size:0.75rem;margin-bottom:6px">Packet Loss</div>
                        <span class="delta-badge {{ $delta['packet_loss'] >= 0 ? 'delta-positive' : 'delta-negative' }}">
                            <i class="bi bi-arrow-{{ $delta['packet_loss'] >= 0 ? 'down' : 'up' }}-right"></i>
                            {{ abs($delta['packet_loss']) }}%
                        </span>
                        <div class="text-muted" style="font-size:0.7rem;margin-top:4px">
                            {{ $delta['packet_loss'] >= 0 ? 'Berkurang ✓' : 'Bertambah' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Charts --}}
    <div class="grid grid-2 mb-20">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-bar-chart" style="color:#3b82f6"></i> Perbandingan Rata-rata QoS
                </div>
            </div>
            <div class="card-body">
                <canvas id="barChart" height="220"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-pie-chart" style="color:#06b6d4"></i> Distribusi Kategori Kualitas
                </div>
            </div>
            <div class="card-body" style="display:flex;align-items:center;gap:20px">
                <canvas id="doughnutChart" width="180" height="180" style="max-width:180px;max-height:180px;"></canvas>
                <div>
                    @foreach($distribusiKategori as $d)
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                            @php $badge = \App\Helpers\QosHelper::badgeKategori($d->kategori); @endphp
                            <span class="badge badge-{{ $badge }}">{{ $d->kategori }}</span>
                            <span class="text-muted" style="font-size:0.8rem">{{ $d->total }} data
                                ({{ $pengujian->count() > 0 ? round($d->total / $pengujian->count() * 100) : 0 }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-table" style="color:#94a3b8"></i> Tabel Data Laporan
                ({{ $pengujian->count() }} data)</div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Periode</th>
                        <th>Throughput (Kbps)</th>
                        <th>Delay (ms)</th>
                        <th>Jitter (ms)</th>
                        <th>Packet Loss (%)</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengujian as $i => $p)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $p->lokasi }}</td>
                            <td>
                                <span class="badge {{ $p->periode === 'sebelum' ? 'badge-warning' : 'badge-success' }}">
                                    {{ ucfirst($p->periode) }}
                                </span>
                            </td>
                            <td>{{ $p->parameterQos?->throughput ?? '-' }}</td>
                            <td>{{ $p->parameterQos?->delay ?? '-' }}</td>
                            <td>{{ $p->parameterQos?->jitter ?? '-' }}</td>
                            <td>{{ $p->parameterQos?->packet_loss ?? '-' }}</td>
                            <td>
                                @if($p->parameterQos)
                                    @php $badge = \App\Helpers\QosHelper::badgeKategori($p->parameterQos->kategori); @endphp
                                    <span class="badge badge-{{ $badge }}">{{ $p->parameterQos->kategori }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;color:#64748b;padding:40px">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pengujian->count() > 0)
                    <tfoot>
                        <tr style="background:rgba(255,255,255,0.03)">
                            <td colspan="4" style="padding:10px 14px;font-weight:600;color:#94a3b8;font-size:0.8rem">RATA-RATA
                            </td>
                            <td style="padding:10px 14px;font-weight:700;color:#3b82f6">
                                {{ round($pengujian->avg(fn($p) => $p->parameterQos?->throughput), 2) }}</td>
                            <td style="padding:10px 14px;font-weight:700;color:#f59e0b">
                                {{ round($pengujian->avg(fn($p) => $p->parameterQos?->delay), 2) }}</td>
                            <td style="padding:10px 14px;font-weight:700;color:#06b6d4">
                                {{ round($pengujian->avg(fn($p) => $p->parameterQos?->jitter), 2) }}</td>
                            <td style="padding:10px 14px;font-weight:700;color:#ef4444">
                                {{ round($pengujian->avg(fn($p) => $p->parameterQos?->packet_loss), 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Throughput (Kbps)', 'Delay (ms)', 'Jitter (ms)', 'Packet Loss (%)'],
                datasets: [
                    {
                        label: 'Sebelum',
                        data: [{{ $sebelum?->throughput ?? 0 }}, {{ $sebelum?->delay ?? 0 }}, {{ $sebelum?->jitter ?? 0 }}, {{ $sebelum?->packet_loss ?? 0 }}],
                        backgroundColor: 'rgba(245,158,11,0.7)', borderColor: '#f59e0b', borderWidth: 2, borderRadius: 6
                    },
                    {
                        label: 'Sesudah',
                        data: [{{ $sesudah?->throughput ?? 0 }}, {{ $sesudah?->delay ?? 0 }}, {{ $sesudah?->jitter ?? 0 }}, {{ $sesudah?->packet_loss ?? 0 }}],
                        backgroundColor: 'rgba(16,185,129,0.7)', borderColor: '#10b981', borderWidth: 2, borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: { legend: { labels: { color: '#94a3b8' } } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(51,65,85,0.5)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(51,65,85,0.5)' } }
                }
            }
        });
        const doughCtx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($distribusiKategori->pluck('kategori')) !!},
                datasets: [{ data: {!! json_encode($distribusiKategori->pluck('total')) !!}, backgroundColor: ['#10b981', '#06b6d4', '#f59e0b', '#ef4444', '#94a3b8'], borderWidth: 0 }]
            },
            options: { responsive: false, plugins: { legend: { display: false } } }
        });
    </script>
@endpush