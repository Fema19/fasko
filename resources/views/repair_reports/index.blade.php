@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold">Repair Reports</h1>
        <a href="{{ route('repair-reports.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
            New Report
        </a>
    </div>

    <div class="bg-white shadow p-4 rounded">
        @forelse($reports as $report)
            <div class="border-b py-3">
                <a href="{{ route('repair-reports.show', $report->id) }}" class="font-semibold text-blue-700">
                    {{ $report->facility->name }}
                </a>
                <p class="text-sm text-gray-600">{{ $report->issue_description }}</p>
            </div>
        @empty
            <p class="text-gray-600">No reports filed yet.</p>
        @endforelse
    </div>
</div>
@endsection
