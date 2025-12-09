@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Upload Tanda Tangan</h1>
            <p class="text-sm text-gray-500">Upload tanda tangan untuk memproses surat</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            
            <!-- Kartu Upload TTD -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-pen-nib text-blue-600"></i>
                    Upload Tanda Tangan
                </h2>
                
                <!-- Preview TTD -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 flex items-center justify-center h-64 mb-4 bg-gray-50">
                    <div id="preview-container" class="text-center">
                        <img id="ttdPreview" src="" class="hidden max-h-56 object-contain mx-auto" alt="Preview TTD">
                        <div id="placeholder" class="text-gray-400">
                            <i class="fa-solid fa-cloud-arrow-up text-5xl mb-3"></i>
                            <p class="text-sm">Klik tombol upload untuk memilih file</p>
                            <p class="text-xs text-gray-400 mt-1">Format: PNG, JPG, JPEG (Max: 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('admin.upload.ttd') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <input type="hidden" name="surat_id" value="{{ $permohonan->id }}">
                    
                    <div class="space-y-3">
                        <!-- Input File (Hidden) -->
                        <input type="file" id="fileInput" name="tanda_tangan" accept="image/png,image/jpeg,image/jpg" class="hidden" onchange="previewTTD(this)" required>
                        
                        <!-- Tombol Upload -->
                        <button type="button" onclick="document.getElementById('fileInput').click()" 
                                class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-3 rounded-lg transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-upload"></i>
                            Pilih File Tanda Tangan
                        </button>

                        <!-- Tombol Submit -->
                        <button type="submit" id="submitBtn" disabled
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i>
                            Proses Surat
                        </button>

                        <!-- Tombol Batal -->
                        <a href="{{ route('admin.permohonan') }}" 
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-lg transition">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                    </div>
                </form>

                @if($errors->any())
                <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-600">
                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                        {{ $errors->first() }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Info Surat -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-blue-600"></i>
                    Informasi Surat
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-xl p-4">
                        <div class="space-y-3 text-sm">
                            <div class="flex">
                                <span class="text-gray-500 w-32">Pemohon:</span>
                                <span class="font-semibold text-gray-800">{{ $permohonan->nama_pemohon }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 w-32">NIK:</span>
                                <span class="font-semibold text-gray-800">{{ $permohonan->nik_pemohon }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 w-32">Jenis Surat:</span>
                                <span class="font-semibold text-gray-800">{{ $permohonan->jenis_surat }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 w-32">Pengajuan:</span>
                                <span class="font-semibold text-gray-800">{{ $permohonan->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 w-32">Status:</span>
                                <span class="inline-flex items-center bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">
                                    <i class="fa-solid fa-clock mr-1"></i> Menunggu
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Bantuan -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <h3 class="font-semibold text-amber-800 text-sm mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb"></i>
                            Panduan
                        </h3>
                        <ul class="text-xs text-amber-700 space-y-1 list-disc list-inside">
                            <li>Pilih file tanda tangan yang sesuai</li>
                            <li>File harus berformat PNG, JPG, atau JPEG</li>
                            <li>Ukuran maksimal 2MB</li>
                            <li>Setelah upload, Anda akan diarahkan ke halaman preview</li>
                            <li>Pastikan tanda tangan terlihat jelas</li>
                        </ul>
                    </div>

                    <!-- Alur Proses -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-route"></i>
                            Alur Proses
                        </h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 text-xs">
                                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center">1</div>
                                <span class="text-gray-600 font-medium">Upload tanda tangan</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <div class="w-6 h-6 rounded-full bg-gray-300 text-white flex items-center justify-center">2</div>
                                <span class="text-gray-500">Preview & konfirmasi</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <div class="w-6 h-6 rounded-full bg-gray-300 text-white flex items-center justify-center">3</div>
                                <span class="text-gray-500">Surat diproses</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <div class="w-6 h-6 rounded-full bg-gray-300 text-white flex items-center justify-center">4</div>
                                <span class="text-gray-500">Klik "Selesaikan"</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function previewTTD(input) {
    const file = input.files && input.files[0];
    if (!file) {
        // Reset jika file dihapus
        document.getElementById('ttdPreview').classList.add('hidden');
        document.getElementById('placeholder').classList.remove('hidden');
        document.getElementById('submitBtn').disabled = true;
        return;
    }
    
    // Validasi ukuran file (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 2MB');
        input.value = '';
        return;
    }
    
    // Validasi tipe file
    const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
        alert('Format file tidak valid! Gunakan PNG, JPG, atau JPEG');
        input.value = '';
        return;
    }
    
    // Preview gambar
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('ttdPreview');
        img.src = e.target.result;
        img.classList.remove('hidden');
        document.getElementById('placeholder').classList.add('hidden');
        document.getElementById('submitBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}

// Konfirmasi sebelum submit
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    if (!confirm('Apakah Anda yakin tanda tangan sudah benar dan ingin melanjutkan ke preview?')) {
        e.preventDefault();
    }
});
</script>
@endsection