@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">🌸 Login ke Akun Anda</h2>

        @if (session('status'))
            <div class="mb-4 text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-300 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-600 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-pink-300 focus:outline-none">
            </div>

            <div class="flex justify-between items-center mb-6">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="mr-2 rounded text-pink-400 focus:ring-pink-300">
                    Ingat saya
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                    Lupa password?
                </a>
            </div>

            <button type="submit"
                class="w-full bg-pink-400 hover:bg-pink-500 text-white font-semibold py-2 rounded-lg transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6 text-sm">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-pink-500 hover:underline font-semibold">Daftar</a>
        </p>
    </div>
</div>
@endsection
