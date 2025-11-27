@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10">

    <h1 class="text-2xl font-bold mb-5">Edit Repair Report</h1>

    <form action="{{ route('repair-reports.update', $report->id) }}" method="POST" class="bg-white shadow p-5 rounded">
        @csrf
        @method('PUT')

        <label>Issue Description</label>
        <textarea name="issue_description" class="w-full border rounded p-2 mb-4">{{ $report->issue_description }}</textarea>

        <label>Status</label>
        <select name="status" class="w-full border rounded p-2 mb-4">
            <option {{ $report->status == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
            <option {{ $report->status == 'in_progress' ? 'selected' : '' }} value="in_progress">In Progress</option>
            <option {{ $report->status == 'completed' ? 'selected' : '' }} value="completed">Completed</option>
        </select>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
    </form>

</div>
@endsection
