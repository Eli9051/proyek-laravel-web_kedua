<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight uppercase">Payroll HR</h2>
                @if(session('success'))
                <div class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="flex items-center">
                        <div class="bg-emerald-500 p-2 rounded-full mr-4">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-emerald-800 font-extrabold text-sm uppercase tracking-wide">Berhasil Diproses</p>
                            <p class="text-emerald-600 text-xs font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                <p class="text-gray-500 text-sm mt-1">Kelola gaji bersih berdasarkan absensi dan kedisiplinan.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <i data-lucide="calendar" class="w-5 h-5 text-[#0090e7]"></i>
                <span class="font-bold text-gray-700 uppercase text-xs">{{ now()->format('F Y') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Karyawan</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Gaji Pokok</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status Warning</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($employees as $emp)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-bold text-gray-800 group-hover:text-[#0090e7] transition-colors">{{ $emp->name }}</div>
                                <div class="text-[10px] text-gray-400 font-medium">{{ $emp->email }}</div>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-gray-700">
                                Rp {{ number_format($emp->basic_salary, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($emp->has_warning)
                                <span class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-[10px] font-black uppercase shadow-sm border border-red-200">ADA PERINGATAN</span>
                                @else
                                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-lg text-[10px] font-black uppercase shadow-sm border border-green-200">BERSIH</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center">
                                <form action="{{ route('hr.payroll.process', $emp->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        Hitung Gaji
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>