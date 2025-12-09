<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permohonan;

class PermohonanController extends Controller
{
    // Menampilkan daftar permohonan masuk
    public function index()
    {
        $permohonan = Permohonan::latest()->get();

        return view('admin.permohonan.index', compact('permohonan'));
    }

    // Update status permohonan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $permohonan = Permohonan::findOrFail($id);
        $permohonan->status = $request->status;
        $permohonan->save();

        return redirect()->back()->with('success', 'Status permohonan berhasil diperbarui.');
    }
}
