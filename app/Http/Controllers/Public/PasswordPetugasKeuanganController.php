<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PasswordPetugasKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordPetugasKeuanganController extends Controller
{
    public function verifikasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'password' => 'required|string|max:50',
            'redirect_url' => 'nullable|string|max:500',
        ]);

        $nama = trim((string) $request->nama);
        $tujuan = $request->redirect_url ?: route('pendaftaran.statistik.keuangan');

        logAktivitas(
            'Petugas Keuangan Public - Submit Verifikasi Password',
            'Mengirim verifikasi password petugas keuangan atas nama ' . $nama . ' untuk akses halaman: ' . $tujuan . '.'
        );

        $data = PasswordPetugasKeuangan::where('nama', $nama)->first();

        if (!$data || !Hash::check($request->password, $data->password)) {
            logAktivitas(
                'Petugas Keuangan Public - Verifikasi Password Gagal',
                'Verifikasi gagal untuk nama petugas: ' . $nama . ' pada halaman: ' . $tujuan . '.'
            );

            return back()->with('error', 'Nama petugas atau password salah.');
        }

        session([
            'akses_keuangan_public' => true,
            'nama_petugas_keuangan_public' => $data->nama,
        ]);

        logAktivitas(
            'Petugas Keuangan Public - Verifikasi Password Berhasil',
            'Berhasil verifikasi petugas keuangan ' . $data->nama . '. Akses statistik keuangan dibuka untuk: ' . $tujuan . '.'
        );

        return redirect($tujuan);
    }
}
