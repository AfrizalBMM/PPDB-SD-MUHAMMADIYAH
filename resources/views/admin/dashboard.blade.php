@extends('layouts.admin')

@section('page-title','Dashboard PPDB')

@section('content')
<div class="space-y-6">

    {{-- STATISTICS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="card relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1.5 bg-primary opacity-80"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-textSecondary uppercase tracking-wider mb-1">Total Pendaftar</p>
                    <h2 class="text-3xl font-heading font-bold text-textPrimary">
                        {{ $totalPendaftar }}
                    </h2>
                </div>
                <div class="p-3 bg-primary/10 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-success flex items-center font-medium"><svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> +12%</span>
                <span class="text-muted ml-2 text-xs">dari bulan lalu</span>
            </div>
        </div>

        <div class="card relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1.5 bg-success opacity-80"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-textSecondary uppercase tracking-wider mb-1">Total Peserta Didik</p>
                    <h2 class="text-3xl font-heading font-bold text-textPrimary">
                        {{ $totalSiswa }}
                    </h2>
                </div>
                <div class="p-3 bg-success/10 rounded-xl text-success group-hover:bg-success group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-success flex items-center font-medium"><svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> +5%</span>
                <span class="text-muted ml-2 text-xs">dari bulan lalu</span>
            </div>
        </div>

        <div class="card relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1.5 bg-warning opacity-80"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-textSecondary uppercase tracking-wider mb-1">Daftar Hari Ini</p>
                    <h2 class="text-3xl font-heading font-bold text-textPrimary">
                        {{ $pendaftarHariIni }}
                    </h2>
                </div>
                <div class="p-3 bg-warning/10 rounded-xl text-warning group-hover:bg-warning group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-textSecondary flex items-center font-medium">Hari ini</span>
            </div>
        </div>

        <div class="card relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-1.5 bg-accent opacity-80"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-textSecondary uppercase tracking-wider mb-1">Pembayaran (Hari Ini)</p>
                    <h2 class="text-2xl font-heading font-bold text-textPrimary mt-1">
                        Rp {{ number_format($pembayaranHariIni ?? 0,0,',','.') }}
                    </h2>
                </div>
                <div class="p-3 bg-accent/10 rounded-xl text-accent group-hover:bg-accent group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-textSecondary flex items-center font-medium">Total masuk hari ini</span>
            </div>
        </div>

    </div>

    {{-- TABLE PENDAFTAR TERBARU --}}
    <div class="card p-0 overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center justify-between p-6 border-b border-border">
            <h3 class="font-heading font-bold text-lg text-textPrimary">
                Pendaftar Terbaru
            </h3>
            <a href="{{ route('pendaftar.index') }}" class="mt-2 sm:mt-0 btn-secondary flex items-center gap-2">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>No Registrasi</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarTerbaru as $p)
                    <tr>
                        <td class="font-medium text-textPrimary flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($p->nama, 0, 2) }}
                            </div>
                            {{ $p->nama }}
                        </td>
                        <td>
                            <span class="badge-info font-mono">
                                {{ optional($p->registration)->nomor_registrasi ?? '-' }}
                            </span>
                        </td>
                        <td class="text-textSecondary">
                            {{ $p->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <a href="#" class="btn-ghost px-3 py-1.5 text-xs inline-block">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-textSecondary py-8">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span>Belum ada pendaftar terbaru</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-border bg-background/40">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                    @foreach(request()->except(['page', 'per_page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label for="perPageDashboard">Tampilkan</label>
                    <select id="perPageDashboard" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                        @foreach([10,20,50,100] as $size)
                            <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 10) === $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>data</span>
                </form>
                {{ $pendaftarTerbaru->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
