<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredictionController;
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/prediksi', function () {
    return view('prediksi');
})->name('prediksi');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/kunjungan', function () {
    return view('kunjungan');
})->name('kunjungan');

Route::get('/gizi', function () {
    return view('gizi');
})->name('gizi');

Route::get('/anemia', function () {
    return view('anemia');
})->name('anemia');

Route::get('/tekanan-darah', function () {
    return view('tekananDarah');
})->name('tekanan-darah');

Route::get('gula-darah', function () {
    return view('gulaDarah');
})->name('gula-darah');

Route::post('/predict', [PredictionController::class, 'predict'])->name('predict');
Route::get('/test-predict', [PredictionController::class, 'testPredict']);


// Admin Routes
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::resource('petugas', PetugasController::class)
        ->parameters(['petugas' => 'petugas'])
        ->except(['show']);
    Route::resource('peserta', PesertaController::class)
        ->parameters(['peserta' => 'peserta'])
        ->except(['show']);
    Route::get('pemeriksaan/export', [PemeriksaanController::class, 'export'])->name('pemeriksaan.export');
    Route::get('pemeriksaan/export-pdf', [PemeriksaanController::class, 'exportPdf'])->name('pemeriksaan.export-pdf');
    Route::resource('pemeriksaan', PemeriksaanController::class)->except(['show']);
});

// Handle login POST
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
