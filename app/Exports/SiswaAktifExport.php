<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaAktifExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private bool $includeWali;

    public function __construct(private readonly Collection $rows)
    {
        $this->includeWali = $this->rows->contains(function ($item) {
            if (method_exists($item, 'relationLoaded') && $item->relationLoaded('wali')) {
                return (bool) $item->getRelation('wali');
            }

            return !empty($item->wali);
        });
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'No Registrasi',
            'Tanggal Daftar',
            'Tahun Ajaran',
            'Status',
            'Input By',
            'Kelas',

            'Nama Peserta Didik',
            'Jenis Kelamin',
            'NIK',
            'No KK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No Akta',
            'Agama',
            'Kewarganegaraan',
            'Berkebutuhan Khusus',
            'Tinggal Bersama',
            'Transportasi',
            'No KKS',
            'KPS',
            'KIP',
            'Layak PIP',

            'Alamat',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Kelurahan',
            'RT',
            'RW',
            'Kode Pos',

            'Ayah - Nama',
            'Ayah - NIK',
            'Ayah - No HP',
            'Ayah - Tahun Lahir',
            'Ayah - Pendidikan',
            'Ayah - Pekerjaan',
            'Ayah - Pekerjaan Lainnya',
            'Ayah - Penghasilan',

            'Ibu - Nama',
            'Ibu - NIK',
            'Ibu - No HP',
            'Ibu - Tahun Lahir',
            'Ibu - Pendidikan',
            'Ibu - Pekerjaan',
            'Ibu - Pekerjaan Lainnya',
            'Ibu - Penghasilan',

            'Tinggi',
            'Berat',
            'Jarak',
            'Jumlah Saudara',
            'Anak Ke',
            'PAUD/TK (Referensi)',
            'PAUD/TK Manual?',
            'Nama TK Manual',
            'Alamat TK',
            'Hobi',
            'Cita-cita',

            'Hasil Tes',
        ];

        if ($this->includeWali) {
            $headings = array_merge($headings, [
                'Wali - Nama',
                'Wali - Hubungan',
                'Wali - Hubungan Lainnya',
                'Wali - No HP',
                'Wali - NIK',
                'Wali - Tahun Lahir',
                'Wali - Pendidikan',
                'Wali - Pekerjaan',
                'Wali - Pekerjaan Lainnya',
                'Wali - Penghasilan',
                'Wali - Alamat',
            ]);
        }

        return $headings;
    }

    public function collection(): Collection
    {
        return $this->rows->values()->map(function ($item, $index) {
            $registration = $item->registration;
            $alamat = $item->alamat;
            $ayah = $item->ayah;
            $ibu = $item->ibu;
            $wali = $item->wali;
            $dataPendukung = $item->dataPendukung;

            $row = [
                $index + 1,
                optional($registration)->nomor_registrasi ?? '-',
                optional($registration?->tanggal_daftar)->format('Y-m-d') ?? '-',
                optional($registration?->tahunAjaran)->nama ?? '-',
                \App\Models\Registration::statusLabel(optional($registration)->status),
                optional($registration)->input_by ?? '-',
                optional($item->kelasSiswa)->nama_kelas ?? 'Belum Masuk Kelas',

                $item->nama ?? '-',
                ui_label($item->jenis_kelamin),
                $item->nik ?? '-',
                $item->no_kk ?? '-',
                $item->tempat_lahir ?? '-',
                optional($item->tanggal_lahir)->format('Y-m-d') ?? '-',
                $item->akta_no ?? '-',
                $item->agama ?? '-',
                $item->kewarganegaraan ?? '-',
                $item->berkebutuhan_khusus ?? '-',
                $item->tinggal_bersama ?? '-',
                $item->transportasi ?? '-',
                $item->no_kks ?? '-',
                $item->kps ?? '-',
                $item->kip ?? '-',
                (string) ($item->layak_pip ?? '-'),

                optional($alamat)->alamat ?? '-',
                optional($alamat)->provinsi ?? '-',
                optional($alamat)->kabupaten ?? '-',
                optional($alamat)->kecamatan ?? '-',
                optional($alamat)->kelurahan ?? '-',
                optional($alamat)->rt ?? '-',
                optional($alamat)->rw ?? '-',
                optional($alamat)->kode_pos ?? '-',

                optional($ayah)->nama ?? '-',
                optional($ayah)->nik ?? '-',
                optional($ayah)->no_hp ?? '-',
                optional($ayah)->tahun_lahir ?? '-',
                optional($ayah)->pendidikan ?? '-',
                optional($ayah)->pekerjaan ?? '-',
                optional($ayah)->pekerjaan_lainnya ?? '-',
                optional($ayah)->penghasilan ?? '-',

                optional($ibu)->nama ?? '-',
                optional($ibu)->nik ?? '-',
                optional($ibu)->no_hp ?? '-',
                optional($ibu)->tahun_lahir ?? '-',
                optional($ibu)->pendidikan ?? '-',
                optional($ibu)->pekerjaan ?? '-',
                optional($ibu)->pekerjaan_lainnya ?? '-',
                optional($ibu)->penghasilan ?? '-',

                optional($dataPendukung)->tinggi ?? '-',
                optional($dataPendukung)->berat ?? '-',
                optional($dataPendukung)->jarak ?? '-',
                optional($dataPendukung)->jumlah_saudara ?? '-',
                optional($dataPendukung)->anak_ke ?? '-',
                optional($dataPendukung?->paudTk)->nama ?? '-',
                (string) (optional($dataPendukung)->is_tk_manual ?? '-'),
                optional($dataPendukung)->nama_tk_manual ?? '-',
                optional($dataPendukung)->alamat_tk ?? '-',
                optional($dataPendukung)->hobi ?? '-',
                optional($dataPendukung)->cita_cita ?? '-',

                $item->hasil_tes ?? '-',
            ];

            if ($this->includeWali) {
                $row = array_merge($row, [
                    optional($wali)->nama ?? '',
                    optional($wali)->hubungan ?? '',
                    optional($wali)->hubungan_lainnya ?? '',
                    optional($wali)->no_hp ?? '',
                    optional($wali)->nik ?? '',
                    optional($wali)->tahun_lahir ?? '',
                    optional($wali)->pendidikan ?? '',
                    optional($wali)->pekerjaan ?? '',
                    optional($wali)->pekerjaan_lainnya ?? '',
                    optional($wali)->penghasilan ?? '',
                    optional($wali)->alamat ?? '',
                ]);
            }

            return $row;
        });
    }
}
