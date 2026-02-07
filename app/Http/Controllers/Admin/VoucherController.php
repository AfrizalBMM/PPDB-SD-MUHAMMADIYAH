<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('admin.voucher.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis_biaya' => 'required',
            'diskon_nominal' => 'required|numeric|min:0',
            'maks_penggunaan' => 'required|numeric|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $kode = strtoupper(Str::slug($request->nama)).'-'.$request->diskon_nominal;

        Voucher::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'jenis_biaya' => $request->jenis_biaya,
            'diskon_nominal' => $request->diskon_nominal,
            'maks_penggunaan' => $request->maks_penggunaan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'aktif' => true,
        ]);

        logAktivitas(
            'Kelola Voucher',
            'Menambahkan voucher '.$voucher->kode
        );

        return back()->with('success','Voucher berhasil dibuat');
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->update(['aktif' => !$voucher->aktif]);
        return back();
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success','Voucher dihapus');
    }
}
