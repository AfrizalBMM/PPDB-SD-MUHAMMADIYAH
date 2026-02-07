<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // total pendaftar
        $totalPendaftar = Siswa::count();

        // total diterima
        $totalDiterima = Siswa::whereHas('registration', function ($q) {
            $q->where('status', 'diterima');
        })->count();

        // pendaftar hari ini
        $pendaftarHariIni = Siswa::whereDate('created_at', Carbon::today())->count();

        // total pemasukan (semua waktu)
        $totalPembayaran = Pembayaran::sum('nominal_bayar');

        // pemasukan hari ini
        $pembayaranHariIni = Pembayaran::whereDate(
            'tanggal_bayar',
            Carbon::today()
        )->sum('nominal_bayar');

        $pendaftarTerbaru = Siswa::with('registration')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPendaftar',
            'totalDiterima',
            'pendaftarHariIni',
            'totalPembayaran',
            'pembayaranHariIni',
            'pendaftarTerbaru'
        ));
    }
}
