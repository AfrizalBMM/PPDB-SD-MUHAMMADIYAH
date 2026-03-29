<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingProgram;
use Illuminate\Http\Request;

class LandingProgramController extends Controller
{
    public function index()
    {
        $programs = LandingProgram::orderBy('order')->get();
        return view('admin.landing-programs.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing', 'public');
        }

        LandingProgram::create($data);
        logAktivitas('Kelola Program', 'Menambah program unggulan baru: ' . $data['title']);
        return back()->with('success', 'Program unggulan berhasil ditambahkan!');
    }

    public function update(Request $request, LandingProgram $landingProgram)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing', 'public');
        }

        $landingProgram->update($data);
        logAktivitas('Kelola Program', 'Memperbarui program unggulan: ' . $landingProgram->title);
        return back()->with('success', 'Program unggulan berhasil diperbarui!');
    }

    public function destroy(LandingProgram $landingProgram)
    {
        $title = $landingProgram->title;
        $landingProgram->delete();
        logAktivitas('Kelola Program', 'Menghapus program unggulan: ' . $title);
        return back()->with('success', 'Program unggulan berhasil dihapus!');
    }
}
