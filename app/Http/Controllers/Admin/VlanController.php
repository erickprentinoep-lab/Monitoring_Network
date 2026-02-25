<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vlan;
use Illuminate\Http\Request;

class VlanController extends Controller
{
    public function index()
    {
        $vlans = Vlan::orderBy('vlan_id')->paginate(15);
        return view('admin.vlan.index', compact('vlans'));
    }

    public function create()
    {
        return view('admin.vlan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vlan_id' => 'required|integer|unique:vlans,vlan_id',
            'nama' => 'required|string|max:100',
            'departemen' => 'nullable|string|max:100',
            'bandwidth_allocated' => 'required|numeric|min:0.1',
            'subnet' => 'nullable|string|max:50',
            'gateway' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'aktif' => 'nullable|boolean',
        ]);
        $data['aktif'] = $request->has('aktif');
        Vlan::create($data);
        return redirect()->route('admin.vlan.index')->with('success', 'VLAN berhasil ditambahkan.');
    }

    public function edit(Vlan $vlan)
    {
        return view('admin.vlan.edit', compact('vlan'));
    }

    public function update(Request $request, Vlan $vlan)
    {
        $data = $request->validate([
            'vlan_id' => 'required|integer|unique:vlans,vlan_id,' . $vlan->id,
            'nama' => 'required|string|max:100',
            'departemen' => 'nullable|string|max:100',
            'bandwidth_allocated' => 'required|numeric|min:0.1',
            'subnet' => 'nullable|string|max:50',
            'gateway' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);
        $data['aktif'] = $request->has('aktif');
        $vlan->update($data);
        return redirect()->route('admin.vlan.index')->with('success', 'VLAN berhasil diperbarui.');
    }

    public function destroy(Vlan $vlan)
    {
        $vlan->delete();
        return redirect()->route('admin.vlan.index')->with('success', 'VLAN berhasil dihapus.');
    }
}
