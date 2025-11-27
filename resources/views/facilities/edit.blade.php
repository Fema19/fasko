@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10">

    <h1 class="text-2xl font-bold mb-5">Edit Facility</h1>

    <form action="{{ route('facilities.update', $facility->id) }}" method="POST" class="bg-white shadow rounded p-5">
        @csrf
        @method('PUT')

        <label class="block mb-2">Name</label>
        <input type="text" name="name" value="{{ $facility->name }}" class="w-full border rounded p-2 mb-4">

        <label class="block mb-2">Category</label>
        <input type="text" name="category" value="{{ $facility->category }}" class="w-full border rounded p-2 mb-4">

        <label class="block mb-2">Description</label>
        <textarea name="description" class="w-full border rounded p-2 mb-4">{{ $facility->description }}</textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
