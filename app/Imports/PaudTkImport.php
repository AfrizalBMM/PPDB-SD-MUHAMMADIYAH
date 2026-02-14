<?php

namespace App\Imports;

use App\Models\PaudTk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaudTkImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new PaudTk([
            'npsn'       => $row['npsn'] ?? null,
            'nama'       => $row['nama'],
            'jenis'      => $row['jenis'],
            'alamat'     => $row['alamat'] ?? null,
            'kelurahan'  => $row['kelurahan'] ?? null,
            'kecamatan'  => $row['kecamatan'] ?? null,
            'telp'       => $row['telp'] ?? null,
            'akreditasi' => $row['akreditasi'] ?? null,
            'aktif'      => true,
        ]);
    }
}

