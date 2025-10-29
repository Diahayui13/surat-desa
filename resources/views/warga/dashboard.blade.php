@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Selamat Datang,</p>
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                </div>
                <div class="bg-white/20 p-4 rounded-full">
                    <i class="fa-solid fa-user text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 text-center border border-gray-100">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid fa-file-lines text-blue-600 text-xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistik['total'] }}</div>
                <div class="text-xs text-gray-500">Total</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 text-center border border-gray-100">
                <div class="bg-yellow-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistik['menunggu'] }}</div>
                <div class="text-xs text-gray-500">Menunggu</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 text-center border border-gray-100">
                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid fa-spinner text-green-600 text-xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistik['diproses'] }}</div>
                <div class="text-xs text-gray-500">Proses</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 text-center border border-gray-100">
                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistik['selesai'] }}</div>
                <div class="text-xs text-gray-500">Selesai</div>
            </div>
        </div>

        <!-- Quick Action -->
        <a href="{{ route('warga.pilih-jenis-surat') }}" 
           class="block bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg p-6 mb-6 transition group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-xl">
                        <i class="fa-solid fa-file-circle-plus text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-1">Ajukan Surat Baru</h3>
                        <p class="text-sm opacity-90">Buat pengajuan surat dengan mudah</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-2xl group-hover:translate-x-1 transition"></i>
            </div>
        </a>

        <!-- Riwayat Surat -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Riwayat Pengajuan</h3>
                <a href="{{ route('warga.surat.masuk') }}" class="text-blue-600 text-sm font-medium hover:underline">
                    Lihat Semua
                </a>
            </div>

            <div class="space-y-3">
                @forelse($suratMasuk->take(5) as $surat)
                <div class="border border-gray-100 rounded-xl p-4 hover:border-blue-200 hover:bg-blue-50 transition">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 mb-1">{{ $surat->jenis_surat }}</h4>
                            <p class="text-xs text-gray-500 mb-2">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                {{ $surat->created_at->format('d M Y, H:i') }}
                            </p>
                            <div>
                                @if($surat->status === 'Menunggu')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($surat->status === 'Diproses')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="fa-solid fa-spinner mr-1"></i> Sedang Diproses
                                    </span>
                                @elseif($surat->status === 'Selesai')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-300"></i>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fa-regular fa-folder-open text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Belum ada pengajuan surat</p>
                    <a href="{{ route('warga.pilih-jenis-surat') }}" 
                       class="inline-block mt-3 text-blue-600 text-sm font-medium hover:underline">
                        Ajukan Surat Sekarang →
                    </a>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@if(session('success'))
<div class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-full shadow-lg z-50 animate-bounce">
    <i class="fa-solid fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif
@endsection