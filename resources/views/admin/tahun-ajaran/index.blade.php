@extends('layouts.admin')
@section('title','Tahun Ajaran')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Tambah Tahun Ajaran
        </h2>

        <form
            method="POST"
            action="{{ route('tahun-ajaran.store') }}"
            class="flex flex-wrap items-end gap-4">
            @csrf

            <div>
                <label class="label">Nama Tahun Ajaran</label>
                <input
                    type="text"
                    name="nama"
                    placeholder="2025/2026"
                    class="input w-48"
                    required>
                <p class="text-xs text-slate-500 mt-1">
                    Contoh: <span class="font-medium">2025/2026</span>
                </p>
            </div>

            {{-- Default tidak aktif --}}
            <input type="hidden" name="aktif" value="0">

            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="aktif"
                    id="aktif"
                    value="1"
                    class="rounded border-slate-300 focus:ring-primary">
                <label for="aktif" class="text-sm text-slate-700">
                    Aktifkan sekarang
                </label>
            </div>

            <div>
                <button class="btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Daftar Tahun Ajaran
        </h2>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td class="font-medium">
                                {{ $item->nama }}
                            </td>

                            <td class="text-center">
                                @if($item->aktif)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-warning">Tidak Aktif</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="flex justify-center gap-3">

                                    {{-- Aktifkan --}}
                                    @if(!$item->aktif)
                                        <form
                                            method="POST"
                                            action="{{ route('tahun-ajaran.aktifkan',$item) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="text-xs text-blue-700 hover:underline">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hapus --}}
                                    <form
                                        method="POST"
                                        action="{{ route('tahun-ajaran.destroy',$item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-xs text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-slate-500">
                                Data tahun ajaran belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
