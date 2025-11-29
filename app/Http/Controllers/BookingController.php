<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /* ============================================================
       1. USER / GURU / SISWA - LIST BOOKING DIRI SENDIRI
    ============================================================*/
    public function index()
    {
        $bookings = Booking::with(['facility','user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $view = Auth::user()->role === 'guru'
            ? 'guru.bookings.index'
            : 'siswa.bookings.index';

        return view($view, compact('bookings'));
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
       5. HALAMAN REQUEST ADMIN & GURU PJ RUANGAN
    ============================================================*/
    public function requests(Request $request)
    {
        $user = Auth::user();

        $query = Booking::with(['user','facility'])
            ->where('status','pending');

        // FILTER BY DATE
        if ($request->date) {
            $query->whereDate('start_time', $request->date);
        }

        // Guru hanya melihat request fasilitas dari ruangan yang ia tangani
        if ($user->role === 'guru') {
            if (! $user->room_id) {
                abort(403,'Anda tidak terdaftar sebagai penanggung jawab ruangan');
            }

            $query->whereHas('facility', function($q) use ($user){
                $q->where('room_id', $user->room_id);
            });

            return view('guru.bookings.requests', ['bookings'=>$query->get()]);
        }

        // Admin melihat semua
        return view('admin.bookings.requests',['bookings'=>$query->get()]);
    }


    /* ============================================================
       6. APPROVE (HANYA ADMIN & GURU PJ RUANGAN)
    ============================================================*/
    public function approve(Booking $booking)
    {
        if (Auth::user()->role === 'guru') {
            if (Auth::user()->room_id !== $booking->facility->room_id) {
                abort(403,"Anda bukan penanggung jawab ruangan ini");
            }
        }

        $booking->update([
            'status'=>'approved',
            'approved_by'=>Auth::id()
        ]);

        return back()->with('success','Booking disetujui');
    }


    /* ============================================================
       7. REJECT (HANYA ADMIN & GURU PJ RUANGAN)
    ============================================================*/
    public function reject(Booking $booking)
    {
        if (Auth::user()->role === 'guru') {
            if (Auth::user()->room_id !== $booking->facility->room_id) {
                abort(403,"Anda bukan penanggung jawab ruangan ini");
            }
        }

        $booking->update([
            'status'=>'rejected',
            'approved_by'=>Auth::id()
        ]);

        return back()->with('error','Booking ditolak');
    }


    /* ============================================================
       8. CHECKIN (Admin / PJ Ruangan)
    ============================================================*/
    public function checkIn(Booking $booking)
    {
        if (Auth::user()->role === 'guru' && Auth::user()->room_id !== $booking->facility->room_id) {
            abort(403,'Tidak berwenang check-in booking ini');
        }

        $booking->update([
            'status'=>'active',
            'checked_in'=>true,
            'check_in_time'=>now()
        ]);

        return back()->with('success','Check-in berhasil');
    }


    /* ============================================================
       9. COMPLETE / SELESAI
    ============================================================*/
    public function complete(Booking $booking)
    {
        if (Auth::user()->role === 'guru' && Auth::user()->room_id !== $booking->facility->room_id) {
            abort(403,'Tidak memiliki akses menyelesaikan booking ini');
        }

        $booking->update([
            'status'=>'completed',
            'checked_out'=>true,
            'check_out_time'=>now()
        ]);

        return back()->with('success','Booking selesai');
    }


    /* ============================================================
       10. HISTORY ADMIN (FULL DATA)
    ============================================================*/
    public function history()
    {
        return view('admin.bookings.history',[
            'bookings'=>Booking::with(['user','facility'])->latest()->get()
        ]);
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
}
