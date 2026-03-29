<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingFacility;
use Illuminate\Http\Request;

class LandingFacilityController extends Controller
{
    public function index()
    {
        $facilities = LandingFacility::orderBy('order')->get();
        return view('admin.landing-facilities.index', compact('facilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing', 'public');
        }

        LandingFacility::create($data);
        logAktivitas('Kelola Fasilitas', 'Menambah fasilitas baru: ' . $data['title']);
        return back()->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function update(Request $request, LandingFacility $landingFacility)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing', 'public');
        }

        $landingFacility->update($data);
        logAktivitas('Kelola Fasilitas', 'Memperbarui fasilitas: ' . $landingFacility->title);
        return back()->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy(LandingFacility $landingFacility)
    {
        $title = $landingFacility->title;
        $landingFacility->delete();
        logAktivitas('Kelola Fasilitas', 'Menghapus fasilitas: ' . $title);
        return back()->with('success', 'Fasilitas berhasil dihapus!');
    }
}
