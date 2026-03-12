<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;


class PendaftaranController extends Controller
{
    public function sukses(Siswa $siswa)
    {
        $siswa->load([
            'registration',
            'ibu',
            'ayah',
            'wali',
            'alamat',
            'dataPendukung.paudTk',
            'tagihan.biaya',
        ]);

        return view('pendaftaran.sukses', compact('siswa'));
    }

    public function list(Request $request)
    {
        $search = $request->search;

        $siswa = Siswa::with([
                'registration',
                'ibu',
                'tagihan.biaya'
            ])
            ->withSum('tagihan', 'total') // total biaya siswa
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                ->orWhereHas('registration', function ($q) use ($search) {
                    $q->where('nomor_registrasi', 'like', "%$search%");
                })
                ->orWhereHas('ibu', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%")
                        ->orWhere('no_hp', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pendaftaran.list', compact('siswa', 'search'));
    }

    public function show($id)
    {
        $siswa = Siswa::with([
            'registration',
            'alamat',
            'ibu',
            'ayah',
            'wali',
            'dataPendukung.paudTk',
            'tagihan.biaya'
        ])->findOrFail($id);

        return view('pendaftaran.detail', compact('siswa'));
    }

    public function showBiaya(Siswa $siswa)
    {
        $siswa->load([
            'registration',
            'ibu',
            'tagihan.biaya'
        ]);

        return view('pendaftaran.pembiayaan.biaya', compact('siswa'));
    }

}
