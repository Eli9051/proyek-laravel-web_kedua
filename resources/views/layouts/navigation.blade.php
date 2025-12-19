<aside class="w-64 min-h-screen bg-white border-r border-gray-100 hidden md:block fixed">
    <div class="p-6">
        <img src="public\images\logo-perusahaan.png" class="w-16 h-16 object-contain mb-6 drop-shadow-md">

        <nav class="space-y-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                🏠 Dashboard
            </x-nav-link>

            @if(Auth::user()->role == 'hr')
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Menu HRD</div>
                <x-nav-link href="#">👥 Kelola Karyawan</x-nav-link>
                <x-nav-link href="#">📊 Prediksi Resign AI</x-nav-link>
                <x-nav-link href="#">📅 Persetujuan Cuti</x-nav-link>
            @endif

            @if(Auth::user()->role == 'karyawan')
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Menu Saya</div>
                <x-nav-link href="#">⏰ Absensi Online</x-nav-link>
                <x-nav-link href="#">📝 Ajukan Cuti</x-nav-link>
                <x-nav-link href="#">💰 Slip Gaji</x-nav-link>
            @endif
        </nav>
    </div>
</aside>

<div class="absolute bottom-0 w-full p-6 border-t border-gray-100 bg-gray-50/50">
    <form method="POST" action="{{ route('logout') }}">
        @csrf <button type="submit" class="flex items-center gap-3 text-red-500 font-bold hover:text-red-700 transition-colors w-full group">
            <span class="text-xl group-hover:scale-110 transition-transform">🚪</span>
            <span>Keluar Sistem</span>
        </button>
    </form>
</div>