<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','PPDB SD Muhammadiyah Wonorejo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-100 text-slate-800 font-sans flex flex-col min-h-screen">

{{-- HEADER --}}
<header class="bg-primary text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <div class="flex items-center gap-4">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo SD Muhammadiyah Wonorejo"
                class="h-12 w-auto">

            <div>
                <h1 class="font-bold text-lg leading-tight">
                    PPDB SD Muhammadiyah Wonorejo
                </h1>
                <p class="text-xs opacity-80">
                    Islami, Mandiri, Berprestasi
                </p>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-3">

            {{-- DAFTAR PENDAFTAR --}}
            <a
                href="{{ route('pendaftaran.list') }}"
                class="bg-secondary text-white px-4 py-2 rounded-lg
                    text-sm font-semibold hover:bg-blue-700 transition">
                📋 Daftar Calon Peserta Didik
            </a>

            {{-- LOGIN --}}
            <a
                href="{{ route('login') }}"
                class="bg-white text-primary px-4 py-2 rounded-lg
                    text-sm font-semibold hover:bg-slate-100 transition">
                Login
            </a>

        </div>

        <details class="md:hidden relative">
            <summary class="list-none cursor-pointer rounded-lg bg-white/15 px-3 py-2 hover:bg-white/20 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </summary>

            <div class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden z-50">
                <a
                    href="{{ route('pendaftaran.list') }}"
                    class="block px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    📋 Daftar Calon Peserta Didik
                </a>
                <a
                    href="{{ route('login') }}"
                    class="block px-4 py-3 text-sm font-semibold text-primary hover:bg-slate-50 transition border-t border-slate-100">
                    Login
                </a>
            </div>
        </details>


    </div>
</header>

{{-- MAIN --}}
<main class="flex-1 py-8">
    @isset($slot)
        {{ $slot }}
    @endisset

    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="text-center text-xs text-slate-400 py-4">
    © {{ date('Y') }} SD Muhammadiyah Wonorejo
</footer>

<div id="globalToast"
     class="fixed bottom-4 right-4 z-[200] hidden min-w-[260px] max-w-sm overflow-hidden rounded-xl border px-4 py-3 shadow-2xl transition-all duration-200 translate-y-3 opacity-0"
     role="status"
     aria-live="polite">
    <div class="flex items-start gap-3">
        <div id="globalToastIcon" class="mt-0.5"></div>
        <div class="flex-1">
            <p id="globalToastTitle" class="text-sm font-semibold"></p>
            <p id="globalToastMessage" class="mt-0.5 text-xs"></p>
        </div>
        <button type="button" id="globalToastClose" class="rounded-md p-1 text-current/70 hover:bg-black/5 hover:text-current" aria-label="Tutup notifikasi">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<div id="globalConfirmModal" class="fixed inset-0 z-[220] hidden items-center justify-center bg-black/45 p-4 backdrop-blur-[1px]">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
        <h3 id="globalConfirmTitle" class="text-base font-semibold text-slate-800">Konfirmasi</h3>
        <p id="globalConfirmMessage" class="mt-2 text-sm leading-relaxed text-slate-600">Apakah Anda yakin?</p>

        <div class="mt-5 flex justify-end gap-2">
            <button type="button" id="globalConfirmCancel" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Batal</button>
            <button type="button" id="globalConfirmOk" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
(() => {
    const box = document.getElementById('globalToast');
    const icon = document.getElementById('globalToastIcon');
    const title = document.getElementById('globalToastTitle');
    const message = document.getElementById('globalToastMessage');
    const close = document.getElementById('globalToastClose');

    if (!box || !icon || !title || !message || !close) {
        return;
    }

    let timer = null;

    const themes = {
        success: {
            title: 'Sukses',
            className: 'border-emerald-200 bg-emerald-50 text-emerald-800',
            icon: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0" /></svg>'
        },
        warning: {
            title: 'Peringatan',
            className: 'border-amber-200 bg-amber-50 text-amber-800',
            icon: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.81A1 1 0 003.76 18h16.48a1 1 0 00.87-1.33l-7.4-12.81a1 1 0 00-1.74 0z" /></svg>'
        },
        info: {
            title: 'Info',
            className: 'border-sky-200 bg-sky-50 text-sky-800',
            icon: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        },
        danger: {
            title: 'Gagal',
            className: 'border-red-200 bg-red-50 text-red-800',
            icon: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        }
    };

    const hideToast = () => {
        box.classList.add('opacity-0', 'translate-y-3');
        setTimeout(() => {
            box.classList.add('hidden');
        }, 180);
    };

    close.addEventListener('click', hideToast);

    const showToast = (type = 'info', text = '', options = {}) => {
        const theme = themes[type] || themes.info;

        box.className = 'fixed bottom-4 right-4 z-[200] min-w-[260px] max-w-sm overflow-hidden rounded-xl border px-4 py-3 shadow-2xl transition-all duration-200 ' + theme.className;
        icon.innerHTML = theme.icon;
        title.textContent = options.title || theme.title;
        message.textContent = text || '';
        box.classList.remove('hidden');
        requestAnimationFrame(() => {
            box.classList.remove('opacity-0', 'translate-y-3');
        });

        clearTimeout(timer);
        const duration = Number(options.duration || 2800);
        if (duration > 0) {
            timer = setTimeout(hideToast, duration);
        }
    };

    // API global utama
    window.showGlobalToast = showToast;
    // Backward compatibility untuk pemanggilan lama
    window.showGlobalNotice = showToast;
})();

(() => {
    const modal = document.getElementById('globalConfirmModal');
    const title = document.getElementById('globalConfirmTitle');
    const message = document.getElementById('globalConfirmMessage');
    const btnCancel = document.getElementById('globalConfirmCancel');
    const btnOk = document.getElementById('globalConfirmOk');

    if (!modal || !title || !message || !btnCancel || !btnOk) {
        return;
    }

    let resolver = null;

    const close = (result) => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (resolver) {
            resolver(result);
            resolver = null;
        }
    };

    btnCancel.addEventListener('click', () => close(false));
    btnOk.addEventListener('click', () => close(true));
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            close(false);
        }
    });

    window.showGlobalConfirm = (text = 'Apakah Anda yakin?', options = {}) => {
        title.textContent = options.title || 'Konfirmasi';
        message.textContent = text;
        btnOk.textContent = options.okText || 'Ya, Lanjutkan';
        btnCancel.textContent = options.cancelText || 'Batal';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        return new Promise((resolve) => {
            resolver = resolve;
        });
    };

    window.globalConfirmSubmit = (form, text, options = {}) => {
        window.showGlobalConfirm(text, options).then((ok) => {
            if (ok) {
                form.submit();
            }
        });
        return false;
    };
})();
</script>

@livewireScripts
</body>
</html>
