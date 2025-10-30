@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Data Surat</h1>
            <p class="text-gray-500 mt-1">Daftar surat masuk dan terbit dari sistem e-surat sumbertlaseh</p>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <!-- Search Box -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Cari nama / NIK"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Tanggal -->
                <button 
                    onclick="openFilterModal('tanggal')"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2"
                >
                    <i class="fa-solid fa-calendar"></i>
                    <span>Tanggal</span>
                </button>

                <!-- Filter Jenis Surat -->
                <button 
                    onclick="openFilterModal('jenis')"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2"
                >
                    <i class="fa-solid fa-filter"></i>
                    <span>Jenis Surat</span>
                </button>

                <!-- Refresh Button -->
                <button 
                    onclick="refreshData()"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
        </div>

        <!-- Daftar Surat -->
        <div id="suratList" class="space-y-3">
            @forelse($surats as $surat)
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow cursor-pointer" 
                 onclick="window.location='{{ route('admin.surat.detail', $surat->id) }}'">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Nama & Jenis Surat -->
                        <h3 class="font-semibold text-gray-800 mb-1">
                            {{ $surat->nama_pemohon ?? $surat->user->name }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $surat->jenis_surat }}
                        </p>

                        <!-- Info Tanggal & NIK -->
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->format('d M Y, H:i') }}
                            </span>
                            <span>NIK: {{ $surat->nik_pemohon ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="ml-4">
                        @if($surat->status == 'Selesai')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                <i class="fa-solid fa-check-circle"></i> Selesai
                            </span>
                        @elseif($surat->status == 'Diproses')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                <i class="fa-solid fa-clock"></i> Proses
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                <i class="fa-solid fa-hourglass"></i> Menunggu
                            </span>
                        @endif
                    </div>

                    <!-- Arrow -->
                    <i class="fa-solid fa-chevron-right text-gray-400 ml-2"></i>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">Belum ada data surat</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($surats->hasPages())
        <div class="mt-6">
            {{ $surats->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Filter Tanggal -->
<div id="filterTanggalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Filter Tanggal</h3>
            <button onclick="closeFilterModal('tanggal')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="filterTanggalForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="dari_tanggal"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="sampai_tanggal"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div class="flex gap-3 pt-2">
                <button 
                    type="button"
                    onclick="resetFilterTanggal()"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Reset
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Filter Jenis Surat -->
<div id="filterJenisModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Filter Jenis Surat</h3>
            <button onclick="closeFilterModal('jenis')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="filterJenisForm" class="space-y-3">
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" name="jenis_surat[]" value="Surat Keterangan Domisili" class="mr-3">
                <span class="text-sm">Surat Keterangan Domisili</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" name="jenis_surat[]" value="Surat Keterangan Tidak Mampu" class="mr-3">
                <span class="text-sm">Surat Keterangan Tidak Mampu</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" name="jenis_surat[]" value="Surat Keterangan Usaha" class="mr-3">
                <span class="text-sm">Surat Keterangan Usaha</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <input type="checkbox" name="jenis_surat[]" value="Surat Keterangan Kelahiran" class="mr-3">
                <span class="text-sm">Surat Keterangan Kelahiran</span>
            </label>
            
            <div class="flex gap-3 pt-2">
                <button 
                    type="button"
                    onclick="resetFilterJenis()"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Reset
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Search functionality
let searchTimeout;
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterData();
        }, 500);
    });
}

// Modal functions
function openFilterModal(type) {
    const modalId = 'filter' + capitalize(type) + 'Modal';
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('hidden');
}

function closeFilterModal(type) {
    const modalId = 'filter' + capitalize(type) + 'Modal';
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('hidden');
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Filter form submissions
const filterTanggalForm = document.getElementById('filterTanggalForm');
if (filterTanggalForm) {
    filterTanggalForm.addEventListener('submit', function(e) {
        e.preventDefault();
        filterData();
        closeFilterModal('tanggal');
    });
}

const filterJenisForm = document.getElementById('filterJenisForm');
if (filterJenisForm) {
    filterJenisForm.addEventListener('submit', function(e) {
        e.preventDefault();
        filterData();
        closeFilterModal('jenis');
    });
}

// Reset filters
function resetFilterTanggal() {
    document.getElementById('filterTanggalForm').reset();
    filterData();
}

function resetFilterJenis() {
    document.getElementById('filterJenisForm').reset();
    filterData();
}

// Main filter function
function filterData() {
    const search = document.getElementById('searchInput')?.value || '';
    const dariTanggal = document.querySelector('[name="dari_tanggal"]')?.value || '';
    const sampaiTanggal = document.querySelector('[name="sampai_tanggal"]')?.value || '';
    const jenisSurat = Array.from(document.querySelectorAll('[name="jenis_surat[]"]:checked'))
        .map(cb => cb.value);
    
    // Build query string
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (dariTanggal) params.append('dari_tanggal', dariTanggal);
    if (sampaiTanggal) params.append('sampai_tanggal', sampaiTanggal);
    jenisSurat.forEach(js => params.append('jenis_surat[]', js));
    
    // Reload page with filters
    window.location.href = '{{ route("admin.surat") }}?' + params.toString();
}

// Refresh data
function refreshData() {
    window.location.href = '{{ route("admin.surat") }}';
}

// Close modal on outside click
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script>

@endsection