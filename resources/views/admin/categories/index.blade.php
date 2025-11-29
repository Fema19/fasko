@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kategori Fasilitas</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Tambah Kategori</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $c)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $c->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.categories.edit', $c) }}" class="text-yellow-600 mr-2">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin?')" class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-600">Belum ada kategori</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
