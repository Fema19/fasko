@extends('layouts.admin')
@section('page_title', 'Daftar Ruangan')
@section('content')

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Daftar Ruangan</h1>

    <a href="{{ route('admin.rooms.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow">
       + Tambah Ruangan
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg overflow-hidden">

    <table class="w-full border-collapse text-sm">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Ruangan</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Kode</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Guru Penanggung Jawab</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rooms as $room)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $loop->iteration }}</td>

                    <td class="px-4 py-3 text-gray-900 font-medium">
                        {{ $room->name }}
                    </td>

                    <td class="px-4 py-3 text-gray-700">
                        {{ $room->code ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($room->user)
                            <span class="px-3 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ $room->user->name }}
                            </span>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center space-x-1">

                        <a href="{{ route('admin.rooms.show', $room) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                           Lihat
                        </a>

                        <a href="{{ route('admin.rooms.edit', $room) }}" 
                           class="text-yellow-500 hover:text-yellow-700 font-medium">
                           Edit
                        </a>

                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus ruangan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800 font-medium">
                                Hapus
                            </button>
                        </form>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">
                        Belum ada ruangan terdaftar
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
