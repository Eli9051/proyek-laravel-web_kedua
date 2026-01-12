@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Daftar Pelanggaran Lokasi</h1>
            <p class="text-gray-500 text-sm font-medium">Memantau karyawan yang absen di luar jangkauan kantor.</p>
        </div>

        {{-- TOMBOL KONFIRMASI SEMUA --}}
        @php
            $adaPelanggaranBaru = $pelanggaran->where('hr_reviewed', false)->count() > 0;
        @endphp

        @if($adaPelanggaranBaru)
        <form action="{{ route('hr.attendance.confirmAll') }}" method="POST" onsubmit="return confirm('Konfirmasi semua pelanggaran hari ini?')">
            @csrf
            <button type="submit" class="flex items-center gap-2 bg-emerald-500 text-white px-6 py-3 rounded-2xl font-black text-xs hover:bg-black transition-all shadow-lg shadow-emerald-100 uppercase tracking-widest">
                <i data-lucide="check-check" class="w-4 h-4"></i>
                Konfirmasi Semua
            </button>
        </form>
        @endif
    </div>

    {{-- NOTIFIKASI SUKSES --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-500 text-white rounded-2xl font-bold text-sm shadow-md animate-bounce flex items-center gap-2">
        <i data-lucide="party-popper" class="w-5 h-5"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- TABEL --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Karyawan</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu & Tanggal</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Koordinat GPS</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pelanggaran as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        {{-- Nama Karyawan --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center font-bold uppercase text-xs">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-gray-800">{{ $item->user->name }}</span>
                            </div>
                        </td>
                        
                        {{-- Waktu --}}
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                            <div class="text-[10px] font-bold text-gray-400">{{ $item->check_in }}</div>
                        </td>

                        {{-- GPS --}}
                        <td class="px-8 py-5">
                            <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-[10px] font-black text-blue-500 hover:underline flex items-center gap-1">
                                <i data-lucide="map" class="w-3 h-3"></i> LIHAT DI MAPS
                            </a>
                            <div class="text-[9px] text-gray-400 tabular-nums">{{ $item->latitude }}, {{ $item->longitude }}</div>
                        </td>

                        {{-- Tombol Aksi --}}
                        <td class="px-8 py-5 text-center">
                            @if(!$item->hr_reviewed)
                            <form action="{{ route('hr.attendance.confirm', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white text-[10px] px-4 py-2 rounded-xl font-black hover:bg-black transition uppercase flex items-center gap-1 mx-auto shadow-md shadow-red-50/50">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus Notif
                                </button>
                            </form>
                            @else
                            <span class="text-[10px] font-black text-emerald-500 uppercase flex items-center justify-center gap-1 bg-emerald-50 py-1 px-3 rounded-full w-fit mx-auto">
                                <i data-lucide="shield-check" class="w-3 h-3"></i> Selesai
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-gray-400 font-bold italic">Tidak ada data pelanggaran ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION --}}
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $pelanggaran->links() }}
        </div>
    </div>
</div>
@endsection