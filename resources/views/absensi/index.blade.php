<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        ⏰ Absensi Online
                    </h2>
                    <p class="text-gray-500 mt-1">Halo {{ auth()->user()->name }}, pastikan lokasi dan waktu kamu sudah benar sebelum absen ya!</p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center">
                    <p class="text-blue-600 font-semibold mb-2">Status Hari Ini:</p>
                    <span class="px-4 py-2 bg-white text-blue-700 rounded-full font-bold shadow-sm inline-block">
                        Belum Absen
                    </span>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>