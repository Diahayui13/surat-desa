@extends('layouts.admin')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-bold mb-1">Tanda Tangan</h1>
  <p class="text-gray-500 mb-6">Gunakan tanda tangan digital untuk validasi surat resmi</p>

  <div class="grid md:grid-cols-2 gap-6">
    {{-- Kartu upload TTD --}}
    <div class="bg-white rounded-xl shadow p-5">
      <h2 class="font-semibold mb-3">Tanda Tangan</h2>
      <div class="border rounded-xl p-4 flex items-center justify-center h-64">
        <img id="ttdPreview" src="{{ $tandaTanganAktif? asset('storage/'.$tandaTanganAktif->file_path) : '' }}" class="max-h-56 object-contain" alt="">
        @unless($tandaTanganAktif)
          <span class="text-gray-400">Belum ada preview</span>
        @endunless
      </div>

      <form action="{{ route('admin.tanda.tangan.upload') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">
        <input type="hidden" name="surat_id" value="{{ $permohonan->id }}">
        <label class="block">
          <input type="file" name="tanda_tangan" accept="image/png,image/jpeg" class="hidden" onchange="previewTTD(this)">
          <span class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg cursor-pointer" onclick="this.previousElementSibling.click()">
            <i class="fa-solid fa-upload"></i> Upload
          </span>
        </label>

        <button type="submit" class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-lg">
          Gunakan Tanda Tangan ini
        </button>
      </form>
    </div>

    {{-- Ringkas info surat --}}
    <div class="bg-white rounded-xl shadow p-5">
      <h2 class="font-semibold mb-3">Info Surat</h2>
      <div class="text-sm text-gray-700 space-y-1">
        <div><span class="text-gray-500">Pemohon:</span> {{ $permohonan->nama }}</div>
        <div><span class="text-gray-500">Jenis Surat:</span> {{ $permohonan->jenis_surat }}</div>
        <div><span class="text-gray-500">Tanggal Pengajuan:</span> {{ $permohonan->created_at->format('d M Y, H:i') }}</div>
      </div>
      <p class="text-xs text-gray-500 mt-4">Setelah upload, kamu akan diarahkan ke halaman konfirmasi untuk memeriksa preview surat.</p>
    </div>
  </div>
</div>

<script>
function previewTTD(input){
  const file = input.files && input.files[0];
  if(!file) return;
  const img = document.getElementById('ttdPreview');
  img.src = URL.createObjectURL(file);
}
</script>
@endsection
