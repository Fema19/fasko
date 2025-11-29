@extends('layouts.admin')
@section('page_title', 'Edit Ruangan')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-8">Edit Ruangan</h1>

<div class="bg-white shadow rounded-lg p-6 max-w-xl">

    <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
        @csrf
        @method('PUT')

        {{-- Nama Ruangan --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2 text-gray-700">Nama Ruangan</label>
            <input type="text" name="name"
                   value="{{ old('name', $room->name) }}"
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                   placeholder="Masukkan nama ruangan..." required>

            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kode Ruangan (opsional) --}}
        <div class="mb-6">
            <label class="block font-semibold mb-2 text-gray-700">Kode Ruangan</label>
            <input type="text" name="code"
                   value="{{ old('code', $room->code) }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Ex: LAB01 / KLS12">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                Update
            </button>

            <a href="{{ route('admin.rooms.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded font-medium">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection
