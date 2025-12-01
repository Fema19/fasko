@extends('layouts.guru')
@section('page_title','Buat Booking')

@section('content')
<div class="space-y-4">
    <div class="card card-padding flex items-start justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Pilih Fasilitas untuk Booking</h2>
            <p class="text-sm muted">Telusuri fasilitas, lalu isi jadwal booking.</p>
        </div>
        <a href="{{ route('guru.bookings.index') }}" class="btn-ghost">Kembali</a>
    </div>

    <div class="card card-padding space-y-4">
        @if ($errors->any())
            <div class="card card-padding border-red-200 bg-red-50/80 text-red-700 text-sm">
                <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" class="grid md:grid-cols-3 gap-3 text-sm">
            <div>
                <label class="block mb-1 text-slate-700">Cari</label>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="input-base" placeholder="Nama / deskripsi">
            </div>
            <div>
                <label class="block mb-1 text-slate-700">Kategori</label>
                <select name="category_id" class="input-base">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? null)==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="btn-dark">Filter</button>
                <a href="{{ route('guru.bookings.create') }}" class="btn-ghost">Reset</a>
            </div>
        </form>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($facilities as $f)
                <div class="facility-card hover:shadow-lg transition" data-facility="{{ $f->id }}">
                    <div class="h-32 bg-slate-100 flex items-center justify-center overflow-hidden">
                        @if($f->photo)
                            <img src="{{ asset('storage/'.$f->photo) }}" alt="{{ $f->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs text-slate-500">Tidak ada foto</span>
                        @endif
                    </div>
                    <div class="p-3 space-y-1">
                        <p class="font-semibold text-slate-900">{{ $f->name }}</p>
                        <p class="text-xs text-slate-500">{{ $f->category->name ?? 'Tanpa kategori' }} • {{ $f->room->name ?? '-' }}</p>
                        <span class="tag">Kondisi: {{ $f->condition }}</span>
                        <div class="flex flex-wrap gap-2 text-[11px] text-slate-600 mt-1">
                            <span>Kapasitas: {{ $f->capacity ?? '-' }}</span>
                            <span>
                                {{ $f->availability_label === 'capacity' ? 'Kapasitas tersedia sekarang' : 'Unit tersedia sekarang' }}:
                                {{ $f->available_stock_now }} / {{ $f->availability_limit }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 mt-1">{{ \Illuminate\Support\Str::limit($f->description, 80) }}</p>
                        <button type="button" class="mt-2 text-xs px-3 py-2 rounded-md bg-slate-900 text-white w-full select-facility" data-id="{{ $f->id }}" data-name="{{ $f->name }}">Pilih</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">Fasilitas tidak ditemukan.</p>
            @endforelse
        </div>

        <form action="{{ route('guru.bookings.store') }}" method="POST" class="space-y-4" id="bookingForm">
            @csrf
            <input type="hidden" name="facility_id" id="facilityInput" value="{{ old('facility_id') }}">
            <div class="card card-padding bg-slate-50 text-sm">
                <p class="font-semibold text-slate-800">Fasilitas terpilih:</p>
                <p id="selectedFacility" class="text-slate-600">{{ old('facility_id') ? 'ID: '.old('facility_id') : 'Belum dipilih' }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Mulai</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="input-base" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Selesai</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="input-base" required>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Unit yang dibutuhkan</label>
                    <input type="number" min="1" name="capacity_used" value="{{ old('capacity_used', 1) }}" class="input-base" required>
                    <p class="text-[11px] text-slate-500 mt-1">Tidak boleh melebihi unit tersedia pada fasilitas.</p>
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm text-slate-700">Keperluan</label>
                <textarea name="reason" rows="3" class="input-base" placeholder="Contoh: rapat, presentasi">{{ old('reason') }}</textarea>
            </div>

            <div class="pt-2 flex gap-2">
                <button class="btn-dark" id="submitBooking">Kirim Booking</button>
                <a href="{{ route('guru.bookings.index') }}" class="btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.select-facility').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        document.getElementById('facilityInput').value = id;
        document.getElementById('selectedFacility').textContent = name;
    });
});
</script>
@endsection
