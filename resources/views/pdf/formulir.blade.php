<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir PPDB</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px; }
        .border td { border:1px solid #000; }
        .title { text-align:center; font-weight:bold; font-size:16px; }

        @page {
            size: F4 portrait;
            margin: 10mm 10mm 10mm 10mm; /* Narrow */
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px;
        }
        .border td {
            border: 1px solid #000;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="title">
    <b>FORMULIR PESERTA DIDIK</b><br>
    SD MUHAMMADIYAH WONOREJO<br>
    Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}
</div>

<hr style="margin:6px 0;">

<p>
Tanggal: {{ now()->format('d/m/Y') }} <br>
Kelas: .................................
</p>

<hr>

<h4>IDENTITAS PESERTA DIDIK</h4>
<table class="border">
<tr><td>Nama</td><td>{{ $siswa->nama }}</td></tr>
<tr><td>Jenis Kelamin</td><td>{{ ucfirst($siswa->jenis_kelamin) }}</td></tr>
<tr><td>NIK</td><td>{{ $siswa->nik }}</td></tr>
<tr><td>Tempat / Tgl Lahir</td><td>{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</td></tr>
<tr><td>No Akta</td><td>{{ $siswa->akta_no }}</td></tr>
<tr><td>Agama</td><td>{{ $siswa->agama }}</td></tr>
<tr><td>Kewarganegaraan</td><td>{{ $siswa->kewarganegaraan }}</td></tr>
<tr><td>Berkebutuhan Khusus</td><td>{{ $siswa->berkebutuhan_khusus }}</td></tr>
</table>

<h4>ALAMAT</h4>
<table class="border">
<tr><td>Alamat</td><td>{{ $siswa->alamat->alamat ?? '-' }}</td></tr>
<tr><td>RT/RW</td><td>{{ $siswa->alamat->rt ?? '-' }}/{{ $siswa->alamat->rw ?? '-' }}</td></tr>
<tr><td>Desa</td><td>{{ $siswa->alamat->kelurahan ?? '-' }}</td></tr>
<tr><td>Kecamatan</td><td>{{ $siswa->alamat->kecamatan ?? '-' }}</td></tr>
<tr><td>Kode Pos</td><td>{{ $siswa->alamat->kode_pos ?? '-' }}</td></tr>
</table>

<h4>DATA AYAH</h4>
<table class="border">
<tr><td>Nama</td><td>{{ $siswa->ayah->nama ?? '-' }}</td></tr>
<tr><td>NIK</td><td>{{ $siswa->ayah->nik ?? '-' }}</td></tr>
<tr><td>Pekerjaan</td><td>{{ $siswa->ayah->pekerjaan ?? '-' }}</td></tr>
</table>

<h4>DATA IBU</h4>
<table class="border">
<tr><td>Nama</td><td>{{ $siswa->ibu->nama ?? '-' }}</td></tr>
<tr><td>NIK</td><td>{{ $siswa->ibu->nik ?? '-' }}</td></tr>
<tr><td>No HP</td><td>{{ $siswa->ibu->no_hp ?? '-' }}</td></tr>
</table>

<!-- ================= TANDA TANGAN ================= -->

<table style="margin-top:40px; width:100%;">
<tr>
    <td width="50%" align="center">
        Wonorejo, {{ now()->format('d F Y') }}<br><br><br><br>
        ( {{ $petugas }} )<br>
        <b>Petugas</b>
    </td>

    <td width="50%" align="center">
        Orang Tua / Wali<br><br><br><br>
        ( {{ $siswa->ibu->nama ?? '-' }} )<br>
        <b>Wali Murid (Ibu)</b>
    </td>
</tr>
</table>

<hr style="margin-top:20px;">

<p style="font-size:10px;">
    Dicetak oleh: <b>{{ $petugas }}</b> |
    {{ now()->format('d F Y H:i') }}
</p>


</body>
</html>
