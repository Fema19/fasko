@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-5">Edit Booking</h1>

    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="bg-white shadow p-5 rounded">
        @csrf
        @method('PUT')

        <label>Start Time</label>
        <input type="datetime-local" name="start_time" value="{{ $booking->start_time }}" class="w-full border rounded p-2 mb-3">

        <label>End Time</label>
        <input type="datetime-local" name="end_time" value="{{ $booking->end_time }}" class="w-full border rounded p-2 mb-3">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
