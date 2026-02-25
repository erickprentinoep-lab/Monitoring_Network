<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengujian;
use App\Models\ParameterQos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengujian::with(['parameterQos', 'user'])->orderBy('tanggal', 'desc');

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $pengujian = $query->get();

        // Data perbandingan sebelum vs sesudah
        $sebelum = ParameterQos::whereHas('pengujian', fn($q) => $q->where('periode', 'sebelum'))
            ->selectRaw('ROUND(AVG(throughput),2) as throughput, ROUND(AVG(delay),2) as delay,
                         ROUND(AVG(jitter),2) as jitter, ROUND(AVG(packet_loss),2) as packet_loss')
            ->first();

        $sesudah = ParameterQos::whereHas('pengujian', fn($q) => $q->where('periode', 'sesudah'))
            ->selectRaw('ROUND(AVG(throughput),2) as throughput, ROUND(AVG(delay),2) as delay,
                         ROUND(AVG(jitter),2) as jitter, ROUND(AVG(packet_loss),2) as packet_loss')
            ->first();

        $distribusiKategori = ParameterQos::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')->get();

        // Peningkatan / penurunan %
        $delta = null;
        if ($sebelum && $sesudah && $sebelum->throughput && $sesudah->throughput) {
            $delta = [
                'throughput' => round((($sesudah->throughput - $sebelum->throughput) / $sebelum->throughput) * 100, 1),
                'delay' => $sebelum->delay ? round((($sebelum->delay - $sesudah->delay) / $sebelum->delay) * 100, 1) : null,
                'jitter' => $sebelum->jitter ? round((($sebelum->jitter - $sesudah->jitter) / $sebelum->jitter) * 100, 1) : null,
                'packet_loss' => $sebelum->packet_loss ? round((($sebelum->packet_loss - $sesudah->packet_loss) / $sebelum->packet_loss) * 100, 1) : null,
            ];
        }

        return view('admin.laporan', compact(
            'pengujian',
            'sebelum',
            'sesudah',
            'distribusiKategori',
            'delta'
        ));
    }
}
