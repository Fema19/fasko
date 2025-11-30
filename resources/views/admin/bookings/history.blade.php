@extends('layouts.admin')
@section('page_title', 'History Booking')

@php
    $statusColors = [
        'completed' => 'bg-green-50 text-green-700 border-green-200',
        'cancelled' => 'bg-orange-50 text-orange-700 border-orange-200',
        'rejected'  => 'bg-red-50 text-red-700 border-red-200',
        'approved'  => 'bg-blue-50 text-blue-700 border-blue-200',
        'active'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
    ];

    $statusLabels = [
        'completed' => 'Selesai / Check-out',
        'cancelled' => 'Dibatalkan',
        'rejected'  => 'Ditolak',
        'approved'  => 'Disetujui (belum mulai)',
        'active'    => 'Sedang berlangsung',
    ];
@endphp

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">History Booking</h1>
        <p class="text-sm text-gray-500">Pantau booking yang ditolak, dibatalkan, atau sudah check-out.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.bookings.history.export', request()->query()) }}" class="text-sm bg-gray-900 text-white px-3 py-2 rounded">
            Export PDF
        </a>
        <form action="{{ route('admin.bookings.history.reset') }}" method="POST" class="js-reset-history">
            @csrf
            @method('DELETE')
            <button class="text-sm text-red-600 hover:text-red-800 px-3 py-2 border rounded">Reset History</button>
        </form>
        <a href="{{ route('admin.bookings.requests') }}" class="text-sm text-gray-600 hover:text-gray-900 px-3 py-2 border rounded">
            Permintaan
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white border rounded-xl p-4 mb-4">
    <form method="GET" class="grid gap-3 md:grid-cols-4">
        <div class="flex flex-col">
            <label class="text-xs text-gray-600 font-semibold mb-1">Tanggal mulai</label>
            <input type="date" name="date" value="{{ $filters['date'] }}"
                class="border rounded px-3 py-2 text-sm focus:ring focus:ring-gray-200">
        </div>
        <div class="flex flex-col">
            <label class="text-xs text-gray-600 font-semibold mb-1">Status</label>
            <select name="status" class="border rounded px-3 py-2 text-sm focus:ring focus:ring-gray-200">
                <option value="">Semua status (kecuali pending)</option>
                @foreach($statusLabels as $key => $label)
                    <option value="{{ $key }}" {{ $filters['status'] === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2 flex flex-col">
            <label class="text-xs text-gray-600 font-semibold mb-1">Cari</label>
            <input type="text" name="q" value="{{ $filters['q'] }}"
                placeholder="Nama peminjam, fasilitas, atau keperluan"
                class="border rounded px-3 py-2 text-sm focus:ring focus:ring-gray-200">
        </div>
        <div class="md:col-span-4 flex justify-end gap-2">
            <button class="bg-gray-900 text-white px-4 py-2 rounded text-xs">
                Terapkan
            </button>
            <a href="{{ route('admin.bookings.history') }}"
               class="px-4 py-2 border text-xs rounded text-gray-700 hover:bg-gray-50">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-600 text-green-900 px-4 py-3 mb-3 text-sm rounded">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-600 text-red-900 px-4 py-3 mb-3 text-sm rounded">
    {{ session('error') }}
</div>
@endif

<div class="bg-white border rounded-xl overflow-hidden">
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-800">Daftar Riwayat</h2>
        <span class="text-xs text-gray-500">Total: {{ $bookings->total() }}</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-[11px] text-gray-600 uppercase tracking-wide border-b">
            <tr>
                <th class="px-4 py-3 text-left">Peminjam</th>
                <th class="px-4 py-3 text-left">Fasilitas</th>
                <th class="px-4 py-3 text-left">Jadwal</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Catatan</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($bookings as $b)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="font-semibold text-gray-900">{{ $b->user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $b->user->email }}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800">{{ $b->facility->name }}</div>
                    <div class="text-xs text-gray-500">
                        Ruangan: {{ $b->facility->room->name ?? '-' }}
                    </div>
                    <div class="text-xs text-gray-500">Unit dipakai: {{ $b->capacity_used }}</div>
                </td>
                <td class="px-4 py-3 text-gray-700">
                    <div>{{ optional($b->start_time)->format('d M Y H:i') }}</div>
                    <div class="text-xs text-gray-500">s/d {{ optional($b->end_time)->format('d M Y H:i') }}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs border capitalize {{ $statusColors[$b->status] ?? 'border-gray-200 text-gray-700' }}">
                        {{ $b->status }}
                    </span>
                    @if($b->checked_out && $b->check_out_time)
                        <div class="text-[11px] text-gray-500 mt-1">Checkout: {{ $b->check_out_time->format('d M Y H:i') }}</div>
                    @elseif($b->checked_in && $b->check_in_time)
                        <div class="text-[11px] text-gray-500 mt-1">Check-in: {{ $b->check_in_time->format('d M Y H:i') }}</div>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-700">
                    {{ $b->reason ?: '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-500 text-sm">
                    Belum ada riwayat untuk filter ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($bookings->hasPages())
    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-reset-history').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Bersihkan riwayat?',
                text: 'History booking yang sudah lewat akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
