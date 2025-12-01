<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /* ============================================================
       1. USER / GURU / SISWA - LIST BOOKING DIRI SENDIRI
    ============================================================*/
    public function index()
    {
        $user = Auth::user();

        // Auto-reset riwayat untuk siswa/guru non-PJ (hapus booking lampau milik sendiri)
        $this->cleanupUserHistory($user);

        $bookings = Booking::with(['facility','user'])
            ->where('user_id', Auth::id())
            ->where(function($q){
                $q->whereNull('end_time')
                  ->orWhere('end_time', '>=', now()->subDays(3));
            })
            ->latest()
            ->get();

        $messages = Message::where('receiver_id', $user->id)
            ->latest()
            ->get();

        Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $view = $user->role === 'guru'
            ? 'guru.bookings.index'
            : 'siswa.bookings.index';

        return view($view, compact('bookings','messages'));
    }


    /* ============================================================
       2. DETAIL BOOKING
    ============================================================*/
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403,"Akses booking tidak valid");
        }

        return view('bookings.show', compact('booking'));
    }


    /* ============================================================
       3. FORM BOOKING
    ============================================================*/
    public function create()
    {
        $view = Auth::user()->role === 'guru'
            ? 'guru.bookings.create'
            : 'siswa.bookings.create';

        $filters = [
            'category_id' => request('category_id'),
            'q' => request('q')
        ];

        $facilitiesQuery = Facility::with(['category','room']);

        if ($filters['category_id']) {
            $facilitiesQuery->where('category_id', $filters['category_id']);
        }

        if ($filters['q']) {
            $facilitiesQuery->where(function($q) use ($filters){
                $q->where('name','like','%'.$filters['q'].'%')
                  ->orWhere('description','like','%'.$filters['q'].'%');
            });
        }

        return view($view,[
            'facilities' => $facilitiesQuery->orderBy('name')->get(),
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'filters'    => $filters,
        ]);
    }


    /* ============================================================
       4. SIMPAN BOOKING USER
    ============================================================*/
    public function store(Request $request)
    {
        $request->validate([
            'facility_id'=>'required|exists:facilities,id',
            'start_time'=>'required|date',
            'end_time'=>'required|date|after:start_time',
            'reason'=>'nullable|string',
            'capacity_used'=>'required|integer|min:1',
        ]);

        $facility = Facility::with('category')->findOrFail($request->facility_id);

        $max = $facility->availability_limit;
        if ($request->capacity_used > $max) {
            return back()->withErrors(['capacity_used' => 'Unit yang diminta melebihi ketersediaan fasilitas (maks '.$max.').'])->withInput();
        }

        Booking::create([
            'user_id'=>Auth::id(),
            'facility_id'=>$request->facility_id,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'reason'=>$request->reason,
            'capacity_used'=>$request->capacity_used,
            'status'=>'pending',
        ]);

        return redirect()->route(
            Auth::user()->role === 'guru' ? 'guru.bookings.index':'siswa.bookings.index'
        )->with('success','Booking dibuat, menunggu persetujuan.');
    }


    /* ============================================================
       8. CHECKIN (Pemilik booking: siswa/guru tanpa room)
    ============================================================*/
    public function checkIn(Booking $booking)
    {
        $user = Auth::user();

        // Hanya pemilik booking (siswa atau guru tanpa room_id)
        if ($booking->user_id !== $user->id) {
            abort(403,'Hanya pemilik booking yang boleh check-in');
        }
        if ($user->role === 'admin' || ($user->role === 'guru' && $user->room_id)) {
            abort(403,'Role ini tidak boleh check-in');
        }

        $now = now();
        $start = $booking->start_time;
        // Normalisasi ke menit untuk menghindari selisih detik
        $windowStart = $start->copy()->subMinutes(30)->startOfMinute();
        $windowEnd = $windowStart->copy()->addMinutes(5)->subSecond(); // 5 menit penuh

        // Check-in dibuka 30 menit sebelum mulai
        if ($now->lt($windowStart)) {
            return back()->with('error','Check-in dibuka mulai '. $windowStart->format('H:i') .' (30 menit sebelum waktu mulai).');
        }

        // Jika melewati 5 menit window, batalkan otomatis
        if ($now->gt($windowEnd)) {
            $booking->update(['status' => 'cancelled']);
            return back()->with('error','Tidak check-in dalam 5 menit setelah dibuka, booking dibatalkan.');
        }

        $booking->update([
            'status'=>'active',
            'checked_in'=>true,
            'check_in_time'=>now()
        ]);

        return back()->with('success','Check-in berhasil');
    }


    /* ============================================================
       11. CANCEL (USER/GURU PEMILIK BOOKING)
    ============================================================*/
    public function destroy(Booking $booking)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
            abort(403,'Tidak boleh membatalkan booking ini');
        }

        if ($booking->status === 'pending' || $booking->status === 'approved') {
            $booking->update(['status' => 'cancelled']);
        }

        $route = $user->role === 'guru' ? 'guru.bookings.index' : 'siswa.bookings.index';

        return redirect()->route($route)->with('success','Booking dibatalkan');
    }

    /**
     * Hapus otomatis riwayat yang sudah lewat (dipanggil sebelum menampilkan history).
     */
    private function cleanupUserHistory($user): void
    {
        // Hanya untuk siswa atau guru non-PJ (room_id null)
        if ($user->role === 'siswa' || ($user->role === 'guru' && ! $user->room_id)) {
            Booking::where('user_id', $user->id)
                ->where('status','!=','pending')
                ->whereNotNull('end_time')
                ->where('end_time','<=', now()->subDays(2))
                ->delete();
        }
    }
}
