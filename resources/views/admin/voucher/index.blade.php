@extends('layouts.admin')

@section('title','Master Voucher')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h2 class="font-semibold text-slate-800 mb-4">
            Tambah Voucher
        </h2>

        <form
            method="POST"
            action="{{ route('voucher.store') }}"
            class="grid md:grid-cols-3 gap-4"
            id="voucherForm">
            @csrf

            <div>
                <label class="label">Nama Voucher</label>
                <input
                    name="nama"
                    class="input"
                    placeholder="Nama Voucher"
                    required>
            </div>

            <div>
                <label class="label">Jenis Biaya</label>
                <select name="jenis_biaya" class="input" required>
                    <option value="">Pilih</option>
                    <option value="pendaftaran">Pendaftaran</option>
                    <option value="daftar_ulang">Daftar Ulang</option>
                    <option value="udp">UDP</option>
                </select>
            </div>

            <div>
                <label class="label">Diskon Nominal (Rp)</label>
                <input
                    type="number"
                    name="diskon_nominal"
                    class="input"
                    placeholder="Contoh: 50000"
                    min="0"
                    required>
            </div>

            <div>
                <label class="label">Maksimal Penggunaan</label>
                <input
                    type="number"
                    name="maks_penggunaan"
                    class="input"
                    placeholder="Contoh: 10"
                    min="1"
                    required>
            </div>

            <div>
                <label class="label">Tanggal Mulai</label>
                <input
                    type="date"
                    name="tanggal_mulai"
                    class="input"
                    required>
            </div>

            <div>
                <label class="label">Tanggal Selesai</label>
                <input
                    type="date"
                    name="tanggal_selesai"
                    class="input"
                    required>
            </div>

            <div class="md:col-span-3">
                <button
                    type="submit"
                    class="btn-primary"
                    id="submitVoucherBtn">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <!-- Judul -->
            <h2 class="font-semibold text-slate-800 text-lg">
                Daftar Voucher
            </h2>

            <!-- Hapus Semua -->
            <button
                onclick="document.getElementById('modalDeleteAll').classList.remove('hidden')"
                class="btn-danger text-sm">
                🗑 Hapus Semua Voucher
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-right">Diskon</th>
                        <th class="px-4 py-3 text-center">Dipakai</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($vouchers as $v)
                <tr class="hover:bg-slate-50 transition">
                    <!-- Kode -->
                    <td class="px-4 py-3 font-mono">
                        {{ $v->kode }}
                    </td>

                    <!-- Nama + Tanggal Mulai & Selesai -->
                    <td class="px-4 py-3 font-medium">
                        {{ $v->nama }}
                        <div class="mt-1 text-xs text-slate-500 flex flex-wrap gap-1">
                            <span class="badge-info">{{ date('d M Y', strtotime($v->tanggal_mulai)) }}</span>
                            -
                            <span class="badge-danger">{{ date('d M Y', strtotime($v->tanggal_selesai)) }}</span>
                        </div>
                    </td>

                    <!-- Jenis Biaya -->
                    <td class="px-4 py-3">
                        {{ ucfirst($v->jenis_biaya) }}
                    </td>

                    <!-- Diskon -->
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        Rp {{ number_format($v->diskon_nominal,0,',','.') }}
                    </td>

                    <!-- Maks Penggunaan -->
                    <td class="px-4 py-3 text-center">
                        {{ $v->digunakan }} / {{ $v->maks_penggunaan }}
                    </td>

                    <!-- Status -->
                    <td class="px-4 py-3 text-center">
                        @if($v->masihBerlaku())
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-warning">Tidak Aktif</span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-3">
                            <form method="POST" action="{{ route('voucher.toggle',$v) }}">
                                @csrf
                                <button type="submit" class="text-xs text-blue-700 hover:underline">
                                    Toggle
                                </button>
                            </form>

                            <form method="POST" action="{{ route('voucher.destroy',$v) }}">
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
                    <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                        Data voucher belum tersedia
                    </td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>


{{-- MODAL HAPUS SEMUA VOUCHER --}}
<div id="modalDeleteAll" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 space-y-4">
        <h3 class="text-lg font-semibold text-red-600">Konfirmasi Hapus Semua Voucher</h3>
        <p>Apakah Anda yakin ingin menghapus <strong>semua data voucher</strong>? Aksi ini tidak bisa dibatalkan.</p>

        <div class="flex justify-end gap-3 mt-4">
            <button type="button" 
                onclick="document.getElementById('modalDeleteAll').classList.add('hidden')"
                class="btn-secondary">
                Batal
            </button>

            <form method="POST" action="{{ route('voucher.destroyAll') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    Hapus Semua
                </button>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // FORM SIMPAN VOUCHER
    const voucherForm = document.getElementById('voucherForm');
    const submitBtn = document.getElementById('submitVoucherBtn');

    voucherForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Loading...';
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    // SEMUA FORM HAPUS VOUCHER
    const deleteForms = document.querySelectorAll('form[action*="voucher.destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button[type="submit"]');
            if(btn){
                btn.disabled = true;
                btn.textContent = 'Menghapus...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });

    // FORM HAPUS SEMUA VOUCHER
    const deleteAllForm = document.querySelector('form[action="{{ route("voucher.destroyAll") }}"]');
    if(deleteAllForm){
        deleteAllForm.addEventListener('submit', function() {
            const btn = deleteAllForm.querySelector('button[type="submit"]');
            if(btn){
                btn.disabled = true;
                btn.textContent = 'Menghapus semua...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    }
});

</script>

@endsection
