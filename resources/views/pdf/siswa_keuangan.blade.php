<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Export Keuangan' }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #111827; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 6px; vertical-align: top; }
        th { background: #f3f4f6; font-size: 10px; text-transform: uppercase; letter-spacing: .04em; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .wrap { white-space: normal; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 16%;">Nama</th>
                <th style="width: 8%;">JK</th>
                <th style="width: 14%;">No Registrasi</th>
                <th style="width: 12%;" class="text-right">Total Biaya</th>
                <th style="width: 12%;" class="text-right">Total Terbayar</th>
                <th style="width: 12%;" class="text-right">Total Kekurangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="wrap">{{ $row['nama'] }}</td>
                    <td>{{ $row['jenis_kelamin'] ?? '-' }}</td>
                    <td>{{ $row['no_registrasi'] }}</td>
                    <td class="text-right">{{ number_format($row['total_biaya'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row['total_terbayar'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row['total_kekurangan'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
