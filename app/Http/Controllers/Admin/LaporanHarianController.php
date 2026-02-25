<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\DetailVlanLaporan;
use App\Models\Vlan;
use App\Helpers\GradeHelper;
use Illuminate\Http\Request;

class LaporanHarianController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanHarian::with('user')->orderBy('tanggal', 'desc');
        if ($request->tanggal_dari)
            $query->where('tanggal', '>=', $request->tanggal_dari);
        if ($request->tanggal_sampai)
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        if ($request->grade)
            $query->where('grade', $request->grade);
        if ($request->status)
            $query->where('status_jaringan', $request->status);
        $laporan = $query->paginate(15);
        return view('admin.laporan.index', compact('laporan'));
    }

    public function create()
    {
        $vlans = Vlan::where('aktif', true)->orderBy('vlan_id')->get();
        $sudahAda = LaporanHarian::whereDate('tanggal', today())->exists();
        return view('admin.laporan.create', compact('vlans', 'sudahAda'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|unique:laporan_harian,tanggal',
            'total_bandwidth_tersedia' => 'required|numeric|min:0.1',
            'total_bandwidth_terpakai' => 'required|numeric|min:0',
            'status_jaringan' => 'required|in:Normal,Degraded,Down',
            'isp_aktif' => 'nullable|string|max:100',
            'insiden' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'vlans' => 'nullable|array',
            'vlans.*.id_vlan' => 'required|exists:vlans,id',
            'vlans.*.bandwidth_terpakai' => 'required|numeric|min:0',
            'vlans.*.packet_loss' => 'nullable|numeric|min:0|max:100',
            'vlans.*.delay' => 'nullable|numeric|min:0',
            'vlans.*.jitter' => 'nullable|numeric|min:0',
            'vlans.*.status' => 'required|in:UP,DOWN,Degraded',
            'vlans.*.catatan' => 'nullable|string',
        ]);

        $persen = GradeHelper::hitungPersentase(
            $validated['total_bandwidth_tersedia'],
            $validated['total_bandwidth_terpakai']
        );
        $grade = GradeHelper::hitungGrade(
            $validated['total_bandwidth_tersedia'],
            $validated['total_bandwidth_terpakai']
        );

        $laporan = LaporanHarian::create([
            'tanggal' => $validated['tanggal'],
            'total_bandwidth_tersedia' => $validated['total_bandwidth_tersedia'],
            'total_bandwidth_terpakai' => $validated['total_bandwidth_terpakai'],
            'persentase_bandwidth' => $persen,
            'grade' => $grade,
            'status_jaringan' => $validated['status_jaringan'],
            'isp_aktif' => $validated['isp_aktif'] ?? null,
            'insiden' => $validated['insiden'] ?? null,
            'tindakan' => $validated['tindakan'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'id_user' => auth()->id(),
        ]);

        if (!empty($validated['vlans'])) {
            foreach ($validated['vlans'] as $v) {
                $vlan = Vlan::find($v['id_vlan']);
                $persenVlan = $vlan ? GradeHelper::hitungPersentase($vlan->bandwidth_allocated, $v['bandwidth_terpakai']) : 0;
                DetailVlanLaporan::create([
                    'id_laporan' => $laporan->id,
                    'id_vlan' => $v['id_vlan'],
                    'bandwidth_terpakai' => $v['bandwidth_terpakai'],
                    'persentase' => $persenVlan,
                    'packet_loss' => $v['packet_loss'] ?? 0,
                    'delay' => $v['delay'] ?? 0,
                    'jitter' => $v['jitter'] ?? 0,
                    'status' => $v['status'],
                    'catatan' => $v['catatan'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.laporan.show', $laporan->id)->with('success', 'Laporan harian berhasil disimpan.');
    }

    public function show(LaporanHarian $laporan)
    {
        $laporan->load(['user', 'detailVlan.vlan', 'failover.user']);
        return view('admin.laporan.show', compact('laporan'));
    }

    public function edit(LaporanHarian $laporan)
    {
        $vlans = Vlan::where('aktif', true)->orderBy('vlan_id')->get();
        $laporan->load('detailVlan.vlan');
        return view('admin.laporan.edit', compact('laporan', 'vlans'));
    }

    public function update(Request $request, LaporanHarian $laporan)
    {
        $validated = $request->validate([
            'total_bandwidth_tersedia' => 'required|numeric|min:0.1',
            'total_bandwidth_terpakai' => 'required|numeric|min:0',
            'status_jaringan' => 'required|in:Normal,Degraded,Down',
            'isp_aktif' => 'nullable|string|max:100',
            'insiden' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $laporan->update(array_merge($validated, [
            'persentase_bandwidth' => GradeHelper::hitungPersentase($validated['total_bandwidth_tersedia'], $validated['total_bandwidth_terpakai']),
            'grade' => GradeHelper::hitungGrade($validated['total_bandwidth_tersedia'], $validated['total_bandwidth_terpakai']),
        ]));

        return redirect()->route('admin.laporan.show', $laporan->id)->with('success', 'Laporan diperbarui.');
    }

    public function destroy(LaporanHarian $laporan)
    {
        $laporan->delete();
        return redirect()->route('admin.laporan.index')->with('success', 'Laporan dihapus.');
    }
}
