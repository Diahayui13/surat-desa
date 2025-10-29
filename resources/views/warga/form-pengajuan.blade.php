@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('warga.pilih-jenis-surat') }}" class="text-gray-600">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Ajukan Surat</h1>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Form Pengajuan</h2>
            
            <form action="{{ route('warga.surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                
                <input type="hidden" name="jenis_surat" value="{{ $jenisSurat }}">
                
                <!-- Pilih Jenis Surat -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jenis Surat</label>
                    <input type="text" value="{{ $jenisSurat }}" readonly
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50">
                </div>
                
                <!-- Data Pemohon -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">Data Pemohon</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <input type="text" name="nama_pemohon" placeholder="Nama" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            @error('nama_pemohon')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <input type="text" name="nik_pemohon" placeholder="NIK" required maxlength="16"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            @error('nik_pemohon')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <textarea name="alamat" placeholder="Alamat" rows="3" required
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                            @error('alamat')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <textarea name="keperluan" placeholder="Keperluan" rows="2"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                            @error('keperluan')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                @if($jenisSurat === 'Surat Keterangan Tidak Mampu')
                <!-- Data Anak (untuk SKTM) -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">Data Anak</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <input type="text" name="nama_anak" placeholder="Nama Anak"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="tempat_lahir_anak" placeholder="Tempat Lahir"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            
                            <input type="date" name="tanggal_lahir_anak"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <select name="jenis_kelamin_anak"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Jenis Kelamin Anak</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <input type="text" name="pendidikan" placeholder="Pendidikan (SD/SMP/SMA/Universitas)"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Upload File Pendukung -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">Upload File Pendukung</h3>
                    
                    <div class="space-y-4">
                        <!-- Upload KTP -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Surat Pengantar RT/RW</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_ktp" accept="image/*" class="hidden" id="file_ktp" onchange="previewFile(this, 'preview_ktp')">
                                <label for="file_ktp" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_ktp" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2">✓ File berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload KK -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Kartu Tanda Penduduk (KTP)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_kk" accept="image/*" class="hidden" id="file_kk" onchange="previewFile(this, 'preview_kk')">
                                <label for="file_kk" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_kk" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2">✓ File berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Surat Pernyataan -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Kartu Keluarga (KK)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_surat_pernyataan" accept="image/*" class="hidden" id="file_surat" onchange="previewFile(this, 'preview_surat')">
                                <label for="file_surat" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_surat" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2">✓ File berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Foto Rumah -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Surat Pernyataan Tidak Mampu</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_foto_rumah" accept="image/*" class="hidden" id="file_rumah" onchange="previewFile(this, 'preview_rumah')">
                                <label for="file_rumah" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_rumah" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2">✓ File berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Foto Rumah -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Foto Rumah</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" accept="image/*" class="hidden" id="file_foto_rumah_extra" onchange="previewFile(this, 'preview_rumah_extra')">
                                <label for="file_foto_rumah_extra" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_rumah_extra" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2">✓ File berhasil dipilih</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl transition shadow-lg">
                    Kirim Permohonan
                </button>
            </form>
        </div>
        
    </div>
</div>

<script>
function previewFile(input, previewId) {
    const preview = document.getElementById(previewId);
    const img = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #245BCA;
    }
</style>
@endsection