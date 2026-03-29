<div
    id="modalBayar"
    class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">

    <div class="card w-full max-w-md relative">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800">
                Input Pembayaran
            </h3>

            <button onclick="closeBayar()" class="text-slate-400 hover:text-red-600 text-xl">
                &times;
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('pembayaran.store') }}" class="space-y-3">
            @csrf

            <input type="hidden" name="tagihan_siswa_id" id="tagihan_id">

            <div>
                <label class="label text-sm text-slate-600 mb-1 block">Nama Siswa / Jenis Biaya</label>
                <div class="flex gap-2">
                    <input id="nama_siswa" disabled class="input bg-slate-100 text-sm w-1/2">
                    <input id="nama_biaya" disabled class="input bg-slate-100 text-sm w-1/2">
                </div>
            </div>

            <div>
                <label class="label text-sm text-slate-600 mb-1 block">Sisa Tagihan</label>
                <input id="sisa_tagihan" disabled class="input bg-slate-100 font-semibold text-red-600 text-sm w-full">
            </div>

            <div>
                <label class="label text-sm text-slate-600 mb-1 block">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required class="input text-sm w-full border-slate-300 focus:ring-blue-200">
            </div>

            <div>
                <label class="label text-sm text-slate-600 mb-1 block">Nominal Bayar</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 border border-r-0 border-slate-300 bg-slate-100 rounded-l-lg text-sm">Rp.</span>
                    <input type="text" id="nominal_display" class="w-full border border-slate-300 rounded-r-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="0" onkeyup="formatRupiah(this)" required>
                </div>
                <input type="hidden" name="nominal_bayar" id="nominal_bayar">
            </div>

            <div>
                <label class="label text-sm text-slate-600 mb-1 block">Penerima Pembayaran (Wajib)</label>
                <input type="text" name="admin_penerima" class="input text-sm w-full border-slate-300 focus:ring-blue-200" placeholder="Nama petugas penerima" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label text-sm text-slate-600 mb-1 block">Metode</label>
                    <select name="metode" class="input text-sm w-full border-slate-300">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="label text-sm text-slate-600 mb-1 block">Keterangan</label>
                    <input type="text" name="keterangan" class="input text-sm w-full border-slate-300" placeholder="opsional">
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 pt-4 border-t border-slate-200 mt-5">
                <button type="button" onclick="closeBayar()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="btn-primary text-sm px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Pembayaran
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function openBayar(id, siswa, biaya, sisa) {
    const modal = document.getElementById('modalBayar');
    modal.classList.remove('hidden');

    document.getElementById('tagihan_id').value = id;
    document.getElementById('nama_siswa').value = siswa;
    document.getElementById('nama_biaya').value = biaya;
    document.getElementById('sisa_tagihan').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisa);
}

function closeBayar() {
    document.getElementById('modalBayar').classList.add('hidden');
    document.getElementById('nominal_display').value = '';
    document.getElementById('nominal_bayar').value = '';
}

function formatRupiah(input) {
    let angka = input.value.replace(/\D/g,'');
    document.getElementById('nominal_bayar').value = angka;
    let formatted = new Intl.NumberFormat('id-ID').format(angka);
    input.value = formatted;
}
</script>
