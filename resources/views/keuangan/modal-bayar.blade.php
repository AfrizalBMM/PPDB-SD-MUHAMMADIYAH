<div
    id="modalBayar"
    class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">

    <div class="bg-white rounded-xl w-full max-w-md p-6">

        <h3 class="font-semibold text-slate-800 mb-4">
            Input Pembayaran
        </h3>

        <form method="POST" action="{{ route('pembayaran.store') }}" class="space-y-3">
            @csrf

            <input type="hidden" name="tagihan_id" id="tagihan_id">

            <div>
                <label class="label">Nama Siswa</label>
                <input
                    id="nama_siswa"
                    disabled
                    class="input bg-slate-100">
            </div>

            <div>
                <label class="label">Jenis Biaya</label>
                <input
                    id="nama_biaya"
                    disabled
                    class="input bg-slate-100">
            </div>

            <div>
                <label class="label">Sisa Tagihan</label>
                <input
                    id="sisa_tagihan"
                    disabled
                    class="input bg-slate-100">
            </div>

            <div>
                <label class="label">Nominal Bayar</label>
                <input
                    name="nominal_bayar"
                    type="number"
                    min="1"
                    required
                    class="input"
                    placeholder="Masukkan nominal">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button
                    type="button"
                    onclick="closeBayar()"
                    class="px-4 py-2 rounded-lg border text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </button>

                <button class="btn-primary">
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
        document.getElementById('sisa_tagihan').value =
            'Rp ' + Number(sisa).toLocaleString('id-ID');
    }

    function closeBayar() {
        document.getElementById('modalBayar').classList.add('hidden');
    }
</script>
