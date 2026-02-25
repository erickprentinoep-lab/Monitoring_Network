<?php
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\LaporanFailover;
use App\Models\Vlan;
use App\Models\KonfigurasiJaringan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = LaporanHarian::with(['detailVlan.vlan'])
            ->whereDate('tanggal', today())->first();

        $tren = LaporanHarian::orderBy('tanggal', 'asc')
            ->where('tanggal', '>=', now()->subDays(13))->get();

        $distribusiGrade = LaporanHarian::select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')->get()->keyBy('grade');

        $totalLaporan = LaporanHarian::count();
        $totalFailover = LaporanFailover::count();
        $vlanAktif = Vlan::where('aktif', true)->count();
        $recentLaporan = LaporanHarian::with('user')->orderBy('tanggal', 'desc')->take(7)->get();
        $recentFailover = LaporanFailover::with(['user'])->orderBy('created_at', 'desc')->take(5)->get();
        $avgBandwidth = LaporanHarian::where('tanggal', '>=', now()->subDays(13))->avg('persentase_bandwidth');

        return view('manager.dashboard', compact(
            'hariIni',
            'tren',
            'distribusiGrade',
            'totalLaporan',
            'totalFailover',
            'vlanAktif',
            'recentLaporan',
            'recentFailover',
            'avgBandwidth'
        ));
    }

    public function laporan(Request $request)
    {
        $query = LaporanHarian::with(['user', 'detailVlan.vlan'])->orderBy('tanggal', 'desc');
        if ($request->tanggal_dari)
            $query->where('tanggal', '>=', $request->tanggal_dari);
        if ($request->tanggal_sampai)
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        if ($request->grade)
            $query->where('grade', $request->grade);
        $laporan = $query->paginate(15);
        return view('manager.laporan', compact('laporan'));
    }

    public function showLaporan(LaporanHarian $laporan)
    {
        $laporan->load(['user', 'detailVlan.vlan', 'failover.user']);
        return view('manager.laporan_detail', compact('laporan'));
    }

    public function failover(Request $request)
    {
        $failovers = LaporanFailover::with(['user', 'laporan'])->orderBy('tanggal', 'desc')->paginate(15);
        return view('manager.failover', compact('failovers'));
    }

    public function konfigurasi()
    {
        $config = KonfigurasiJaringan::first();
        return view('manager.konfigurasi', compact('config'));
    }
}
