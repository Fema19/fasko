<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\RepairReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RepairReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Auto-reset laporan lama untuk siswa / guru non-PJ
        $this->cleanupUserReports($user);

        $query = $this->buildScopedQuery($user);

        $reports = $query->latest()->get();

        return $this->view('repair_reports.index', compact('reports'));
    }

    public function create()
    {
        if (Auth::user()?->role === 'admin') {
            abort(403,'Admin hanya memantau laporan, bukan membuat.');
        }

        $facilities = Facility::all();

        return $this->view('repair_reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role === 'admin') {
            abort(403,'Admin tidak membuat laporan kerusakan.');
        }

        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('repair_reports', 'public');
        }

        RepairReport::create([
            'facility_id' => $request->facility_id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'photo' => $filePath,
        ]);

        $role = Auth::user()->role;
        $route = match($role) {
            'guru'  => 'guru.reports.index',
            'siswa' => 'siswa.reports.index',
            'admin' => 'admin.repair-reports.index',
            default => 'repair-reports.index'
        };

        return redirect()->route($route)->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    public function updateStatus(RepairReport $report, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,fixed',
        ]);

        $user = Auth::user();

        if ($user->role === 'guru') {
            if (! $user->room_id || $report->facility?->room_id !== $user->room_id) {
                abort(403,'Tidak boleh mengubah status laporan di luar ruangan Anda.');
            }
        } elseif ($user->role !== 'admin') {
            abort(403,'Tidak diizinkan mengubah status laporan.');
        }

        $report->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status laporan diperbarui.');
    }

    public function export()
    {
        $user = Auth::user();

        if ($user->role === 'guru' && ! $user->room_id) {
            abort(403,'Export hanya untuk admin atau guru penanggung jawab ruangan.');
        }

        $query = $this->buildScopedQuery($user);
        $reports = $query->latest()->get();

        $pdf = Pdf::loadView('repair_reports.pdf', [
            'reports' => $reports,
            'user' => $user,
        ]);

        return $pdf->download('laporan-kerusakan-'.now()->format('Ymd_His').'.pdf');
    }

    public function reset()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $deleted = RepairReport::query()->delete();
        } elseif ($user->role === 'guru' && $user->room_id) {
            $deleted = RepairReport::whereHas('facility', function($q) use ($user){
                $q->where('room_id', $user->room_id);
            })->delete();
        } else {
            abort(403,'Tidak boleh reset laporan.');
        }

        return back()->with('success', "Laporan direset ($deleted entri).");
    }

    private function buildScopedQuery($user)
    {
        $query = RepairReport::with(['facility.room', 'user']);

        if ($user->role === 'admin') {
            return $query;
        }

        if ($user->role === 'guru') {
            if ($user->room_id) {
                return $query->whereHas('facility', function($q) use ($user){
                    $q->where('room_id', $user->room_id);
                });
            }

            return $query->where('user_id', $user->id);
        }

        return $query->where('user_id', $user->id);
    }

    /**
     * Hapus laporan lebih dari 7 hari (siswa & guru non-PJ).
     */
    private function cleanupUserReports($user): void
    {
        if ($user->role === 'siswa' || ($user->role === 'guru' && ! $user->room_id)) {
            RepairReport::where('user_id', $user->id)
                ->where('created_at','<=', now()->subDays(7))
                ->delete();
        }
    }
}
