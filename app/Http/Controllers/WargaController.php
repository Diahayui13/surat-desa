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

    // ✅ FIXED: Semua field dan file WAJIB diisi
    public function storeSurat(Request $request)
    {
        try {
            // ✅ Validasi - SEMUA field WAJIB diisi
            $rules = [
                'jenis_surat' => 'required|string',
                'nama_pemohon' => 'required|string|max:255',
                'nik_pemohon' => 'required|string|size:16', // NIK harus 16 digit
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'keperluan' => 'required|string',
                
                // ✅ File uploads - SEMUA WAJIB
                'file_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'file_kk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'file_surat_pernyataan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ];
            
            // Jika SKTM, tambah validasi untuk data anak dan file tambahan
            if ($request->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                $rules['nama_anak'] = 'required|string|max:255';
                $rules['tempat_lahir_anak'] = 'required|string|max:255';
                $rules['tanggal_lahir_anak'] = 'required|date';
                $rules['jenis_kelamin_anak'] = 'required|in:Laki-laki,Perempuan';
                $rules['pendidikan'] = 'required|string|max:255';
                $rules['file_foto_rumah'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }
            
            // Custom error messages
            $messages = [
                'required' => 'Field :attribute wajib diisi.',
                'size' => ':attribute harus :size digit.',
                'image' => ':attribute harus berupa gambar.',
                'mimes' => ':attribute harus berformat: :values.',
                'max' => ':attribute maksimal :max KB.',
            ];
            
            // Custom attribute names
            $attributes = [
                'jenis_surat' => 'Jenis Surat',
                'nama_pemohon' => 'Nama Lengkap',
                'nik_pemohon' => 'NIK',
                'tempat_lahir' => 'Tempat Lahir',
                'tanggal_lahir' => 'Tanggal Lahir',
                'jenis_kelamin' => 'Jenis Kelamin',
                'pekerjaan' => 'Pekerjaan',
                'alamat' => 'Alamat',
                'keperluan' => 'Keperluan',
                'file_ktp' => 'Surat Pengantar RT/RW',
                'file_kk' => 'Kartu Tanda Penduduk (KTP)',
                'file_surat_pernyataan' => 'Kartu Keluarga (KK)',
                'file_foto_rumah' => 'Foto Rumah',
                'nama_anak' => 'Nama Anak',
                'tempat_lahir_anak' => 'Tempat Lahir Anak',
                'tanggal_lahir_anak' => 'Tanggal Lahir Anak',
                'jenis_kelamin_anak' => 'Jenis Kelamin Anak',
                'pendidikan' => 'Pendidikan',
            ];
            
            $validated = $request->validate($rules, $messages, $attributes);
            
            // Upload files
            $fileFields = ['file_ktp', 'file_kk', 'file_surat_pernyataan'];
            
            // Tambah foto rumah jika SKTM
            if ($request->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                $fileFields[] = 'file_foto_rumah';
            }
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $validated[$field] = $file->storeAs('uploads/surat', $filename, 'public');
                }
            }
            
            // ✅ Set data tambahan
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'Menunggu';
            $validated['judul'] = $request->jenis_surat;
            $validated['tanggal_pengajuan'] = now();
            
            // Simpan ke database
            $surat = Surat::create($validated);
            
            return redirect()->route('warga.dashboard')
                ->with('success', 'Pengajuan surat berhasil dikirim! Menunggu verifikasi admin.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error validasi
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', 'Mohon lengkapi semua data yang wajib diisi!');
                
        } catch (\Exception $e) {
            // Error lainnya
            \Log::error('Error creating surat: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengajuan. Error: ' . $e->getMessage());
        }
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
    
    // Detail surat warga
    public function detailSurat($id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);
        return view('warga.detail-surat', compact('surat'));
    }
    
    // Riwayat surat
    public function riwayat()
    {
        $surats = Surat::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('warga.riwayat', compact('surats'));
    }
}