<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#1B254B]">Ajukan Cuti Baru</h1>
                @if(session('success'))
                <div class="p-4 mb-4 text-sm text-white bg-green-500 rounded-2xl font-bold shadow-lg">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="p-4 mb-4 text-sm text-white bg-red-500 rounded-2xl font-bold shadow-lg">
                    {{ session('error') }}
                </div>
                @endif
                <p class="text-[#A3AED0] font-medium text-sm">Silakan isi formulir di bawah untuk pengajuan cuti resmi.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white border border-gray-200 text-[#1B254B] rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1 space-y-6">
                <div class="bg-[#4318FF] p-6 rounded-[30px] text-white shadow-xl shadow-indigo-100">
                    <span class="text-xs font-bold opacity-70 uppercase tracking-widest">Sisa Jatah Cuti</span>
                    <h2 class="text-4xl font-black mt-2 italic">{{ Auth::user()->kuota_cuti }} <span class="text-sm font-medium not-italic opacity-80">Hari</span></h2>
                    {{-- Menampilkan tanggal terakhir di bulan saat ini secara otomatis --}}
                    <p class="text-[10px]">*Berlaku hingga {{ \Carbon\Carbon::now()->endOfMonth()->format('d M Y') }}</p>
                </div>

                <div class="bg-white p-6 rounded-[30px] border border-gray-100 shadow-sm">
                    <h4 class="font-bold text-[#1B254B] mb-4 text-sm">Ketentuan Cuti</h4>
                    <ul class="text-xs text-[#A3AED0] space-y-3 font-medium">
                        <li class="flex gap-2"><span>•</span> Pengajuan minimal 3 hari sebelum tanggal mulai.</li>
                        <li class="flex gap-2"><span>•</span> Lampirkan dokumen pendukung jika sakit/keperluan mendesak.</li>
                    </ul>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-100">
                    <form action="{{ route('cuti.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-[#A3AED0] uppercase tracking-widest ml-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="w-full p-4 bg-[#F4F7FE] border-none rounded-2xl text-sm font-bold text-[#1B254B] focus:ring-2 focus:ring-[#4318FF] transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-black text-[#A3AED0] uppercase tracking-widest ml-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="w-full p-4 bg-[#F4F7FE] border-none rounded-2xl text-sm font-bold text-[#1B254B] focus:ring-2 focus:ring-[#4318FF] transition-all">
                                @error('end_date') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black text-[#A3AED0] uppercase tracking-widest ml-1">Jenis Cuti</label>
                            <select name="type" class="w-full p-4 bg-[#F4F7FE] border-none rounded-2xl text-sm font-bold text-[#1B254B] focus:ring-2 focus:ring-[#4318FF] appearance-none transition-all">
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="sakit">Cuti Sakit</option>
                                <option value="penting">Izin Kepentingan Mendesak</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black text-[#A3AED0] uppercase tracking-widest ml-1">Alasan Cuti</label>
                            <textarea name="reason" rows="4" placeholder="Jelaskan alasan Anda secara singkat..." class="w-full p-4 bg-[#F4F7FE] border-none rounded-2xl text-sm font-bold text-[#1B254B] focus:ring-2 focus:ring-[#4318FF] transition-all"></textarea>
                            @error('reason') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 bg-[#4318FF] text-white font-black rounded-2xl shadow-lg shadow-indigo-100 hover:bg-[#3311CC] active:scale-[0.98] transition-all">
                            KIRIM PENGAJUAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>