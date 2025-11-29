@extends('layouts.guru')
@section('page_title','Ruangan Saya')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Ruangan Saya</h1>
            <p class="text-xs text-slate-500">Detail ruangan yang Anda kelola.</p>
        </div>
    </div>

    @if($room)
        <div class="bg-white border rounded-lg p-4 mb-4">
            <h2 class="font-semibold text-slate-900">{{ $room->name }}</h2>
            <p class="text-sm text-slate-600">{{ $room->description }}</p>
        </div>

        <div class="bg-white border rounded-lg p-4">
            <h3 class="font-semibold text-slate-900 mb-2">Fasilitas</h3>
            <ul class="space-y-2">
                @forelse($room->facilities as $f)
                    <li class="text-sm text-slate-700">{{ $f->name }} - {{ ucfirst($f->condition) }}</li>
                @empty
                    <li class="text-sm text-slate-500">Belum ada fasilitas.</li>
                @endforelse
            </ul>
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 p-4 rounded text-amber-800 text-sm">Anda belum memiliki ruangan.</div>
    @endif
</div>
@endsection
