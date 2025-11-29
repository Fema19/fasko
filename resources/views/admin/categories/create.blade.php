@extends('layouts.admin')
@section('page_title', 'Tambah Kategori')
@section('content')

<div class="max-w-2xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Tambah Kategori</h1>

    <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-700 font-semibold mb-1">Nama Kategori</label>
                <input 
                    name="name" 
                    value="{{ old('name') }}" 
                    class="w-full border px-3 py-2 rounded @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>

                <a href="{{ route('admin.categories.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>

@endsection
