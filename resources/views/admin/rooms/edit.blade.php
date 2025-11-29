@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Ruangan</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-semibold">Nama</label>
                <input name="name" value="{{ old('name', $room->name) }}" class="w-full border px-3 py-2 rounded">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
