@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Profil --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="h-32 bg-gradient-to-r from-[#0090e7] to-[#00d25b]"></div>
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <div class="h-24 w-24 bg-[#0090e7] rounded-2xl flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-lg uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase tracking-widest">
                    {{ Auth::user()->status ?? 'Active' }}
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h1>
            <p class="text-gray-500 font-medium">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
    </div>

    {{-- Grid Informasi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Detail Pekerjaan --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="briefcase" class="w-4 h-4"></i> Informasi Pekerjaan
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Email Perusahaan</p>
                    <p class="text-gray-800 font-semibold">{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Gaji Pokok</p>
                    <p class="text-gray-800 font-bold text-lg">Rp {{ number_format(Auth::user()->basic_salary, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Kuota Cuti</p>
                    <p class="text-gray-800 font-semibold">{{ Auth::user()->kuota_cuti ?? 0 }} Hari</p>
                </div>
            </div>
        </div>

        {{-- Status Keamanan/AI --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4"></i> Status Keamanan AI
            </h3>
            <div class="p-4 rounded-2xl {{ Auth::user()->risk_score > 50 ? 'bg-red-50' : 'bg-blue-50' }} mb-4">
                <p class="text-[10px] font-bold {{ Auth::user()->risk_score > 50 ? 'text-red-500' : 'text-blue-500' }} uppercase">Skor Risiko Resign</p>
                <p class="text-2xl font-black {{ Auth::user()->risk_score > 50 ? 'text-red-600' : 'text-blue-600' }}">{{ Auth::user()->risk_score ?? 0 }}%</p>
            </div>
            <p class="text-xs text-gray-500 italic">
                *Skor ini dihitung secara otomatis oleh sistem berdasarkan riwayat kehadiran dan lokasi Anda.
            </p>
        </div>
    </div>
</div>
@endsection