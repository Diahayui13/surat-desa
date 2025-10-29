@extends('layouts.admin')

@section('content')
<div class="p-6">
  <h1 class="text-2xl font-bold mb-1">Konfirmasi</h1>
  <p class="text-gray-500 mb-6">Gunakan tanda tangan digital untuk validasi surat resmi</p>

  <div class="bg-white rounded-xl shadow p-5">
    <h2 class="font-semibold mb-4">Preview Surat</h2>

    {{-- Preview HTML surat yang sudah diinject TTD --}}
    <div class="border rounded-xl overflow-hidden bg-gray-50">
      <iframe srcdoc='{!! $preview !!}' class="w-full" style="height: 70vh;"></iframe>
    </div>

    <form action="{{ route('admin.tanda.tangan.simpan', $permohonan->id) }}" method="POST" class="mt-4 flex gap-3">
      @csrf
      <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg">
        Konfirmasi & Simpan
      </button>
      <a href="{{ route('admin.ttddigital') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 py-2.5 rounded-lg">
        Batal
      </a>
    </form>
  </div>
</div>
@endsection
