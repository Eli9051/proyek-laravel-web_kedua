@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Kehadiran</h1>
        <p class="text-gray-500">Pantau aktivitas absen dan lokasi Anda selama 30 hari terakhir.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Jam Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Lokasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $item->check_in ?? '--:--' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="{{ $item->is_outside ? 'map-pin-off' : 'map-pin' }}" class="w-4 h-4 {{ $item->is_outside ? 'text-red-500' : 'text-emerald-500' }}"></i>
                                <span class="text-xs {{ $item->is_outside ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                    {{ $item->is_outside ? 'Di Luar Radius' : 'Dalam Kantor' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = [
                                    'Hadir' => 'bg-emerald-100 text-emerald-700',
                                    'Terlambat' => 'bg-amber-100 text-amber-700',
                                    'Luar Kantor' => 'bg-red-100 text-red-700',
                                ][$item->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada riwayat absensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $riwayat->links() }}
        </div>
    </div>
</div>
@endsection