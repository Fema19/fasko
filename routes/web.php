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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =============================
// WELCOME
// =============================
Route::get('/', fn() => view('welcome'));


// =============================
// LOGIN
// =============================
Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru'  => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect('/')
        };
    }

    return back()->withErrors([
        'email' => 'Email atau password salah'
    ])->onlyInput('email');

})->name('login.attempt');


// =============================
// LOGOUT GLOBAL (semua role)
// =============================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('success', 'Berhasil logout');
})->name('logout');


// =============================
// PUBLIC RESOURCES
// =============================
Route::resource('messages', MessageController::class);


// =============================
// ADMIN ROUTES
// =============================
// =============================
// ADMIN ROUTES
// =============================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Anda berhasil logout sebagai Admin');
        })->name('logout');

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('rooms', RoomController::class);
        Route::resource('facilities', FacilityController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('bookings', BookingController::class);
        Route::resource('repair-reports', RepairReportController::class);
        Route::resource('users', UserController::class);
    });



// =============================
// GURU ROUTES
// =============================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Anda berhasil logout sebagai Guru');
        })->name('logout');

        Route::get('/dashboard', function () {
            $user = Auth::user();
            $room = $user->room?->load('facilities');
            return view('guru.dashboard', compact('room'));
        })->name('dashboard');

        Route::resource('facilities', FacilityController::class);
        Route::resource('booking', BookingController::class);
        Route::resource('reports', RepairReportController::class);
    });


// =============================
// SISWA ROUTES
// =============================
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Anda berhasil logout sebagai Siswa');
        })->name('logout');

        Route::get('/dashboard', function () {
            $userId = Auth::id();
            return view('siswa.dashboard', [
                'totalBookings' => Booking::where('user_id', $userId)->count(),
                'approved'      => Booking::where('user_id', $userId)->where('status', 'approved')->count(),
                'pending'       => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            ]);
        })->name('dashboard');

        Route::resource('booking', BookingController::class);
        Route::resource('report', RepairReportController::class);
    });
