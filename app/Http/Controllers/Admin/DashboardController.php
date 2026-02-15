<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        // ================= TOTAL PENDAFTAR =================
        $totalPendaftar = Siswa::count();

        // ================= TOTAL DITERIMA =================
        $totalDiterima = Siswa::whereHas('registration', function ($q) {
            $q->where('status', 'diterima');
        })->count();

        // ================= PENDAFTAR HARI INI =================
        $pendaftarHariIni = Siswa::whereDate('created_at', now())->count();

        // ================= TOTAL PEMASUKAN =================
        $totalPembayaran = Pembayaran::sum('nominal_bayar');

        // ================= PEMASUKAN HARI INI =================
        $pembayaranHariIni = Pembayaran::whereDate(
            'tanggal_bayar',
            now()
        )->sum('nominal_bayar');

        // ================= PENDAFTAR TERBARU =================
        $pendaftarTerbaru = Siswa::with([
                'registration.tahunAjaran'
            ])
            ->orderByDesc('created_at')
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