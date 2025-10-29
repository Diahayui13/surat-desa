<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Desa Digital') }} - Warga</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Mobile Bottom Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            z-index: 30;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        
        .main-content {
            padding-bottom: 5rem;
        }
        
        @media (min-width: 768px) {
            .mobile-nav {
                display: none;
            }
            
            .main-content {
                padding-bottom: 0;
            }
        }
        /* Safe-area iOS untuk bottom navigation */
        .mobile-nav { padding-bottom: env(safe-area-inset-bottom); }
        .main-content { padding-bottom: calc(5rem + env(safe-area-inset-bottom)); }
        @media (min-width: 768px) {
        .main-content { padding-bottom: 0; }
        }

    </style>
</head>
<body class="bg-gray-50">
     @include('components.toast-notification')
    <!-- Desktop Header -->
    <div class="hidden md:block bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 sticky top-0 z-20 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="fa-solid fa-building-columns text-white text-xl"></i>
                </div>
                <div class="text-white">
                    <h2 class="text-lg font-bold">Desa Digital</h2>
                    <p class="text-xs opacity-90">Sumbertlaseh</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-white text-sm">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Mobile Header -->
        <div class="md:hidden bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-4 sticky top-0 z-20 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i class="fa-solid fa-building-columns text-white text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-lg font-bold">Desa Digital</h2>
                        <p class="text-xs opacity-90">Sumbertlaseh</p>
                    </div>
                </div>
                <button class="text-white">
                    <i class="fa-solid fa-bell text-xl"></i>
                </button>
            </div>
        </div>
        
        @yield('content')
    </main>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav">
        <div class="grid grid-cols-4 h-16">
            <a href="{{ route('warga.dashboard') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('warga.dashboard') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-house text-xl mb-1"></i>
                <span class="text-xs">Beranda</span>
            </a>
            
            <a href="{{ route('warga.pilih-jenis-surat') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('warga.pilih-jenis-surat') || request()->routeIs('warga.form-pengajuan') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-file-circle-plus text-xl mb-1"></i>
                <span class="text-xs">Ajukan</span>
            </a>
            
            <a href="{{ route('warga.surat.masuk') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('warga.surat.masuk') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-folder-open text-xl mb-1"></i>
                <span class="text-xs">Riwayat</span>
            </a>
            
            <a href="{{ route('warga.pengaturan') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('warga.pengaturan') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-user text-xl mb-1"></i>
                <span class="text-xs">Profil</span>
            </a>
        </div>
    </nav>
</body>
</html>