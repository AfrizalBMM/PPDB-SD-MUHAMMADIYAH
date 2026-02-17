@extends('layouts.public')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    <div class="card">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            
            <h1 class="text-2xl font-bold text-slate-800">
                📋 Daftar Pendaftar PPDB
            </h1>

            <a href="{{ route('pendaftaran.public') }}"
            class="btn-primary px-5 py-2 text-sm">
                Daftar Siswa Baru
            </a>

        </div>


        <p class="text-sm text-slate-600 mb-6">
            Berikut adalah daftar calon peserta didik yang telah mendaftar.
        </p>

        <div class="overflow-x-auto">
            <table class="w-full border border-slate-200 text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Nama Siswa</th>
                        <th class="p-2 border">NIK</th>
                        <th class="p-2 border">Jenis Kelamin</th>
                        <th class="p-2 border">Nama Ibu</th>
                        <th class="p-2 border">No HP Ibu</th>
                        <th class="p-2 border">Voucher</th>
                        <th class="p-2 border">Tanggal Daftar</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>


                <tbody>
                    @forelse($siswa as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                        <td class="p-2 border font-medium">{{ $item->nama }}</td>
                        <td class="p-2 border">{{ $item->nik }}</td>
                        <td class="p-2 border capitalize">{{ $item->jenis_kelamin }}</td>

                        {{-- NAMA IBU --}}
                        <td class="p-2 border">
                            {{ optional($item->ibu)->nama ?? '-' }}
                        </td>

                        {{-- NO HP IBU --}}
                        <td class="p-2 border">
                            {{ optional($item->ibu)->no_hp ?? '-' }}
                        </td>

                        {{-- VOUCHER --}}
                        <td class="p-2 border">
                            {{ $item->tagihan->first()->kode_voucher ?? '-' }}
                        </td>

                        <td class="p-2 border">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                        <td class="p-2 border text-center">
                            @if($item->status == 'diterima')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                    Diterima
                                </span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                    Pending
                                </span>
                            @endif
                        </td>

                        {{-- BUTTON DETAIL --}}
                        <td class="p-2 border text-center">
                            <a href="{{ route('pendaftaran.detail', $item->id) }}"
                            class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                            Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-4 text-center text-slate-500">
                            Belum ada pendaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
