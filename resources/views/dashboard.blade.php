@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <div class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-lg font-semibold">Desa Digital - Admin</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 px-3 py-1 rounded-md">Logout</button>
        </form>
    </div>

    <!-- Menu -->
    <div class="grid grid-cols-2 gap-4 p-4 sm:grid-cols-3 md:grid-cols-4">
        <a href="{{ route('admin.permohonan') }}" class="bg-white rounded-xl shadow p-4 text-center hover:bg-blue-100">
            📬<div class="mt-2 font-semibold">Permohonan Masuk</div>
        </a>

        <a href="{{ route('admin.surat') }}" class="bg-white rounded-xl shadow p-4 text-center hover:bg-blue-100">
            📄<div class="mt-2 font-semibold">Data Surat</div>
        </a>

        <a href="{{ route('admin.ttddigital') }}" class="bg-white rounded-xl shadow p-4 text-center hover:bg-blue-100">
            ✍️<div class="mt-2 font-semibold">TTD Digital</div>
        </a>

        <a href="{{ route('admin.profiladmin') }}" class="bg-white rounded-xl shadow p-4 text-center hover:bg-blue-100">
            👤<div class="mt-2 font-semibold">Profil Admin</div>
        </a>
    </div>

    <!-- Konten Utama -->
    <div class="p-4">
        <h2 class="text-xl font-bold mb-2">Selamat Datang, Admin!</h2>
        <p class="text-gray-600">Gunakan menu di atas untuk mengelola surat, warga, dan pengaturan sistem.</p>
    </div>
</div>
@endsection
