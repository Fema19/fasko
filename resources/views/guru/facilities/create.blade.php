@extends('layouts.guru')
@section('page_title','Tambah Fasilitas')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Tambah Fasilitas</h1>
            <p class="text-xs text-slate-500">Ruangan: {{ Auth::user()->room->name ?? '-' }}</p>
        </div>
        <a href="{{ route('guru.facilities.index') }}" class="text-xs text-slate-600 hover:text-slate-900">Kembali</a>
    </div>

    <div class="bg-white p-5 rounded-lg border">
        @if($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                <p class="font-semibold mb-1">Periksa kembali:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('guru.facilities.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="room_id" value="{{ Auth::user()->room_id }}">

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Kategori</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200" required id="categorySelect">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" data-type="{{ $c->type ?? 'unit' }}" {{ old('category_id')==$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @if($categories->isEmpty())
                        <p class="text-xs text-amber-600 mt-1">Belum ada kategori. Buat dulu di panel admin.</p>
                    @endif
                </div>
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Nama</label>
                    <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Kondisi</label>
                    <select name="condition" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                        <option value="baik" {{ old('condition')=='baik'?'selected':'' }}>Baik</option>
                        <option value="rusak" {{ old('condition')=='rusak'?'selected':'' }}>Rusak</option>
                        <option value="perawatan" {{ old('condition')=='perawatan'?'selected':'' }}>Perawatan</option>
                        <option value="hilang" {{ old('condition')=='hilang'?'selected':'' }}>Hilang</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Foto (opsional)</label>
                    <input type="file" name="photo" class="w-full text-sm">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div id="capacityField">
                    <label class="block mb-1 text-sm text-slate-700">Kapasitas (wajib untuk kategori capacity)</label>
                    <input type="number" name="capacity" min="1" value="{{ old('capacity') }}" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200">
                </div>
                <div id="unitField">
                    <label class="block mb-1 text-sm text-slate-700">Unit (wajib untuk kategori unit)</label>
                    <input type="number" name="unit" min="0" value="{{ old('unit') }}" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200">
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm text-slate-700">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200" placeholder="Detail fasilitas">{{ old('description') }}</textarea>
            </div>

            <div class="pt-2">
                <button class="px-4 py-2 bg-slate-900 text-white rounded-md text-sm hover:bg-slate-800">Simpan</button>
            </div>
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
