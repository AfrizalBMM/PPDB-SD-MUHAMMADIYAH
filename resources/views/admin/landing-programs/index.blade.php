@extends('layouts.admin')

@section('title', 'Kelola Program Unggulan')
@section('page-title', 'Program Unggulan')

@section('content')
<div x-data="{ 
    showCreate: false, 
    showEdit: false,
    editData: { id: '', title: '', description: '', order: 0 }
}">
    <div class="card">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-textPrimary">Daftar Program & Ekskul</h2>
                <p class="text-textSecondary text-sm">Kelola program unggulan yang ditampilkan di landing page.</p>
            </div>
            <button @click="showCreate = true" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Program
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-16 text-center">Urutan</th>
                        <th>Program</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th class="w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $p)
                    <tr>
                        <td class="text-center font-medium">{{ $p->order }}</td>
                        <td>
                            <div class="font-bold text-textPrimary">{{ $p->title }}</div>
                        </td>
                        <td>
                            <div class="text-xs text-textSecondary max-w-xs line-clamp-2">{{ $p->description }}</div>
                        </td>
                        <td>
                            @if($p->image)
                                <img src="{{ Storage::url($p->image) }}" class="h-10 w-10 object-cover rounded-lg border border-border">
                            @else
                                <div class="h-10 w-10 bg-background flex items-center justify-center rounded-lg border border-border">
                                    <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-center items-center gap-2">
                                <button @click="
                                    editData = { 
                                        id: '{{ $p->id }}', 
                                        title: '{{ addslashes($p->title) }}', 
                                        description: '{{ addslashes($p->description) }}', 
                                        order: '{{ $p->order }}' 
                                    };
                                    showEdit = true;
                                " class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('landing-programs.destroy', $p->id) }}" method="POST" onsubmit="return window.globalConfirmSubmit(this, 'Yakin hapus program ini?', { title: 'Konfirmasi Hapus' });" class="inline">
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

        <div class="mt-4 flex flex-col gap-3 border-t border-border pt-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex items-center gap-2 text-xs text-textSecondary">
                <label for="perPagePrograms">Tampilkan</label>
                <select id="perPagePrograms" name="per_page" onchange="this.form.submit()" class="rounded border border-border px-2 py-1 text-xs">
                    @foreach([10,20,50,100] as $size)
                        <option value="{{ $size }}" {{ (int) request('per_page', $perPage ?? 20) === $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span>data</span>
            </form>

            <div>
                {{ $programs->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div x-show="showCreate" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/50" @click="showCreate = false"></div>

            <div x-show="showCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-textPrimary">Tambah Program Baru</h3>
                    <button @click="showCreate = false" class="text-muted hover:text-textPrimary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('landing-programs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="label">Judul Program</label>
                        <input type="text" name="title" class="input" required placeholder="Cth: Tahfidz Qur'an">
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Deskripsi</label>
                        <textarea name="description" class="input h-auto py-3" rows="3" required placeholder="Jelaskan tentang program ini..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="label">Urutan</label>
                            <input type="number" name="order" value="0" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Icon/Gambar</label>
                            <input type="file" name="image" class="text-xs text-textSecondary file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="showCreate = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Program</button>
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
                    <h3 class="text-xl font-bold text-textPrimary">Edit Program</h3>
                    <button @click="showEdit = false" class="text-muted hover:text-textPrimary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="'{{ url('admin/landing-programs') }}/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="space-y-1.5">
                        <label class="label">Judul Program</label>
                        <input type="text" name="title" x-model="editData.title" class="input" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Deskripsi</label>
                        <textarea name="description" x-model="editData.description" class="input h-auto py-3" rows="3" required></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="label">Urutan</label>
                            <input type="number" name="order" x-model="editData.order" class="input">
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Ganti Icon/Gambar (Opsional)</label>
                            <input type="file" name="image" class="text-xs text-textSecondary file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="showEdit = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Perbarui Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

