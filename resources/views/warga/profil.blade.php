@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola profil dan akun Anda</p>
        </div>

        <!-- Card Profil dengan Avatar -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-4">
            <div class="flex items-center gap-4 mb-6">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Bergabung Sejak</p>
                            <p class="font-medium text-gray-800">{{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Pengaturan -->
        <div class="bg-white rounded-2xl shadow-sm mb-4 overflow-hidden">
            <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 w-10 h-10 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-key text-blue-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Ubah Password</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
            
            <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 w-10 h-10 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-circle-info text-green-600"></i>
                    </div>
                    <span class="font-medium text-gray-800">Bantuan</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
        </div>

        <!-- Tombol Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-2xl transition flex items-center justify-center gap-2 shadow-sm">
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                <span>Keluar dari Akun</span>
            </button>
        </form>

        <!-- Footer Info -->
        <div class="text-center mt-6 text-sm text-gray-400">
            <p>Desa Digital v1.0</p>
            <p class="mt-1">© 2025 Sumbertlaseh</p>
        </div>

    </div>
</div>
@endsection