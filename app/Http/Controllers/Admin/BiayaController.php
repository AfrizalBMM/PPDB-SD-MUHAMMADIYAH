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

        $tahunId = TahunAjaran::where('aktif', true)->value('id');

        $biaya = Biaya::create([
            'tahun_ajaran_id' => $tahunId,
            'jenis_biaya' => $request->jenis_biaya,
            'kategori' => $request->kategori,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_biaya' => $request->nama_biaya,
            'nominal' => $request->nominal,
            'aktif' => true,
        ]);

        // --- Log aktivitas ---
        logAktivitas(
            'Kelola Biaya',
            'Menambahkan biaya #'.$biaya->id.' "'.$biaya->nama_biaya.'" ('.$biaya->kategori.')'
        );

        return back()->with('success','Biaya berhasil ditambahkan');
    }

    public function destroy(Biaya $biaya)
    {
        $nama = $biaya->nama_biaya;
        $kategori = $biaya->kategori;
        $id = $biaya->id;

        $biaya->delete();

        // --- Log aktivitas ---
        logAktivitas(
            'Kelola Biaya',
            'Menghapus biaya #'.$id.' "'.$nama.'" ('.$kategori.')'
        );

        return back()->with('success','Biaya dihapus');
    }

    public function update(Request $request, Biaya $biaya)
    {
        $request->validate([
            'jenis_biaya' => 'required',
            'kategori' => 'required',
            'jenis_kelamin' => 'required',
            'nama_biaya' => 'required',
            'nominal' => 'required|numeric|min:0',
        ]);

        $biaya->update([
            'jenis_biaya' => $request->jenis_biaya,
            'kategori' => $request->kategori,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_biaya' => $request->nama_biaya,
            'nominal' => $request->nominal,
        ]);

        // --- Log aktivitas ---
        logAktivitas(
            'Kelola Biaya',
            'Mengubah biaya #'.$biaya->id.' menjadi "'.$biaya->nama_biaya.'" ('.$biaya->kategori.')'
        );

        return back()->with('success','Biaya berhasil diperbarui');
    }
}
