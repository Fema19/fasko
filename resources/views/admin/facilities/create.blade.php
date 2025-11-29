@extends('layouts.admin')
@section('page_title','Tambah Fasilitas')
@section('content')

<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Tambah Fasilitas</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('admin.facilities.store') }}">
            @csrf

            <div class="mb-4">
                <label class="font-semibold">Nama</label>
                <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Kategori</label>
                <select name="category_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Ruangan</label>
                <select name="room_id" class="w-full border px-3 py-2 rounded">
                    <option value="">-- Pilih --</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Kondisi</label>
                <select name="condition" class="w-full border px-3 py-2 rounded">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="perlu perbaikan">Perlu Perbaikan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Deskripsi</label>
                <textarea name="description" class="w-full border px-3 py-2 rounded" rows="4"></textarea>
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </form>
    </div>
</div>

@endsection
