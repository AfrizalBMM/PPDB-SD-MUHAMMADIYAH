<div
    id="modalNota"
    class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">

    <div class="bg-white rounded-xl w-full max-w-md p-6">

        <h3 class="font-semibold text-slate-800 mb-4">
            Cetak Nota Pendaftaran
        </h3>

        <form
            method="GET"
            action="{{ route('pembayaran.nota', $siswa->pembayaranPendaftaran->id ?? '') }}"
            target="_blank"
            class="space-y-4">

            <div>
                <label class="label">Nama Admin / Panitia</label>
                <input
                    name="nama_admin"
                    required
                    class="input"
                    value="{{ auth()->user()->name ?? '' }}"
                    placeholder="Nama admin / panitia">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button
                    type="button"
                    onclick="document.getElementById('modalNota').classList.add('hidden')"
                    class="px-4 py-2 rounded-lg border text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </button>

                <button class="btn-primary">
                    Cetak Nota
                </button>
            </div>
        </form>

    </div>
</div>
