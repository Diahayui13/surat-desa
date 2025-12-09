@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-lg mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('warga.dashboard') }}" class="text-gray-600">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Ajukan Surat</h1>
                <p class="text-sm text-gray-500">Pilih jenis surat yang ingin diajukan</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <!-- Dropdown Jenis Surat -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Jenis Surat</label>
                <div class="relative">
                    <select id="jenis-surat-select" 
                        class="w-full px-4 py-4 pr-10 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none appearance-none bg-white font-medium text-gray-700 cursor-pointer">
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="domisili">Surat Keterangan Domisili</option>
                        <option value="usaha">Surat Keterangan Usaha</option>
                        <option value="ktp">Surat Pengantar KTP</option>
                        <option value="sktm">Surat Keterangan Tidak Mampu</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- List Jenis Surat -->
            <div class="space-y-2">
                <a href="{{ route('warga.buat_surat.jenis', ['jenis' => 'domisili']) }}" class="block p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 group-hover:bg-blue-500 w-10 h-10 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-house text-blue-600 group-hover:text-white transition"></i>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-blue-700 transition">Surat Keterangan Domisili</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-blue-500 transition"></i>
                    </div>
                </a>

                <a href="{{ route('warga.buat_surat.jenis', ['jenis' => 'usaha']) }}" class="block p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 group-hover:bg-blue-500 w-10 h-10 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-briefcase text-blue-600 group-hover:text-white transition"></i>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-blue-700 transition">Surat Keterangan Usaha</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-blue-500 transition"></i>
                    </div>
                </a>

                <a href="{{ route('warga.buat_surat.jenis', ['jenis' => 'ktp']) }}" class="block p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 group-hover:bg-blue-500 w-10 h-10 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-id-card text-blue-600 group-hover:text-white transition"></i>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-blue-700 transition">Surat Pengantar KTP</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-blue-500 transition"></i>
                    </div>
                </a>

                <a href="{{ route('warga.buat_surat.jenis', ['jenis' => 'sktm']) }}" class="block p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 group-hover:bg-blue-500 w-10 h-10 rounded-lg flex items-center justify-center transition">
                                <i class="fa-solid fa-user text-blue-600 group-hover:text-white transition"></i>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-blue-700 transition">Surat Keterangan Tidak Mampu</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-blue-500 transition"></i>
                    </div>
                </a>
            </div>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <div class="flex gap-3">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-700 mb-1">Perhatian:</p>
                        <ul class="space-y-1 text-xs">
                            <li>• Pastikan data yang diisi sudah benar</li>
                            <li>• Siapkan file pendukung (KTP, KK, dll)</li>
                            <li>• Proses verifikasi 1–3 hari kerja</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Auto redirect saat pilih dropdown
    document.getElementById('jenis-surat-select').addEventListener('change', function() {
        if (this.value) {
            window.location.href = `/warga/buat-surat/${this.value}`;
        }
    });
</script>
@endsection
