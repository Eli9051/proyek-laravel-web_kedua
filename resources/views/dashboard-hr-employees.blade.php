<div class="overflow-x-auto">
    <table class="w-full border-collapse">
        <thead class="bg-gray-50 text-gray-600 uppercase text-[11px] font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4 text-left">Nama & NIK</th>
                <th class="px-6 py-4 text-left">Jabatan</th>
                <th class="px-6 py-4 text-center">Risiko Resign</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>

        <tbody class="bg-white">
            @foreach($employees as $emp)
            <tr class="border-b border-gray-50 hover:bg-blue-50/20 transition-all duration-200">
                <td class="px-6 py-5">
                    <div class="font-semibold text-black-800 text-sm">{{ $emp->name }}</div>
                    <div class="text-[15px] text-black-400 font-mono mt-0.5"> {{ $emp->nik ?? '2025' . $emp->id }}</div>
                </td>
                <td class="px-6 py-5">
                    <span class="text-sm text-black-500">{{ $emp->position ?? 'Staff' }}</span>
                </td>
                <td class="px-6 py-5 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                        {{ $emp->resign_risk_score > 70 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                        {{ $emp->resign_risk_score }}%
                    </span>
                </td>
                <td class="px-6 py-5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $emp->status == 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-50 text-gray-500' }}">
                        {{ $emp->status == 'active' ? 'AKTIF' : 'NON-AKTIF' }}
                    </span>
                </td>
                <td class="px-6 py-5 text-right">
                    <a href="#" class="text-blue-500 font-bold text-xs hover:text-blue-700 hover:underline">
                        Ubah
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>