<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tighter">Edit Data Karyawan</h1>
            <p class="text-gray-500 font-medium">Ubah informasi profil untuk {{ $employee->name }}.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('hr.employees.update', $employee->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT') {{-- PENTING: Untuk rute update resource --}}

                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $employee->name }}" required
                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0090e7] font-bold text-gray-700">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ $employee->email }}" required
                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0090e7] font-bold text-gray-700">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="********"
                        class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0090e7] font-bold text-gray-700">
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-1 bg-[#0090e7] text-white py-5 rounded-2xl font-black shadow-xl hover:bg-blue-600 transition uppercase tracking-widest">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('hr.employees.index') }}" class="py-5 px-8 bg-gray-100 text-gray-400 rounded-2xl font-black hover:bg-gray-200 transition">BATAL</a>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                        Gaji Pokok (Rp)
                    </label>
                    <input type="number"
                        name="basic_salary"
                        value="{{ old('basic_salary', $employee->basic_salary) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0090e7] transition-all"
                        placeholder="Masukkan nominal gaji pokok..."
                        required>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>