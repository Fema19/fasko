<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /* ============================================================
       1. USER / GURU / SISWA - LIST BOOKING DIRI SENDIRI
    ============================================================*/
    public function index()
    {
        $bookings = Booking::with(['facility','user'])
            ->where('user_id', Auth::id())
            ->where(function($q){
                $q->whereNull('end_time')
                  ->orWhere('end_time', '>=', now()->subDays(3));
            })
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
       5. HALAMAN REQUEST ADMIN & GURU PJ RUANGAN
    ============================================================*/
    public function requests(Request $request)
    {
        $user = Auth::user();

        // Auto-cancel approved bookings that missed the 5-minute check-in window
        $this->cancelLateCheckins();

        $query = Booking::with(['user','facility'])
            ->where('status','pending');

        $checklist = Booking::with(['user','facility'])
            ->whereIn('status',['approved','active'])
            ->latest();

        $historyQuery = Booking::with(['user','facility'])
            ->where('status','!=','pending')
            ->latest();

        // FILTER BY DATE
        if ($request->date) {
            $query->whereDate('start_time', $request->date);
            $historyQuery->whereDate('start_time', $request->date);
            $checklist->whereDate('start_time', $request->date);
        }

        // Guru hanya melihat request fasilitas dari ruangan yang ia tangani
        if ($user->role === 'guru') {
            if (! $user->room_id) {
                abort(403,'Anda tidak terdaftar sebagai penanggung jawab ruangan');
            }

            $query->whereHas('facility', function($q) use ($user){
                $q->where('room_id', $user->room_id);
            });

            $historyQuery->whereHas('facility', function($q) use ($user){
                $q->where('room_id', $user->room_id);
            });

            $checklist->whereHas('facility', function($q) use ($user){
                $q->where('room_id', $user->room_id);
            });

            return view('guru.bookings.requests', [
                'bookings'=>$query->get(),
                'history'=>$historyQuery->limit(10)->get(),
                'checklist'=>$checklist->get(),
            ]);
        }

        // Admin melihat semua
        return view('admin.bookings.requests',[
            'bookings'=>$query->get(),
            'history'=>$historyQuery->limit(10)->get(),
            'checklist'=>$checklist->get(),
        ]);
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

        // Cek dulu stok logis pada rentang waktu booking ini
        $remaining = $this->remainingStock($booking->facility, $booking, $booking->id);
        if ($remaining < $booking->capacity_used) {
            return back()->with('error', 'Stok fasilitas habis pada rentang waktu tersebut');
        }

        try {
            DB::transaction(function () use ($booking) {
                $facility = Facility::lockForUpdate()->find($booking->facility_id);

                // Validasi ulang di dalam transaction untuk menghindari race condition
                $remainingLocked = $this->remainingStock($facility, $booking, $booking->id);
                if ($remainingLocked < $booking->capacity_used) {
                    throw new \RuntimeException('Stok fasilitas habis pada rentang waktu tersebut');
                }

                // Setujui booking
                $booking->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id()
                ]);

                // Jika setelah disetujui stok habis, batalkan semua booking lain yang bentrok
                $remainingAfter = $this->remainingStock($facility, $booking);
                if ($remainingAfter <= 0) {
                    $this->cancelPendingConflicts($facility, $booking);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

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
       9. COMPLETE / SELESAI
    ============================================================*/
    public function complete(Booking $booking)
    {
        if (Auth::user()->role === 'guru' && Auth::user()->room_id !== $booking->facility->room_id) {
            abort(403,'Tidak memiliki akses menyelesaikan booking ini');
        }

        $now = now();
        $end = $booking->end_time;

        if ($now->lt($end)) {
            return back()->with('error','Check-out hanya dapat dilakukan setelah jadwal selesai.');
        }

        $booking->update([
            'status'=>'completed',
            'checked_out'=>true,
            'check_out_time'=>now()
        ]);

        if ($now->gt($end->copy()->addMinutes(15))) {
            return back()->with('error','Terlambat check-out lebih dari 15 menit. Sanksi dapat dikenakan.');
        }

        return back()->with('success','Booking selesai dan check-out berhasil');
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

    /**
     * Hitung stok tersisa pada rentang waktu booking tertentu.
     */
    private function remainingStock(Facility $facility, Booking $booking, $excludeBookingId = null): int
    {
        $maxStock = $facility->max_availability;

        $used = Booking::where('facility_id', $facility->id)
            ->whereIn('status', ['approved','active'])
            ->when($excludeBookingId, fn($q) => $q->where('id','!=',$excludeBookingId))
            ->where(function($q) use ($booking){
                $q->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                  ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                  ->orWhere(function($q) use ($booking){
                      $q->where('start_time','<=',$booking->start_time)
                        ->where('end_time','>=',$booking->end_time);
                  });
            })
            ->sum('capacity_used');

        return $maxStock - $used;
    }

    /**
     * Batalkan booking pending lain yang bentrok di waktu yang sama ketika stok habis.
     */
    private function cancelPendingConflicts(Facility $facility, Booking $approvedBooking): void
    {
        Booking::where('facility_id', $facility->id)
            ->where('status', 'pending')
            ->where('id','!=',$approvedBooking->id)
            ->where(function($q) use ($approvedBooking){
                $q->whereBetween('start_time', [$approvedBooking->start_time, $approvedBooking->end_time])
                  ->orWhereBetween('end_time', [$approvedBooking->start_time, $approvedBooking->end_time])
                  ->orWhere(function($q) use ($approvedBooking){
                      $q->where('start_time','<=',$approvedBooking->start_time)
                        ->where('end_time','>=',$approvedBooking->end_time);
                  });
            })
            ->update(['status' => 'cancelled']);
    }

    /**
     * Batalkan otomatis booking approved yang tidak check-in dalam 5 menit (window 30-25 menit sebelum start).
     */
    private function cancelLateCheckins(): void
    {
        $now = now();
        Booking::where('status','approved')
            ->where('checked_in', false)
            ->get()
            ->each(function($booking) use ($now) {
                $windowStart = $booking->start_time->copy()->subMinutes(30)->startOfMinute();
                $windowEnd = $windowStart->copy()->addMinutes(5)->subSecond();
                if ($now->gt($windowEnd)) {
                    $booking->update(['status' => 'cancelled']);
                }
            });
    }

    /* ============================================================
       12. RESET HISTORY (ADMIN / GURU PJ)
    ============================================================*/
    public function resetHistory()
    {
        $user = Auth::user();

        // Hapus riwayat non-pending yang sudah lewat (end_time <= now)
        $query = Booking::where('status','!=','pending')
            ->whereNotNull('end_time')
            ->where('end_time','<=', now());

        if ($user->role === 'guru') {
            if (! $user->room_id) abort(403,'Anda tidak terdaftar sebagai penanggung jawab ruangan');

            $query->whereHas('facility', function($q) use ($user){
                $q->where('room_id', $user->room_id);
            });
        } elseif ($user->role !== 'admin') {
            abort(403,'Tidak boleh reset history');
        }

        $deleted = $query->delete();

        return back()->with('success', "History dibersihkan ($deleted entri)");
    }
}
