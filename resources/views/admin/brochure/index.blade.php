@extends('layouts.admin')

@section('page-title', 'Kelola Brosur')

@section('content')
<div class="space-y-6">
    {{-- Header & Search --}}
    <div class="card relative z-30 overflow-visible">
        <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div class="w-full md:max-w-xl">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input 
                        name="q" 
                        value="{{ request('q') }}" 
                        class="input pl-10" 
                        placeholder="Cari nama orang tua atau nomor WhatsApp...">
                </div>
            </div>

            <div class="flex items-stretch gap-2 w-full md:w-auto md:shrink-0">
                @if(request('q'))
                    <a href="{{ route('admin.brochure.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                        Reset Pencarian
                    </a>
                @endif
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-xs font-medium text-white hover:bg-blue-700 transition-colors">
                    Cari Data
                </button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead>
                    <tr>
                        <th class="w-16">No</th>
                        <th>Nama Orang Tua</th>
                        <th>Nomor WhatsApp</th>
                        <th>Waktu Unduh</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($downloads as $i => $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="text-slate-500 font-medium">
                                {{ $downloads->firstItem() + $i }}
                            </td>
                            <td>
                                <div class="font-semibold text-slate-800">{{ $item->name }}</div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->nomor_wa) }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    {{ $item->nomor_wa }}
                                </a>
                            </td>
                            <td>
                                <div class="text-xs text-slate-500 uppercase tracking-wider font-medium">
                                    {{ $item->created_at->translatedFormat('d F Y') }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="text-right">
                                <form action="{{ route('admin.brochure.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center p-2 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="bg-slate-50 rounded-2xl p-8 max-w-xs mx-auto border border-dashed border-slate-200">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <h4 class="text-slate-600 font-semibold mb-1">Tidak ada data</h4>
                                    <p class="text-slate-400 text-xs">Belum ada riwayat unduhan brosur saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($downloads->hasPages())
    <div class="mt-4">
        {{ $downloads->links() }}
    </div>
    @endif
</div>
@endsection
