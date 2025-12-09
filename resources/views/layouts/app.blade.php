<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Desa Digital') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gradient-to-br from-blue-50 to-blue-50 min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm py-3 px-4 flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-700">Aplikasi Surat Desa</h1>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="bg-blue-400 hover:bg-blue-500 text-white text-sm font-semibold py-1.5 px-4 rounded-xl transition">
                Logout
            </button>
        </form>
    </nav>

    <!-- HALAMAN UTAMA -->
    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>
