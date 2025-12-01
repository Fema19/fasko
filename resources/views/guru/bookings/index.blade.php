@extends('layouts.guru')
@section('page_title','Daftar Booking')

@section('content')
<div class="card card-padding mb-4 flex items-start justify-between gap-3">
    <div>
        <h2 class="text-xl font-semibold text-slate-900">Daftar Booking</h2>
        <p class="text-sm muted">Pantau status peminjaman yang Anda ajukan.</p>
    </div>
    <a href="{{ route('guru.bookings.create') }}"
       class="btn-dark">+ Booking</a>
</div>

@if(session('success'))
    <div class="card card-padding mb-4 border-green-200 bg-green-50/80 text-green-800 text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="card card-padding mb-4 border-red-200 bg-red-50/80 text-red-800 text-sm">
        {{ session('error') }}
    </div>
@endif
@if(isset($messages) && $messages->count())
    <div class="card card-padding mb-4 border-amber-200 bg-amber-50/80 text-amber-900 text-sm">
        <div class="flex items-center justify-between gap-2 mb-2">
            <span class="font-semibold text-xs uppercase tracking-wide">Pesan penolakan</span>
            <span class="text-[11px] text-amber-700">Terbaru</span>
        </div>
        <ul class="space-y-1">
            @foreach($messages as $msg)
                <li class="flex items-start justify-between gap-3">
                    <span class="text-sm">{{ $msg->message }}</span>
                    <span class="text-[11px] text-amber-700 whitespace-nowrap">{{ $msg->created_at->format('d M Y H:i') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card overflow-hidden">
    <div class="table-shell">
        <table class="text-sm">
            <thead>
                <tr>
                    <th>Fasilitas</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr class="hover:bg-slate-50">
                    <td class="font-medium text-slate-900">{{ $b->facility->name }}</td>
                    <td class="text-slate-700">{{ date('d M Y H:i', strtotime($b->start_time)) }}</td>
                    <td class="text-slate-700">{{ date('d M Y H:i', strtotime($b->end_time)) }}</td>
                    <td>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="badge-soft">
                                {{ ucfirst($b->status) }}
                            </span>
                            @if($b->status === 'pending')
                            <form action="{{ route('guru.bookings.destroy', $b) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-[11px] px-2 py-1 rounded border text-red-700 hover:bg-red-50">Batalkan</button>
                            </form>
                            @endif
                            @if($b->status === 'approved' && !$b->checked_in)
                            <form action="{{ route('guru.bookings.checkin', $b) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="text-[11px] px-2 py-1 rounded border text-slate-700 hover:bg-slate-50">Check-in</button>
                            </form>
                            @elseif($b->checked_in)
                                <span class="text-[11px] px-2 py-1 rounded border border-green-600 text-green-700 bg-green-50">IN</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-500 text-sm">Belum ada booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
