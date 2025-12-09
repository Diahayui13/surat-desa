<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// =====================
// 🏡 HALAMAN AWAL
// =====================
Route::get('/', function () {
    return view('welcome');
});

// =====================
// 🧭 DASHBOARD UMUM (redirect sesuai role)
// =====================
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    if (!$user) {
        return redirect('/login');
    }

    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('warga.dashboard');
})->middleware('auth')->name('dashboard');

// =====================
// 👑 ROUTE ADMIN
// =====================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Permohonan
    Route::get('/permohonan', [AdminController::class, 'permohonan'])->name('permohonan');
    
    // Proses Surat (Upload TTD)
    Route::get('/tanda-tangan/{id}', [AdminController::class, 'tandaTangan'])->name('tanda.tangan');
    Route::post('/upload-ttd', [AdminController::class, 'uploadTandaTangan'])->name('upload.ttd');
    
    // Preview & Konfirmasi TTD
    Route::get('/ttd-konfirmasi/{id}', [AdminController::class, 'konfirmasiTandaTangan'])->name('tanda.tangan.konfirmasi');
    Route::post('/simpan-ttd/{id}', [AdminController::class, 'simpanTandaTangan'])->name('simpan.ttd');
    Route::post('/batal-konfirmasi/{id}', [AdminController::class, 'batalKonfirmasi'])->name('batal.konfirmasi');
    
    // Selesaikan Surat (dengan notifikasi)
    Route::patch('/surat/{id}/update-status', [AdminController::class, 'updateStatus'])->name('surat.update-status');
    
    // Data Surat
    Route::get('/data-surat', [AdminController::class, 'datasurat'])->name('data.surat');
    Route::get('/surat/{id}', [AdminController::class, 'detailSurat'])->name('surat.detail');
    
    // Preview & Download Surat
    Route::get('/surat/{id}/preview', [AdminController::class, 'previewSurat'])->name('surat.preview');
    Route::get('/surat/{id}/download-pdf', [AdminController::class, 'downloadPDF'])->name('surat.download-pdf');

    // Profil
    Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
});

// =====================
// 👥 ROUTE WARGA
// =====================
Route::middleware(['auth', 'role:warga'])
    ->prefix('warga')
    ->name('warga.')
    ->group(function () {

        // 🏠 Dashboard
        Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');

        // 🔔 Notifikasi
        Route::get('/notifications', [WargaController::class, 'notifications'])->name('notifications');
        Route::get('/notifications/{id}/read', [WargaController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [WargaController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');

        // ✉️ Pengajuan Surat
        Route::get('/buat-surat', [WargaController::class, 'pilihJenisSurat'])->name('buat_surat');
        Route::get('/buat-surat/{jenis}', [WargaController::class, 'buatSurat'])->name('buat_surat.jenis');
        Route::post('/surat/store', [WargaController::class, 'storeSurat'])->name('surat.store');

        // 📩 Surat Masuk & Riwayat
        Route::get('/surat/masuk', [WargaController::class, 'suratMasuk'])->name('surat.masuk');
        Route::get('/status', [WargaController::class, 'status'])->name('status');
        Route::get('/riwayat', [WargaController::class, 'riwayat'])->name('riwayat');

        // ⚙️ Profil & Pengaturan
        Route::get('/profil', [WargaController::class, 'profil'])->name('profil');
        Route::get('/pengaturan', [WargaController::class, 'pengaturan'])->name('pengaturan');
        
        // 📄 Preview & Download Surat
        Route::get('/surat/{id}/preview', [WargaController::class, 'previewSurat'])->name('surat.preview');
        Route::get('/surat/{id}/download-pdf', [WargaController::class, 'downloadPDF'])->name('surat.download-pdf');
    });

// =====================
// 🚪 LOGOUT
// =====================
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// =====================
// ⚙️ PROFIL GLOBAL
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================
// 🔐 AUTH ROUTE
// =====================
require __DIR__ . '/auth.php';