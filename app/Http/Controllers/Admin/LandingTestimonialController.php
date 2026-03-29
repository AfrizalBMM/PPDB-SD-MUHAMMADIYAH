<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingTestimonial;
use Illuminate\Http\Request;

class LandingTestimonialController extends Controller
{
    public function index()
    {
        $testimonials = LandingTestimonial::latest()->get();
        return view('admin.landing-testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_or_video' => 'nullable|image|max:2048'
        ]);
        
        if ($request->hasFile('image_or_video')) {
            $data['image_or_video'] = $request->file('image_or_video')->store('landing', 'public');
        }

        LandingTestimonial::create($data);
        logAktivitas('Kelola Testimoni', 'Menambah testimoni baru dari: ' . $data['name']);
        return back()->with('success', 'Testimonial berhasil ditambahkan!');
    }

    public function update(Request $request, LandingTestimonial $landingTestimonial)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_or_video' => 'nullable|image|max:2048'
        ]);
        
        if ($request->hasFile('image_or_video')) {
            $data['image_or_video'] = $request->file('image_or_video')->store('landing', 'public');
        }

        $landingTestimonial->update($data);
        logAktivitas('Kelola Testimoni', 'Memperbarui testimoni dari: ' . $landingTestimonial->name);
        return back()->with('success', 'Testimonial berhasil diperbarui!');
    }

    public function destroy(LandingTestimonial $landingTestimonial)
    {
        $name = $landingTestimonial->name;
        $landingTestimonial->delete();
        logAktivitas('Kelola Testimoni', 'Menghapus testimoni dari: ' . $name);
        return back()->with('success', 'Testimonial berhasil dihapus!');
    }
}
