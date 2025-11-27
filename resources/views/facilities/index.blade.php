@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold">Facilities</h1>
        <a href="{{ route('facilities.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
            Add Facility
        </a>
    </div>

    <div class="bg-white rounded shadow p-4">
        @forelse($facilities as $facility)
            <div class="border-b py-3">
                <a href="{{ route('facilities.show', $facility->id) }}" class="font-semibold text-blue-700">
                    {{ $facility->name }}
                </a>
                <p class="text-gray-600 text-sm">{{ $facility->category }}</p>
            </div>
        @empty
            <p class="text-gray-600">No facilities available.</p>
        @endforelse
    </div>
</div>
@endsection
