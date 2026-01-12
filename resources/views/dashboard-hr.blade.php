<<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> {{-- Diperkecil padding-nya untuk HP --}}

        {{-- 1. NOTIFIKASI SUKSES --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl font-bold shadow-lg flex items-center gap-3 animate-bounce text-sm sm:text-base">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- 2. HEADER PUSAT ANALISIS --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 mb-8">
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 flex items-center gap-3">
                Pusat Analisis HR
            </h1>
            <p class="text-gray-500 mt-1 font-medium text-xs sm:text-sm">
                Memantau kesehatan organisasi dengan teknologi AI Heuristic secara real-time.
            </p>
        </div>

        {{-- 3. GRAFIK TREN RESIKO --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
            <h3 class="text-lg sm:text-xl font-black text-gray-800 mb-6">Tren Resiko Resign Organisasi</h3>
            {{-- Kontainer grafik agar fleksibel di HP --}}
            <div class="relative w-full" style="height: 250px; sm:height: 300px;">
                <canvas id="riskTrendChart"></canvas>
            </div>
        </div>

        {{-- 4. TABEL PREDIKSI AI --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-red-100">
            <h3 class="text-lg sm:text-xl font-black text-gray-800 mb-6">
                Prediksi Resiko Resign (AI)
            </h3>

            {{-- SAKTI: overflow-x-auto agar tabel bisa digeser di HP --}}
            <div class="overflow-x-auto -mx-6 sm:mx-0">
                <div class="inline-block min-w-full align-middle px-6 sm:px-0">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="text-gray-400 text-[10px] sm:text-sm uppercase border-b">
                                <th class="pb-4 pr-4">Nama</th>
                                <th class="pb-4 text-center">Skor</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($employees as $emp)
                            @php $ai = \App\Services\ResignationAIService::calculateRisk($emp); @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-bold text-gray-700 text-sm whitespace-nowrap pr-4">
                                    {{ $emp->name }}
                                </td>
                                <td class="py-4 text-center">
                                    <span class="font-black {{ $ai['score'] > 70 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $ai['score'] }}%
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $ai['score'] > 70 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $ai['score'] > 70 ? 'Bahaya' : 'Stabil' }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    @if($ai['score'] > 40)
                                    <form action="{{ route('hr.send.warning', $emp->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-[10px] font-bold shadow-md">
                                            Kirim
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $employees->links() }}
            </div>
        </div>
    </div>


    {{-- SCRIPTS: CHART.JS DINAMIS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('riskTrendChart').getContext('2d');

            // KUNCINYA DI SINI: Harus rapat dalam satu baris!
            const labelsDariDatabase = {!!json_encode($chartLabels)!!};
            const dataDariDatabase = {!!json_encode($chartData)!!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsDariDatabase,
                    datasets: [{
                        label: 'Rata-rata Skor Resiko (%)',
                        data: ({!!json_encode($chartData)!!}).map(Number),
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