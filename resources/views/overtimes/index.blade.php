<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight uppercase">Manajemen Lembur</h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                    {{ Auth::user()->role === 'hr' ? 'Panel Persetujuan HR' : 'Riwayat Pengajuan Saya' }}
                </p>
            </div>

            @if(Auth::user()->role !== 'hr')
            <button onclick="toggleModal('modal-lembur', true)"
                class="inline-flex items-center justify-center gap-2 bg-[#0090e7] text-white px-6 py-3 rounded-2xl text-sm font-black shadow-xl shadow-blue-100 hover:bg-blue-600 hover:-translate-y-0.5 transition-all active:scale-95 uppercase tracking-widest">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Ajukan Lembur
            </button>
            @endif
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <i data-lucide="check-circle" class="text-emerald-500 w-5 h-5"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl animate-in fade-in slide-in-from-top-4">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="alert-circle" class="text-red-500 w-5 h-5"></i>
                <p class="text-sm font-black text-red-800 uppercase tracking-tight">Terjadi Kesalahan</p>
            </div>
            <ul class="list-disc ml-8 text-xs font-bold text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-50">
                            @if(Auth::user()->role === 'hr')
                            <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Karyawan</th>
                            @endif
                            <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Tanggal</th>
                            <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Durasi/Jam</th>
                            <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Keterangan</th>
                            <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Status</th>
                            @if(Auth::user()->role === 'hr')
                            <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($overtimes as $ot)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            {{-- Kolom Nama Karyawan (Hanya tampil di sisi HR) --}}
                            @if(auth()->user()->role === 'hr')
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center font-bold text-[#0090e7] text-xs">
                                        {{ substr($ot->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-black text-gray-800 text-sm italic">{{ $ot->user->name }}</span>
                                </div>
                            </td>
                            @endif

                            {{-- Kolom Tanggal & Jam (Sama untuk semua) --}}
                            <td class="py-5 px-8 font-bold text-gray-600 text-sm">
                                {{ \Carbon\Carbon::parse($ot->date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-5 px-8 font-mono text-xs font-black text-[#0090e7]">
                                {{ $ot->start_time }} - {{ $ot->end_time }}
                            </td>

                            {{-- Kolom Status --}}
                            <td class="py-5 px-8 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                {{ $ot->status == 'approved' ? 'bg-emerald-100 text-emerald-700' : ($ot->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $ot->status }}
                                </span>
                            </td>

                            {{-- Kolom Aksi Khusus HR --}}
                            @if(auth()->user()->role === 'hr')
                            <td class="py-5 px-8 text-right">
                                @if($ot->status === 'pending')
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('overtimes.update', $ot) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="bg-emerald-500 text-white p-2 rounded-lg hover:bg-emerald-600 shadow-lg shadow-emerald-100 transition">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('overtimes.update', $ot) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 shadow-lg shadow-red-100 transition">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter italic">No Action Needed</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        {{-- Empty State --}}
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($overtimes, 'links'))
            <div class="p-6 bg-gray-50/50 border-t border-gray-50">
                {{ $overtimes->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- MODAL FORM LEMBUR --}}
    <div id="modal-lembur"
        class="hidden fixed inset-0 z-[60] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
        onclick="if(event.target === this) toggleModal('modal-lembur', false)">

        <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            <div class="relative p-8">
                {{-- Decorative element --}}
                <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
                    <i data-lucide="clock-4" class="w-24 h-24"></i>
                </div>

                <div class="flex justify-between items-center mb-8 relative">
                    <div>
                        <h3 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Pengajuan Lembur</h3>
                        <p class="text-[10px] font-bold text-[#0090e7] uppercase tracking-[0.2em] mt-1">Formulir Resmi Karyawan</p>
                    </div>
                    <button onclick="toggleModal('modal-lembur', false)" class="text-gray-400 hover:text-red-500 hover:rotate-90 transition-all duration-300">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form action="{{ route('overtimes.store') }}" method="POST" class="space-y-6 relative">
                    @csrf
                    <div class="group">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-[#0090e7] transition-colors">Tanggal Lembur</label>
                        <div class="relative">
                            <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-[#0090e7]"></i>
                            <input type="date" name="date" required
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-[#0090e7] transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-[#0090e7]">Jam Mulai</label>
                            <input type="time" name="start_time" required
                                class="w-full px-4 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-[#0090e7] transition-all">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-[#0090e7]">Jam Selesai</label>
                            <input type="time" name="end_time" required
                                class="w-full px-4 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-[#0090e7] transition-all">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-[#0090e7]">Alasan / Pekerjaan</label>
                        <textarea name="reason" rows="3" required placeholder="Apa yang Anda kerjakan?"
                            class="w-full px-4 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-2 focus:ring-[#0090e7] transition-all resize-none"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full py-5 bg-[#0090e7] text-white rounded-[24px] font-black shadow-xl shadow-blue-100 hover:bg-blue-600 hover:-translate-y-1 transition-all active:scale-95 uppercase tracking-[0.2em] text-sm">
                            Kirim Pengajuan
                        </button>
                        <p class="text-center text-[9px] font-bold text-gray-400 uppercase mt-4 tracking-tighter italic">
                            *Setiap pengajuan akan ditinjau oleh pihak HRD
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function toggleModal(modalId, show) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById('modal-content');

            if (show) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.mb-6.p-4');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.transition = 'all 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</x-app-layout>