<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Desa Digital') }} - Admin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Sidebar untuk desktop */
        @media (min-width: 768px) {
            .desktop-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 16rem;
                overflow-y: auto;
                z-index: 40;
            }
            .main-content { margin-left: 16rem; }
            .mobile-nav { display: none; }
        }
        
        /* Mobile Navigation */
        @media (max-width: 767px) {
            .desktop-sidebar {
                position: fixed;
                top: 0;
                left: -16rem;
                height: 100vh;
                width: 16rem;
                transition: left 0.3s ease;
                z-index: 50;
            }
            .desktop-sidebar.open { left: 0; }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 45;
            }
            .sidebar-overlay.show { display: block; }
            .main-content {
                margin-left: 0;
                padding-bottom: 5rem;
            }
            .mobile-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e5e7eb;
                z-index: 30;
            }
        }

        .no-scroll { overflow: hidden; }
        .mobile-nav { padding-bottom: env(safe-area-inset-bottom); }
        @media (max-width: 767px) {
            .main-content { padding-bottom: calc(5rem + env(safe-area-inset-bottom)); }
        }
    </style>
</head>
<body class="bg-gray-50">
@include('components.toast-notification')

    <!-- Overlay untuk mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Desktop Sidebar -->
    <aside class="desktop-sidebar bg-gradient-to-b from-blue-50 to-white shadow-lg" id="desktopSidebar">
        <div class="p-6">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-500 text-white rounded-lg p-2">
                    <i class="fa-solid fa-building-columns text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-blue-600">Desa Digital</h1>
                    <p class="text-xs text-gray-500">Panel Admin</p>
                </div>
            </div>
            
            <!-- Menu Items -->
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fa-solid fa-house w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.permohonan') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.permohonan') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fa-solid fa-inbox w-5"></i>
                    <span>Permohonan Masuk</span>
                </a>
                
                <a href="{{ route('admin.data.surat') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.data.surat') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fa-solid fa-folder-open w-5"></i>
                    <span>Data Surat</span>
                </a>
                
                <a href="{{ route('admin.profil') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.profil') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fa-solid fa-user w-5"></i>
                    <span>Profil Admin</span>
                </a>
            </nav>
            
            <!-- Logout Button (Desktop) -->
            <div class="mt-8">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-500 text-white font-medium hover:bg-blue-600 transition">
                        <i class="fa-solid fa-right-from-bracket w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Mobile Header -->
        <div class="md:hidden bg-white border-b px-4 py-3 flex items-center justify-between sticky top-0 z-30">
            <button onclick="toggleSidebar()" class="text-gray-700">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h2 class="text-lg font-bold text-blue-600">Desa Digital</h2>
            <div class="w-8"></div>
        </div>
        
        @yield('content')
    </main>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav">
        <div class="grid grid-cols-4 h-16">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-house text-xl mb-1"></i>
                <span class="text-xs">Beranda</span>
            </a>
            
            <a href="{{ route('admin.permohonan') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('admin.permohonan') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-inbox text-xl mb-1"></i>
                <span class="text-xs">Status</span>
            </a>
            
            <a href="{{ route('admin.data.surat') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('admin.data.surat') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-folder-open text-xl mb-1"></i>
                <span class="text-xs">Data Surat</span>
            </a>
            
            <a href="{{ route('admin.profil') }}"
               class="flex flex-col items-center justify-center {{ request()->routeIs('admin.profil') ? 'text-blue-600' : 'text-gray-400' }}">
                <i class="fa-solid fa-user text-xl mb-1"></i>
                <span class="text-xs">Profil</span>
            </a>
        </div>
    </nav>
    
<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('desktopSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const isOpen = sidebar.classList.toggle('open');

    overlay.classList.toggle('show', isOpen);
    document.body.classList.toggle('no-scroll', isOpen);

    const btn = document.querySelector('[onclick="toggleSidebar()"]');
    if (btn) btn.setAttribute('aria-expanded', String(isOpen));
  }

  document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('desktopSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburger = event.target.closest('button[onclick="toggleSidebar()"]');

    if (window.innerWidth < 768) {
      if (!sidebar.contains(event.target) && !hamburger) {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        document.body.classList.remove('no-scroll');
      }
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) document.body.classList.remove('no-scroll');
  });
</script>
</body>
</html>