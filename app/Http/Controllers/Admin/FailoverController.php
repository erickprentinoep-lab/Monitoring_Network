<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LaporanFailover;
use App\Models\LaporanHarian;
use Illuminate\Http\Request;

class FailoverController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanFailover::with(['user', 'laporan'])->orderBy('tanggal', 'desc')->orderBy('waktu_mulai', 'desc');
        if ($request->tanggal_dari)
            $query->where('tanggal', '>=', $request->tanggal_dari);
        if ($request->tanggal_sampai)
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        $failovers = $query->paginate(15);
        return view('admin.failover.index', compact('failovers'));
    }

    public function create()
    {
        $laporanHarian = LaporanHarian::orderBy('tanggal', 'desc')->take(30)->get();
        return view('admin.failover.create', compact('laporanHarian'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_laporan' => 'nullable|exists:laporan_harian,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'provider_dari' => 'required|string|max:100',
            'provider_ke' => 'required|string|max:100',
            'penyebab' => 'required|in:Gangguan ISP,Maintenance,Manual,Lainnya',
            'durasi_menit' => 'nullable|integer|min:0',
            'status' => 'required|in:Berjalan,Selesai',
            'keterangan' => 'nullable|string',
        ]);
        $data['id_user'] = auth()->id();
        LaporanFailover::create($data);
        return redirect()->route('admin.failover.index')->with('success', 'Log failover berhasil disimpan.');
    }

    public function show(LaporanFailover $failover)
    {
        return view('admin.failover.show', compact('failover'));
    }

    public function edit(LaporanFailover $failover)
    {
        $laporanHarian = LaporanHarian::orderBy('tanggal', 'desc')->take(30)->get();
        return view('admin.failover.edit', compact('failover', 'laporanHarian'));
    }

    public function update(Request $request, LaporanFailover $failover)
    {
        $data = $request->validate([
            'id_laporan' => 'nullable|exists:laporan_harian,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'provider_dari' => 'required|string|max:100',
            'provider_ke' => 'required|string|max:100',
            'penyebab' => 'required|in:Gangguan ISP,Maintenance,Manual,Lainnya',
            'durasi_menit' => 'nullable|integer|min:0',
            'status' => 'required|in:Berjalan,Selesai',
            'keterangan' => 'nullable|string',
        ]);
        $failover->update($data);
        return redirect()->route('admin.failover.index')->with('success', 'Log failover diperbarui.');
    }

    public function destroy(LaporanFailover $failover)
    {
        $failover->delete();
        return redirect()->route('admin.failover.index')->with('success', 'Log failover dihapus.');
    }
}
