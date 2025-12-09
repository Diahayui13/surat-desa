@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Admin</h1>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center gap-6 mb-6">
                <div class="bg-blue-100 w-24 h-24 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user text-blue-600 text-4xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                        Administrator
                    </span>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Nama Lengkap</label>
                        <p class="text-gray-800 font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="text-gray-800 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Role</label>
                        <p class="text-gray-800 font-medium">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection