@extends('layouts.admin')

@section('page-title','Dashboard Keuangan')

@section('content')

<div class="card">

    <h2 class="font-semibold text-slate-800 mb-4">
        Daftar Tagihan PPDB
    </h2>

    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th class="text-center">No Reg</th>
                    <th>Jenis Biaya</th>
                    <th class="text-right">Nominal</th>
                    <th class="text-right">Dibayar</th>
                    <th class="text-right">Kekurangan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tagihan as $t)
                    @php
                        $dibayar = $t->pembayaran->sum('nominal_bayar');
                        $sisa = ($t->total ?? 0) - $dibayar;
                    @endphp

                    <tr>
                        <td>
                            <div class="font-medium">
                                {{ optional($t->siswa)->nama ?? '-' }}
                            </div>

                            @if($t->kode_voucher)
                                <div class="text-xs text-green-600">
                                    Voucher: {{ $t->kode_voucher }}
                                </div>
                            @endif
                        </td>

                        <td class="text-center whitespace-nowrap">
                            {{ optional(optional($t->siswa)->registration)->nomor_registrasi ?? '-' }}
                        </td>

                        <td>
                            {{ optional($t->biaya)->nama ?? '-' }}
                        </td>

                        <td class="text-right whitespace-nowrap">
                            Rp {{ number_format($t->total ?? 0,0,',','.') }}
                        </td>

                        <td class="text-right whitespace-nowrap text-green-700">
                            Rp {{ number_format($dibayar,0,',','.') }}
                        </td>

                        <td class="text-right whitespace-nowrap">
                            Rp {{ number_format($sisa,0,',','.') }}
                        </td>

                        <td class="text-center">
                            @if($sisa <= 0)
                                <span class="badge-success">Lunas</span>
                            @else
                                <span class="badge-warning">Belum</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($sisa > 0)
                                <button
                                    onclick="openBayar(
                                        '{{ $t->id }}',
                                        '{{ optional($t->siswa)->nama }}',
                                        '{{ optional($t->biaya)->nama }}',
                                        '{{ $sisa }}'
                                    )"
                                    class="btn-primary text-xs">
                                    Bayar
                                </button>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-slate-500">
                            Data tagihan belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@include('keuangan.modal-bayar')

@endsection
