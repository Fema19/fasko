<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\RepairReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = RepairReport::with(['facility.room', 'user'])->latest();

        if ($user->role === 'admin') {
            // admin melihat semua
        } elseif ($user->role === 'guru') {
            // guru PJ melihat laporan di ruangannya; jika tidak punya ruangan, tampilkan laporan yang ia kirim
            if ($user->room_id) {
                $query->whereHas('facility', function($q) use ($user){
                    $q->where('room_id', $user->room_id);
                });
            } else {
                $query->where('user_id', $user->id);
            }
        } else {
            // siswa atau role lain hanya melihat laporan yang ia buat
            $query->where('user_id', $user->id);
        }

        $reports = $query->get();

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
}
