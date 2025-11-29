@extends('layouts.siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
    <div class="flex items-start justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Laporan Kerusakan</h1>
        <a href="{{ route('siswa.reports.create') }}" class="px-3 py-2 bg-slate-900 text-white rounded-md text-xs hover:bg-slate-800">+ Buat Laporan</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-3 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-3">
        @forelse($reports as $r)
            @php
                $badge = match($r->status ?? 'pending') {
                    'in_progress' => 'bg-yellow-100 text-yellow-700',
                    'fixed' => 'bg-green-100 text-green-700',
                    default => 'bg-gray-200 text-gray-700'
                };
            @endphp
            <div class="bg-white border rounded-lg p-4 space-y-2">
                <p class="font-semibold text-slate-900">{{ $r->facility->name ?? '-' }}</p>
                <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">{{ $r->status ?? 'pending' }}</span>
                <p class="text-sm text-slate-700">{{ \Illuminate\Support\Str::limit($r->description, 120) }}</p>
                <div class="text-xs">
                    @if($r->photo)
                        <a href="{{ asset('storage/'.$r->photo) }}" target="_blank" class="text-blue-600 underline">Lihat foto</a>
                    @else
                        <span class="text-slate-500">Tidak ada foto</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border rounded-lg p-4 text-center text-slate-500 text-sm">Belum ada laporan.</div>
        @endforelse
    </div>
</div>
@endsection
