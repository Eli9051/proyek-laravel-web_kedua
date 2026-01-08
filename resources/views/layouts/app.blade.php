<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyAbsensi - Premium</title>
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Scrollbar halus untuk sidebar */
        nav::-webkit-scrollbar {
            width: 4px;
        }

        nav::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        /* Utility untuk line clamp pada konten pengumuman */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-[#f2f2f7] font-sans antialiased text-[#1f1f1f]"
    x-data="{ sidebarOpen: window.innerWidth > 768 }">
    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 bg-[#191c24] text-[#b1b1b1] transition-all duration-300 ease-in-out flex flex-col shadow-xl"
            :class="sidebarOpen ? 'w-64' : 'w-0 -translate-x-full md:w-20 md:translate-x-0'" x-cloak>

            <div class="h-20 flex items-center justify-center border-b border-gray-700 px-4">
                <span class="text-xl font-bold text-white tracking-widest uppercase" x-show="sidebarOpen">MYABSENSI</span>
                <span class="text-xl font-bold text-white uppercase" x-show="!sidebarOpen">MA</span>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                {{-- Dashboard Utama --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('dashboard') ? 'bg-[#0090e7] text-white shadow-lg' : 'hover:bg-gray-800' }} rounded-xl transition-all font-semibold mb-2">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                {{-- MENU KHUSUS HR --}}
                @if(Auth::check() && strtolower(Auth::user()->role) === 'hr')
                <div class="pt-4 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest px-4" x-show="sidebarOpen">Menu HR</div>

                <a href="{{ route('hr.employees.index') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('hr.employees.*') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="users" class="w-5 h-5 text-blue-400"></i>
                    <span x-show="sidebarOpen">Manajemen Karyawan</span>
                </a>

                <a href="{{ route('hr.payroll.index') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('hr.payroll.*') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="banknote" class="w-5 h-5 text-emerald-400"></i>
                    <span x-show="sidebarOpen">Payroll HR</span>
                </a>

                <a href="{{ route('leaves.index') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('leaves.index') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="clipboard-check" class="w-5 h-5 text-orange-400"></i>
                    <span x-show="sidebarOpen" class="flex-1">Persetujuan Cuti</span>
                    @php $count = \App\Models\Leave::where('status', 'pending')->count(); @endphp
                    @if($count > 0)
                    <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">{{ $count }}</span>
                    @endif
                </a>

                <a href="{{ route('hr.announcements.index') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('hr.announcements.*') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="megaphone" class="w-5 h-5 text-pink-400"></i>
                    <span x-show="sidebarOpen">Pengumuman</span>
                </a>
                @endif

                {{-- MENU SAYA (KARYAWAN) --}}
                <div class="pt-4 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest px-4" x-show="sidebarOpen">Menu Saya</div>

                <a href="{{ route('karyawan.slip') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('karyawan.slip') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="file-text" class="w-5 h-5 text-yellow-400"></i>
                    <span x-show="sidebarOpen">Slip Gaji</span>
                </a>

                <a href="{{ route('cuti.ajukan') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('cuti.ajukan') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-purple-400"></i>
                    <span x-show="sidebarOpen">Ajukan Cuti</span>
                </a>

                {{-- LAYANAN UMUM --}}
                <div class="pt-4 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest px-4" x-show="sidebarOpen">Layanan</div>

                <a href="{{ route('events.index') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('events.*') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="calendar-range" class="w-5 h-5 text-teal-400"></i>
                    <span x-show="sidebarOpen">Kalender Event</span>
                </a>

                <a href="{{ route('documents.index') }}" class="flex items-center gap-4 p-3 {{ request()->routeIs('documents.*') ? 'bg-[#0090e7] text-white' : 'hover:bg-gray-800' }} rounded-xl transition-all font-medium">
                    <i data-lucide="book-open" class="w-5 h-5 text-indigo-400"></i>
                    <span x-show="sidebarOpen">Dokumen SOP</span>
                </a>
            </nav>

            {{-- LOGOUT --}}
            <div class="p-4 border-t border-gray-700 bg-[#191c24]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-4 p-3 text-red-500 font-bold w-full hover:bg-gray-800 rounded-xl transition-all outline-none">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span x-show="sidebarOpen">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'">
            {{-- Header --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-40">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg outline-none transition-colors">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p id="live-clock" class="text-sm font-bold text-gray-800">00:00:00</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ now()->format('d F Y') }}</p>
                    </div>
                    <div class="h-10 w-10 bg-[#0090e7] rounded-full flex items-center justify-center text-white font-bold shadow-md uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Main Body --}}
            <main class="p-6">
                {{-- Breadcrumb Sederhana --}}
                <div class="mb-4 flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span>Menu</span>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <span class="text-gray-800">{{ request()->segment(1) ?? 'Dashboard' }}</span>
                </div>

                {{-- Konten Utama --}}
                @if(isset($slot))
                {{ $slot }}
                @else
                @yield('content')
                @endif
            </main>
        </div>
    </div>

    <script>
        // Inisialisasi Ikon Lucide
        lucide.createIcons();

        // Jam Digital Header
        setInterval(() => {
            const now = new Date();
            const clock = document.getElementById('live-clock');
            if (clock) {
                clock.innerText = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
        }, 1000);
    </script>
</body>

</html>