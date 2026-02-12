@extends('layouts.admin')

@section('page-title','Dashboard PPDB')

@section('content')
<div class="space-y-6">

    {{-- STATISTICS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

        <x-card>
            <p class="text-sm text-slate-500">Total Pendaftar</p>
            <h2 class="text-2xl font-bold text-primary">
                {{ $totalPendaftar }}
            </h2>
        </x-card>

        <x-card>
            <p class="text-sm text-slate-500">Diterima</p>
            <h2 class="text-2xl font-bold text-success">
                {{ $totalDiterima }}
            </h2>
        </x-card>

        <x-card>
            <p class="text-sm text-slate-500">Daftar Hari Ini</p>
            <h2 class="text-2xl font-bold">
                {{ $pendaftarHariIni }}
            </h2>
        </x-card>

        <x-card>
            <p class="text-sm text-slate-500">Pembayaran Hari Ini</p>
            <h2 class="text-2xl font-bold text-accent">
                Rp {{ number_format($pembayaranHariIni ?? 0,0,',','.') }}
            </h2>
        </x-card>

    </div>

    {{-- TABLE PENDAFTAR TERBARU --}}
    <x-card>

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800">
                Pendaftar Terbaru
            </h3>

            <a href="{{ route('admin.pendaftar.index') }}" class="text-sm text-primary hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No Registrasi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pendaftarTerbaru as $p)
                    <tr>
                        <td class="font-medium">{{ $p->nama }}</td>
                        <td>{{ optional($p->registration)->nomor_registrasi ?? '-' }}</td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-slate-500 py-6">
                            sayangnya Belum ada pendaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-card>

</div>
@endsection
