@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold mb-4">Detail Pesan</h2>

<div class="bg-white p-6 rounded shadow">
    <h3 class="text-xl font-semibold mb-2">{{ $message->subject }}</h3>
    <p class="text-gray-700 mb-2"><strong>Dari:</strong> {{ $message->name }} ({{ $message->email }})</p>
    <p class="text-gray-600 mb-4"><strong>Dikirim:</strong> {{ $message->created_at->format('d M Y H:i') }}</p>

    <hr class="my-4">

    <p class="text-gray-800 leading-relaxed whitespace-pre-line">
        {{ $message->content }}
    </p>
</div>

<a href="{{ route('messages.index') }}" class="mt-4 inline-block text-gray-700">Kembali</a>
@endsection
