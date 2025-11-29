@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Dashboard Guru</h1>
        <nav class="text-sm text-gray-500">Home / Guru / Dashboard</nav>
    </div>

    @if($room)
        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="font-semibold">Ruangan Anda: {{ $room->name }}</h2>
            <p class="text-sm text-gray-600">{{ $room->description }}</p>
            <p class="mt-2">Jumlah fasilitas: {{ $room->facilities->count() }}</p>
            <a href="{{ route('guru.facilities.index') }}" class="inline-block mt-3 px-3 py-2 bg-blue-600 text-white rounded">Kelola Fasilitas</a>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">Anda belum memiliki ruangan yang ditetapkan.</div>
    @endif
</div>
@endsection
