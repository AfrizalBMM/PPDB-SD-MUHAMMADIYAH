{{-- HEADER --}}
<div class="px-5 py-4 border-b border-white/10 flex items-center space-x-3">
    <img src="{{ asset('images/logo.png') }}" alt="Logo PPDB" class="h-10 w-10 object-contain">
    <div class="flex flex-col">
        <div class="text-lg font-bold tracking-wide">
            PPDB SDM
        </div>
        <div class="text-xs text-white/70 mt-1">
            Islami, Mandiri, Berprestasi
        </div>
    </div>
</div>


{{-- MENU --}}
<nav class="px-3 py-4 space-y-1 text-sm">

    {{-- MAIN --}}
    <p class="px-3 mb-2 text-xs uppercase tracking-wider text-white/50">
        Menu Utama
    </p>

    <a href="{{ route('dashboard') }}"
       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        📊 Dashboard
    </a>

    @role('superadmin|admin')
        <a href="{{ route('pendaftar.index') }}"
           class="sidebar-link {{ request()->routeIs('pendaftar.*') ? 'active' : '' }}">
            📝 Pendaftar
        </a>

        <a href="{{ route('siswa.kelas1') }}"
           class="sidebar-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            🎓 Siswa
        </a>
    @endrole

    @role('superadmin|keuangan')
        <a href="{{ route('keuangan.index') }}"
           class="sidebar-link {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
            💰 Keuangan
        </a>
    @endrole

    {{-- MASTER --}}
    @role('superadmin')
        <p class="px-3 mt-4 mb-2 text-xs uppercase tracking-wider text-white/50">
            Master Data
        </p>

        <a href="{{ route('admin.password.panitia') }}"
            class="sidebar-link {{ request()->routeIs('admin.password.panitia') ? 'active' : '' }}">
            🔑 Password Panitia
        </a>

        <a href="{{ route('tahun-ajaran.index') }}"
           class="sidebar-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}">
            📅 Tahun Ajaran
        </a>

        <a href="{{ route('biaya.index') }}"
           class="sidebar-link {{ request()->routeIs('biaya.*') ? 'active' : '' }}">
            💳 Set Biaya
        </a>

        <a href="{{ route('voucher.index') }}"
           class="sidebar-link {{ request()->routeIs('voucher.*') ? 'active' : '' }}">
            🎟 Voucher
        </a>

        <a href="{{ route('paud-tk.index') }}"
           class="sidebar-link {{ request()->routeIs('paud-tk.*') ? 'active' : '' }}">
            🏫 PAUD / TK
        </a>

        <a href="{{ route('users.index') }}"
           class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            👤 Akun User
        </a>

        <a href="{{ route('log.aktivitas') }}"
           class="sidebar-link {{ request()->routeIs('log.aktivitas') ? 'active' : '' }}">
            🧾 Log Aktivitas
        </a>
    @endrole

</nav>

{{-- FOOTER --}}
<div class="absolute bottom-0 left-0 w-full border-t border-white/10 p-3">
    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
        <button 
            type="button"
            onclick="openModal('logoutModal')"
            class="sidebar-link text-red-300 hover:text-red-100 w-full text-left">
            🚪 Logout
        </button>
    </form>
</div>


{{-- MODAL LOGOUT --}}
<x-modal id="logoutModal" title="Konfirmasi Logout">
    <p>Apakah Anda yakin ingin keluar dari sistem?</p>

    <div class="flex justify-end gap-2 mt-4">
        <button 
            type="button"
            onclick="closeModal('logoutModal')" 
            class="btn-secondary">
            Batal
        </button>

        <button 
            type="button"
            onclick="document.getElementById('logoutForm').submit()" 
            class="btn-danger">
            Ya, Logout
        </button>
    </div>
</x-modal>

