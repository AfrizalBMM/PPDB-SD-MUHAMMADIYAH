<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        return view('admin.tahun-ajaran.index', [
            'data' => TahunAjaran::orderByDesc('id')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama',
            'aktif' => 'boolean',
        ], [
            'nama.unique' => 'Nama tahun ajaran sudah ada.',
        ]);

        if ($request->aktif) {
            TahunAjaran::where('aktif',true)->update(['aktif'=>false]);
        }

        TahunAjaran::create([
            'nama' => $request->nama,
            'aktif' => $request->aktif ?? false
        ]);

        return back();
    }

    public function aktifkan(TahunAjaran $tahunAjaran)
    {
        TahunAjaran::where('aktif',true)->update(['aktif'=>false]);
        $tahunAjaran->update(['aktif'=>true]);

        return back();
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->aktif) {
            return back()->withErrors(
                'Tahun ajaran aktif tidak boleh dihapus.'
            );
        }

        $tahunAjaran->delete();

        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

}
