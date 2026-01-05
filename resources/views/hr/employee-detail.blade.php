<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        
        {{-- Header Profil --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-800">{{ $user->name }}</h1>
                <p class="text-gray-500 font-medium">Email: {{ $user->email }} | Bergabung: {{ $user->created_at->format('M Y') }}</p>
            </div>
            <a href="{{ route('hr.dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition">Kembali</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Kartu Statistik --}}
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-orange-100">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Total Terlambat</p>
                    <h2 class="text-4xl font-black text-orange-500">{{ $avgLate }} <span class="text-sm text-gray-400">Kali</span></h2>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-blue-100">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Cuti Diambil</p>
                    <h2 class="text-4xl font-black text-blue-500">{{ $totalCuti }} <span class="text-sm text-gray-400">Hari</span></h2>
                </div>
            </div>

            {{-- Grafik Perkembangan Resiko (Opsi A) --}}
            <div class="md:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6">Tren Resiko Individu (6 Bulan Terakhir)</h3>
                <canvas id="individualRiskChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('individualRiskChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($riskHistory->pluck('period')) !!},
                datasets: [{
                    label: 'Skor Resiko %',
                    data: {!! json_encode($riskHistory->pluck('score')) !!},
                    borderColor: '#3b82f6',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)'
                }]
            },
            options: {
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });
    </script>
</x-app-layout>