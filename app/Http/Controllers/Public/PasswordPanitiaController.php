<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordPanitia;
use Illuminate\Support\Facades\Hash;
use App\Models\TahunAjaran;

class PasswordPanitiaController extends Controller
{
    public function verifikasi(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'redirect_url' => 'nullable|string|max:500',
        ]);

        $tujuan = $request->redirect_url ?: url()->previous();
        $isJson = $request->expectsJson();
        
        logAktivitas(
            'Panitia Public - Submit Verifikasi Password',
            'Mengirim verifikasi password panitia untuk akses halaman: ' . $tujuan . '.'
        );

        $tahunAjaran = TahunAjaran::where('aktif',1)->first();

        if (!$tahunAjaran) {
            logAktivitas(
                'Panitia Public - Verifikasi Password Gagal',
                'Gagal verifikasi password karena tahun ajaran aktif tidak ditemukan.'
            );
            
            if ($isJson) {
                return response()->json([
                    'error' => 'Tahun ajaran aktif tidak ditemukan'
                ], 400);
            }
            
            return back()->with('error','Tahun ajaran aktif tidak ditemukan');
        }

        $data = PasswordPanitia::where('tahun_ajaran_id',$tahunAjaran->id)->first();

        if (!$data) {
            logAktivitas(
                'Panitia Public - Verifikasi Password Gagal',
                'Gagal verifikasi password karena data password panitia belum tersedia untuk tahun ajaran ID ' . $tahunAjaran->id . '.'
            );
            
            if ($isJson) {
                return response()->json([
                    'error' => 'Password panitia belum dibuat'
                ], 400);
            }
            
            return back()->with('error','Password panitia belum dibuat');
        }

        if (!Hash::check($request->password, $data->password)) {
            logAktivitas(
                'Panitia Public - Verifikasi Password Gagal',
                'Password panitia tidak sesuai saat mengakses: ' . $tujuan . '.'
            );
            
            if ($isJson) {
                return response()->json([
                    'error' => 'Password salah'
                ], 401);
            }
            
            return back()->with('error','Password salah');
        }

        session(['akses_pembayaran' => true]);

        logAktivitas(
            'Panitia Public - Verifikasi Password Berhasil',
            'Berhasil verifikasi password panitia. Akses pembayaran dibuka untuk tujuan: ' . $tujuan . '.'
        );

        if ($isJson) {
            return response()->json([
                'success' => true,
                'message' => 'Password verified',
                'redirect' => $tujuan
            ]);
        }

        return redirect($tujuan);
    }
}