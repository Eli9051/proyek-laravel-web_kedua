<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="mb-10">
            <h2 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">Analisis Performa KPI</h2>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-2">Data Berdasarkan Absensi & Lembur Bulan Ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($performanceData as $data)
            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-500 group">
                <div class="flex items-center justify-between mb-8">
                    <div class="h-14 w-14 bg-blue-50 rounded-2xl flex items-center justify-center font-black text-[#0090e7] text-xl italic shadow-inner">
                        {{ substr($data['name'], 0, 1) }}
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-black text-gray-300 uppercase tracking-widest">Skor Akhir</span>
                        <span class="text-3xl font-black text-gray-800 italic">{{ number_format($data['final'], 1) }}</span>
                    </div>
                </div>

                <h4 class="text-lg font-black text-gray-800 uppercase mb-6 tracking-tight">{{ $data['name'] }}</h4>
                
                <div class="space-y-6">
                    {{-- Bar Kedisiplinan --}}
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2">
                            <span class="text-gray-400 tracking-widest">Kedisiplinan</span>
                            <span class="text-[#0090e7]">{{ number_format($data['discipline'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-50 h-3 rounded-full p-1 shadow-inner">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-full rounded-full transition-all duration-1000 shadow-lg shadow-blue-100" style="width: {{ $data['discipline'] }}%"></div>
                        </div>
                    </div>

                    {{-- Bar Produktivitas --}}
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase mb-2">
                            <span class="text-gray-400 tracking-widest">Produktivitas (Lembur)</span>
                            <span class="text-emerald-500">{{ number_format($data['productivity'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-50 h-3 rounded-full p-1 shadow-inner">
                            <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-full rounded-full transition-all duration-1000 shadow-lg shadow-emerald-100" style="width: {{ $data['productivity'] }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-dashed border-gray-100 flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full {{ $data['final'] >= 80 ? 'bg-emerald-500 animate-pulse' : 'bg-orange-500 animate-bounce' }}"></div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $data['final'] >= 80 ? 'text-emerald-500' : 'text-orange-500' }}">
                        {{ $data['final'] >= 80 ? 'Excellent Performance' : 'Need Improvement' }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-gray-400 italic font-bold">Belum ada data karyawan untuk dianalisis.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>