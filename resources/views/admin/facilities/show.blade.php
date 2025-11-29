@extends('layouts.app')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold mb-2">{{ $facility->name }}</h1>
    <div class="bg-white p-4 rounded shadow">
        <p><strong>Kategori:</strong> {{ $facility->category->name ?? '-' }}</p>
        <p><strong>Ruangan:</strong> {{ $facility->room->name ?? '-' }}</p>
        <p><strong>Kondisi:</strong> {{ $facility->condition }}</p>
        <p class="mt-2">{{ $facility->description }}</p>
    </div>
</div>
@endsection
