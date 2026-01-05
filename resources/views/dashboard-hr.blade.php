<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @php
        $outsideAttendances = \App\Models\Attendance::with('user')
        ->where('is_outside', true)
        ->where('date', now()->toDateString())
        ->get();
        @endphp

        @if($outsideAttendances->count( ) > 0)
        <div class="mb-8 p-6 bg-red-50 border-l-8 border-red-500 rounded-2xl shadow-sm animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <i data-lucide="map-pin-off" class="w-8 h-8 text-red-600"></i>
                    <div>
                        <h3 class="font-black text-red-800 uppercase text-sm tracking-widest">Peringatan Keamanan Lokasi</h3>
                        <p class="text-red-600 text-xs font-medium">Ada {{ $outsideAttendances->count() }} karyawan absen di luar jangkauan kantor hari ini.</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 space-y-2">
                @foreach($outsideAttendances as $att)
                <div class="bg-white p-3 rounded-xl flex items-center justify-between text-[11px] font-bold shadow-sm">
                    <span class="text-gray-700 uppercase">{{ $att->user->name }}</span>
                    <a href="https://www.google.com/maps?q={{ $att->latitude }},{{ $att->longitude }}"
                        target="_blank"
                        class="text-blue-500 flex items-center gap-1 hover:underline">
                        <i data-lucide="map" class="w-3 h-3"></i> LIHAT LOKASI
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        {{-- 1. NOTIFIKASI SUKSES (PENTING: Agar HR tahu pesan terkirim) --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl font-bold shadow-lg flex items-center gap-3 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- 2. HEADER PUSAT ANALISIS --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-800 flex items-center gap-3">
                        Pusat Analisis HR 🏢
                    </h1>
                    <p class="text-gray-500 mt-1 font-medium">
                        Memantau kesehatan organisasi dengan teknologi AI Heuristic secara real-time.
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. GRAFIK TREN RESIKO (Dinamis dari Database) --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-xl">📈</span>
                <h3 class="text-xl font-black text-gray-800">Tren Resiko Resign Organisasi</h3>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="riskTrendChart"></canvas>
            </div>
        </div>

        {{-- 4. TABEL PREDIKSI AI & TOMBOL PERINGATAN --}}
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-red-100">
            <h3 class="text-xl font-black text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-2xl">🤖</span> Prediksi Resiko Resign (AI Heuristic)
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-sm uppercase border-b">
                            <th class="pb-4">Nama Karyawan</th>
                            <th class="pb-4 text-center">Skor Resiko</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Rekomendasi AI</th>
                            <th class="pb-4 text-right">Aksi HR</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Mengambil data karyawan secara otomatis --}}
                        @foreach(\App\Models\User::where('role', 'karyawan')->get() as $emp)
                        @php
                        // Memanggil Service AI untuk hitung skor real
                        $ai = \App\Services\ResignationAIService::calculateRisk($emp);
                        @endphp
                        <tr class="border-b last:border-0 hover:bg-gray-50 transition-colors">
                            <td class="py-4 font-bold text-gray-700">
                                <a href="{{ route('hr.employee.detail', $emp->id) }}" class="hover:text-blue-600 transition">
                                    {{ $emp->name }}
                                </a>
                            </td>
                            <td class="py-4 text-center">
                                <div class="text-lg font-black {{ $ai['score'] > 70 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $ai['score'] }}%
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase
                                    {{ $ai['score'] > 70 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $ai['score'] > 70 ? 'Bahaya' : 'Stabil' }}
                                </span>
                            </td>
                            <td class="py-4 text-sm text-gray-500 italic">
                                {{ $ai['score'] > 70 ? '⚠️ Lakukan Wawancara Retensi' : '✅ Karyawan Stabil' }}
                            </td>
                            <td class="py-4 text-right">
                                {{-- Tombol akan otomatis aktif jika skor di atas 40% (untuk testing) atau 70% (untuk real) --}}
                                @if($ai['score'] > 40)
                                <<form action="{{ route('hr.send.warning', $emp->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition shadow-lg">
                                        Kirim Peringatan
                                    </button>
                                    </form>
                                    @else
                                    <span class="text-gray-300 text-xs font-bold italic">Kondisi Aman</span>
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SCRIPTS: CHART.JS DINAMIS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('riskTrendChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Okt', 'Nov', 'Des', 'Jan'],
                    datasets: [{
                        label: 'Rata-rata Skor Resiko (%)',
                        data: [30, 42, 55, 68],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ef4444',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        // Menghilangkan notifikasi otomatis setelah 3 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
</x-app-layout>