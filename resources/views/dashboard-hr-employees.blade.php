<tbody class="divide-y divide-gray-50 text-sm">
    @foreach($employees as $emp) <tr class="hover:bg-blue-50/30 transition-colors">
        <td class="px-6 py-4 font-bold text-gray-700">
            {{ $emp->name }}
            <p class="text-[10px] text-gray-400 font-mono font-normal">NIK: {{ $emp->nik ?? 'Belum Diatur' }}</p>
        </td>
        <td class="px-6 py-4 text-gray-500">{{ $emp->position ?? 'Staff' }}</td>
        <td class="px-6 py-4 text-center">
            <span class="px-3 py-1 rounded-full text-[10px] font-bold 
                {{ $emp->resign_risk_score > 70 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $emp->resign_risk_score }}% ({{ $emp->resign_risk_score > 70 ? 'Bahaya' : 'Aman' }})
            </span>
        </td>
        <td class="px-6 py-4 uppercase text-[10px] font-bold text-gray-400">
            {{ $emp->status }}
        </td>
        <td class="px-6 py-4 text-right space-x-2">
            <button class="text-blue-600 font-bold hover:underline">Ubah</button>
        </td>
    </tr>
    @endforeach
</tbody>