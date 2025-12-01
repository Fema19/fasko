@extends('layouts.siswa')

@section('content')
<div class="space-y-4">
    <div class="card card-padding flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Peminjaman Saya</h1>
            <p class="text-sm muted">Status dan detail peminjaman.</p>
        </div>
        <a href="{{ route('siswa.bookings.create') }}" class="btn-dark">+ Booking</a>
    </div>

    @if(session('success'))
        <div class="card card-padding border-green-200 bg-green-50/80 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="card card-padding border-red-200 bg-red-50/80 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if(isset($messages) && $messages->count())
        <div class="card card-padding border-amber-200 bg-amber-50/80 text-amber-900 text-sm">
            <div class="flex items-center justify-between gap-2 mb-2">
                <span class="font-semibold text-xs uppercase tracking-wide">Pesan penolakan</span>
                <span class="text-[11px] text-amber-700">Terbaru</span>
            </div>
            <ul class="space-y-1">
                @foreach($messages as $msg)
                    <li class="flex items-start justify-between gap-3">
                        <span class="text-sm">{{ $msg->message }}</span>
                        <span class="text-[11px] text-amber-700 whitespace-nowrap">{{ $msg->created_at->format('d M Y H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-3">
        @forelse($bookings as $b)
            <div class="card card-padding space-y-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $b->facility->name }}</p>
                        <p class="text-xs text-slate-600">{{ $b->start_time->format('d M Y H:i') }} - {{ $b->end_time->format('H:i') }}</p>
                    </div>
                    <span class="badge-soft">{{ ucfirst($b->status) }}</span>
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
            <div class="card card-padding text-center text-slate-500 text-sm">Belum ada peminjaman.</div>
        @endforelse
    </div>
</div>
@endsection
