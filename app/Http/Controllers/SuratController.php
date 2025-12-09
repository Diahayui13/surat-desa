<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    // Halaman permohonan masuk dengan filter & search
    public function permohonanIndex(Request $request)
    {
        $query = Surat::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != 'total') {
            $query->where('status', ucfirst($request->status));
        }

        // Search berdasarkan nama atau NIK
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pemohon', 'like', "%{$search}%")
                  ->orWhere('nik_pemohon', 'like', "%{$search}%");
            });
        }

        $surats = $query->get();

        // Hitung statistik
        $total = Surat::count();
        $menunggu = Surat::where('status', 'Menunggu')->count();
        $proses = Surat::where('status', 'Diproses')->count();
        $selesai = Surat::where('status', 'Selesai')->count();

        // Jika AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'surats' => $surats,
                'stats' => compact('total', 'menunggu', 'proses', 'selesai')
            ]);
        }

        return view('admin.data-surat.permohonan', compact('surats', 'total', 'menunggu', 'proses', 'selesai'));
    }

    // Halaman detail surat
    public function show($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        return view('admin.data-surat.detail-lengkap', compact('surat'));
    }

    // Update status surat
    public function updateStatus(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        $oldStatus = $surat->status;
        $newStatus = $request->status;

        $surat->status = $newStatus;

        // Auto set tanggal berdasarkan perubahan status
        if ($newStatus == 'Diproses' && $oldStatus == 'Menunggu') {
            $surat->tanggal_diproses = now();
        } elseif ($newStatus == 'Selesai' && $oldStatus == 'Diproses') {
            $surat->tanggal_selesai = now();
        }

        $surat->save();

        return back()->with('success', 'Status surat berhasil diupdate');
    }

    // Halaman tanda tangan digital
    public function tandaTanganIndex()
    {
        $surat = Surat::where('status', 'Selesai')
                      ->orWhereNotNull('tanda_tangan')
                      ->get();
        
        return view('admin.data-surat.ttddigital', compact('surat'));
    }

    // Upload tanda tangan digital
    public function uploadTandaTangan(Request $request)
    {
        $request->validate([
            'surat_id' => 'required|exists:surat,id',
            'tanda_tangan' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $surat = Surat::findOrFail($request->surat_id);

        // Hapus file lama jika ada
        if ($surat->tanda_tangan && Storage::disk('public')->exists($surat->tanda_tangan)) {
            Storage::disk('public')->delete($surat->tanda_tangan);
        }

        // Upload file baru
        $path = $request->file('tanda_tangan')->store('uploads/tanda_tangan', 'public');

        $surat->tanda_tangan = $path;
        $surat->save();

        return back()->with([
            'success' => 'Tanda tangan berhasil diupload.',
            'uploaded_surat_id' => $surat->id,
            'tanda_tangan_path' => $path
        ]);
    }

    // Hapus tanda tangan
    public function deleteTandaTangan($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->tanda_tangan && Storage::disk('public')->exists($surat->tanda_tangan)) {
            Storage::disk('public')->delete($surat->tanda_tangan);
        }

        $surat->tanda_tangan = null;
        $surat->save();

        return back()->with('success', 'Tanda tangan berhasil dihapus');
    }

    // Preview surat dengan tanda tangan
    public function previewSurat($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        
        // Cek apakah ada tanda tangan
        if (!$surat->tanda_tangan) {
            return back()->with('error', 'Belum ada tanda tangan untuk surat ini');
        }

        return view('admin.data-surat.template-surat', compact('surat'));
    }
}