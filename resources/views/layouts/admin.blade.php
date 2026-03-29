<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Admin PPDB')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- AlpineJS with Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-background text-textPrimary font-sans antialiased">

<div class="flex min-h-screen">

    {{-- OVERLAY MOBILE --}}
    <div id="sidebarOverlay"
         onclick="toggleSidebar()"
         class="fixed inset-0 bg-black/40 z-30 hidden md:hidden">
    </div>

    {{-- SIDEBAR --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-white text-textPrimary border-r border-border shadow-sm
               transform -translate-x-full md:translate-x-0
               transition-transform duration-200">

        @include('layouts.partials.sidebar')
    </aside>

    {{-- CONTENT --}}
    <div class="flex-1 flex flex-col md:ml-64">

        {{-- HEADER --}}
        <header class="bg-white shadow-sm border-b border-border px-6 py-4 flex items-center justify-between sticky top-0 z-50">
            <!-- Tombol sidebar -->
            <button onclick="toggleSidebar()" class="md:hidden text-xl text-textSecondary hover:text-primary transition-colors">☰</button>

            <!-- Judul halaman -->
            <h1 class="font-heading font-bold text-2xl text-textPrimary">
                @yield('page-title','Dashboard')
            </h1>

            <!-- Waktu & user -->
            <div class="flex items-center space-x-6 text-sm text-slate-600">
                <!-- Label + tanggal & waktu live dengan badge -->
                <span class="hidden lg:flex items-center space-x-1">
                    <span id="live-clock" class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full font-medium border border-slate-200"></span>
                </span>
                
                <!-- Nama user Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 hover:bg-slate-50 px-3 py-2 rounded-lg transition-colors group">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="font-bold text-textPrimary leading-none">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] uppercase tracking-wider text-textSecondary mt-1">{{ auth()->user()->role }}</span>
                        </div>
                        <svg class="w-4 h-4 text-textSecondary transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-border py-2 z-50"
                         x-cloak>
                        
                        <div class="px-4 py-2 border-b border-border mb-1">
                            <p class="text-[10px] uppercase font-bold text-textSecondary tracking-widest">Akun Saya</p>
                        </div>

                        <a href="{{ route('admin.profile.password.edit') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-textPrimary hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 text-textSecondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            <span>Ganti Password</span>
                        </a>

                        <button onclick="openModal('logoutModal')" class="w-full flex items-center space-x-2 px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
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

{{-- MODAL LOGOUT --}}
<div id="logoutModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[100] p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-textPrimary mb-2">Konfirmasi Logout</h3>
        <p class="text-textSecondary mb-8 leading-relaxed text-sm">Apakah Anda yakin ingin keluar dari sistem? Sesi Anda akan berakhir.</p>

        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="closeModal('logoutModal')" class="btn-secondary py-3 text-sm font-bold">
                    Batal
                </button>
                <button type="submit" class="bg-red-600 text-white rounded-xl py-3 text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">
                    Ya, Logout
                </button>
            </div>
        </form>
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

function openModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

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

    // Array nama hari
    const days = [
        "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
    ];

    const dayName = days[now.getDay()]; // nama hari
    const day = now.getDate();
    const monthName = months[now.getMonth()];
    const year = now.getFullYear();

    // Format waktu
    const hours = String(now.getHours()).padStart(2,'0');
    const minutes = String(now.getMinutes()).padStart(2,'0');
    const seconds = String(now.getSeconds()).padStart(2,'0');

    clock.textContent = `${dayName}, ${day} ${monthName} ${year} ${hours}:${minutes}:${seconds}`;
}

setInterval(updateClock, 1000); // update tiap detik
updateClock(); // langsung tampil saat page load


</script>

</body>
</html>
