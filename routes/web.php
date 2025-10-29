<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Route dashboard yang otomatis redirect berdasarkan role
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('warga.dashboard');
        }
    }
    return redirect('/login');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 Grup route khusus ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Permohonan Masuk
    Route::get('/permohonan', [AdminController::class, 'permohonan'])->name('permohonan');
    
    // Data Surat
    Route::get('/surat', [AdminController::class, 'datasurat'])->name('surat');
    Route::get('/surat/{id}', [AdminController::class, 'detailSurat'])->name('surat.detail');
    
    // ✨ Route alias untuk detail-surat (backward compatibility)
    Route::get('/datasurat/{id}', [AdminController::class, 'detailSurat'])->name('detail-surat');
    
    // Update Status
    Route::match(['post', 'patch'], '/surat/{id}/update-status', [AdminController::class, 'updateStatus'])->name('surat.update-status');
    
    // TTD Digital
    Route::get('/ttddigital', [AdminController::class, 'ttddigital'])->name('ttddigital');
    Route::get('/tanda-tangan/{id}', [AdminController::class, 'tandaTangan'])->name('tanda.tangan');
    Route::post('/tanda-tangan/upload', [AdminController::class, 'uploadTandaTangan'])->name('tanda.tangan.upload');
    Route::get('/tanda-tangan/konfirmasi/{id}', [AdminController::class, 'konfirmasiTandaTangan'])->name('tanda.tangan.konfirmasi');
    Route::post('/tanda-tangan/simpan/{id}', [AdminController::class, 'simpanTandaTangan'])->name('tanda.tangan.simpan');
    
    // Generate Surat
    Route::post('/surat/generate/{id}', [AdminController::class, 'generateSurat'])->name('surat.generate');
    
    // Profil Admin
    Route::get('/profiladmin', [AdminController::class, 'profiladmin'])->name('profiladmin');
});

// 🔐 Grup route khusus WARGA
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');
    Route::get('/pilih-jenis-surat', [WargaController::class, 'pilihJenisSurat'])->name('pilih-jenis-surat');
    Route::get('/buat-surat/{jenis}', [WargaController::class, 'buatSurat'])->name('buat-surat');
    Route::post('/surat/store', [WargaController::class, 'storeSurat'])->name('surat.store');
    Route::get('/riwayat', [WargaController::class, 'riwayat'])->name('riwayat');
    Route::get('/surat/{id}', [WargaController::class, 'detailSurat'])->name('surat.detail');

    
    Route::get('/surat-masuk', [WargaController::class, 'suratMasuk'])->name('surat.masuk');
    Route::get('/surat-keluar', [WargaController::class, 'suratKeluar'])->name('surat.keluar');
    Route::get('/data-warga', [WargaController::class, 'dataWarga'])->name('data.warga');
    Route::get('/pengaturan', [WargaController::class, 'pengaturan'])->name('pengaturan');
    Route::get('/surat/create', [WargaController::class, 'create'])->name('surat.create');
    Route::get('/surat/riwayat', [WargaController::class, 'riwayat'])->name('surat.riwayat');


});

// 🚪 Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// 🔧 Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';