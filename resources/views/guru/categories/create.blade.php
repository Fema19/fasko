@extends('layouts.guru')
@section('page_title', 'Tambah Kategori')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Tambah Kategori</h1>
            <p class="text-xs text-slate-500">Guru dengan ruangan dapat menambah kategori untuk fasilitasnya.</p>
        </div>
        <a href="{{ route('guru.categories.index') }}" class="text-xs text-slate-600 hover:text-slate-900">Kembali</a>
    </div>

    <div class="bg-white border rounded-lg p-5">
        <form method="POST" action="{{ route('guru.categories.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kategori</label>
                <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe</label>
                <select name="type" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200">
                    <option value="unit" {{ old('type')==='unit'?'selected':'' }}>Unit (barang/perangkat)</option>
                    <option value="capacity" {{ old('type')==='capacity'?'selected':'' }}>Capacity (ruangan/lapangan)</option>
                </select>
                @error('type')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-slate-900 text-white rounded text-sm hover:bg-slate-800">Simpan</button>
                <a href="{{ route('guru.categories.index') }}" class="px-4 py-2 border rounded text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
