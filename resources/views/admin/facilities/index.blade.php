@extends('layouts.admin')
@section('page_title','Daftar Fasilitas')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Fasilitas Sekolah</h1>
        <p class="text-gray-500 text-sm">Kelola semua data fasilitas dengan mudah</p>
    </div>

    <a href="{{ route('admin.facilities.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm font-semibold transition">
       + Tambah Fasilitas
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700 border-b">
                <tr>
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Nama Fasilitas</th>
                    <th class="px-5 py-3 text-left">Kategori</th>
                    <th class="px-5 py-3 text-left">Ruangan</th>
                    <th class="px-5 py-3 text-left">Kondisi</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($facilities as $f)

                @php
                    $badge = match($f->condition) {
                        'baik'  => 'bg-green-200 text-green-800',
                        'rusak' => 'bg-red-200 text-red-800',
                        default => 'bg-yellow-200 text-yellow-800'
                    };
                @endphp

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">{{ $loop->iteration }}</td>

                    <td class="px-5 py-3 font-medium text-gray-800">
                        {{ $f->name }}
                    </td>

                    <td class="px-5 py-3 text-gray-700">
                        {{ $f->category->name ?? '-' }}
                    </td>

                    <td class="px-5 py-3 text-gray-700">
                        {{ $f->room->name ?? '-' }}
                    </td>

                    <td class="px-5 py-3">
                        <span class="px-2 py-1 text-xs rounded font-bold {{ $badge }}">
                            {{ strtoupper($f->condition) }}
                        </span>
                    </td>

                    <td class="px-5 py-3">

                        <div class="flex items-center justify-center gap-2">

                            <a href="{{ route('admin.facilities.show', $f) }}" 
                               class="text-blue-600 hover:underline">Detail</a>

                            <a href="{{ route('admin.facilities.edit', $f) }}" 
                               class="text-yellow-600 hover:underline">Edit</a>

                            <form action="{{ route('admin.facilities.destroy', $f) }}" 
                                  method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>

                        </div>

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-gray-500">
                        Tidak ada fasilitas ditemukan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
