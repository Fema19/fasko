@extends('layouts.admin')
@section('page_title','Detail Fasilitas')
@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-800">{{ $facility->name }}</h1>

        <div class="flex gap-2">
            <a href="{{ route('admin.facilities.edit', $facility) }}" 
               class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm shadow">
                ✏ Edit
            </a>

            <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm shadow">
                    🗑 Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Card Detail Fasilitas -->
    <div class="bg-white shadow-lg rounded-lg p-6 border">

        {{-- KATEGORI --}}
        <p class="mb-2">
            <span class="font-semibold text-gray-600">Kategori :</span>
            <span class="text-gray-800">{{ $facility->category->name ?? '-' }}</span>
        </p>

        {{-- RUANGAN --}}
        <p class="mb-2">
            <span class="font-semibold text-gray-600">Ruangan :</span>
            <span class="text-gray-800">{{ $facility->room->name ?? '-' }}</span>
        </p>

        {{-- KONDISI --}}
        @php
            $badge = match($facility->condition) {
                'baik'  => 'bg-green-200 text-green-800',
                'rusak' => 'bg-red-200 text-red-800',
                default => 'bg-yellow-200 text-yellow-800'
            };
        @endphp

        <p class="mb-2 flex items-center gap-2">
            <span class="font-semibold text-gray-600">Kondisi :</span>
            <span class="px-2 py-1 text-xs font-semibold rounded {{ $badge }}">
                {{ strtoupper($facility->condition) }}
            </span>
        </p>

        {{-- DESKRIPSI --}}
        <div class="mt-4">
            <p class="font-semibold text-gray-600 mb-1">Deskripsi :</p>
            <p class="text-gray-700 leading-relaxed">
                {{ $facility->description ?? 'Tidak ada deskripsi.' }}
            </p>
        </div>

        {{-- FOTO FASILITAS (opsional jika sudah ada) --}}
        @if($facility->photo)
            <div class="mt-6">
                <p class="font-semibold text-gray-600 mb-2">Foto Fasilitas :</p>
                <img src="{{ asset('storage/'.$facility->photo) }}" 
                     class="rounded-lg shadow-md max-h-64 border">
            </div>
        @endif

    </div>

    <div class="mt-6">
        <a href="{{ route('admin.facilities.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-md shadow hover:bg-gray-700">
           ⬅ Kembali ke Daftar
        </a>
    </div>

</div>

@endsection
