@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">💙 Buat Akun Baru</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-600 mb-1">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-600 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-600 mb-1">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full bg-blue-400 hover:bg-blue-500 text-white font-semibold py-2 rounded-lg transition">
                Daftar
            </button>

            <p class="text-center text-gray-600 mt-6 text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline font-semibold">Login</a>
            </p>
        </form>
    </div>
</div>
@endsection
