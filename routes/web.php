<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\KreditController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', function () {
    return view('welcome');
});

// Produk Sarimadu (public pages)
Route::get('/kredit', fn () => view('kredit.kredit'))->name('kredit');
Route::get('/deposito', fn () => view('deposito.deposito'))->name('deposito');
Route::get('/rekening', fn () => view('rekening.rekening'))->name('rekening');
Route::get('/about-us', fn () => view('about-us'))->name('about');

// Authenticated area
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Nasabah (JANGAN duplikasi store dengan resource)
    Route::get('/nasabah/create', [NasabahController::class, 'create'])->name('nasabah.create');
    Route::post('/nasabah',       [NasabahController::class, 'store'])->name('nasabah.store');
    Route::resource('nasabah', NasabahController::class)->only(['update','show','edit']); // store sudah di atas
    Route::get('/nasabah/{nasabah}/preview', [NasabahController::class, 'previewKtp'])->name('nasabah.preview');
    Route::get('/nasabah/{nasabah}/bukti', [NasabahController::class, 'downloadKtp'])->name('nasabah.bukti');
    Route::get('/nasabah/{nasabah}/edit', [NasabahController::class, 'edit'])->name('nasabah.edit');
    Route::put('/nasabah/{nasabah}', [NasabahController::class, 'update'])->name('nasabah.update');

    Route::get('/pengajuan/{type}/{id}/detail', [DashboardController::class, 'showDetail'])->name('nasabah.detail');
    Route::delete('/pengajuan/{type}/{id}/cancel', [DashboardController::class, 'cancelPengajuan'])->name('nasabah.cancel');

    Route::middleware('can:submit-applications')->group(function () {
        // Rekening
        Route::get('/rekening/create', [RekeningController::class, 'create'])->name('rekening.create');
        Route::post('/rekening',       [RekeningController::class, 'store'])->name('rekening.store');
        Route::get('/rekening/{rekening}/edit', [RekeningController::class, 'edit'])->name('rekening.edit');
        Route::patch('/rekening/{rekening}', [RekeningController::class, 'update'])->name('rekening.update');

        // Kredit
        Route::get('/kredit/create', [KreditController::class, 'create'])->name('kredit.create');
        Route::post('/kredit/store', [KreditController::class, 'store'])->name('kredit.store');
        Route::get('/kredit/{kredit}/edit', [KreditController::class, 'edit'])->name('kredit.edit');
        Route::patch('/kredit/{kredit}', [KreditController::class, 'update'])->name('kredit.update');

        // Deposito
        Route::get('/deposito/create', [DepositoController::class, 'create'])->name('deposito.create');
        Route::post('/deposito',       [DepositoController::class, 'store'])->name('deposito.store');
        Route::get('/deposito/{deposito}/edit', [DepositoController::class, 'edit'])->name('deposito.edit');
        Route::patch('/deposito/{deposito}', [DepositoController::class, 'update'])->name('deposito.update');
    });
    
    // Receipt
    Route::get('/pengajuan/cetak/{tipe}/{id}', [ReceiptController::class, 'cetak'])->name('pengajuan.cetak');
  
    // Kredit
    Route::get('/kredit/{kredit}/bukti', [KreditController::class, 'downloadBukti'])
        ->name('kredit.bukti');
    Route::get('/kredit/{kredit}/preview', [KreditController::class, 'previewBukti'])
        ->name('kredit.preview');

    // Deposito
    Route::get('/deposito/{deposito}/bukti', [DepositoController::class, 'downloadBukti'])
        ->name('deposito.bukti');
    Route::get('/deposito/{deposito}/preview', [DepositoController::class, 'previewBukti'])
        ->name('deposito.preview');

    // Admin
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::patch('/admin/rekening/{rekening}', [AdminController::class, 'updateRekening'])->name('admin.rekening.update');
        Route::patch('/admin/kredit/{kredit}', [AdminController::class, 'updateKredit'])->name('admin.kredit.update');
        Route::patch('/admin/deposito/{deposito}', [AdminController::class, 'updateDeposito'])->name('admin.deposito.update');
    });
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pengajuan',[PengajuanController::class,'index'])->name('pengajuan.index'); // list + filter + sort + pagination
    Route::get('/pengajuan/{type}/{id}', [PengajuanController::class,'show'])->name('pengajuan.show');   // detail (AJAX)
    Route::post('/pengajuan/{type}/{id}/status', [PengajuanController::class,'updateStatus'])->name('pengajuan.status');
    Route::post('/pengajuan/{type}/{id}/notes',  [PengajuanController::class,'addNote'])->name('pengajuan.notes');
    Route::post('/pengajuan/{type}/{id}/assign', [PengajuanController::class,'assign'])->name('pengajuan.assign');
    Route::get('/pengajuan/{type}/{id}/preview',  [PengajuanController::class,'preview'])->name('pengajuan.preview');
    Route::get('/pengajuan/{type}/{id}/download', [PengajuanController::class,'download'])->name('pengajuan.download');
});

// Guest auth
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // OTP
    Route::get('verify-otp',  [RegisteredUserController::class, 'showVerifyForm'])->name('verify.otp.form');
    Route::post('verify-otp', [RegisteredUserController::class, 'verifyOTP'])->name('verify.otp');
    Route::post('resend-otp', [RegisteredUserController::class, 'resendOTP'])->name('resend.otp');
});

require __DIR__.'/auth.php';