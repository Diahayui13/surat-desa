@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] p-4">

    <!-- Header -->
    <div class="flex items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="ml-3 text-lg font-semibold text-gray-800">Data Surat</h1>
    </div>

    <p class="text-sm text-gray-500 mb-3">
        Daftar surat masuk dan terbit dari sistem e-surat sumberslash
    </p>

    <!-- Search bar -->
    <div class="flex items-center bg-white rounded-full shadow-sm px-3 py-2 mb-3">
        <input type="text" placeholder="Cari nama / NIK"
               class="flex-grow text-sm bg-transparent outline-none text-gray-700">
        <button class="text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V13a6.002 6.002 0 00-5-5.917V7a2 2 0 10-4 0v.083A6.002 6.002 0 004 13v1.159c0 .538-.214 1.055-.595 1.436L2 17h5m8 0a3 3 0 11-6 0h6z" />
            </svg>
        </button>
        <button class="ml-2 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17V12.414L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
        </button>
    </div>

    <!-- Filter chips -->
    <div class="flex space-x-2 mb-4">
        <button class="bg-white border text-gray-600 text-sm px-4 py-1.5 rounded-full flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10m-9 4h9m-11 4h13" />
            </svg>
            Tanggal
        </button>
        <button class="bg-white border text-gray-600 text-sm px-4 py-1.5 rounded-full flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17V12.414L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Jenis Surat
        </button>
    </div>

    <!-- Riwayat surat -->
    <div class="space-y-3 pb-20">
        @foreach ($surats as $s)
        <div class="bg-white rounded-2xl shadow-sm p-4 flex justify-between items-center hover:bg-blue-50 transition">
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">{{ $s->nama_pemohon }}</h3>
                <p class="text-xs text-gray-500">{{ $s->jenis_surat }}</p>
                <p class="text-xs text-gray-400">{{ $s->tanggal ?? '10 Okt 2025' }}</p>
            </div>

            <div class="flex flex-col items-end">
                @if ($s->status === 'Disetujui')
                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full mb-2">Disetujui</span>
                @elseif ($s->status === 'Proses')
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full mb-2">Proses</span>
                @elseif ($s->status === 'Ditolak')
                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full mb-2">Ditolak</span>
                @endif
               <a href="{{ route('admin.surat.detail', $s->id) }}" class="text-gray-400 hover:text-gray-700">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
