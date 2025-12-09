@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('warga.buat_surat') }}" class="text-gray-600">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Ajukan Surat</h1>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-red-800">Error!</h3>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-red-800">Periksa Form Anda!</h3>
                    <ul class="text-sm text-red-700 list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Form Pengajuan</h2>
            
            <form action="{{ route('warga.surat.store') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
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
                            <input type="text" name="nama_pemohon" placeholder="Nama Lengkap *" required
                                   value="{{ old('nama_pemohon') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            @error('nama_pemohon')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <input type="text" name="nik_pemohon" placeholder="NIK (16 digit) *" required maxlength="16"
                                   value="{{ old('nik_pemohon') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('nik_pemohon')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <textarea name="alamat" placeholder="Alamat Lengkap *" rows="3" required
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <textarea name="keperluan" placeholder="Keperluan (opsional)" rows="2"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
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
                            <input type="text" name="nama_anak" placeholder="Nama Anak *" required
                                   value="{{ old('nama_anak') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="tempat_lahir_anak" placeholder="Tempat Lahir *" required
                                   value="{{ old('tempat_lahir_anak') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            
                            <input type="date" name="tanggal_lahir_anak" required
                                   value="{{ old('tanggal_lahir_anak') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <select name="jenis_kelamin_anak" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="">Jenis Kelamin Anak *</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin_anak') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin_anak') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <input type="text" name="pendidikan" placeholder="Pendidikan (SD/SMP/SMA/Universitas) *" required
                                   value="{{ old('pendidikan') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Upload File Pendukung -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">Upload File Pendukung</h3>
                    
                    <div class="space-y-4">
                        <!-- Upload Surat Pengantar RT/RW -->
                        <div class="upload-wrapper">
                            <label class="block text-sm text-gray-600 mb-2">Surat Pengantar RT/RW *</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer" id="area_ktp">
                                <input type="file" name="file_ktp" accept="image/*" required class="hidden" id="file_ktp" onchange="previewFile(this, 'preview_ktp', 'area_ktp')">
                                <label for="file_ktp" class="cursor-pointer">
                                    <div class="flex flex-col items-center" id="placeholder_ktp">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_ktp" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded-lg shadow">
                                    <p class="text-xs text-green-600 mt-2 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check-circle"></i> File berhasil dipilih
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="cancelUpload('file_ktp', 'preview_ktp', 'area_ktp')" 
                                    id="btn_cancel_ktp"
                                    class="hidden mt-2 w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 rounded-lg transition text-sm">
                                <i class="fa-solid fa-times-circle mr-1"></i> Batal & Upload Ulang
                            </button>
                        </div>
                        
                        <!-- Upload KTP -->
                        <div class="upload-wrapper">
                            <label class="block text-sm text-gray-600 mb-2">Kartu Tanda Penduduk (KTP) *</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer" id="area_kk">
                                <input type="file" name="file_kk" accept="image/*" required class="hidden" id="file_kk" onchange="previewFile(this, 'preview_kk', 'area_kk')">
                                <label for="file_kk" class="cursor-pointer">
                                    <div class="flex flex-col items-center" id="placeholder_kk">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_kk" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded-lg shadow">
                                    <p class="text-xs text-green-600 mt-2 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check-circle"></i> File berhasil dipilih
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="cancelUpload('file_kk', 'preview_kk', 'area_kk')" 
                                    id="btn_cancel_kk"
                                    class="hidden mt-2 w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 rounded-lg transition text-sm">
                                <i class="fa-solid fa-times-circle mr-1"></i> Batal & Upload Ulang
                            </button>
                        </div>
                        
                        <!-- Upload KK -->
                        <div class="upload-wrapper">
                            <label class="block text-sm text-gray-600 mb-2">Kartu Keluarga (KK) *</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer" id="area_surat">
                                <input type="file" name="file_surat_pernyataan" accept="image/*" required class="hidden" id="file_surat" onchange="previewFile(this, 'preview_surat', 'area_surat')">
                                <label for="file_surat" class="cursor-pointer">
                                    <div class="flex flex-col items-center" id="placeholder_surat">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_surat" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded-lg shadow">
                                    <p class="text-xs text-green-600 mt-2 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check-circle"></i> File berhasil dipilih
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="cancelUpload('file_surat', 'preview_surat', 'area_surat')" 
                                    id="btn_cancel_surat"
                                    class="hidden mt-2 w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 rounded-lg transition text-sm">
                                <i class="fa-solid fa-times-circle mr-1"></i> Batal & Upload Ulang
                            </button>
                        </div>
                        
                        @if($jenisSurat === 'Surat Keterangan Tidak Mampu')
                        <!-- Upload Foto Rumah (SKTM) -->
                        <div class="upload-wrapper">
                            <label class="block text-sm text-gray-600 mb-2">Foto Rumah *</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer" id="area_rumah">
                                <input type="file" name="file_foto_rumah" accept="image/*" required class="hidden" id="file_rumah" onchange="previewFile(this, 'preview_rumah', 'area_rumah')">
                                <label for="file_rumah" class="cursor-pointer">
                                    <div class="flex flex-col items-center" id="placeholder_rumah">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Upload File</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_rumah" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded-lg shadow">
                                    <p class="text-xs text-green-600 mt-2 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check-circle"></i> File berhasil dipilih
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="cancelUpload('file_rumah', 'preview_rumah', 'area_rumah')" 
                                    id="btn_cancel_rumah"
                                    class="hidden mt-2 w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 rounded-lg transition text-sm">
                                <i class="fa-solid fa-times-circle mr-1"></i> Batal & Upload Ulang
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    Kirim Permohonan
                </button>
            </form>
        </div>
        
    </div>
</div>

<script>
function previewFile(input, previewId, areaId) {
    const file = input.files[0];
    if (!file) return;
    
    // Validasi ukuran file
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 2MB');
        input.value = '';
        return;
    }
    
    // Validasi tipe file
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
        alert('Format file tidak valid! Gunakan JPG atau PNG');
        input.value = '';
        return;
    }
    
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById('placeholder_' + previewId.replace('preview_', ''));
    const img = preview.querySelector('img');
    const btnCancel = document.getElementById('btn_cancel_' + previewId.replace('preview_', ''));
    
    const reader = new FileReader();
    reader.onload = function(e) {
        img.src = e.target.result;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
        if (btnCancel) btnCancel.classList.remove('hidden');
    }
    reader.readAsDataURL(file);
}

function cancelUpload(inputId, previewId, areaId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById('placeholder_' + inputId.replace('file_', ''));
    const btnCancel = document.getElementById('btn_cancel_' + inputId.replace('file_', ''));
    const img = preview.querySelector('img');
    
    // Reset input
    input.value = '';
    
    // Reset preview
    img.src = '';
    preview.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (btnCancel) btnCancel.classList.add('hidden');
}
</script>

<style>
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #3B82F6;
    }
</style>
@endsection