@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Buat Laporan Kerusakan</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('siswa.report.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold">Fasilitas</label>
                <select name="facility_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih --</option>
                    @foreach($facilities as $f)
                        <option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
                @error('facility_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Deskripsi</label>
                <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Foto (opsional)</label>
                <input type="file" name="photo" class="w-full">
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Kirim</button>
                <a href="{{ route('siswa.report.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
