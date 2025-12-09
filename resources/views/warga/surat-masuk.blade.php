@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">📬 Surat Masuk Saya</h2>

    @if ($suratMasuk->isEmpty())
        <p class="text-gray-500">Belum ada surat yang diajukan.</p>
    @else
        <div class="space-y-4">
            @foreach ($suratMasuk as $surat)
                <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition">
                    <h3 class="font-semibold text-gray-800">{{ $surat->jenis_surat }}</h3>
                    <p class="text-sm text-gray-500">Status: 
                        <span class="font-medium 
                            {{ $surat->status == 'Selesai' ? 'text-green-600' : ($surat->status == 'Diproses' ? 'text-yellow-600' : 'text-gray-600') }}">
                            {{ $surat->status }}
                        </span>
                    </p>
                    <p class="text-xs text-gray-400">Diajukan pada {{ $surat->created_at->format('d M Y H:i') }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
