@extends('layouts.siswa')
@section('page_title','Buat Booking')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Buat Booking Fasilitas</h2>
            <p class="text-xs text-slate-500">Pilih fasilitas → isi jadwal → kirim booking.</p>
        </div>

        <a href="{{ route('siswa.bookings.index') }}" 
           class="text-xs text-slate-600 hover:text-slate-900 transition">Kembali</a>
    </div>



    {{-- FILTER CARD --}}
    <div class="rounded-lg border bg-white p-5 space-y-4">

        @if ($errors->any())
        <div class="rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700">
            <p class="font-semibold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif


        {{-- FILTER INPUT --}}
        <form method="GET" class="grid md:grid-cols-3 gap-3 text-sm">
            <div>
                <label class="text-slate-700 text-[13px]">Cari</label>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="input-base" placeholder="Nama / deskripsi">
            </div>

            <div>
                <label class="text-slate-700 text-[13px]">Kategori</label>
                <select name="category_id" class="input-base">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ ($filters['category_id']??null)==$cat->id?'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button class="btn-dark w-full md:w-auto">Filter</button>
                <a href="{{ route('siswa.bookings.create') }}" class="btn-light">Reset</a>
            </div>
        </form>


        {{-- LIST FASILITAS --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">

            @forelse($facilities as $f)
            <div class="facility-card">
                
                <div class="h-32 bg-slate-100 overflow-hidden flex items-center justify-center">
                    @if($f->photo)
                        <img src="{{ asset('storage/'.$f->photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-[11px] text-slate-500">Tidak ada foto</span>
                    @endif
                </div>

                <div class="space-y-1 p-3">
                    <p class="font-semibold text-[13px] text-slate-900 truncate">{{ $f->name }}</p>
                    <p class="text-[11px] text-slate-500">
                        {{ $f->category->name ?? 'Tanpa kategori' }} • {{ $f->room->name ?? '-' }}
                    </p>

                    <span class="tag">Kondisi: {{ $f->condition }}</span>

                    <div class="flex flex-wrap gap-2 text-[11px] text-slate-600">
                        <span>Kapasitas: {{ $f->capacity ?? '-' }}</span>
                        <span>
                            {{ $f->availability_label === 'capacity' ? 'Kapasitas tersedia sekarang' : 'Unit tersedia sekarang' }}:
                            {{ $f->available_stock_now }} / {{ $f->availability_limit }}
                        </span>
                    </div>

                    <p class="text-[11px] text-slate-600 line-clamp-3">
                        {{ Str::limit($f->description, 85) }}
                    </p>

                    <button type="button"
                        class="btn-dark-small w-full mt-2 select-facility"
                        data-id="{{ $f->id }}" data-name="{{ $f->name }}">
                        Pilih Fasilitas
                    </button>
                </div>

            </div>
            @empty
                <p class="text-sm text-slate-500 col-span-full text-center py-4">Tidak ada fasilitas ditemukan.</p>
            @endforelse

        </div>



        {{-- FORM BOOKING --}}
        <form action="{{ route('siswa.bookings.store') }}" method="POST" class="space-y-5" id="bookingForm">
            @csrf

            <input type="hidden" name="facility_id" id="facilityInput" value="{{ old('facility_id') }}">

            <div class="form-box">
                <p class="font-semibold text-[13px] text-slate-800">Fasilitas dipilih:</p>
                <p id="selectedFacility" class="text-[13px] text-slate-600">
                    {{ old('facility_id') ? 'ID: '.old('facility_id') : 'Belum dipilih' }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-slate-700 mb-1">Mulai</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="input-base" required>
                </div>
                <div>
                    <label class="block text-sm text-slate-700 mb-1">Selesai</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="input-base" required>
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-700 mb-1">Unit yang dibutuhkan</label>
                <input type="number" min="1" name="capacity_used" value="{{ old('capacity_used',1) }}" class="input-base" required>
                <p class="text-[11px] text-slate-500 mt-1">Tidak boleh melebihi unit tersedia pada fasilitas.</p>
            </div>

            <div>
                <label class="block text-sm text-slate-700 mb-1">Keperluan</label>
                <textarea name="reason" rows="3" class="input-base" placeholder="Contoh: rapat, presentasi">{{ old('reason') }}</textarea>
            </div>

            <div class="flex gap-3 pt-1">
                <button class="btn-dark">Kirim Booking</button>
                <a href="{{ route('siswa.bookings.index') }}" class="btn-light">Batal</a>
            </div>

        </form>

    </div>
</div>


{{-- JS --}}
<script>
document.querySelectorAll('.select-facility').forEach(btn=>{
    btn.onclick=()=>{
        facilityInput.value = btn.dataset.id
        selectedFacility.textContent = btn.dataset.name
    }
})
</script>


{{-- STYLE --}}
<style>
    .input-base{ @apply w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-slate-200; }
    .btn-dark{ @apply px-4 py-2 bg-slate-900 text-white text-sm rounded-md hover:bg-slate-800 transition; }
    .btn-light{ @apply text-xs px-3 py-2 rounded-md border text-slate-700 hover:bg-slate-50 transition; }
    .btn-dark-small{ @apply text-xs px-3 py-2 bg-slate-900 text-white rounded-md hover:bg-slate-800 transition; }

    .facility-card{ @apply border rounded-lg overflow-hidden bg-white hover:shadow-md transition; }
    .tag{ @apply px-2 py-1 text-[11px] border rounded text-slate-700 bg-slate-50 inline-block; }

    .form-box{ @apply p-3 border rounded bg-slate-50 text-sm; }
</style>
@endsection
