<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WargaController extends Controller
{
    // Halaman pilih jenis surat
    public function pilihJenisSurat()
{
    $jenisSurat = [
        [
            'nama' => 'Surat Keterangan Domisili',
            'icon' => 'fa-home',
            'slug' => 'domisili',
            'deskripsi' => 'Surat keterangan tempat tinggal'
        ],
        [
            'nama' => 'Surat Keterangan Usaha',
            'icon' => 'fa-briefcase',
            'slug' => 'usaha',
            'deskripsi' => 'Surat keterangan memiliki usaha'
        ],
        [
            'nama' => 'Surat Pengantar KTP',
            'icon' => 'fa-id-card',
            'slug' => 'ktp',
            'deskripsi' => 'Surat pengantar pembuatan KTP'
        ],
        [
            'nama' => 'Surat Keterangan Tidak Mampu',
            'icon' => 'fa-hand-holding-heart',
            'slug' => 'sktm',
            'deskripsi' => 'Surat keterangan tidak mampu'
        ],
    ];

    return view('warga.pilih-jenis-surat', compact('jenisSurat'));
}
    
// Method untuk menampilkan form pengajuan surat berdasarkan jenis
public function buatSurat($jenis)
{
    // Mapping slug ke nama lengkap jenis surat
    $jenisSuratMap = [
        'domisili' => 'Surat Keterangan Domisili',
        'usaha' => 'Surat Keterangan Usaha',
        'ktp' => 'Surat Pengantar KTP',
        'sktm' => 'Surat Keterangan Tidak Mampu',
    ];
    
    // Cek apakah jenis surat valid
    if (!isset($jenisSuratMap[$jenis])) {
        return redirect()->route('warga.pilih-jenis-surat')
            ->with('error', 'Jenis surat tidak valid!');
    }
    
    $jenisSurat = $jenisSuratMap[$jenis];
    $user = Auth::user();
    
    return view('warga.form-pengajuan', compact('jenisSurat', 'jenis', 'user'));
}

// Method untuk menyimpan pengajuan surat
public function storeSurat(Request $request)
{
    // Validasi dasar
    $validated = $request->validate([
        'jenis_surat' => 'required|string',
        'nama_pemohon' => 'required|string|max:255',
        'nik_pemohon' => 'required|string|max:20',
        'tempat_lahir' => 'nullable|string|max:255',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        'pekerjaan' => 'nullable|string|max:255',
        'alamat' => 'nullable|string',
        'keperluan' => 'nullable|string',
        
        // File uploads (opsional)
        'file_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'file_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'file_pendukung' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    
    // Upload files jika ada
    $fileFields = ['file_ktp', 'file_kk', 'file_pendukung'];
    foreach ($fileFields as $field) {
        if ($request->hasFile($field)) {
            $validated[$field] = $request->file($field)->store('uploads/surat', 'public');
        }
    }
    
    // Set data tambahan
    $validated['user_id'] = Auth::id();
    $validated['status'] = 'Menunggu';
    $validated['judul'] = $request->jenis_surat;
    $validated['tanggal_pengajuan'] = now();
    
    // Simpan ke database
    $surat = Surat::create($validated);
    
    return redirect()->route('warga.dashboard')
        ->with('success', 'Pengajuan surat berhasil dikirim! Menunggu verifikasi admin.');
}

    // Form pengajuan surat (dynamic berdasarkan jenis)
    public function formPengajuan($jenis)
    {
        $jenisSurat = str_replace('-', ' ', $jenis);
        $jenisSurat = ucwords($jenisSurat);
        
        return view('warga.form-pengajuan', compact('jenisSurat'));
    }
    
    // Simpan pengajuan surat
    public function storePengajuan(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required',
            'nama_pemohon' => 'required|string|max:255',
            'nik_pemohon' => 'required|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pekerjaan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'keperluan' => 'nullable|string',
            
            // SKTM specific
            'nama_anak' => 'nullable|string|max:255',
            'tempat_lahir_anak' => 'nullable|string|max:255',
            'tanggal_lahir_anak' => 'nullable|date',
            'jenis_kelamin_anak' => 'nullable|in:Laki-laki,Perempuan',
            'pendidikan' => 'nullable|string|max:255',
            
            // File uploads
            'file_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_surat_pernyataan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_foto_rumah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Upload files
        $fileFields = ['file_ktp', 'file_kk', 'file_surat_pernyataan', 'file_foto_rumah'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('uploads/pengajuan', 'public');
            }
        }
        
        // Set user_id
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Menunggu';
        $validated['judul'] = $request->jenis_surat;
        
        $surat = Surat::create($validated);
        
        return redirect()->route('warga.dashboard')
            ->with('success', 'Pengajuan surat berhasil dikirim dan menunggu verifikasi admin');
    }
    
    // Dashboard warga
    public function index()
    {
        $suratMasuk = Surat::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        $statistik = [
            'total' => $suratMasuk->count(),
            'menunggu' => $suratMasuk->where('status', 'Menunggu')->count(),
            'diproses' => $suratMasuk->where('status', 'Diproses')->count(),
            'selesai' => $suratMasuk->where('status', 'Selesai')->count(),
        ];
        
        return view('warga.dashboard', compact('suratMasuk', 'statistik'));
    }

    public function pengaturan()
    {
        return view('warga.pengaturan');
    }
}