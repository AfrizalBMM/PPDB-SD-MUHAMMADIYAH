<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CetakController;
use App\Livewire\PendaftaranWizard;
use App\Models\Siswa;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// landing page
Route::view('/', 'public.landing')->name('public.landing');

// pendaftaran calon siswa (Livewire)
Route::get('/pendaftaran', PendaftaranWizard::class)
    ->name('pendaftaran.public');

// sukses pendaftaran + review data
Route::get('/pendaftaran/sukses/{siswa}', function (Siswa $siswa) {
    $siswa->load([
        'registration',
        'ibu',
        'ayah',
        'wali',
        'alamat',
        'dataPendukung.paudTk',
        'tagihan.biaya',
    ]);

    return view('pendaftaran.sukses', compact('siswa'));
})->name('pendaftaran.sukses');


// cetak formulir pendaftaran (public access setelah daftar)
Route::get('/cetak/formulir/{siswa}', [CetakController::class, 'formulir'])
    ->name('cetak.formulir');

/*
|--------------------------------------------------------------------------
| AUTH (LOGIN / LOGOUT)
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'form'])->name('login');
Route::post('/login', [LoginController::class, 'proses'])->name('login.proses');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
