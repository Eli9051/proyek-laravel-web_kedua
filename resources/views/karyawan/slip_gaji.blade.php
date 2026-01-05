<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-8 border-b-2 border-gray-100 pb-6">
            <div>
                <h2 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Slip Gaji</h2>
                <p class="text-gray-500 font-medium italic">Periode: {{ now()->format('F Y') }}</p>
            </div>
            <div class="text-right">
                <h3 class="font-bold text-gray-800 uppercase">{{ $user->name }}</h3>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-blue-50 rounded-xl text-blue-600">
                            <i data-lucide="banknote" class="w-6 h-6"></i>
                        </div>
                        <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Pendapatan</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Gaji Pokok Utama</p>
                    <p class="text-2xl font-black text-gray-800">Rp {{ number_format($user->basic_salary, 0, ',', '.') }}</p>
                </div>

                <div class="bg-red-50/50 p-6 rounded-3xl border border-red-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="p-2 bg-red-100 rounded-xl text-red-600">
                            <i data-lucide="trending-down" class="w-6 h-6"></i>
                        </div>
                        <span class="text-xs font-bold text-red-500 uppercase tracking-widest">Potongan Disiplin</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 font-medium">Keterlambatan ({{ $lateCount }}x)</span>
                            <span class="text-sm text-red-600 font-bold">- Rp {{ number_format($latePenalty, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 font-medium">Penalti Warning AI</span>
                            <span class="text-sm text-red-600 font-bold">- Rp {{ number_format($warningPenalty, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-red-200 pt-4 flex justify-between items-center font-black">
                            <span class="text-red-800 uppercase text-xs">Total Potongan</span>
                            <span class="text-red-700">Rp {{ number_format($latePenalty + $warningPenalty, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-3xl p-8 text-white flex flex-col justify-center relative overflow-hidden shadow-2xl">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
                
                <div class="relative">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mb-2">Total Gaji Diterima (Take Home Pay)</p>
                    <h4 class="text-4xl font-black tracking-tight mb-8">
                        Rp {{ number_format($netSalary, 0, ',', '.') }}
                    </h4>
                    
                    <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm">
                        <p class="text-[10px] text-slate-300 uppercase font-bold mb-1 tracking-widest">Status Pembayaran</p>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-emerald-400 font-black text-sm uppercase italic">Sudah Diproses HR</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="text-center text-gray-400 text-[10px] mt-12 font-medium italic">
            *Slip ini dihasilkan secara otomatis oleh sistem MyAbsensi AI. Hubungi HR jika terdapat ketidaksesuaian data.
        </p>
    </div>
</x-app-layout>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>