@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('warga.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Riwayat Pengajuan</h1>
                <p class="text-sm text-gray-500">Daftar surat yang sudah selesai diproses</p>
            </div>
        </div>

        <!-- List Surat Selesai -->
        <div class="space-y-4">
            @forelse($suratSelesai as $surat)
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i class="fa-solid fa-check-circle mr-1"></i> Selesai
                            </span>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-lg mb-1">{{ $surat->jenis_surat }}</h3>
                        <p class="text-sm text-gray-600 mb-1">{{ $surat->nama_pemohon }}</p>
                        
                        <div class="flex items-center gap-4 text-xs text-gray-500 mt-2">
                            <div class="flex items-center gap-1">
                                <i class="fa-regular fa-calendar"></i>
                                <span>Diajukan: {{ $surat->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Selesai: {{ $surat->tanggal_selesai ? $surat->tanggal_selesai->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        
                        @if($surat->nomor_surat)
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fa-solid fa-file-lines mr-1"></i>
                            Nomor Surat: <span class="font-medium">{{ $surat->nomor_surat }}</span>
                        </p>
                        @endif
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                    @if($surat->file_ttd)
                        <a href="{{ route('warga.surat.preview', $surat->id) }}" 
                           target="_blank"
                           class="flex-1 text-center bg-blue-50 text-blue-700 px-4 py-3 rounded-lg font-medium hover:bg-blue-100 transition text-sm">
                            <i class="fa-solid fa-eye mr-2"></i>Preview
                        </a>
                        <a href="{{ route('warga.surat.download-pdf', $surat->id) }}"
                           class="flex-1 text-center bg-green-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-green-700 transition text-sm shadow-sm">
                            <i class="fa-solid fa-download mr-2"></i>Cetak PDF
                        </a>
                    @else
                        <button disabled
                                class="flex-1 text-center bg-gray-100 text-gray-400 px-4 py-3 rounded-lg font-medium cursor-not-allowed text-sm">
                            <i class="fa-solid fa-clock mr-2"></i>Menunggu TTD
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-folder-open text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Surat Selesai</h3>
                <p class="text-gray-500 mb-6">Riwayat surat yang sudah selesai diproses akan muncul di sini</p>
                <a href="{{ route('warga.buat_surat') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    <i class="fa-solid fa-plus"></i>
                    Ajukan Surat Baru
                </a>
            </div>
            @endforelse
        </div>
        
        <!-- Info Box -->
        @if($suratSelesai->count() > 0)
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-1">Informasi</h4>
                    <p class="text-sm text-blue-800">
                        Surat yang sudah selesai dapat langsung dicetak atau diunduh dalam format PDF. 
                        Pastikan untuk menyimpan salinan digital sebagai backup.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-full shadow-lg z-50 animate-bounce">
    <i class="fa-solid fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-full shadow-lg z-50">
    <i class="fa-solid fa-exclamation-circle mr-2"></i>
    {{ session('error') }}
</div>
@endif
@endsection