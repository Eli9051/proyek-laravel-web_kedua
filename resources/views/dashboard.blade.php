<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 space-y-6 px-4">

        {{-- 1. BAGIAN NOTIFIKASI & PERINGATAN KERAS DARI HR --}}
        <div id="main-notification-area" class="space-y-4">
            {{-- Notifikasi Sukses/Gagal Umum --}}
            @if(session('success'))
            <div class="alert-auto-hide p-4 bg-green-500 text-white rounded-2xl font-bold shadow-lg flex items-center gap-3 transition-all duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert-auto-hide p-4 bg-red-500 text-white rounded-2xl font-bold shadow-lg flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                {{ session('error') }}
            </div>
            @endif

            {{-- BLOK PERINGATAN AI/HR (DARI KODE BARU ANDA) --}}
            @if(auth()->user()->has_warning)
            <div class="bg-red-600 border-4 border-yellow-400 p-8 rounded-3xl shadow-2xl mb-10 animate-pulse transition-all">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 text-white">
                    <div class="flex items-center gap-6">
                        <div class="bg-white/20 p-4 rounded-2xl">
                            <span class="text-5xl">📢</span>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black uppercase italic tracking-tighter">Peringatan Penting!</h2>
                            <p class="text-xl font-bold opacity-95">{{ auth()->user()->warning_message }}</p>
                        </div>
                    </div>
                    <form action="{{ route('warning.read') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-white text-red-600 px-10 py-4 rounded-2xl font-black text-lg shadow-xl hover:bg-black hover:text-white transition">
                            OK, SAYA MENGERTI
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- 2. HEADER DENGAN STATUS --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center relative overflow-hidden">
            <div class="flex items-center gap-4 z-10">
                <div class="relative">
                    <h1 class="text-2xl font-black text-gray-800">
                        Halo, {{ Auth::user()->name }}! 👋
                        @if(auth()->user()->has_warning)
                        <span class="text-red-500 animate-bounce inline-block">🔴</span>
                        @endif
                    </h1>
                </div>
            </div>
            <div class="text-center md:text-right z-10 mt-4 md:mt-0">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Waktu Presensi</p>
                <p id="dashboard-clock" class="text-3xl font-black text-[#0090e7] tabular-nums">00:00</p>
            </div>
        </div>

        {{-- 3. TOMBOL ABSENSI & LIVE DURATION --}}
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Durasi Kerja Live</h3>
                    <p id="live-clock-big" class="text-5xl font-black text-gray-800 tracking-tighter">00:00:00</p>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <form action="{{ route('absen.masuk') }}" method="POST">
                        @csrf
                        {{-- Menambahkan data lokasi dummy atau bisa diintegrasikan dengan geolocation script --}}
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="long" id="long">
                        <button type="submit" onclick="getLocation()" class="px-10 py-5 bg-green-500 text-white rounded-2xl font-black hover:bg-green-600 shadow-xl shadow-green-100 transition active:scale-95 uppercase tracking-widest">
                            MASUK
                        </button>
                    </form>
                    <form action="{{ route('absen.pulang') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-10 py-5 bg-orange-500 text-white rounded-2xl font-black hover:bg-orange-600 shadow-xl shadow-orange-100 transition active:scale-95 uppercase tracking-widest">
                            PULANG
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 4. TABEL RIWAYAT --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Riwayat Kehadiran 5 Hari Terakhir</h3>
                <span class="text-[10px] font-bold text-gray-400">Pembaruan Otomatis</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white text-[10px] font-black uppercase text-gray-400">
                        <tr>
                            <th class="py-5 px-8">Tanggal</th>
                            <th class="py-5 px-8">Check In</th>
                            <th class="py-5 px-8">Check Out</th>
                            <th class="py-5 px-8 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($attendances as $absen)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-5 px-8 font-bold text-gray-600">{{ \Carbon\Carbon::parse($absen->date)->format('d M Y') }}</td>
                            <td class="py-5 px-8 font-black text-[#0090e7] tabular-nums tracking-tighter">{{ $absen->check_in ?? '--:--' }}</td>
                            <td class="py-5 px-8 font-black text-red-500 tabular-nums tracking-tighter">{{ $absen->check_out ?? '--:--' }}</td>
                            <td class="py-5 px-8 text-center">
                                <span class="px-4 py-1.5 {{ $absen->check_out ? 'bg-green-100 text-green-700' : 'bg-[#0090e7]/10 text-[#0090e7]' }} rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $absen->check_out ? 'Selesai' : 'Bekerja' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400 font-bold">Belum ada data kehadiran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // 1. Live Clock Jam Utama
        setInterval(() => {
            const now = new Date();
            document.getElementById('dashboard-clock').innerText =
                now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0');
        }, 1000);

        // 2. Auto Hide Notification
        setTimeout(() => {
            document.querySelectorAll('.alert-auto-hide').forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);

        // 3. Script Geolocation (Penting untuk backend route absen.masuk)
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('long').value = position.coords.longitude;
                });
            }
        }

        // 4. Durasi Kerja Live (Timer)
        @php
        $absenHariIni = $attendances->where('date', now()->toDateString())->first();
        @endphp

        const checkInTime = "{{ $absenHariIni ? $absenHariIni->check_in : '' }}";
        const hasCheckedOut = "{{ ($absenHariIni && $absenHariIni->check_out) ? 'yes' : 'no' }}";

        if (checkInTime && hasCheckedOut === 'no') {
            setInterval(() => {
                const today = new Date().toDateString();
                const start = new Date(today + ' ' + checkInTime);
                const now = new Date();
                const diff = Math.abs(now - start);

                const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
                const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
                const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');

                const display = document.getElementById('live-clock-big');
                if (display) display.innerText = `${h}:${m}:${s}`;
            }, 1000);
        }
    </script>
</x-app-layout>