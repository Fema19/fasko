<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Concerns\HandlesBookings;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\Message;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    use HandlesBookings;

    public function requests()
    {
        $this->ensureRoomOwner();
        $this->cancelLateCheckins();

        $roomId = Auth::user()->room_id;

        $bookings = Booking::with(['user','facility'])
            ->where('status','pending')
            ->whereHas('facility', fn($q) => $q->where('room_id', $roomId))
            ->get();

        $checklist = Booking::with(['user','facility'])
            ->whereIn('status',['approved','active'])
            ->whereHas('facility', fn($q) => $q->where('room_id', $roomId))
            ->latest()
            ->get();

        return view('guru.bookings.requests', compact('bookings','checklist'));
    }

    public function approve(Booking $booking)
    {
        $this->ensureRoomOwner();

        if (Auth::user()->room_id !== $booking->facility->room_id) {
            abort(403,"Anda bukan penanggung jawab ruangan ini");
        }

        $remaining = $this->remainingStock($booking->facility, $booking, $booking->id);
        if ($remaining < $booking->capacity_used) {
            return back()->with('error', 'Stok fasilitas habis pada rentang waktu tersebut');
        }

        try {
            DB::transaction(function () use ($booking) {
                $facility = Facility::lockForUpdate()->find($booking->facility_id);

                $remainingLocked = $this->remainingStock($facility, $booking, $booking->id);
                if ($remainingLocked < $booking->capacity_used) {
                    throw new \RuntimeException('Stok fasilitas habis pada rentang waktu tersebut');
                }

                $booking->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id()
                ]);

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

    public function reject(Request $request, Booking $booking)
    {
        $this->ensureRoomOwner();

        if (Auth::user()->room_id !== $booking->facility->room_id) {
            abort(403,"Anda bukan penanggung jawab ruangan ini");
        }

        $data = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $booking->update([
            'status'=>'rejected',
            'approved_by'=>Auth::id()
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $booking->user_id,
            'message'     => sprintf(
                'Pengajuan booking %s (%s - %s) ditolak oleh %s: %s',
                $booking->facility->name,
                $booking->start_time->format('d M Y H:i'),
                $booking->end_time->format('d M Y H:i'),
                Auth::user()->name,
                $data['message']
            ),
        ]);

        return back()->with('error','Booking ditolak');
    }

    public function complete(Booking $booking)
    {
        $this->ensureRoomOwner();

        if (Auth::user()->room_id !== $booking->facility->room_id) {
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

    public function history(Request $request)
    {
        $this->ensureRoomOwner();
        $roomId = Auth::user()->room_id;

        $filters = $this->historyFilters($request);

        $query = Booking::with(['user','facility.room'])
            ->whereIn('status', ['approved','active','completed','cancelled','rejected'])
            ->latest('start_time')
            ->whereHas('facility', fn($q) => $q->where('room_id', $roomId));

        $this->applyHistoryFilters($query, $filters);

        $bookings = $query->paginate(15)->withQueryString();

        return view('guru.bookings.history', compact('bookings','filters'));
    }

    public function exportHistory(Request $request)
    {
        $this->ensureRoomOwner();
        $roomId = Auth::user()->room_id;

        $filters = $this->historyFilters($request);

        $query = Booking::with(['user','facility.room'])
            ->whereIn('status', ['approved','active','completed','cancelled','rejected'])
            ->latest('start_time')
            ->whereHas('facility', fn($q) => $q->where('room_id', $roomId));

        $this->applyHistoryFilters($query, $filters);

        $bookings = $query->get();

        $pdf = Pdf::loadView('bookings.history-pdf', [
            'bookings' => $bookings,
            'filters'  => $filters,
            'user'     => Auth::user(),
        ]);

        $filename = 'history-booking-'.now()->format('Ymd_His').'.pdf';

        return $pdf->download($filename);
    }

    public function resetHistory()
    {
        $this->ensureRoomOwner();
        $roomId = Auth::user()->room_id;

        $deleted = Booking::where('status','!=','pending')
            ->whereNotNull('end_time')
            ->where('end_time','<=', now())
            ->whereHas('facility', fn($q) => $q->where('room_id', $roomId))
            ->delete();

        return back()->with('success', "History dibersihkan ($deleted entri)");
    }

    private function ensureRoomOwner(): void
    {
        if (! Auth::user()?->room_id) {
            abort(403,'Anda tidak terdaftar sebagai penanggung jawab ruangan');
        }
    }
}
