@extends('layouts.guru')
@section('page_title','Kategori Fasilitas')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Kategori Fasilitas</h1>
            <p class="text-xs text-slate-500">Guru dengan ruangan dapat kelola kategori.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('guru.categories.create') }}" class="text-xs px-3 py-2 rounded bg-slate-900 text-white hover:bg-slate-800">+ Tambah</a>
            <a href="{{ route('guru.dashboard') }}" class="text-xs text-slate-600 hover:text-slate-900">Dashboard</a>
        </div>
    </div>

    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-[11px] uppercase text-slate-600 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $c)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-700">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $c->name }}</td>
                        <td class="px-4 py-3 text-slate-700 capitalize">{{ $c->type ?? 'unit' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('guru.categories.edit', $c) }}" class="text-xs px-3 py-1.5 rounded border text-slate-700 hover:bg-slate-50">Edit</a>
                                <form action="{{ route('guru.categories.destroy', $c) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs px-3 py-1.5 rounded border text-red-700 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-slate-500 text-sm">
                            Belum ada kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
