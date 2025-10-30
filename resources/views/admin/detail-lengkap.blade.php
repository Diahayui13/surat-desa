@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-4 md:p-6">
        
        <!-- Back Button & Header -->
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.permohonan') }}" 
               class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm hover:shadow-md transition">
                <i class="fa-solid fa-arrow-left text-gray-700"></i>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Detail Surat</h1>
        </div>

        <!-- Card Utama -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Header Status -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 md:px-6 py-4 border-b border-blue-200">
                @if($surat->status === 'Menunggu')
                    <span class="inline-flex items-center bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fa-solid fa-clock mr-2"></i> Menunggu Verifikasi
                    </span>
                @elseif($surat->status === 'Diproses')
                    <span class="inline-flex items-center bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fa-solid fa-check-circle mr-2"></i> Disetujui
                    </span>
                @elseif($surat->status === 'Selesai')
                    <span class="inline-flex items-center bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fa-solid fa-check-double mr-2"></i> Selesai
                    </span>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4 md:p-6 space-y-6">
                
                <!-- Detail Pemohon -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Detail Pemohon</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Nama</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $surat->nama_pemohon }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">NIK</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $surat->nik_pemohon }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">No. KK</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $surat->no_kk ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Alamat</span>
                            <span class="text-sm font-semibold text-gray-900 text-right">{{ $surat->alamat ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-600">No. HP</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $surat->user->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Detail Surat -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Detail Surat</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Jenis Surat</span>
                            <span class="text-sm font-semibold text-gray-900 text-right">{{ $surat->jenis_surat }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Keperluan</span>
                            <span class="text-sm font-semibold text-gray-900 text-right">{{ $surat->keperluan ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Tanggal Pengajuan</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y, H:i') : ($surat->created_at ? $surat->created_at->format('d M Y, H:i') : '-') }}
                            </span>
                        </div>
                        
                        @if($surat->tanggal_diproses)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Tanggal Diproses</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $surat->tanggal_diproses->format('d M Y, H:i') }}
                            </span>
                        </div>
                        @endif

                        @if($surat->tanggal_selesai)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Tanggal Selesai</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $surat->tanggal_selesai->format('d M Y, H:i') }}
                            </span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="text-sm font-semibold">
                                @if($surat->status === 'Menunggu')
                                    <span class="text-yellow-600">⏱ {{ $surat->status }}</span>
                                @elseif($surat->status === 'Diproses')
                                    <span class="text-green-600">✓ {{ $surat->status }}</span>
                                @else
                                    <span class="text-blue-600">✓ {{ $surat->status }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="sticky bottom-0 md:relative bg-white border-t border-gray-100 p-4 md:px-6 md:py-5">
                <div class="flex gap-3">
                    @if($surat->status === 'Menunggu')
                        <a href="{{ route('admin.surat.proses', $surat->id) }}" 
                           class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl transition flex items-center justify-center gap-2 font-medium shadow-sm">
                            <i class="fa-solid fa-play"></i>
                            <span>Proses</span>
                        </a>
                    @elseif($surat->status === 'Diproses')
                        <form action="{{ route('admin.surat.update-status', $surat->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Selesai">
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl transition flex items-center justify-center gap-2 font-medium shadow-sm">
                                <i class="fa-solid fa-check"></i>
                                <span>Selesaikan</span>
                            </button>
                        </form>
                    @else
                        <div class="flex-1 bg-green-50 border border-green-200 text-green-700 py-3 rounded-xl flex items-center justify-center gap-2 font-medium">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Surat Selesai</span>
                        </div>
                    @endif
                    
                    <a href="{{ route('admin.permohonan') }}" 
                       class="px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-xl transition font-medium">
                        Kembali
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection