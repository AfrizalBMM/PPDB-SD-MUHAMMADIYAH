<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Biaya;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::where('aktif', true)->first();

        if (!$tahunAktif) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Silakan aktifkan Tahun Ajaran terlebih dahulu.');
        }

        $biaya = Biaya::where('tahun_ajaran_id', $tahunAktif->id)->get();

        return view('admin.biaya.index', compact('biaya', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_biaya' => 'required',
            'kategori' => 'required',
            'jenis_kelamin' => 'required',
            'nama_biaya' => 'required',
            'nominal' => 'required|numeric|min:0',
        ]);

        Biaya::create([
            'tahun_ajaran_id' => TahunAjaran::where('aktif', true)->value('id'),
            'jenis_biaya' => $request->jenis_biaya,
            'kategori' => $request->kategori,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_biaya' => $request->nama_biaya,
            'nominal' => $request->nominal,
            'aktif' => true,
        ]);

        logAktivitas(
            'Kelola Biaya',
            'Menambahkan biaya '.$request->nama_biaya.
            ' ('.$request->kategori.')'
        );

        return back()->with('success','Biaya berhasil ditambahkan');
    }

    public function toggle(Biaya $biaya)
    {
        $biaya->update(['aktif' => !$biaya->aktif]);
        return back();
    }

    public function destroy(Biaya $biaya)
    {
        $biaya->delete();
        return back()->with('success','Biaya dihapus');
    }
}
