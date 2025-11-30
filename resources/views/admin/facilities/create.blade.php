@extends('layouts.admin')
@section('page_title','Tambah Fasilitas')
@section('content')

<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Tambah Fasilitas</h1>

    <div class="bg-white p-4 rounded shadow">
        <form method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="font-semibold">Nama</label>
                <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Kategori</label>
                <select name="category_id" class="w-full border px-3 py-2 rounded" id="categorySelect">
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" data-type="{{ $c->type ?? 'unit' }}">{{ $c->name }}</option>
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

            <div class="mb-4">
                <label class="font-semibold">Foto (opsional)</label>
                <input type="file" name="photo" class="w-full">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div id="capacityField">
                    <label class="font-semibold">Kapasitas (wajib untuk kategori capacity)</label>
                    <input type="number" name="capacity" min="1" class="w-full border px-3 py-2 rounded" value="{{ old('capacity') }}">
                </div>
                <div id="unitField">
                    <label class="font-semibold">Unit (wajib untuk kategori unit)</label>
                    <input type="number" name="unit" min="0" class="w-full border px-3 py-2 rounded" value="{{ old('unit') }}">
                </div>
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </form>
    </div>
</div>

<script>
    function toggleFields() {
        const select = document.getElementById('categorySelect');
        const type = select?.options[select.selectedIndex]?.dataset.type || 'unit';
        const capacityField = document.getElementById('capacityField');
        const unitField = document.getElementById('unitField');
        if (type === 'capacity') {
            capacityField.style.display = 'block';
            unitField.style.display = 'none';
        } else {
            capacityField.style.display = 'none';
            unitField.style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('categorySelect');
        if (select) {
            select.addEventListener('change', toggleFields);
            toggleFields();
        }
    });
</script>
@endsection
