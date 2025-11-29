@extends('layouts.app')

@section('content')
<div class="max-w-7xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Fasilitas</h1>
        <a href="{{ route('admin.facilities.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Tambah Fasilitas</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Kategori</th>
                    <th class="px-4 py-2">Ruangan</th>
                    <th class="px-4 py-2">Kondisi</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($facilities as $f)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $f->name }}</td>
                        <td class="px-4 py-2">{{ $f->category->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $f->room->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $f->condition }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.facilities.show', $f) }}" class="text-blue-600 mr-2">Lihat</a>
                            <a href="{{ route('admin.facilities.edit', $f) }}" class="text-yellow-600 mr-2">Edit</a>
                            <form action="{{ route('admin.facilities.destroy', $f) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin?')" class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-600">Belum ada fasilitas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
