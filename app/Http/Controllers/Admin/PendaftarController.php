<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with([
            'registration.tahunAjaran',
            'tagihan.biaya',
            'tagihan.pembayaran',
        ]);

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        return view('admin.pendaftar.index', [
            'siswa' => $query
                ->orderByDesc('created_at')
                ->paginate(20)
        ]);
    }

    public function show(Siswa $siswa)
    {
        $siswa->load([
            // ================= REGISTRATION =================
            'registration.tahunAjaran',

            // ================= IDENTITAS TAMBAHAN =================
            'alamatSiswa',

            // ================= ORANG TUA =================
            'ibu',
            'ayah',
            'wali',

            // ================= DATA PENDUKUNG =================
            'dataPendukung.paudTk',

            // ================= KEUANGAN =================
            'tagihan.biaya',
            'tagihan.pembayaran',
            'tagihan.voucher',
        ]);

        return view('admin.pendaftar.show', compact('siswa'));
    }
}