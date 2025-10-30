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

        {{-- Alert Error --}}
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-start">
                <i class="fa-solid fa-exclamation-circle text-red-500 mr-3 mt-1"></i>
                <div>
                    <p class="text-red-700 font-medium mb-2">Mohon lengkapi data berikut:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
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
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Form Pengajuan</h2>
                <p class="text-sm text-red-600 mt-1">
                    <i class="fa-solid fa-info-circle"></i> 
                    Semua field bertanda <span class="font-bold">*</span> wajib diisi
                </p>
            </div>
            
            <form action="{{ route('warga.surat.store') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
                @csrf
                
                <input type="hidden" name="jenis_surat" value="{{ $jenisSurat }}">
                
                <!-- Pilih Jenis Surat -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                    <input type="text" value="{{ $jenisSurat }}" readonly
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50">
                </div>
                
                <!-- Data Pemohon -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">Data Pemohon</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pemohon" placeholder="Nama lengkap sesuai KTP" required
                                   value="{{ old('nama_pemohon') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            @error('nama_pemohon')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nik_pemohon" placeholder="16 digit NIK" required 
                                   maxlength="16" pattern="[0-9]{16}"
                                   value="{{ old('nik_pemohon') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <p class="text-xs text-gray-500 mt-1">Contoh: 3524010101990001</p>
                            @error('nik_pemohon')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tempat_lahir" placeholder="Contoh: Surabaya" required
                                       value="{{ old('tempat_lahir') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                @error('tempat_lahir')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_lahir" required
                                       value="{{ old('tanggal_lahir') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                @error('tanggal_lahir')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pekerjaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pekerjaan" placeholder="Contoh: Wiraswasta, Petani, dll" required
                                   value="{{ old('pekerjaan') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            @error('pekerjaan')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" placeholder="RT/RW, Desa, Kecamatan, Kabupaten" rows="3" required
                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Keperluan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="keperluan" placeholder="Untuk keperluan apa surat ini dibuat?" rows="2" required
                                      class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                @if($jenisSurat === 'Surat Keterangan Tidak Mampu')
                <!-- Data Anak (untuk SKTM) -->
                <div class="mb-6 bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">
                        <i class="fa-solid fa-child text-blue-600"></i> Data Anak
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Anak <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_anak" placeholder="Nama lengkap anak" required
                                   value="{{ old('nama_anak') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            @error('nama_anak')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tempat Lahir Anak <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tempat_lahir_anak" placeholder="Tempat lahir" required
                                       value="{{ old('tempat_lahir_anak') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                @error('tempat_lahir_anak')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Lahir Anak <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_lahir_anak" required
                                       value="{{ old('tanggal_lahir_anak') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                @error('tanggal_lahir_anak')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kelamin Anak <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin_anak" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin_anak') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin_anak') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin_anak')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pendidikan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pendidikan" placeholder="SD/SMP/SMA/Universitas dan Kelas/Semester" required
                                   value="{{ old('pendidikan') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            @error('pendidikan')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Upload File Pendukung -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">
                        <i class="fa-solid fa-cloud-arrow-up text-blue-600"></i> Upload File Pendukung
                    </h3>
                    <p class="text-sm text-red-600 mb-4">Semua file wajib diupload (Format: JPG, PNG - Max 2MB)</p>
                    
                    <div class="space-y-4">
                        <!-- Upload Surat Pengantar RT/RW -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                1. Surat Pengantar RT/RW <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer" id="upload_area_ktp">
                                <input type="file" name="file_ktp" accept="image/jpeg,image/png,image/jpg" required class="hidden" id="file_ktp" onchange="previewFile(this, 'preview_ktp')">
                                <label for="file_ktp" class="cursor-pointer">
                                    <div class="flex flex-col items-center" id="placeholder_ktp">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk Upload</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_ktp" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2 font-semibold">✓ File berhasil dipilih</p>
                                    <button type="button" onclick="removeFile('file_ktp', 'preview_ktp')" class="text-xs text-red-500 mt-1 hover:underline">Hapus file</button>
                                </div>
                            </div>
                            @error('file_ktp')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Upload KTP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                2. Kartu Tanda Penduduk (KTP) <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_kk" accept="image/jpeg,image/png,image/jpg" required class="hidden" id="file_kk" onchange="previewFile(this, 'preview_kk')">
                                <label for="file_kk" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk Upload</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_kk" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2 font-semibold">✓ File berhasil dipilih</p>
                                    <button type="button" onclick="removeFile('file_kk', 'preview_kk')" class="text-xs text-red-500 mt-1 hover:underline">Hapus file</button>
                                </div>
                            </div>
                            @error('file_kk')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Upload KK -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                3. Kartu Keluarga (KK) <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_surat_pernyataan" accept="image/jpeg,image/png,image/jpg" required class="hidden" id="file_surat" onchange="previewFile(this, 'preview_surat')">
                                <label for="file_surat" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk Upload</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_surat" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2 font-semibold">✓ File berhasil dipilih</p>
                                    <button type="button" onclick="removeFile('file_surat', 'preview_surat')" class="text-xs text-red-500 mt-1 hover:underline">Hapus file</button>
                                </div>
                            </div>
                            @error('file_surat_pernyataan')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        @if($jenisSurat === 'Surat Keterangan Tidak Mampu')
                        <!-- Upload Foto Rumah (SKTM Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                4. Foto Rumah Tampak Depan <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                <input type="file" name="file_foto_rumah" accept="image/jpeg,image/png,image/jpg" required class="hidden" id="file_rumah" onchange="previewFile(this, 'preview_rumah')">
                                <label for="file_rumah" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-blue-100 p-4 rounded-full mb-3">
                                            <i class="fa-solid fa-upload text-blue-500 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk Upload</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG (max 2MB)</p>
                                    </div>
                                </label>
                                <div id="preview_rumah" class="mt-3 hidden">
                                    <img src="" class="max-h-32 mx-auto rounded">
                                    <p class="text-xs text-green-600 mt-2 font-semibold">✓ File berhasil dipilih</p>
                                    <button type="button" onclick="removeFile('file_rumah', 'preview_rumah')" class="text-xs text-red-500 mt-1 hover:underline">Hapus file</button>
                                </div>
                            </div>
                            @error('file_foto_rumah')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="border-t pt-6">
                    <button type="submit" id="btnSubmit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl transition shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center">
                        <span id="btnText">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Kirim Permohonan
                        </span>
                        <span id="btnLoading" class="hidden">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                            Mengirim...
                        </span>
                    </button>
                    <p class="text-xs text-gray-500 text-center mt-3">
                        Pastikan semua data sudah benar sebelum mengirim
                    </p>
                </div>
            </form>
        </div>
        
    </div>
</div>

<script>
function previewFile(input, previewId) {
    const preview = document.getElementById(previewId);
    const img = preview.querySelector('img');
    const placeholder = input.parentElement.querySelector('[id^="placeholder"]');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (max 2MB)
        if (file.size > 2048000) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
            alert('Format file tidak valid! Hanya JPG/PNG yang diperbolehkan');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(file);
    }
}

function removeFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const placeholder = input.parentElement.querySelector('[id^="placeholder"]');
    
    input.value = '';
    preview.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
}

// Prevent double submit
document.getElementById('formPengajuan').addEventListener('submit', function(e) {
    const btn = document.getElementById('btnSubmit');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    
    // Validate all required files
    const requiredFiles = ['file_ktp', 'file_kk', 'file_surat'];
    
    const jenisSurat = '{{ $jenisSurat }}';
    if (jenisSurat === 'Surat Keterangan Tidak Mampu') {
        requiredFiles.push('file_rumah');
    }
    
    for (let fileId of requiredFiles) {
        const fileInput = document.getElementById(fileId);
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault();
            alert('Mohon upload semua file yang diperlukan!');
            return false;
        }
    }
    
    btn.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
});

// Auto-format NIK input (only numbers)
document.querySelector('[name="nik_pemohon"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>

<style>
    input:focus, textarea:focus, select:focus {
        outline: none;
    }
</style>
@endsection