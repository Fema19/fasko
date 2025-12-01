@extends('layouts.guru')
@section('page_title', 'Permintaan Booking Ruangan')

@section('content')

<div class="card card-padding flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-slate-900">Permintaan Booking</h1>
        <p class="text-sm muted mt-1">ACC atau tolak booking untuk ruangan Anda.</p>
    </div>
    <a href="{{ route('guru.dashboard') }}" class="btn-ghost">Dashboard</a>
</div>

{{-- ===== ALERT MSG ===== --}}
@if(session('success'))
<div class="card card-padding border-green-200 bg-green-50/80 text-green-900 mb-3 text-sm">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="card card-padding border-red-200 bg-red-50/80 text-red-900 mb-3 text-sm">
    {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="card card-padding border-red-200 bg-red-50/80 text-red-900 mb-3 text-sm">
    {{ $errors->first() }}
</div>
@endif


{{-- ===== TABLE REQUEST LIST ===== --}}
<div class="card overflow-hidden">

    <div class="table-shell">
        <table class="text-sm">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Fasilitas</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Keperluan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-50 transition">

                    <td class="font-medium text-slate-900">{{ $b->user->name }}</td>

                    <td class="text-slate-700">{{ $b->facility->name }}</td>

                    <td>{{ date('d M Y H:i', strtotime($b->start_time)) }}</td>
                    <td>{{ date('d M Y H:i', strtotime($b->end_time)) }}</td>

                    <td class="text-slate-600">{{ $b->reason ?? '-' }}</td>

                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">
                            <form action="{{ route('guru.bookings.approve', $b) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="btn-dark text-xs">ACC</button>
                            </form>

                            <form action="{{ route('guru.bookings.reject', $b) }}" method="POST" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="message" required placeholder="Alasan penolakan" class="input-base w-44 text-xs">
                                <button class="btn-ghost text-xs">Tolak</button>
                            </form>
                        </div>
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
</div>

{{-- Approved/Active untuk Check-in/Check-out --}}
<div class="card overflow-hidden mt-5">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-900">Perlu Check-in / Check-out</h2>
        <span class="text-xs text-slate-500">Status approved/active</span>
    </div>
    <div class="table-shell">
        <table class="text-sm">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Fasilitas</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($checklist as $c)
                <tr class="hover:bg-gray-50 transition">
                    <td class="font-medium text-slate-900">{{ $c->user->name }}</td>
                    <td class="text-slate-700">{{ $c->facility->name }}</td>
                    <td>{{ date('d M Y H:i', strtotime($c->start_time)) }}</td>
                    <td>{{ date('d M Y H:i', strtotime($c->end_time)) }}</td>
                    <td class="capitalize">
                        {{ $c->status }}
                        @if($c->checked_in)
                            <span class="ml-2 px-2 py-1 text-[10px] rounded border border-green-600 text-green-700 bg-green-50">IN</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">
                            @if($c->status === 'active')
                                <form action="{{ route('guru.bookings.complete', $c) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="btn-ghost text-xs">Check-out</button>
                                </form>
                            @endif
                        </div>
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
</div>

@endsection
