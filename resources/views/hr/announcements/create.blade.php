<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4">
        {{-- Header Section --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight uppercase">Buat Pengumuman</h2>
                <p class="text-gray-500 text-sm mt-1">Publikasikan informasi penting ke seluruh workstation karyawan.</p>
            </div>
            <a href="{{ route('hr.announcements.index') }}" class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Batal
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <form action="{{ route('hr.announcements.store') }}" method="POST" class="p-12 space-y-10">
                @csrf
                
                <div class="grid grid-cols-1 gap-10">
                    {{-- Input Judul --}}
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Judul Informasi</label>
                        <input type="text" name="title" required 
                            placeholder="Contoh: Pemeliharaan Server MyAbsensi"
                            class="w-full px-8 py-5 bg-gray-50 border-none rounded-2xl text-base font-bold text-gray-800 focus:ring-2 focus:ring-[#0090e7] transition-all placeholder:text-gray-300">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- Select Tipe --}}
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Kategori Prioritas</label>
                            <select name="type" class="w-full px-8 py-5 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-[#0090e7] transition-all appearance-none">
                                <option value="info">INFORMASI STANDAR</option>
                                <option value="warning">PERINGATAN MODERAT</option>
                                <option value="danger">PENTING & MENDESAK</option>
                            </select>
                        </div>

                        {{-- Helper Box Style (Sesuai Payroll) --}}
                        <div class="flex items-center p-6 bg-blue-50/50 rounded-2xl border border-blue-100/50">
                            <i data-lucide="info" class="w-8 h-8 text-[#0090e7] mr-4"></i>
                            <p class="text-[10px] text-blue-700 font-bold leading-relaxed uppercase tracking-tight">
                                Tipe menentukan label warna yang akan muncul di dashboard utama karyawan.
                            </p>
                        </div>
                    </div>

                    {{-- Textarea Konten --}}
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Detail Pesan</label>
                        <textarea name="content" rows="6" required 
                            placeholder="Tuliskan detail pengumuman Anda di sini..."
                            class="w-full px-8 py-5 bg-gray-50 border-none rounded-2xl text-base font-bold text-gray-800 focus:ring-2 focus:ring-[#0090e7] transition-all placeholder:text-gray-300 resize-none"></textarea>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-6 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="flex items-center gap-3 px-12 py-5 bg-[#191c24] text-white rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] hover:bg-[#0090e7] hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 active:scale-95">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Terbitkan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    // Memastikan icon ter-render di dalam component
    lucide.createIcons();
</script>