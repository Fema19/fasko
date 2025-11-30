@extends('layouts.admin')
@section('page_title', 'Kategori Fasilitas')
@section('content')

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Kategori Fasilitas</h1>

    <a href="{{ route('admin.categories.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow">
       + Tambah Kategori
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg overflow-hidden">

    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Kategori</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipe</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($categories as $c)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $loop->iteration }}</td>

                    <td class="px-4 py-3 text-gray-900">
                        {{ $c->name }}
                    </td>

                    <td class="px-4 py-3 text-gray-700 capitalize">
                        {{ $c->type ?? 'unit' }}
                    </td>

                    <td class="px-4 py-3 text-center space-x-1">

                        <a href="{{ route('admin.categories.edit', $c) }}"
                           class="text-yellow-500 hover:text-yellow-700 font-medium">
                           Edit
                        </a>

                        <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                        </form>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">
                        Belum ada kategori terdaftar
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
