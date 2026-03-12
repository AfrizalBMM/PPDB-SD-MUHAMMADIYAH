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
            'password' => 'required'
        ]);

        $tahunAjaran = TahunAjaran::where('aktif',1)->first();

        if (!$tahunAjaran) {
            return back()->with('error','Tahun ajaran aktif tidak ditemukan');
        }

        $data = PasswordPanitia::where('tahun_ajaran_id',$tahunAjaran->id)->first();

        if (!$data) {
            return back()->with('error','Password panitia belum dibuat');
        }

        if (!Hash::check($request->password, $data->password)) {
            return back()->with('error','Password salah');
        }

        session(['akses_pembayaran' => true]);

        return redirect($request->redirect_url);
    }
}