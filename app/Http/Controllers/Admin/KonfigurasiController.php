<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\KonfigurasiJaringan;
use Illuminate\Http\Request;

class KonfigurasiController extends Controller
{
    public function index()
    {
        $config = KonfigurasiJaringan::first();
        return view('admin.konfigurasi.index', compact('config'));
    }

    public function edit()
    {
        $config = KonfigurasiJaringan::firstOrNew([]);
        return view('admin.konfigurasi.edit', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_perusahaan' => 'required|string|max:150',
            'isp_utama' => 'nullable|string|max:100',
            'isp_backup' => 'nullable|string|max:100',
            'bandwidth_utama' => 'nullable|numeric|min:0',
            'bandwidth_backup' => 'nullable|numeric|min:0',
            'perangkat_utama' => 'nullable|string|max:150',
            'konfigurasi_qos' => 'nullable|string',
            'konfigurasi_failover' => 'nullable|string',
            'konfigurasi_vlan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);
        $data['updated_by'] = auth()->id();
        KonfigurasiJaringan::updateOrCreate(['id' => 1], $data);
        return redirect()->route('admin.konfigurasi')->with('success', 'Konfigurasi jaringan berhasil diperbarui.');
    }
}
