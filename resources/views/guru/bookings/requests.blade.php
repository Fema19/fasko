@extends('layouts.guru')
@section('page_title', 'Permintaan Booking Ruangan')

@section('content')

<div class="flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-slate-900">Permintaan Booking</h1>
        <p class="text-xs text-slate-500 mt-1">ACC atau tolak booking untuk ruangan Anda.</p>
    </div>
    <a href="{{ route('guru.dashboard') }}" class="text-xs text-slate-600 hover:text-slate-900">Dashboard</a>
</div>

{{-- ===== ALERT MSG ===== --}}
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-600 text-green-900 px-4 py-3 mb-3 text-sm rounded">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-600 text-red-900 px-4 py-3 mb-3 text-sm rounded">
    {{ session('error') }}
</div>
@endif


{{-- ===== TABLE REQUEST LIST ===== --}}
<div class="bg-white border rounded-lg overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-[11px] text-slate-600 uppercase tracking-wide border-b">
            <tr>
                <th class="px-4 py-3 text-left">Peminjam</th>
                <th class="px-4 py-3">Fasilitas</th>
                <th class="px-4 py-3">Mulai</th>
                <th class="px-4 py-3">Selesai</th>
                <th class="px-4 py-3">Keperluan</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($bookings as $b)
            <tr class="hover:bg-gray-50 transition">

                <td class="px-4 py-3 font-medium text-slate-900">{{ $b->user->name }}</td>

                <td class="px-4 py-3 text-slate-700">{{ $b->facility->name }}</td>

                <td class="px-4 py-3">{{ date('d M Y H:i', strtotime($b->start_time)) }}</td>
                <td class="px-4 py-3">{{ date('d M Y H:i', strtotime($b->end_time)) }}</td>

                <td class="px-4 py-3 text-slate-600">{{ $b->reason ?? '-' }}</td>

                <td class="px-4 py-3 flex justify-center gap-2">
                    <form action="{{ route('guru.bookings.approve', $b) }}" method="POST">
                        @csrf @method('PUT')
                        <button class="px-3 py-1.5 rounded text-xs bg-slate-900 text-white hover:bg-slate-800">
                            ACC
                        </button>
                    </form>

                    <form action="{{ route('guru.bookings.reject', $b) }}" method="POST">
                        @csrf @method('PUT')
                        <button class="px-3 py-1.5 rounded text-xs border text-slate-700 hover:bg-slate-50">
                            Tolak
                        </button>
                    </form>

                </td>
            </tr>
            @empty

            <tr>
                <td colspan="6" class="text-center py-6 text-slate-500 text-sm">
                    Tidak ada booking menunggu persetujuan untuk ruangan Anda.
                </td>
            </tr>

            @endforelse
        </tbody>
    </table>
</div>

{{-- Approved/Active untuk Check-in/Check-out --}}
<div class="bg-white border rounded-lg overflow-hidden mt-5">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-900">Perlu Check-in / Check-out</h2>
        <span class="text-xs text-slate-500">Status approved/active</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-[11px] text-slate-600 uppercase tracking-wide border-b">
            <tr>
                <th class="px-4 py-3 text-left">Peminjam</th>
                <th class="px-4 py-3">Fasilitas</th>
                <th class="px-4 py-3">Mulai</th>
                <th class="px-4 py-3">Selesai</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($checklist as $c)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-slate-900">{{ $c->user->name }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $c->facility->name }}</td>
                <td class="px-4 py-3">{{ date('d M Y H:i', strtotime($c->start_time)) }}</td>
                <td class="px-4 py-3">{{ date('d M Y H:i', strtotime($c->end_time)) }}</td>
                <td class="px-4 py-3 capitalize">
                    {{ $c->status }}
                    @if($c->checked_in)
                        <span class="ml-2 px-2 py-1 text-[10px] rounded border border-green-600 text-green-700 bg-green-50">IN</span>
                    @endif
                </td>
                <td class="px-4 py-3 flex justify-center gap-2">
                    @if($c->status === 'active')
                        <form action="{{ route('guru.bookings.complete', $c) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="px-3 py-1.5 rounded text-xs border text-slate-700 hover:bg-slate-50">Check-out</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-slate-500 text-sm">
                    Tidak ada booking approved/active.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
