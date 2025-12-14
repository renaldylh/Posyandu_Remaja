<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredictionController;

// Optimized: Use Route::view() instead of closures for better performance
Route::view('/', 'home')->name('home');
Route::view('/prediksi', 'prediksi')->name('prediksi');
Route::view('/login', 'login')->name('login');
Route::view('/kunjungan', 'kunjungan')->name('kunjungan');
Route::view('/gizi', 'gizi')->name('gizi');
Route::view('/anemia', 'anemia')->name('anemia');
Route::view('/tekanan-darah', 'tekananDarah')->name('tekanan-darah');
Route::view('/gula-darah', 'gulaDarah')->name('gula-darah');


Route::post('/predict', [PredictionController::class, 'predict'])->name('predict');
Route::get('/test-predict', [PredictionController::class, 'testPredict']);


// Admin Routes
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::resource('petugas', PetugasController::class)
        ->parameters(['petugas' => 'petugas'])
        ->except(['show']);
    Route::resource('peserta', PesertaController::class)
        ->parameters(['peserta' => 'peserta'])
        ->except(['show']);
    Route::get('pemeriksaan/export', [PemeriksaanController::class, 'export'])->name('pemeriksaan.export');
    Route::get('pemeriksaan/export-pdf', [PemeriksaanController::class, 'exportPdf'])->name('pemeriksaan.export-pdf');
    Route::resource('pemeriksaan', PemeriksaanController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
});

// Handle login POST
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
