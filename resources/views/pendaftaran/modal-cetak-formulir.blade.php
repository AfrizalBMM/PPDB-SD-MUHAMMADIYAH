<div id="modalPetugas" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
        <h2 class="font-semibold text-lg mb-1">Cetak Formulir</h2>
        <p class="text-sm text-slate-600 mb-4">Masukkan nama panitia, lalu file PDF akan otomatis terunduh.</p>

        <form id="formCetakFormulirSukses" method="POST" action="{{ route('cetak.formulir.post') }}" target="cetakFormulirFrameSukses" onsubmit="submitCetakFormulirSukses()">
            @csrf
            <input type="hidden" name="siswa_id" id="modalSiswaId">

            <div class="mb-3">
                <label class="text-sm font-medium">Nama Panitia</label>
                <input type="text" name="nama_panitia" required
                       class="w-full border rounded-lg px-3 py-2"
                       placeholder="Misal: Ahmad S.Pd">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalPetugas()"
                        class="px-3 py-2 bg-slate-200 rounded">
                    Batal
                </button>
                <button id="btnCetakFormulirSukses" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Download PDF
                </button>
            </div>
        </form>
    </div>
</div>

<iframe name="cetakFormulirFrameSukses" id="cetakFormulirFrameSukses" class="hidden"></iframe>

<div id="toastCetakFormulirSukses" class="fixed bottom-4 right-4 z-[70] pointer-events-none opacity-0 translate-y-2 transition-all duration-200">
    <div class="rounded-lg border border-green-200 bg-green-100/90 px-3 py-2 text-xs font-medium text-green-700 shadow-lg">
        Download formulir dimulai
    </div>
</div>

<script>
let isCetakFormulirSuksesSubmitting = false;

function showCetakFormulirSuksesToast() {
    const toast = document.getElementById('toastCetakFormulirSukses');
    if (!toast) {
        return;
    }

    toast.classList.remove('opacity-0', 'translate-y-2');

    setTimeout(function () {
        toast.classList.add('opacity-0', 'translate-y-2');
    }, 1500);
}

function openModalPetugas(id){
    document.getElementById('modalSiswaId').value = id;
    document.getElementById('modalPetugas').classList.remove('hidden');
}

function closeModalPetugas(){
    document.getElementById('modalPetugas').classList.add('hidden');
}

function submitCetakFormulirSukses(){
    isCetakFormulirSuksesSubmitting = true;

    const button = document.getElementById('btnCetakFormulirSukses');
    if (button) {
        button.disabled = true;
        button.classList.add('opacity-70', 'cursor-not-allowed');
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    const frame = document.getElementById('cetakFormulirFrameSukses');
    const form = document.getElementById('formCetakFormulirSukses');
    const button = document.getElementById('btnCetakFormulirSukses');

    if (!frame) {
        return;
    }

    frame.addEventListener('load', function () {
        if (!isCetakFormulirSuksesSubmitting) {
            return;
        }

        closeModalPetugas();
        showCetakFormulirSuksesToast();

        if (form) {
            form.reset();
        }

        if (button) {
            button.disabled = false;
            button.classList.remove('opacity-70', 'cursor-not-allowed');
        }

        isCetakFormulirSuksesSubmitting = false;
    });
});
</script>
