@extends('layouts.admin')

@section('title','Laporan Keuangan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- FILTER --}}
    <div class="card">
        <form class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="label">Tanggal Mulai</label>
                <input
                    type="date"
                    name="mulai"
                    value="{{ $mulai }}"
                    class="input">
            </div>

            <div>
                <label class="label">Tanggal Selesai</label>
                <input
                    type="date"
                    name="selesai"
                    value="{{ $selesai }}"
                    class="input">
            </div>

            <div class="flex items-end">
                <button class="btn-primary">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Total Pendapatan:
            <span class="text-green-700">
                Rp {{ number_format($total ?? 0,0,',','.') }}
            </span>
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                        <th class="px-4 py-3 text-left">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pembayaran as $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d-m-Y') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ucfirst(optional(optional($p->tagihan)->biaya)->jenis_biaya ?? '-') }}
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap font-medium">
                            Rp {{ number_format($p->nominal_bayar ?? 0,0,',','.') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($p->admin)->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                            Tidak ada data pembayaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
