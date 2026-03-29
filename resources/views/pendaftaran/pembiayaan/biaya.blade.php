@extends('layouts.public')

@section('content')

    <div class="max-w-5xl mx-auto px-6 py-8">

        @if(session('success'))
            <div id="alertSuccess" class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">
                ✅ {{ session('success') }}
            </div>

            <script>
                setTimeout(function () {
                    document.getElementById('alertSuccess').style.display = 'none';
                }, 3000);
            </script>
        @endif
        <div class="md:col-span-2 card">

            <h2 class="font-semibold text-lg text-slate-800 mb-4">
                Rincian Pembiayaan Calon Siswa
            </h2>

            <div class="bg-yellow-50 border border-yellow-200 p-3 text-xs rounded mb-6">
                ⚠️ Informasi pembiayaan ini bersifat RAHASIA, hanya untuk panitia dan orang tua/wali siswa.
            </div>


            {{-- ===============================
            DATA SISWA
            =============================== --}}
            <div class="overflow-x-auto mb-8">

                <h3 class="font-semibold mb-3">
                    Informasi Siswa
                </h3>

                <table class="w-full text-sm border border-slate-200 rounded-lg">

                    <tbody>

                        <tr class="bg-slate-50">
                            <td class="p-3 w-48 font-medium">
                                Nama Siswa
                            </td>

                            <td class="p-3">
                                {{ $siswa->nama }}
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3 font-medium">
                                No Registrasi
                            </td>

                            <td class="p-3">
                                {{ optional($siswa->registration)->nomor_registrasi ?? '-' }}
                            </td>
                        </tr>

                        <tr class="bg-slate-50">
                            <td class="p-3 font-medium">
                                Nama Ibu
                            </td>

                            <td class="p-3">
                                {{ optional($siswa->ibu)->nama ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3 font-medium">
                                No HP Ibu
                            </td>

                            <td class="p-3">
                                {{ optional($siswa->ibu)->no_hp ?? '-' }}
                            </td>
                        </tr>

                        <tr class="bg-slate-50">
                            <td class="p-3 font-medium">
                                Voucher
                            </td>

                            <td class="p-3">
                                @php
                                    $voucher = optional($siswa->registration)->voucher;
                                @endphp
                                @if($voucher)
                                    <span class="badge-success">{{ $voucher->kode }}</span>
                                    dengan potongan <strong>Rp
                                        {{ number_format($voucher->diskon_nominal, 0, ',', '.') }}</strong>
                                    berlaku untuk biaya <strong>{{ ui_label($voucher->jenis_biaya) }}</strong>
                                @else
                                    <i class="text-slate-400">Tidak dapat Voucher</i>
                                @endif
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>



            {{-- ===============================
            RINCIAN BIAYA
            =============================== --}}
            <div class="overflow-x-auto">

                <h3 class="font-semibold mb-3">
                    Rincian Biaya
                </h3>

                <table class="w-full text-sm border border-slate-200 rounded-lg">

                    <tbody>

                        @php
                            $totalBiaya = 0;
                            $totalKekurangan = 0;
                        @endphp

                        @foreach($siswa->tagihan as $tagihan)

                            @php
                                $totalBiaya += $tagihan->total;
                                $totalKekurangan += $tagihan->sisa;
                            @endphp


                            <tr class="bg-slate-100">
                                <th class="p-3 border text-left">
                                    Jenis Biaya
                                </th>

                                <th class="p-3 border text-right">
                                    Nominal
                                </th>

                                <th class="p-3 border text-right">
                                    Diskon
                                </th>

                                <th class="p-3 border text-right">
                                    Total
                                </th>

                                <th class="p-3 border text-right">
                                    Kekurangan
                                </th>

                                <th class="p-3 border text-center">
                                    Status
                                </th>

                                <th class="p-3 border text-center">
                                    Aksi
                                </th>
                            </tr>

                            {{-- ROW TAGIHAN --}}
                            <tr class="{{ $loop->even ? 'bg-slate-50' : '' }}">

                                <td class="p-3 border">
                                    {{ ui_label($tagihan->biaya->jenis_biaya) }}
                                </td>

                                <td class="p-3 border text-right">
                                    Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                                </td>

                                <td class="p-3 border text-right text-green-600">
                                    Rp {{ number_format($tagihan->diskon, 0, ',', '.') }}
                                </td>

                                <td class="p-3 border text-right font-medium">
                                    Rp {{ number_format($tagihan->total, 0, ',', '.') }}
                                </td>

                                <td class="p-3 border text-right">
                                    Rp {{ number_format($tagihan->sisa, 0, ',', '.') }}
                                </td>

                                <td class="p-3 border text-center">

                                    @if($tagihan->is_lunas)

                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                            Lunas
                                        </span>

                                    @else

                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                                            Belum Lunas
                                        </span>

                                    @endif

                                </td>

                                <td class="p-3 border text-center">

                                    @if(!$tagihan->is_lunas)

                                        <button onclick="openBayarModal({{ $tagihan->id }}, {{ $tagihan->sisa }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded">
                                            Bayar
                                        </button>

                                    @else

                                        <button class="bg-gray-300 text-gray-600 text-xs px-3 py-1 rounded cursor-not-allowed"
                                            disabled>
                                            Lunas
                                        </button>

                                    @endif

                                </td>

                            </tr>

                            {{-- BREAKDOWN CICILAN --}}
                            @if($tagihan->pembayaran->count())

                                <tr>

                                    <td colspan="7" class="border bg-slate-50 p-3">

                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-xs font-semibold">
                                                Riwayat Cicilan
                                            </div>
                                            <button type="button"
                                                onclick="toggleRiwayatCicilan('{{ $tagihan->id }}')"
                                                id="riwayatToggleBtn{{ $tagihan->id }}"
                                                aria-label="Toggle riwayat cicilan"
                                                class="text-slate-600 hover:text-slate-900 p-1.5 border border-slate-300 rounded-md bg-white transition-colors">
                                                <svg class="w-4 h-4 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.512a.75.75 0 0 1-1.08 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="riwayatContent{{ $tagihan->id }}" class="hidden">
                                            <table class="w-full text-xs">

                                                <thead>
                                                    <tr class="text-slate-600">

                                                        <th class="text-left py-2">
                                                            Tanggal Bayar
                                                        </th>

                                                        <th class="text-left py-1">
                                                            Nominal
                                                        </th>

                                                        <th class="text-left py-1">
                                                            Metode
                                                        </th>

                                                        <th class="text-left py-1">
                                                            Penerima
                                                        </th>

                                                        <th class="text-left py-1">
                                                            Keterangan
                                                        </th>

                                                        <th class="text-center py-1">
                                                            Aksi
                                                        </th>

                                                    </tr>
                                                </thead>


                                                <tbody>

                                                    @foreach($tagihan->pembayaran as $bayar)

                                                        <tr class="border-t">

                                                            <td class="py-1">
                                                                {{ $bayar->tanggal_bayar->format('d M Y') }}
                                                            </td>

                                                            <td class="py-1 text-left">
                                                                Rp {{ number_format($bayar->nominal_bayar, 0, ',', '.') }}
                                                            </td>

                                                            <td class="py-1">
                                                                {{ $bayar->metode ? ucfirst($bayar->metode) : '-' }}
                                                            </td>

                                                            <td class="py-1">
                                                                {{ $bayar->admin_penerima ?? '-' }}
                                                            </td>

                                                            <td class="py-1">
                                                                {{ $bayar->keterangan ?? '-' }}
                                                            </td>

                                                            <td class="py-1 text-center">

                                                                <button type="button"
                                                                    onclick="openNotaModal('{{ route('pembayaran.public.nota.post', $bayar->id) }}')"
                                                                    title="Cetak Nota"
                                                                    aria-label="Cetak Nota"
                                                                    class="text-blue-600 hover:text-blue-800 inline-flex items-center justify-center p-1 rounded">
                                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                                        <path d="M7 9V4h10v5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <rect x="6" y="14" width="12" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M5 10h14a2 2 0 0 1 2 2v4h-3" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M6 17H5a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </button>

                                                                <button type="button"
                                                                    onclick='openEditModal(@json(route("pembayaran.public.update", $bayar->id)), @json($bayar->tanggal_bayar->format("Y-m-d")), {{ (int) $bayar->nominal_bayar }}, @json($bayar->metode), @json($bayar->keterangan), @json($bayar->admin_penerima), {{ (int) $tagihan->sisa + (int) $bayar->nominal_bayar }})'
                                                                    title="Edit"
                                                                    aria-label="Edit"
                                                                    class="text-amber-500 hover:text-amber-700 inline-flex items-center justify-center p-1 rounded ml-2">
                                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                                        <path d="M4 20h4l10-10-4-4L4 16v4Z" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M13 7l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M15 5l2-2a1.5 1.5 0 0 1 2 2l-2 2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </button>

                                                                <button type="button"
                                                                    onclick="openHapusModal('{{ route('pembayaran.public.destroy', $bayar->id) }}', '{{ $bayar->tanggal_bayar->format('d M Y') }}', '{{ number_format($bayar->nominal_bayar, 0, ',', '.') }}')"
                                                                    title="Hapus"
                                                                    aria-label="Hapus"
                                                                    class="text-red-500 hover:text-red-700 inline-flex items-center justify-center p-1 rounded ml-2">
                                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                                        <path d="M4 7h16" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M10 3h4" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M8 7l1 13h6l1-13" stroke-linecap="round" stroke-linejoin="round"/>
                                                                        <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </button>

                                                            </td>

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                            </table>
                                        </div>

                                    </td>

                                </tr>

                            @endif

                            <tr>
                                <td colspan="7" style="border: none; height: 10px; padding: 0;"></td>
                            </tr>


                        @endforeach

                    </tbody>



                    <tfoot>

                        <tr class="bg-slate-100 font-semibold">

                            <td class="p-3 border text-right" colspan="3">
                                Total Biaya
                            </td>

                            <td class="p-3 border text-right">
                                Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                            </td>

                            <td colspan="3" class="border"></td>

                        </tr>

                        <tr class="bg-red-50 font-semibold">

                            <td class="p-3 border text-right" colspan="4">
                                Total Kekurangan
                            </td>

                            <td class="p-3 border text-right text-red-600">
                                Rp {{ number_format($totalKekurangan, 0, ',', '.') }}
                            </td>

                            <td colspan="2" class="border p-2 text-right">
                                <button type="button"
                                    onclick="openNotaModal('{{ route('pendaftaran.biaya.nota.post', $siswa->id) }}')"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg font-semibold shadow-sm">
                                    Cetak Rincian Biaya
                                </button>
                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>



            <div class="mt-6">

                <a href="{{ route('pendaftaran.list') }}" class="btn-primary inline-block">
                    Kembali
                </a>

            </div>

        </div>

    </div>

    {{-- ===============================
    MODAL PEMBAYARAN
    =============================== --}}
    <div id="modalBayar" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

            <h3 class="font-semibold text-lg mb-4">
                Pembayaran Cicilan
            </h3>

            <form id="formBayar" onsubmit="handleFormPaymentSubmit(event)">
                @csrf

                <input type="hidden" name="tagihan_siswa_id" id="tagihan_id">

                {{-- SISA TAGIHAN --}}
                <div class="mb-3">

                    <label class="block text-sm font-medium mb-1">
                        Sisa Tagihan
                    </label>

                    <input type="text" id="sisa_tagihan" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>

                </div>

                {{-- TANGGAL BAYAR --}}
                <div class="mb-3">

                    <label class="block text-sm font-medium mb-1">
                        Tanggal Bayar
                    </label>

                    <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}"
                        class="w-full border rounded px-3 py-2" required>

                </div>

                {{-- NOMINAL BAYAR --}}
                <div class="mb-3">

                    <label class="block text-sm font-medium mb-1">
                        Nominal Bayar
                    </label>

                    <div class="flex">

                        {{-- PREFIX RP --}}
                        <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-100 rounded-l">
                            Rp.
                        </span>

                        {{-- INPUT DISPLAY --}}
                        <input type="text" id="nominal_display"
                            class="w-full border border-gray-300 rounded-r px-3 py-2 focus:outline-none" placeholder="0"
                            onkeyup="formatRupiah(this)" required>

                    </div>

                    {{-- INPUT ANGKA ASLI --}}
                    <input type="hidden" name="nominal_bayar" id="nominal_bayar">

                    <p id="nominalErrorText" class="mt-1 text-xs text-red-600 hidden">
                        jumlah melebihi sisa tagihan
                    </p>

                    {{-- QUICK BADGE NOMINAL --}}
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" onclick="applyQuickNominal(50000)"
                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 border border-green-300 rounded-full hover:bg-green-200">
                            50.000
                        </button>
                        <button type="button" onclick="applyQuickNominal(100000)"
                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 border border-green-300 rounded-full hover:bg-green-200">
                            100.000
                        </button>
                        <button type="button" onclick="applyQuickNominal(150000)"
                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 border border-green-300 rounded-full hover:bg-green-200">
                            150.000
                        </button>
                        <button type="button" onclick="applyQuickNominal(200000)"
                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 border border-green-300 rounded-full hover:bg-green-200">
                            200.000
                        </button>
                        <button type="button" onclick="applyQuickNominal(500000)"
                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 border border-green-300 rounded-full hover:bg-green-200">
                            500.000
                        </button>
                    </div>

                </div>

                {{-- METODE --}}
                <div class="mb-3">

                    <label class="block text-sm font-medium mb-1">
                        Metode Pembayaran
                    </label>

                    <select name="metode" class="w-full border rounded px-3 py-2">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>

                </div>

                {{-- KETERANGAN --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium mb-1">
                        Keterangan
                    </label>

                    <input type="text" name="keterangan" class="w-full border rounded px-3 py-2" placeholder="opsional">

                </div>

                <div class="mb-3">

                    <label class="block text-sm font-medium mb-1">
                        Penerima Pembayaran (Wajib)
                    </label>

                    <input type="text" name="admin_penerima" class="w-full border rounded px-3 py-2"
                        placeholder="Nama petugas" required>

                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-2">

                    <button type="button" onclick="closeBayarModal()" class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button type="submit" id="btnSimpanPembayaran" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan Pembayaran
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ===============================
    MODAL KONFIRMASI HAPUS CICILAN
    =============================== --}}
    <div id="modalHapus" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 text-center">

            <div class="text-red-500 text-5xl mb-4">⚠️</div>

            <h3 class="font-semibold text-lg mb-2 text-slate-800">
                Hapus Riwayat Cicilan?
            </h3>

            <p class="text-sm text-slate-500 mb-1">
                Anda akan menghapus cicilan:
            </p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-sm">
                <p>Tanggal: <strong id="hapusTanggal"></strong></p>
                <p>Nominal: <strong id="hapusNominal"></strong></p>
            </div>

            <p class="text-xs text-red-600 mb-4">
                Tindakan ini tidak dapat dibatalkan. Sisa tagihan akan dihitung ulang secara otomatis.
            </p>

            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')

                <div class="mb-4 text-left">
                    <label class="block text-sm font-medium mb-1 text-slate-700">
                        Nama Petugas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="admin_penghapus" id="adminPenghapus"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300"
                        placeholder="Masukkan nama petugas" required>
                </div>

                <div class="flex justify-center gap-3">

                    <button type="button" onclick="closeHapusModal()"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Batal
                    </button>

                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                        Ya, Hapus
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ===============================
    MODAL EDIT CICILAN
    =============================== --}}
    <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

            <h3 class="font-semibold text-lg mb-4 text-slate-800">
                Edit Riwayat Cicilan
            </h3>

            <form id="formEdit" method="POST" onsubmit="return confirmEditCicilan()">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 text-slate-700">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" id="editTanggalBayar"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 text-slate-700">Nominal Bayar</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-100 rounded-l text-sm">
                            Rp.
                        </span>
                        <input type="text" id="editNominalDisplay"
                            class="w-full border border-gray-300 rounded-r px-3 py-2 text-sm" placeholder="0"
                            oninput="formatRupiahEdit(this)" required>
                    </div>
                    <input type="hidden" name="nominal_bayar" id="editNominalBayar">
                    <p id="editNominalErrorText" class="mt-1 text-xs text-red-600 hidden">
                        jumlah melebihi sisa tagihan / jenis biaya
                    </p>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 text-slate-700">Metode Pembayaran</label>
                    <select name="metode" id="editMetode" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="">- Pilih Metode -</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 text-slate-700">Keterangan</label>
                    <input type="text" name="keterangan" id="editKeterangan"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="opsional">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1 text-slate-700">Penerima Pembayaran</label>
                    <input type="text" name="admin_penerima" id="editAdminPenerima"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-slate-700">
                        Nama Petugas Pengubah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="admin_pengubah" id="editAdminPengubah"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Masukkan nama petugas" required>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>

    </div>

    {{-- ===============================
    MODAL CETAK NOTA (INPUT PANITIA)
    =============================== --}}
    <div id="modalNota" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

            <h3 class="font-semibold text-lg mb-4 text-slate-800">
                🖨️ Cetak Kuitansi Pembayaran
            </h3>

            <form id="formNota" method="POST" target="_blank">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-slate-700">
                        Nama Panitia PPDB <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="panitia" id="inputPanitia"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                        placeholder="Masukkan nama panitia" required>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeNotaModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Cetak Nota
                    </button>
                </div>

            </form>

        </div>

    </div>

    <script>

        // ---- Session Status Check (OUTPUT BOOLEAN, NOT STRING) ----
        const userHasAksesPembayaran = @if(session('akses_pembayaran')) true @else false @endif;
        console.log('📋 Page loaded - userHasAksesPembayaran =', userHasAksesPembayaran, 'type:', typeof userHasAksesPembayaran);

        // ---- Handle Form Pembayaran Submit via AJAX ----
        function handleFormPaymentSubmit(event) {
            event.preventDefault();

            const nominalBayar = Number(document.getElementById('nominal_bayar').value || 0);
            const nominalValid = validateNominalAgainstSisa(nominalBayar);

            if (!nominalValid) {
                document.getElementById('nominal_display').focus();
                return false;
            }
            
            console.log('Form pembayaran submit - checking session...', userHasAksesPembayaran);
            
            // Jika user BELUM punya akses pembayaran
            if (!userHasAksesPembayaran) {
                console.log('❌ No akses_pembayaran session - showing password modal');
                
                // Set redirect ke halaman biaya saat ini setelah password verified
                const currentUrl = window.location.href;
                bukaPasswordModal(currentUrl);
                
                // Setelah user verifikasi password dan halaman reload, 
                // user bisa submit form pembayaran lagi dengan session yang sudah ada
                return false;
            }
            
            // ✅ User sudah punya akses - submit form via AJAX
            console.log('✅ Has akses_pembayaran session - submitting form via AJAX');
            submitFormPembayaranAjax();
        }

        // ---- Submit Pembayaran Form via AJAX ----
        function submitFormPembayaranAjax() {
            const form = document.getElementById('formBayar');
            const formData = new FormData(form);
            const btn = document.getElementById('btnSimpanPembayaran');
            
            console.log('📤 Submitting form data via AJAX...');
            console.log('Form data entries:', Array.from(formData.entries()));
            
            // Extract CSRF token dari form atau meta tag
            let csrfToken = form.querySelector('input[name="_token"]')?.value;
            if (!csrfToken) {
                csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            }
            console.log('🔐 CSRF Token found:', !!csrfToken, '(' + (csrfToken ? csrfToken.substring(0,10) + '...' : 'none') + ')');
            
            // Ensure CSRF token in FormData
            if (csrfToken) {
                formData.set('_token', csrfToken);  // Use set instead of append to avoid duplicates
                console.log('✅ CSRF token added to FormData');
            }
            
            // Disable button
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
            
            console.log('🔐 Sending request with credentials: include');
            
            fetch('{{ route("pembayaran.public.store") }}', {
                method: 'POST',
                body: formData,
                credentials: 'include',  // ← CRITICAL: Send session cookies!
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken || '',  // ← Add CSRF to header as well
                }
            })
            .then(response => {
                console.log('📨 Response received');
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                console.log('Response type:', response.type);
                console.log('Response url:', response.url);
                
                // Log response headers
                console.log('--- Response Headers ---');
                response.headers.forEach((value, name) => {
                    if (name.toLowerCase().includes('csrf') || 
                        name.toLowerCase().includes('set-cookie') || 
                        name.toLowerCase().includes('cookie')) {
                        console.log(`${name}: ${value}`);
                    }
                });
                
                // Parse JSON dulu
                return response.json().then(data => {
                    console.log('📦 Response JSON data:', data);
                    console.log('Response keys:', Object.keys(data));
                    console.log('Response full:', JSON.stringify(data, null, 2));
                    
                    // Handle non-2xx status codes
                    if (!response.ok) {
                        console.warn('❌ Response not ok. Status:', response.status);
                        console.warn('Error details:', data);
                        
                        // If 403 Unauthorized (CSRF fail atau akses ditolak)
                        if (response.status === 403) {
                            console.log('❌ Status 403 - Forbidden/Unauthorized');
                            console.warn('This could be CSRF token issue or session expired');
                            alert('⚠️ Error 403: Unauthorized.\n\nPossible causes:\n- CSRF token invalid\n- Session expired\n- No permission\n\nSilakan verifikasi password panitia kembali.');
                            bukaPasswordModal(window.location.href);
                            throw new Error('403 Forbidden - showing password modal');
                        }
                        
                        // Other error responses
                        throw new Error(data.error || data.message || `Server error (${response.status})`);
                    }
                    
                    // Success response (2xx status)
                    if (data.success) {
                        console.log('✅ Success response received');
                        return data;
                    }
                    
                    // No success flag - treat as error
                    console.warn('❌ Response has no success flag or success=false');
                    throw new Error(data.error || data.message || 'Unknown error');
                });
            })
            .then(data => {
                // Only reach here if success = true
                console.log('✅ Payment saved successfully!');
                console.log('Response data:', data);
                window.location.reload();
            })
            .catch(error => {
                console.error('❌ Error submitting form:', error);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                
                // Don't show alert if it's the session expired error (password modal already shown)
                if (!error.message.includes('Session expired')) {
                    alert('❌ Terjadi Error:\n' + error.message);
                }
            })
            .finally(() => {
                console.log('🏁 AJAX submit finalized');
                // Re-enable button
                btn.disabled = false;
                btn.textContent = 'Simpan Pembayaran';
            });
        }

        // ---- Modal Pembayaran ----
        let currentSisaTagihan = 0;

        function openBayarModal(tagihanId, sisa) {
            document.getElementById('modalBayar').classList.remove('hidden');
            document.getElementById('modalBayar').classList.add('flex');

            currentSisaTagihan = Number(sisa) || 0;

            document.getElementById('tagihan_id').value = tagihanId;

            document.getElementById('sisa_tagihan').value =
                'Rp ' + new Intl.NumberFormat('id-ID').format(sisa);

            document.getElementById('nominal_bayar').max = sisa;
            document.getElementById('nominal_bayar').value = '';
            document.getElementById('nominal_display').value = '';
            validateNominalAgainstSisa(0);
        }

        function closeBayarModal() {
            document.getElementById('modalBayar').classList.add('hidden');
            document.getElementById('modalBayar').classList.remove('flex');
        }

        function formatRupiah(input) {
            let angka = input.value.replace(/\D/g, '');

            document.getElementById('nominal_bayar').value = angka;

            validateNominalAgainstSisa(Number(angka || 0));

            let formatted = new Intl.NumberFormat('id-ID').format(angka);

            input.value = formatted;
        }

        function validateNominalAgainstSisa(nominal) {
            const errorEl = document.getElementById('nominalErrorText');
            const isExceeded = Number(nominal || 0) > Number(currentSisaTagihan || 0);

            if (isExceeded) {
                errorEl.classList.remove('hidden');
                return false;
            }

            errorEl.classList.add('hidden');
            return true;
        }

        function applyQuickNominal(nominal) {
            const selectedNominal = Number(nominal) || 0;
            const finalNominal = currentSisaTagihan > 0
                ? Math.min(selectedNominal, currentSisaTagihan)
                : selectedNominal;

            const nominalInput = document.getElementById('nominal_bayar');
            const nominalDisplay = document.getElementById('nominal_display');

            nominalInput.value = finalNominal;
            nominalDisplay.value = new Intl.NumberFormat('id-ID').format(finalNominal);
            validateNominalAgainstSisa(finalNominal);
        }

        function toggleRiwayatCicilan(tagihanId) {
            const contentEl = document.getElementById('riwayatContent' + tagihanId);
            const btnEl = document.getElementById('riwayatToggleBtn' + tagihanId);
            const iconEl = btnEl ? btnEl.querySelector('svg') : null;

            if (!contentEl || !btnEl) return;

            const isHidden = contentEl.classList.contains('hidden');
            if (isHidden) {
                contentEl.classList.remove('hidden');
                iconEl?.classList.add('rotate-180');
            } else {
                contentEl.classList.add('hidden');
                iconEl?.classList.remove('rotate-180');
            }
        }

        // ---- Modal Hapus Cicilan ----
        function openHapusModal(actionUrl, tanggal, nominal) {
            document.getElementById('formHapus').action = actionUrl;
            document.getElementById('hapusTanggal').textContent = tanggal;
            document.getElementById('hapusNominal').textContent = 'Rp ' + nominal;

            document.getElementById('modalHapus').classList.remove('hidden');
            document.getElementById('modalHapus').classList.add('flex');
        }

        function closeHapusModal() {
            document.getElementById('modalHapus').classList.add('hidden');
            document.getElementById('modalHapus').classList.remove('flex');
        }

        let currentEditMaxNominal = 0;

        function openEditModal(actionUrl, tanggal, nominal, metode, keterangan, adminPenerima, maxNominal) {
            document.getElementById('formEdit').action = actionUrl;
            document.getElementById('editTanggalBayar').value = tanggal || '';
            document.getElementById('editNominalBayar').value = nominal || '';
            document.getElementById('editNominalDisplay').value = new Intl.NumberFormat('id-ID').format(Number(nominal || 0));
            document.getElementById('editMetode').value = (metode || '').toLowerCase();
            document.getElementById('editKeterangan').value = keterangan || '';
            document.getElementById('editAdminPenerima').value = adminPenerima || '';
            document.getElementById('editAdminPengubah').value = '';
            currentEditMaxNominal = Number(maxNominal || 0);
            validateEditNominal(Number(nominal || 0));

            const modal = document.getElementById('modalEdit');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('modalEdit');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function formatRupiahEdit(input) {
            const angka = input.value.replace(/\D/g, '');
            document.getElementById('editNominalBayar').value = angka;
            validateEditNominal(Number(angka || 0));
            input.value = new Intl.NumberFormat('id-ID').format(angka || 0);
        }

        function validateEditNominal(nominal) {
            const errorEl = document.getElementById('editNominalErrorText');
            const isExceeded = Number(nominal || 0) > Number(currentEditMaxNominal || 0);

            if (isExceeded) {
                errorEl.classList.remove('hidden');
                return false;
            }

            errorEl.classList.add('hidden');
            return true;
        }

        function confirmEditCicilan() {
            const nominal = Number(document.getElementById('editNominalBayar').value || 0);
            if (nominal < 1) {
                alert('Nominal bayar harus lebih dari 0');
                document.getElementById('editNominalDisplay').focus();
                return false;
            }

            if (!validateEditNominal(nominal)) {
                document.getElementById('editNominalDisplay').focus();
                return false;
            }

            return confirm('Yakin ingin menyimpan perubahan cicilan ini?');
        }

        // ---- Modal Cetak Nota ----
        function openNotaModal(actionUrl) {
            document.getElementById('formNota').action = actionUrl;
            document.getElementById('inputPanitia').value = '';

            document.getElementById('modalNota').classList.remove('hidden');
            document.getElementById('modalNota').classList.add('flex');
        }

        function closeNotaModal() {
            document.getElementById('modalNota').classList.add('hidden');
            document.getElementById('modalNota').classList.remove('flex');
        }

        // ---- Modal Password Panitia ----
        function bukaPasswordModal(url) {
            document.getElementById('redirectUrl').value = url;
            const modal = document.getElementById('modalPassword');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePasswordModal() {
            const modal = document.getElementById('modalPassword');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // ---- Handle Password Verification (AJAX) ----
        function handlePasswordSubmit(event) {
            event.preventDefault();
            
            const pwd = document.getElementById('inputPasswordPanitia').value;
            const redirectUrl = document.getElementById('redirectUrl').value;
            const btn = event.target.querySelector('button[type="submit"]');
            
            console.log('🔐 Password submit - redirectUrl:', redirectUrl);
            
            if (!pwd) {
                alert('❌ Masukkan password terlebih dahulu');
                return false;
            }
            
            btn.disabled = true;
            btn.textContent = 'Memverifikasi...';
            
            fetch('{{ route("verifikasi.password.panitia") }}', {
                method: 'POST',
                credentials: 'include',  // ← CRITICAL: Send & receive session cookies!
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                },
                body: JSON.stringify({
                    password: pwd,
                    redirect_url: redirectUrl
                })
            })
            .then(response => {
                console.log('🔐 Password response status:', response.status);
                return response.json().then(data => {
                    console.log('🔐 Password response data:', data);
                    
                    if (!response.ok) {
                        throw new Error(data.error || data.message || 'Verifikasi gagal');
                    }
                    return data;
                });
            })
            .then(data => {
                console.log('✅ Password verified! Reloading page...');
                // Password verified, session now set on server
                // Reload page to get fresh session state
                alert('✅ Password verified! Loading...');
                window.location.reload();
            })
            .catch(error => {
                console.error('❌ Password error:', error.message);
                alert('❌ Error: ' + error.message);
                btn.disabled = false;
                btn.textContent = 'Verifikasi';
            });
            
            return false;
        }

    </script>

    {{-- ===============================
    MODAL PASSWORD VERIFIKASI
    =============================== --}}
    <div id="modalPassword"
         onclick="closePasswordModal()"
         class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

        <div class="bg-white p-6 rounded w-80 relative" onclick="event.stopPropagation()">

            <button
                type="button"
                onclick="closePasswordModal()"
                class="absolute top-2 right-2 text-slate-500 hover:text-slate-700 p-1"
                aria-label="Tutup modal"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="font-semibold mb-3">
                🔐 Verifikasi Password Panitia
            </h3>

            <form id="formPasswordVerifikasi" onsubmit="handlePasswordSubmit(event)">
                @csrf

                <input type="hidden" name="redirect_url" id="redirectUrl">

                <input
                    type="password"
                    id="inputPasswordPanitia"
                    name="password"
                    class="w-full border rounded px-3 py-2 mb-3"
                    placeholder="Masukkan password"
                    required
                    autofocus
                >

                <div class="flex gap-2">
                    <button
                        type="button"
                        onclick="closePasswordModal()"
                        class="w-1/2 bg-slate-200 text-slate-700 px-4 py-2 rounded hover:bg-slate-300"
                    >
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Verifikasi
                    </button>
                </div>
            </form>

        </div>

    </div>

@endsection