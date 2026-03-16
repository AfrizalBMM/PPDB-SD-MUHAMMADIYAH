<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

@page{
    size:210mm 330mm;
    margin:10mm;
}

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:11px;
}

.wrapper{
    width:100%;
}

.kwitansi{
    border:1px solid #222;
    padding:12px;
    position:relative;
    background:rgba(30, 90, 180, 0.05);
}

.potong{
    border-top:2px dashed #000;
    margin:10px 0;
}

.header{
    border-bottom:2px solid #000;
    margin-bottom:8px;
}

.logo{
    width:60px;
}

.sekolah{
    font-size:16px;
    font-weight:bold;
    letter-spacing:1px;
}

.alamat{
    font-size:10px;
}

.judul{
    text-align:center;
    font-size:15px;
    font-weight:bold;
    margin-top:4px;
}

.nomor{
    text-align:right;
    font-size:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:3px;
}

.label{
    width:160px;
}

.nominal{
    font-size:16px;
    font-weight:bold;
    color:#1e40af;
}

.terbilang{
    font-style:italic;
    border:1px dashed #999;
    padding:4px;
    background:#f8fafc;
}

.ttd{
    margin-top:20px;
}

.ttd td{
    text-align:center;
}

.watermark{
    position:absolute;
    top:40%;
    left:25%;
    font-size:80px;
    color:#000;
    opacity:0.08;
    transform:rotate(-30deg);
}

</style>

</head>

<body>

<table class="wrapper">

<tr>
<td>

<div class="kwitansi">

<div class="watermark">LUNAS</div>

<table class="header">

<tr>

<td width="70">
<img src="{{ public_path('images/logo.png') }}" class="logo">
</td>

<td>

<div class="sekolah">
SEKOLAH DASAR MUHAMMADIYAH
</div>

<div class="alamat">
Jl. Contoh Alamat Sekolah No 123<br>
Telp: 0812-xxxx-xxxx
</div>

</td>

</tr>

</table>

<div class="judul">
KWITANSI PEMBAYARAN
</div>

<div class="nomor">
No Kwitansi : KW-{{ date('Y') }}-{{ str_pad($pembayaran->id,4,'0',STR_PAD_LEFT) }}
</div>

<br>

<table>

<tr>
<td class="label">Sudah Terima Dari</td>
<td>: {{ $pembayaran->tagihan->siswa->nama }}</td>
</tr>

<tr>
<td>Uang Sejumlah</td>
<td>: <span class="nominal">
Rp {{ number_format($pembayaran->nominal_bayar,0,',','.') }}
</span></td>
</tr>

<tr>
<td></td>
<td>
<div class="terbilang">
{{ $terbilang }}
</div>
</td>
</tr>

<tr>
<td>Untuk Pembayaran</td>
<td>: {{ $pembayaran->tagihan->biaya->jenis_biaya }}</td>
</tr>

<tr>
<td>Tanggal</td>
<td>: {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}</td>
</tr>

<tr>
<td>Keterangan</td>
<td>: {{ $pembayaran->keterangan ?? '-' }}</td>
</tr>

</table>

<table class="ttd">

<tr>
<td width="50%">Orang Tua / Wali</td>
<td width="50%">Petugas</td>
</tr>

<tr>
<td style="height:55px"></td>
<td></td>
</tr>

<tr>
<td>(........................)</td>
<td>( {{ $pembayaran->admin_penerima }} )</td>
</tr>

</table>

</div>

</td>
</tr>

<tr>
<td>

<div class="potong"></div>

</td>
</tr>

<tr>
<td>

<div class="kwitansi">

<div class="watermark">LUNAS</div>

<table class="header">

<tr>

<td width="70">
<img src="{{ public_path('images/logo.png') }}" class="logo">
</td>

<td>

<div class="sekolah">
SEKOLAH DASAR MUHAMMADIYAH
</div>

<div class="alamat">
Jl. Contoh Alamat Sekolah No 123<br>
Telp: 0812-xxxx-xxxx
</div>

</td>

</tr>

</table>

<div class="judul">
KWITANSI PEMBAYARAN
</div>

<div class="nomor">
No Kwitansi : KW-{{ date('Y') }}-{{ str_pad($pembayaran->id,4,'0',STR_PAD_LEFT) }}
</div>

<br>

<table>

<tr>
<td class="label">Sudah Terima Dari</td>
<td>: {{ $pembayaran->tagihan->siswa->nama }}</td>
</tr>

<tr>
<td>Uang Sejumlah</td>
<td>: <b>Rp {{ number_format($pembayaran->nominal_bayar,0,',','.') }}</b></td>
</tr>

<tr>
<td></td>
<td><i>{{ $terbilang }}</i></td>
</tr>

<tr>
<td>Untuk Pembayaran</td>
<td>: {{ $pembayaran->tagihan->biaya->jenis_biaya }}</td>
</tr>

<tr>
<td>Tanggal</td>
<td>: {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}</td>
</tr>

<tr>
<td>Keterangan</td>
<td>: {{ $pembayaran->keterangan ?? '-' }}</td>
</tr>

</table>

<table class="ttd">

<tr>
<td width="50%">Orang Tua / Wali</td>
<td width="50%">Petugas</td>
</tr>

<tr>
<td style="height:55px"></td>
<td></td>
</tr>

<tr>
<td>(........................)</td>
<td>( {{ $pembayaran->admin_penerima }} )</td>
</tr>

</table>

</div>

</td>
</tr>

</table>

</body>
</html>