<x-app-layout>
    <div class="ml-64 p-10 min-h-screen bg-gray-50/50">
        <header class="mb-10">
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Persetujuan Cuti</h2>
            <p class="text-gray-500 mt-2 font-medium">Ada {{ $leaves->count() }} permintaan cuti yang menunggu keputusanmu. </p>
        </header>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-[11px] font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Karyawan</th>
                        <th class="px-6 py-4 text-left">Alasan</th>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($leaves as $leave) {{-- <--- Kita mengenalkan $leave di sini --}}
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-5 font-bold text-gray-700 text-sm">{{ $leave->user->name }}</td>
                        <td class="px-6 py-5 text-sm text-gray-500 italic">"{{ $leave->reason }}"</td>
                        <td class="px-6 py-5 text-xs text-gray-400 font-mono">
                            {{ $leave->start_date }} - {{ $leave->end_date }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center gap-2">

                                {{-- Form untuk Tombol Setujui --}}
                                <form action="{{ route('leaves.action', $leave) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="approved">
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white text-xs font-bold rounded-xl hover:bg-green-600 transition">
                                        Setujui
                                    </button>
                                </form>

                                {{-- Form untuk Tombol Tolak --}}
                                <form action="{{ route('leaves.action', $leave) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="rejected">
                                    <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 text-xs font-bold rounded-xl hover:bg-red-200 transition">
                                        Tolak
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                            Belum ada pengajuan cuti. ✨
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>