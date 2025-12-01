@extends('layouts.admin')
@section('page_title', 'Permintaan Booking')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Permintaan Booking</h1>
        <p class="text-sm text-gray-500">Setujui atau tolak pengajuan peminjaman fasilitas.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
</div>

{{-- Alert --}}
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
@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-600 text-red-900 px-4 py-3 mb-3 text-sm rounded">
    {{ $errors->first() }}
</div>
@endif

<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-[11px] text-gray-600 uppercase tracking-wide border-b">
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
                <td class="px-4 py-3 font-medium text-gray-900">{{ $b->user->name }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $b->facility->name }}</td>
                <td class="px-4 py-3">{{ date('d M Y H:i', strtotime($b->start_time)) }}</td>
                <td class="px-4 py-3">{{ date('d M Y H:i', strtotime($b->end_time)) }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $b->reason ?? '-' }}</td>
                <td class="px-4 py-3 flex justify-center gap-2">
                    <form action="{{ route('admin.bookings.approve', $b) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="px-3 py-1.5 rounded text-xs bg-gray-900 text-white hover:bg-gray-800">
                            ACC
                        </button>
                    </form>
                    <form action="{{ route('admin.bookings.reject', $b) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="message" required placeholder="Alasan penolakan" class="w-40 px-2 py-1 border rounded text-xs focus:ring-2 focus:ring-red-200 focus:border-red-400">
                        <button class="px-3 py-1.5 rounded text-xs border text-gray-700 hover:bg-gray-50">
                            Tolak
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500 text-sm">
                    Tidak ada permintaan booking menunggu persetujuan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Approved/Active untuk Check-in/Check-out --}}
<div class="bg-white border rounded-xl mt-5">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-800">Perlu Check-in / Check-out</h2>
        <span class="text-xs text-gray-500">Status approved/active</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-[11px] text-gray-600 uppercase tracking-wide border-b">
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
                <td class="px-4 py-3 font-medium text-gray-900">{{ $c->user->name }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $c->facility->name }}</td>
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
                        <form action="{{ route('admin.bookings.complete', $c) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="px-3 py-1.5 rounded text-xs border text-gray-700 hover:bg-gray-50">Check-out</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500 text-sm">
                    Tidak ada booking approved/active.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
