@extends('layouts.warga')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Status Pengajuan Surat</h2>

    {{-- Statistik kecil di atas --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="p-4 border rounded-xl text-center bg-blue-50">
            <p class="text-2xl font-bold text-blue-700">{{ $surat->count() }}</p>
            <p class="text-sm text-gray-600">Total Pengajuan</p>
        </div>
        <div class="p-4 border rounded-xl text-center bg-yellow-50">
            <p class="text-2xl font-bold text-yellow-700">{{ $surat->where('status', 'Menunggu')->count() }}</p>
            <p class="text-sm text-gray-600">Menunggu</p>
        </div>
        <div class="p-4 border rounded-xl text-center bg-green-50">
            <p class="text-2xl font-bold text-green-700">{{ $surat->where('status', 'Selesai')->count() }}</p>
            <p class="text-sm text-gray-600">Selesai</p>
        </div>
    </div>

    {{-- Daftar Surat --}}
    <div class="space-y-3">
        @forelse($surat as $item)
        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">{{ $item->jenis_surat }}</p>
                    <p class="text-sm text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</p>
                </div>

                {{-- Status Warna --}}
                @if($item->status == 'Menunggu')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Menunggu</span>
                @elseif($item->status == 'Diproses')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Diproses</span>
                @elseif($item->status == 'Selesai')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Selesai</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ $item->status }}</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-10">Belum ada pengajuan surat.</p>
        @endforelse
    </div>

</div>
@endsection