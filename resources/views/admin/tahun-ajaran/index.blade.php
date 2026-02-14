@extends('layouts.admin')
@section('title','Tahun Ajaran')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="grid md:grid-cols-2 gap-6">

        {{-- KIRI: Daftar Tahun Ajaran --}}
        <div class="card">
            <h2 class="font-semibold text-slate-800 mb-4">
                Daftar Tahun Ajaran
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($data as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-medium">
                                    {{ $item->nama }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($item->aktif)
                                        <span class="badge-success">Aktif</span>
                                    @else
                                        <span class="badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        @if(!$item->aktif)
                                            <form method="POST" action="{{ route('tahun-ajaran.aktifkan',$item) }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-blue-700 hover:underline">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('tahun-ajaran.destroy',$item) }}" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                    Data tahun ajaran belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KANAN: Form Tambah Tahun Ajaran --}}
        <div class="card">
            <h2 class="font-semibold text-slate-800 mb-4">
                Tambah Tahun Ajaran
            </h2>

            <form method="POST" action="{{ route('tahun-ajaran.store') }}" class="flex flex-col gap-4">
                @csrf

                <div>
                    <label class="label">Nama Tahun Ajaran</label>
                    <input type="text" name="nama" placeholder="2025/2026" class="input w-full" required>
                    <p class="text-xs text-slate-500 mt-1">
                        Contoh: <span class="font-medium">2025/2026</span>
                    </p>
                </div>

                {{-- Default tidak aktif --}}
                <input type="hidden" name="aktif" value="0">

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="aktif" id="aktif" value="1" class="rounded border-slate-300 focus:ring-primary">
                    <label for="aktif" class="text-sm text-slate-700">
                        Aktifkan sekarang
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection
