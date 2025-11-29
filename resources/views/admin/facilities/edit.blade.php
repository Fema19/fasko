@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Fasilitas</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold">Nama</label>
                <input name="name" value="{{ old('name', $facility->name) }}" class="w-full border px-3 py-2 rounded">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Kategori</label>
                <select name="category_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id', $facility->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Ruangan</label>
                <select name="room_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ old('room_id', $facility->room_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
                @error('room_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                <a href="{{ route('admin.facilities.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
