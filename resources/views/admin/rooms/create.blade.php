@extends('layouts.admin')
@section('page_title', 'Tambah Ruangan')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-8">Tambah Ruangan</h1>

<div class="bg-white shadow rounded-lg p-6 w-full max-w-xl">

    <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf

        {{-- Nama Ruangan --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2 text-gray-700">Nama Ruangan</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                   placeholder="Masukkan nama ruangan..." required>
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kode Opsional --}}
        <div class="mb-6">
            <label class="block font-semibold mb-2 text-gray-700">Kode Ruangan (Opsional)</label>
            <input type="text" name="code" value="{{ old('code') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Ex: LAB01 / RAGUD01">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">
                Simpan
            </button>
            <a href="{{ route('admin.rooms.index') }}"
                class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 font-medium">
                Batal
            </a>
        </div>
    </form>

</div>

@endsection
