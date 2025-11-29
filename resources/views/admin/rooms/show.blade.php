@extends('layouts.admin')
@section('page_title', 'Detail Ruangan')
@section('content')

<div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold">{{ $room->name }}</h1>
    <a href="{{ route('admin.rooms.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

<div class="bg-white p-5 rounded shadow mb-4">
    <h2 class="text-xl font-semibold">{{ $room->name }}</h2>
    <p class="text-gray-600 text-sm mt-1">{{ $room->description ?? 'Tidak ada deskripsi' }}</p>
</div>

<div class="bg-white p-5 rounded shadow">
    <h3 class="font-bold mb-3">Fasilitas</h3>

    @forelse ($room->facilities as $f)

        @php
            $badge = match($f->condition) {
                'baik'  => 'bg-green-100 text-green-700',
                'rusak' => 'bg-red-100 text-red-700',
                default => 'bg-yellow-100 text-yellow-700'
            };
        @endphp

        <div class="border-b py-2 flex justify-between items-center">
            <span>{{ $f->name }}</span>
            <span class="px-3 py-1 rounded text-xs font-semibold {{ $badge }}">
                {{ ucfirst($f->condition) }}
            </span>
        </div>

    @empty
        <p class="text-gray-500">Belum ada fasilitas</p>
    @endforelse
</div>

@endsection
