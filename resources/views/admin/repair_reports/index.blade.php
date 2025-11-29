@extends('layouts.admin')
@section('page_title','Laporan Kerusakan')

@section('content')
<div class="flex items-start justify-between mb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Kerusakan</h1>
        <p class="text-sm text-gray-500">Pantau dan kelola laporan kerusakan fasilitas.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-3 py-2 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded-lg overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-[11px] border-b">
            <tr>
                <th class="px-4 py-3 text-left">Fasilitas</th>
                <th class="px-4 py-3 text-left">Pelapor</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Deskripsi</th>
                <th class="px-4 py-3 text-left">Foto</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($reports as $r)
                @php
                    $badge = match($r->status ?? 'pending') {
                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                        'fixed' => 'bg-green-100 text-green-700',
                        default => 'bg-gray-200 text-gray-700'
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $r->facility->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $r->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">{{ $r->status ?? 'pending' }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ \Illuminate\Support\Str::limit($r->description, 80) }}</td>
                    <td class="px-4 py-3">
                        @if($r->photo)
                            <a href="{{ asset('storage/'.$r->photo) }}" target="_blank" class="text-blue-600 underline text-xs">Lihat</a>
                        @else
                            <span class="text-xs text-gray-500">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.repair-reports.status', $r) }}" class="flex gap-2 items-center text-xs">
                            @csrf
                            @method('PUT')
                            <select name="status" class="border rounded px-2 py-1">
                                <option value="pending" {{ $r->status==='pending'?'selected':'' }}>Pending</option>
                                <option value="in_progress" {{ $r->status==='in_progress'?'selected':'' }}>Proses</option>
                                <option value="fixed" {{ $r->status==='fixed'?'selected':'' }}>Selesai</option>
                            </select>
                            <button class="px-2 py-1 bg-blue-600 text-white rounded">Simpan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
