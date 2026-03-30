<?php

namespace App\Services;

use App\Models\Biaya;
use App\Models\Pembayaran;
use App\Models\Registration;
use App\Models\Siswa;
use App\Models\TagihanSiswa;

class StatusPpdbService
{
    public function syncBySiswa(Siswa $siswa): void
    {
        $siswa->loadMissing('registration');

        if (!$siswa->registration) {
            return;
        }

        // Status Peserta Didik adalah status final dan tidak diturunkan otomatis.
        if ((int) $siswa->registration->status === Registration::STATUS_PESERTA_DIDIK) {
            return;
        }

        $hasAcuan = Biaya::query()
            ->where('is_acuan_status_ppdb', true)
            ->exists();

        $tagihanQuery = TagihanSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->where('total', '>', 0)
            ->when($hasAcuan, function ($q) {
                $q->whereHas('biaya', function ($q2) {
                    $q2->where('is_acuan_status_ppdb', true);
                });
            });

        $hasRelevantTagihan = (clone $tagihanQuery)->exists();
        $hasBelumLunas = (clone $tagihanQuery)
            ->where('status', '!=', 'lunas')
            ->exists();

        $hasAnyPayment = Pembayaran::query()
            ->whereHas('tagihan', function ($q) use ($siswa, $hasAcuan) {
                $q->where('siswa_id', $siswa->id)
                    ->where('total', '>', 0)
                    ->when($hasAcuan, function ($q2) {
                        $q2->whereHas('biaya', function ($q3) {
                            $q3->where('is_acuan_status_ppdb', true);
                        });
                    });
            })
            ->exists();

        if ($hasRelevantTagihan && !$hasBelumLunas) {
            $targetStatus = Registration::STATUS_PESERTA_DIDIK;
        } elseif ($hasAnyPayment) {
            $targetStatus = Registration::STATUS_CALON;
        } else {
            $targetStatus = Registration::STATUS_BAKAL_CALON;
        }

        if ((int) $siswa->registration->status !== $targetStatus) {
            $siswa->registration->status = $targetStatus;
            $siswa->registration->save();
        }
    }
}
