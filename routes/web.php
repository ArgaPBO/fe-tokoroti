<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Branch\DashboardBranchController;
use Illuminate\Support\Facades\Route;

// Controllers Halaman Utama
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;

// Controllers Autentikasi (Halaman Tampilan)
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE PUBLIK / HALAMAN UTAMA ---
Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');


// --- RUTE GRUP ADMIN (TIDAK PAKAI LOGIN SEMENTARA) ---
Route::prefix('admin')      // 1. URL diawali /admin
  ->name('admin.')      // 2. Nama rute diawali admin.
  //
  // BARIS ->middleware(['auth']) DIHAPUS SEMENTARA UNTUK DEVELOPMENT
  //
  ->group(function () {

    // Rute Dashboard
    // URL: /admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Produk
    // URL: /admin/products
    Route::get('/products', [ProductController::class, 'index'])->name('product');

    Route::get('/branchs', [BranchController::class, 'index'])->name('branch');

    Route::get('/financial', [FinancialController::class, 'index'])->name('financial');

    // Rute Laporan
    // URL: /admin/laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    // URL: /admin/laporan/create
    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
    // URL: /admin/laporan (Method POST)
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

  });


// --- RUTE GRUP ADMIN (TIDAK PAKAI LOGIN SEMENTARA) ---
Route::prefix('branch')      // 1. URL diawali /branch
  ->name('branch.')      // 2. Nama rute diawali branch.
  //
  // BARIS ->middleware(['auth']) DIHAPUS SEMENTARA UNTUK DEVELOPMENT
  //
  ->group(function () {

    // Rute Dashboard
    // URL: /branch
    Route::get('/', [DashboardBranchController::class, 'index'])->name('dashboard');



  });


// --- RUTE LAIN-LAIN (TEMPLATE) ---

// Locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// Halaman Tampilan Autentikasi
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

// Kita tidak memerlukan 'require __DIR__.'/auth.php';'
// karena kita menonaktifkan middleware-nya.
