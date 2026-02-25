@extends('layouts.app')
@section('title', 'Dashboard Manager')
@section('page-title', 'Dashboard Monitoring Jaringan')
@section('breadcrumb', 'Dashboard')
@section('content')

    {{-- Status Hari Ini --}}
    <div class="mb-20">
        @if($hariIni)
            <div class="card"
                style="border-left: 4px solid {{ $hariIni->status_jaringan === 'Normal' ? '#10b981' : ($hariIni->status_jaringan === 'Degraded' ? '#f59e0b' : '#ef4444') }}">
                <div class="card-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
                    <div class="grade-badge grade-{{ $hariIni->grade }}">{{ $hariIni->grade }}</div>
                    <div>
                        <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:1px">Status Jaringan
                            Hari Ini</div>
                        <div style="font-size:1.1rem;font-weight:700;margin-top:2px">
                            {{ $hariIni->tanggal->format('l, d F Y') }}
                            <span class="badge badge-{{ \App\Helpers\GradeHelper::badgeStatus($hariIni->status_jaringan) }}"
                                style="margin-left:8px">{{ $hariIni->status_jaringan }}</span>
                        </div>
                        <div style="font-size:0.8rem;color:#94a3b8;margin-top:2px">ISP Aktif: <strong
                                style="color:#e2e8f0">{{ $hariIni->isp_aktif ?? '-' }}</strong> · Grade: <strong
                                style="color:#e2e8f0">{{ $hariIni->label_grade }}</strong></div>
                    </div>
                    <div style="margin-left:auto;text-align:right">
                        <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase">Bandwidth Terpakai</div>
                        <div style="font-size:1.5rem;font-weight:800;color:#3b82f6">{{ $hariIni->persentase_bandwidth }}%</div>
                        <div style="font-size:0.75rem;color:#94a3b8">
                            {{ $hariIni->total_bandwidth_terpakai }}/{{ $hariIni->total_bandwidth_tersedia }} Mbps</div>
                    </div>
                    <a href="{{ route('manager.laporan.show', $hariIni->id) }}" class="btn btn-outline btn-sm"><i
                            class="bi bi-eye"></i> Detail</a>
                </div>
            </div>
        @else
            <div class="card" style="border-left:4px solid #64748b">
                <div class="card-body">
                    <div style="color:#64748b;font-size:0.9rem"><i class="bi bi-clock"></i> Menunggu admin mengisi laporan
                        harian hari ini...</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-4 mb-20">
        <div class="stat-card" style="border-left:3px solid #3b82f6">
            <div class="icon-bg" style="background:rgba(59,130,246,0.15)"><i class="bi bi-journal-text"
                    style="color:#3b82f6"></i></div>
            <div class="stat-label">Total Laporan</div>
            <div class="stat-value">{{ $totalLaporan }}</div>
            <div class="stat-sub">laporan tersimpan</div>
        </div>
        <div class="stat-card" style="border-left:3px solid #10b981">
            <div class="icon-bg" style="background:rgba(16,185,129,0.15)"><i class="bi bi-speedometer2"
                    style="color:#10b981"></i></div>
            <div class="stat-label">Rata Bandwidth 14 Hari</div>
            <div class="stat-value">{{ round($avgBandwidth ?? 0, 1) }}%</div>
            <div class="stat-sub">rata-rata utilisasi</div>
        </div>
        <div class="stat-card" style="border-left:3px solid #f59e0b">
            <div class="icon-bg" style="background:rgba(245,158,11,0.15)"><i class="bi bi-arrow-left-right"
                    style="color:#f59e0b"></i></div>
            <div class="stat-label">Total Failover</div>
            <div class="stat-value">{{ $totalFailover }}</div>
            <div class="stat-sub">perpindahan ISP</div>
        </div>
        <div class="stat-card" style="border-left:3px solid #06b6d4">
            <div class="icon-bg" style="background:rgba(6,182,212,0.15)"><i class="bi bi-diagram-3"
                    style="color:#06b6d4"></i></div>
            <div class="stat-label">VLAN Aktif</div>
            <div class="stat-value">{{ $vlanAktif }}</div>
            <div class="stat-sub">VLAN terdaftar</div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-2 mb-20">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-graph-up" style="color:#3b82f6"></i> Tren Utilisasi Bandwidth (14
                    Hari)</div>
            </div>
            <div class="card-body"><canvas id="lineChart" height="160"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-pie-chart" style="color:#06b6d4"></i> Distribusi Grade Kualitas
                </div>
            </div>
            <div class="card-body" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                <canvas id="doughnutChart" width="160" height="160" style="max-width:160px;max-height:160px"></canvas>
                <div>
                    @foreach(['A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang Baik'] as $g => $label)
                        @php $d = $distribusiGrade[$g] ?? null; @endphp
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                            <span class="grade-badge grade-{{ $g }}"
                                style="width:32px;height:32px;font-size:0.9rem">{{ $g }}</span>
                            <span style="font-size:0.8rem">{{ $label }}</span>
                            <span class="text-muted" style="font-size:0.75rem">{{ $d ? $d->total : 0 }} hari</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Laporan Terbaru --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Laporan 7 Hari Terakhir</div>
            <a href="{{ route('manager.laporan') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLaporan as $l)
                        <tr>
                            <td style="font-weight:500">{{ $l->tanggal->format('d/m/Y') }}</td>
                            <td class="text-muted" style="font-size:0.82rem">{{ $l->isp_aktif ?? '-' }}</td>
                            <td>{{ $l->total_bandwidth_terpakai }}/{{ $l->total_bandwidth_tersedia }} <span class="text-muted"
                                    style="font-size:0.7rem">Mbps</span></td>
                            <td
                                style="font-weight:700;color:{{ $l->persentase_bandwidth >= 80 ? '#10b981' : ($l->persentase_bandwidth >= 60 ? '#06b6d4' : ($l->persentase_bandwidth >= 40 ? '#f59e0b' : '#ef4444')) }}">
                                {{ $l->persentase_bandwidth }}%</td>
                            <td><span class="grade-badge grade-{{ $l->grade }}"
                                    style="width:28px;height:28px;font-size:0.85rem">{{ $l->grade }}</span></td>
                            <td><span
                                    class="badge badge-{{ \App\Helpers\GradeHelper::badgeStatus($l->status_jaringan) }}">{{ $l->status_jaringan }}</span>
                            </td>
                            <td><a href="{{ route('manager.laporan.show', $l->id) }}" class="btn btn-outline btn-sm"><i
                                        class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:#64748b;padding:30px">Belum ada laporan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const trenLabels = {!! json_encode($tren->map(fn($t) => $t->tanggal->format('d/m'))->toArray()) !!};
        const trenData = {!! json_encode($tren->map(fn($t) => $t->persentase_bandwidth)->toArray()) !!};
        const colors = trenData.map(v => v >= 80 ? '#10b981' : v >= 60 ? '#06b6d4' : v >= 40 ? '#f59e0b' : '#ef4444');
        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: { labels: trenLabels, datasets: [{ label: 'Utilisasi Bandwidth (%)', data: trenData, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.08)', fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: colors, pointBorderColor: colors }] },
            options: { responsive: true, plugins: { legend: { labels: { color: '#94a3b8' } } }, scales: { x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(51,65,85,0.5)' } }, y: { min: 0, max: 100, ticks: { color: '#94a3b8', callback: v => v + '%' }, grid: { color: 'rgba(51,65,85,0.5)' } } } }
        });
        new Chart(document.getElementById('doughnutChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: ['A', 'B', 'C', 'D'], datasets: [{ data: [{{ $distribusiGrade['A']->total ?? 0 }},{{ $distribusiGrade['B']->total ?? 0 }},{{ $distribusiGrade['C']->total ?? 0 }},{{ $distribusiGrade['D']->total ?? 0 }}], backgroundColor: ['#10b981', '#06b6d4', '#f59e0b', '#ef4444'], borderWidth: 0 }] },
            options: { responsive: false, plugins: { legend: { display: false } } }
        });
    </script>
@endpush