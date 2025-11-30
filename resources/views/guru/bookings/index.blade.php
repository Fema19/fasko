@extends('layouts.guru')
@section('page_title','Daftar Booking')

@section('content')
<div class="flex items-start justify-between mb-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-900">Daftar Booking</h2>
        <p class="text-xs text-slate-500">Cek status peminjaman.</p>
    </div>
    <a href="{{ route('guru.bookings.create') }}"
       class="px-3 py-2 bg-slate-900 text-white text-xs rounded-md">+ Booking</a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 mb-4 rounded text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 mb-4 rounded text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white border rounded-lg overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 uppercase text-[11px] border-b">
            <tr>
                <th class="p-3 text-left">Fasilitas</th>
                <th class="p-3 text-left">Mulai</th>
                <th class="p-3 text-left">Selesai</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($bookings as $b)
            <tr class="hover:bg-slate-50">
                <td class="p-3 font-medium text-slate-900">{{ $b->facility->name }}</td>
                <td class="p-3 text-slate-700">{{ date('d M Y H:i', strtotime($b->start_time)) }}</td>
                <td class="p-3 text-slate-700">{{ date('d M Y H:i', strtotime($b->end_time)) }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2 py-1 text-[11px] rounded-full border text-slate-700">
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
@endsection
