@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tanda Tangan Digital</h1>
                <p class="text-sm text-gray-500 mt-1">Gunakan tanda tangan digital untuk validasi surat resmi</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <h3 class="text-lg font-semibold text-gray-700 mb-4">Daftar Surat Siap TTD</h3>

        <!-- Cards List -->
        <div class="space-y-4">
            @forelse ($surat as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    
                    <!-- Info Surat -->
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-lg mb-1">{{ $item->nama_pemohon }}</h4>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span><i class="fa-regular fa-file-lines mr-1 text-blue-500"></i> {{ $item->jenis_surat }}</span>
                            <span><i class="fa-regular fa-calendar mr-1 text-blue-500"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                        
                        @if($item->tanda_tangan)
                            <!-- TTD Sudah Ada -->
                            <div class="flex items-center gap-3">
                                <!-- Preview TTD -->
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $item->tanda_tangan) }}" 
                                         alt="TTD" 
                                         class="h-16 w-24 object-contain border-2 border-green-200 rounded-lg bg-white p-1">
                                    
                                    <!-- Tombol Hapus (X) -->
                                    <form action="{{ route('admin.ttddigital.delete', $item->id) }}" 
                                          method="POST" 
                                          class="absolute -top-2 -right-2"
                                          onsubmit="return confirm('Yakin ingin menghapus tanda tangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition shadow-md">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Tombol Lihat Surat -->
                                <a href="{{ route('admin.template.preview', $item->id) }}"
                                   target="_blank"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition flex items-center gap-2 font-medium shadow-sm">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Lihat Surat</span>
                                </a>
                            </div>
                        @else
                            <!-- Form Upload TTD -->
                            <form action="{{ route('admin.ttddigital.upload') }}" 
                                  method="POST" 
                                  enctype="multipart/form-data" 
                                  class="upload-form-{{ $item->id }} flex items-center gap-3">
                                @csrf
                                <input type="hidden" name="surat_id" value="{{ $item->id }}">

                                <!-- Preview Container (Hidden by default) -->
                                <div class="preview-container-{{ $item->id }} hidden flex items-center gap-3">
                                    <div class="relative">
                                        <img src="" 
                                             alt="Preview" 
                                             class="preview-image-{{ $item->id }} h-16 w-24 object-contain border-2 border-blue-300 rounded-lg bg-white p-1">
                                        
                                        <!-- Tombol Cancel -->
                                        <button type="button" 
                                                onclick="cancelUpload({{ $item->id }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition shadow-md">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Tombol Submit -->
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition flex items-center gap-2 font-medium shadow-sm">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Konfirmasi</span>
                                    </button>
                                </div>

                                <!-- Upload Button -->
                                <label class="upload-btn-{{ $item->id }} cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition flex items-center gap-2 font-medium shadow-sm">
                                    <i class="fa-solid fa-upload"></i>
                                    <span>Upload TTD</span>
                                    <input type="file" 
                                           name="tanda_tangan" 
                                           accept="image/*" 
                                           class="hidden file-input-{{ $item->id }}"
                                           onchange="previewTandaTangan({{ $item->id }}, this)">
                                </label>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <i class="fa-regular fa-folder-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada surat yang siap untuk ditandatangani</p>
                <p class="text-gray-400 text-sm mt-2">Surat yang sudah disetujui akan muncul di sini</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

<script>
    // Preview tanda tangan sebelum upload
    function previewTandaTangan(suratId, input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Tampilkan preview
                document.querySelector(`.preview-image-${suratId}`).src = e.target.result;
                document.querySelector(`.preview-container-${suratId}`).classList.remove('hidden');
                
                // Sembunyikan tombol upload
                document.querySelector(`.upload-btn-${suratId}`).classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Cancel upload dan reset form
    function cancelUpload(suratId) {
        // Reset file input
        document.querySelector(`.file-input-${suratId}`).value = '';
        
        // Sembunyikan preview
        document.querySelector(`.preview-container-${suratId}`).classList.add('hidden');
        
        // Tampilkan kembali tombol upload
        document.querySelector(`.upload-btn-${suratId}`).classList.remove('hidden');
        
        // Reset preview image
        document.querySelector(`.preview-image-${suratId}`).src = '';
    }
</script>

<style>
    .upload-form img {
        transition: transform 0.2s ease;
    }
    
    .upload-form img:hover {
        transform: scale(1.05);
    }
</style>
@endsection