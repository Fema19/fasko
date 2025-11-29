@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Peminjaman (Guru)</h1>
        <a href="{{ route('guru.booking.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Buat Peminjaman</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Peminjam</th>
                    <th class="px-4 py-2">Fasilitas</th>
                    <th class="px-4 py-2">Waktu</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bookings as $b)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $b->user->name }}</td>
                        <td class="px-4 py-2">{{ $b->facility->name }}</td>
                        <td class="px-4 py-2 text-xs">{{ $b->start_time->format('d/m/Y H:i') }} - {{ $b->end_time->format('H:i') }}</td>
                        <td class="px-4 py-2">{{ $b->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center">Belum ada peminjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
