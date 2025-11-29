@extends('layouts.guru')
@section('page_title','Laporan Kerusakan')

@php
    $createRoute = route('guru.reports.create');
@endphp

@section('content')
<div class="flex items-start justify-between mb-4">
    <div>
        <h1 class="text-xl font-semibold text-slate-900">Laporan Kerusakan</h1>
        <p class="text-xs text-slate-500">Daftar laporan yang Anda kirim atau terkait ruangan Anda.</p>
    </div>
    <a href="{{ $createRoute }}" class="px-3 py-2 bg-slate-900 text-white rounded text-xs">+ Buat Laporan</a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-3 py-2 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded-lg overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 uppercase text-[11px] border-b">
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
                        'in_progress' => 'bg-amber-100 text-amber-700',
                        'fixed' => 'bg-green-100 text-green-700',
                        default => 'bg-slate-200 text-slate-700'
                    };
                @endphp
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $r->facility->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $r->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">{{ $r->status ?? 'pending' }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-700">{{ \Illuminate\Support\Str::limit($r->description, 80) }}</td>
                    <td class="px-4 py-3">
                        @if($r->photo)
                            <a href="{{ asset('storage/'.$r->photo) }}" target="_blank" class="text-blue-600 underline text-xs">Lihat</a>
                        @else
                            <span class="text-xs text-slate-500">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if(Auth::user()->room_id && ($r->facility->room_id ?? null) === Auth::user()->room_id)
                        <form method="POST" action="{{ route('guru.reports.status', $r) }}" class="flex gap-2 items-center text-xs">
                            @csrf
                            @method('PUT')
                            <select name="status" class="border rounded px-2 py-1">
                                <option value="pending" {{ $r->status==='pending'?'selected':'' }}>Pending</option>
                                <option value="in_progress" {{ $r->status==='in_progress'?'selected':'' }}>Proses</option>
                                <option value="fixed" {{ $r->status==='fixed'?'selected':'' }}>Selesai</option>
                            </select>
                            <button class="px-2 py-1 bg-slate-900 text-white rounded">Simpan</button>
                        </form>
                        @else
                            <span class="text-xs text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
