@extends('layouts.admin')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-bold mb-1">Konfirmasi</h1>
  <p class="text-gray-500 mb-6">Gunakan tanda tangan digital untuk validasi surat resmi</p>

  <div class="bg-white rounded-xl shadow p-5">
  <h2 class="font-semibold mb-4">Preview Surat</h2>

  {{-- Preview HTML surat yang sudah diinject TTD --}}
  <div class="border rounded-xl overflow-hidden bg-gray-50 mb-5">
    <iframe srcdoc='{!! $preview !!}' class="w-full" style="height: 70vh;"></iframe>
  </div>

  {{-- ✅ Tombol Konfirmasi & Batal yang Rapi --}}
  <div class="flex gap-3">
    <form action="{{ route('admin.tanda.tangan.simpan', $permohonan->id) }}" 
          method="POST" 
          class="flex-1"
          onsubmit="return confirm('Konfirmasi tanda tangan ini? Surat akan berstatus Diproses.')">
      @csrf
      <button type="submit" 
              class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg transition font-medium shadow-sm flex items-center justify-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        <span>Konfirmasi & Simpan</span>
      </button>
    </form>
    
    <form action="{{ route('admin.tanda.tangan.batal', $permohonan->id) }}" 
          method="POST"
          class="flex-1"
          onsubmit="return confirm('Yakin batalkan konfirmasi? Status surat akan dikembalikan ke Menunggu.')">
      @csrf
      <button type="submit" 
              class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg transition font-medium shadow-sm flex items-center justify-center gap-2">
        <i class="fa-solid fa-xmark-circle"></i>
        <span>Batal</span>
      </button>
    </form>
  </div>
</div>

@endsection