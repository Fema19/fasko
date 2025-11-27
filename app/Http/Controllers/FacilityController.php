<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::with('category')->latest()->get();
        return view('facilities.index', compact('facilities'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('facilities.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak,perawatan,hilang',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('facility_photos', 'public');
        }

        Facility::create($data);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function edit(Facility $facility)
    {
        $categories = Category::all();
        return view('facilities.edit', compact('facility', 'categories'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak,perawatan,hilang',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('facility_photos', 'public');
        }

        $facility->update($data);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return back()->with('success', 'Fasilitas berhasil dihapus');
    }
}
