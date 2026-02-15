<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Biaya;
use App\Models\Voucher;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\DB;

class GenerateTagihanService
{
    public static function generate(Siswa $siswa, ?int $voucherId = null): void
    {
        // Jika sudah punya tagihan → stop
        if ($siswa->tagihan()->exists()) {
            return;
        }

        DB::transaction(function () use ($siswa, $voucherId) {

            $voucher = null;
            $voucherDipakai = false;

            /*
            |--------------------------------------------------------------------------
            | AMBIL & LOCK VOUCHER (ANTI RACE CONDITION)
            |--------------------------------------------------------------------------
            */

            if ($voucherId) {
                $voucher = Voucher::where('id', $voucherId)
                    ->where('aktif', true)
                    ->lockForUpdate()
                    ->first();

                if ($voucher) {
                    $masihBerlaku =
                        $voucher->digunakan < $voucher->maks_penggunaan &&
                        now()->between($voucher->tanggal_mulai, $voucher->tanggal_selesai);

                    if (!$masihBerlaku) {
                        $voucher = null;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | AMBIL BIAYA SESUAI TAHUN & JK
            |--------------------------------------------------------------------------
            */

            $biayaList = Biaya::aktif()
                ->untukTahun($siswa->registration->tahun_ajaran_id)
                ->untukJenisKelamin($siswa->jenis_kelamin)
                ->get();

            foreach ($biayaList as $biaya) {

                $diskon = 0;

                /*
                |--------------------------------------------------------------------------
                | HITUNG DISKON (HANYA SEKALI)
                |--------------------------------------------------------------------------
                */

                if (
                    $voucher &&
                    !$voucherDipakai &&
                    $voucher->jenis_biaya === $biaya->jenis_biaya
                ) {
                    $diskon = min($voucher->diskon_nominal, $biaya->nominal);
                    $voucherDipakai = true;
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN TAGIHAN
                |--------------------------------------------------------------------------
                */

                TagihanSiswa::create([
                    'siswa_id'     => $siswa->id,
                    'biaya_id'     => $biaya->id,
                    'nominal'      => $biaya->nominal,
                    'diskon'       => $diskon,
                    'total'        => max(0, $biaya->nominal - $diskon),
                    'voucher_id'   => $voucherDipakai ? $voucher?->id : null,
                    'kode_voucher' => $voucherDipakai ? $voucher?->kode : null,
                    'status'       => 'belum_lunas',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE KUOTA VOUCHER
            |--------------------------------------------------------------------------
            */

            if ($voucher && $voucherDipakai) {
                $voucher->increment('digunakan');
            }
        });
    }
}