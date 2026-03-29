@extends('layouts.admin')

@section('title','Siswa Kelas 1')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="card">

        <h2 class="text-lg font-semibold mb-4">
            Daftar Siswa Kelas 1
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr class="border-b">
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">No Registrasi</th>
                        <th class="px-4 py-3 text-left">JK</th>
                        <th class="px-4 py-3 text-left">Tahun Ajaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($siswa as $s)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium">
                            {{ $s->nama }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional($s->registration)->nomor_registrasi ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ui_label($s->jenis_kelamin) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ optional(optional($s->registration)->tahunAjaran)->nama ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                            Data siswa belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
