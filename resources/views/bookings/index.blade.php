@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold">Bookings</h1>
        <a href="{{ route('bookings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
            New Booking
        </a>
    </div>

    <div class="bg-white shadow p-4 rounded">
        @forelse($bookings as $booking)
            <div class="border-b py-3">
                <a class="font-semibold text-blue-700" href="{{ route('bookings.show', $booking->id) }}">
                    {{ $booking->facility->name }}
                </a>
                <p class="text-sm text-gray-600">
                    {{ $booking->start_time }} - {{ $booking->end_time }}
                </p>
            </div>
        @empty
            <p class="text-gray-600">No bookings yet.</p>
        @endforelse
    </div>
</div>
@endsection
