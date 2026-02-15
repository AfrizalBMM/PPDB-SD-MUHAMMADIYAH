@extends('layouts.admin')

@section('page-title','Pendaftar')

@section('content')
<div class="card">

    {{-- Search --}}
    <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2">
        <input
            name="q"
            value="{{ old('q', request('q')) }}"
            class="input sm:w-64"
            placeholder="Cari nama / NIK">
        <button class="btn-primary w-fit">
            Cari
        </button>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Tahun Ajaran</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">No Registrasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($siswa as $i => $s)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3">
                            {{ $siswa->firstItem() + $i }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $s->registration->tahunAjaran->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $s->nama }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $s->registration->nomor_registrasi ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @switch($s->registration->status ?? 'pending')

                                @case('diterima')
                                    <span class="badge-success">Diterima</span>
                                @break

                                @case('pending')
                                    <span class="badge-warning">Pending</span>
                                @break

                                @default
                                    <span class="badge-warning">Belum diproses</span>

                            @endswitch
                        </td>

                        <td class="px-4 py-3">
                            <a
                                href="{{ route('pendaftar.show', $s->id) }}"
                                class="text-primary font-medium hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Data pendaftar belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $siswa->withQueryString()->links() }}
    </div>

</div>
@endsection
