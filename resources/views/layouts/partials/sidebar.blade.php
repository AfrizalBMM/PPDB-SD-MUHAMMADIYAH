{{-- HEADER --}}
<div class="px-6 py-5 border-b border-border flex items-center space-x-3">
    <img src="{{ asset('images/logo.png') }}" alt="Logo PPDB" class="h-10 w-10 object-contain drop-shadow-sm">
    <div class="flex flex-col">
        <div class="text-lg font-heading font-bold tracking-wide text-primary">
            PPDB SDM
        </div>
        <div class="text-xs text-textSecondary mt-0.5 font-medium">
            Islami, Mandiri, Berprestasi
        </div>
    </div>
</div>


{{-- MENU --}}
<nav class="px-3 py-4 space-y-1.5 text-sm h-[calc(100vh-140px)] overflow-y-auto w-full">

    {{-- MAIN --}}
    <h3 class="sidebar-section">
        Menu Utama
    </h3>

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
        <h3 class="sidebar-section mt-6">
            Master Data
        </h3>

        <a href="{{ route('admin.password.panitia') }}"
            class="sidebar-link {{ request()->routeIs('admin.password.panitia') ? 'active' : '' }}">
            🔑 Password Panitia
        </a>

        <a href="{{ route('admin.password.petugas-keuangan') }}"
            class="sidebar-link {{ request()->routeIs('admin.password.petugas-keuangan*') ? 'active' : '' }}">
            💼 Password Petugas Keuangan
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
            🧾 Monitoring Aktivitas
        </a>
        <a href="{{ route('admin.settings.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            ⚙️ Setting Website Base
        </a>
        <a href="{{ route('landing-programs.index') }}"
           class="sidebar-link {{ request()->routeIs('landing-programs.*') ? 'active' : '' }}">
            🎯 Program Unggulan
        </a>
        <a href="{{ route('landing-facilities.index') }}"
           class="sidebar-link {{ request()->routeIs('landing-facilities.*') ? 'active' : '' }}">
            🏢 Fasilitas Sekolah
        </a>
        <a href="{{ route('landing-testimonials.index') }}"
           class="sidebar-link {{ request()->routeIs('landing-testimonials.*') ? 'active' : '' }}">
            💬 Testimonial
        </a>
        <a href="{{ route('landing-galleries.index') }}"
           class="sidebar-link {{ request()->routeIs('landing-galleries.*') ? 'active' : '' }}">
            🖼️ Galeri Sekolah
        </a>
        <a href="{{ route('landing-faqs.index') }}"
           class="sidebar-link {{ request()->routeIs('landing-faqs.*') ? 'active' : '' }}">
            ❓ FAQ Landing
        </a>
    @endrole

</nav>






