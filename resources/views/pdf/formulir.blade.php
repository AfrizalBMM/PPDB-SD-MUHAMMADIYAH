<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Formulir PPDB</title>

<style>
@page {
    size: 210mm 330mm; /* F4 */
    margin: 10mm;
}

/* FONT */
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    line-height: 1.2;
}

/* JUDUL */
.title {
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 5px;
}

/* TABLE GLOBAL */
table {
    width: 100%;
    border-collapse: collapse;
}

/* CELL */
td {
    padding: 2px 3px;
    vertical-align: middle;
}

/* LABEL KIRI */
.label {
    width: 32%;
    white-space: nowrap;
}

/* TITIK DUA */
.colon {
    width: 2%;
    text-align: center;
}

/* VALUE */
.value {
    width: 66%;
}

/* KOTAK PANJANG (ISI DATA) */
.box {
    border: 1px solid #000;
    height: 15px;
    padding: 1px 3px;
    font-size: 11px;
}

/* KOTAK KECIL PER DIGIT (NIK, Tanggal) */
.box-small {
    border: 1px solid #000;
    width: 13px;
    height: 15px;
    text-align: center;
    font-size: 10px;
    display: inline-block;
    margin-right: -1px; /* biar rapat */
}

/* HEADER SECTION ABU */
.section {
    background: #e0e0e0;
    font-weight: bold;
    text-align: center;
    padding: 3px;
    border: 1px solid #000;
    margin-top: 6px;
}

/* GARIS INPUT KOSONG */
.line {
    border-bottom: 1px solid #000;
    height: 15px;
}

/* RT RW BOX KECIL */
.box-rt {
    border: 1px solid #000;
    width: 25px;
    height: 15px;
    display: inline-block;
    text-align: center;
}

/* TANDA TANGAN */
.ttd {
    text-align: center;
    padding-top: 30px;
}
</style>

</head>

<body>

<div class="title">
FORMULIR PESERTA DIDIK SD MUHAMMADIYAH WONOREJO<br>
Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}
</div>

<br>

<!-- TANGGAL & KELAS -->
<table>
<tr>
<td width="20%">Tanggal</td><td width="2%">:</td>
<td>
<table>
<tr>
<td class="box-small"></td>
<td class="box-small"></td>
<td>/</td>
<td class="box-small"></td>
<td class="box-small"></td>
<td>/</td>
<td class="box-small">2</td>
<td class="box-small">0</td>
<td class="box-small">{{ date('y')[0] }}</td>
<td class="box-small">{{ date('y')[1] }}</td>
</tr>
</table>
</td>
</tr>

<tr>
<td>Kelas</td><td>:</td>
<td class="box"></td>
</tr>
</table>

<br>

<!-- ================= IDENTITAS SISWA ================= -->
<table>
<tr><td colspan="3" class="section">IDENTITAS PESERTA DIDIK (WAJIB DIISI)</td></tr>

<tr><td class="label">a Nama</td><td>:</td><td class="box">{{ $siswa->nama }}</td></tr>
<tr><td>b Jenis Kelamin</td><td>:</td><td class="box">{{ $siswa->jenis_kelamin }}</td></tr>

<tr>
<td>c NISN</td><td>:</td>
<td>
<table><tr>
@for($i=0;$i<10;$i++)
<td class="box-small">{{ $siswa->nisn[$i] ?? '' }}</td>
@endfor
</tr></table>
</td>
</tr>

<tr>
<td>d NIK</td><td>:</td>
<td>
<table><tr>
@for($i=0;$i<16;$i++)
<td class="box-small">{{ $siswa->nik[$i] ?? '' }}</td>
@endfor
</tr></table>
</td>
</tr>

<tr><td>e Tempat Lahir</td><td>:</td><td class="box">{{ $siswa->tempat_lahir }}</td></tr>

<tr>
<td>f Tanggal Lahir</td><td>:</td>
<td>
<table><tr>
@php $tgl = date('dmY', strtotime($siswa->tanggal_lahir)); @endphp
@for($i=0;$i<8;$i++)
<td class="box-small">{{ $tgl[$i] }}</td>
@if($i==1 || $i==3)<td>/</td>@endif
@endfor
</tr></table>
</td>
</tr>

<tr><td>g No Registrasi Akta Lahir</td><td>:</td><td class="box">{{ $siswa->akta_no }}</td></tr>
<tr><td>h Agama</td><td>:</td><td class="box">{{ $siswa->agama }}</td></tr>
<tr><td>i Kewarganegaraan</td><td>:</td><td class="box">{{ $siswa->kewarganegaraan }}</td></tr>
<tr><td>j Berkebutuhan Khusus</td><td>:</td><td class="box">{{ $siswa->berkebutuhan_khusus }}</td></tr>
<tr><td>k Alamat</td><td>:</td><td class="box">{{ $siswa->alamat->alamat ?? '' }}</td></tr>

<tr>
<td></td><td></td>
<td>RT 
<table style="display:inline-table">
<tr>
<td class="box-small">{{ $siswa->alamat->rt ?? '' }}</td>
</tr>
</table>
RW 
<table style="display:inline-table">
<tr>
<td class="box-small">{{ $siswa->alamat->rw ?? '' }}</td>
</tr>
</table>
</td>
</tr>

<tr><td>l Desa</td><td>:</td><td class="box">{{ $siswa->alamat->kelurahan ?? '' }}</td></tr>
<tr><td>m Kecamatan</td><td>:</td><td class="box">{{ $siswa->alamat->kecamatan ?? '' }}</td></tr>
<tr><td>n Kode Pos</td><td>:</td><td class="box">{{ $siswa->alamat->kode_pos ?? '' }}</td></tr>

<tr><td>o Tempat Tinggal</td><td>:</td><td class="box">Bersama Orang Tua / Bersama Wali</td></tr>
<tr><td>p Moda Transportasi</td><td>:</td><td class="box">Jalan Kaki / Sepeda / Antar Jemput</td></tr>
<tr><td>q No KKS</td><td>:</td><td class="box"></td></tr>
<tr><td>r No KPS</td><td>:</td><td class="box"></td></tr>
<tr><td>s No KIP</td><td>:</td><td class="box"></td></tr>
</table>

<br>

<!-- ================= DATA AYAH ================= -->
<table>
<tr><td colspan="3" class="section">DATA AYAH KANDUNG</td></tr>
<tr><td>a Nama</td><td>:</td><td class="box">{{ $siswa->ayah->nama ?? '' }}</td></tr>
<tr><td>b NIK Ayah</td><td>:</td><td class="box">{{ $siswa->ayah->nik ?? '' }}</td></tr>
<tr><td>c Tahun Lahir Ayah</td><td>:</td><td class="box">{{ $siswa->ayah->tahun_lahir ?? '' }}</td></tr>
<tr><td>d Pendidikan Ayah</td><td>:</td><td class="box">{{ $siswa->ayah->pendidikan ?? '' }}</td></tr>
<tr><td>e Pekerjaan Ayah</td><td>:</td><td class="box">{{ $siswa->ayah->pekerjaan ?? '' }}</td></tr>
<tr><td>f Penghasilan Ayah (WAJIB DIISI)</td><td>:</td><td class="box">{{ $siswa->ayah->penghasilan ?? '' }}</td></tr>
</table>

<br>

<!-- ================= DATA IBU ================= -->
<table>
<tr><td colspan="3" class="section">DATA IBU KANDUNG</td></tr>
<tr><td>a Nama</td><td>:</td><td class="box">{{ $siswa->ibu->nama ?? '' }}</td></tr>
<tr><td>b NIK Ibu</td><td>:</td><td class="box">{{ $siswa->ibu->nik ?? '' }}</td></tr>
<tr><td>c Tahun Lahir Ibu</td><td>:</td><td class="box">{{ $siswa->ibu->tahun_lahir ?? '' }}</td></tr>
<tr><td>d Pendidikan Ibu</td><td>:</td><td class="box">{{ $siswa->ibu->pendidikan ?? '' }}</td></tr>
<tr><td>e Pekerjaan Ibu</td><td>:</td><td class="box">{{ $siswa->ibu->pekerjaan ?? '' }}</td></tr>
<tr><td>f Penghasilan Ibu (WAJIB DIISI)</td><td>:</td><td class="box">{{ $siswa->ibu->penghasilan ?? '' }}</td></tr>
<tr><td>Nomor HP (WAJIB DIISI)</td><td>:</td><td class="box">{{ $siswa->ibu->no_hp ?? '' }}</td></tr>
</table>

<br>

<!-- ================= DATA RINCI ================= -->
<table>
<tr><td colspan="3" class="section">DATA RINCI</td></tr>
<tr><td>a Tinggi Badan</td><td>:</td><td class="box">{{ $siswa->tinggi_badan ?? '' }} cm</td></tr>
<tr><td>b Berat Badan</td><td>:</td><td class="box">{{ $siswa->berat_badan ?? '' }} Kg</td></tr>
<tr><td>c Jarak Rumah ke Sekolah</td><td>:</td><td class="box">{{ $siswa->jarak_sekolah ?? '' }} km</td></tr>
<tr><td>d Jumlah Saudara Kandung</td><td>:</td><td class="box">{{ $siswa->jumlah_saudara ?? '' }}</td></tr>
<tr><td>e Nama TK</td><td>:</td><td class="box">{{ $siswa->nama_tk ?? '' }}</td></tr>
<tr><td>f Alamat TK</td><td>:</td><td class="box">{{ $siswa->alamat_tk ?? '' }}</td></tr>
<tr><td>g Hobi dan Cita-cita</td><td>:</td><td class="box">{{ $siswa->hobi ?? '' }}</td></tr>
</table>

<br><br>

<!-- ================= TTD ================= -->
<table width="100%">
<tr>
<td width="60%"></td>
<td width="40%" align="center">
Polokarto, {{ date('d F Y') }}<br><br><br><br>
( {{ $siswa->ibu->nama ?? '.......................' }} )<br>
<b>Wali Murid</b>
</td>
</tr>
</table>

</body>
</html>
