@extends('layouts.admin')

@section('title', 'Kelola Testimonial')
@section('page-title', 'Testimonial')

@section('content')
<div x-data="{ 
    showCreate: false, 
    showEdit: false,
    editData: { id: '', name: '', role: '', content: '' }
}" class="mx-auto max-w-7xl space-y-6">
    @php
        $totalTestimoni = $testimonials->total();
        $withPhoto = $testimonials->getCollection()->whereNotNull('image_or_video')->count();
        $withoutPhoto = $testimonials->getCollection()->whereNull('image_or_video')->count();
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 via-white to-cyan-50 p-5 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Testimoni Orang Tua & Tokoh</h2>
                <p class="mt-1 text-sm text-slate-600">Kelola testimoni untuk membangun kepercayaan calon wali murid.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">Total: {{ $totalTestimoni }}</span>
                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Dengan Foto: {{ $withPhoto }}</span>
                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Tanpa Foto: {{ $withoutPhoto }}</span>
            </div>
        </div>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-base font-semibold text-slate-800">Daftar Testimoni</h3>
                <p class="mt-1 text-xs text-slate-500">Pastikan narasi testimoni singkat, jelas, dan autentik.</p>
            </div>

            <button @click="showCreate = true" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Testimoni
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-left text-sm font-semibold text-slate-700">
                    <tr>
                        <th class="w-16 px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Nama & Peran</th>
                        <th class="px-4 py-3">Isi Testimoni</th>
                        <th class="w-32 px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($testimonials as $t)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3">
                            @if($t->image_or_video)
                                <img src="{{ Storage::url($t->image_or_video) }}" class="h-10 w-10 rounded-full border border-slate-200 object-cover shadow-sm">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                    {{ substr($t->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800">{{ $t->name }}</div>
                            <div class="text-[10px] text-slate-500">{{ $t->role }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-sm line-clamp-2 text-xs italic text-slate-600">"{{ $t->content }}"</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="
                                    editData = { 
                                        id: '{{ $t->id }}', 
                                        name: '{{ addslashes($t->name) }}', 
                                        role: '{{ addslashes($t->role) }}', 
                                        content: '{{ addslashes($t->content) }}' 
                                    };
                                    showEdit = true;
                                " class="rounded-lg border border-blue-200 bg-blue-50 p-2 text-blue-700 hover:bg-blue-100" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('landing-testimonials.destroy', $t->id) }}" method="POST" onsubmit="return window.globalConfirmSubmit(this, 'Yakin hapus testimoni ini?', { title: 'Konfirmasi Hapus' });" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg border border-red-200 bg-red-50 p-2 text-red-700 hover:bg-red-100" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 px-4 py-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex items-center gap-2 text-xs text-slate-600">
                <label for="perPageTestimonials">Tampilkan</label>
                <select id="perPageTestimonials" name="per_page" onchange="this.form.submit()" class="rounded border border-slate-300 px-2 py-1 text-xs">
                    @foreach([10,20,50,100] as $size)
                        <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </form>

            <div>
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div x-show="showCreate" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 transition-opacity" @click="showCreate = false"></div>

            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg my-8 overflow-hidden rounded-2xl bg-white p-8 text-left align-middle shadow-xl transition-all transform">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Tambah Testimoni</h3>
                    <button @click="showCreate = false" class="text-slate-500 hover:text-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('landing-testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="label">Nama Tokoh/Ortu</label>
                            <input type="text" name="name" class="input" required placeholder="Cth: Budi Santoso">
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Role/Peran</label>
                            <input type="text" name="role" class="input" placeholder="Cth: Wali Murid Kelas 2">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Isi Testimoni</label>
                        <textarea name="content" class="input h-auto py-3" rows="4" required placeholder="Tuliskan pengalaman positif mereka..."></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Foto Profile (Opsional)</label>
                        <input type="file" name="image_or_video" class="text-xs text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-100 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-blue-700 hover:file:bg-blue-200">
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="showCreate = false" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan Testimoni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showEdit" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 transition-opacity" @click="showEdit = false"></div>

            <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg my-8 overflow-hidden rounded-2xl bg-white p-8 text-left align-middle shadow-xl transition-all transform">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Edit Testimoni</h3>
                    <button @click="showEdit = false" class="text-slate-500 hover:text-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="'{{ url('admin/landing-testimonials') }}/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="label">Nama Tokoh/Ortu</label>
                            <input type="text" name="name" x-model="editData.name" class="input" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Role/Peran</label>
                            <input type="text" name="role" x-model="editData.role" class="input">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Isi Testimoni</label>
                        <textarea name="content" x-model="editData.content" class="input h-auto py-3" rows="4" required></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Ganti Foto (Opsional)</label>
                        <input type="file" name="image_or_video" class="text-xs text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-100 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-blue-700 hover:file:bg-blue-200">
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="showEdit = false" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Perbarui Testimoni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

