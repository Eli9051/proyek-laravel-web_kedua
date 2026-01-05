<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold uppercase tracking-widest text-gray-800 mb-8">Pusat Dokumen & SOP</h2>

        @if(Auth::user()->role === 'hr')
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Judul Dokumen</label>
                    <input type="text" name="title" class="w-full rounded-xl border-gray-200 text-sm" required placeholder="Contoh: Peraturan Lembur 2026">
                </div>
                <div class="flex-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">File PDF</label>
                    <input type="file" name="file" class="w-full rounded-xl border-gray-200 text-sm" required>
                </div>
                <button type="submit" class="bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold uppercase text-xs hover:bg-indigo-600 transition shadow-md">
                    Unggah Dokumen
                </button>
            </form>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documents as $doc)
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all group">
                <div class="flex items-start justify-between">
                    <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">PDF Document</span>
                </div>
                <h3 class="mt-4 font-bold text-gray-800 uppercase text-sm leading-tight">{{ $doc->title }}</h3>
                <div class="mt-6 flex gap-2">
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="flex-1 text-center py-2 bg-gray-100 text-gray-600 rounded-xl text-[10px] font-bold uppercase hover:bg-gray-200 transition">
                        Lihat
                    </a>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" download class="flex-1 text-center py-2 bg-indigo-500 text-white rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 transition shadow-sm">
                        Download
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 text-gray-400 italic">
                Belum ada dokumen perusahaan yang tersedia.
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>