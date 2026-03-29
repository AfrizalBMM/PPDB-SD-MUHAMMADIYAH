@extends('layouts.admin')

@section('title', 'Kelola Testimonial')
@section('page-title', 'Testimonial')

@section('content')
<div x-data="{ 
    showCreate: false, 
    showEdit: false,
    editData: { id: '', name: '', role: '', content: '' }
}">
    <div class="card">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-textPrimary">Testimonial Ortu & Tokoh</h2>
                <p class="text-textSecondary text-sm">Kelola testimoni untuk membangun kepercayaan calon wali murid.</p>
            </div>
            <button @click="showCreate = true" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Testimoni
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-16">Foto</th>
                        <th>Nama & Peran</th>
                        <th>Isi Testimoni</th>
                        <th class="w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $t)
                    <tr>
                        <td>
                            @if($t->image_or_video)
                                <img src="{{ Storage::url($t->image_or_video) }}" class="h-10 w-10 object-cover rounded-full border border-border shadow-sm">
                            @else
                                <div class="h-10 w-10 bg-primary/10 text-primary flex items-center justify-center rounded-full font-bold text-xs">
                                    {{ substr($t->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="font-bold text-textPrimary">{{ $t->name }}</div>
                            <div class="text-[10px] text-muted">{{ $t->role }}</div>
                        </td>
                        <td>
                            <div class="text-xs text-textSecondary italic max-w-sm line-clamp-2">"{{ $t->content }}"</div>
                        </td>
                        <td>
                            <div class="flex justify-center items-center gap-2">
                                <button @click="
                                    editData = { 
                                        id: '{{ $t->id }}', 
                                        name: '{{ addslashes($t->name) }}', 
                                        role: '{{ addslashes($t->role) }}', 
                                        content: '{{ addslashes($t->content) }}' 
                                    };
                                    showEdit = true;
                                " class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('landing-testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin hapus testimoni ini?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-danger hover:bg-danger/10 rounded-lg transition-colors" title="Hapus">
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
    </div>

    <!-- Modal Create -->
    <div x-show="showCreate" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/50" @click="showCreate = false"></div>

            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-textPrimary">Tambah Testimoni</h3>
                    <button @click="showCreate = false" class="text-muted hover:text-textPrimary">
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
                        <input type="file" name="image_or_video" class="text-xs text-textSecondary file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="showCreate = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Testimoni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showEdit" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/50" @click="showEdit = false"></div>

            <div x-show="showEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-textPrimary">Edit Testimoni</h3>
                    <button @click="showEdit = false" class="text-muted hover:text-textPrimary">
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
                        <input type="file" name="image_or_video" class="text-xs text-textSecondary file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="showEdit = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Perbarui Testimoni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

