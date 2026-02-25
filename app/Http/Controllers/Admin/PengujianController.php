<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengujian;
use App\Models\ParameterQos;
use App\Helpers\QosHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengujianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengujian::with(['parameterQos', 'user'])->orderBy('tanggal', 'desc');

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $pengujian = $query->paginate(10)->withQueryString();

        return view('admin.pengujian.index', compact('pengujian'));
    }

    public function create()
    {
        return view('admin.pengujian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:100',
            'periode' => 'required|in:sebelum,sesudah',
            'keterangan' => 'nullable|string|max:255',
            'throughput' => 'required|numeric|min:0',
            'delay' => 'required|numeric|min:0',
            'jitter' => 'required|numeric|min:0',
            'packet_loss' => 'required|numeric|min:0|max:100',
        ]);

        $kategori = QosHelper::hitungKategori(
            $validated['throughput'],
            $validated['delay'],
            $validated['jitter'],
            $validated['packet_loss']
        );

        $kesimpulan = QosHelper::generateKesimpulan([
            'throughput' => $validated['throughput'],
            'delay' => $validated['delay'],
            'jitter' => $validated['jitter'],
            'packet_loss' => $validated['packet_loss'],
            'kategori' => $kategori,
        ]);

        $pengujian = Pengujian::create([
            'tanggal' => $validated['tanggal'],
            'lokasi' => $validated['lokasi'],
            'periode' => $validated['periode'],
            'keterangan' => $validated['keterangan'] ?? null,
            'id_user' => Auth::id(),
        ]);

        ParameterQos::create([
            'id_pengujian' => $pengujian->id_pengujian,
            'throughput' => $validated['throughput'],
            'delay' => $validated['delay'],
            'jitter' => $validated['jitter'],
            'packet_loss' => $validated['packet_loss'],
            'kategori' => $kategori,
            'kesimpulan' => $kesimpulan,
        ]);

        return redirect()->route('admin.pengujian.index')
            ->with('success', 'Data pengujian berhasil ditambahkan.');
    }

    public function show(Pengujian $pengujian)
    {
        $pengujian->load(['parameterQos', 'user']);
        return view('admin.pengujian.show', compact('pengujian'));
    }

    public function edit(Pengujian $pengujian)
    {
        $pengujian->load('parameterQos');
        return view('admin.pengujian.edit', compact('pengujian'));
    }

    public function update(Request $request, Pengujian $pengujian)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:100',
            'periode' => 'required|in:sebelum,sesudah',
            'keterangan' => 'nullable|string|max:255',
            'throughput' => 'required|numeric|min:0',
            'delay' => 'required|numeric|min:0',
            'jitter' => 'required|numeric|min:0',
            'packet_loss' => 'required|numeric|min:0|max:100',
        ]);

        $kategori = QosHelper::hitungKategori(
            $validated['throughput'],
            $validated['delay'],
            $validated['jitter'],
            $validated['packet_loss']
        );

        $kesimpulan = QosHelper::generateKesimpulan([
            'throughput' => $validated['throughput'],
            'delay' => $validated['delay'],
            'jitter' => $validated['jitter'],
            'packet_loss' => $validated['packet_loss'],
            'kategori' => $kategori,
        ]);

        $pengujian->update([
            'tanggal' => $validated['tanggal'],
            'lokasi' => $validated['lokasi'],
            'periode' => $validated['periode'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $pengujian->parameterQos()->updateOrCreate(
            ['id_pengujian' => $pengujian->id_pengujian],
            [
                'throughput' => $validated['throughput'],
                'delay' => $validated['delay'],
                'jitter' => $validated['jitter'],
                'packet_loss' => $validated['packet_loss'],
                'kategori' => $kategori,
                'kesimpulan' => $kesimpulan,
            ]
        );

        return redirect()->route('admin.pengujian.index')
            ->with('success', 'Data pengujian berhasil diperbarui.');
    }

    public function destroy(Pengujian $pengujian)
    {
        $pengujian->delete();
        return redirect()->route('admin.pengujian.index')
            ->with('success', 'Data pengujian berhasil dihapus.');
    }
}
