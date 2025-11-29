@extends('layouts.admin')
@section('page_title', 'Daftar Ruangan')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Ruangan</h1>
        <a href="{{ route('admin.rooms.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Tambah Ruangan</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Kode</th>
                    <th class="px-4 py-2">Guru</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($rooms as $room)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $room->name }}</td>
                        <td class="px-4 py-2">{{ $room->code ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $room->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.rooms.show', $room) }}" class="text-blue-600 mr-2">Lihat</a>
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="text-yellow-600 mr-2">Edit</a>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin?')" class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-600">Belum ada ruangan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
