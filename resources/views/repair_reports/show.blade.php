@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto mt-10">

    <h1 class="text-2xl font-bold">{{ $report->facility->name }}</h1>

    <p class="mt-3"><strong>Issue:</strong></p>
    <p class="text-gray-600">{{ $report->issue_description }}</p>

    <p class="mt-3">
        <strong>Status:</strong> {{ $report->status }}
    </p>

    <a href="{{ route('repair-reports.edit', $report->id) }}"
        class="mt-5 inline-block px-4 py-2 bg-yellow-600 text-white rounded">
        Edit
    </a>

</div>

@endsection
