<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use PDF; // Akan install package dompdf nanti

class TemplateSuratController extends Controller
{
    // Generate template surat berdasarkan jenis
    public function generateTemplate($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        
        // Cek apakah ada tanda tangan
        if (!$surat->tanda_tangan) {
            return back()->with('error', 'Belum ada tanda tangan untuk surat ini. Silakan upload tanda tangan terlebih dahulu.');
        }
        
        // Pilih template berdasarkan jenis surat
        $template = match($surat->jenis_surat) {
            'Surat Keterangan Tidak Mampu' => 'templates.sktm',
            'Surat Keterangan Domisili' => 'templates.skd',
            'Surat Keterangan Usaha' => 'templates.sku',
            default => 'templates.default'
        };
        
        return view($template, compact('surat'));
    }
    
    // Download PDF
    public function downloadPDF($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        
        if (!$surat->tanda_tangan) {
            return back()->with('error', 'Belum ada tanda tangan untuk surat ini.');
        }
        
        $template = match($surat->jenis_surat) {
            'Surat Keterangan Tidak Mampu' => 'templates.sktm',
            'Surat Keterangan Domisili' => 'templates.skd',
            'Surat Keterangan Usaha' => 'templates.sku',
            default => 'templates.default'
        };
        
        $pdf = PDF::loadView($template, compact('surat'))->setPaper('a4', 'portrait');
        
        $filename = sprintf('%s_%s_%s.pdf', 
            str_replace(' ', '_', $surat->jenis_surat),
            $surat->nama_pemohon,
            date('YmdHis')
        );
        
        return $pdf->download($filename);
    }
    
    // Preview surat sebelum download
    public function preview($id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        
        return view('admin.data-surat.preview-surat', compact('surat'));
    }
}