@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Fasilitas (Ruangan Saya)</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('guru.facilities.update', $facility) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold">Nama</label>
                <input name="name" value="{{ old('name', $facility->name) }}" class="w-full border px-3 py-2 rounded">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Kondisi</label>
                <select name="condition" class="w-full border px-3 py-2 rounded">
                    <option value="baik" {{ old('condition', $facility->condition) == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ old('condition', $facility->condition) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="perawatan" {{ old('condition', $facility->condition) == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                    <option value="hilang" {{ old('condition', $facility->condition) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
                @error('condition') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                <a href="{{ route('guru.facilities.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
