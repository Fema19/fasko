@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10">
	<h1 class="text-2xl font-bold mb-5">Tambah Kategori</h1>

	<form action="{{ route('categories.store') }}" method="POST" class="bg-white shadow rounded p-5">
		@csrf

		@if($errors->any())
			<div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
				<ul class="list-disc pl-5">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<label class="block mb-2">Nama</label>
		<input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2 mb-4">

		<div class="flex gap-2">
			<button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
			<a href="{{ route('categories.index') }}" class="px-4 py-2 border rounded">Batal</a>
		</div>
	</form>
</div>
@endsection

