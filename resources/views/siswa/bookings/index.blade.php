@extends('layouts.siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Peminjaman Saya</h1>
        <p class="text-sm text-slate-500">Status dan detail peminjaman.</p>
    </div>

    @if(session('success'))
        <div class="rounded border border-green-200 bg-green-50 text-green-800 text-sm px-3 py-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded border border-red-200 bg-red-50 text-red-800 text-sm px-3 py-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-3">
        @forelse($bookings as $b)
            <div class="bg-white border rounded-xl p-4 shadow-sm space-y-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $b->facility->name }}</p>
                        <p class="text-xs text-slate-600">{{ $b->start_time->format('d M Y H:i') }} - {{ $b->end_time->format('H:i') }}</p>
                    </div>
                    <span class="px-2 py-1 text-[11px] rounded border text-slate-700">{{ ucfirst($b->status) }}</span>
                </div>
                @if($b->reason)
                    <p class="text-xs text-slate-600">{{ $b->reason }}</p>
                @endif

                <div class="pt-2 flex gap-2 flex-wrap">
                    @if($b->status === 'pending')
                        <form action="{{ route('siswa.bookings.destroy', $b) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Batalkan peminjaman?')" class="text-xs px-3 py-2 rounded border text-red-700 hover:bg-red-50">
                                Batalkan
                            </button>
                        </form>
                    @endif

                    @if($b->status === 'approved' && !$b->checked_in)
                        <form action="{{ route('siswa.bookings.checkin', $b) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="text-xs px-3 py-2 rounded border text-slate-700 hover:bg-slate-50">
                                Check-in
                            </button>
                        </form>
                    @elseif($b->checked_in)
                        <span class="text-xs px-3 py-2 rounded border border-green-600 text-green-700 bg-green-50">IN</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border rounded-xl p-4 text-center text-slate-500 text-sm">Belum ada peminjaman.</div>
        @endforelse
    </div>
</div>
@endsection
