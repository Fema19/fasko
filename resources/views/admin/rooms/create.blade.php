@extends('layouts.admin')
@section('page_title', 'Tambah Ruangan')

@section('content')

<h1 class="text-3xl font-bold text-gray-800 mb-8">Tambah Ruangan</h1>

<div class="bg-white shadow rounded-lg p-6 w-full max-w-xl">

    <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf

        {{-- NAMA --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2">Nama Ruangan</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        {{-- KODE --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2">Kode Ruangan (Opsional)</label>
            <input type="text" name="code" value="{{ old('code') }}"
                   class="w-full border px-3 py-2 rounded">
        </div>

        {{-- PILIH GURU --}}
        <div class="mb-6">
            <label class="block font-semibold mb-2">Guru Penanggung Jawab</label>
            <select name="user_id" class="w-full border px-3 py-2 rounded">
                <option value="">-- Tidak Ada Guru --</option>

                @foreach($gurus as $g)
                    <option value="{{ $g->id }}" 
                        {{ old('user_id') == $g->id ? 'selected' : '' }}>
                        {{ $g->name }} ({{ $g->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.rooms.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>

    </form>

</div>

@endsection
