@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold mb-4">Edit Pesan</h2>

<form action="{{ route('messages.update', $message->id) }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block font-medium">Nama</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $message->name) }}">
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label class="block font-medium">Email</label>
        <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email', $message->email) }}">
        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label class="block font-medium">Subjek</label>
        <input type="text" name="subject" class="w-full border rounded p-2" value="{{ old('subject', $message->subject) }}">
        @error('subject') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="mb-4">
        <label class="block font-medium">Isi Pesan</label>
        <textarea name="content" rows="4" class="w-full border rounded p-2">{{ old('content', $message->content) }}</textarea>
        @error('content') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <button class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
    <a href="{{ route('messages.index') }}" class="ml-2 text-gray-700">Kembali</a>
</form>
@endsection
