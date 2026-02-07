<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .nota {
            height: 48%;
            border: 1px solid #000;
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        .potong {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .label {
            width: 30%;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
        }
    </style>
</head>
<body>

@for($i = 1; $i <= 2; $i++)

<div class="nota">

    <div class="judul">
        KWITANSI PEMBAYARAN<br>
        SD MUHAMMADIYAH WONOREJO
    </div>

    <table>
        <tr>
            <td class="label">No Kwitansi</td>
            <td>
                : {{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}
            </td>
        </tr>
        <tr>
            <td>Sudah terima dari</td>
            <td>
                : {{ optional(optional($pembayaran->tagihan)->siswa)->nama ?? '-' }}
            </td>
        </tr>
        <tr>
            <td>No Registrasi</td>
            <td>
                : {{ optional(optional(optional($pembayaran->tagihan)->siswa)->registration)->nomor_registrasi ?? '-' }}
            </td>
        </tr>
        <tr>
            <td>Untuk Pembayaran</td>
            <td>
                : {{ strtoupper(optional(optional($pembayaran->tagihan)->biaya)->nama ?? '-') }}
            </td>
        </tr>
        <tr>
            <td class="total">Jumlah</td>
            <td class="total">
                : Rp {{ number_format($pembayaran->nominal_bayar ?? 0,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td>
                : {{ \Illuminate\Support\Str::title(terbilang($pembayaran->nominal_bayar ?? 0)) }} Rupiah
            </td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <td width="60%"></td>
            <td align="center">
                Wonorejo,
                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y') }}
                <br><br><br>
                {{ optional($pembayaran->admin)->name ?? '-' }}<br>
                Bendahara
            </td>
        </tr>
    </table>

</div>

@if($i === 1)
<div class="potong"></div>
@endif

@endfor

</body>
</html>
