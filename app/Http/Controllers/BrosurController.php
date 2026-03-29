<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrochureDownload;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class BrosurController extends Controller
{
    public function download(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        BrochureDownload::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        logAktivitas('Download Brosur', 'Mendownload brosur oleh: ' . $request->name . ' (' . $request->email . ')');

        $brochurePath = Setting::where('key', 'brochure')->value('value');
        
        if ($brochurePath && Storage::disk('public')->exists($brochurePath)) {
            return Storage::disk('public')->download($brochurePath, 'Brosur-SD-Muhammadiyah.pdf');
        }

        return back()->with('error', 'Maaf, file brosur belum tersedia saat ini.');
    }
}
