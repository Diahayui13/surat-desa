@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Profil Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi profil Anda</p>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Profil -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-3xl font-bold text-blue-600">
                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">{{ $admin->name ?? 'Admin' }}</h2>
                        <p class="text-blue-100 text-sm">{{ $admin->email ?? 'admin@example.com' }}</p>
                        <span class="inline-block mt-2 bg-white/20 px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fa-solid fa-crown mr-1"></i> Administrator
                        </span>
                    </div>
                </div>
            </div>

            <!-- Body Card -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                
                <div class="space-y-4">
                    <!-- Nama -->
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">Nama Lengkap</p>
                            <p class="text-gray-800 font-semibold">{{ $admin->name ?? 'Tidak ada data' }}</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">Email</p>
                            <p class="text-gray-800 font-semibold">{{ $admin->email ?? 'Tidak ada data' }}</p>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">Role</p>
                            <p class="text-gray-800 font-semibold capitalize">{{ $admin->role ?? 'Admin' }}</p>
                        </div>
                    </div>

                    <!-- Terdaftar Sejak -->
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-medium">Terdaftar Sejak</p>
                            <p class="text-gray-800 font-semibold">
                                {{ $admin->created_at ? \Carbon\Carbon::parse($admin->created_at)->locale('id')->isoFormat('D MMMM Y') : 'Tidak ada data' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Edit (Optional) -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('profile.edit') }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection