<?php

namespace App\Exports;

use App\Models\Registration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PendaftarExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(private readonly Collection $rows)
    {
    }

    public function headings(): array
    {
        return [
            'No',
            'No Registrasi',
            'Nama',
            'Jenis Kelamin',
            'Tanggal Daftar',
            'Status PPDB',
            'Status Pembayaran',
            'NIK',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows->values()->map(function ($siswa, $index) {
            $statusPpdb = Registration::statusLabel((int) optional($siswa->registration)->status);
            $tagihanAktif = $siswa->tagihan->filter(fn ($t) => (float) $t->total > 0);

            if ($tagihanAktif->isEmpty()) {
                $statusPembayaran = 'Belum Ada Tagihan';
            } elseif ($tagihanAktif->every(fn ($t) => $t->status === 'lunas')) {
                $statusPembayaran = 'Lunas';
            } else {
                $statusPembayaran = 'Belum Lunas';
            }

            return [
                $index + 1,
                $siswa->registration->nomor_registrasi ?? '-',
                $siswa->nama,
                ui_label($siswa->jenis_kelamin ?? '-'),
                optional($siswa->registration?->tanggal_daftar)->format('d-m-Y') ?? '-',
                $statusPpdb,
                $statusPembayaran,
                $siswa->nik ?? '-',
            ];
        });
    }
}
