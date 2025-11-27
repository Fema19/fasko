@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
	<div class="flex justify-between items-center mb-5">
		<h1 class="text-2xl font-bold">Kategori</h1>
		<a href="{{ route('categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
			Tambah Kategori
		</a>
	</div>

	@if(session('success'))
		<div class="p-3 mb-4 bg-green-500 text-white rounded">{{ session('success') }}</div>
	@endif

	<div class="bg-white rounded shadow p-4">
		<table class="w-full bg-white">
			<thead class="bg-gray-100">
				<tr>
					<th class="p-3 text-left">#</th>
					<th class="p-3 text-left">Nama</th>
					<th class="p-3 text-left">Jumlah Fasilitas</th>
					<th class="p-3 text-left">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse($categories as $category)
					<tr class="border-b">
						<td class="p-3">{{ $loop->iteration }}</td>
						<td class="p-3">{{ $category->name }}</td>
						<td class="p-3">{{ $category->facilities->count() }}</td>
						<td class="p-3 flex gap-2">

							<a href="{{ route('categories.edit', $category->id) }}"
							   class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>

							<form action="{{ route('categories.destroy', $category->id) }}" method="POST">
								@csrf
								@method('DELETE')
								<button onclick="return confirm('Hapus kategori ini?')"
									class="bg-red-600 text-white px-3 py-1 rounded text-sm">
									Hapus
								</button>
							</form>

						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="text-center p-4 text-gray-600">Belum ada kategori</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@endsection

