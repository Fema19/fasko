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

{{-- ===== FILTER TANGGAL ===== --}}
<div class="bg-white p-4 rounded-lg border mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex flex-col">
            <label class="text-xs text-slate-600 font-semibold mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}"
                class="border rounded px-3 py-2 text-sm focus:ring-slate-200">
        </div>
        <div class="flex items-end gap-2">
            <button class="bg-slate-900 text-white px-3 py-2 rounded text-xs">
                Filter
            </button>
            <a href="{{ route('guru.bookings.requests') }}"
               class="px-3 py-2 border text-xs rounded text-slate-700 hover:bg-slate-50">Reset</a>
        </div>
    </form>
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

@endsection
