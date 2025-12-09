<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Notifications\SuratSelesaiNotification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ==============================
    // 🏠 DASHBOARD
    // ==============================
    public function index()
    {
        $totalSurat = Surat::count();
        $menunggu = Surat::where('status', 'Menunggu')->count();
        $diproses = Surat::where('status', 'Diproses')->count();
        $selesai = Surat::where('status', 'Selesai')->count();
        
        $suratTerbaru = Surat::with('user')
            ->whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('totalSurat', 'menunggu', 'diproses', 'selesai', 'suratTerbaru'));
    }

    // ==============================
    // 📮 PERMOHONAN MASUK
    // ==============================
    public function permohonan(Request $request)
    {
        $query = Surat::with('user')->orderByDesc('created_at');
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== 'total') {
            $statusMap = [
                'menunggu' => 'Menunggu',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai'
            ];
            
            if (isset($statusMap[$request->status])) {
                $query->where('status', $statusMap[$request->status]);
            }
        }
        
        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nik_pemohon', 'like', "%{$search}%");
            });
        }
        
        $surats = $query->get();
        
        // Statistik
        $total = Surat::count();
        $menunggu = Surat::where('status', 'Menunggu')->count();
        $proses = Surat::where('status', 'Diproses')->count();
        $selesai = Surat::where('status', 'Selesai')->count();
        
        // Jika AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'surats' => $surats,
                'stats' => compact('total', 'menunggu', 'proses', 'selesai')
            ]);
        }
        
        return view('admin.permohonan', compact('surats', 'total', 'menunggu', 'proses', 'selesai'));
    }

    // ==============================
    // ⚙️ HALAMAN UPLOAD TTD (Tombol "Proses")
    // ==============================
    public function tandaTangan($id)
    {
        $surat = Surat::findOrFail($id);
        
        // Cek status harus Menunggu
        if ($surat->status !== 'Menunggu') {
            return redirect()->route('admin.permohonan')
                ->with('error', 'Surat ini sudah diproses atau selesai!');
        }
        
        $permohonan = $surat;
        
        return view('admin.tanda-tangan', compact('permohonan'));
    }

    // ==============================
    // ⚙️ UPLOAD TTD & REDIRECT KE PREVIEW
    // ==============================
    public function uploadTandaTangan(Request $request)
    {
        $request->validate([
            'tanda_tangan' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'surat_id' => 'required'
        ]);

        $surat = Surat::findOrFail($request->surat_id);

        // Hapus TTD lama jika ada
        if ($surat->file_ttd && Storage::disk('public')->exists($surat->file_ttd)) {
            Storage::disk('public')->delete($surat->file_ttd);
        }

        // Simpan TTD baru
        $file = $request->file('tanda_tangan');
        $filename = 'ttd_' . time() . '_' . $surat->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('tanda-tangan', $filename, 'public');

        $surat->file_ttd = $path;
        
        // Generate nomor surat jika belum ada
        if (!$surat->nomor_surat) {
            $surat->nomor_surat = $this->generateNomorSurat($surat->jenis_surat ?? 'Surat Keterangan');
        }
        
        // ✅ Status masih Menunggu (belum diproses)
        $surat->save();

        // ✅ Redirect ke halaman preview konfirmasi
        return redirect()->route('admin.tanda.tangan.konfirmasi', $surat->id)
            ->with('success', 'Tanda tangan berhasil diupload! Silakan cek preview.');
    }

    // ==============================
    // 👁️ PREVIEW SURAT SETELAH UPLOAD TTD
    // ==============================
    public function konfirmasiTandaTangan($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        
        // Cek TTD harus sudah ada
        if (!$surat->file_ttd) {
            return redirect()->route('admin.tanda.tangan', $id)
                ->with('error', 'Belum ada tanda tangan. Silakan upload terlebih dahulu.');
        }
        
        // Generate preview HTML
        $preview = $this->generatePreviewSurat($surat);
        
        $permohonan = $surat;
        
        return view('admin.ttd-konfirmasi', compact('permohonan', 'preview'));
    }

    // ==============================
    // ✅ KONFIRMASI TTD (Menunggu → Diproses)
    // ==============================
    public function simpanTandaTangan(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        
        // ✅ Ubah status dari Menunggu → Diproses
        $surat->status = 'Diproses';
        $surat->tanggal_diproses = now();
        $surat->save();
        
        // ✅ Redirect ke permohonan dengan pesan sukses
        return redirect()->route('admin.permohonan')
            ->with('success', 'Surat berhasil diproses! Status: Diproses. Klik "Selesaikan" untuk menyelesaikan.');
    }

    // ==============================
    // ❌ BATAL KONFIRMASI (Hapus TTD & Kembali ke Menunggu)
    // ==============================
    public function batalKonfirmasi($id)
    {
        $surat = Surat::findOrFail($id);
        
        // Hapus file TTD
        if ($surat->file_ttd && Storage::disk('public')->exists($surat->file_ttd)) {
            Storage::disk('public')->delete($surat->file_ttd);
        }
        
        // Kembalikan status ke Menunggu
        $surat->status = 'Menunggu';
        $surat->file_ttd = null;
        $surat->nomor_surat = null;
        $surat->tanggal_diproses = null;
        $surat->save();
        
        return redirect()->route('admin.permohonan')
            ->with('info', 'Proses dibatalkan. Surat dikembalikan ke status Menunggu.');
    }

    // ==============================
    // ✅ SELESAIKAN SURAT (Diproses → Selesai)
    // ==============================
    public function updateStatus(Request $request, $id)
{
    $surat = Surat::findOrFail($id);
    
    $validated = $request->validate([
        'status' => 'required|in:Menunggu,Diproses,Selesai'
    ]);
    
    $oldStatus = $surat->status;
    $newStatus = $validated['status'];
    
    // Update status
    $surat->status = $newStatus;
    
    // Set tanggal sesuai status
    if ($newStatus === 'Diproses' && !$surat->tanggal_diproses) {
        $surat->tanggal_diproses = now();
    } elseif ($newStatus === 'Selesai' && !$surat->tanggal_selesai) {
        $surat->tanggal_selesai = now();
    }
    
    $surat->save();
    
    // ✅ KIRIM NOTIFIKASI ke warga jika surat SELESAI
    if ($newStatus === 'Selesai' && $oldStatus !== 'Selesai') {
        try {
            $warga = $surat->user;
            if ($warga) {
                $warga->notify(new SuratSelesaiNotification($surat));
            }
        } catch (\Exception $e) {
            // Jika notifikasi gagal, tetap lanjut (jangan block proses)
            \Log::warning('Failed to send notification: ' . $e->getMessage());
        }
    }
    
    $message = match($newStatus) {
        'Diproses' => 'Surat berhasil diproses',
        'Selesai' => 'Surat berhasil diselesaikan dan notifikasi telah dikirim ke warga',
        default => 'Status surat berhasil diperbarui'
    };
    
    return redirect()->back()->with('success', $message);
}

    // ==============================
    // 📋 DATA SURAT
    // ==============================
    public function datasurat(Request $request)
    {
        $query = Surat::with('user')->orderBy('created_at', 'desc');
        
        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->dari_tanggal) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        
        if ($request->has('sampai_tanggal') && $request->sampai_tanggal) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }
        
        // Filter jenis surat
        if ($request->has('jenis_surat') && !empty($request->jenis_surat)) {
            $query->whereIn('jenis_surat', $request->jenis_surat);
        }
        
        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nik_pemohon', 'like', "%{$search}%");
            });
        }
        
        $surats = $query->paginate(10);
        
        return view('admin.data-surat.surat', compact('surats'));
    }

    // ==============================
    // 📄 DETAIL SURAT
    // ==============================
    public function detailSurat($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        return view('admin.data-surat.detail', compact('surat')); 
    }

    // ==============================
    // 👤 PROFIL ADMIN
    // ==============================
    public function profil()
    {
        $admin = auth()->user();
        return view('admin.profil', compact('admin'));
    }

    // ==============================
    // 🛠️ HELPER: GENERATE PREVIEW SURAT
    // ==============================
    private function generatePreviewSurat($surat)
{
    $user = $surat->user;
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            @page {
                size: A4;
                margin: 2cm;
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
                padding: 10px;
            }
            
            .kop-surat {
                text-align: center;
                border-bottom: 3px solid #000;
                padding-bottom: 12px;
                margin-bottom: 25px;
            }
            
            .kop-surat h2 { 
                margin: 4px 0; 
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
                margin: 20px 0;
                font-size: 13pt;
            }
            
            .nomor-surat {
                text-align: center;
                margin-bottom: 20px;
                font-size: 11pt;
            }
            
            table { 
                width: 100%; 
                margin: 15px 0;
                border-collapse: collapse;
            }
            
            table td { 
                padding: 3px 0; 
                vertical-align: top;
                font-size: 11pt;
            }
            
            table td:first-child { 
                width: 180px; 
            }
            
            table td:nth-child(2) { 
                width: 20px; 
            }
            
            .penutup {
                margin-top: 20px;
                font-size: 11pt;
            }
            
            .ttd-section { 
                margin-top: 50px;
                text-align: right;
            }
            
            .ttd-section .lokasi-tanggal {
                font-size: 11pt;
                margin-bottom: 3px;
            }
            
            .ttd-section .jabatan {
                font-weight: bold;
                margin: 3px 0 8px 0;
                font-size: 11pt;
            }
            
            .ttd-image { 
                max-height: 70px;
                max-width: 140px;
                margin: 5px 0;
            }
            
            .ttd-section .nama-ttd {
                font-weight: bold;
                text-decoration: underline;
                margin-top: 8px;
                font-size: 11pt;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="kop-surat">
                <h2>PEMERINTAH KABUPATEN BOJONEGORO</h2>
                <h3>KECAMATAN DANDER</h3>
                <h3>DESA SUMBERTLASEH</h3>
                <p>Sekretariat Jalan Balai Desa No.97 Sumbertlaseh</p>
            </div>
            
            <div class="judul">' . strtoupper($surat->jenis_surat ?? 'SURAT KETERANGAN') . '</div>
            <div class="nomor-surat">Nomor: ' . ($surat->nomor_surat ?? '___/___/___/' . date('Y')) . '</div>
            
            <p style="font-size: 11pt;">Yang bertanda tangan di bawah ini:</p>
            <table>
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
            
            <p style="font-size: 11pt;">Menerangkan dengan sesungguhnya bahwa:</p>
            <table>
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
            
            <div class="penutup">
                <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
            </div>
            
            <div class="ttd-section">
                <div class="lokasi-tanggal">Sumbertlaseh, ' . now()->locale('id')->isoFormat('D MMMM Y') . '</div>
                <div class="jabatan">Kepala Desa Sumbertlaseh</div>';
    
    if ($surat->file_ttd) {
        $ttdPath = storage_path('app/public/' . $surat->file_ttd);
        if (file_exists($ttdPath)) {
            $ttdBase64 = base64_encode(file_get_contents($ttdPath));
            $mimeType = mime_content_type($ttdPath);
            $html .= '<img src="data:' . $mimeType . ';base64,' . $ttdBase64 . '" class="ttd-image" alt="TTD">';
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

    /**
 * Generate Preview Surat Full Page (1 Halaman Penuh)
 */
private function generatePreviewSuratFullPage($surat)
{
    $user = $surat->user;
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Preview Surat - ' . $surat->nama_pemohon . '</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: "Times New Roman", serif;
                background: #f5f5f5;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                min-height: 100vh;
            }
            
            .page-container {
                width: 21cm;
                min-height: 29.7cm;
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 2cm;
            }
            
            .kop-surat {
                text-align: center;
                border-bottom: 3px solid #000;
                padding-bottom: 12px;
                margin-bottom: 25px;
            }
            
            .kop-surat h2 {
                margin: 4px 0;
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
                margin: 20px 0;
                font-size: 13pt;
            }
            
            .nomor-surat {
                text-align: center;
                margin-bottom: 20px;
                font-size: 11pt;
            }
            
            .content {
                font-size: 11pt;
                line-height: 1.8;
            }
            
            table {
                width: 100%;
                margin: 15px 0;
                border-collapse: collapse;
            }
            
            table td {
                padding: 3px 0;
                vertical-align: top;
                font-size: 11pt;
            }
            
            table td:first-child {
                width: 180px;
            }
            
            table td:nth-child(2) {
                width: 20px;
            }
            
            .penutup {
                margin-top: 20px;
                font-size: 11pt;
            }
            
            .ttd-section {
                margin-top: 50px;
                text-align: right;
            }
            
            .ttd-section .lokasi-tanggal {
                font-size: 11pt;
                margin-bottom: 3px;
            }
            
            .ttd-section .jabatan {
                font-weight: bold;
                margin: 3px 0 8px 0;
                font-size: 11pt;
            }
            
            .ttd-image {
                max-height: 70px;
                max-width: 140px;
                margin: 5px 0;
            }
            
            .ttd-section .nama-ttd {
                font-weight: bold;
                text-decoration: underline;
                margin-top: 8px;
                font-size: 11pt;
            }
            
            /* Print Styles */
            @media print {
                body {
                    background: white;
                    padding: 0;
                }
                
                .page-container {
                    width: 100%;
                    box-shadow: none;
                    padding: 2cm;
                }
            }
        </style>
    </head>
    <body>
        <div class="page-container">
            <!-- Kop Surat -->
            <div class="kop-surat">
                <h2>PEMERINTAH KABUPATEN BOJONEGORO</h2>
                <h3>KECAMATAN DANDER</h3>
                <h3>DESA SUMBERTLASEH</h3>
                <p>Sekretariat Jalan Balai Desa No.97 Sumbertlaseh</p>
            </div>
            
            <!-- Judul -->
            <div class="judul">' . strtoupper($surat->jenis_surat ?? 'SURAT KETERANGAN') . '</div>
            
            <!-- Nomor Surat -->
            <div class="nomor-surat">Nomor: ' . ($surat->nomor_surat ?? '___/___/___/' . date('Y')) . '</div>
            
            <!-- Isi Surat -->
            <div class="content">
                <p>Yang bertanda tangan di bawah ini:</p>
                <table>
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
                <table>
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
            <div class="ttd-section">
                <div class="lokasi-tanggal">Sumbertlaseh, ' . now()->locale('id')->isoFormat('D MMMM Y') . '</div>
                <div class="jabatan">Kepala Desa Sumbertlaseh</div>';
    
    if ($surat->file_ttd) {
        $ttdPath = storage_path('app/public/' . $surat->file_ttd);
        if (file_exists($ttdPath)) {
            $ttdBase64 = base64_encode(file_get_contents($ttdPath));
            $mimeType = mime_content_type($ttdPath);
            $html .= '<img src="data:' . $mimeType . ';base64,' . $ttdBase64 . '" class="ttd-image" alt="TTD">';
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
    // ==============================
    // 🛠️ HELPER: GENERATE NOMOR SURAT
    // ==============================
    private function generateNomorSurat($jenisSurat)
    {
        $prefix = match($jenisSurat) {
            'Surat Keterangan Tidak Mampu' => 'SKTM',
            'Surat Keterangan Domisili' => 'SKD',
            'Surat Keterangan Usaha' => 'SKU',
            default => 'SK'
        };
        
        $bulan = now()->format('m');
        $tahun = now()->format('Y');
        $counter = Surat::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count() + 1;
        
        return sprintf('%s/%03d/%s/%s', $prefix, $counter, $bulan, $tahun);
    }

public function previewSurat($id)
{
    $surat = Surat::with('user')->findOrFail($id);
    
    if (!$surat->file_ttd) {
        return redirect()->back()->with('error', 'Surat belum memiliki tanda tangan.');
    }
    
    $html = $this->generatePreviewSuratFullPage($surat);
    
    return response($html)->header('Content-Type', 'text/html');
}

public function downloadPDF($id)
{
    $surat = Surat::with('user')->findOrFail($id);
    
    if (!$surat->file_ttd) {
        return redirect()->back()->with('error', 'Surat belum memiliki tanda tangan.');
    }
    
    $html = $this->generatePreviewSurat($surat);
    
    // Generate PDF
    $pdf = Pdf::loadHTML($html);
    $pdf->setPaper('A4', 'portrait');
    
    $filename = 'Surat_' . str_replace(' ', '_', $surat->nama_pemohon) . '_' . now()->format('YmdHis') . '.pdf';
    
    return $pdf->download($filename);
}
}

