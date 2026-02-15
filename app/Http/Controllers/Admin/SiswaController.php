<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class SiswaController extends Controller
{
    public function kelas1()
    {
        $tahunAktif = TahunAjaran::where('aktif', true)->first();

        if (!$tahunAktif) {
            return back()->with('error', 'Tidak ada Tahun Ajaran aktif.');
        }

        $siswa = Siswa::with([
                'registration.tahunAjaran'
            ])
            ->whereHas('registration', function ($q) use ($tahunAktif) {
                $q->where('status', 'diterima')
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })
            ->orderBy('nama')
            ->paginate(20);

        return view('admin.siswa.kelas1', compact('siswa','tahunAktif'));
    }
}