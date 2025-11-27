@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">

    <h1 class="text-2xl font-bold">{{ $booking->facility->name }}</h1>

    <p class="mt-3"><strong>Start:</strong> {{ $booking->start_time }}</p>
    <p><strong>End:</strong> {{ $booking->end_time }}</p>

    <a href="{{ route('bookings.edit', $booking->id) }}" class="mt-5 inline-block px-4 py-2 bg-yellow-600 text-white rounded">
        Edit
    </a>
</div>
@endsection
