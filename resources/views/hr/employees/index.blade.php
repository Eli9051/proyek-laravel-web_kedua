<x-app-layout>
	<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
		@if(session('success'))
		<div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-xl flex items-center gap-3">
			<i data-lucide="check-circle" class="w-5 h-5"></i>
			<span class="font-bold">{{ session('success') }}</span>
		</div>
		@endif
		<div class="flex justify-between items-center mb-8">
			<h2 class="text-3xl font-bold text-gray-800 tracking-tight uppercase">Daftar Karyawan</h2>
			<a href="{{ route('hr.employees.create') }}" class="bg-[#0090e7] hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2 text-sm">
				<i data-lucide="plus" class="w-5 h-5"></i>
				Tambah Karyawan Baru
			</a>
		</div>

		<div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
			<div class="overflow-x-auto">
				<table class="w-full text-left border-collapse">
					<thead>
						<tr class="bg-gray-50 border-b border-gray-100">
							<th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Nama Karyawan</th>
							<th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Email</th>
							<th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Gaji Pokok</th>
							<th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
							<th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-50">
						@foreach($employees as $employee)
						<tr class="hover:bg-gray-50/50 transition-colors group">
							<td class="px-8 py-5">
								<div class="font-bold text-gray-800 text-lg group-hover:text-[#0090e7] transition-colors">{{ $employee->name }}</div>
							</td>
							<td class="px-8 py-5 text-gray-500 font-medium">
								{{ $employee->email }}
							</td>
							<td class="px-8 py-5 text-right font-black text-gray-700">
								Rp {{ number_format($employee->basic_salary, 0, ',', '.') }}
							</td>
							<td class="px-8 py-5 text-center">
								<span class="px-4 py-1.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
									{{ $employee->status ?? 'ACTIVE' }}
								</span>
							</td>
							<td class="px-8 py-5">
								<div class="flex justify-center gap-3">
									<a href="{{ route('hr.employees.edit', $employee->id) }}" class="p-2.5 text-blue-500 hover:bg-blue-50 rounded-xl transition-all shadow-sm border border-gray-100">
										<i data-lucide="edit-3" class="w-5 h-5"></i>
									</a>
									<form action="{{ route('hr.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Hapus data karyawan ini?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="p-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-all shadow-sm border border-gray-100">
											<i data-lucide="trash-2" class="w-5 h-5"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="mt-6 px-4">
					{{ $employees->links() }}
				</div>
			</div>
		</div>
	</div>
</x-app-layout>