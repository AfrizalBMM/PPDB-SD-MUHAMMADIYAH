<?php

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logAktivitas')) {
    /**
     * Catat aktivitas user ke tabel log_aktivitas
     *
     * @param string $aksi       Aksi yang dilakukan
     * @param string|null $keterangan  Keterangan tambahan
     */
    function logAktivitas(string $aksi, string $keterangan = null)
    {
        try {
            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'role'       => Auth::check() && isset(Auth::user()->role) ? Auth::user()->role : 'public',
                'aksi'       => $aksi,
                'keterangan' => $keterangan ?? '', // pastikan tidak NULL
                'ip_address' => request()->ip() ?? 'unknown', // pastikan selalu ada string
            ]);
        } catch (\Exception $e) {
            // Jika gagal, log ke file saja agar tidak mengganggu proses utama
            \Log::error('Gagal log aktivitas: '.$e->getMessage(), [
                'aksi' => $aksi,
                'keterangan' => $keterangan,
            ]);
        }
    }
}
