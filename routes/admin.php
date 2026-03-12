<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\BiayaController;
use App\Http\Controllers\Admin\PaudTkController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\LaporanKeuanganController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\CetakController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PasswordPanitiaController;

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ================= SUPERADMIN + ADMIN =================
        Route::middleware('role:superadmin,admin')->group(function () {

            Route::get('/pendaftar', [PendaftarController::class, 'index'])
                ->name('pendaftar.index');

            Route::get('/pendaftar/{siswa}', [PendaftarController::class, 'show'])
                ->name('pendaftar.show');

            Route::get('/pendaftaran/{siswa}/cetak', [CetakController::class, 'formulir'])
                ->name('pendaftaran.cetak');

            Route::get('/siswa/kelas-1', [SiswaController::class, 'kelas1'])
                ->name('siswa.kelas1');
        });

        // ================= SUPERADMIN + KEUANGAN =================
        Route::middleware('role:superadmin,keuangan')->group(function () {

            Route::get('/keuangan', [KeuanganController::class, 'index'])
                ->name('keuangan.index');

            Route::post('/pembayaran', [PembayaranController::class, 'store'])
                ->name('pembayaran.store');

            Route::get('/pembayaran/{pembayaran}/nota', [CetakController::class, 'nota'])
                ->name('pembayaran.nota');
        });

        // ================= SUPERADMIN ONLY =================
        Route::middleware('role:superadmin')->group(function () {

            Route::resource('tahun-ajaran', TahunAjaranController::class)
                ->only(['index','store']);

            Route::patch('/tahun-ajaran/{tahunAjaran}/aktifkan',
                [TahunAjaranController::class, 'aktifkan'])
                ->name('tahun-ajaran.aktifkan');

            Route::resource('biaya', BiayaController::class)
                ->only(['index','store','destroy']);

            // Route Biaya toggle
            Route::patch('/admin/biaya/{biaya}/toggle',
                [BiayaController::class, 'toggle']
            )->name('biaya.toggle');

            Route::delete('/voucher/destroy-all', [VoucherController::class, 'destroyAll'])
                ->name('voucher.destroyAll');

            Route::resource('voucher', VoucherController::class)
                ->except(['show','edit','update']);

            Route::patch('/voucher/{voucher}/toggle', [VoucherController::class, 'toggle'])
                ->name('voucher.toggle');

            Route::delete('/paud-tk/destroy-all', [PaudTkController::class, 'destroyAll'])
                ->name('paud-tk.destroyAll');

            Route::resource('paud-tk', PaudTkController::class)
                ->except(['show','edit','update']);

            Route::post('/paud-tk/import', [PaudTkController::class,'import'])->name('paud-tk.import');
            Route::get('/paud-tk/template', [PaudTkController::class,'template'])->name('paud-tk.template');

            Route::post('/paud-tk/{paudTk}/toggle',
            [PaudTkController::class, 'toggle'])
            ->name('paud-tk.toggle');

            Route::get('/laporan/keuangan',
                [LaporanKeuanganController::class,'index'])
                ->name('laporan.keuangan');

            Route::get('/log-aktivitas',
                [LogAktivitasController::class,'index'])
                ->name('log.aktivitas');
            
            Route::delete('/log-aktivitas/destroy-all', [LogAktivitasController::class, 'destroyAll'])
                ->name('logs.destroyAll');

            Route::resource('users', UserController::class)
                ->only(['index','store']);

            Route::delete(
                'tahun-ajaran/{tahunAjaran}',
                [TahunAjaranController::class, 'destroy']
            )->name('tahun-ajaran.destroy');

            Route::get('/password-panitia',
            [PasswordPanitiaController::class,'index'])
            ->name('admin.password.panitia');

            Route::post('/password-panitia/store',
                [PasswordPanitiaController::class,'store'])
                ->name('admin.password.panitia.store');

        });
    });
