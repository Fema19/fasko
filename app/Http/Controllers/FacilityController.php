<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    /* ==========================
       INDEX — tampil berdasarkan role
    ==========================*/
    public function index()
    {
        $user = Auth::user();

        $facilities = Facility::with(['category','room'])->latest()->get();

        if ($user->role === 'admin') {
            return view('admin.facilities.index', compact('facilities'));
        }

        if ($user->role === 'guru') {
            if ($user->room_id) {
                $facilities = $facilities->where('room_id', $user->room_id);
                return view('guru.facilities.index', compact('facilities'));
            }
            abort(403,"Anda tidak memiliki izin mengelola fasilitas");
        }

        // siswa melihat daftar fasilitas (read-only)
        return view('siswa.facilities.index', ['facilities'=>$facilities]);
    }


    /* ==========================
       SHOW (boleh admin & guru PJ)
    ==========================*/
    public function show(Facility $facility)
    {
        if (Auth::user()->role==='guru' && Auth::user()->room_id !== $facility->room_id) {
            abort(403,'Tidak boleh melihat fasilitas ruangan lain');
        }

        return view(
            Auth::user()->role==='admin' ? 'admin.facilities.show' : 'guru.facilities.show',
            compact('facility')
        );
    }


    /* ==========================
       CREATE — hanya admin & guru PJ
    ==========================*/
    public function create()
    {
        $user = Auth::user();

        if ($user->role==='guru' && !$user->room_id) abort(403,'Tidak punya ruangan');

        return view(
            $user->role==='admin' ? 'admin.facilities.create' : 'guru.facilities.create',
            [
                'categories'=>Category::all(),
                'rooms'=>$user->role==='admin'
                    ? Room::all()
                    : Room::where('id',$user->room_id)->get()
            ]
        );
    }


    /* ==========================
       STORE
    ==========================*/
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'category_id'=>'required|exists:categories,id',
            'room_id'=>'nullable|exists:rooms,id',
            'name'=>'required|max:100',
            'condition'=>'required|in:baik,rusak,perawatan,hilang',
            'description'=>'nullable|string',
            'photo'=>'nullable|image|max:2048',
            'capacity'=>'nullable|integer|min:1',
            'unit'=>'nullable|integer|min:0',
        ]);

        // Guru hanya boleh tambah fasilitas pada ruangannya sendiri
        if ($user->role==='guru') {
            if (! $user->room_id) abort(403,'Tidak punya ruangan');
            $validated['room_id'] = $user->room_id; // paksa sesuai ruangan guru
        }

        $category = Category::find($validated['category_id']);
        $categoryType = $category->type ?? 'unit';

        if ($categoryType === 'unit') {
            $validated['unit'] = $validated['unit'] ?? 1;
            // kolom capacity di DB tidak nullable, set minimal 1 sebagai placeholder
            $validated['capacity'] = $validated['capacity'] ?? 1;
        } else {
            $validated['capacity'] = $validated['capacity'] ?? 1;
            $validated['unit'] = null;
        }

        if ($request->hasFile('photo'))
            $validated['photo']=$request->file('photo')->store('facility_photos','public');

        Facility::create($validated);

        return redirect()->route(
            $user->role === 'admin' ? 'admin.facilities.index' : 'guru.facilities.index'
        )->with('success','Fasilitas berhasil ditambahkan');
    }


    /* ==========================
       EDIT
    ==========================*/
    public function edit(Facility $facility)
    {
        $user = Auth::user();

        if ($user->role==='guru' && $facility->room_id != $user->room_id)
            abort(403,'Tidak punya akses edit');

        return view(
            $user->role==='admin' ? 'admin.facilities.edit' : 'guru.facilities.edit',
            [
                'facility'=>$facility,
                'categories'=>Category::all(),
                'rooms'=>$user->role==='admin'
                    ? Room::all()
                    : Room::where('id',$user->room_id)->get()
            ]
        );
    }


    /* ==========================
       UPDATE
    ==========================*/
    public function update(Request $request, Facility $facility)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'category_id'=>'required|exists:categories,id',
            'room_id'=>'nullable|exists:rooms,id',
            'name'=>'required|max:100',
            'condition'=>'required|in:baik,rusak,perawatan,hilang',
            'description'=>'nullable|string',
            'photo'=>'nullable|image|max:2048',
            'capacity'=>'nullable|integer|min:1',
            'unit'=>'nullable|integer|min:0',
        ]);

        if ($user->role==='guru') {
            if ($facility->room_id != $user->room_id) 
                abort(403,'Tidak boleh mengubah fasilitas ini');
            $validated['room_id'] = $user->room_id;
        }

        $category = Category::find($validated['category_id']);
        $categoryType = $category->type ?? 'unit';

        if ($categoryType === 'unit') {
            $validated['unit'] = $validated['unit'] ?? 1;
            // kolom capacity tidak nullable, isi minimal 1
            $validated['capacity'] = $validated['capacity'] ?? 1;
        } else {
            $validated['capacity'] = $validated['capacity'] ?? 1;
            $validated['unit'] = null;
        }

        if ($request->hasFile('photo'))
            $validated['photo']=$request->file('photo')->store('facility_photos','public');

        $facility->update($validated);

        return redirect()->route(
            $user->role === 'admin' ? 'admin.facilities.index' : 'guru.facilities.index'
        )->with('success','Fasilitas berhasil diperbarui');
    }


    /* ==========================
       DELETE
    ==========================*/
    public function destroy(Facility $facility)
    {
        if (Auth::user()->role==='guru' && $facility->room_id != Auth::user()->room_id)
            abort(403,'Tidak punya izin menghapus');

        $facility->delete();

        return back()->with('success','Fasilitas dihapus');
    }
}
