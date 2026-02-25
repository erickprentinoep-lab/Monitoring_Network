<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\LaporanFailover;
use App\Models\Vlan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = LaporanHarian::with(['detailVlan.vlan', 'failover'])
            ->whereDate('tanggal', today())->first();

        // Tren 14 hari terakhir
        $tren = LaporanHarian::orderBy('tanggal', 'asc')
            ->where('tanggal', '>=', now()->subDays(13))
            ->get();

        // Distribusi grade semua waktu
        $distribusiGrade = LaporanHarian::select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')->get()->keyBy('grade');

        // Statistik
        $totalLaporan = LaporanHarian::count();
        $totalFailover = LaporanFailover::count();
        $vlanAktif = Vlan::where('aktif', true)->count();

        // Laporan terbaru (7 baris)
        $recentLaporan = LaporanHarian::with('user')->orderBy('tanggal', 'desc')->take(7)->get();

        // Failover terbaru
        $recentFailover = LaporanFailover::with(['user', 'laporan'])->orderBy('created_at', 'desc')->take(5)->get();

        // Rata-rata bandwidth 14 hari
        $avgBandwidth = LaporanHarian::where('tanggal', '>=', now()->subDays(13))
            ->avg('persentase_bandwidth');

        return view('admin.dashboard', compact(
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
}
