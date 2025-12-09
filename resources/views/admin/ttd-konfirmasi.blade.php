@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Tanda Tangan</h1>
            <p class="text-sm text-gray-500">Pastikan tanda tangan dan template surat sudah sesuai sebelum menyimpan</p>
        </div>

        <!-- Alert Info -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-xl mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-blue-800 text-sm">Periksa Kembali</h3>
                    <p class="text-xs text-blue-700 mt-1">
                        Pastikan tanda tangan terlihat jelas dan posisinya sesuai. Jika sudah benar, klik <strong>"Konfirmasi & Simpan"</strong> untuk memproses surat. 
                        Surat akan berpindah ke status <strong>"Diproses"</strong> dan siap diselesaikan.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- Preview Surat (Kiri - Lebih Besar) -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <i class="fa-regular fa-file-lines"></i>
                        Preview Surat dengan Tanda Tangan
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="border-2 border-gray-200 rounded-xl overflow-hidden bg-white shadow-inner">
                        <iframe srcdoc='{!! $preview !!}' class="w-full border-0" style="height: 75vh;"></iframe>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info & Aksi (Kanan) -->
            <div class="space-y-6">
                
                <!-- Info Surat -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-blue-600"></i>
                        Informasi Surat
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Pemohon</label>
                            <p class="font-semibold text-gray-800">{{ $permohonan->nama_pemohon }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">NIK</label>
                            <p class="font-semibold text-gray-800">{{ $permohonan->nik_pemohon }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Jenis Surat</label>
                            <p class="font-semibold text-gray-800">{{ $permohonan->jenis_surat }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Nomor Surat</label>
                            <p class="font-semibold text-gray-800">{{ $permohonan->nomor_surat ?? 'Belum ada' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium">Status Saat Ini</label>
                            <span class="inline-flex items-center bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium mt-1">
                                <i class="fa-solid fa-clock mr-1"></i> Menunggu Konfirmasi
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tanda Tangan Preview -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-pen-nib text-blue-600"></i>
                        Tanda Tangan
                    </h3>
                    
                    @if($permohonan->file_ttd)
                    <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                        <img src="{{ asset('storage/' . $permohonan->file_ttd) }}" 
                             alt="Tanda Tangan" 
                             class="max-h-32 mx-auto object-contain">
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        <i class="fa-solid fa-check-circle text-green-600"></i>
                        Tanda tangan berhasil diupload
                    </p>
                    @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fa-solid fa-exclamation-triangle text-3xl mb-2"></i>
                        <p class="text-sm">Tanda tangan tidak ditemukan</p>
                    </div>
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi</h3>
                    
                    <div class="space-y-3">
                        <!-- Tombol Konfirmasi & Simpan -->
                        <form action="{{ route('admin.simpan.ttd', $permohonan->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin tanda tangan dan surat sudah benar? Surat akan diproses dan tidak bisa diubah lagi.')"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-circle"></i>
                                Konfirmasi & Simpan
                            </button>
                        </form>

                        <!-- Tombol Batal -->
                        <form action="{{ route('admin.batal.konfirmasi', $permohonan->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan? Tanda tangan akan dihapus dan surat kembali ke status Menunggu.')"
                                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-medium py-3 rounded-lg transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-times-circle"></i>
                                Batal & Upload Ulang
                            </button>
                        </form>

                        <!-- Tombol Kembali -->
                        <a href="{{ route('admin.permohonan') }}" 
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-lg transition">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Kembali ke Permohonan
                        </a>
                    </div>
                </div>

                <!-- Info Status Setelah Konfirmasi -->
                <div class="bg-gradient-to-br from-green-50 to-white border border-green-200 rounded-xl p-4">
                    <h4 class="font-semibold text-green-800 text-sm mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-info-circle"></i>
                        Setelah Konfirmasi
                    </h4>
                    <ul class="text-xs text-green-700 space-y-1">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                            <span>Status surat berubah menjadi <strong>"Diproses"</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                            <span>Surat muncul di menu Permohonan dengan tombol <strong>"Selesaikan"</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-green-600 mt-0.5"></i>
                            <span>Admin dapat klik "Selesaikan" untuk menyelesaikan surat</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection