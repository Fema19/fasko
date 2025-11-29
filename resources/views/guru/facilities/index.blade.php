@extends('layouts.guru')
@section('page_title','Fasilitas Ruangan')

@section('content')
<div class="max-w-5xl">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Fasilitas Ruangan Saya</h1>
            <p class="text-xs text-slate-500">Hanya ruangan yang Anda kelola.</p>
        </div>
        <a href="{{ route('guru.facilities.create') }}" class="px-3 py-2 bg-slate-900 text-white rounded-md text-xs">+ Tambah</a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-3 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-[11px] uppercase text-slate-600 border-b">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-left">Kondisi</th>
                    <th class="px-4 py-2 text-left">Ruangan</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($facilities as $f)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2 font-medium text-slate-900">{{ $f->name }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ $f->category->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ ucfirst($f->condition) }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ $f->room->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('guru.facilities.edit', $f) }}" class="text-xs px-3 py-1.5 rounded border text-slate-700 hover:bg-slate-50">Edit</a>
                                <form action="{{ route('guru.facilities.destroy', $f) }}" method="POST" onsubmit="return confirm('Yakin hapus fasilitas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs px-3 py-1.5 rounded border text-red-700 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-slate-500 text-sm">Belum ada fasilitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
