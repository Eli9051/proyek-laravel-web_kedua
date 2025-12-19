<x-app-layout>
    <div class="ml-64 p-10 min-h-screen bg-gray-50/50"> 
        
        <header class="mb-10 flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight leading-none">
                    {{ Auth::user()->role == 'hr' ? 'HR Strategic Panel' : 'Pusat Kerja Karyawan' }}
                </h2>
                <p class="text-gray-500 mt-3 text-lg font-medium">Selamat bekerja kembali, {{ Auth::user()->name }}! ✨</p>
            </div>
            
            <div class="px-5 py-2.5 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ Auth::user()->role == 'hr' ? 'bg-blue-400' : 'bg-green-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ Auth::user()->role == 'hr' ? 'bg-blue-500' : 'bg-green-500' }}"></span>
                </div>
                <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">{{ Auth::user()->role }} ACCOUNT</span>
            </div>
        </header>

        <div class="space-y-10 animate-fade-in">
            @if(Auth::user()->role == 'hr')
                @include('dashboard-hr-content')

                <div class="mt-10">
                    @include('dashboard-hr-employees')
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    @include('karyawan.dashboard')
                </div>
            @endif
        </div>

    </div>
</x-app-layout>