@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Buat Peminjaman (Guru)</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('guru.booking.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold">Fasilitas</label>
                <select name="facility_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih --</option>
                    @foreach($facilities as $f)
                        <option value="{{ $f->id }}" {{ old('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }} ({{ $f->room->name ?? '-' }})</option>
                    @endforeach
                </select>
                @error('facility_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Mulai</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="w-full border px-3 py-2 rounded">
                @error('start_time') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Selesai</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="w-full border px-3 py-2 rounded">
                @error('end_time') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Alasan</label>
                <textarea name="reason" class="w-full border px-3 py-2 rounded">{{ old('reason') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Jumlah Pengguna</label>
                <input type="number" name="capacity_used" value="{{ old('capacity_used',1) }}" class="w-full border px-3 py-2 rounded" min="1">
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Buat</button>
                <a href="{{ route('guru.booking.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
