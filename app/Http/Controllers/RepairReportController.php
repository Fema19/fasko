<?php

namespace App\Http\Controllers;

use App\Models\RepairReport;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairReportController extends Controller
{
    public function index()
    {
        $reports = RepairReport::with(['facility', 'user'])->latest()->get();
        return view('repair_reports.index', compact('reports'));
    }

    public function create()
    {
        $facilities = Facility::all();
        return view('repair_reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
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

        return redirect()->route('repair-reports.index')->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    public function updateStatus(RepairReport $report, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,fixed',
        ]);

        $report->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status laporan diperbarui.');
    }
}
