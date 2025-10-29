<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Surat;
use App\Models\Permohonan;  
use App\Models\TandaTangan;
use PDF;

class AdminController extends Controller
{
    public function index()
    {
        $totalSurat = Surat::count();
        $menunggu = Surat::where('status', 'Menunggu')->count();
        $diproses = Surat::where('status', 'Diproses')->count();
        $selesai = Surat::where('status', 'Selesai')->count();
        
        // Ambil 5 surat terbaru
        $suratTerbaru = Surat::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalSurat', 'menunggu', 'diproses', 'selesai', 'suratTerbaru'));
    }

    public function permohonan(Request $request)
{
    // ✅ Ambil data dari tabel surats
    $query = Surat::with('user')->orderBy('created_at', 'desc');
    
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
    
    // Search berdasarkan nama atau NIK
    if ($request->has('search') && $request->search !== '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_pemohon', 'like', "%{$search}%")
              ->orWhere('nik_pemohon', 'like', "%{$search}%");
        });
    }
    
    $suratList = $query->get();
    
    // Hitung statistik dari SEMUA data (bukan hasil filter)
    $allSurats = Surat::all();
    $total = $allSurats->count();
    $menunggu = $allSurats->where('status', 'Menunggu')->count();
    $proses = $allSurats->where('status', 'Diproses')->count();
    $selesai = $allSurats->where('status', 'Selesai')->count();
    
    // Map data untuk view
    $surats = $suratList->map(function($item) {
        return (object)[
            'id' => $item->id,
            'nama_pemohon' => $item->nama_pemohon ?? optional($item->user)->name,
            'nik_pemohon' => $item->nik_pemohon,
            'jenis_surat' => $item->jenis_surat ?? $item->judul,
            'judul' => $item->judul,
            'tanggal_pengajuan' => $item->tanggal_pengajuan ?? $item->created_at,
            'created_at' => $item->created_at,
            'status' => $item->status,
            'tanggal_diproses' => $item->tanggal_diproses,
            'tanggal_selesai' => $item->tanggal_selesai,
        ];
    });
    
    // Jika request AJAX, return JSON
    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'surats' => $surats,
            'stats' => [
                'total' => $total,
                'menunggu' => $menunggu,
                'proses' => $proses,
                'selesai' => $selesai,
            ]
        ]);
    }
    
    return view('admin.permohonan', compact('surats', 'total', 'menunggu', 'proses', 'selesai'));
}

    public function datasurat()
    {
        $surat = Permohonan::where('status', 'disetujui')
        ->orderBy('tanggal_surat', 'desc')
        ->get();
    
    return view('admin.data-surat.surat', compact('surats'));

        $surats = [
            (object)[
                'id' => 1,
                'nama_pemohon' => 'Muhammad Andri',
                'jenis_surat' => 'Surat Keterangan Domisili',
                'tanggal' => '10 Okt 2025',
                'status' => 'Disetujui'
            ],
            (object)[
                'id' => 2,
                'nama_pemohon' => 'Siti Lestari',
                'jenis_surat' => 'Surat Keterangan Kelahiran',
                'tanggal' => '12 Okt 2025',
                'status' => 'Proses'
            ],
            (object)[
                'id' => 3,
                'nama_pemohon' => 'Budi Hartono',
                'jenis_surat' => 'Surat Keterangan Menikah',
                'tanggal' => '09 Okt 2025',
                'status' => 'Ditolak'
            ],
        ];

        return view('admin.surat', compact('surats'));
    }

    // 📄 Detail surat
    public function detailSurat($id)
{
    $surat = Permohonan::with('user')->findOrFail($id);
    return view('admin.detail-surat', compact('surat'));
 $surat = Permohonan::with('user')->findOrFail($id);
    return view('admin.detail-surat', compact('surat'));
}

public function updateStatus(Request $request, $id)
{
    \Log::info('Update Status Request', [
        'id' => $id, 
        'status' => $request->status,
        'method' => $request->method()
    ]);
    
    // ✅ Gunakan model Surat (bukan Permohonan)
    $surat = Surat::findOrFail($id);
    
    // Validasi status
    $validStatus = ['Diproses', 'Selesai'];
    if (!in_array($request->status, $validStatus)) {
        return redirect()->back()->with('error', 'Status tidak valid!');
    }
    
    // Update status
    $surat->status = $request->status;
    
    // Set timestamp
    if ($request->status === 'Diproses') {
        $surat->tanggal_diproses = now();
    } elseif ($request->status === 'Selesai') {
        $surat->tanggal_selesai = now();
    }
    
    $surat->save();
    
    \Log::info('Status Updated Successfully', ['surat_id' => $surat->id, 'new_status' => $surat->status]);
    
    // Redirect berdasarkan status
    if ($request->status === 'Diproses') {
        // Cek apakah route TTD ada
        if (\Route::has('admin.tanda.tangan')) {
            return redirect()->route('admin.tanda.tangan', $surat->id)
                ->with('success', 'Status diperbarui menjadi Diproses. Silakan upload tanda tangan.');
        } else {
            return redirect()->route('admin.permohonan')
                ->with('success', 'Status berhasil diperbarui menjadi Diproses!');
        }
    }
    
    return redirect()->route('admin.permohonan')
        ->with('success', 'Status berhasil diperbarui menjadi ' . $request->status . '!');
}

    // 👤 Profil admin
    public function profiladmin()
    {
        return view('admin.pengaturan');
    }

   // 🧾 Tanda tangan digital
public function ttddigital()
{
    // Ambil permohonan yang statusnya sedang diproses (siap diberi TTD)
    $surat = Permohonan::where('status', 'diproses')
        // kalau mau hanya yang belum punya file TTD, un-comment baris bawah:
        // ->whereNull('tanda_tangan')
        ->orderBy('created_at', 'desc')
        ->get();

    // Penting: kirim variabel bernama $surat karena view ttd-digital.blade pakai @forelse ($surat as $item)
    return view('admin.ttddigital', compact('surat'));

    $permohonanSiapTTD = Permohonan::where('status', 'diproses')
        ->orderBy('created_at', 'desc')
        ->get();

    // kirim ke view 'admin.ttddigital' (tanpa tanda minus)
    // dan mapping ke key 'surat' karena blade pakai @forelse ($surat as $item)
    return view('admin.ttddigital', ['surat' => $permohonanSiapTTD]);

    // Contoh data dummy sementara
    $surat = [
        (object)[
            'id' => 1,
            'nama_pemohon' => 'Muhammad Andri',
            'jenis_surat' => 'Surat Keterangan Domisili',
            'created_at' => now()->subDays(2),
            'tanda_tangan' => session('tanda_tangan_path'), // ambil dari session biar preview muncul
        ],
        (object)[
            'id' => 2,
            'nama_pemohon' => 'Siti Lestari',
            'jenis_surat' => 'Surat Keterangan Usaha',
            'created_at' => now()->subDays(1),
            'tanda_tangan' => session('tanda_tangan_path'),
        ],
    ];

    return view('admin.ttddigital', compact('surat'));
}


public function tandaTangan($id)
{
    // Cari surat berdasarkan ID
    $surat = Surat::findOrFail($id);
    
    // Cek apakah status sudah Diproses
    if ($surat->status !== 'Diproses') {
        return redirect()->route('admin.permohonan')
            ->with('error', 'Surat ini belum diproses atau sudah selesai!');
    }
    
    // ✅ Sesuaikan dengan view: gunakan variabel $permohonan
    $permohonan = $surat; // Alias agar view bisa pakai $permohonan
    
    // Ambil tanda tangan aktif (jika ada sistem TTD master)
    $tandaTanganAktif = null; // Bisa diganti dengan TandaTangan::where('is_active', true)->first();
    
    return view('admin.tanda-tangan', compact('permohonan', 'tandaTanganAktif'));
}

public function uploadTandaTangan(Request $request)
{
    $request->validate([
        'tanda_tangan' => 'required|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    // Ambil ID dari surat_id (view mengirim surat_id)
    $suratId = $request->surat_id ?? $request->permohonan_id;
    
    if (!$suratId) {
        return redirect()->back()->with('error', 'ID surat tidak ditemukan!');
    }
    
    $surat = Surat::findOrFail($suratId);

    // Hapus TTD lama jika ada
    if ($surat->file_ttd && Storage::disk('public')->exists($surat->file_ttd)) {
        Storage::disk('public')->delete($surat->file_ttd);
    }

    // Simpan file TTD baru
    $file = $request->file('tanda_tangan');
    $filename = 'ttd_' . time() . '_' . $surat->id . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('tanda-tangan', $filename, 'public');

    // Update field file_ttd di database
    $surat->file_ttd = $path;
    
    // Generate nomor surat jika belum ada
    if (!$surat->nomor_surat) {
        $surat->nomor_surat = $this->generateNomorSurat($surat->jenis_surat ?? 'Surat Keterangan');
    }
    
    if (!$surat->tanggal_surat) {
        $surat->tanggal_surat = now();
    }
    
    $surat->save();

    // Redirect ke halaman konfirmasi
    return redirect()->route('admin.tanda.tangan.konfirmasi', $surat->id)
        ->with('success', 'Tanda tangan berhasil diupload!');
}

public function konfirmasiTandaTangan($id)
{
    $surat = Surat::with('user')->findOrFail($id);
    
    // Cek apakah sudah ada TTD
    if (!$surat->file_ttd) {
        return redirect()->route('admin.tanda.tangan', $id)
            ->with('error', 'Belum ada tanda tangan. Silakan upload terlebih dahulu.');
    }
    
    // Generate preview HTML
    $preview = $this->generatePreviewSurat($surat);
    
    // ✅ Alias $surat ke $permohonan agar view bisa pakai
    $permohonan = $surat;
    
    return view('admin.ttd-konfirmasi', compact('permohonan', 'preview'));
}

public function simpanTandaTangan(Request $request, $id)
{
    $permohonan = Permohonan::findOrFail($id);
    
    // Ubah status jadi disetujui (Selesai)
    $permohonan->status = 'disetujui';
    $permohonan->tanggal_selesai = now();
    $permohonan->save();
    
    return redirect()->route('admin.permohonan')
        ->with('success', 'Surat berhasil diselesaikan dan siap untuk dicetak!');
}

public function generateSurat(Request $request, $id)
{
    $permohonan = Permohonan::findOrFail($id);
    
    // Data untuk template
    $data = [
        'nomor_surat' => $permohonan->nomor_surat,
        'nama' => $permohonan->nama,
        'nik' => $permohonan->nik,
        'tempat_lahir' => $permohonan->tempat_lahir,
        'tanggal_lahir' => $permohonan->tanggal_lahir->format('d F Y'),
        'jenis_kelamin' => $permohonan->jenis_kelamin,
        'pekerjaan' => $permohonan->pekerjaan,
        'alamat' => $permohonan->alamat,
        'keperluan' => $permohonan->keperluan,
        'tanggal' => $permohonan->tanggal_surat->format('d F Y'),
        'pejabat' => 'Kepala Desa Sumbertlaseh', // Bisa diambil dari DB
        'jabatan' => 'Kepala Desa',
        'nama_desa' => 'Sumbertlaseh',
        'ttd_path' => storage_path('app/public/' . $permohonan->tanda_tangan),
    ];
    $pdf = $this->generatePDFFromTemplate($permohonan->jenis_surat, $data);

// Simpan PDF
    $filename = 'surat_' . $permohonan->id . '_' . time() . '.pdf';
    $path = 'surat/' . $filename;
    Storage::disk('public')->put($path, $pdf);
    
    // Update status
    $permohonan->file_surat = $path;
    $permohonan->status = 'disetujui';
    $permohonan->save();
    
    return redirect()->route('admin.surat')->with('success', 'Surat berhasil digenerate!');
}

private function generatePreviewSurat($surat)
{
    // Ambil data user jika ada relasi
    $user = $surat->user;
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { 
                font-family: "Times New Roman", serif; 
                padding: 40px;
                line-height: 1.8;
                max-width: 800px;
                margin: 0 auto;
                background: white;
            }
            .kop-surat {
                text-align: center;
                border-bottom: 3px solid #000;
                padding-bottom: 15px;
                margin-bottom: 30px;
            }
            .kop-surat h2 { margin: 5px 0; font-size: 18pt; font-weight: bold; }
            .kop-surat h3 { margin: 3px 0; font-size: 16pt; font-weight: bold; }
            .kop-surat p { margin: 2px 0; font-size: 11pt; }
            .judul {
                text-align: center;
                font-weight: bold;
                text-decoration: underline;
                margin: 30px 0;
                font-size: 14pt;
            }
            .nomor {
                text-align: center;
                margin-bottom: 30px;
                font-size: 11pt;
            }
            table { width: 100%; margin: 20px 0; border-collapse: collapse; }
            table td { padding: 5px 0; vertical-align: top; }
            table td:first-child { width: 200px; }
            table td:nth-child(2) { width: 20px; text-align: center; }
            .ttd-section {
                margin-top: 60px;
                text-align: right;
            }
            .ttd-container {
                display: inline-block;
                text-align: center;
                min-width: 250px;
            }
            .ttd-image {
                max-height: 100px;
                max-width: 200px;
                margin: 10px 0;
            }
            .ttd-nama {
                font-weight: bold;
                text-decoration: underline;
                margin-top: 10px;
            }
            .content { margin: 20px 0; text-align: justify; }
        </style>
    </head>
    <body>
        <!-- Kop Surat -->
        <div class="kop-surat">
            <h2>PEMERINTAH KABUPATEN BOJONEGORO</h2>
            <h3>KECAMATAN DANDER</h3>
            <h3>DESA SUMBERTLASEH</h3>
            <p>Sekretariat Jalan Balai Desa No.97 Sumbertlaseh</p>
        </div>
        
        <!-- Judul -->
        <div class="judul">
            ' . strtoupper($surat->jenis_surat ?? 'SURAT KETERANGAN') . '
        </div>
        
        <!-- Nomor Surat -->
        <div class="nomor">
            Nomor: ' . ($surat->nomor_surat ?? '___/___/___/' . date('Y')) . '
        </div>
        
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
                    <td>Tempat/Tgl. Lahir</td>
                    <td>:</td>
                    <td>' . ($surat->tempat_lahir ?? '-') . ', ' . ($surat->tanggal_lahir ? \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d F Y') : '-') . '</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>' . ($surat->jenis_kelamin ?? '-') . '</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>' . ($surat->pekerjaan ?? '-') . '</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>' . ($surat->alamat ?? '-') . '</td>
                </tr>
            </table>
            
            <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        </div>
        
        <!-- Tanda Tangan -->
        <div class="ttd-section">
            <div class="ttd-container">
                <p>Sumbertlaseh, ' . now()->locale('id')->isoFormat('D MMMM Y') . '</p>
                <p style="font-weight: bold; margin-top: 10px;">Kepala Desa Sumbertlaseh</p>
                ';
    
    // Tambahkan gambar TTD
    if ($surat->file_ttd) {
        $ttdPath = storage_path('app/public/' . $surat->file_ttd);
        if (file_exists($ttdPath)) {
            $ttdBase64 = base64_encode(file_get_contents($ttdPath));
            $mimeType = mime_content_type($ttdPath);
            $html .= '<img src="data:' . $mimeType . ';base64,' . $ttdBase64 . '" class="ttd-image">';
        }
    }
    
    $html .= '
                <div class="ttd-nama">( NAMA KEPALA DESA )</div>
            </div>
        </div>
    </body>
    </html>
    ';
    
    return $html;
}

private function generateNomorSurat($jenisSurat)
{
    $prefix = '';
    
    switch ($jenisSurat) {
        case 'Surat Keterangan Tidak Mampu':
            $prefix = 'SKTM';
            break;
        case 'Surat Keterangan Domisili':
            $prefix = 'SKD';
            break;
        case 'Surat Keterangan Usaha':
            $prefix = 'SKU';
            break;
        case 'Surat Keterangan Ahli Waris':
            $prefix = 'SKAW';
            break;
        default:
            $prefix = 'SK';
    }
    
    $bulan = now()->format('m');
    $tahun = now()->format('Y');
    $counter = Permohonan::whereYear('created_at', $tahun)
        ->whereMonth('created_at', $bulan)
        ->count() + 1;
    
    return sprintf('%s/%03d/%s/%s', $prefix, $counter, $bulan, $tahun);
}

// Helper: Generate PDF dari template
private function generatePDFFromTemplate($jenisSurat, $data)
{
    $template = $this->getTemplateByJenisSurat($jenisSurat);
    
    // Replace semua variable
    foreach ($data as $key => $value) {
        if ($key !== 'ttd_path') {
            $template = str_replace('${'.$key.'}', $value, $template);
        }
    }
    
    // Convert image TTD ke base64 untuk PDF
    if (isset($data['ttd_path']) && file_exists($data['ttd_path'])) {
        $ttdBase64 = base64_encode(file_get_contents($data['ttd_path']));
        $ttdImg = '<img src="data:image/png;base64,'.$ttdBase64.'" style="width:150px;height:auto;" />';
        $template = str_replace('${tanda_tangan}', $ttdImg, $template);
    }
    
    // Generate PDF dengan Dompdf (install dulu)
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($template);
    $pdf->setPaper('A4', 'portrait');

    return $pdf->output();
}
}
