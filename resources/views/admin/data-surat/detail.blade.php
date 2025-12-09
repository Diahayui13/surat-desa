@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-5xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
           <a href="{{ route('admin.permohonan') }}" class="text-gray-600 hover:text-gray-800">

                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Surat</h1>
                <p class="text-sm text-gray-500">Informasi lengkap pengajuan surat</p>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @if($surat->status === 'Menunggu')
                <span class="inline-flex items-center bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fa-solid fa-clock mr-2"></i> Menunggu Verifikasi
                </span>
            @elseif($surat->status === 'Diproses')
                <span class="inline-flex items-center bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fa-solid fa-spinner mr-2"></i> Sedang Diproses
                </span>
            @elseif($surat->status === 'Selesai')
                <span class="inline-flex items-center bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fa-solid fa-check-circle mr-2"></i> Selesai
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Data Pemohon -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Info Pemohon -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-user text-blue-600"></i>
                        Detail Pemohon
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Nama</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->nama_pemohon }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">NIK</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->nik_pemohon }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">No. KK</label>
                            <p class="text-sm font-semibold text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Alamat</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->alamat }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">No. HP</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Surat -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-blue-600"></i>
                        Detail Surat
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Jenis Surat</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->jenis_surat }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Keperluan</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->keperluan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Tanggal Pengajuan</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y, H:i') : '-' }}</p>
                        </div>
                        @if($surat->tanggal_diproses)
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Tanggal Diproses</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->tanggal_diproses->format('d M Y, H:i') }}</p>
                        </div>
                        @endif
                        @if($surat->tanggal_selesai)
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Tanggal Selesai</label>
                            <p class="text-sm font-semibold text-gray-800">{{ $surat->tanggal_selesai->format('d M Y, H:i') }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Status</label>
                            <p class="text-sm font-semibold text-gray-800">
                                @if($surat->status === 'Menunggu')
                                    <span class="text-yellow-600">⏱ {{ $surat->status }}</span>
                                @elseif($surat->status === 'Diproses')
                                    <span class="text-green-600">✓ {{ $surat->status }}</span>
                                @else
                                    <span class="text-blue-600">✓ {{ $surat->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- File Pendukung -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-blue-600"></i>
                        File Pendukung
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        @if($surat->file_ktp)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-700">Surat Pengantar RT/RW</span>
                                <a href="{{ asset('storage/' . $surat->file_ktp) }}" target="_blank" 
                                class="text-blue-600 hover:text-blue-700">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $surat->file_ktp) }}" 
                                class="w-full h-32 object-cover rounded" 
                                alt="KTP"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect fill=\'%23f0f0f0\' width=\'100\' height=\'100\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'sans-serif\' font-size=\'14\'%3EGambar Error%3C/text%3E%3C/svg%3E'">
                        </div>
                        @endif

                        @if($surat->file_kk)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-700">Kartu Keluarga (KK)</span>
                                <a href="{{ asset('storage/' . $surat->file_kk) }}" target="_blank" 
                                class="text-blue-600 hover:text-blue-700">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $surat->file_kk) }}" 
                                class="w-full h-32 object-cover rounded" 
                                alt="KK"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect fill=\'%23f0f0f0\' width=\'100\' height=\'100\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'sans-serif\' font-size=\'14\'%3EGambar Error%3C/text%3E%3C/svg%3E'">
                        </div>
                        @endif

                        @if($surat->file_surat_pernyataan)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-700">Surat Pernyataan</span>
                                <a href="{{ asset('storage/' . $surat->file_surat_pernyataan) }}" target="_blank" 
                                class="text-blue-600 hover:text-blue-700">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $surat->file_surat_pernyataan) }}" 
                                class="w-full h-32 object-cover rounded" 
                                alt="Surat"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect fill=\'%23f0f0f0\' width=\'100\' height=\'100\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'sans-serif\' font-size=\'14\'%3EGambar Error%3C/text%3E%3C/svg%3E'">
                        </div>
                        @endif

                        @if($surat->file_foto_rumah)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-700">Foto Rumah</span>
                                <a href="{{ asset('storage/' . $surat->file_foto_rumah) }}" target="_blank" 
                                class="text-blue-600 hover:text-blue-700">
                                    <i class="fa-solid fa-external-link-alt"></i>
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $surat->file_foto_rumah) }}" 
                                class="w-full h-32 object-cover rounded" 
                                alt="Rumah"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'%3E%3Crect fill=\'%23f0f0f0\' width=\'100\' height=\'100\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-family=\'sans-serif\' font-size=\'14\'%3EGambar Error%3C/text%3E%3C/svg%3E'">
                        </div>
                        @endif

                        @if(!$surat->file_ktp && !$surat->file_kk && !$surat->file_surat_pernyataan && !$surat->file_foto_rumah)
                        <div class="col-span-2 text-center py-8 text-gray-400">
                            <i class="fa-regular fa-folder-open text-4xl mb-2"></i>
                            <p class="text-sm">Belum ada file pendukung</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-4">
                
                <!-- Change Status -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-md font-bold text-gray-800 mb-4">Ubah Status</h3>
                    
                    @if($surat->status === 'Menunggu')
                        <form action="{{ route('admin.surat.update-status', $surat->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Diproses">
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-play"></i>
                                Proses Surat
                            </button>
                        </form>
                    @elseif($surat->status === 'Diproses')
                        <form action="{{ route('admin.surat.update-status', $surat->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Selesai">
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i>
                                Selesaikan
                            </button>
                        </form>
                    @else
                        <div class="bg-blue-50 border border-green-100 rounded-lg p-4 text-center">
                            <i class="fa-solid fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-green-700">Surat Selesai</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-md font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                    
                    <div class="space-y-2">
                        @if($surat->file_ttd)
                            <!-- Preview Surat -->
                            <a href="{{ route('admin.surat.preview', $surat->id) }}" 
                            target="_blank"
                            class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-3 rounded-lg transition text-center">
                                <i class="fa-regular fa-eye mr-2"></i>
                                Preview Surat
                            </a>
                            
                            <!-- Download PDF -->
                            <a href="{{ route('admin.surat.download-pdf', $surat->id) }}" 
                            class="block w-full bg-green-100 hover:bg-green-200 text-green-700 font-medium py-3 rounded-lg transition text-center">
                                <i class="fa-solid fa-download mr-2"></i>
                                Download PDF
                            </a>
                        @else
                            <button disabled
                                    class="block w-full bg-gray-100 text-gray-400 font-medium py-3 rounded-lg cursor-not-allowed text-center">
                                <i class="fa-regular fa-eye mr-2"></i>
                                Belum Ada TTD
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Info Timeline -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-sm p-6 border border-blue-100">
                    <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                        Timeline
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                                <div class="w-0.5 h-full bg-blue-200 mt-1"></div>
                            </div>
                            <div class="pb-4">
                                <p class="text-sm font-semibold text-gray-800">Diajukan</p>
                                <p class="text-xs text-gray-500">{{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                        
                        @if($surat->tanggal_diproses)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-green-600"></div>
                                <div class="w-0.5 h-full bg-green-200
                                </div>
</div>
@endif