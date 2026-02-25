@extends('layouts.app')
@section('title', 'Buat Laporan Harian')
@section('page-title', 'Buat Laporan Harian')
@section('breadcrumb', 'Laporan → Buat')
@section('content')

    @if($sudahAda)
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> Laporan untuk hari ini sudah ada.
            Silakan pilih tanggal lain atau edit laporan yang sudah ada.</div>
    @endif

    <form method="POST" action="{{ route('admin.laporan.store') }}" id="formLaporan">
        @csrf
        <div class="grid grid-2" style="align-items:start">

            {{-- Kolom Kiri: Informasi Utama --}}
            <div>
                <div class="card mb-20">
                    <div class="card-header">
                        <div class="card-title"><i class="bi bi-info-circle" style="color:#3b82f6"></i> Informasi Laporan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row cols-2">
                            <div class="form-group">
                                <label class="form-label">Tanggal <span style="color:#ef4444">*</span></label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}">
                                @error('tanggal')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Jaringan <span style="color:#ef4444">*</span></label>
                                <select name="status_jaringan" class="form-control">
                                    <option value="Normal" {{ old('status_jaringan') === 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Degraded" {{ old('status_jaringan') === 'Degraded' ? 'selected' : '' }}>
                                        Degraded</option>
                                    <option value="Down" {{ old('status_jaringan') === 'Down' ? 'selected' : '' }}>Down
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row cols-3">
                            <div class="form-group">
                                <label class="form-label">Bandwidth Tersedia (Mbps) <span
                                        style="color:#ef4444">*</span></label>
                                <input type="number" name="total_bandwidth_tersedia" step="0.01" min="0.1"
                                    value="{{ old('total_bandwidth_tersedia') }}"
                                    class="form-control {{ $errors->has('total_bandwidth_tersedia') ? 'is-invalid' : '' }}"
                                    id="totalTersedia">
                                @error('total_bandwidth_tersedia')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bandwidth Terpakai (Mbps) <span
                                        style="color:#ef4444">*</span></label>
                                <input type="number" name="total_bandwidth_terpakai" step="0.01" min="0"
                                    value="{{ old('total_bandwidth_terpakai') }}"
                                    class="form-control {{ $errors->has('total_bandwidth_terpakai') ? 'is-invalid' : '' }}"
                                    id="totalTerpakai" oninput="hitungGrade()">
                                @error('total_bandwidth_terpakai')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">ISP Aktif Hari Ini</label>
                                <input type="text" name="isp_aktif" value="{{ old('isp_aktif') }}" class="form-control"
                                    placeholder="cth: Telkom IndiHome">
                            </div>
                        </div>

                        {{-- Grade Preview --}}
                        <div class="info-box" style="display:flex;align-items:center;gap:16px">
                            <div id="gradePreview" class="grade-badge grade-A">A</div>
                            <div>
                                <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase">Grade Otomatis</div>
                                <div id="gradeLabel" style="font-weight:600">Sangat Baik</div>
                                <div id="persenLabel" style="font-size:0.75rem;color:#94a3b8">- %</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="bi bi-clipboard-text" style="color:#f59e0b"></i> Catatan
                            Operasional</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Insiden / Masalah Hari Ini</label>
                            <textarea name="insiden" class="form-control" rows="3"
                                placeholder="Tuliskan kejadian atau masalah jaringan jika ada...">{{ old('insiden') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tindakan yang Diambil</label>
                            <textarea name="tindakan" class="form-control" rows="3"
                                placeholder="Tuliskan tindakan/solusi yang dilakukan...">{{ old('tindakan') }}</textarea>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="catatan" class="form-control" rows="2"
                                placeholder="Catatan umum lainnya...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Detail VLAN --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-diagram-3" style="color:#06b6d4"></i> Kondisi per VLAN</div>
                    <span class="text-muted" style="font-size:0.75rem">{{ $vlans->count() }} VLAN aktif</span>
                </div>
                <div class="card-body" style="padding:0">
                    @forelse($vlans as $idx => $vlan)
                        <div style="padding:16px 20px;border-bottom:1px solid var(--border)">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                                <div>
                                    <span
                                        style="background:rgba(59,130,246,0.15);color:#60a5fa;padding:2px 8px;border-radius:6px;font-size:0.72rem;font-weight:700">VLAN
                                        {{ $vlan->vlan_id }}</span>
                                    <span style="font-weight:600;margin-left:8px">{{ $vlan->nama }}</span>
                                    <span class="text-muted"
                                        style="font-size:0.74rem;margin-left:4px">{{ $vlan->departemen }}</span>
                                </div>
                                <span class="text-muted" style="font-size:0.75rem">Alokasi: {{ $vlan->bandwidth_allocated }}
                                    Mbps</span>
                            </div>
                            <input type="hidden" name="vlans[{{ $idx }}][id_vlan]" value="{{ $vlan->id }}">
                            <div class="form-row" style="grid-template-columns:1fr 1fr 1fr 1fr;gap:10px">
                                <div>
                                    <label
                                        style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Terpakai
                                        (Mbps)</label>
                                    <input type="number" name="vlans[{{ $idx }}][bandwidth_terpakai]" step="0.01" min="0"
                                        value="{{ old("vlans.$idx.bandwidth_terpakai") }}" class="form-control" required>
                                </div>
                                <div>
                                    <label
                                        style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Packet
                                        Loss (%)</label>
                                    <input type="number" name="vlans[{{ $idx }}][packet_loss]" step="0.01" min="0" max="100"
                                        value="{{ old("vlans.$idx.packet_loss", 0) }}" class="form-control">
                                </div>
                                <div>
                                    <label
                                        style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Delay
                                        (ms)</label>
                                    <input type="number" name="vlans[{{ $idx }}][delay]" step="0.01" min="0"
                                        value="{{ old("vlans.$idx.delay", 0) }}" class="form-control">
                                </div>
                                <div>
                                    <label
                                        style="font-size:0.68rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Status</label>
                                    <select name="vlans[{{ $idx }}][status]" class="form-control">
                                        <option value="UP">UP</option>
                                        <option value="Degraded">Degraded</option>
                                        <option value="DOWN">DOWN</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:30px;text-align:center;color:#64748b">
                            <i class="bi bi-diagram-3" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                            Belum ada VLAN terdaftar. <a href="{{ route('admin.vlan.create') }}" style="color:#60a5fa">Tambah
                                VLAN</a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="mt-20" style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Laporan</button>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function hitungGrade() {
            const tersedia = parseFloat(document.getElementById('totalTersedia').value) || 0;
            const terpakai = parseFloat(document.getElementById('totalTerpakai').value) || 0;
            if (tersedia <= 0) return;
            const pct = Math.round((terpakai / tersedia) * 100 * 100) / 100;
            let grade = 'D', label = 'Kurang Baik', cls = 'grade-D';
            if (pct >= 80) { grade = 'A'; label = 'Sangat Baik'; cls = 'grade-A'; }
            else if (pct >= 60) { grade = 'B'; label = 'Baik'; cls = 'grade-B'; }
            else if (pct >= 40) { grade = 'C'; label = 'Cukup'; cls = 'grade-C'; }
            const el = document.getElementById('gradePreview');
            el.textContent = grade;
            el.className = 'grade-badge ' + cls;
            document.getElementById('gradeLabel').textContent = label;
            document.getElementById('persenLabel').textContent = pct + '%';
        }
        document.getElementById('totalTersedia').addEventListener('input', hitungGrade);
        hitungGrade();
    </script>
@endpush