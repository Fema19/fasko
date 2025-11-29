<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RepairReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\RepairReport;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Simple auth (manual)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'guru' => redirect('/guru/dashboard'),
            'siswa' => redirect('/siswa/dashboard'),
            default => redirect('/')
        };
    }

    return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
})->name('login.attempt');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

// Public resources (messages) kept as-is but will resolve to role namespaces when possible
Route::resource('messages', MessageController::class);

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalRooms = Room::count();
        $totalFacilities = Facility::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $reports = RepairReport::where('status', 'pending')->count();

        return view('admin.dashboard', compact('totalRooms', 'totalFacilities', 'pendingBookings', 'reports'));
    })->name('dashboard');

    Route::resource('rooms', RoomController::class);
    Route::resource('facilities', FacilityController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('repair-reports', RepairReportController::class);
    Route::resource('users', UserController::class);
});

// Guru routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    /**
     * @return \Illuminate\View\View
     */
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $room = $user instanceof User ? $user->rooms()->with('facilities')->first() : null;

        return view('guru.dashboard', compact('room'));
    })->name('dashboard');

    // Guru manages facilities in their room: reuse FacilityController but views will resolve to guru.*
    Route::resource('facilities', FacilityController::class);
    // Guru can see bookings (for other rooms) and create bookings
    Route::resource('booking', BookingController::class);
    // Reports
    Route::resource('reports', RepairReportController::class)->parameters(['reports' => 'report']);
});

// Siswa routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    /**
     * @return \Illuminate\View\View
     */
    Route::get('/dashboard', function () {
        $userId = Auth::id() ?? 0;
        $totalBookings = Booking::where('user_id', $userId)->count();
        $approved = Booking::where('user_id', $userId)->where('status', 'approved')->count();
        $pending = Booking::where('user_id', $userId)->where('status', 'pending')->count();

        return view('siswa.dashboard', compact('totalBookings', 'approved', 'pending'));
    })->name('dashboard');

    Route::resource('booking', BookingController::class);
    Route::resource('report', RepairReportController::class);
});
