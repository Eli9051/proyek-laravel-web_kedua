<x-app-layout>
	<div class="max-w-7xl mx-auto py-10 px-4">
		<div class="flex justify-between items-center mb-8">
			<h2 class="text-2xl font-bold uppercase tracking-widest text-gray-800">Kalender Event Perusahaan</h2>
			<div class="bg-white px-4 py-2 rounded-xl shadow-sm border text-xs font-bold text-gray-500 uppercase">
				{{ now()->format('F Y') }}
			</div>
		</div>

		@if(Auth::user()->role === 'hr')
		<div class="bg-red p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
			<form action="{{ route('events.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
				@csrf
				<input type="text" name="title" placeholder="Nama Acara (Rapat, Libur, dll)"
					class="rounded-xl border-gray-200 text-sm focus:ring-teal-500 focus:border-teal-500" required>

				<input type="date" name="event_date"
					class="rounded-xl border-gray-200 text-sm focus:ring-teal-500 focus:border-teal-500" required>

				<button type="submit" class="w-full py-3 bg-teal-500 text-red rounded-xl font-bold uppercase text-xs hover:bg-teal-600 transition shadow-md flex items-center justify-center gap-2">
					<i data-lucide="plus-circle" class="w-4 h-4"></i>
					Tambah Jadwal
				</button>
			</form>
		</div>
		@endif

		<div class="space-y-4">
			@forelse($events as $event)
			<div class="bg-white p-5 rounded-2xl border border-gray-100 flex items-center gap-6 shadow-sm hover:shadow-md transition-shadow">
				<div class="flex flex-col items-center justify-center bg-teal-50 text-teal-600 w-16 h-16 rounded-2xl border border-teal-100">
					<span class="text-xs font-black uppercase">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
					<span class="text-xl font-black">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
				</div>
				<div class="flex-1">
					<h4 class="font-bold text-gray-800 text-lg uppercase tracking-tight">{{ $event->title }}</h4>
					<p class="text-gray-400 text-xs font-medium uppercase tracking-widest flex items-center gap-2">
						<i data-lucide="map-pin" class="w-3 h-3"></i> {{ $event->location ?? 'Kantor Pusat' }}
					</p>
				</div>
				<div class="hidden md:block">
					<span class="px-4 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-widest">
						Upcoming
					</span>
				</div>
			</div>
			@empty
			<div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 text-gray-400 italic">
				Belum ada jadwal event untuk bulan ini.
			</div>
			@endforelse
		</div>
	</div>
</x-app-layout>