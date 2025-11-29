@extends('layouts.app')

@section('content')
<div class="max-w-3xl">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Ruangan Saya</h1>
        <nav class="text-sm text-gray-500">Guru / Ruangan Saya</nav>
    </div>

    @if($room)
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="font-semibold">{{ $room->name }}</h2>
            <p class="text-sm text-gray-600">{{ $room->description }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Fasilitas</h3>
            <ul class="list-disc pl-6">
                @forelse($room->facilities as $f)
                    <li>{{ $f->name }} — {{ $f->condition }}</li>
                @empty
                    <li class="text-gray-600">Belum ada fasilitas</li>
                @endforelse
            </ul>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">Anda belum memiliki ruangan.</div>
    @endif
</div>
@endsection
