 
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50 p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Admin</h1>
            <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}! 👋</p>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Surat -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Surat</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $totalSurat ?? 0 }}</h3>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fa-solid fa-file-lines text-blue-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Menunggu</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $menunggu ?? 0 }}</h3>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class="fa-solid fa-clock text-yellow-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Diproses -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Diproses</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $diproses ?? 0 }}</h3>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fa-solid fa-spinner text-green-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Selesai</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $selesai ?? 0 }}</h3>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fa-solid fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.permohonan') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition group">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-4 rounded-full group-hover:bg-blue-200 transition">
                        <i class="fa-solid fa-inbox text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-1">Permohonan Masuk</h4>
                        <p class="text-sm text-gray-500">Kelola permohonan surat warga</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.data.surat') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition group">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-4 rounded-full group-hover:bg-green-200 transition">
                        <i class="fa-solid fa-folder-open text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-1">Data Surat</h4>
                        <p class="text-sm text-gray-500">Lihat semua data surat</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Surat Terbaru -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-blue-500"></i>
                Surat Terbaru
            </h3>
            
            @if(isset($suratTerbaru) && count($suratTerbaru) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pemohon</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jenis Surat</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($suratTerbaru as $surat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $surat->nama_pemohon ?? $surat->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $surat->jenis_surat ?? $surat->judul }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $surat->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    @if($surat->status === 'Menunggu')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Menunggu</span>
                                    @elseif($surat->status === 'Diproses')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Diproses</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.surat.detail', $surat->id) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                        Lihat Detail →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fa-regular fa-folder-open text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Belum ada surat masuk</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection