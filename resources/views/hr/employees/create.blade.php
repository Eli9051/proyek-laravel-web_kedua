<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="mb-8">
            <a href="{{ route('hr.employees.index') }}" class="text-[#0090e7] font-bold text-sm flex items-center gap-2 mb-2">
                ← Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tighter">Tambah Karyawan Baru</h1>
            <p class="text-gray-500 font-medium">Daftarkan akun karyawan agar mereka bisa melakukan presensi.</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Gaji Pokok (Rp)</label>
            <input type="number" name="basic_salary"
                value="{{ old('basic_salary', $employee->basic_salary ?? '') }}"
                class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Contoh: 5000000" required>
        </div>
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('hr.employees.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Input Nama --}}
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0090e7] font-bold text-gray-700">
                </div>

                {{-- Input Email --}}
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" required placeholder="karyawan@perusahaan.com"
                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0090e7] font-bold text-gray-700">
                </div>

                {{-- Input Password --}}
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Password Akun</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0090e7] font-bold text-gray-700">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#0090e7] text-white py-5 rounded-2xl font-black shadow-xl shadow-blue-100 hover:bg-blue-600 transition uppercase tracking-widest">
                        Daftarkan Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>