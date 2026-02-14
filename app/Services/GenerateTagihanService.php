<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Biaya;
use App\Models\Voucher;
use App\Models\TagihanSiswa;
use Carbon\Carbon;

class GenerateTagihanService
{
    public static function generate(Siswa $siswa, ?int $voucherId = null): void
    {
        $voucher = null;

        if ($siswa->tagihan()->exists()) {
            return;
        }

        // ================= AMBIL & VALIDASI VOUCHER =================
        if ($voucherId) {
            $voucher = Voucher::where('id', $voucherId)
                ->where('aktif', true)
                ->first();

            // cek expired
            if ($voucher && $voucher->expired_at && Carbon::parse($voucher->expired_at)->isPast()) {
                $voucher = null;
            }
        }

        // ================= AMBIL BIAYA =================
        $biayaList = Biaya::where('tahun_ajaran_id', $siswa->registration->tahun_ajaran_id)
            ->where('aktif', true)
            ->where(function ($q) use ($siswa) {
                $q->where('jenis_kelamin', $siswa->jenis_kelamin)
                  ->orWhere('jenis_kelamin', 'semua');
            })
            ->get();

        $voucherDipakai = false;

        foreach ($biayaList as $biaya) {

            $diskon = 0;

            // ================= HITUNG DISKON =================
            if (
                $voucher &&
                !$voucherDipakai &&
                $voucher->jenis_biaya === $biaya->jenis_biaya
            ) {
                if ($voucher->tipe === 'nominal') {
                    $diskon = min($voucher->nilai, $biaya->nominal);
                }

                if ($voucher->tipe === 'persen') {
                    $diskon = floor($biaya->nominal * ($voucher->nilai / 100));
                }

                $voucherDipakai = true;
            }

            // ================= SIMPAN TAGIHAN =================
            TagihanSiswa::firstOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'biaya_id' => $biaya->id
                ],
                [
                    'nominal'      => $biaya->nominal,
                    'total'        => max(0, $biaya->nominal - $diskon),
                    'diskon'       => $diskon,
                    'voucher_id'   => $voucher?->id,
                    'kode_voucher' => $voucher?->kode,
                    'status'       => 'belum_lunas',
                ]
            );

        }

        // ================= UPDATE VOUCHER (SEKALI SAJA) =================
        if ($voucher && $voucherDipakai) {
            $voucher->increment('digunakan');
        }
    }
}
