<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        {{-- Header --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-gray-800 uppercase italic">Aset & Inventaris</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Daftar Inventaris Perusahaan</p>
            </div>

            @if(auth()->user()->role === 'hr')
            <button onclick="document.getElementById('modal-aset').classList.remove('hidden')" class="bg-[#0090e7] text-white px-6 py-3 rounded-2xl text-xs font-black shadow-lg hover:bg-blue-600 transition uppercase tracking-widest">
                + Tambah Aset
            </button>
            @endif
        </div>

        {{-- Tabel Aset --}}
        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 text-[10px] font-black uppercase text-gray-400">
                    <tr>
                        @if(auth()->user()->role === 'hr') <th class="py-5 px-8">Karyawan</th> @endif
                        <th class="py-5 px-8">Nama Barang</th>
                        <th class="py-5 px-8">Kode Aset</th>
                        <th class="py-5 px-8 text-center">Tgl Pinjam</th>
                        <th class="py-5 px-8 text-center">Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($inventories as $inv)
                    <tr class="hover:bg-gray-50 transition-colors {{ $inv->return_date ? 'opacity-50' : '' }}">
                        @if(auth()->user()->role === 'hr')
                        <td class="py-5 px-8 font-bold text-gray-800">{{ $inv->user->name }}</td>
                        @endif
                        <td class="py-5 px-8">
                            <span class="font-black text-[#0090e7]">{{ $inv->item_name }}</span>
                            @if($inv->return_date)
                            <span class="block text-[9px] text-red-500 font-bold uppercase italic mt-1">Dikembalikan: {{ \Carbon\Carbon::parse($inv->return_date)->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="py-5 px-8 font-mono text-xs text-gray-500 uppercase">{{ $inv->item_code }}</td>
                        <td class="py-5 px-8 text-center">{{ \Carbon\Carbon::parse($inv->loan_date)->format('d/m/Y') }}</td>
                        <td class="py-5 px-8 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
        {{ $inv->condition == 'good' ? 'bg-green-100 text-green-700' : '' }}
        {{ $inv->condition == 'damaged' ? 'bg-orange-100 text-orange-700' : '' }}
        {{ $inv->condition == 'lost' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $inv->condition }}
                            </span>
                        </td>

                        @if(auth()->user()->role === 'hr')
                        <td class="py-5 px-8 text-right">
                            @if(!$inv->return_date)
                            <form action="{{ route('inventories.update', $inv) }}" method="POST" onsubmit="return confirm('Yakin barang sudah dikembalikan?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[10px] font-black bg-gray-800 text-white px-3 py-1.5 rounded-lg hover:bg-black transition uppercase tracking-tighter">
                                    Tandai Kembali
                                </button>
                            </form>
                            @else
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 inline-block"></i>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal khusus HR untuk Input Aset --}}
    @if(auth()->user()->role === 'hr')
    <div id="modal-aset" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[40px] p-8 w-full max-w-md shadow-2xl">
            <h3 class="text-xl font-black text-gray-800 uppercase mb-6 tracking-tighter">Pinjamkan Aset</h3>
            <form action="{{ route('inventories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Karyawan</label>
                    <select name="user_id" class="w-full mt-1 p-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Barang</label>
                    <input type="text" name="item_name" required placeholder="Contoh: MacBook Pro 2023" class="w-full mt-1 p-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode Aset</label>
                    <input type="text" name="item_code" required placeholder="LPT-001" class="w-full mt-1 p-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Pinjam</label>
                    <input type="date" name="loan_date" required class="w-full mt-1 p-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700">
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="button" onclick="this.closest('#modal-aset').classList.add('hidden')" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-[#0090e7] text-white rounded-2xl font-black shadow-xl shadow-blue-100 uppercase tracking-widest">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-app-layout>