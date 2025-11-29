@extends('layouts.guru')
@section('page_title', 'Edit Booking')

@section('content')

<div class="max-w-3xl">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Edit Booking</h2>
            <p class="text-xs text-slate-500">Perbarui jadwal atau keperluan.</p>
        </div>
        <a href="{{ route('guru.bookings.index') }}" class="text-xs text-slate-600 hover:text-slate-900">Kembali</a>
    </div>

    <div class="bg-white p-5 rounded-lg border">
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.bookings.update', $booking) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 text-sm text-slate-700">Fasilitas</label>
                <select name="facility_id" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                    @foreach($facilities as $f)
                    <option value="{{ $f->id }}" {{ $booking->facility_id == $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Mulai</label>
                    <input type="datetime-local" name="start_time"
                        value="{{ date('Y-m-d\TH:i', strtotime($booking->start_time)) }}"
                        class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                </div>

                <div>
                    <label class="block mb-1 text-sm text-slate-700">Selesai</label>
                    <input type="datetime-local" name="end_time"
                        value="{{ date('Y-m-d\TH:i', strtotime($booking->end_time)) }}"
                        class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm text-slate-700">Jumlah Pengguna</label>
                    <input type="number" min="1" name="capacity_used"
                        value="{{ old('capacity_used', $booking->capacity_used) }}"
                        class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                </div>
            </div>

            <div>
                <label class="block mb-1 text-sm text-slate-700">Keperluan</label>
                <textarea name="reason" rows="3" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:ring focus:ring-slate-200">{{ $booking->reason }}</textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button class="px-4 py-2 bg-slate-900 text-white rounded-md text-sm hover:bg-slate-800">
                    Simpan Perubahan
                </button>

                <a href="{{ route('guru.bookings.index') }}"
                    class="px-4 py-2 bg-slate-100 text-slate-700 rounded-md text-sm hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
