@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Permohonan Masuk</h2>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="p-4 border rounded-lg text-center bg-blue-50">
            <p class="text-2xl font-bold">{{ $permohonan->count() }}</p>
            <p>Total</p>
        </div>
        <div class="p-4 border rounded-lg text-center bg-yellow-50">
            <p class="text-2xl font-bold">{{ $permohonan->where('status', 'Menunggu')->count() }}</p>
            <p>Menunggu</p>
        </div>
        <div class="p-4 border rounded-lg text-center bg-green-50">
            <p class="text-2xl font-bold">{{ $permohonan->where('status', 'Proses')->count() }}</p>
            <p>Proses</p>
        </div>
        <div class="p-4 border rounded-lg text-center bg-blue-50">
            <p class="text-2xl font-bold">{{ $permohonan->where('status', 'Selesai')->count() }}</p>
            <p>Selesai</p>
        </div>
    </div>

    @foreach ($permohonan as $p)
    <div class="bg-white p-4 rounded-lg shadow mb-3 flex justify-between items-center">
        <div>
            <p class="font-semibold">{{ $p->nama }}</p>
            <p class="text-sm text-gray-500">{{ $p->jenis_surat }}</p>
            <p class="text-xs text-gray-400">{{ $p->created_at->format('d M Y, H:i') }}</p>
        </div>

        <form action="{{ route('admin.permohonan.updateStatus', $p->id) }}" method="POST" class="flex gap-2">
            @csrf
            @if ($p->status == 'Menunggu')
                <input type="hidden" name="status" value="Proses">
                <button class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition">Proses</button>
            @elseif ($p->status == 'Proses')
                <input type="hidden" name="status" value="Selesai">
                <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition">Selesai</button>
            @elseif ($p->status == 'Selesai')
                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-md">Selesai</span>
            @endif
        </form>
    </div>
    @endforeach
</div>
@endsection
