<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: 210mm 330mm;
            margin: 15mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .kop img {
            width: 100%;
            margin-bottom: 8px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section {
            background: #e5e5e5;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

{{-- KOP --}}
<div class="kop">
    <img src="{{ public_path('kop-sdm.png') }}">
</div>

<div class="judul">
    FORMULIR PENDAFTARAN PESERTA DIDIK BARU<br>
    SD MUHAMMADIYAH WONOREJO<br>
    TAHUN AJARAN {{ optional($siswa->registration->tahunAjaran)->nama ?? '-' }}
</div>

{{-- A. DATA UMUM --}}
<table>
    <tr>
        <td colspan="4" class="section">A. DATA UMUM</td>
    </tr>
    <tr>
        <td width="25%">No Registrasi</td>
        <td width="25%">
            {{ optional($siswa->registration)->nomor_registrasi ?? '-' }}
        </td>
        <td width="25%">Tanggal Daftar</td>
        <td width="25%">
            {{ optional($siswa->registration)->tanggal_daftar ?? '-' }}
        </td>
    </tr>
</table>

<br>

{{-- B. IDENTITAS SISWA --}}
<table>
    <tr>
        <td colspan="4" class="section">B. IDENTITAS PESERTA DIDIK</td>
    </tr>
    <tr>
        <td>Nama Lengkap</td>
        <td colspan="3">{{ $siswa->nama }}</td>
    </tr>
    <tr>
        <td>Jenis Kelamin</td>
        <td>{{ strtoupper($siswa->jenis_kelamin) }}</td>
        <td>NIK</td>
        <td>{{ $siswa->nik }}</td>
    </tr>
    <tr>
        <td>No KK</td>
        <td>{{ $siswa->no_kk }}</td>
        <td>No Akta Lahir</td>
        <td>{{ $siswa->akta_no }}</td>
    </tr>
    <tr>
        <td>Tempat, Tgl Lahir</td>
        <td colspan="3">
            {{ $siswa->tempat_lahir }},
            {{ optional($siswa->tanggal_lahir)
                ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y')
                : '-' }}
        </td>
    </tr>
    <tr>
        <td>Agama</td>
        <td>{{ $siswa->agama }}</td>
        <td>Kewarganegaraan</td>
        <td>{{ $siswa->kewarganegaraan }}</td>
    </tr>
    <tr>
        <td>Berkebutuhan Khusus</td>
        <td colspan="3">{{ $siswa->berkebutuhan_khusus }}</td>
    </tr>
</table>

<br>

{{-- C. ALAMAT --}}
<table>
    <tr>
        <td colspan="4" class="section">C. ALAMAT TEMPAT TINGGAL</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td colspan="3">{{ $siswa->alamat }}</td>
    </tr>
    <tr>
        <td>Kelurahan</td>
        <td>{{ $siswa->kelurahan }}</td>
        <td>Kecamatan</td>
        <td>{{ $siswa->kecamatan }}</td>
    </tr>
    <tr>
        <td>Kabupaten</td>
        <td>{{ $siswa->kabupaten }}</td>
        <td>Provinsi</td>
        <td>{{ $siswa->provinsi }}</td>
    </tr>
    <tr>
        <td>RT</td>
        <td>{{ $siswa->rt }}</td>
        <td>RW</td>
        <td>{{ $siswa->rw }}</td>
    </tr>
    <tr>
        <td>Kode Pos</td>
        <td colspan="3">{{ $siswa->kode_pos }}</td>
    </tr>
</table>

<br>

{{-- D. DATA IBU --}}
<table>
    <tr>
        <td colspan="4" class="section">D. DATA IBU KANDUNG</td>
    </tr>
    <tr>
        <td>Nama Ibu</td>
        <td colspan="3">{{ optional($siswa->ibu)->nama ?? '-' }}</td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>{{ optional($siswa->ibu)->nik ?? '-' }}</td>
        <td>No HP</td>
        <td>{{ optional($siswa->ibu)->no_hp ?? '-' }}</td>
    </tr>
    <tr>
        <td>Pendidikan</td>
        <td>{{ optional($siswa->ibu)->pendidikan ?? '-' }}</td>
        <td>Pekerjaan</td>
        <td>{{ optional($siswa->ibu)->pekerjaan ?? '-' }}</td>
    </tr>
    <tr>
        <td>Penghasilan</td>
        <td colspan="3">{{ optional($siswa->ibu)->penghasilan ?? '-' }}</td>
    </tr>
</table>

<br>

{{-- DATA AYAH --}}
@if($siswa->ayah)
<table>
    <tr>
        <td colspan="4" class="section">DATA AYAH KANDUNG</td>
    </tr>
    <tr>
        <td>Nama Ayah</td>
        <td colspan="3">{{ $siswa->ayah->nama }}</td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>{{ $siswa->ayah->nik }}</td>
        <td>No HP</td>
        <td>{{ $siswa->ayah->no_hp }}</td>
    </tr>
    <tr>
        <td>Pendidikan</td>
        <td>{{ $siswa->ayah->pendidikan }}</td>
        <td>Pekerjaan</td>
        <td>{{ $siswa->ayah->pekerjaan }}</td>
    </tr>
</table>
@endif

<br>

{{-- E. DATA PENDUKUNG --}}
<table>
    <tr>
        <td colspan="4" class="section">E. DATA PENDUKUNG</td>
    </tr>
    <tr>
        <td>Tinggi Badan</td>
        <td>{{ optional($siswa->dataPendukung)->tinggi ?? '-' }} cm</td>
        <td>Berat Badan</td>
        <td>{{ optional($siswa->dataPendukung)->berat ?? '-' }} kg</td>
    </tr>
    <tr>
        <td>Jarak ke Sekolah</td>
        <td>{{ optional($siswa->dataPendukung)->jarak ?? '-' }} km</td>
        <td>Jumlah Saudara</td>
        <td>{{ optional($siswa->dataPendukung)->jumlah_saudara ?? '-' }}</td>
    </tr>
    <tr>
        <td>Asal PAUD / TK</td>
        <td colspan="3">
            {{ optional(optional($siswa->dataPendukung)->paudTk)->nama ?? '-' }}
        </td>
    </tr>
    <tr>
        <td>Hobi</td>
        <td>{{ optional($siswa->dataPendukung)->hobi ?? '-' }}</td>
        <td>Cita-cita</td>
        <td>{{ optional($siswa->dataPendukung)->cita_cita ?? '-' }}</td>
    </tr>
    <tr>
        <td>Hasil Tes</td>
        <td colspan="3">{{ $siswa->hasil_tes ?? '-' }}</td>
    </tr>
</table>

<br><br>

<table style="border:none">
    <tr>
        <td style="border:none" width="60%"></td>
        <td style="border:none" align="center">
            Wonorejo, {{ now()->translatedFormat('d F Y') }}<br><br><br>
            ( _________________________ )<br>
            Orang Tua / Wali
        </td>
    </tr>
</table>

</body>
</html>
