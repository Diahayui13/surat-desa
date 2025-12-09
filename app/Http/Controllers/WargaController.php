<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WargaController extends Controller
{
    // ==========================
    // DASHBOARD
    // ==========================
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
        
        // Get unread notifications count
        $unreadNotifications = Auth::user()->unreadNotifications->count();

        return view('warga.dashboard', compact('suratMasuk', 'statistik', 'unreadNotifications'));
    }

    // ==========================
    // NOTIFICATIONS
    // ==========================
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('warga.notifications', compact('notifications'));
    }
    
    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect to action URL if exists
            if (isset($notification->data['action_url'])) {
                return redirect($notification->data['action_url']);
            }
        }
        
        return redirect()->route('warga.notifications');
    }
    
    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    // ==========================
    // SURAT MASUK
    // ==========================
    public function suratMasuk()
    {
        $suratMasuk = Surat::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('warga.surat-masuk', compact('suratMasuk'));
    }

    // ==========================
    // PILIH JENIS SURAT
    // ==========================
    public function pilihJenisSurat()
    {
        $jenisSurat = [
            ['nama' => 'Surat Keterangan Domisili', 'icon' => 'fa-home', 'slug' => 'domisili', 'deskripsi' => 'Keterangan tempat tinggal'],
            ['nama' => 'Surat Keterangan Usaha', 'icon' => 'fa-briefcase', 'slug' => 'usaha', 'deskripsi' => 'Keterangan memiliki usaha'],
            ['nama' => 'Surat Pengantar KTP', 'icon' => 'fa-id-card', 'slug' => 'ktp', 'deskripsi' => 'Pengantar pembuatan KTP'],
            ['nama' => 'Surat Keterangan Tidak Mampu', 'icon' => 'fa-hand-holding-heart', 'slug' => 'sktm', 'deskripsi' => 'Keterangan tidak mampu'],
        ];

        return view('warga.pilih-jenis-surat', compact('jenisSurat'));
    }

    // ==========================
    // FORM PENGAJUAN SURAT
    // ==========================
    public function buatSurat($jenis)
    {
        $map = [
            'domisili' => 'Surat Keterangan Domisili',
            'usaha' => 'Surat Keterangan Usaha',
            'ktp' => 'Surat Pengantar KTP',
            'sktm' => 'Surat Keterangan Tidak Mampu',
        ];

        if (!isset($map[$jenis])) {
            return redirect()->route('warga.buat_surat')->with('error', 'Jenis surat tidak valid!');
        }

        return view('warga.form-pengajuan', [
            'jenis' => $jenis,
            'jenisSurat' => $map[$jenis],
            'user' => Auth::user(),
        ]);
    }

    // ==========================
    // SIMPAN PENGAJUAN SURAT
    // ==========================
    public function storeSurat(Request $request)
    {
        try {
            // ✅ Validasi hanya field yang ada di form
            $validated = $request->validate([
                'jenis_surat' => 'required|string',
                'nama_pemohon' => 'required|string|max:255',
                'nik_pemohon' => 'required|string|size:16',
                'alamat' => 'required|string',
                'keperluan' => 'nullable|string',
                
                // File wajib
                'file_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'file_kk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'file_surat_pernyataan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);
            
            // Validasi tambahan untuk SKTM
            if ($request->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                $request->validate([
                    'nama_anak' => 'required|string|max:255',
                    'tempat_lahir_anak' => 'required|string|max:255',
                    'tanggal_lahir_anak' => 'required|date',
                    'jenis_kelamin_anak' => 'required|in:Laki-laki,Perempuan',
                    'pendidikan' => 'required|string|max:255',
                    'file_foto_rumah' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                ]);
                
                $validated['nama_anak'] = $request->nama_anak;
                $validated['tempat_lahir_anak'] = $request->tempat_lahir_anak;
                $validated['tanggal_lahir_anak'] = $request->tanggal_lahir_anak;
                $validated['jenis_kelamin_anak'] = $request->jenis_kelamin_anak;
                $validated['pendidikan'] = $request->pendidikan;
            }

            // Upload files
            $fileFields = ['file_ktp', 'file_kk', 'file_surat_pernyataan'];
            if ($request->jenis_surat === 'Surat Keterangan Tidak Mampu') {
                $fileFields[] = 'file_foto_rumah';
            }
            
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $validated[$field] = $request->file($field)->store('uploads/surat', 'public');
                }
            }

            // ✅ SET DATA TAMBAHAN
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'Menunggu';
            $validated['judul'] = $validated['jenis_surat'];
            $validated['tanggal_pengajuan'] = now();

            Surat::create($validated);

            // ✅ HANYA REDIRECT DENGAN FLASH MESSAGE - HAPUS NOTIFIKASI POP-UP
            return redirect()->route('warga.dashboard')
                ->with('success', 'Pengajuan surat berhasil dikirim! Menunggu verifikasi admin.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', 'Mohon periksa kembali form Anda!');
                
        } catch (\Exception $e) {
            \Log::error('Error storing surat: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengajuan. Silakan coba lagi.');
        }
    }

    // ==========================
    // HALAMAN TAMBAHAN
    // ==========================
    public function status()
    {
        $surat = Surat::where('user_id', Auth::id())->latest()->get();
        return view('warga.status', compact('surat'));
    }

    public function riwayat()
    {
        // ✅ HANYA TAMPILKAN SURAT SELESAI
        $suratSelesai = Surat::where('user_id', auth()->id())
            ->where('status', 'Selesai')
            ->orderBy('tanggal_selesai', 'desc')
            ->get();

        return view('warga.riwayat', compact('suratSelesai'));
    }

    public function profil()
    {
        $user = Auth::user();
        return view('warga.profil', compact('user'));
    }

    public function pengaturan()
    {
        return view('warga.pengaturan');
    }
    
    public function detailSurat($id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);
        return view('warga.detail-lengkap', compact('surat'));
    }

    public function previewSurat($id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$surat->file_ttd) {
            return redirect()->back()->with('error', 'Surat belum selesai diproses.');
        }
        
        $html = $this->generatePreviewSurat($surat);
        
        return response($html)->header('Content-Type', 'text/html');
    }

    public function downloadPDF($id)
    {
        $surat = Surat::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$surat->file_ttd) {
            return redirect()->back()->with('error', 'Surat belum selesai diproses.');
        }
        
        $html = $this->generatePreviewSurat($surat);
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Surat_' . $surat->nama_pemohon . '_' . now()->format('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }

    private function generatePreviewSurat($surat)
    {
        $user = $surat->user;
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                @page {
                    size: A4;
                    margin: 2cm 2cm 2cm 2cm;
                }
                
                body { 
                    font-family: "Times New Roman", serif; 
                    line-height: 1.8;
                    margin: 0;
                    padding: 0;
                    font-size: 12pt;
                }
                
                .container {
                    width: 100%;
                    max-width: 21cm;
                    margin: 0 auto;
                    padding: 20px;
                }
                
                .kop-surat {
                    text-align: center;
                    border-bottom: 3px solid #000;
                    padding-bottom: 15px;
                    margin-bottom: 30px;
                    page-break-inside: avoid;
                }
                
                .kop-surat h2 { 
                    margin: 5px 0; 
                    font-size: 16pt; 
                    font-weight: bold; 
                }
                
                .kop-surat h3 { 
                    margin: 3px 0; 
                    font-size: 14pt; 
                    font-weight: bold; 
                }
                
                .kop-surat p { 
                    margin: 2px 0; 
                    font-size: 10pt; 
                }
                
                .judul {
                    text-align: center;
                    font-weight: bold;
                    text-decoration: underline;
                    margin: 25px 0;
                    font-size: 13pt;
                    page-break-inside: avoid;
                }
                
                .nomor-surat {
                    text-align: center;
                    margin-bottom: 25px;
                    font-size: 11pt;
                    page-break-inside: avoid;
                }
                
                .content {
                    margin: 20px 0;
                    font-size: 11pt;
                    page-break-inside: avoid;
                }
                
                table { 
                    width: 100%; 
                    margin: 15px 0;
                    border-collapse: collapse;
                    page-break-inside: avoid;
                }
                
                table td { 
                    padding: 4px 0; 
                    vertical-align: top;
                    font-size: 11pt;
                }
                
                table td:first-child { 
                    width: 180px; 
                }
                
                table td:nth-child(2) { 
                    width: 20px; 
                    text-align: center;
                }
                
                .penutup {
                    margin-top: 25px;
                    font-size: 11pt;
                    page-break-inside: avoid;
                }
                
                .ttd-section { 
                    margin-top: 40px;
                    text-align: right;
                    page-break-inside: avoid;
                    min-height: 150px;
                }
                
                .ttd-section .lokasi-tanggal {
                    font-size: 11pt;
                    margin-bottom: 5px;
                }
                
                .ttd-section .jabatan {
                    font-weight: bold;
                    margin-top: 5px;
                    margin-bottom: 10px;
                    font-size: 11pt;
                }
                
                .ttd-image { 
                    max-height: 80px;
                    max-width: 150px;
                    margin: 10px 0;
                    display: inline-block;
                }
                
                .ttd-section .nama-ttd {
                    font-weight: bold;
                    text-decoration: underline;
                    margin-top: 10px;
                    font-size: 11pt;
                }
                
                /* Prevent page break */
                .no-break {
                    page-break-inside: avoid;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <!-- Kop Surat -->
                <div class="kop-surat">
                    <h2>PEMERINTAH KABUPATEN BOJONEGORO</h2>
                    <h3>KECAMATAN DANDER</h3>
                    <h3>DESA SUMBERTLASEH</h3>
                    <p>Sekretariat Jalan Balai Desa No.97 Sumbertlaseh</p>
                </div>
                
                <!-- Judul Surat -->
                <div class="judul">' . strtoupper($surat->jenis_surat ?? 'SURAT KETERANGAN') . '</div>
                
                <!-- Nomor Surat -->
                <div class="nomor-surat">Nomor: ' . ($surat->nomor_surat ?? '___/___/___/' . date('Y')) . '</div>
                
                <!-- Isi Surat -->
                <div class="content">
                    <p>Yang bertanda tangan di bawah ini:</p>
                    <table class="no-break">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>Kepala Desa Sumbertlaseh</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>Kepala Desa</td>
                        </tr>
                    </table>
                    
                    <p>Menerangkan dengan sesungguhnya bahwa:</p>
                    <table class="no-break">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><strong>' . ($surat->nama_pemohon ?? ($user->name ?? 'N/A')) . '</strong></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td><strong>' . ($surat->nik_pemohon ?? 'N/A') . '</strong></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>' . ($surat->alamat ?? '-') . '</td>
                        </tr>';
        
        if ($surat->keperluan) {
            $html .= '
                        <tr>
                            <td>Keperluan</td>
                            <td>:</td>
                            <td>' . $surat->keperluan . '</td>
                        </tr>';
        }
        
        $html .= '
                    </table>
                </div>
                
                <!-- Penutup -->
                <div class="penutup">
                    <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
                </div>
                
                <!-- Tanda Tangan -->
                <div class="ttd-section no-break">
                    <div class="lokasi-tanggal">Sumbertlaseh, ' . now()->locale('id')->isoFormat('D MMMM Y') . '</div>
                    <div class="jabatan">Kepala Desa Sumbertlaseh</div>';
        
        if ($surat->file_ttd) {
            $ttdPath = storage_path('app/public/' . $surat->file_ttd);
            if (file_exists($ttdPath)) {
                $ttdBase64 = base64_encode(file_get_contents($ttdPath));
                $mimeType = mime_content_type($ttdPath);
                $html .= '
                    <div>
                        <img src="data:' . $mimeType . ';base64,' . $ttdBase64 . '" class="ttd-image" alt="TTD">
                    </div>';
            }
        }
        
        $html .= '
                    <div class="nama-ttd">( NAMA KEPALA DESA )</div>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}