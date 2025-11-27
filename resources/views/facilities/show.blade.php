@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-3">{{ $facility->name }}</h1>

    <p class="text-gray-700 mb-1"><strong>Category:</strong> {{ $facility->category }}</p>
    <p class="text-gray-700"><strong>Description:</strong></p>
    <p class="text-gray-600 mb-5">{{ $facility->description }}</p>

    <a href="{{ route('facilities.edit', $facility->id) }}"
        class="px-4 py-2 bg-yellow-600 text-white rounded">Edit</a>

</div>
@endsection
