@extends('layouts.admin')

@section('page-title','Dashboard Keuangan')

@section('content')

<div class="card">

    <h2 class="font-semibold text-slate-800 mb-4">
        Daftar Tagihan PPDB
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left">Siswa</th>
                    <th class="px-4 py-3 text-center">No Reg</th>
                    <th class="px-4 py-3 text-left">Jenis Biaya</th>
                    <th class="px-4 py-3 text-right">Nominal</th>
                    <th class="px-4 py-3 text-right">Dibayar</th>
                    <th class="px-4 py-3 text-right">Kekurangan</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($tagihan as $t)
                    @php
                        $dibayar = $t->pembayaran->sum('nominal_bayar');
                        $sisa = $t->total - $dibayar;
                    @endphp

                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3">
                            <div class="font-medium">
                                {{ optional($t->siswa)->nama ?? '-' }}
                            </div>

                            @if($t->kode_voucher)
                                <div class="text-xs text-green-600">
                                    Voucher: {{ $t->kode_voucher }}
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            {{ optional(optional($t->siswa)->registration)->nomor_registrasi ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ optional($t->biaya)->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            Rp {{ number_format($t->total ?? 0,0,',','.') }}
                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap text-green-700">
                            Rp {{ number_format($dibayar,0,',','.') }}
                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            Rp {{ number_format($sisa,0,',','.') }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($sisa <= 0)
                                <span class="badge-success">Lunas</span>
                            @else
                                <span class="badge-warning">Belum</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
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
                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">
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
