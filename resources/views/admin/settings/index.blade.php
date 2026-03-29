@extends('layouts.admin')

@section('title', 'Setting Website')
@section('page-title', 'Setting Website')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="card mb-6">
        <div class="flex items-center gap-3 border-b border-border pb-4 mb-6">
            <div class="p-2 bg-primary/10 text-primary rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-textPrimary">Konfigurasi Landing Page</h2>
                <p class="text-textSecondary text-sm">Kelola konten utama, SEO, dan identitas sekolah.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-8">
                {{-- SEO & KONTAK --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 flex items-center justify-center bg-gray-100 text-textSecondary text-xs font-bold rounded-full">01</span>
                        <h3 class="font-bold text-textPrimary">SEO & Informasi Kontak</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="label">Meta Title (Judul Tab Browser)</label>
                            <input type="text" name="seo_title" value="{{ $settings['seo_title'] ?? 'PPDB SD Muhammadiyah Wonorejo' }}" class="input" placeholder="Cth: PPDB 2026 - SD Muhammadiyah Wonorejo">
                            <p class="text-[10px] text-muted italic">Maksimal 60 karakter disarankan.</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Nomor WhatsApp (Admin)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted text-sm">+</span>
                                <input type="text" name="wa_number" value="{{ $settings['wa_number'] ?? '628123456789' }}" class="input pl-7" placeholder="62xxxxxxxxxx">
                            </div>
                            <p class="text-[10px] text-muted italic">Tanpa tanda + atau spasi.</p>
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="label">Meta Description (Penjelasan Singkat di Google)</label>
                            <textarea name="seo_description" rows="2" class="input h-auto py-3" placeholder="Deskripsikan sekolah Anda...">{{ $settings['seo_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </section>

                <hr class="border-border">

                {{-- HERO SECTION --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 flex items-center justify-center bg-gray-100 text-textSecondary text-xs font-bold rounded-full">02</span>
                        <h3 class="font-bold text-textPrimary">Banner Utama (Hero Section)</h3>
                    </div>
                    
                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="label">Headline Utama</label>
                            <input type="text" name="hero_headline" value="{{ $settings['hero_headline'] ?? '' }}" class="input" placeholder="Kalimat ajakan besar...">
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Sub Headline</label>
                            <textarea name="hero_subheadline" rows="2" class="input h-auto py-3" placeholder="Penjelasan tambahan...">{{ $settings['hero_subheadline'] ?? '' }}</textarea>
                        </div>
                        <div class="w-full md:w-1/3 space-y-1.5">
                            <label class="label">Kuota Tersisa (Urgency)</label>
                            <input type="number" name="hero_quota" value="{{ $settings['hero_quota'] ?? '' }}" class="input" placeholder="Cth: 15">
                        </div>
                    </div>
                </section>

                <hr class="border-border">

                {{-- VALUE PROP --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 flex items-center justify-center bg-gray-100 text-textSecondary text-xs font-bold rounded-full">03</span>
                        <h3 class="font-bold text-textPrimary">Keunggulan Utama (Value Prop)</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @for($i=1; $i<=4; $i++)
                            <div class="p-4 bg-background border border-border rounded-xl space-y-3">
                                <h4 class="text-xs font-bold text-primary uppercase">Keunggulan {{ $i }}</h4>
                                <div class="space-y-2">
                                    <input type="text" name="vp_title_{{$i}}" value="{{ $settings['vp_title_'.$i] ?? '' }}" class="input bg-white" placeholder="Judul Singkat">
                                    <textarea name="vp_desc_{{$i}}" rows="2" class="input bg-white h-auto py-2 text-xs" placeholder="Deskripsi">{{ $settings['vp_desc_'.$i] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endfor
                    </div>
                </section>

                <hr class="border-border">

                {{-- FILES --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 flex items-center justify-center bg-gray-100 text-textSecondary text-xs font-bold rounded-full">04</span>
                        <h3 class="font-bold text-textPrimary">Media & Dokumen</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="label">Logo Website</label>
                            <div class="flex items-center gap-4">
                                @if(isset($settings['logo']))
                                    <img src="{{ Storage::url($settings['logo']) }}" class="h-12 w-12 object-contain bg-gray-50 rounded p-1 border">
                                @endif
                                <input type="file" name="logo" class="text-xs text-textSecondary file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="label">Download Brosur (PDF)</label>
                            <div class="flex items-center gap-4">
                                @if(isset($settings['brochure']))
                                    <div class="p-2 bg-success/10 text-success rounded">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="brochure" class="text-xs text-textSecondary file:mr-4 file:py-2 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-8 border-t border-border flex justify-end gap-3">
                    <button type="reset" class="btn-secondary">Reset</button>
                    <button type="submit" class="btn-primary">Simpan Konfigurasi</button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
