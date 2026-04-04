<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaKeuanganExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(private readonly Collection $rows)
    {
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Jenis Kelamin',
            'No Registrasi',
            'Total Biaya',
            'Total Terbayar',
            'P',
            'DU',
            'UDP',
            'Total Kekurangan',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows->values()->map(function ($item, $index) {
            $registration = $item->registration;
            $tagihan = $item->tagihan ?? collect();
            $totalBiaya = (int) $tagihan->sum('total');
            $totalTerbayar = (int) $tagihan->sum('total_dibayar');
            $totalKekurangan = (int) $tagihan->sum('sisa');

            // Kekurangan per jenis biaya (P, DU, UDP)
            $kekuranganP = 0;
            $kekuranganDU = 0;
            $kekuranganUDP = 0;
            foreach ($tagihan as $t) {
                $jenis = strtolower(str_replace(' ', '_', optional($t->biaya)->jenis_biaya ?? ''));
                $sisa = isset($t->sisa) ? (int) $t->sisa : 0;
                if ($jenis === 'pendaftaran') {
                    $kekuranganP += $sisa;
                } elseif ($jenis === 'daftar_ulang') {
                    $kekuranganDU += $sisa;
                } elseif ($jenis === 'udp') {
                    $kekuranganUDP += $sisa;
                }
            }

            return [
                $index + 1,
                $item->nama ?? '-',
                $item->jenis_kelamin === 'laki-laki' ? 'Laki-laki' : ($item->jenis_kelamin === 'perempuan' ? 'Perempuan' : ($item->jenis_kelamin ?? '-')),
                optional($registration)->nomor_registrasi ?? '-',
                $totalBiaya,
                // Kolom rincian pembayaran dihapus agar selaras dengan PDF
                '',
                $kekuranganP,
                $kekuranganDU,
                $kekuranganUDP,
                $totalTerbayar,
                $totalKekurangan,
            ];
        });
    }
}
