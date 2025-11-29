@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Tambah Fasilitas (Ruangan Saya)</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('guru.facilities.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold">Nama</label>
                <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Kondisi</label>
                <select name="condition" class="w-full border px-3 py-2 rounded">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="perawatan">Perawatan</option>
                    <option value="hilang">Hilang</option>
                </select>
                @error('condition') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                <a href="{{ route('guru.facilities.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
