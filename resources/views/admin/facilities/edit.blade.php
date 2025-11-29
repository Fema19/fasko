@extends('layouts.admin')
@section('page_title','Edit Fasilitas')
@section('content')

<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Edit Fasilitas</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('admin.facilities.update',$facility) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="font-semibold">Nama</label>
                <input name="name" value="{{ $facility->name }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Kategori</label>
                <select name="category_id" class="w-full border px-3 py-2 rounded">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $facility->category_id==$c->id?'selected':'' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Ruangan</label>
                <select name="room_id" class="w-full border px-3 py-2 rounded">
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ $facility->room_id==$r->id?'selected':'' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>
            </div>

           <div class="mb-4">
    <label class="font-semibold">Kondisi</label>
    <select name="condition" class="w-full border px-3 py-2 rounded">
        @foreach(['baik','rusak','perawatan','hilang'] as $c)
            <option value="{{ $c }}" {{ $facility->condition == $c ? 'selected' : '' }}>
                {{ ucfirst($c) }}
            </option>
        @endforeach
    </select>
</div>


            <div class="mb-4">
                <label class="font-semibold">Deskripsi</label>
                <textarea name="description" class="w-full border px-3 py-2 rounded" rows="4">
                    {{ $facility->description }}
                </textarea>
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>

                <a href="{{ route('admin.facilities.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded">
                   Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
