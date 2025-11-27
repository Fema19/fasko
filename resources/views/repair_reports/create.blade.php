@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10">

    <h1 class="text-2xl font-bold mb-5">New Repair Report</h1>

    <form action="{{ route('repair-reports.store') }}" method="POST" class="bg-white shadow p-5 rounded">
        @csrf

        <label>Facility</label>
        <select name="facility_id" class="w-full border rounded p-2 mb-3">
            @foreach($facilities as $facility)
                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
            @endforeach
        </select>

        <label>Issue Description</label>
        <textarea name="issue_description" class="w-full border rounded p-2 mb-3"></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
    </form>

</div>
@endsection
