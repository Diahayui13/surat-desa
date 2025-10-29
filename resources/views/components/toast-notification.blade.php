@if(session('success'))
<div id="toast-success" class="fixed bottom-20 md:bottom-6 right-6 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 flex items-center gap-3 animate-slide-in">
    <i class="fa-solid fa-circle-check text-2xl"></i>
    <div>
        <p class="font-semibold">Berhasil!</p>
        <p class="text-sm">{{ session('success') }}</p>
    </div>
    <button onclick="closeToast('toast-success')" class="ml-4 text-white hover:text-gray-200">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@endif

@if(session('error'))
<div id="toast-error" class="fixed bottom-20 md:bottom-6 right-6 bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 flex items-center gap-3 animate-slide-in">
    <i class="fa-solid fa-circle-exclamation text-2xl"></i>
    <div>
        <p class="font-semibold">Error!</p>
        <p class="text-sm">{{ session('error') }}</p>
    </div>
    <button onclick="closeToast('toast-error')" class="ml-4 text-white hover:text-gray-200">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@endif

<style>
    @keyframes slide-in {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>

<script>
    function closeToast(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    // Auto close after 5 seconds
    setTimeout(() => {
        const toasts = document.querySelectorAll('[id^="toast-"]');
        toasts.forEach(toast => {
            if (toast) toast.style.display = 'none';
        });
    }, 5000);
</script>