@extends('layouts.admin')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-md mt-10 p-6 text-center">
    <h2 class="text-xl font-semibold text-gray-700 mb-2">Tanda Tangan</h2>
    <p class="text-gray-500 mb-6">Gunakan tanda tangan digital untuk validasi surat resmi</p>

    <div class="border border-gray-200 rounded-2xl py-8 px-4 flex justify-center items-center bg-gray-50">
        <img src="{{ asset('images/sample-signature.png') }}" alt="Tanda tangan" class="h-32 mx-auto">
    </div>

    <div class="flex justify-center gap-4 mt-6">
        <button class="bg-blue-400 hover:bg-blue-500 text-white px-5 py-2 rounded-xl text-sm">
            🖋️ Gambar
        </button>
        <form action="{{ route('admin.tanda.tangan.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="surat_id" value="{{ $surat->id ?? 1 }}">
            <label class="bg-blue-400 hover:bg-blue-500 text-white px-5 py-2 rounded-xl text-sm cursor-pointer">
                📁 Upload
                <input type="file" name="tanda_tangan" class="hidden" onchange="this.form.submit()">
            </label>
        </form>
    </div>

    <a href="{{ route('admin.tanda.tangan.konfirmasi', $surat->id ?? 1) }}" 
       class="block mt-6 bg-gradient-to-r from-blue-400 to-blue-400 hover:opacity-90 text-white px-6 py-3 rounded-xl font-semibold text-sm">
        Gunakan Tanda Tangan ini
    </a>
</div>
@endsection
