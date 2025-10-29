<x-guest-layout>
    <div class="text-center py-20">
        <h1 class="text-3xl font-bold">Selamat datang di Desa Digital</h1>
        <p class="mt-2 text-gray-600">Login atau daftar untuk melanjutkan.</p>
        <div class="mt-4">
            <a href="{{ route('login') }}" class="text-blue-500 underline">Login</a> |
            <a href="{{ route('register') }}" class="text-blue-500 underline">Daftar</a>
        </div>
    </div>
</x-guest-layout>
