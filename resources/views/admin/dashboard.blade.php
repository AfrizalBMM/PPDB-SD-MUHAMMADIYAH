@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- STAT CARD --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

        <div class="card">
            <p class="text-sm text-slate-500">
                Total Pendaftar
            </p>
            <h2 class="text-2xl font-bold">
                {{ $totalPendaftar }}
            </h2>
        </div>

        <div class="card">
            <p class="text-sm text-slate-500">
                Diterima
            </p>
            <h2 class="text-2xl font-bold">
                {{ $totalDiterima }}
            </h2>
        </div>

        <div class="card">
            <p class="text-sm text-slate-500">
                Daftar Hari Ini
            </p>
            <h2 class="text-2xl font-bold">
                {{ $pendaftarHariIni }}
            </h2>
        </div>

        <div class="card">
            <p class="text-sm text-slate-500">
                Pembayaran Hari Ini
            </p>
            <h2 class="text-3xl font-bold">
                Rp {{ number_format($pembayaranHariIni ?? 0,0,',','.') }}
            </h2>
        </div>

    </div>

    {{-- PENDAFTAR TERBARU --}}
    <div class="card">
        <h3 class="font-semibold text-slate-800 mb-4">
            Pendaftar Terbaru
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">No Registrasi</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pendaftarTerbaru as $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium">
                            {{ $p->nama }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($p->registration)->nomor_registrasi ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $p->created_at->format('d-m-Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                            Belum ada pendaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
