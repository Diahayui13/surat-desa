@extends('layouts.warga')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('warga.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Notifikasi</h1>
                    <p class="text-sm text-gray-500">Pemberitahuan terkait pengajuan surat Anda</p>
                </div>
            </div>
            
            @if(Auth::user()->unreadNotifications->count() > 0)
            <form action="{{ route('warga.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    <i class="fa-solid fa-check-double mr-1"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
            <a href="{{ route('warga.notifications.read', $notification->id) }}"
               class="block bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-l-blue-500' }}">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-semibold text-gray-800">
                                Surat Selesai Diproses
                            </h3>
                            @if(!$notification->read_at)
                            <span class="flex-shrink-0 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                Baru
                            </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $notification->data['message'] ?? 'Surat Anda telah selesai diproses' }}
                        </p>
                        
                        @if(isset($notification->data['jenis_surat']))
                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-2">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-file-lines"></i>
                                {{ $notification->data['jenis_surat'] }}
                            </span>
                            @if(isset($notification->data['nomor_surat']))
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-hashtag"></i>
                                {{ $notification->data['nomor_surat'] }}
                            </span>
                            @endif
                        </div>
                        @endif
                        
                        <p class="text-xs text-gray-400 mt-2">
                            <i class="fa-regular fa-clock mr-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    <!-- Arrow -->
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-chevron-right text-gray-300"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-bell-slash text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Notifikasi</h3>
                <p class="text-gray-500">Notifikasi tentang status surat Anda akan muncul di sini</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-full shadow-lg z-50 animate-bounce">
    <i class="fa-solid fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif
@endsection