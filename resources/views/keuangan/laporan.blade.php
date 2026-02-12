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
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th class="text-right">Nominal</th>
                        <th>Admin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pembayaran as $p)
                    <tr>
                        <td class="whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ ucfirst(optional(optional($p->tagihan)->biaya)->jenis_biaya ?? '-') }}
                        </td>

                        <td class="text-right whitespace-nowrap font-medium">
                            Rp {{ number_format($p->nominal_bayar ?? 0,0,',','.') }}
                        </td>

                        <td>
                            {{ optional($p->admin)->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-slate-500">
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
