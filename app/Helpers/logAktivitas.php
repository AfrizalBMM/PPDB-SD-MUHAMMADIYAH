<?php

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            Log::error('Gagal log aktivitas: '.$e->getMessage(), [
                'aksi' => $aksi,
                'keterangan' => $keterangan,
            ]);
        }
    }
}

if (!function_exists('ui_label')) {
    /**
     * Format nilai enum/slug dari DB agar ramah UI.
     * Contoh: orang_tua -> Orang Tua, daftar_ulang -> Daftar Ulang.
     */
    function ui_label($value, string $fallback = '-'): string
    {
        if ($value === null) {
            return $fallback;
        }

        $text = trim((string) $value);

        if ($text === '' || $text === '-') {
            return $fallback;
        }

        $acronymMap = [
            'udp' => 'UDP',
            'kps' => 'KPS',
            'pkh' => 'PKH',
            'kip' => 'KIP',
            'pip' => 'PIP',
            'ppdb' => 'PPDB',
        ];

        $parts = preg_split('/[\s_-]+/u', mb_strtolower($text, 'UTF-8')) ?: [];

        $formatted = array_map(function ($part) use ($acronymMap) {
            if ($part === '') {
                return '';
            }

            if (isset($acronymMap[$part])) {
                return $acronymMap[$part];
            }

            return mb_convert_case($part, MB_CASE_TITLE, 'UTF-8');
        }, $parts);

        $result = trim(implode(' ', array_filter($formatted, fn ($part) => $part !== '')));

        return $result !== '' ? $result : $fallback;
    }
}
