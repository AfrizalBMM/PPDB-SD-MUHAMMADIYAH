{{-- HEADER --}}
<div class="p-5 font-bold text-lg border-b border-white/10">
    PPDB ADMIN
    <div class="text-xs font-normal opacity-80 mt-1">
        {{ auth()->user()->role }}
    </div>
</div>

{{-- NAV --}}
<nav class="p-4 space-y-1 text-sm">

    {{-- DASHBOARD --}}
    <a
        href="{{ route('dashboard') }}"
        class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Dashboard
    </a>

    {{-- SUPERADMIN + ADMIN --}}
    @role('superadmin|admin')
        <a
            href="{{ route('pendaftar.index') }}"
            class="sidebar-link {{ request()->routeIs('pendaftar.*') ? 'active' : '' }}">
            Pendaftar
        </a>

        <a
            href="{{ route('siswa.kelas1') }}"
            class="sidebar-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            Siswa
        </a>
    @endrole

    {{-- SUPERADMIN + KEUANGAN --}}
    @role('superadmin|keuangan')
        <a
            href="{{ route('keuangan.index') }}"
            class="sidebar-link {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
            Keuangan
        </a>
    @endrole

    {{-- SUPERADMIN ONLY --}}
    @role('superadmin')
        <hr class="border-white/10 my-3">

        <p class="px-4 mb-2 text-xs uppercase tracking-wider text-white/60">
            Master Data
        </p>

        <a
            href="{{ route('tahun-ajaran.index') }}"
            class="sidebar-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}">
            Tahun Ajaran
        </a>

        <a
            href="{{ route('biaya.index') }}"
            class="sidebar-link {{ request()->routeIs('biaya.*') ? 'active' : '' }}">
            Biaya
        </a>

        <a
            href="{{ route('voucher.index') }}"
            class="sidebar-link {{ request()->routeIs('voucher.*') ? 'active' : '' }}">
            Voucher
        </a>

        <a
            href="{{ route('paud-tk.index') }}"
            class="sidebar-link {{ request()->routeIs('paud-tk.*') ? 'active' : '' }}">
            PAUD / TK
        </a>

        <a
            href="{{ route('users.index') }}"
            class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            Akun User
        </a>

        <a
            href="{{ route('log.aktivitas') }}"
            class="sidebar-link {{ request()->routeIs('log.aktivitas') ? 'active' : '' }}">
            Log Aktivitas
        </a>
    @endrole

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}" class="pt-4">
        @csrf
        <button
            type="submit"
            class="w-full text-left sidebar-link text-red-200 hover:text-red-100">
            Logout
        </button>
    </form>

</nav>
