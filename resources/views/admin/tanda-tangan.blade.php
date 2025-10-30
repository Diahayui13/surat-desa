@extends('layouts.admin')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-bold mb-1">Tanda Tangan</h1>
  <p class="text-gray-500 mb-6">Gunakan tanda tangan digital untuk validasi surat resmi</p>

  <div class="max-w-2xl mx-auto">
    {{-- Kartu upload TTD --}}
    <div class="bg-white rounded-xl shadow p-5">
      <h2 class="font-semibold mb-4">Tanda Tangan</h2>

      {{-- Form Upload TTD --}}
      <form action="{{ route('admin.tanda.tangan.upload') }}" 
            method="POST" 
            enctype="multipart/form-data"
            id="uploadForm">
          @csrf
          <input type="hidden" name="surat_id" value="{{ $permohonan->id }}">

          <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                  Upload Tanda Tangan (PNG/JPG)
              </label>
              
              {{-- Preview Container (Hidden by default) --}}
              <div id="previewContainer" class="hidden mb-4">
                  <div class="relative inline-block">
                      <img id="previewImage" 
                           src="" 
                           alt="Preview" 
                           class="max-h-40 border-2 border-blue-300 rounded-lg p-2 bg-blue-50">
                      
                      {{-- ✅ Tombol X untuk Cancel Upload --}}
                      <button type="button" 
                              onclick="cancelUpload()"
                              class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg transition">
                          <i class="fa-solid fa-xmark"></i>
                      </button>
                  </div>
              </div>

              {{-- Input File --}}
              <input type="file" 
                     name="tanda_tangan" 
                     id="ttdInput"
                     accept="image/png,image/jpeg,image/jpg" 
                     onchange="previewTTD(event)"
                     class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100 cursor-pointer"
                     required>
              <p class="mt-1 text-xs text-gray-500">Format: PNG, JPG, JPEG (Max: 2MB)</p>
          </div>

          {{-- ✅ Tombol Submit & Reset --}}
          <div class="flex gap-3">
              <button type="submit" 
                      id="uploadBtn"
                      class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg transition font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                  <i class="fa-solid fa-upload mr-2"></i>
                  <span id="btnText">Gunakan Tanda Tangan Ini</span>
              </button>
              
          </div>
      </form>
    </div>
  </div>
</div>

<script>
// Preview TTD sebelum upload
function previewTTD(event) {
    const file = event.target.files[0];
    
    if (file) {
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            return;
        }

        // Validasi tipe file
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak valid! Gunakan PNG, JPG, atau JPEG');
            event.target.value = '';
            return;
        }

        // Tampilkan preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
            
            // ✅ Ganti text tombol jadi "Gunakan Tanda Tangan Ini"
            document.getElementById('btnText').textContent = 'Gunakan Tanda Tangan Ini';
            document.querySelector('#uploadBtn i').className = 'fa-solid fa-check mr-2';
            
            // ✅ Sembunyikan tombol Reset
            document.getElementById('resetBtn').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// Cancel upload (hapus preview)
function cancelUpload() {
    document.getElementById('ttdInput').value = '';
    document.getElementById('previewContainer').classList.add('hidden');
    document.getElementById('previewImage').src = '';
    
    // ✅ Kembalikan text tombol jadi "Upload"
    document.getElementById('btnText').textContent = 'Upload';
    document.querySelector('#uploadBtn i').className = 'fa-solid fa-upload mr-2';
    
    // ✅ Tampilkan kembali tombol Reset
    document.getElementById('resetBtn').classList.remove('hidden');
}

// Prevent double submit
document.getElementById('uploadForm').addEventListener('submit', function() {
    const btn = document.getElementById('uploadBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Mengupload...';
});
</script>

@endsection