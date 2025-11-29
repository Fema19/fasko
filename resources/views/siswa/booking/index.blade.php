@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Peminjaman Saya</h1>
        <a href="{{ route('siswa.booking.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Buat Peminjaman</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Fasilitas</th>
                    <th class="px-4 py-2">Waktu</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bookings as $b)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $b->facility->name }}</td>
                        <td class="px-4 py-2 text-xs">{{ $b->start_time->format('d/m/Y H:i') }} - {{ $b->end_time->format('H:i') }}</td>
                        <td class="px-4 py-2">{{ $b->status }}</td>
                        <td class="px-4 py-2">
                            @if($b->status === 'pending')
                                <form action="{{ route('siswa.booking.destroy', $b) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Batalkan peminjaman?')" class="text-red-600">Batalkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center">Belum ada peminjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
