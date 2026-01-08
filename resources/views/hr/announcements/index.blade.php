@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    {{-- Header Section: Mengikuti gaya Payroll --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight uppercase">Manajemen Pengumuman</h2>
            <p class="text-gray-500 text-sm mt-1 font-medium">Publikasikan informasi dan kebijakan terbaru kepada seluruh karyawan.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('hr.announcements.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-[#0090e7] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-600 shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                Tambah Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
        <div class="flex items-center">
            <div class="bg-emerald-500 p-2 rounded-full mr-4">
                <i data-lucide="check" class="w-4 h-4 text-white"></i>
            </div>
            <div>
                <p class="text-emerald-800 font-extrabold text-sm uppercase tracking-wide">Status Berhasil</p>
                <p class="text-emerald-600 text-xs font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Table Section: Mengikuti gaya Payroll --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Detail Informasi</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Prioritas</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Tanggal Terbit</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($announcements as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="font-bold text-gray-800 group-hover:text-[#0090e7] transition-colors uppercase tracking-tight">{{ $item->title }}</div>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1 font-medium">{{ $item->content }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($item->type === 'danger')
                                <span class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-tighter border border-red-200">PENTING & MENDESAK</span>
                            @elseif($item->type === 'warning')
                                <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-tighter border border-amber-200">PERINGATAN</span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-tighter border border-blue-200">INFORMASI</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-tight">{{ $item->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('hr.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400 italic text-sm">
                            <i data-lucide="megaphone-off" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                            Belum ada pengumuman yang diterbitkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $announcements->links() }}
    </div>
</div>
@endsection