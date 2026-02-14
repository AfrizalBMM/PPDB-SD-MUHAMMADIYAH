<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Admin PPDB')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-muted">

<div class="flex min-h-screen">

    {{-- OVERLAY MOBILE --}}
    <div id="sidebarOverlay"
         onclick="toggleSidebar()"
         class="fixed inset-0 bg-black/40 z-30 hidden md:hidden">
    </div>

    {{-- SIDEBAR --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-primary text-white
               transform -translate-x-full md:translate-x-0
               transition-transform duration-200">

        @include('layouts.partials.sidebar')
    </aside>

    {{-- CONTENT --}}
    <div class="flex-1 flex flex-col md:ml-64">

        {{-- HEADER --}}
        <header class="bg-white shadow px-4 py-3 flex items-center justify-between sticky top-0 z-20">
            <!-- Tombol sidebar -->
            <button onclick="toggleSidebar()" class="md:hidden text-xl">☰</button>

            <!-- Judul halaman -->
            <h1 class="font-semibold text-lg">
                @yield('page-title','Dashboard')
            </h1>

            <!-- Waktu & user -->
            <div class="flex items-center space-x-4 text-sm text-slate-600">
                <!-- Label + tanggal & waktu live dengan badge -->
                <span class="flex items-center space-x-1">
                    <span id="live-clock" class="bg-green-500 text-white px-2 py-0.5 rounded"></span>
                </span>
                <!-- Nama user -->
                <span>{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="p-4 md:p-6 flex-1 relative">

            {{-- ALERTS --}}
            <div class="absolute top-0 left-0 w-full px-4 md:px-0">
                {{-- Success --}}
                @if(session('success'))
                    <div class="alert-success bg-green-100 text-green-800 px-4 py-2 rounded mb-4 shadow-md max-w-2xl mx-auto">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error --}}
                @if(session('error'))
                    <div class="alert-error bg-red-100 text-red-800 px-4 py-2 rounded mb-4 shadow-md max-w-2xl mx-auto">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert-error bg-red-100 text-red-800 px-4 py-2 rounded mb-4 shadow-md max-w-2xl mx-auto">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- KONTEN HALAMAN --}}
            @yield('content')
        </main>

        <!-- Tambahkan ini tepat sebelum </body> -->
        <footer class="bg-white border-t border-slate-200 text-center text-sm text-slate-500 py-3 mt-auto">
            Sistem Pendaftaran Peserta Didik Baru - SD Muhammadiyah Wonorejo
        </footer>

    </div>
</div>




<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function openDeleteModal(action){
    const modal = document.getElementById('deleteModal');
    const form  = document.getElementById('deleteForm');
    form.action = action;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openConfirmModal(){
    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmModal').classList.add('flex');
}

function closeConfirmModal(){
    document.getElementById('confirmModal').classList.add('hidden');
}


function closeDeleteModal(){
    document.getElementById('deleteModal').classList.add('hidden');
}

document.querySelectorAll('input[name="nominal"]').forEach(el => {
    el.addEventListener('input', function(){
        this.value = this.value.replace(/\D/g,'');
    });
});

document.addEventListener("DOMContentLoaded", function(){

    // ===== ALERT SUKSES OTOMATIS HILANG =====
    document.querySelectorAll('.alert-success').forEach(alert => {
        setTimeout(() => alert.remove(), 4000);
    });

    // ===== ALERT ERROR OTOMATIS HILANG =====
    document.querySelectorAll('.alert-error').forEach(alert => {
        setTimeout(() => alert.remove(), 4000);
    });

    // ===== LOADING BUTTON OTOMATIS UNTUK SEMUA FORM =====
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(){
            const btn = form.querySelector('button[type="submit"]');
            if(btn){
                btn.disabled = true;
                btn.textContent = 'Loading...';
                btn.classList.add('opacity-50','cursor-not-allowed');
            }
        });
    });

});

    function updateClock() {
        const clock = document.getElementById('live-clock');
        const now = new Date();

        // Array nama bulan
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const day = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();

        // Format waktu
        const hours = String(now.getHours()).padStart(2,'0');
        const minutes = String(now.getMinutes()).padStart(2,'0');
        const seconds = String(now.getSeconds()).padStart(2,'0');

        clock.textContent = `${day} ${monthName} ${year} ${hours}:${minutes}:${seconds}`;
    }

    setInterval(updateClock, 1000); // update tiap detik
    updateClock(); // langsung tampil saat page load

</script>

</body>
</html>
