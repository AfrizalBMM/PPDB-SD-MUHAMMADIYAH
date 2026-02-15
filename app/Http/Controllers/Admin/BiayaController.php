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
        $tahunAktif = TahunAjaran::aktifSekarang();

        if (!$tahunAktif) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Silakan aktifkan Tahun Ajaran terlebih dahulu.');
        }

        $biaya = Biaya::untukTahun($tahunAktif->id)->get();

        return view('admin.biaya.index', compact('biaya', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_biaya'   => 'required|in:pendaftaran,daftar_ulang,udp',
            'kategori'      => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan,semua',
            'nama_biaya'    => 'required|string|max:150',
            'nominal'       => 'required|integer|min:0',
        ]);

        $tahunAktif = TahunAjaran::aktifSekarang();

        if (!$tahunAktif) {
            return back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $biaya = Biaya::create([
            'tahun_ajaran_id' => $tahunAktif->id,
            'jenis_biaya'     => $request->jenis_biaya,
            'kategori'        => $request->kategori,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'nama_biaya'      => $request->nama_biaya,
            'nominal'         => $request->nominal,
            'aktif'           => true,
        ]);

        logAktivitas(
            'Kelola Biaya',
            'Menambahkan biaya #'.$biaya->id.' "'.$biaya->nama_biaya.'" ('.$biaya->kategori.')'
        );

        return back()->with('success','Biaya berhasil ditambahkan');
    }

    public function update(Request $request, Biaya $biaya)
    {
        $request->validate([
            'jenis_biaya'   => 'required|in:pendaftaran,daftar_ulang,udp',
            'kategori'      => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan,semua',
            'nama_biaya'    => 'required|string|max:150',
            'nominal'       => 'required|integer|min:0',
        ]);

        // ❗ Jangan boleh ubah nominal jika sudah dipakai
        if ($biaya->tagihan()->exists()) {
            return back()->with('error', 'Biaya sudah digunakan pada tagihan dan tidak bisa diubah.');
        }

        $biaya->update([
            'jenis_biaya'   => $request->jenis_biaya,
            'kategori'      => $request->kategori,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_biaya'    => $request->nama_biaya,
            'nominal'       => $request->nominal,
        ]);

        logAktivitas(
            'Kelola Biaya',
            'Mengubah biaya #'.$biaya->id.' menjadi "'.$biaya->nama_biaya.'" ('.$biaya->kategori.')'
        );

        return back()->with('success','Biaya berhasil diperbarui');
    }

    public function destroy(Biaya $biaya)
    {
        // ❗ Jangan boleh hapus jika sudah dipakai
        if ($biaya->tagihan()->exists()) {
            return back()->with('error','Biaya sudah digunakan dan tidak dapat dihapus.');
        }

        $nama = $biaya->nama_biaya;
        $kategori = $biaya->kategori;
        $id = $biaya->id;

        $biaya->delete();

        logAktivitas(
            'Kelola Biaya',
            'Menghapus biaya #'.$id.' "'.$nama.'" ('.$kategori.')'
        );

        return back()->with('success','Biaya dihapus');
    }

    public function toggle(Biaya $biaya)
    {
        $statusBaru = !$biaya->aktif;

        $biaya->update([
            'aktif' => $statusBaru
        ]);

        logAktivitas(
            'Kelola Biaya',
            ($statusBaru ? 'Mengaktifkan' : 'Menonaktifkan') .
            ' biaya #' . $biaya->id . ' "' . $biaya->nama_biaya . '"'
        );

        return back()->with('success','Status biaya diperbarui');
    }
    
}