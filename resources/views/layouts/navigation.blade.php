<nav class="flex-1 p-4 space-y-2 overflow-y-auto">
    {{-- 1. DASHBOARD UTAMA (Berlaku untuk semua role) --}}
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl transition-all">
        <span>🏠</span> Dashboard Utama
    </a>

    {{-- 2. MENU KHUSUS KARYAWAN --}}
    @if(Auth::user()->role == 'karyawan')
        <div class="pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest px-4">Menu Saya</div>
        
        <a href="{{ route('cuti.ajukan') }}" class="flex items-center gap-4 p-4 {{ request()->routeIs('cuti.ajukan') ? 'bg-[#4318FF] text-white font-bold' : 'text-[#A3AED0] hover:bg-white/5' }} rounded-2xl transition-all">
            <span class="text-xl">📝</span>
            <span x-show="sidebarOpen">Ajukan Cuti</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-2xl font-medium transition-all">
            <span>💰</span> Slip Gaji
        </a>
    @endif

    {{-- 3. MENU KHUSUS HR --}}
    @if(strtolower(Auth::user()->role) === 'hr')
        <div class="pt-4 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest px-4">Menu HR</div>
        
        {{-- Menu Manajemen Karyawan (Gaya Baru yang Disamakan) --}}
        <a href="{{ route('hr.employees.index') }}" class="flex items-center gap-4 p-4 {{ request()->routeIs('hr.employees.*') ? 'bg-[#4318FF] text-white font-bold' : 'text-[#A3AED0] hover:bg-white/5' }} rounded-2xl transition-all">
            <span class="text-xl">👥</span>
            <span>Manajemen Karyawan</span>
        </a>

        {{-- Menu Persetujuan Cuti --}}
        <a href="{{ route('leaves.index') }}" class="flex items-center gap-4 p-4 {{ request()->routeIs('leaves.index') ? 'bg-[#4318FF] text-white font-bold' : 'text-[#A3AED0] hover:bg-white/5' }} rounded-2xl transition-all">
            <span class="text-xl">📋</span>
            <span>Persetujuan Cuti</span>
        </a>
    @endif

    {{-- 4. LOGOUT --}}
    <div class="pt-10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-2xl font-bold transition-all">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>
</nav>