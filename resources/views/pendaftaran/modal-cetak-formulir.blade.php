<div id="modalPetugas" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
        <h2 class="font-semibold text-lg mb-3">Input Nama Petugas</h2>

        <form method="POST" action="{{ route('cetak.formulir.post') }}">
            @csrf
            <input type="hidden" name="siswa_id" id="modalSiswaId">

            <div class="mb-3">
                <label class="text-sm font-medium">Nama Petugas</label>
                <input type="text" name="nama_petugas" required
                       class="w-full border rounded-lg px-3 py-2"
                       placeholder="Misal: Ahmad S.Pd">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalPetugas()"
                        class="px-3 py-2 bg-slate-200 rounded">
                    Batal
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Cetak Formulir
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModalPetugas(id){
    document.getElementById('modalSiswaId').value = id;
    document.getElementById('modalPetugas').classList.remove('hidden');
}
function closeModalPetugas(){
    document.getElementById('modalPetugas').classList.add('hidden');
}
</script>
