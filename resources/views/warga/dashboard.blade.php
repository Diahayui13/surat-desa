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
                <div class="flex items-center gap-4">
                    <!-- Icon Notifikasi -->
                    <a href="{{ route('warga.notifications') }}" class="relative">
                        <div class="bg-white/20 p-3 rounded-full hover:bg-white/30 transition">
                            <i class="fa-solid fa-bell text-2xl"></i>
                        </div>
                        @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                            {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                        </span>
                        @endif
                    </a>
            
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
                <div class="bg-emerald-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid fa-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistik['selesai'] }}</div>
                <div class="text-xs text-gray-500">Selesai</div>
            </div>
        </div>

        <!-- Menu Aksi Cepat -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('warga.buat_surat') }}" class="flex items-center gap-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg p-5 transition">
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fa-solid fa-file-circle-plus text-3xl"></i>
                </div>
                <div>
                    <h5 class="font-semibold text-lg">Ajukan Surat Baru</h5>
                    <p class="text-sm text-blue-100">Buat pengajuan surat dengan mudah</p>
                </div>
            </a>

            <a href="{{ route('warga.status') }}" class="flex items-center gap-4 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl shadow-lg p-5 transition">
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fa-solid fa-hourglass-half text-3xl"></i>
                </div>
                <div>
                    <h5 class="font-semibold text-lg">Status Pengajuan</h5>
                    <p class="text-sm text-yellow-100">Lihat status surat yang sedang diverifikasi</p>
                </div>
            </a>

            <a href="{{ route('warga.riwayat') }}" class="flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg p-5 transition">
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fa-solid fa-clock-rotate-left text-3xl"></i>
                </div>
                <div>
                    <h5 class="font-semibold text-lg">Riwayat Pengajuan</h5>
                    <p class="text-sm text-green-100">Daftar surat yang sudah selesai</p>
                </div>
            </a>

            <a href="{{ route('warga.profil') }}" class="flex items-center gap-4 bg-gray-600 hover:bg-gray-700 text-white rounded-xl shadow-lg p-5 transition">
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fa-solid fa-user-circle text-3xl"></i>
                </div>
                <div>
                    <h5 class="font-semibold text-lg">Profil Saya</h5>
                    <p class="text-sm text-gray-200">Lihat dan ubah data akun kamu</p>
                </div>
            </a>
        </div>

        <!-- Riwayat Pengajuan dengan Detail Status -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Riwayat Pengajuan</h3>
                <a href="{{ route('warga.surat.masuk') }}" class="text-blue-600 text-sm font-medium hover:underline">
                    Lihat Semua
                </a>
            </div>

            <div class="space-y-3">
                @forelse($suratMasuk->take(5) as $surat)
                <div class="border border-gray-100 rounded-xl p-4 hover:border-blue-200 hover:bg-blue-50 transition cursor-pointer"
                     onclick="showDetailModal({{ json_encode($surat) }})">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 mb-1">{{ $surat->jenis_surat }}</h4>
                            <p class="text-xs text-gray-500 mb-2">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                {{ $surat->created_at->format('d M Y, H:i') }}
                            </p>
                            <div class="flex items-center gap-2">
                                @if($surat->status === 'Menunggu')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($surat->status === 'Diproses')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
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
                    <a href="{{ route('warga.buat_surat') }}"
                       class="inline-block mt-3 text-blue-600 text-sm font-medium hover:underline">
                        Ajukan Surat Sekarang →
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Surat -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Detail Pengajuan Surat</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6" id="modalContent">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
function showDetailModal(surat) {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    
    // Format tanggal
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };
    
    // Status badge
    let statusBadge = '';
    if (surat.status === 'Menunggu') {
        statusBadge = '<span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-medium"><i class="fa-solid fa-clock mr-2"></i>Menunggu Verifikasi</span>';
    } else if (surat.status === 'Diproses') {
        statusBadge = '<span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium"><i class="fa-solid fa-spinner mr-2"></i>Sedang Diproses</span>';
    } else if (surat.status === 'Selesai') {
        statusBadge = '<span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium"><i class="fa-solid fa-check-circle mr-2"></i>Selesai</span>';
    }
    
    // Timeline
    let timeline = '<div class="space-y-3">';
    timeline += `
        <div class="flex items-start gap-3">
            <div class="bg-yellow-100 p-2 rounded-full mt-1">
                <i class="fa-solid fa-paper-plane text-yellow-600"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-gray-800">Pengajuan Dikirim</p>
                <p class="text-sm text-gray-500">${formatDate(surat.tanggal_pengajuan || surat.created_at)}</p>
            </div>
        </div>
    `;
    
    if (surat.tanggal_diproses) {
        timeline += `
            <div class="flex items-start gap-3">
                <div class="bg-blue-100 p-2 rounded-full mt-1">
                    <i class="fa-solid fa-hourglass-half text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Sedang Diproses</p>
                    <p class="text-sm text-gray-500">${formatDate(surat.tanggal_diproses)}</p>
                </div>
            </div>
        `;
    }
    
    if (surat.tanggal_selesai) {
        timeline += `
            <div class="flex items-start gap-3">
                <div class="bg-green-100 p-2 rounded-full mt-1">
                    <i class="fa-solid fa-check-circle text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Selesai Diproses</p>
                    <p class="text-sm text-gray-500">${formatDate(surat.tanggal_selesai)}</p>
                </div>
            </div>
        `;
    }
    timeline += '</div>';
    
    // Tombol cetak (hanya jika selesai)
    let cetakButton = '';
    if (surat.status === 'Selesai' && surat.file_ttd) {
        cetakButton = `
            <div class="mt-6 flex gap-3">
                <a href="/warga/surat/${surat.id}/preview" target="_blank"
                   class="flex-1 text-center bg-blue-100 text-blue-700 px-4 py-3 rounded-xl font-medium hover:bg-blue-200 transition">
                    <i class="fa-solid fa-eye mr-2"></i>Preview Surat
                </a>
                <a href="/warga/surat/${surat.id}/download-pdf"
                   class="flex-1 text-center bg-green-600 text-white px-4 py-3 rounded-xl font-medium hover:bg-green-700 transition">
                    <i class="fa-solid fa-download mr-2"></i>Cetak PDF
                </a>
            </div>
        `;
    }
    
    modalContent.innerHTML = `
        <div class="space-y-6">
            <!-- Status -->
            <div class="text-center">
                ${statusBadge}
            </div>
            
            <!-- Info Surat -->
            <div class="bg-gray-50 rounded-xl p-4">
                <h4 class="font-semibold text-gray-800 mb-3">Informasi Surat</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Surat:</span>
                        <span class="font-medium text-gray-800">${surat.jenis_surat}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Pemohon:</span>
                        <span class="font-medium text-gray-800">${surat.nama_pemohon}</span>
                    </div>
                    ${surat.nomor_surat ? `
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Surat:</span>
                        <span class="font-medium text-gray-800">${surat.nomor_surat}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
            
            <!-- Timeline -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-3">Timeline Proses</h4>
                ${timeline}
            </div>
            
            ${cetakButton}
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>

@if(session('success'))
<div class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-full shadow-lg z-50 animate-bounce">
    <i class="fa-solid fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
<script>
    // Auto hide after 5 seconds
    setTimeout(() => {
        const successMsg = document.querySelector('.animate-bounce');
        if (successMsg) {
            successMsg.style.animation = 'fadeOut 0.5s';
            setTimeout(() => successMsg.remove(), 500);
        }
    }, 5000);
</script>
@endif
@endsection